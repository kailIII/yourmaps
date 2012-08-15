<?php
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/Config.class.php";

$config = Config::singleton();
$username = $config->username;
$hostname = $config->hostname;
$password = $config->password;
$database = $config->database;


try {
		$dbh = new PDO("mysql:host=$hostname;dbname=$database",
		 								$username, $password, 
							 array(PDO::ATTR_PERSISTENT => true));
		 
		$dbh->query("SET CHARACTER SET utf8");
		
		$sql = "ALTER TABLE KML_SERVICES ADD SPATIAL INDEX(BBOX);";
		
		$dbh->query($sql);
		$sql = "ALTER TABLE WMS_SERVICES ADD SPATIAL INDEX(BBOX)";
		
		$dbh->query($sql);
		
		
}catch(PDOException $e){
	echo $e->getMessage();
}
$dbh = null;
?>