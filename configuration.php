<?php

$configuration = array();

$configuration['pathApplication'] = dirname(__FILE__) . '/';

$configuration['includeDirectories'] = array(
	$configuration['pathApplication'],
	$configuration['pathApplication'] . '../motivado-api/',
	$configuration['pathApplication'] . '../nacho/'
);

$configuration['Database'] = array(
	'type' => 'MySql',
	'host' => 'localhost',
	'name' => 'motivado_ui',
	'user' => 'motivado',
	'password' => ''
);

$configuration['debugMode'] = TRUE;

$configuration['simulateUser'] = FALSE;
