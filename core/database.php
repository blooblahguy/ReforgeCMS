<?php

// Database
$db = new DB\SQL(
	"mysql:host={$configuration["database_host"]};port={$configuration["database_port"]};dbname={$configuration["database"]}",
	"{$configuration["database_user"]}",
	"{$configuration["database_password"]}",
	array(
		\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
		\PDO::ATTR_PERSISTENT => true,
		\PDO::MYSQL_ATTR_COMPRESS => true,
	)
);