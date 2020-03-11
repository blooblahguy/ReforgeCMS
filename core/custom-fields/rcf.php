<?

	class RCF extends Prefab {
		public $types = array();
		public $rules = array();
		public $directory = "";

		function __construct() {
			$this->directory = dirname(__FILE__);

			// only load when on specific pages
			add_action("admin/before_header", array($this, "load"));
			add_action("admin/custom_fields", array($this, "load"));

			// load fields and evalutate conditions
			add_action("admin/custom_fields", array($this, "fields_load"));

			// field rendering
			add_action("rcf/admin_render_settings", array($this, "render_settings"));
			add_action("rcf/admin_render_rules", array($this, "render_rules"));
		}

		
		function load($slug) {
			if ($slug == "post" || $slug == "custom_fields") {
				require_once("includes/init.php");
			}
		}


		function fields_load($type) {
			global $request;

			$cfs = new CustomField();
			$cfs = $cfs->load_all();

			foreach ($cfs as $id => $field) {
				$load_rules = unserialize($field["load_rules"]);

				$load = false;
				foreach ($load_rules as $group => $rules) {
					$passed = true;
					foreach ($rules as $rule) {
						$rs = $this->get_rule($rule['key'])->rule_match($request, $rule);
						if (! $rs) { $passed = false; }
					}

					if ($passed) { $load = true; }
				}

				if ($load) {
					$this->render_fields($field["id"], $request['page_id'], $type);
				}
			}
		}

		function render_fields($cf_id, $page_id, $type) {
			global $db;
			$cf = new CustomField();
			if ($cf_id > 0) {
				$cf->load("id = $cf_id", null, 1);
			}

			// $metas = new Meta();
			$data = $db->exec("SELECT * FROM post_meta WHERE meta_type = '{$type}' AND parent_id = {$page_id} AND meta_key = 'custom_fields' ");
			$data = unserialize($data[0]['meta_value']);

			$view = array(
				"fields" => $cf->get_fields(),
				"source" => $data,
				"parent" => 0
			);

			// load view
			rcf_get_view('group-fields', $view);
		}

		function render_settings($id) {
			$cf = new CustomField();
			if ($id > 0) {
				$cf->load("id = $id", null, 1);
			}

			$view = array(
				"fields" => $cf->get_fields(),
				"parent" => 0
			);

			// load view
			rcf_get_view('group-settings', $view);
		}

		function render_rules($id) {
			$cf = new CustomField();
			if ($id > 0) {
				$cf->load("id = $id", null, 1);
			}

			$rules = array("all_rules" => $cf->get_rules());

			// load view
			rcf_get_view('group-rules', $rules);
		}


		/**
		 * Register Rule Type From Class
		 */
		function register_rule_type($class) {
			$this->rules[ $class->name ] = $class;
		}
		function get_rule($key) {
			return $this->rules[$key];
		}
		function get_rule_type_choices($key, $value) {
			echo $this->rules[$key]->render_choices($value);
		}

		/**
		 * Register Field Type From Class
		 */
		function register_field_type($class) {
			$this->types[ $class->name ] = $class;
		}

		public function get_field_types() {
			if ($this->type_array) {return $this->type_array; }
			$this->type_array = array();

			foreach ($this->types as $type) {
				$label = $type->label;
				$name = $type->name;
				$category = $type->category;

				$this->type_array[$category][$name] = $label;
			}

			return $this->type_array;
		}
	}

	function RCF() {
		return RCF::instance(); 
	}

	RCF();
?>