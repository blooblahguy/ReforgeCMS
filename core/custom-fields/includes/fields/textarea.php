<?

class reforge_field_TEXTAREA extends reforge_field {

	function __construct() {
		$this->name = "textarea";
		$this->label = "Text Area";
		$this->category = "Content";

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

?>