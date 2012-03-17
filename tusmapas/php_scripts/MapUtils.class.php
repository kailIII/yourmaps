<?php

class MapUtils {
	private static $instance;
	
 	private function __construct()
    {
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
    
    public function getKeywords($pdo, $mapId, $mapType){
    	$solution = array();
    	$query = "select Wms_Keywords.text, Wms_Keywords.friendly_url_text, Wms_Keywords.computed from Wms_Keywords, Keywords_Services where Keywords_Services.fk_keyword_id = Wms_Keywords.pk_id and Keywords_Services.fk_wms_id = ".$mapId." and Keywords_Services.service_type = '".$mapType."'";  
		$statement = $pdo->query($query);
		if($statement->execute()){
				$i = 0;
				while ($row = $statement->fetch()){
					//process $row with text, friendly_url_text and
					array_push($solution, $row);
					$i++;
					
					if($i > 4)
					break;
				}
		}
		return $solution;
    }
    
    function startsWith($haystack, $needle){
	    $length = strlen($needle);
	    return (substr($haystack, 0, $length) === $needle);
	}

	function endsWith($haystack, $needle){
	    $length = strlen($needle);
	    $start  = $length * -1; //negative
	    return (substr($haystack, $start) === $needle);
	}
    
	
}

