<?

	class RCF extends Prefab {
		public $types = array();
		public $rules = array();
		public $loaded = array();
		public $current_data = array();
		public $directory = "";

		function __construct() {
			$this->directory = dirname(__FILE__);

			// only load when on specific pages
			// add_action("admin/before_header", array($this, "load"));
			// add_action("admin/custom_fields", array($this, "load"));

			// load fields and evalutate conditions
			add_action("admin/custom_fields", array($this, "fields_load"));

			// field rendering
			add_action("rcf/admin_render_settings", array($this, "render_settings"));
			add_action("rcf/admin_render_rules", array($this, "render_rules"));
		}
		
		// function load($slug) {
		// 	if ($slug == "post" || $slug == "custom_fields") {
				
		// 	}
		// }


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
					$this->render_fields($field["id"], $request['page_id'], $type, $field);
				}
			}
		}

		
		function delete_all($type, $id) {
			// if (! isset($this->loaded[$type.":".$id])) {
				// $metas = new Meta();
			global $db;
			$this->loaded[$type.":".$id] = $db->query("DELETE FROM post_meta WHERE meta_type = '{$type}' AND meta_parent = {$id} ");
			// }
			// return $this->loaded[$type.":".$id];
		}

		function load_fields($type, $id) {
			if (! isset($this->loaded[$type.":".$id])) {
				$metas = new Meta();
				$rs = $metas->query("SELECT * FROM post_meta WHERE meta_type = '{$type}' AND meta_parent = {$id} ");
				$rs = rekey_array("meta_key", $rs);
				$this->loaded[$type.":".$id] = $rs;
			}
			return $this->loaded[$type.":".$id];
		}

		function get_fields($type, $id) {
			// debug("loading fields");
			$fields = $this->load_fields($type, $id);
			if (! $fields) {
				$fields = array();
			}

			$data = array();
			$row = array();
			foreach ($fields as $key => $meta) {
				$more = preg_split("/_[0-9]_/", $key);
				array_shift($more);
				preg_match_all('/_[0-9]_/', $key, $matches);
				$matches = reset($matches);


				if (count($more) == 0) {
					$data[$key] = $meta['meta_value'];
					$row = &$data[$key];

					continue;
				}

				// reference loop build array
				$sub = &$row;
				foreach ($more as $i => $v) {
					$k = false;
					if (isset($matches[$i])) {
						$k = (int) preg_replace("/[^0-9]/", "", $matches[$i]);
					}

					if (gettype($sub) !== "array") {
						$sub = array();
					}
					$sub[$k] = isset($sub[$k]) ? $sub[$k] : array();
					$sub[$k][$v] = isset($sub[$k][$v]) ? $sub[$k][$v] : array();

					$sub = &$sub[$k][$v];
				}

				$sub = htmlspecialchars_decode($meta['meta_value']);

			
			}

			return $data;
		}

		function save_fields($type, $id) {

			$metas = $_POST['rcf_meta'];
			$current = $this->load_fields($type, $id);
			$current = rekey_array("meta_key", $current);

			// debug($metas);
			// exit();

			// run save preperations
			if (isset($metas)) {
				foreach ($metas as $key => $values) {
					$field = $this->types[ $values['type'] ];
					$prepared_values = $field->prepare_save($values, $metas);
					$metas[$key] = $prepared_values;
					// $metas[$key]['field_type'] = $values['type'];
				}

				foreach ($metas as $key => $values) {
					$meta = new Meta();
					$meta->load("meta_key = '{$key}' AND meta_parent = {$id} AND meta_type = '{$type}'");
					$meta->meta_parent = $id;
					$meta->meta_type = $type;
					$meta->meta_key = $key;
					$meta->meta_value = $values['meta_value'];
					$meta->meta_info = $values['type'];
					$meta->save();

					unset($current[$key]);
				}
			}

			foreach ($current as $key => $values) {
				$meta = new Meta();
				$meta->query("DELETE FROM {$meta->model_table} WHERE meta_key = '{$key}' AND meta_type = '{$type}' AND meta_parent = $id ");
			}
		}

		function render_fields($cf_id, $page_id, $type, $fields) {
			// global $db;
			$cf = new CustomField();
			if ($cf_id > 0) {
				$cf->set_object($fields);
			}

			$this->current_data = $this->load_fields($type, $page_id);

			$view = array(
				"fields" => $cf->get_fields(),
				"context" => "",
			);

			$this->meta_type = $type;
			$this->page_id = $page_id;

			// load view
			echo '<div class="section">';
			rcf_get_template('group-fields', $view);
			echo '</div>';
		}

		function render_settings($id) {
			$cf = new CustomField();
			if ($id > 0) {
				$cf->load("id = $id", null, 1);
			}

			$view = array(
				"fields" => $cf->get_fields(),
				"parent" => 0,
				"post_id" => $id
			);

			// load view
			rcf_get_template('group-settings', $view);
		}

		function render_rules($id) {
			$cf = new CustomField();
			if ($id > 0) {
				$cf->load("id = $id", null, 1);
			}

			$rules = array("all_rules" => $cf->get_rules());

			// load view
			rcf_get_template('group-rules', $rules);
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

	require "$root/core/custom_fields/includes/init.php";
?>