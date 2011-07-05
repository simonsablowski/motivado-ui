<?php

$configuration = array();

$configuration['pathApplication'] = dirname(__FILE__) . '/';

$configuration['includeDirectories'] = array(
	$configuration['pathApplication'],
	'D:/Entwicklung/api/',
	'D:/Entwicklung/nacho/'
);

$configuration['Database'] = array(
	'type' => 'MySql',
	'host' => 'localhost',
	'name' => 'motivado_ui',
	'user' => 'root',
	'password' => ''
);

$configuration['debugMode'] = TRUE;

$configuration['simulateUser'] = TRUE;