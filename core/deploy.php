<?php

$content = file_get_contents("php://input");
$secret = "BDGSECRET_2020_GLADE";
$token = false;
$json = json_decode($content, true);
$id = $json['repository']['id'];

if ($id != 242001243) {
	http_response_code(403);
	exit();
}

chdir("/home/runcloud/webapps/bigdumb/");
// echo exec("ssh -T git@github.com 2>&1");
// echo exec("git reset --hard origin/master git@github.com:blooblahguy/ReforgeCMS.git 2>&1");
exec("git pull git@github.com:blooblahguy/ReforgeCMS.git 2>&1", $output, $exit);

if ($exit !== 0) {
	http_response_code(403);
	print_r($output);
	exit();
}

echo "<pre>";
print_r($output);
echo "</pre>";