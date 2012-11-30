<?php
include_once $_SERVER["DOCUMENT_ROOT"]."php_scripts/exceptions/PoiTableCreationException.php";
include_once $_SERVER["DOCUMENT_ROOT"]."php_scripts/kml/KmlReader.php";

class Poimodel extends CI_Model {
	
	public function __construct(){
		 parent::__construct();
		 $this->load->database();
	}
	
	
	public function createPoiTable($tableName, $geometryColumn) {
      
        
        $sql = "CREATE TABLE IF NOT EXISTS ".$tableName." (".
					"id INT(10) NOT NULL AUTO_INCREMENT,".
					"name TEXT NULL,".
					"description LONGTEXT NULL,".
					"".$geometryColumn." GEOMETRY NULL DEFAULT NULL,".
					"source TEXT NOT NULL COMMENT 'Data source (looking for maps, added by user, etc)',".
					"url_source TEXT NOT NULL COMMENT 'URL of the data source',".
					"photo_url TEXT NULL,".
					"WEB_URL TEXT NULL,".
					"PRIMARY KEY (id),".
        			"FULLTEXT INDEX name_description (name, description) )".
					"COLLATE='utf8_general_ci' ".
					"ENGINE=MyISAM ROW_FORMAT=DEFAULT";
        if(! $this->db->query($sql)){
        	throw new PoiTableCreationException("No se ha podido crear la tabla ".$tableName);
        }
   
        
        return true;
    }
    
    
    
	public function existPoi($tableName, $geometryColumn, $poiName, 
    						  $wktGeometry){
			
    		$this->db->select("id");
    		$this->db->where("name = '$poiName' AND MBRIntersects(GeomFromText('$wktGeometry'),$geometryColumn)");				  	
    		$query = $this->db->get($tableName); 	
    		if ($query->num_rows() > 0){
    			return true;
    		}
			return false;
	}
    
    
    public function insertPoi($tableName, $geometryColumn, $poiName, 
    						  $poiDescription, $wktGeometry, 
    						  $poiSource, $urlSource, $photoUrl = null, $webUrl = null ){
    						  	
    						  	
    	if(! $this->existPoi($tableName, $geometryColumn, $poiName, $wktGeometry)){
    	//FIXME no es mejor llamar addslashes en lugar de escape_str ???
    		$sPoiName = $this->db->escape_str($poiName, "utf-8");
    		$sPoiDescription = $this->db->escape_str($poiDescription, "utf-8");
    		
	    	$this->db->set('name', "$sPoiName");
	    	$this->db->set('description', "$sPoiDescription");
	    	$this->db->set($geometryColumn, 'GEOMFROMTEXT("'.$wktGeometry.'")',false);//false tells dont scape strings
	    	$this->db->set('source', "$poiSource");
	    	$this->db->set('url_source',"$urlSource");
	    	if($photoUrl != null)
	    		$this->db->set('photo_url', "$photoUrl");
	    		
	    	if($webUrl != null)
	    		$this->db->set('web_url',"$webUrl");
	    	
	    	$this->db->insert($tableName); 
	    	
	    	return true;
    	}
    	return false;
    }
    
    
    public function addPoisFromManyKmls($tableName, $geometryColumn, $kmlList, $sourceDescription = ""){
    	$mapsArray = explode("\n",$kmlList);
    	
    	$numKml = sizeof($mapsArray);
    	
    	for($i = 0; $i < $numKml; $i++){//meter try catch por si el kml está caido
    		$this->addPoisFromKml($tableName, $geometryColumn, $mapsArray[$i], $mapsArray[$i]);
    	}
    }
    
    
    
    public function addPoisFromKml($tableName, $geometryColumn, $kmlUrl, $sourceDescription = "" ){
    	
    	$kmlReader = new KMlReader();
    	
    	$placeMarks =  $kmlReader->loadKmlFromUrl($kmlUrl);
    	
   		$numPlaceMarks = sizeof($placeMarks);
   		
   		for($i = 0; $i < $numPlaceMarks; $i++){
   			
   			$kmlPm = $placeMarks[$i];
   			$newpoi = $this->insertPoi($tableName, $geometryColumn, $kmlPm->getName(), 
   							 $kmlPm->getDescription(), $kmlPm->getWktText(), 
   							 $sourceDescription, $kmlUrl, null, null);
   		}	
    	
    }
    
    /**
     * 
     * Search POIs for the $tableName data source
     * 
     * @param double $x
     * @param double $y
     * @param double $radius Search radius in latitude degreees (1 degree = 111,12 km, 0.1 = 11,11 km)
     */
    public function searchByGeo($tableName, $geometryColumn, $x, $y, $radius = 0.1){
    	$wktGeom = null;
    	if($radius > 0){
    		$wktGeom = "POLYGON ((".($x - $radius)." ".($y - $radius).", ".($x + $radius)." ".($y -$radius).", ".($x + $radius)." ".($y + $radius).", ".($x - $radius)." ".($y + $radius).", ".($x - $radius)." ".($y -$radius)." ))";
    	}else{
    		$wktGeom = "POINT($x $y)";
    	}
    	$this->db->where("MBRIntersects(GeomFromText('$wktGeom'),$geometryColumn)");				  	
    	$query = $this->db->get($tableName); 	
    	if ($query->num_rows() > 0){
    		return $query->result();
    	}
		return false;
    }
    
    
    public function searchByText($tableName, $searchText){
    	$this->db->where("match(name,description) against ('$searchText' in natural language mode)");
    	$query = $this->db->get($tableName); 	
    	if ($query->num_rows() > 0){
    		return $query->result();
    	}
		return false;
    }

}

