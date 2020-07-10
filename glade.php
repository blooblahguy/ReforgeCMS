<?php

// error_reporting(E_ALL);
// ini_set("display_errors", 1);
// // ini_set("error_log", $root."/core/logs/php-error-".Date("Y-m-d").".log");

phpinfo();

// function cache_set($key, $val) {
// 	$root = rtrim($_SERVER['DOCUMENT_ROOT'], "/");

// 	$val = var_export($val, true);
// 	// HHVM fails at __set_state, so just use object cast for now
// 	$val = str_replace('stdClass::__set_state', '(object)', $val);
// 	// Write to temp file first to ensure atomicity
// 	$tmp = $root."/tmp/$key." . uniqid('', true) . '.tmp';
// 	file_put_contents($tmp, '<?php $val = ' . $val . ';', LOCK_EX);
// 	rename($tmp, $root."/tmp/$key");
//  }

//  function cache_get($key) {
// 	$root = rtrim($_SERVER['DOCUMENT_ROOT'], "/");

//     @include $root."/tmp/$key";
//     return isset($val) ? $val : false;
// }

// $data = array_fill(0, 1000000, 'hi'); // your application data here
// cache_set('my_key', $data);
// apcu_store('my_key', $data);

// echo "<pre>";
// $t = microtime(true);
// $data = cache_get('my_key');
// echo microtime(true) - $t;
// echo "</pre>";
// // 0.00013017654418945

// $t = microtime(true);
// $data = apcu_fetch('my_key');
// echo "<pre>";
// echo microtime(true) - $t;
// echo "</pre>";
// // 0.061056137084961