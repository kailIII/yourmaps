<?php

class MapsKeywordRelationship {
	private $fk_keyword_id;
	private $fk_map_id;
	private $service_type;
	
	public function __construct($keywordId, $mapId, $serviceType){
		$this->fk_keyword_id = $keywordId;
		$this->fk_map_id = $mapId;
		$this->service_type = $serviceType;
	}
	
	
public function save($pdo){
			if(! $this->exist($pdo)){
				
				$sql = "INSERT INTO Keywords_Services (fk_keyword_id, fk_wms_id, service_type)".
								" VALUES(";
				$sql .= "'".$this->fk_keyword_id."'," .
						"'".$this->fk_map_id."'," .
						"'".$this->service_type."')";
					
			
				
				$count = $pdo->exec($sql);
			}//if
		}
		
		public function exist($pdo){
			$query = "select * from Keywords_Services where fk_keyword_id = ".$this->fk_keyword_id." and ".
					" fk_wms_id = ".$this->fk_map_id." and " .
					" service_type = '".$this->service_type."'";
			$statement = $pdo->query($query);
			if($statement->execute()){
				$numResults = $statement->rowCount();
				return $numResults > 0;
			}else{
				return false;
			}
		}
	
}