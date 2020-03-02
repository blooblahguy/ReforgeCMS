<?

	class reforge_field {
		var $name = '',
			$label = '',
			$category = 'basic',
			$defaults = array();

		function __construct() {
			RCF::instance()->register_field_type($this);

			$this->add_field_action('rcf/render_field', array($this, 'render_field'), 9);
			$this->add_field_action('rcf/render_field_settings', array($this, 'render_field_settings'), 9);
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