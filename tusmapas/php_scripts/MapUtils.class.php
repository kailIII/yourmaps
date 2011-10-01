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
    	$query = "select Wms_Keywords.text, Wms_Keywords.friendly_url_text, Wms_Keywords.computed from Wms_Keywords, Keywords_Services where Keywords_Services.fk_keyword_id = Wms_Keywords.pk_id and Keywords_Services.fk_wms_id = ".$mapId." and Keywords_Services.service_type = ".$mapType;  
		$statement = $pdo->query($query);
		if($statement->execute()){
			$numResults = $statement->rowCount();
			if ($numResults > 0){
				$sql = "SELECT PK_GID FROM KML_SERVICES WHERE URL_ORIGEN = '".$this->urlOrigen."'";
				$statement = $pdo->query($sql);
				if($statement->execute()){
					if($row = $statement->fetch()){
						$this->gid = $row["PK_GID"];
					}
				return true;
				}
			}
		}
		return false;
    	
    	
    	
    }
	
}

