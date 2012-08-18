<?php
class Config {
    private static $instance;
    
    var $hostname;
 	var $username;
 	var $password;
 	var $database;
 	
 	var $facebookAppId;
 	var $facebookSecret;
 	
 	public function getHostName(){
 		return $hostname;
 	}
 	
	public function getUserName(){
 		return $username;
 	}
 	
	public function getPassword(){
 		return $password;
 	}
 	
 	public function getDatabase(){
 		return $database;
 	}
    // A private constructor; previene creacion de objetos via new
    private function __construct()
    {
    	$db_configuracion = parse_ini_file("database.ini");
		$this->hostname = $db_configuracion['ip'];
		$this->username = $db_configuracion['username'];
		$this->password = $db_configuracion['password'];
		$this->database = $db_configuracion['database'];
		$this->facebookAppId = $db_configuracion['FACEBOOK_APP_ID'];
 		$this->facebookSecret = $db_configuracion['FACEBOOK_SECRET'];
    }
 
    public static function singleton()
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }
 
    public function __clone()
    {
        trigger_error('Clone no se permite.', E_USER_ERROR);
    }
 
}