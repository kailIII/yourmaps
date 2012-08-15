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
$startMap = 0;
$endMap = 40653;

$dbh = null;
	
try{

	$dbh = new PDO("mysql:host=$hostname;dbname=$database", $username, $password, 
	array(
		PDO::ATTR_PERSISTENT => true
	));
	$dbh->query("SET CHARACTER SET utf8");
	
	$sql = "SELECT LAST_IKIMAP_INDEXED, MAX_IKIMAP FROM CONSTANTS";
	$statement = $dbh->query($sql);
	if($statement->execute()){
		if($row = $statement->fetch()){
			$startMap = $row["LAST_IKIMAP_INDEXED"];
			$endMap = $row["MAX_IKIMAP"];
		}
	}
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



for($i = $startMap; $i < $endMap; $i ++){
	session_start();
	
	$aMap = "http://www.ikimap.com/querykml?nid=".$i."&dl=t&old=0";
		
	try {
		$aMapReader = new KmlReader();
		$aMapReader->loadMap($aMap, $user, "ikimap.com", $dbh);
		
		$messageText .= "El mapa ".$aMap." cargado correctamente<br/></br>";
		
		
		unset($aMapReader);
		

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
	}
	
	$dbh->query("UPDATE CONSTANTS SET LAST_IKIMAP_INDEXED = ".$i);
	
	$_SESSION[$processId] = $messageText;
	
	session_write_close();
	
	sleep(1);
	
	session_start();
	if($_SESSION["cancel_ikimap"])
		break;
	session_write_close();
}//for

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

