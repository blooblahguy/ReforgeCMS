<?
	class Comment extends \RF\Mapper {
		function __construct() {
			global $db;
			$schema = array(
				"author" => array(
					"type" => "INT(7)",
				)
			);

			parent::__construct("rf_comments", $schema);
		}
	}
