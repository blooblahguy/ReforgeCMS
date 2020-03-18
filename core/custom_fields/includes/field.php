<?

	class reforge_field {
		var $name = '',
			$label = '',
			$category = 'basic',
			$defaults = array();

		function __construct() {
			global $core; 
			RCF()->register_field_type($this);

			$this->add_field_filter('rcf/prepare_value', array($this, 'prepare_value'), 9);
			$this->add_field_action('rcf/html', array($this, 'html'), 9);
			$this->add_field_action('rcf/options_html', array($this, 'options_html'), 9);

			// Ajax field display
			$core->route("GET /admin/custom_fields/settings/{$this->name}", function($core, $args) {
				$type = $this->name;
				// attempt to load existing layout if it exists
				if ($_GET['post_id'] > 0) {
					$cf = new CustomField();
					$cf->load("id = {$_GET['post_id']}", null, 1);
					$fields = $cf->get_fields();

					$field = $this->find_field_table($_GET['field_key'], $fields);
				} 
				// didn't load correctly or didn't request an existing layout
				if ($_GET['post_id'] == 0 || ! isset($field)) {
					$field = array(
						"fields" => "",
						"key" => $_GET['field_key'],
						"parent" => $_GET['parent_key'],
						"menu_order" => $_GET['menu_order']
					);
				}

				$field['post_id'] = $_GET['post_id'];

				do_action("rcf/options_html/type={$type}", $field);
			});
		}

		// recursive data finder
		function find_field_table($key, $fields) {
			foreach ($fields as $id => $field) {
				if ($id == $key) {
					return $field;
				}
				if (isset($field['children'])) {
					return $this->find_field_table($key, $field['children']);
				}
			}
		}

		function add_field_action($action, $func, $priority = 10) {
			$action .= '/type=' . $this->name;
			add_action( $action, $func, $priority );
		}
		function add_field_filter($action, $func, $priority = 10) {
			$action .= '/type=' . $this->name;
			add_filter( $action, $func, $priority );
		}

		function prepare_value($meta) {
			return $meta;
		}

		function prepare_save($meta, $metas) {
			return $meta;
		}
	}

?>