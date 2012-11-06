<?php

//if ($_SERVER['DOCUMENT_ROOT'] == "")
//	$_SERVER['DOCUMENT_ROOT'] = "/var/www/tusmapas/";

if ($_SERVER['DOCUMENT_ROOT'] == "")
   $_SERVER['DOCUMENT_ROOT'] = "/home/oxmzmurs/public_html/";

include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/Config.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/Util.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/MapAlreadyExistException.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/NotAuthorizedException.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/KmlWithoutCoordinatesException.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/WmsWithoutBBoxException.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/UnknownMapException.class.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/kml/KmlReader.class.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/wms-simple/WmsReader.class.php";


include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/ReaderFactory.class.php";


include_once "CronJob.class.php";
require_once '../json/JSON.php';
include "../simple_html_dom/simple_html_dom.php";


//database params
$config = Config::singleton();
$username = $config->username;
$hostname = $config->hostname;
$password = $config->password;
$database = $config->database;

$user = "anonymous";

if( !ini_get('safe_mode') ){
	set_time_limit(0);
}


$messageText = "";

$dbh = null;
$cronjob = null;
$wmsReader = new WmsReader();

$startMap = 0;
$maxMap = 4050;

try{

	$dbh = new PDO("mysql:host=$hostname;dbname=$database", $username, $password,
	array(
	PDO::ATTR_PERSISTENT => true
	));
	$dbh->query("SET CHARACTER SET utf8");
	$cronJob = new CronJob("mapmatters");
	$cronJob->save($dbh);

	/*
	 ALTER TABLE `CONSTANTS`  ADD COLUMN `LAST_MAPMATTERS_INDEXED` INT(10) NULL DEFAULT '0' AFTER `LAST_GOOL_ZOOM_COUNTRY_MAP`,  ADD COLUMN `MAX_MAP_MATTERS` INT(10) NULL DEFAULT '0' AFTER `LAST_MAPMATTERS_INDEXED`;
	 */
	$sql = "SELECT LAST_MAPMATTERS_INDEXED, MAX_MAP_MATTERS FROM CONSTANTS";
	$statement = $dbh->query($sql);
	if($statement->execute()){
		if($row = $statement->fetch()){
			$startMap = $row["LAST_MAPMATTERS_INDEXED"];
			$maxMap = $row["MAX_MAP_MATTERS"];
		}
	}

}catch(PDOException $exception){
	$messageText .= "<b>Conexión a BBDD no disponible</b><br/>";


	echo $message;

	die();
}


$okMaps = 0;
$alreadyExist = 0;
$wrongKml = 0;
$kmlWithoutCoords = 0;
$unknownType = 0;
$wrongXml = 0;



for($i = $startMap; $i < $maxMap;$i++){

	$mapUri = "http://www.mapmatters.org/server/".$i;
	$htmlDom = file_get_html($mapUri);
	if(! $htmlDom){
		echo $mapUri . "no es un mapa de mapmatters";
		try{
			$dbh->query("UPDATE CONSTANTS SET LAST_MAPMATTERS_INDEXED = ".$i);
		}catch(Exception $e){
			echo "Error al actualizar el indice de rastreo ".$e;
			die();
		}
		
		continue;
		sleep(1);
	}
	$elements = $htmlDom->find("dd a");
	
	$url = $elements[0]->href;

	if(! $url){
		echo $mapUri . "no tiene URL de servicio WMS";
		continue;
	}

	
	try {
		
		$wmsReader->loadMap($url, "anonymous", "mapmatters.com", $dbh);
	
		echo "El mapa ".$url." cargado correctamente<br/></br>";
	
		$okMaps++;
	
	}catch (MapAlreadyExistException $e){
		echo "el mapa ".$url." ya existe";
		$alreadyExist++;
	}catch (NotKmlException $e){
		echo "El mapa ".$url." no es del tipo KML" . "<br/><br/>";
		$wrongKml++;
	} catch (NotAuthorizedException $e){
		echo "El mapa ".$url." está securizado" . "<br/><br/>";
	}catch (KmlWithoutCoordinatesException $e){
		echo "El mapa ".$url." es un KML sin coordenadas" . "<br/><br/>";
		$kmlWithoutCoords++;
	}catch (UnknownMapException $e){
		echo "El mapa ".$url." es de tipo desconocido" . "<br/><br/>";
		$unknownType++;
	}catch(XmlException $e){
		echo "El mapa ".$url." no es XML bien formado" . "<br/><br/>";
		$wrongXml ++;
	}catch(PDOException $e){
		echo "Problemas con la BBDD MySQL ".$url." <br/><br/>";
	}catch(Exception $e){
		$messageText = $e;
	}
	
	try{
		$dbh->query("UPDATE CONSTANTS SET LAST_MAPMATTERS_INDEXED = ".$i);
	}catch(Exception $e){
		echo "Error al actualizar el indice de rastreo ".$e;
		die();
	}

	sleep(1);
	
	if($okMaps > 10)
		die();


}//for i



unset($dbh);
unset($statement);




