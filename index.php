<?php
	require 'router.php';
	
	$route = $_SERVER["PATH_INFO"] . "/";
	$router = new router;
	$router->run($route);