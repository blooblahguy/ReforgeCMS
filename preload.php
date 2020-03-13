<?

$files = array();
$files[] = "core/vendor/fatfree-core/base.php";
$files[] = "core/vendor/fatfree-core/f3.php";
$files[] = "core/vendor/fatfree-core/magic.php";
$files[] = "core/vendor/fatfree-core/bcrypt.php";
$files[] = "core/vendor/fatfree-core/audit.php";
$files[] = "core/vendor/fatfree-core/basket.php";

$files[] = "core/vendor/fatfree-core/db/sql.php";
$files[] = "core/vendor/fatfree-core/db/cursor.php";
$files[] = "core/vendor/fatfree-core/db/sql/mapper.php";

foreach ($files as $file) {
    opcache_compile_file($file);
}

?>