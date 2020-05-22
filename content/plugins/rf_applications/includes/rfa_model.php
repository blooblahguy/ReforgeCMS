<?php

namespace RF_Applications;

class Model extends \RF\Mapper {
	function __construct() {
		parent::__construct("rf_applications", false, "rfa_applications");
	}
}