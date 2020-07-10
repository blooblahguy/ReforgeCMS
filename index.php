<?

$PATH = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");	
list($CONTROLLER) = explode("/", $PATH);
$root = rtrim($_SERVER['DOCUMENT_ROOT'], "/");

error_reporting(E_ALL);
ini_set("error_log", $root."/core/logs/php-error-".Date("Y-m-d").".log");
ini_set("display_errors", 1);

require $root."/core/init.php";
require $root."/core/core.php";