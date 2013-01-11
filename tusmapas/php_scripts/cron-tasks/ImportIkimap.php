<?php

//if ($_SERVER['DOCUMENT_ROOT'] == "")
//   $_SERVER['DOCUMENT_ROOT'] = "/var/www/tusmapas/";

 if ($_SERVER['DOCUMENT_ROOT'] == "")
   $_SERVER['DOCUMENT_ROOT'] = "/home/oxmzmurs/public_html/";
   
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts". "/Config.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts". "/Util.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts". "/user_exceptions/MapAlreadyExistException.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts". "/user_exceptions/NotAuthorizedException.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts". "/user_exceptions/KmlWithoutCoordinatesException.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts". "/user_exceptions/UnknownMapException.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts". "/kml/KmlReader.class.php";

include_once "CronJob.class.php";





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
$startMap = 0;
$endMap = 40653;

$dbh = null;
	
try{

	$dbh = new PDO("mysql:host=$hostname;dbname=$database", $username, $password, 
	array(
		PDO::ATTR_PERSISTENT => true
	));
	$dbh->query("SET CHARACTER SET utf8");
	
	$cronJob = new CronJob("ikimap");
	$cronJob->save($dbh);
	
	
	$sql = "SELECT LAST_IKIMAP_INDEXED, MAX_IKIMAP FROM CONSTANTS";
	$statement = $dbh->query($sql);
	if($statement->execute()){
		if($row = $statement->fetch()){
			$startMap = $row["LAST_IKIMAP_INDEXED"];
			$endMap = $row["MAX_IKIMAP"];
		}
	}
	
	echo ("start = ".$startMap." end = ".$endMap);
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



for($i = $startMap; $i < $endMap; $i ++){
	
	
	$aMap = "http://www.ikimap.com/querykml?nid=".$i."&dl=t&old=0";
		
	try {
		$aMapReader = new KmlReader();
		$aMapReader->loadMap($aMap, $user, "ikimap.com", $dbh);
		
		echo ("El mapa ".$aMap." cargado correctamente<br/></br>");
		
		
		
		$okMaps++;
		
		unset($aMapReader);
		

	}catch (MapAlreadyExistException $e){
		$alreadyExist++;
		echo "El mapa ".$aMap." ya estaba cargado en BBDD";
	}catch (NotKmlException $e){
		echo "El mapa ".$aMap." no es del tipo KML" . "<br/><br/>";
		$wrongKml++;
	} catch (NotAuthorizedException $e){
		echo "El mapa ".$aMap." está securizado" . "<br/><br/>";
		
	}catch (KmlWithoutCoordinatesException $e){
		echo "El mapa ".$aMap." es un KML sin coordenadas" . "<br/><br/>";
		$kmlWithoutCoords++;
	}catch (UnknownMapException $e){
		echo "El mapa ".$aMap." es de tipo desconocido" . "<br/><br/>";
		$unknownType++;
	}catch(XmlException $e){
		echo "El mapa ".$aMap." no es XML bien formado" . "<br/><br/>";
		$wrongXml ++;
	}catch(PDOException $e){
		echo "Problemas con la BBDD MySQL ".$aMap." <br/><br/>";
	}catch(Exception $e){
		$messageText = $e;
	}
	
	try{
		$dbh->query("UPDATE CONSTANTS SET LAST_IKIMAP_INDEXED = ".$i);
	}catch(Exception $e){
		echo "Error al actualizar el indice de rastreo ".$e;
		die();
	}
	
	sleep(1);
	
	if($okMaps >= 50){
		echo "añadidos nuevos 50 mapas, interrumpimos el proceso";
		die();
	}
	
}//for


unset($dbh);
unset($statement);


