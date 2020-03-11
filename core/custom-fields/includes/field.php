<?

	class reforge_field {
		var $name = '',
			$label = '',
			$category = 'basic',
			$defaults = array();

		function __construct() {
			global $core; 
			RCF()->register_field_type($this);

			$this->add_field_action('rcf/html', array($this, 'html'), 9);
			$this->add_field_action('rcf/options_html', array($this, 'options_html'), 9);

			// Ajax field display
			$core->route("GET /admin/custom_fields/settings/{$this->name}", function($core, $args) {
				$type = $this->name;

				$field = array(
					"key" => $_GET['key'],
					"parent" => $_GET['parent'],
					"menu_order" => $_GET['menu_order']
				);

				do_action("rcf/options_html/type={$type}", $field);
			});
		}

		function add_field_action($action, $func, $priority = 10) {
			// append
			$action .= '/type=' . $this->name;
			add_action( $action, $func, $priority );
		}

		function load_value() {
			global $request;
			$page_id = $request["page_id"];
		}
	}

?>