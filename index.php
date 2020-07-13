<?
$reforge_load_time = -hrtime(true);

$start_perf = -hrtime(true);
function logp($name = null) {
	global $start_perf;


	$new = hrtime(true);

	$start_perf += $new;
	$friendly = $start_perf / 1e+6;
	$start_perf = -$new;

	echo "<pre>";
	if ($name) {
		echo "<strong>$name: </strong>";
	}
	echo $friendly;
	echo "</pre>";
}


$PATH = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");	
list($CONTROLLER) = explode("/", $PATH);
$root = rtrim($_SERVER['DOCUMENT_ROOT'], "/");

error_reporting(E_ALL);
ini_set("display_errors", 1);

require $root."/core/init.php";
require $root."/core/core.php";