<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 14/11/14
 * Time: 11:32 AM
 */

namespace
{
	require_once 'vendor/autoload.php';

	$client = new \Raygun4php\RaygunClient("HeOop4TH6WT7lwN0UIL03g==", false);

	function error_handler($errno, $errstr, $errfile, $errline ) {
		global $client;
		$client->SendError($errno, $errstr, $errfile, $errline);
	}

	function exception_handler($exception)
	{
		global $client;
		$client->SendException($exception);
	}

	set_exception_handler('exception_handler');
	set_error_handler("error_handler");
}