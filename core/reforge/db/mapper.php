<?php

namespace RF;

class Schema extends \Prefab {
	function __construct() {}

	function update($table, $fields) {
		global $db;

		// ensure the table exists
		$db->exec("CREATE TABLE IF NOT EXISTS `$table` (id INT(7) PRIMARY KEY NOT null AUTO_INCREMENT)");

		// track indexes
		$indexes = $db->exec("SHOW INDEXES FROM `$table`");
		$indexes = rekey_array("Key_name", $indexes);
		$maintain_indexes = array("PRIMARY" => "INDEX");

		// track columns
		$columns = $db->exec("SHOW COLUMNS FROM `$table`");
		$columns = rekey_array("Field", $columns);

		// ignore changes to id field
		unset($columns['id']);
		// ignore changes to created field
		if (! isset($fields["created"])) {
			$fields["created"] = array(
				"type" => "DATETIME",
				"attrs" => "NOT null DEFAULT CURRENT_TIMESTAMP",
			);
		}
		if (! isset($fields["modified"])) {
			$fields["modified"] = array(
				"type" => "DATETIME",
				"attrs" => "NOT null DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
			);
		}

		// Now loop through the fields and do properties
		$last = "id";
		foreach ($fields as $key => $props) {
			if (! $props) { continue; }
			// track indexes/uniques
			if ($props['index'] == true) {
				$maintain_indexes[$key] = "INDEX";
			} elseif ($props['unique'] == true) {
				$maintain_indexes[$key] = "UNIQUE";
			}

			// build query by array
			$qry = array();
			$qry[] = "ALTER TABLE `{$table}`";
			
			// check if we need to add or modify
			if (! isset($columns[$key])) {
				$qry[] = "ADD COLUMN";
			} else {
				$qry[] = "MODIFY COLUMN";
			}

			// now add in key and properties
			$qry[] = "`{$key}`";
			$qry[] = $props['type'];
			$qry[] = $props['attrs'];
			$qry[] = "AFTER `$last`";

			// now join the query string
			$qry = implode(' ', array_filter($qry));
			// debug($qry);
			$db->exec($qry);

			// track last, unset insured column
			$last = $key;
			unset($columns[$key]);
		}

		// for any fields that weren't found again in the update, drop them
		foreach ($columns as $key => $props) {
			$qry = "ALTER TABLE `{$table}` DROP COLUMN `{$key}` ";
			$db->exec($qry);
		}

		// Do the same for indexes
		foreach ($maintain_indexes as $key => $type) {
			if (isset($indexes[$key])) {
				unset($indexes[$key]);
			} else {
				$qry = "ALTER TABLE `{$table}` ADD {$type} (`{$key}`)";
				$db->exec($qry);
			}
		}

		// Removed uninsured indexes
		foreach ($indexes as $key => $index) {
			$db->exec("ALTER TABLE `$table` DROP INDEX $key");
		}

		\Alerts::instance()->success($table." updated");
	}
}

// $rf_mapper_cache = array();

class Mapper extends \Magic {
	// variables
	protected 
		$data,
		$changed = array();
	public 
		$cache = array(),
		$schema = array(),
		$table;

	// construct mapper with provided information
	function __construct($table, $schema = false) {
		if (! $table) { echo "no table"; return false; }
		$this->table = $table;

		$this->cache['queries'] = new \RF\Cache("{$table}.queries");
		$this->cache['schema'] = new \RF\Cache("{$table}.schema");

		// Build schema out, and store for reference
		if ($schema !== false && count($schema) > 0) {
			$this->ensure_schema($schema);
		}
	}

	/**
	 * Validates and sends the schema off to the schema class for building
	 */
	function ensure_schema($schema) {
		$cache = $this->cache['schema'];

		// first, store schema information in our object
		foreach ($schema as $col => $props) {
			$this->schema[$col] = $props['type'];
		}
		if ($this->schema["created"] !== false) {
			$this->schema["created"] = "DATETIME";
		}
		if ($this->schema["modified"] !== false) {
			$this->schema["modified"] = "DATETIME";
		}

		// Now, determine if this needs to be updated
		$field_hash = md5(serialize($schema));
		$field_cache = $cache->get($this->table);

		// doesn't have a cache or doesn't match
		if (! $field_cache || $field_cache != $field_hash) {
			// save hash in cache
			$cache->set($this->table, $field_hash);
			// send to schema class for update
			\RF\Schema::instance()->update($this->table, $schema);
		}
	}

	/**
	 * Load array or database query into this object
	 */
	function factory($object) {
		if (! $object) { return; }
		foreach ($object as $key => $info) {
			$this->data[$key] = $object[$key];
			if (! $this->schema[$key]) {
				$this->schema[$key] = "inherited";
			}
		}

		if (isset($object['id'])) {
			$this->data['id'] = $object['id'];
		}
		if (isset($object['created'])) {
			$this->data['created'] = $object['created'];
			$this->schema["created"] = "DATETIME";
		}
		if (isset($object['modified'])) {
			$this->data['modified'] = $object['modified'];
			$this->schema["modified"] = "DATETIME";
		}
	}

	// Magic Functions
	function exists($key) {
		return array_key_exists($key, $this->data);
	}

	function set($key, $val) {
		// debug($this->schema);
		// debug($key);
		// debug($val);
		if ($key != "id" && $val !== $this->{$key}) {
			$this->changed[$key] = true;
		}
		$this->data[$key] = $val;	
	}

	function &get($key) {
		return $this->data[$key];
	}

	function clear($key) {
		unset($this->data[$key]);
		unset($this->changed[$key]);
	}

	function erase() {
		// unset($this->data[$key]);


		// $args=[];
		// $ctr=0;
		// $filter='';
		// $pkeys=[];
		// foreach ($this->fields as $key=>&$field) {
		// 	if ($field['pkey']) {
		// 		$filter.=($filter?' AND ':'').$this->db->quotekey($key).'=?';
		// 		$args[$ctr+1]=[$field['previous'],$field['pdo_type']];
		// 		$pkeys[$key]=$field['previous'];
		// 		$ctr++;
		// 	}
		// 	$field['value']=null;
		// 	$field['changed']=(bool)$field['default'];
		// 	if ($field['pkey'])
		// 		$field['previous']=null;
		// 	unset($field);
		// }
		// if (!$filter)
		// 	user_error(sprintf(self::E_PKey,$this->source),E_USER_ERROR);
		// foreach ($this->adhoc as &$field) {
		// 	$field['value']=null;
		// 	unset($field);
		// }
		// parent::erase();
		// if (isset($this->trigger['beforeerase']) &&
		// 	\Base::instance()->call($this->trigger['beforeerase'],
		// 		[$this,$pkeys])===false)
		// 	return 0;
		// $out=$this->db->
		// 	exec('DELETE FROM '.$this->table.' WHERE '.$filter.';',$args);
		// if (isset($this->trigger['aftererase']))
		// 	\Base::instance()->call($this->trigger['aftererase'],
		// 		[$this,$pkeys]);
		// return $out;
	}

	private function wipe_query_cache() {
		debug("wiped cache for {$this->table}");
		$this->cache['queries']->reset();
	}

	/**
	 * Updated exsiting record in the database
	 */
	function update() {
		if (count($this->changed) == 0) { return; }

		$qry = array();
		$params = array();
		foreach ($this->changed as $col => $v) {
			$params[":$col"] = $this->{$col};
			$qry[] = "`$col` = :$col";
		}
		// reset this back
		$this->changed = array();
		
		$qry = implode(", ", array_filter($qry));
		$qry = "UPDATE `{$this->table}` SET ".$qry;
		$qry .= " WHERE id = {$this->id}";

		$this->wipe_query_cache();
		return $this->query($qry, $params);
	}

	/**
	 * Inserts record into the database
	 */
	function insert() {
		global $db;

		$cols = array();
		$vals = array();
		$params = array();
		foreach ($this->changed as $col => $v) {
			$cols[] = "`$col`";
			$vals[] = ":$col";
			$params[":$col"] = $this->{$col};
		}

		$cols = implode(", ", array_filter($cols));
		$vals = implode(", ", array_filter($vals));

		$qry = "INSERT INTO `{$this->table}` ($cols) VALUES ($vals)";


		$this->wipe_query_cache();
		$this->query($qry, $params);
		$this->id = $db->lastinsertid();
		return $this->id;
	}

	/**
	 * Automatically maps to update or insert depending on if we've loaded any data
	 */
	function save() {
		$this->wipe_query_cache();
		if ($this->id && $this->id > 0) {
			$this->update();
		} else {
			$this->insert();
		}
	}

	/**
	 * Allows for open database queries with a cached backend
	 */
	function query($cmds, $args = null, $ttl = 0, $log = true, $stamp = false) {
		global $db;
		$op = reset(explode(" ", trim($cmds)));
		$cache = $this->cache['queries'];
		$sql_key = md5(serialize(array(
			$cmds, $args
		)));

		// if we're making changes to our table, then wipe our query cache
		if ($op == "INSERT" || $op == "DELETE" || $op == "UPDATE") {
			$this->wipe_query_cache();
		} else {
			if ($cache->get($sql_key)) {
				return $cache->get($sql_key);
			}
		}


		// query and cache
		// debug($cmds, $args);
		$rs = $db->exec($cmds, $args, 0, $log, $stamp);
		$cache->set($sql_key, $rs);

		return $rs;
	}

	/**
	 * Builds a select query out of provided paramters
	 */
	function find($filter = null, array $options = null) {
		$options = $options ? $options : [];
		$options += [
			'group by' => null,
			'order by' => null,
			'limit' => 0,
			'offset' => 0
		];

		$qry = array();
		$params = array();
		$qry[] = "SELECT * FROM `{$this->table}`";
		if ($filter !== null) {
			if (gettype($filter) == "array") {
				$params = $filter;
				$filter = array_shift($params);
			}
			$qry[] = "WHERE $filter";
		}
		foreach ($options as $key => $opt) {
			if ($opt !== null && $opt !== 0) {
				$qry[] = strtoupper($key);
				$qry[] = $opt;
			}
		}


		// debug($qry, $params);
		$qry = implode(" ", array_filter($qry));

		// debug($qry, $params);
		$rs = $this->query($qry, $params);

		return $rs;
	}

	/**
	 * Load data about this object, then attach it to $this
	 */
	function load($filter = null, array $options = null, $ttl = 0) {
		$cache = $this->cache['queries'];
		$sql_key = md5(serialize(array(
			$filter, $options
		)));
		if ($cache->get($sql_key)) {
			$rs = $cache->get($sql_key);
		}

		if (! $rs) {
			$rs = $this->find($filter, $options);
			if (count($rs) > 1) {
				echo "error";
				return;
			}

			$rs = reset($rs);
			$cache->set($sql_key, $rs);
		}


		// attach data to this object
		$this->factory($rs);

		return $this;
	}
}

