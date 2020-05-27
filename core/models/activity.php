<?
class Activity extends \RF\Mapper {
	function __construct() {
		$schema = array(
			"post_id" => array(
				"type" => "INT(7)",
			),
			"user_id" => array(
				"type" => "INT(7)",
			),
		);

		parent::__construct("rf_activity", $schema);
	}
}

function get_unread($post_id) {
	$user = current_user();

	$activity = new Activity();
	$activity->load("*", array("post_id = :pid AND user_id = :uid", ":pid" => $post_id, ":uid" => $user->id));

	
}

function log_activity($post_id) {
	$user = current_user();

	$activity = new Activity();
	$activity->load("*", array("post_id = :pid AND user_id = :uid", ":pid" => $post_id, ":uid" => $user->id));
	$activity->post_id = $post_id;
	$activity->user_id = $user->id;
	$activity->save();
}