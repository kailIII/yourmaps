<?php
//Lanza un script que, para cada keyword, comprueba si tiene mapas asociados, si ya
//no existen borra la keyword.

//seguidamente, para cada mapa, recalcula las keywords de  calais y geonames y borra las calculadas
//no coincidentes

include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts/". 'Config.class.php';
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts/".'Util.class.php';
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts/".'MapsKeywordRelationship.class.php';
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts/". 'MapKeyword.class.php';
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/kml/KmlReader.class.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/wms-simple/WmsReader.class.php";

include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/open-calais/opencalais.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/WmsWithoutBBoxException.class.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/NotWmsException.class.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/NotAuthorizedException.class.php";



$config = Config::singleton();
$username = $config->username;
$hostname = $config->hostname;
$password = $config->password;
$database = $config->database;

$wmsReader = new WmsReader();
$kmlReader = new KmlReader();


	try {
		
		$dbh = new PDO("mysql:host=$hostname;dbname=$database", $username, $password, array(
   			 PDO::ATTR_PERSISTENT => true
		));
//		$statement = $dbh->query("select * from WMS_SERVICES where match(service_title,service_abstract, keywords_list, layer_names, layer_titles) against ('".$keyword."') IN NATURAL LANGUAGE MODE");
		
		$dbh->query("SET CHARACTER SET utf8");
		
		$startMap = 0;
		$sql = "SELECT LAST_MAP_KEYWORDS_CHECKED FROM CONSTANTS";
		$statement = $dbh->query($sql);
		if($statement->execute()){
			if($row = $statement->fetch()){
				$startMap = $row["LAST_MAP_KEYWORDS_CHECKED"];
			}
		}

	
		$query = "select @rownum:= @rownum+1 AS rownum, MAPS.* FROM (select @rownum := 0)as r, (SELECT WMS_SERVICES.pk_id as map_id, 'WMS' as type from WMS_SERVICES union all select pk_gid as map_id, 'KML' as type from KML_SERVICES) AS MAPS having rownum > ".$startMap." order by rownum asc"; 
		
		$statement = $dbh->query($query);
	
		if($statement && $statement->execute()){
				while ($r = $statement->fetch()){
					session_start();
					
					
					$mapId = $r['map_id'];
					$mapType = $r['type'];
					$rowNum = $r['rownum'];
			
//					$keywordsId = MapsKeywordRelationship::getRelated($mapId, $mapType, $dbh);
					
					//First of all, we delete relationships
					MapsKeywordRelationship::deteleRelationships($mapId, $mapType, $dbh);	

					if($mapType == "WMS"){
						$wmsWords = array();
						$openCalaisWords = array();
						$geonamesWords = array();
						try{
							$wrap = $wmsReader->loadMapFromDB($mapId, $dbh);
						}catch(XmlException $e){
							$e->getTraceAsString();
							unset($wrap);
							continue;
						}catch(NotWmsException $e){
							$e->getTraceAsString();
							unset($wrap);
							continue;
						}catch(NotAuthorizedException $e){
							$e->getTraceAsString();
							unset($wrap);
							continue;
						}
						$wmsWords = $wrap->getWms()->getKeywords();
						$numWmsWords = sizeof($wmsWords);
						for($i = 0; $i < $numWmsWords; $i++){
							$mapKeyword = new MapKeyword($wmsWords[$i], true);
				
							if($dbh != null){
								if(! $mapKeyword->exist($dbh)){
									$mapKeyword->save($dbh);
								}
			
								$relationship = new MapsKeywordRelationship($mapKeyword->getGid(), $mapId, "WMS");
								if(!$relationship->exist($dbh)){
									$relationship->save($dbh);
								}
								unset($relationship);
								unset($mapKeyword);
							}//if
						}//for
				
						try{
							$openCalaisWords = $wrap->searchOpenCalaisKeywords($dbh);
								
						}//FIXME process OpenCalaisException and Services_Geonames_Exception
						catch(OpenCalaisException $e){
							$e->getTraceAsString();
						}
						try{
							$geonamesWords = $wrap->searchGeonamesKeywords($dbh);
						}catch(Services_GeoNames_Exception $e){
							$e->getTraceAsString();
						}catch(WmsWithoutBBoxException $e){
							$e->getTraceAsString();
						}
							
						$mergedKeywords = array_merge($wmsWords,$openCalaisWords,$geonamesWords);
		
						$wrap->setKeywords($mergedKeywords);
		
						unset($openCalaisWords);
						unset($geonamesWords);
						unset($wmsWords);
						unset($mergedKeywords);
					}else if($mapType == "KML"){
						$kml = $kmlReader->loadMapFromDB($mapId, $dbh);
						
						
						
						$keywords = array();
						
						try{
							$oc = Util::getOpenCalais();
							$entities = $oc->getEntities($kml->getKmlContent());
			
							//$entities is a key - value array, where
							//key is the entity type (person, url, place, etc)
							//and value is an array with many values as string
							if($entities != null){
									
								foreach ($entities as $type => $values) {
							
									if($type == "Organization" || $type  == "Company"  || $type == "Industry Term" || $type == "Phone Number"  )
										continue;
							
									foreach ($values as $valueItem) {
										$mapKeyword = new MapKeyword($valueItem, true);
											
										if($dbh != null){
											if(! $mapKeyword->exist($dbh)){
												$mapKeyword->save($dbh);
											}
							
											$relationship = new MapsKeywordRelationship($mapKeyword->getGid(), $kml->getGid(), "KML");
											if(!$relationship->exist($dbh)){
												$relationship->save($dbh);
											}
							
											unset($relationship);
										}//if persist
											
										array_push($keywords, $mapKeyword);
											
										unset($mapKeyword);
											
											
									}//foreach valueItem
								}//foreach entities
							}
						}catch(OpenCalaisException $e){
							$e->getTraceAsString();
						}	
							
							
						try{	
			
							//Geonames tagging
							// find all postal codes near by bbox centroid
								
							$geo = Util::getGeoNames();
								
							$xcent = ($kml->getXmax() + $kml->getXmin()) / 2;
							$ycent = ($kml->getYmin() + $kml->getYmax()) / 2;
							
							$radius = $kml->getYmax() - $kml->getYmin();
						
							if($radius > 30)
								$radius = 30;
								
							$postalCodes = $geo->findNearbyPostalCodes(array(
									    'lat'     => $ycent,
									    'lng'     => $xcent,
									    'radius'  => $radius, // 10km
									    'maxRows' => 10
							));
							
							if($postalCodes != null){
								
								foreach ($postalCodes as $code) {
					
									$mapKeyword = new MapKeyword($code->placeName, true);
					
									if($dbh != null){
										if(! $mapKeyword->exist($dbh)){
											$mapKeyword->save($dbh);
										}
					
										$relationship = new MapsKeywordRelationship($mapKeyword->getGid(), $kml->getGid(), "KML");
										if(!$relationship->exist($dbh)){
											$relationship->save($dbh);
										}
									}//if persist
					
									array_push($keywords, $mapKeyword);
					
									unset($mapKeyword);
									unset($relationship);
								}//foreach
							}
						}catch(Services_GeoNames_Exception $e){
							$e->getTraceAsString();
						}
								
					}//if mapType = kml
					
					$dbh->query("UPDATE CONSTANTS SET LAST_MAP_KEYWORDS_CHECKED = ".$rowNum);
					
					session_write_close();
					
		        }//while
		           
		}//if
}catch(PDOException $e){
	echo $e->getMessage();
}
?>