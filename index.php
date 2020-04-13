<?
// echo "<pre>";
// print_r(unserialize('a:1:{i:0;a:2:{i:0;a:3:{s:3:"key";s:9:"post_type";s:10:"expression";s:2:"==";s:5:"value";s:5:"pages";}i:1;a:3:{s:3:"key";s:5:"pages";s:10:"expression";s:2:"==";s:5:"value";s:1:"4";}}}'));
// echo "</pre>";
error_reporting(E_ALL);
ini_set("display_errors", 1);

$PATH = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");	
list($CONTROLLER) = explode("/", $PATH);
$root = rtrim($_SERVER['DOCUMENT_ROOT'], "/");

$configuration = require "reforge-config.php";
require $root."/core/core.php";

