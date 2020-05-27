<?php

namespace RF;
class Schema extends \Prefab {
	function __construct() {}

	function update($table, $fields) {
		global $db;

		// ensure the table exists
		$db->exec("CREATE TABLE IF NOT EXISTS `$table` (id INT(7) PRIMARY KEY NOT NULL AUTO_INCREMENT)");

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
				"attrs" => "NOT NULL DEFAULT CURRENT_TIMESTAMP",
			);
		}
		if (! isset($fields["modified"])) {
			$fields["modified"] = array(
				"type" => "DATETIME",
				"attrs" => "NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
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