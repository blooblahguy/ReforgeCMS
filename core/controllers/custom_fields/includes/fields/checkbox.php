<?

class reforge_field_CHECKBOX extends reforge_field {

	function __construct() {
		$this->name = "checkbox";
		$this->label = "Checkboxes";
		$this->category = "Choice";

		parent::__construct();
	}


	//========================================================
	// EDIT
	//========================================================
	function html($field) {
		
	}



	//========================================================
	// OPTIONS EDIT
	//========================================================
	function options_html($key, $field) {

	}
}

