<?php

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Config\Adapter\Ini as ConfigIni;

// set the date to come in Spanish
setlocale(LC_TIME, "es_ES");

// include composer
include_once "../vendor/autoload.php";

try
{
	//Register autoLoader for Analytics
	$loaderAnalytics = new Loader();
	$loaderAnalytics->registerDirs(array(
		'../classes/',
		'../app/controllers/'
	))->register();

	//Create Run DI
	$di = new FactoryDefault();

	// Creating the global path to the root folder
	$di->set('path', function () {
		$protocol = empty($_SERVER['HTTPS']) ? "http" : "https";
		return array(
			"root" => dirname(__DIR__),
			"http" => "$protocol://{$_SERVER['HTTP_HOST']}"
		);
	});

	// Setup the view component for Analytics
	$di->set('view', function () {
		$view = new View();
		$view->setLayoutsDir('../layouts/');
		$view->setViewsDir('../app/views/');
		return $view;
	});

	// Handle the request
	$application = new Application($di);
	echo $application->handle()->getContent();
}
catch(\Phalcon\Mvc\Dispatcher\Exception $e)
{
	header('HTTP/1.0 404 Not Found');
	echo "<h1>Error 404</h1><p>We apologize, but this page was not found.</p>";
//	echo "PhalconException: ", $e->getMessage();
}
