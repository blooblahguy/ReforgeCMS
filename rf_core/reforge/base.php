<?php

final class reforge {

	/**
	*	Generate 64bit/base36 hash
	*	@return string
	*	@param $str
	**/
	function hash($str) {
		return str_pad(base_convert(
			substr(sha1($str),-16),16,36),11,'0',STR_PAD_LEFT);
	}

}
?>