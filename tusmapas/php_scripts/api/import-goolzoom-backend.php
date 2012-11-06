<?php
/*OJO Copipasteado de addmap batch backend*/

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


include_once "../cron-tasks/CronJob.class.php";
include_once '../json/JSON.php';


//database params
$config = Config::singleton();
$username = $config->username;
$hostname = $config->hostname;
$password = $config->password;
$database = $config->database;

$user = $_POST['user'];
$processId = $_POST['processid'];

/*
 to avoid this error:
 [error] [client ::1] PHP Fatal error:  
 Maximum execution time of 30 seconds exceeded in /var/www/php_scripts/wms-simple/WMS.class.php on line 868, referer: http://localhost/php_scripts/add-maps-admin.php
 */
if( !ini_get('safe_mode') ){
            set_time_limit(0);
} 


if($user == null)
	$user = "anonymous";

	
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
	
	$sql = "SELECT LAST_GOOL_ZOOM_COUNTRY, LAST_GOOL_ZOOM_COUNTRY_MAP FROM CONSTANTS";
	$statement = $dbh->query($sql);
	if($statement->execute()){
		if($row = $statement->fetch()){
			$startCountry = $row["LAST_GOOL_ZOOM_COUNTRY"];
			$startMap = $row["LAST_GOOL_ZOOM_COUNTRY_MAP"];
		}
	}
	
	$messageText .= "start = ".$startMap." end = ".$endMap;
}catch(PDOException $exception){
	$messageText .= "<b>Conexión a BBDD no disponible</b><br/>";

	$message = array(
		'message' => $messageText
	);

	print json_encode($message);
	
	return;
}


$okMaps = 0;
$alreadyExist = 0;
$wrongKml = 0;
$kmlWithoutCoords = 0;
$unknownType = 0;
$wrongXml = 0;


for($i = 0; $i < sizeof($countries);$i++){

	if($i <= $startCountry){
		continue;
	}

	for($j = 0; $j < $startMap; $j++){
		continue;
	}
	
	session_start();

	$url = "http://".$countries[$i].".goolzoom.com/recursos/mapas/ObtenerMapas.aspx?country=".$countries[$i];
	$json_string =  file_get_contents($url);

	if (get_magic_quotes_gpc()) {
		$json_string = stripslashes($json_string);
	}

	$decoded_json = $json->decode($json_string,true);

	$numMaps = sizeof($decoded_json);

	for($j = 0; $j < $numMaps; $j++){
		$map = $decoded_json[$j];
		$wmsUrl = $map->u;

		try {
			
			$wmsReader->loadMap($wmsUrl, "anonymous", "goolzoom.com", $dbh);

			$messageText .= "El mapa ".$aMap." cargado correctamente<br/></br>";

			$okMaps++;

		}catch (MapAlreadyExistException $e){
			$alreadyExist++;
		}catch (NotKmlException $e){
			$messageText .= "El mapa ".$aMap." no es del tipo KML" . "<br/><br/>";
			$wrongKml++;
		} catch (NotAuthorizedException $e){
			$messageText .= "El mapa ".$aMap." está securizado" . "<br/><br/>";
		}catch (KmlWithoutCoordinatesException $e){
			$messageText .= "El mapa ".$aMap." es un KML sin coordenadas" . "<br/><br/>";
			$kmlWithoutCoords++;
		}catch (UnknownMapException $e){
			$messageText .= "El mapa ".$aMap." es de tipo desconocido" . "<br/><br/>";
			$unknownType++;
		}catch(XmlException $e){
			$messageText .= "El mapa ".$aMap." no es XML bien formado" . "<br/><br/>";
			$wrongXml ++;
		}catch(PDOException $e){
			$messageText .= "Problemas con la BBDD MySQL ".$aMap." <br/><br/>";
		}catch(Exception $e){
			$messageText = $e;
		}

		try{
			$dbh->query("UPDATE CONSTANTS SET LAST_GOOL_ZOOM_COUNTRY = ".$i.", LAST_GOOL_ZOOM_COUNTRY_MAP = ".$j);
		}catch(Exception $e){
			echo "Error al actualizar el indice de rastreo ".$e;
			die();
		}
		
		
		$_SESSION[$processId] = $messageText;
	
		session_write_close();

		sleep(1);
		
		session_start();
		if($_SESSION["cancel_ikimap"]){
			$_SESSION["cancel_ikimap"] = false;
			$messageText .= " operacion cancelada por el indice ".$i;
			session_write_close();
			break;
		}
		session_write_close();

	}//for i

}//for j


$messageText .= "<b>Mapas ok:".$okMaps."</b><br/>";
$messageText .= "<b>Mapas que ya existían:".$alreadyExist."</b><br/>";
$messageText .= "<b>Mapas KML incorrectos:".$wrongKml."</b><br/>";
$messageText .= "<b>Mapas KML sin coordenadas (NetworkLinks):".$kmlWithoutCoords."</b><br/>";
$messageText .= "<b>URL XML mal formadas:".$wrongXml."</b><br/>";
$messageText .= "<b>Mapas de tipo desconocido:".$unknownType."</b><br/>";

$message = array(
		'message' => $messageText
);

unset($dbh);
unset($statement);

print json_encode($message);


