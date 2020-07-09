<?php

// phpinfo();

chdir("/home/runcloud/webapps/bigdumb/");
echo exec("git pull git@github.com:blooblahguy/ReforgeCMS.git 2>&1", $output);

// echo "test";
print_r($output);