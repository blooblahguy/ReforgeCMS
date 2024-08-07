<?php

namespace RF;

$schema_checked = array();
class Mapper extends \Magic {
	// variables
	protected
	$data = array(),
	$changed = array(),
	$in_factory_mode = false;
	public
	$cache = array(),
	$schema = array(),
	$table;

	// construct mapper with provided information
	function __construct( $table, $schema = array(), $cache_key = false ) {
		global $schema_checked;

		if ( ! $table ) {
			echo "no table";
			return;
		}

		$this->table = $table;
		$cache_key = $cache_key ? $cache_key : $table;

		$this->cache['queries'] = new \RF\Cache( "{$cache_key}.queries" );
		$this->cache['schema'] = new \RF\Cache( "{$cache_key}.schema" );

		// $this->cache['queries']->reset();
		// $this->cache['schema']->reset();

		// Build schema out, and store for reference
		if ( ! isset( $schema_checked[ $table ] ) && $schema !== false && count( $schema ) > 0 ) {
			$this->ensure_schema( $schema );
		}
	}

	/**
	 * Validates and sends the schema off to the schema class for building
	 */
	function ensure_schema( $schema ) {
		global $schema_checked;
		$forceupdate = false;

		$cache = $this->cache['schema'];
		// Now, determine if this needs to be updated
		$field_hash = md5( serialize( $schema ) );
		$field_cache = $cache->get( $this->table );

		// doesn't have a cache or doesn't match
		if ( $forceupdate || ! $field_cache || $field_cache != $field_hash ) {

			// first, store schema information in our object
			foreach ( $schema as $col => $props ) {
				if ( $props['type'] ?? null ) {
					// debug( $col, $props );
					$this->schema[ $col ] = $props['type'];
				}
				// if ()
			}
			if ( isset( $this->schema["created"] ) && $this->schema["created"] !== false ) {
				$this->schema["created"] = "DATETIME";
			}
			if ( isset( $this->schema["modified"] ) && $this->schema["modified"] !== false ) {
				$this->schema["modified"] = "DATETIME";
			}

			// save hash in cache
			$cache->set( $this->table, $field_hash );
			// send to schema class for update
			\RF\Schema::instance()->update( $this->table, $schema );
		}

		$schema_checked[ $this->table ] = 1;
	}

	/**
	 * Load array or database query into this object
	 */
	function factory( $object ) {
		if ( ! $object ) {
			return;
		}

		$this->in_factory_mode = true;

		// debug( $object )

		foreach ( $object as $key => $info ) {
			// debug( $key, $info );
			$this->{$key} = $object[ $key ];
			// if (! $this->schema[$key]) {
			// 	$this->schema[$key] = "inherited";
			// }
		}

		// debug( $this );

		if ( isset( $object['created'] ) ) {
			$this->created = $object['created'];
			// $this->schema["created"] = "DATETIME";
		}
		if ( isset( $object['modified'] ) ) {
			$this->modified = $object['modified'];
			// $this->schema["modified"] = "DATETIME";
		}

		$this->in_factory_mode = false;
	}

	// Magic Functions
	function exists( $key ) {
		return array_key_exists( $key, $this->data );
	}

	function set( $key, $val ) {
		// set but don't update or clear cache
		if ( $this->in_factory_mode ) {
			$this->data[ $key ] = $val;
		} else {

			if ( ! isset( $this->data[ $key ] ) || $val !== $this->data[ $key ] ) {
				$this->changed[ $key ] = true;
				$this->cache['queries']->clear( $key );
			}

			$this->data[ $key ] = $val;
		}

		// if ( $key == "id" ) {
		// 	return;
		// }

		// if ( ! isset( $this->data[ $key ] ) ) {
		// 	$this->data[ $key ] = $val;

		// 	if ( ! $this->in_factory_mode ) {
		// 		$this->changed[ $key ] = true;
		// 	}

		// } else if ( $val !== $this->data[ $key ] ) {
		// 	$this->data[ $key ] = $val;
		// 	$this->cache['queries']->clear( $key );
		// }


		// if ( array_key_exists( $key, $this->data ) && $val !== $this->data[ $key ] ) {
		// 	$this->changed[ $key ] = true;
		// }

		// if ( array_key_exists( $key, $this->data ) ) {
		// 	$this->data[ $key ] = $val;
		// 	$this->cache['queries']->clear( $key );
		// }

	}

	function &get( $key ) {
		return $this->data[ $key ];
	}

	function clear( $key ) {
		unset( $this->data[ $key ] );
		unset( $this->changed[ $key ] );
	}

	function erase() {
		if ( ! $this->id ) {
			return false;
		}

		$this->query( "DELETE FROM `{$this->table}` WHERE id = :id", array(
			":id" => $this->id
		) );

		$this->afterdelete();

		return true;
	}

	private function wipe_query_cache() {
		// debug("wiped cache for {$this->table}");
		$this->cache['queries']->reset();
	}

	/**
	 * Updated exsiting record in the database
	 */
	function update() {
		if ( count( $this->changed ) == 0 ) {
			return;
		}

		$qry = array();
		$params = array();
		foreach ( $this->changed as $col => $v ) {
			$params[ ":$col" ] = $this->{$col};
			$qry[] = "`$col` = :$col";
		}

		// debug($qry);
		// reset this back
		$this->changed = array();

		$qry = implode( ", ", array_filter( $qry ) );
		$qry = "UPDATE `{$this->table}` SET " . $qry;
		$qry .= " WHERE id = {$this->id}";

		$rs = $this->query( $qry, $params );
		$this->afterupdate();

		return $rs;
	}

	/**
	 * Inserts record into the database
	 */
	function insert() {
		global $db;

		$cols = array();
		$vals = array();
		$params = array();
		foreach ( $this->changed as $col => $v ) {
			$cols[] = "`$col`";
			$vals[] = ":$col";
			$params[ ":$col" ] = $this->{$col};
		}

		$cols = implode( ", ", array_filter( $cols ) );
		$vals = implode( ", ", array_filter( $vals ) );

		$qry = "INSERT INTO `{$this->table}` ($cols) VALUES ($vals)";

		$this->query( $qry, $params );
		$this->id = $db->lastinsertid();

		$this->afterinsert();

		return $this->id;
	}

	/**
	 * Automatically maps to update or insert depending on if we've loaded any data
	 */
	function save() {
		if ( $this->id && $this->id > 0 ) {
			return $this->update();
		} else {
			return $this->insert();
		}
	}

	/**
	 * Allows for open database queries with a cached backend
	 */
	function query( $cmds, $args = null, $ttl = 0, $log = true, $stamp = false ) {
		global $db;

		$reset = explode( " ", trim( $cmds ) );
		$op = reset( $reset );
		$cache = $this->cache['queries'];
		$sql_key = md5( serialize( array(
			$cmds, $args
		) ) );
		$value = null;

		// $cache = (object) [];

		// if we're making changes to our table, then wipe our query cache
		if ( $op == "INSERT" || $op == "DELETE" || $op == "UPDATE" ) {
			$this->wipe_query_cache();
		} elseif ( $cache->exists( $sql_key, $value ) ) {
			return $value; //$cache->get($sql_key);
		}

		// query and cache
		// debug($cmds, $args);
		$rs = $db->exec( $cmds, $args, 0, $log, $stamp );
		$cache->set( $sql_key, $rs );

		return $rs;
	}

	/**
	 * Builds a select query out of provided paramters
	 */
	function find( $fields, $filter = null, array $options = null ) {
		$options = $options ? $options : [];
		$options += [ 
			'group by' => null,
			'order by' => null,
			'limit' => 0,
			'offset' => 0
		];

		$qry = array();
		$params = array();
		$qry[] = "SELECT " . $fields . " FROM `{$this->table}`";
		if ( $filter !== null ) {
			if ( gettype( $filter ) == "array" ) {
				$params = $filter;
				$filter = array_shift( $params );
			}
			$qry[] = "WHERE $filter";
		}
		foreach ( $options as $key => $opt ) {
			if ( $opt !== null && $opt !== 0 ) {
				$qry[] = strtoupper( $key );
				$qry[] = $opt;
			}
		}

		$qry = implode( " ", array_filter( $qry ) );

		// debug($qry, $params);
		$rs = $this->query( $qry, $params );

		return $rs;
	}

	/**
	 * Load data about this object, then attach it to $this
	 */
	function load( $fields, $filter = null, array $options = null, $ttl = 0 ) {
		$rs = $this->find( $fields, $filter, $options );

		if ( count( $rs ) > 1 ) {
			echo "error";
			return;
		}

		$rs = reset( $rs );

		// attach data to this object
		$this->factory( $rs );

		$this->afterload();

		return $this;
	}
	function afterinsert() {
	}
	function afterupdate() {
	}
	function afterdelete() {
	}
	function afterload() {
	}
}

