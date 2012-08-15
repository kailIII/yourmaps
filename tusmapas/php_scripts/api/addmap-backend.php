<?php

include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/Config.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/Util.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/MapAlreadyExistException.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/NotAuthorizedException.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/KmlWithoutCoordinatesException.class.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/kml/KmlReader.class.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/wms-simple/WmsReader.class.php";

//database params
$config = Config::singleton();
$username = $config->username;
$hostname = $config->hostname;
$password = $config->password;
$database = $config->database;


$uri = rawUrlDecode($_GET['map']);




$mapType = $_GET['type'];
$user = $_GET['user'];

if($user == null)
	$user = "anonymous";
	
	
//$mimeTypes = array(
//	"application/vnd.google-earth.kml+xml"
//);

try {
	
	$dbh = new PDO("mysql:host=$hostname;dbname=$database", $username, $password, array(
 	   PDO::ATTR_PERSISTENT => true
	));
	$dbh->query("SET CHARACTER SET utf8");
	
	//checking mime-type
//	$mime_type = Util::get_url_mime_type($uri);
	

	
	
	if($mapType == "wms"){
		
		$wms = WmsReader::loadWms($uri, $user, $dbh);
		
		$title = $wms->getWms()->getWmsTitle();
		$abstract = $wms->getWms()->getWmsAbstract();
		$keywords = $wms->getKeywordsAsText();
		
		$message = array(
			"docName" => $title,
			"description" => $abstract,
			"keywords" => $keywords
		);
		
		unset($wms);
		unset($title);
		unset($abstract);
		unset($keywords);
		
		print json_encode($message);
		
		
	}else if($mapType == "kml"){
		$kmlMap = KmlReader::loadKml($uri, null, $user,"Looking4Maps", $dbh);
		
		$docName = $kmlMap->getDocumentName();
		$description = $kmlMap->getDescription();
		$keywords = $kmlMap->getKeywordsAsText();
		
		unset($kmlMap);
		unset($docName);
		unset($description);
		unset($keywords);
		
		$message = array(
			"docName" => $docName,
			"description" => $description,
			"keywords" => $keywords
		);
		
		print json_encode($message);
		
		
	}else{
		$message = array('message' => "Formato de mapa no soportado");
		print json_encode($message);
	}
	
	
}catch (MapAlreadyExistException $e){
	$message = array(
		'message' => "Ya existe el mapa ".$uri." de tipo ".$mapType
	);
	print json_encode($message);
	
}catch (NotKmlException $e){
	$message = array(
		'message' => "El enlace ".$uri." no apunta a un mapa de tipo KML"
	);
	print json_encode($message);
	
} catch (NotAuthorizedException $e){
	$message = array(
		'message' => "El servicio ".$uri." está securizado (error 401)"
	);
	print json_encode($message);
	
}catch (KmlWithoutCoordinatesException $e){
	$message = array(
		'message' => "El kml ".$uri." no tiene coordenadas"
	);
	print json_encode($message);
	
}
/*
catch(Exception $e){
	$extendedMessage = $e->getMessage();
	$message = array('message' => "Ha habido un problema al intentar añadir el mapa. Por favor, inténtelo más tarde",
					 'extendedMessage' => $extendedMessage	
	);
	print json_encode($message);
}
*/
