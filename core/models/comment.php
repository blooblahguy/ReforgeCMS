<?

class Comment extends \RF\Mapper {
	function __construct() {
		$schema = array(
			"post_id" => array(
				"type" => "INT(7)",
			),
			"parent_comment" => array(
				"type" => "INT(7)",
			),
			"message" => array(
				"type" => "LONGTEXT",
			),
			"author" => array(
				"type" => "INT(7)",
			)
		);

		parent::__construct("rf_comments", $schema);
	}
}
