<?php

namespace RF_Applications;

class Model extends \RF\Mapper {
	function __construct() {
		parent::__construct("rf_posts", false, "rfa_applications");
	}
}