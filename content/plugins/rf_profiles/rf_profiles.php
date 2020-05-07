<?

class RF_Applications extends \Prefab {
	function __construct() {
		add_action("admin/init", array($this, "admin"));
		add_action("front/init", array($this, "front"));
	}
	function admin() {
		require "includes/rfp_admin.php";
	}
	function front() {
		require "includes/rfp_front.php";
		new RFP_Front();
	}
}

$rfa = \RF_Applications::instance();