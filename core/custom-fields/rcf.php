<?

	class RCF extends \Prefab {
		public $types = array();
		public $rules = array();

		function __construct() {
			global $core;

			// Include Fields
			require_once("functions.php");
			require_once("class-field.php");
			require_once("class-rule.php");

			// Ajax field display
			$core->route("GET /core/custom_fields/settings/@type", function($core, $args) {
				$type = $args["type"];

				$field = array(
					"key" => $_GET['key'],
					"parent" => $_GET['parent'],
					"menu_order" => $_GET['menu_order']
				);

				do_action("rcf/render_field_settings/type={$type}", $field);
			});

			// load fields and evalutate conditions
			add_action("admin/page/edit_after", array($this, "fields_load"));

			// $core->route("GET /core/custom_fields/form/@type", "\RCF->get_form_template");
			// $core->route("GET /core/custom_fields/settings/@type", "\RCF->get_settings_template");
		}

		function fields_load($page) {
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
					$this->render_fields($field["id"]);
				}
			}
		}

		function render_fields($id) {
			$cf = new CustomField();
			if ($id > 0) {
				$cf->load("id = $id", null, 1);
			}

			$view = array(
				"fields" => $cf->get_fields(),
				"parent" => 0
			);

			// load view
			rcf_get_view('group-edits', $view);
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
			rcf_get_view('group-fields', $view);
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

		function validate_value($value, $field, $input) {
			$valid = true;

			// $valid = apply_filters( "acf/validate_value/type={$field['type']}",		$valid, $value, $field, $input );
			// $valid = apply_filters( "acf/validate_value/name={$field['_name']}", 	$valid, $value, $field, $input );
			// $valid = apply_filters( "acf/validate_value/key={$field['key']}", 		$valid, $value, $field, $input );
			// $valid = apply_filters( "acf/validate_value", 							$valid, $value, $field, $input );
			
			
			// allow $valid to be a custom error message
			if( !empty($valid) && is_string($valid) ) {
				$message = $valid;
				$valid = false;
			}

			if ( ! $valid ) {
				// acf_add_validation_error( $input, $message );
			}

			return $valid;
		}
	}

	$rcf = RCF::instance();

	// field rendering
	add_action("rcf/admin_render_fields", array($rcf, "load_files"));
	add_action("rcf/admin_render_settings", array($rcf, "render_settings"));
	add_action("rcf/admin_render_rules", array($rcf, "render_rules"));


	// now require field class files
	require_once("fields/field-boolean.php");
	require_once("fields/field-checkbox.php");
	require_once("fields/field-color.php");
	require_once("fields/field-date.php");
	require_once("fields/field-file.php");
	require_once("fields/field-form.php");
	require_once("fields/field-image.php");
	require_once("fields/field-link.php");
	require_once("fields/field-number.php");
	require_once("fields/field-post.php");
	require_once("fields/field-radio.php");
	require_once("fields/field-relationship.php");
	require_once("fields/field-select.php");
	require_once("fields/field-text.php");
	require_once("fields/field-textarea.php");
	require_once("fields/field-user.php");
	require_once("fields/field-wysiwyg.php");
	require_once("fields/field-accordion.php");
	require_once("fields/field-flexible.php");
	require_once("fields/field-group.php");
	require_once("fields/field-repeater.php");
	require_once("fields/field-tab.php");

	// now require rule class files
	require_once("rules/rule-form.php");
	require_once("rules/rule-page.php");
	require_once("rules/rule-posttype.php");
	require_once("rules/rule-user.php");
	require_once("rules/rule-userrole.php");
	require_once("rules/rule-widget.php");

?>