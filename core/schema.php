<?

	namespace DB;

	class Schema {
		protected $schema = array(), $db, $hashtable;

		function __construct($db, $prefix = "core_") {
			$this->db = $db;
			$this->hashtable = $prefix."hashtable";

			// we do a hashtable for speed
			$qry = "CREATE TABLE IF NOT EXISTS `{$this->hashtable}`(
				`table_name` VARCHAR(100) PRIMARY KEY NOT NULL
				, `change_hash` VARCHAR(100) NOT NULL
				, UNIQUE(table_name)
			) ";
			$rs = $this->db->exec($qry);
		}

		private function hash($array) {
			return md5(serialize($array));
		}

		function add($table, $fields, $options = array()) {
			$this->schema[$table] = $fields;
		}

		private function column_sql($name, $info) {
			$qry = "`{$name}` {$info['type']}";

			// options
			if ($info["default"]) {
				$qry .= " DEFAULT '{$info['default']}'";
			}
			if ($info["unique"]) {
				$qry .= " UNIQUE";
			}
			if ($info["index"]) {
				$qry .= ", INDEX({$name})";
			}

			return $qry;
		}

		function setup() {
			$hashes = $this->db->exec("SELECT * FROM {$this->hashtable}");
			$all = array();
			foreach ($hashes as $t) {
				$all[$t["table_name"]] = $t["change_hash"];
			}
			$hashes = $all;

			// Loop through schemas and update those which need it
			foreach ($this->schema as $table => $fields) {
				$change_hash = $this->hash($fields);

				if (! isset($hashes[$table])) {
					// create new table
					$qry = "CREATE TABLE IF NOT EXISTS `{$table}` (id INT(7) PRIMARY KEY NOT NULL AUTO_INCREMENT, ";
					$index = array();

					// loop and add custom fields
					foreach ($fields as $name => $info) {
						$qry .= $this->column_sql($name, $info);
						$qry .= ", ";
					}

					// always have these fields
					$qry .= "`created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, ";
					$qry .= "`modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, ";
					$qry .= "UNIQUE KEY id (id))";

					// debug($qry);

					$this->db->exec($qry);

					// hash table
					$qry = "INSERT INTO {$this->hashtable} (table_name, change_hash) 
					SELECT '{$table}', '{$change_hash}' FROM DUAL
					WHERE NOT EXISTS (
						SELECT table_name FROM {$this->hashtable} WHERE table_name = '{$table}'
					) LIMIT 1";

					// debug($qry);

					$this->db->exec($qry);

				} elseif ($hashes[$table] !== $change_hash) {
					// update an existing table
					$columns = $this->db->exec("SHOW COLUMNS FROM {$table} ");
					$rekey = array();
					foreach ($columns as $t) {
						$rekey[$t['Field']] = $t;
					}
					$columns = $rekey;

					// Unset these so we don't drop or update them
					unset($columns['id']);
					unset($columns['created']);
					unset($columns['modified']);

					foreach ($fields as $name => $info) {
						$col = $this->column_sql($name, $info);
						if (! isset($columns[$name])) {
							$qry = "ALTER TABLE `{$table}` ADD {$col}";
							// debug($qry);
							$this->db->exec($qry);
							// debug($qry);
						} else {
							$db_type = strtolower($columns['Name']['Type']);
							$qry = "ALTER TABLE `{$table}` MODIFY COLUMN {$col}";
							$this->db->exec($qry);
							// debug($qry);
						}
						
						// field has been refound
						unset($columns[$name]);
					}

					// anything left over should be dropped as a column
					foreach ($columns as $name => $values) {
						$qry = "ALTER TABLE `{$table}` drop column `{$name}` ";
						$this->db->exec($qry);
						// debug($qry);
					}

					// now update change hash, so that we only hit this function when we've updated the $schema
					$qry = "UPDATE {$this->hashtable} SET change_hash = '{$change_hash}' WHERE table_name = '{$table}' ";
					$this->db->exec($qry);
					// debug($qry);
				}
			}
		}
	}




?>