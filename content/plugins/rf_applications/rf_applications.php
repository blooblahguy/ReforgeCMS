<?php

class RFApplications extends \Prefab {
	function __construct() {
		global $core; 
		$this->applications = get_option("rfa_apply_index");
		$this->apply = get_option("rfa_apply_page");
		$this->form = get_option("rfa_apply_form");
		$this->role = get_option("rfa_apply_role");
		$this->path = dirname(__FILE__);

		// actions to load only necessary controllers
		add_action("admin/init", function() {
			require "includes/rfa_admin.php";
		});
		add_action("front/init", function() {
			require "includes/rfa_front.php";
		});

		// add permissions for managing and viewing applications
		add_permission(array(
			"slug" => "manage_applications",
			"label" => "Manage Applications",
			"description" => "Allow user to accept, decline, or close apps. They can also change the application format and settings."
		));
		add_permission(array(
			"slug" => "view_applications",
			"label" => "View Applications",
			"description" => "Allow users to view all guild applications"
		));

		// save our form as an application post type
		add_filter("form/submit", array($this, "save_status"));
		add_filter("form/submit/type", array($this, "save_as_application"));
		add_action("post/comment", array($this, "alert_comment"));
		add_action("post/insert/application", array($this, "alert_discord"));

		// shortcode for front end apply
		add_shortcode("apply_button", array($this, "apply_shortcode"));
	}

	function apply_shortcode() {
		if (current_user()->can("view_applications")) {
			return "";
		}

		if (! logged_in()) {
			echo '<a class="btn-secondary" href="/register">Register to Apply</a>';
			return;
		}

		echo $this->apply_button("Apply Now", false);
	}

	function alert_discord($post) {
		send_discord_app($post);
	}

	function alert_comment($post_id, $comment) {
		global $root;
		$user = current_user();

		$post = new Post();
		$post->load("post_type, author", array("id = :id", ":id" => $post_id));
		if ($post->post_type == "application" && $post->author == $user->id) {
			ob_start();
			include("views/email_update.php");
			$template = ob_get_clean();
			rf_mail($user->email, "Application Update", $template);
		}
	}

	function apply_button($text = "Apply Now") {
		$apply = new Post();
		$apply->load("*", array("id = :id", ":id" => $this->apply));
		?>
		<a class="btn-primary" href="<?= $apply->get_permalink(); ?>"><?= $text; ?></a>
		<?
	}
	function app_link($id) {
		$apps = new Post();
		$apps->load("*", array("id = :id", ":id" => $this->applications));

		return $apps->get_permalink()."/".$id;
	}

	function save_status($entry, $id) {
		if ($id == 0) {
			$user = get_user($entry->author);
			$entry->title = "Open - Application: ".$user->username;
			$entry->post_status = "open";
			$entry->permission = "view_applications";
			$entry->permission_exp = "==";
		}
		return $entry;
	}
	function save_as_application($type, $form_id) {
		if ($form_id == $this->form) {
			return "application";
		}

		return $type;
	}

	function front_view($core, $args) {
		
		// $content->page = $content->pages["/recruitment/applications"];
		// $content->page($core, $args);

		// debug($content);
		// debug($args);
	}
}

function RFApps() {
	return RFApplications::instance();
}
$rfa = RFApps();