<?php

include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts/". 'Config.class.php';
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts/".'Util.class.php';
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts/".'MapsKeywordRelationship.class.php';
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts/". 'MapKeyword.class.php';
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/kml/KmlReader.class.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/wms-simple/WmsReader.class.php";

include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/open-calais/opencalais.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/WmsWithoutBBoxException.class.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/NotWmsException.class.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/NotAuthorizedException.class.php";

$config = Config::singleton();
$username = $config->username;
$hostname = $config->hostname;
$password = $config->password;
$database = $config->database;

$wmsReader = new WmsReader();
$kmlReader = new KmlReader();


$uri = $_GET['map'];
$mapType = $_GET['type'];
$user = $_GET['user'];

try {
	
	$dbh = new PDO("mysql:host=$hostname;dbname=$database", $username, $password, array(
 	   PDO::ATTR_PERSISTENT => true
	));
	$dbh->query("SET CHARACTER SET utf8");
	

	
	

	$message = array('message' => "    ");
	print json_encode($message);
	
	
	
}catch(Exception $e){
	$extendedMessage = $e->getMessage();
	$message = array('message' => "Ha habido un problema al intentar añadir el mapa. Por favor, inténtelo más tarde",
					 'extendedMessage' => $extendedMessage	
	);
	print json_encode($message);
}

