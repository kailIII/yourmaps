<?php
require_once '../Config.class.php';
require_once 'WMS.class.php';
require_once '../WmsMap.class.php';

$config = Config::singleton();
$username = $config->username;
$hostname = $config->hostname;
$password = $config->password;

try {

	$pdo = new PDO("mysql:host=$hostname;dbname=tusmapas", $username, $password);	
	$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$pdo->query("SET CHARACTER SET utf8");

	$wms = new WMS("http://geoportal.invemar.org.co/cgi-bin/iabin_ecosistemas?","curl");
	$wrap = new WmsMap($wms);
	$wrap->save($pdo);
	
	$wrap->searchOpenCalaisKeywords($pdo);
	$wrap->searchGeonamesKeywords($pdo);


}catch(PDOException $e){
		echo $e->getMessage();
}