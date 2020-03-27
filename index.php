<?
error_reporting(E_ALL);
ini_set("display_errors", 1);

$PATH = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");	
list($CONTROLLER) = explode("/", $PATH);
$root = rtrim($_SERVER['DOCUMENT_ROOT'], "/");

$configuration = require "reforge-config.php";
require $root."/core/core.php";