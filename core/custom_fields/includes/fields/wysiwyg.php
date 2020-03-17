<?

class reforge_field_WYSIWYG extends reforge_field {

	function __construct() {
		$this->name = "wysiwyg";
		$this->label = "WYSIWYG";
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