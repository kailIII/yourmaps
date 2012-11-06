<?php
class KeywordManager {
	 private static $instance;
	 
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