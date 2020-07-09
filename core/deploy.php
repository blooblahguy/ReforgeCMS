<?php

$content = file_get_contents("php://input");
$secret = "BDGSECRET_2020_GLADE";
$token = false;
$json = json_decode($content, true);

// $id = $json['repository']['id'];

// if ($id != 242001243) {
// 	http_response_code(403);
// 	exit();
// }

chdir("/home/runcloud/webapps/bigdumb/");
exec("git pull git@github.com:blooblahguy/ReforgeCMS.git 2>&1", $output, $exit);

echo "<pre>";
print_r($output);
print_r($exit);
echo "</pre>";

// define("TOKEN", "BDGSECRET_2020_GLADE");                                       // The secret token to add as a GitHub or GitLab secret, or otherwise as https://www.example.com/?token=secret-token
// define("REMOTE_REPOSITORY", "git@github.com:blooblahguy/ReforgeCMS.git"); // The SSH URL to your repository
// define("DIR", "/home/runcloud/webapps/bigdumb/");                       // The path to your repostiroy; this must begin with a forward slash (/)
// define("BRANCH", "refs/heads/master");                             				// The branch route
// define("LOGFILE", "deploy.log");                                       // The name of the file you want to log to.
// define("GIT", "git");    												 // The path to the git executable
// define("MAX_EXECUTION_TIME", 180);                                     // Override for PHP's max_execution_time (may need set in php.ini)
// define("BEFORE_PULL", "");                                             // A command to execute before pulling
// define("AFTER_PULL", "");                                              // A command to execute after successfully pulling

// include "deployer.php";
