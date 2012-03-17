<?php
/**
 * This script processes broken maps reports from map.php.
 * 
 * When there is a problem with an online map (wms, kml, etc) users can report this,
 * and this php receives broken map id
 * 
 * 
 */
require_once 'Config.class.php';
require_once 'User.class.php';
require_once 'facebook-sdk/facebook.php';
require_once 'Util.class.php';



//database params
$config = Config::singleton();
$username = $config->username;
$hostname = $config->hostname;
$password = $config->password;


//facebook authentication
define('FACEBOOK_APP_ID', '154914597939036');
define('FACEBOOK_SECRET', 'aa9a1c5c6bd7384b7f5df3f8d84c49ad');

$brokenMap = $_GET['map'];
$mapType = $_GET['type'];


try {
	
	$dbh = new PDO("mysql:host=$hostname;dbname=tusmapas", $username, $password);
	$dbh->query("SET CHARACTER SET utf8");

	$facebook = new Facebook(array(
	  'appId' => FACEBOOK_APP_ID,
	  'secret' => FACEBOOK_SECRET,
	));
	
	$userId = $facebook->getUser();
	if ($userId) {
		try {
		  	
		    $user_profile = $facebook->api('/me');
		   
		    $fcbName = $user_profile["name"];
		    $fcbLink = $user_profile["link"];
		    $fcbLocale = $user_profile["locale"];
		    $fcbMail = $user_profile["email"];
		    $fcbVerified = $user_profile["verified"];
		    $user = new User($fcbName, $fcbMail, 
		    				null, $fcbVerified,
		    				 $facebook->getAccessToken(), "facebook");
		    $user->exist($dbh);//we load user id
		    
		    $userId = $user->getId();
		 } catch (FacebookApiException $e) {
		    error_log($e);
		    $userId = 1;
		    $user = null;
		 }
	} //if $userId
	else{
		$userId = 1;//anonymous
	}
		   
	
	//TODO Verificar antes si el usuario ha reportado un problema sobre ese mapa
	$statement = $dbh->query( "insert into BROKEN_MAPS_REPORTS(map_type, map_url, user_id) values(' "
			.$mapType."','".$brokenMap."',".$userId.")");
	$statement->execute();
	
	$message = array('message' => 'Operacion reportada con éxito. Revisaremos el mapa '.$brokenMap);
	
	print json_encode($message);//no ha habido errores
		
}catch(PDOException $e){
	$message = array('message' => 'Ha habido problemas al intentar reportar el problema. Por favor, inténtelo más tarde');
	print json_encode($e->getMessage());
}
$dbh = null;
$userId = null;
$user = null;
?>

