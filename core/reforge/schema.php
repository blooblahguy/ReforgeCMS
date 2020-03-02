<?
	class RF_Schema extends \Prefab {
		protected $schemas = array(),
			$prefix = "rf_";

		function __construct() {
			// do nothing
		}
		
		// function 
		private function hash($array) {
			return md5(serialize($array));
		}

		function add($table, $fields, $options = array()) {
			$this->schemas[$table] = $fields;
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
			} else {

			}

			return $qry;
		}

		/**
		 * Use cache system instead of database table
		 */
		function setup() {
			global $db;
			$cache = \Cache::instance();

			$update = false;
			foreach ($this->schemas as $table => $fields) {
				$hash = $this->hash($table);
				$cached_hash = $cache->get($this->prefix.$table);

				if (! $cached_hash || $cached_hash != $hash) {
					$update = true;
					break;
				}
			}

			if (! $update) { return; }

			// Loop through schemas and update those which need it
			foreach ($this->schemas as $table => $fields) {
				$hash = $this->hash($fields);
				$cached_hash = $cache->get($this->prefix.$table);

				if (! $cached_hash) {
					// create new table
					$qry = "CREATE TABLE IF NOT EXISTS `{$table}` (id INT(7) PRIMARY KEY NOT NULL AUTO_INCREMENT, ";

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
					$db->exec($qry);

					// cach hash
					$cache->set($this->prefix.$table, $hash);
					add_alert("message", "Created $table");

				} else {
					// update an existing table
					$columns = $db->exec("SHOW COLUMNS FROM {$table} ");
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
							$db->exec($qry);
						} else {
							if (! $info["default"]) {
								$qry = "ALTER TABLE `{$table}` ALTER COLUMN `{$name}` DROP DEFAULT";
								// debug($qry);
								$db->exec($qry);
							}
							if (! $info["index"] && ! $info["unique"]) {
								$qry = "ALTER TABLE `{$table}` DROP INDEX IF EXISTS `{$name}`";
								// debug($qry);
								$db->exec($qry);
							}

							$qry = "ALTER TABLE `{$table}` MODIFY COLUMN {$col}";
							// debug($qry);
							$db->exec($qry);
						}
						
						// field has been refound
						unset($columns[$name]);
					}

					// anything left over should be dropped as a column
					foreach ($columns as $name => $values) {
						$qry = "ALTER TABLE `{$table}` DROP COLUMN `{$name}` ";
						// debug($qry);
						$db->exec($qry);
					}

					// now update change hash, so that we only hit this function when we've updated the $schema
					$cache->set($this->prefix.$table, $hash);
					add_alert("message", "Updated $table");
				}
			}
		}
	}




?>