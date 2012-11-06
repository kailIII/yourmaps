<?php
/*
 * www.mapmatters.org/server/96
a
http://www.mapmatters.org/server/3660


<dl class="icons">
     <dt class="info">Capabilities</dt>
     <dd class="info"><a href="http://mapas.topografia.upm.es/cgi-bin/santu/santuarios?SERVICE=WMS&amp;REQUEST=GetCapabilities">Get Capabilities</a></dd>
     <dt class="url">URL</dt>
       <dd class="url"><span style="word-wrap:break-word">http<span style="float:left;"></span>:<span style="float:left;"></span>/<span style="float:left;"></span>/mapas<span style="float:left;"></span>.topografia<span style="float:left;"></span>.upm<span style="float:left;"></span>.es<span style="float:left;"></span>/cgi<span style="float:left;"></span>-bin<span style="float:left;"></span>/santu<span style="float:left;"></span>/santuarios</span></dd>
</dl>


Con YQL, tenemos esta query:

select * from html where url="http://www.mapmatters.org/server/3660" and
      xpath='//dd/a/@href'
      
      
que devuelve:

       <results>
        <a href="http://spatial.dcenr.gov.ie/wmsconnector/com.esri.wms.Esrimap/GSI_Simple?SERVICE=WMS&amp;REQUEST=GetCapabilities">Get Capabilities</a>
    </results>

*/


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

$mapsList = $_POST['maps'];
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



$dbh = new PDO("mysql:host=$hostname;dbname=$database", $username, $password, 
array(
	PDO::ATTR_PERSISTENT => true
));
$dbh->query("SET CHARACTER SET utf8");



//$mapsArray = split("/\r\n|\r|\n/",$mapsList);
$mapsArray = explode("\n",$mapsList);

$messageText = "";

$okMaps = 0;
$alreadyExist = 0;
$wrongKml = 0;
$wrongWms = 0;
$kmlWithoutCoords = 0;
$wmsWithoutBBox = 0;
$unknownType = 0;
$wrongXml = 0;
for($i = 0; $i < sizeof($mapsArray); $i ++){
	session_start();
	
	$aMap = $mapsArray[$i];
	
	if(MapUtils::singleton()->startsWith($aMap, "#"))
		continue;
		
	if(trim($aMap) == "" )
		continue;
		
	try {
		$aMapReader = ReaderFactory::createMapReader($aMap);
		$aMapReader->loadMap($aMap, $user, "Looking4Maps", $dbh);
		
		$messageText .= "El mapa ".$aMap." cargado correctamente<br/></br>";
		
		unset($aMapReader);

	}catch (MapAlreadyExistException $e){
		//$messageText .= "El mapa ".$aMap." ya existía" . "<br/><br/>";
//		error_log("El mapa ".$aMap." ya existía" . "<br/><br/>");
		$alreadyExist++;

	}catch (NotKmlException $e){
		$messageText .= "El mapa ".$aMap." no es del tipo KML" . "<br/><br/>";
//		error_log("El mapa ".$aMap." no es del tipo kml" . "<br/><br/>");
		$wrongKml++;
	} catch (NotAuthorizedException $e){
		$messageText .= "El mapa ".$aMap." está securizado" . "<br/><br/>";

	}catch (KmlWithoutCoordinatesException $e){
		$messageText .= "El mapa ".$aMap." es un KML sin coordenadas" . "<br/><br/>";
		$kmlWithoutCoords++;
	}catch (UnknownMapException $e){
		$messageText .= "El mapa ".$aMap." es de tipo desconocido" . "<br/><br/>";
		$unknownType++;
	}catch( NotWmsException $e){
		$messageText .= "El mapa ".$aMap." no es de tipo WMS" . "<br/><br/>";
//		error_log("El mapa ".$aMap." no es de tipo WMS" . "<br/><br/>");
		$wrongWms++;
	}catch(WmsWithoutBBoxException $e){
		$messageText .= "El mapa ".$aMap." no tiene LatLongBBox " . "<br/><br/>";
		$wmsWithoutBBox++;
	}catch(XmlException $e){
		$messageText .= "El mapa WMS ".$aMap." no es XML bien formado" . "<br/><br/>";
		$wrongXml ++;
	
	}
	$_SESSION[$processId] = $messageText;
	
	session_write_close();
}//for

$messageText .= "<b>Mapas ok:".$okMaps."</b><br/>";
$messageText .= "<b>Mapas que ya existían:".$alreadyExist."</b><br/>";
$messageText .= "<b>Mapas KML incorrectos:".$wrongKml."</b><br/>";
$messageText .= "<b>Mapas KML sin coordenadas (NetworkLinks):".$kmlWithoutCoords."</b><br/>";
$messageText .= "<b>Mapas WMS incorrectos:".$wrongWms."</b><br/>";
$messageText .= "<b>Mapas WMS sin bbox:".$wmsWithoutBBox."</b><br/>";
$messageText .= "<b>URL XML mal formadas:".$wrongXml."</b><br/>";
$messageText .= "<b>Mapas de tipo desconocido:".$unknownType."</b><br/>";

$message = array(
		'message' => $messageText
);

print json_encode($message);


