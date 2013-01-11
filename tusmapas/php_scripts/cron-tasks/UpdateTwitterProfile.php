<?php

if ($_SERVER['DOCUMENT_ROOT'] == "")
$_SERVER['DOCUMENT_ROOT'] = "/var/www/tusmapas/";

// if ($_SERVER['DOCUMENT_ROOT'] == "")
//   $_SERVER['DOCUMENT_ROOT'] = "/home/oxmzmurs/public_html/";
 
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts/Config.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts/MapUtils.class.php";

include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/twitter/twitter.class.php";



//database params
$config = Config::singleton();
$username = $config->username;
$hostname = $config->hostname;
$password = $config->password;
$database = $config->database;


$consumerKey = "QcYWkSfXJeuAiN1deUjNQ";
$consumerSecret = "54nhifvBL2QKIiXgCw4cEdRkFr60y99erPLLRMCiIs";
$accessToken = "359267382-r3Fh9wOjT7riKq4wubtjEe6ek4WKESkIzHHhUZGP";
$accessTokenSecret = "2G2Ek17zimwf2S4FlOhNsO093T5wAOzYpMJq3ustE";

$baseUrl = "http://www.lookingformaps.com/php_scripts/mapa.php?mapa=";


if( !ini_get('safe_mode') ){
	set_time_limit(0);
}

$dbh = null;

try{
	
	$twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
	
	$numMaps = 100000;
	
	$dbh = new PDO("mysql:host=$hostname;dbname=$database",
									$username, $password,
						array(PDO::ATTR_PERSISTENT => true));
		
	$dbh->query("SET CHARACTER SET utf8");

	$seguir = true;
	
	while($seguir){
		$mapToPromote = rand(1,$numMaps);
		$statement = $dbh->query("SELECT friendly_url, xmin, ymin, xmax, ymax, document_name FROM KML_SERVICES where pk_gid = ".$mapToPromote);
		$statement->execute();
		if($row = $statement->fetch()){
			$seguir = false;		
			$friendlyUrl = $row['friendly_url'];
				
			$xmin = $row['xmin'];
			$ymin = $row['ymin'];
			$xmax = $row['xmax'];
			$ymax = $row['ymax'];
			
			$longitude = ( $xmin + $xmax )/ 2;
			$latitude = ( $ymin + $ymax )/ 2;			
		
			$title = $row ['document_name'];
			
			$mapUtil = MapUtils::singleton();
			
			//FIXME  shorten the url map 
			$tweet = $baseUrl.$friendlyUrl." #mapas #map #geospatial #sig";
			$strlen = strlen($tweet);
			if($strlen > 140){
				$tweet = substr($tweet, 0, 140);	
			}
			$tweet = utf8_encode($tweet);
			
			$twitter->send($tweet);
			
			$toponym = $mapUtil->getToponym($latitude, $longitude);
			
			$tweet2 = "More maps from ".$toponym." http://www.lookingformaps.com/php_scripts/maps-around.php?xmin=$xmin&xmax=$xmax&ymin=$ymin&ymax=$ymax";
			$tweet2 = utf8_encode($tweet2);
			
			$twitter->send($tweet2);
			
		}
	}
	
}catch(PDOException $exception){
	$messageText .= "<b>Conexión a BBDD no disponible</b><br/>";
	echo $message;

	die();
}catch(TwitterException $e){
	echo $e;
}
