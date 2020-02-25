$core->route("GET /core/custom_fields/form/@type", "\Core->get_form_template");
	$core->route("GET /core/custom_fields/settings/@type", "\Core->get_settings_template");

	class Core extends \Prefab {
		private $elements = array();

		function __construct() {
			
		}

		function add_element($type, $options) {
			$this->elements[$type] = $options;
			return $this->elements[$type];
		}

		private function build_meta($key, $parent_key, $menu_order) { ?>
			<div class="meta">
				<input type="hidden" name="cfields[<?= $key; ?>][key]" value="<?= $key; ?>">
				<input type="hidden" name="cfields[<?= $key; ?>][parent]" value="<?= $parent_key; ?>">
				<input type="hidden" name="cfields[<?= $key; ?>][menu_order]" value="<?= $menu_order; ?>">
			</div>
		<? }
		function unique_key($key) {
			global $user;
			$str = md5(time().$key.$user->id);
			$str = substr(base_convert($str, 16, 32), 0, 12);
			
			return "field_".$str.$key;
		}

		private function find_value($key, $args) {
			if (isset($_REQUEST[$key])) {
				return $_REQUEST[$key];
			} elseif (isset($args[$key])) {
				return $args[$key];
			}

			return false;
		}

		function get_settings_template($core, $args) {
			$type = $args["type"];
			$field_key = $this->find_value("field_key", $args);
			$parent_key = $this->find_value("parent_key", $args);
			$menu_order = $this->find_value("menu_order", $args);
			$values = $this->find_value("values", $args) ? $this->find_value("values", $args) : array();

			ob_start();
			$this->build_meta($field_key, $parent_key, $menu_order);
			$this->elements[$type]["settings_template"]($field_key, $values);
			$template = ob_get_contents();

			ob_end_clean();
			echo trim($template);
		}
	}