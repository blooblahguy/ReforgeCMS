<?

class reforge_field_COLOR extends reforge_field {

	function __construct() {
		$this->name = "color";
		$this->label = "Color";
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

?>