<?php
include_once $_SERVER["DOCUMENT_ROOT"]."php_scripts/exceptions/PoiTableCreationException.php";
include_once $_SERVER["DOCUMENT_ROOT"]."php_scripts/kml/KmlReader.php";


//include_once $_SERVER["DOCUMENT_ROOT"]."/poiserver/php_scripts/exceptions/PoiTableCreationException.php";
//include_once $_SERVER["DOCUMENT_ROOT"]."/poiserver/php_scripts/kml/KmlReader.php";

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



	public function existPoi($tableName, $geometryColumn, $poiName,$wktGeometry){
			
		$this->db->select("id");

		$escapedName = $this->db->escape_str($poiName, "utf-8");
		$this->db->where("name", $escapedName);

		$this->db->where("MBRIntersects(GeomFromText('$wktGeometry'),$geometryColumn)");
		$query = $this->db->get($tableName);
		if ($query->num_rows() > 0){
			return true;
		}
		return false;
	}


	public function insertPoi($tableName, $geometryColumn, $poiName,
	$poiDescription, $wktGeometry,
	$poiSource, $urlSource,
	$photoUrl = null, $webUrl = null ){
			
			
		if(! $this->existPoi($tableName, $geometryColumn, $poiName, $wktGeometry)){
			//FIXME no es mejor llamar addslashes en lugar de escape_str ???
			$sPoiName = $this->db->escape_str($poiName, "utf-8");
			$poiDescription = $poiDescription . "";//this is a trick for a problem with codeigniter escaping
				
				
			$this->db->set('name', $sPoiName);
			$this->db->set('description', $poiDescription);
			$this->db->set($geometryColumn, 'GEOMFROMTEXT("'.$wktGeometry.'")',false);//false tells dont scape strings
			$this->db->set('source', "$poiSource");
			$this->db->set('url_source',"$urlSource");
			if($photoUrl != null)
			$this->db->set('photo_url', "$photoUrl");

			if($webUrl != null)
			$this->db->set('web_url',"$webUrl");

			$this->db->insert($tableName);


			$lastId = $this->db->insert_id();

			return $lastId;
		}
		return false;
	}


	/**
	 *
	 * Do a checking for $poi_id poi of the given $layer_id
	 * for the user $user_alias
	 *
	 *
	 * @param unknown_type $checkin_table_name
	 * @param unknown_type $layer_id
	 * @param unknown_type $poi_id
	 * @param unknown_type $user_alias
	 * @param unknown_type $checkinDescription
	 */
	public function checkin($checkin_table_name, $layer_id, $poi_id, $user_alias, $checkinDescription ){

		$time = date("Y-m-d H:i:s");
			
		$data = array(
   			'layer_id' => $layer_id,
   			'poi_id' => $poi_id,
   			'check_time' => $time,
   			'user_alias' => $user_alias,
			'description' => $checkinDescription
		);

		return $this->db->insert("$checkin_table_name", $data);
	}

	public function getCheckinsByPoi($checkin_table_name, $layer_id, $poi_id, $checkTime = "all"){
		$this->db->where("layer_id", $layer_id);
		$this->db->where("poi_id", $poi_id);

		if($checkTime == "last_week"){
			$this->db->where('check_time >= DATE_ADD(NOW(),INTERVAL -1 WEEK )');
		}else if($checkTime == "last_hours"){
			$this->db->where('check_time >= DATE_ADD(NOW(),INTERVAL -3 HOUR )');
		}

		$query = $this->db->get("$checkin_table_name");

		if ($query->num_rows() > 0){
			return $query->result();
		}
		return false;
	}

	public function getCheckinsByUser($checkin_table_name, $user_alias, $layer_id, $checkTime = "all"){

		$this->db->where("user_alias", $user_alias);
		$this->db->where("layer_id", $layer_id);

		if($checkTime == "recent"){
			$this->db->where('check_time <= DATE_ADD(NOW(),INTERVAL 7 DAYS )');
		}

		$query = $this->db->get("$checkin_table_name");

		if ($query->num_rows() > 0){
			return $query->result();
		}
		return false;
	}

	public function sendchatmessage($chatTableName, $geometryColumn, $layer_id, $alias, $chat_msg, $wktGeometry){
		$time = date("Y-m-d H:i:s");

		$this->db->set("layer_id",$layer_id);
		$this->db->set($geometryColumn, 'GEOMFROMTEXT("'.$wktGeometry.'")',false);//false tells dont scape strings
		$this->db->set("check_time", $time);
		$this->db->set("user_alias", $alias);
		$this->db->set("text_msg", $chat_msg);

		return $this->db->insert("$chatTableName");
	}


	public function getChatsByGeo($tableName, $geometryColumn, $x, $y, $radius = 0.1){

		$wktGeom = null;
		if($radius > 0){
			$wktGeom = "POLYGON ((".($x - $radius)." ".($y - $radius).", ".($x + $radius)." ".($y -$radius).", ".($x + $radius)." ".($y + $radius).", ".($x - $radius)." ".($y + $radius).", ".($x - $radius)." ".($y -$radius)." ))";
		}else{
			$wktGeom = "POINT($x $y)";
		}

		$sql = "select $tableName.id, $tableName.layer_id, $tableName.user_alias, ".
				 "X($tableName.$geometryColumn) 'long', Y($tableName.$geometryColumn) lat, ".
				 " $tableName.check_time, $tableName.text_msg, ".
				 "(6371392.9 * ACOS(COS(RADIANS($y)) * COS(RADIANS(y($geometryColumn))) * COS(RADIANS(x($geometryColumn)) - RADIANS($x)) + SIN(RADIANS($y)) * SIN(RADIANS(y($geometryColumn))))) dist ".
				 "from $tableName ".
				 "where MBRIntersects(GeomFromText('$wktGeom'),$geometryColumn) ".
				 "order by check_time desc";

		$query = $this->db->query($sql);
		if ($query->num_rows() > 0){
			return $query->result_array();
		}
		return false;
	}

	public function getAllChats($tableName, $geometryColumn, $x, $y){
		$sql = "select $tableName.id, $tableName.layer_id, $tableName.user_alias, ".
				 "X($tableName.$geometryColumn) 'long', Y($tableName.$geometryColumn) lat, ".
				 " $tableName.check_time, $tableName.text_msg, ".
				 "(6371392.9 * ACOS(COS(RADIANS($y)) * COS(RADIANS(y($geometryColumn))) * COS(RADIANS(x($geometryColumn)) - RADIANS($x)) + SIN(RADIANS($y)) * SIN(RADIANS(y($geometryColumn))))) dist ".
				 "from $tableName ".
				 "order by check_time desc";

		$query = $this->db->query($sql);
		if ($query->num_rows() > 0){
			return $query->result_array();
		}
		return false;
	}
	
	public function getChatsByUser($tableName, $userAlias){
		$sql = "select $tableName.id, $tableName.layer_id, $tableName.user_alias, ".
				 " $tableName.check_time, $tableName.text_msg ".
				 "from $tableName ".
				 "where $tableName.user_alias = $userAlias order by check_time desc";

		$query = $this->db->query($sql);
		if ($query->num_rows() > 0){
			return $query->result_array();
		}
		return false;
	}



	public function addPoisFromManyKmls($tableName, $geometryColumn, $kmlList, $sourceDescription = ""){
		$numKml = sizeof($kmlList);

		for($i = 0; $i < $numKml; $i++){//meter try catch por si el kml está caido
			if(trim($kmlList[$i]) == "")
				continue;			
			try{
				$this->addPoisFromKml($tableName, $geometryColumn, $kmlList[$i], $kmlList[$i]);
			}catch(Exception $e){
				continue;
			}
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
	public function searchByGeo($tableName, $geometryColumn, $x, $y, $order = 'num_checkins', $radius = 0.1){

		$wktGeom = null;
		if($radius > 0){
			$wktGeom = "POLYGON ((".($x - $radius)." ".($y - $radius).", ".($x + $radius)." ".($y -$radius).", ".($x + $radius)." ".($y + $radius).", ".($x - $radius)." ".($y + $radius).", ".($x - $radius)." ".($y -$radius)." ))";
		}else{
			$wktGeom = "POINT($x $y)";
		}

		$orderSql = null;

		if($order == 'num_checkins'){
			$orderSql = ' order by num_checkins desc';
		}else if($order == 'distance'){
			$orderSql = ' order by dist asc';
		}


		$sql = "select $tableName.id, $tableName.name, $tableName.description, ".
				 "X($tableName.$geometryColumn) 'long', Y($tableName.$geometryColumn) lat, ".
				 " $tableName.photo_url, $tableName.web_url, ".
				 "(6371392.9 * ACOS(COS(RADIANS($y)) * COS(RADIANS(y($geometryColumn))) * COS(RADIANS(x($geometryColumn)) - RADIANS($x)) + SIN(RADIANS($y)) * SIN(RADIANS(y($geometryColumn))))) dist ,".
				 "count($tableName"."_CHECKIN.check_time) num_checkins from $tableName LEFT JOIN ".$tableName."_CHECKIN ".
				 "on $tableName.id = ".$tableName."_CHECKIN.poi_id  ".
				 "where MBRIntersects(GeomFromText('$wktGeom'),$geometryColumn) ".
				 "group by $tableName.id" . $orderSql;

		$query = $this->db->query($sql);
		if ($query->num_rows() > 0){
			return $query->result_array();
		}
		return false;
	}


	public function searchByText($tableName, $geometryColumn, $searchText){
		//		$this->db->select('id, name, description, x(geom) \'long\', y(geom) \'lat\', astext(geom) \'geometry\', source, url_source, photo_url, WEB_URL');
		//		$this->db->where("match(name,description) against ('$searchText' in natural language mode)");
		$matchText = '"'.$searchText.'"';
		$sql = "select $tableName.id, $tableName.name, $tableName.description, ".
				 "X($tableName.$geometryColumn) 'long', Y($tableName.$geometryColumn) lat,".
				 "$tableName.photo_url, $tableName.web_url, ".
				 "count($tableName"."_CHECKIN.check_time) num_checkins from $tableName LEFT JOIN $tableName"."_CHECKIN ".
				 "on $tableName.id = $tableName"."_CHECKIN.poi_id  ".
				 "where match(name,$tableName.description) against ( '$matchText' in boolean mode) ".
				 "group by $tableName.id order by num_checkins desc";
		
	

		$query = $this->db->query($sql);
		if ($query->num_rows() > 0){
			return $query->result_array();
		}
		return false;
	}

}

