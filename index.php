<?
error_reporting(E_ALL);
ini_set("display_errors", 1);

$PATH = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");	
list($CONTROLLER) = explode("/", $PATH);
$root = $_SERVER['DOCUMENT_ROOT'];

$configuration = require "config.php";
require $root."/core/core.php";

// $meta = array(
// 	"field_5e5568ea32c29" => array(
// 		0 => array(
// 			"field_5e5568fc32c2b" => "Text value in repeater",
// 			"field_5e55690332c2c" => array(
// 				0 => array(
// 					"field_5e55690c32c2d" => "1 test1 in subrepeater",
// 					"field_5e55690d32c2e" => "1 test2 in subrepeater"
// 				),
// 				1 => array(
// 					"field_5e55690c32c2d" => "2 test1 in subrepeater",
// 					"field_5e55690d32c2e" => "2 test2 in subrepeater"
// 				),
// 			)
// 		),
// 	),
// 	"field_5e5568ed32c2a" => "Text String Here"
// );

// print_r(serialize($meta));



	// phpinfo();
	// opcache_get_status();
	
?>