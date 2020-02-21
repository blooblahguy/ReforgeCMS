<?
	namespace CFS;

	// Include Fields
	require_once("boolean.php");
	require_once("checkbox.php");
	require_once("color.php");
	require_once("date.php");
	require_once("file.php");
	require_once("form.php");
	require_once("image.php");
	require_once("link.php");
	require_once("number.php");
	require_once("post.php");
	require_once("radio.php");
	require_once("relationship.php");
	require_once("select.php");
	require_once("text.php");
	require_once("textarea.php");
	require_once("user.php");
	require_once("wysiwyg.php");
	require_once("groups/accordion.php");
	require_once("groups/flexible.php");
	require_once("groups/group.php");
	require_once("groups/repeater.php");
	require_once("groups/tab.php");

	$core->route("GET /core/custom_fields/form/@type", "\CFS\Core->get_form_template");
	$core->route("GET /core/custom_fields/settings/@type", "\CFS\Core->get_settings_template");

	class Core extends \Prefab {
		private $elements = array();

		function __construct() {
			
		}

		function add_element($type, $options) {
			$this->elements[$type] = $options;
			return $this->elements[$type];
		}

		function get_settings_template($core, $args) {
			$type = $args["type"];
			$field_id = $args["field_id"];
			$parent_id = $args["parent_id"];
			$values = isset($_POST["values"]) ? $_POST["values"] : array();

			ob_start();
			$this->elements[$type]["settings_template"]($field_id, $parent_id, $values);
			$template = ob_get_contents();
			ob_end_clean();
			echo trim($template);
		}
	}
?>