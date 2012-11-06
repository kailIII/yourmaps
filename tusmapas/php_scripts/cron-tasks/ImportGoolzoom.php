<?php

//if ($_SERVER['DOCUMENT_ROOT'] == "")
//$_SERVER['DOCUMENT_ROOT'] = "/var/www/tusmapas/";

if ($_SERVER['DOCUMENT_ROOT'] == "")
   $_SERVER['DOCUMENT_ROOT'] = "/home/oxmzmurs/public_html/";

include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/Config.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/Util.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/MapAlreadyExistException.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/NotAuthorizedException.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/KmlWithoutCoordinatesException.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/UnknownMapException.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/wms-simple/WmsReader.class.php";


include_once "CronJob.class.php";
require_once '../json/JSON.php';



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
$json = null;


$countries = array('es','co','mx','pt','ad','de','be','ca',
			'us','au','br','ar','ec','uy','no','it','fr','cz','nl','co.uk','ch',
			'at','dk','pl','eu','lv','se','pe','ro','ve','cl','hr',
			'fi','ie');

$wmsReader = new WmsReader();



try{

	$dbh = new PDO("mysql:host=$hostname;dbname=$database", $username, $password,
	array(
	PDO::ATTR_PERSISTENT => true
	));
	$dbh->query("SET CHARACTER SET utf8");


	$json = new Services_JSON_Ext();
	$cronJob = new CronJob("goolzoom");
	$cronJob->save($dbh);

	/*
	 sql de actualización de constantes

	 ALTER TABLE `CONSTANTS`  ADD COLUMN `LAST_GOOL_ZOOM_COUNTRY` INT(10) NULL DEFAULT '0' AFTER `LAST_MAP_KEYWORDS_CHECKED`,  ADD COLUMN `LAST_GOOL_ZOOM_COUNTRY_MAP` INT(10) NULL DEFAULT '0' AFTER `LAST_GOOL_ZOOM_COUNTRY`;
	 * */
	$sql = "SELECT LAST_GOOL_ZOOM_COUNTRY, LAST_GOOL_ZOOM_COUNTRY_MAP FROM CONSTANTS";
	$statement = $dbh->query($sql);
	if($statement->execute()){
		if($row = $statement->fetch()){
			$startCountry = $row["LAST_GOOL_ZOOM_COUNTRY"];
			$startMap = $row["LAST_GOOL_ZOOM_COUNTRY_MAP"];
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

for($i = 0; $i < sizeof($countries);$i++){

	if($i < $startCountry){
		continue;
	}

	

	$url = "http://".$countries[$i].".goolzoom.com/recursos/mapas/ObtenerMapas.aspx?country=".$countries[$i];
	$json_string =  file_get_contents($url);

	if (get_magic_quotes_gpc()) {
		$json_string = stripslashes($json_string);
	}

	$decoded_json = $json->decode($json_string,true);

	$numMaps = sizeof($decoded_json);

	for($j = 0; $j < $numMaps; $j++){
		
		if($j < $startMap)
			continue;
		$map = $decoded_json[$j];
		$wmsUrl = $map->u;

		try {
			
			$wmsReader->loadMap($wmsUrl, "anonymous", "goolzoom.com", $dbh);

			$messageText .= "El mapa ".$wmsUrl." cargado correctamente<br/></br>";

			$okMaps++;

		}catch (MapAlreadyExistException $e){
			$alreadyExist++;
		}catch (NotKmlException $e){
			$messageText .= "El mapa ".$wmsUrl." no es del tipo KML" . "<br/><br/>";
			$wrongKml++;
		} catch (NotAuthorizedException $e){
			$messageText .= "El mapa ".$wmsUrl." está securizado" . "<br/><br/>";
		}catch (KmlWithoutCoordinatesException $e){
			$messageText .= "El mapa ".$wmsUrl." es un KML sin coordenadas" . "<br/><br/>";
			$kmlWithoutCoords++;
		}catch (UnknownMapException $e){
			$messageText .= "El mapa ".$wmsUrl." es de tipo desconocido" . "<br/><br/>";
			$unknownType++;
		}catch(XmlException $e){
			$messageText .= "El mapa ".$wmsUrl." no es XML bien formado" . "<br/><br/>";
			$wrongXml ++;
		}catch(PDOException $e){
			$messageText .= "Problemas con la BBDD MySQL ".$wmsUrl." <br/><br/>";
		}catch(Exception $e){
			$messageText = $e;
		}

		try{
			$dbh->query("UPDATE CONSTANTS SET LAST_GOOL_ZOOM_COUNTRY = ".$i.", LAST_GOOL_ZOOM_COUNTRY_MAP = ".$j);
		}catch(Exception $e){
			echo "Error al actualizar el indice de rastreo ".$e;
			die();
		}

		sleep(1);

	}//for i

}//for j



unset($dbh);
unset($statement);



