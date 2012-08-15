<?php

include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/Config.class.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/kml/KmlReader.class.php";


//database params
$config = Config::singleton();
$username = $config->username;
$hostname = $config->hostname;
$password = $config->password;


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
   $message = array('message' => "Este servicio del API de Looking4Maps solo se puede acceder por el método POST");
	print json_encode($message);
	return;
}


$kmlContent = $_POST['map'];




$source = $_POST['source'];
$url = $_POST['originalUrl'];


try {
	if($kmlContent){
		$dbh = new PDO("mysql:host=$hostname;dbname=tusmapas", $username, $password, array(
    		PDO::ATTR_PERSISTENT => true
		));
		$dbh->query("SET CHARACTER SET utf8");
		
			$kmlMap = KmlReader::readKml($kmlContent, $url, $source,  $dbh);
			
			$docName = $kmlMap->getDocumentName();
			$description = $kmlMap->getDescription();
			$keywords = $kmlMap->getKeywordsAsText();
			
			$message = array(
				"docName" => $docName,
				"description" => $description,
				"keywords" => $keywords
			);
			
			print json_encode($message);
	}else {
		$message = array('message' => "El parámetro map (contenido del kml) no puede estar vacío");
		print json_encode($message);
	}
		
}catch(Exception $e){
	$extendedMessage = $e->getMessage();
	$message = array('message' => "Ha habido un problema al intentar añadir el mapa. Por favor, inténtelo más tarde".$e,
					 'extendedMessage' => $extendedMessage	
	);
	print json_encode($message);
}
