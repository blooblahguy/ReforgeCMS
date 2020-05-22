<?

	class RCF extends Prefab {
		public $types = array();
		public $rules = array();
		public $loaded = array();
		public $current_data = array();
		public $directory = "";

		private $field_data = array();

		function __construct() {
			$this->directory = dirname(__FILE__)."/custom_fields";

			// only load when on specific pages
			// add_action("admin/before_header", array($this, "load"));
			// add_action("admin/custom_fields", array($this, "load"));

			// load fields and evalutate conditions
			add_action("admin/custom_fields", array($this, "fields_load"));

			// field rendering
			add_action("rcf/admin_render_settings", array($this, "render_settings"));
			add_action("rcf/admin_render_rules", array($this, "render_rules"));
		}

		/*
		Recursively store information about fields by key
		*/
		function store_field_data($fields) {
			foreach ($fields as $field) {
				$children = $field['children'];
				unset($field['children']);

				$this->field_data[$field['key']] = $field;

				if ($children and count($children) > 0) {
					$this->store_field_data($children);
				}
			}
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
						if (! $rs) { 
							$passed = false;
							break;
						}
					}

					if ($passed) { 
						$load = true;
						break;
					}
				}

				if ($load) {
					$this->render_fields($field["id"], $request['page_id'], $type, $field);
				}
			}
		}


		function load_fields($type, $id) {
			if (! isset($this->loaded[$type.":".$id])) {
				$metas = new Meta();
				$rs = $metas->query("SELECT * FROM {$metas->table} WHERE meta_type = '{$type}' AND meta_parent = {$id} ");
				$rs = rekey_array("meta_key", $rs);
				$this->loaded[$type.":".$id] = $rs;
			}
			return $this->loaded[$type.":".$id];
		}

		function load_all_fields() {
			$cfs = new CustomField();
			$cfs = $cfs->load_all();
		}

		// recursively format and sanitize data for front end
		function prepare_values($data) {
			// debug($data);

			foreach ($data as $key => &$d) {

				// debug($d);
				$type = $d['type'];
				if ($type) {	
					unset($d['type']);
				}

				// repeater have sub rows without types
				if ($type == "meta") {
					$d = $d['value'];
					continue;
				} elseif ($type) {
					$d = $this->types[$type]->prepare_field_values($d['value']);
				} 
				
				if (gettype($d) == "array") {
					$d = $this->prepare_values($d);
				}
			}

			// debug($data);

			return $data;
		}

		function get_fields($type, $id) {
			// debug("loading fields");
			$this->load_all_fields();


			$fields = $this->load_fields($type, $id);
			if (! $fields) {
				$fields = array();
			}

			$data = array();
			$row = array();
			$last = false;
			foreach ($fields as $key => $meta) {
				$more = preg_split("/_[0-9]_/", $key);
				array_shift($more);
				preg_match_all('/_[0-9]_/', $key, $matches);
				$matches = reset($matches);
				$field = $this->field_data[$meta['meta_info']];
// 
				// debug($field);
				
				if (count($more) == 0) {
					if (! $field['type']) {
						$field['type'] = "meta";
					}
					$data[$key]  = array(
						"type" => $field['type'],
						"value" => $meta['meta_value'], 
					);
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

					// if parent row was already set to a value, make it an array to we can store values
					if (gettype($sub['value']) !== "array") {
						$sub['value'] = array();
					}

					// build array
					$sub['value'][$k] = isset($sub['value'][$k]) ? $sub['value'][$k] : array();
					$sub['value'][$k][$v] = isset($sub['value'][$k][$v]) ? $sub['value'][$k][$v] : array();
					$sub = &$sub['value'][$k][$v];
				}

				// store values
				$sub = array(
					"type" => $field['type'],
					"value" => $meta['meta_value'], 
				);
			}

			// now run the data through the prepare_value methods
			// debug($data);
			$data = $this->prepare_values($data);

			return $data;
		}

		function save_fields($type, $id) {

			$metas = $_POST['rcf_meta'];
			$current = $this->load_fields($type, $id);
			$current = rekey_array("meta_key", $current);

			// run save preperations
			if (isset($metas)) {
				foreach ($metas as $key => $values) {
					$field = $this->types[ $values['meta_type'] ];
					$prepared_values = $field->prepare_save($values, $metas);
					$metas[$key] = $prepared_values;
				}

				foreach ($metas as $key => $values) {
					$meta = new Meta();
					$meta->load("meta_key = '{$key}' AND meta_parent = {$id} AND meta_type = '{$type}'");
					$meta->meta_parent = $id;
					$meta->meta_type = $type;
					$meta->meta_key = $key;
					$meta->meta_value = $values['meta_value'];
					$meta->meta_info = $values['meta_info'];
					$meta->save();

					unset($current[$key]);
				}
			}

			foreach ($current as $key => $values) {
				$meta = new Meta();
				$meta->query("DELETE FROM {$meta->table} WHERE meta_key = '{$key}' AND meta_type = '{$type}' AND meta_parent = $id ");
			}
		}

		function render_results($cf_id, $page_id, $type, $fields) {
			$cf = new CustomField();
			if ($cf_id > 0) {
				$cf->factory($fields);
			}

			$this->current_data = $this->load_fields($type, $page_id);

			$view = array(
				"fields" => $cf->get_fields(),
				"context" => "",
			);

			// debug($view);

			$this->meta_type = $type;
			$this->page_id = $page_id;

			// load view
			echo '<div class="field_results">';
			rcf_get_template('group-results', $view);
			echo '</div>';
		}

		function render_fields($cf_id, $page_id, $type, $fields) {
			$cf = new CustomField();
			if ($cf_id > 0) {
				$cf->factory($fields);
			}

			$this->current_data = $this->load_fields($type, $page_id);

			$view = array(
				"fields" => $cf->get_fields(),
				"context" => "",
			);

			$this->meta_type = $type;
			$this->page_id = $page_id;

			// load view
			echo '<div class="field_section">';
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

		function ajax_render_choices($core, $args) {
			$slug = $args['slug'];
			$rule = $this->get_rule($slug);

			$rule->render_choices();
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

	require "$root/core/controllers/custom_fields/includes/init.php";
