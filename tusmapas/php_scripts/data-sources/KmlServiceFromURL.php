<?php
class KmlServiceFromURL {
	
	protected $dbh;
	
	public function __construct($pdo){
		$this->dbh = $pdo;	
	}
	
	public function createKmlService($url){
		
			$kmlFile = file_get_contents($url);
			
			if($kmlFile){
				
				if(strpos($kmlFile, "<html xmlns='http://www.w3.org/1999/xhtml'>")){
					echo "no se ha encontrado ".$i;
					continue;
				}
			
				echo '<pre>';
				print_r($kmlFile);
				echo '</pre>';
			
				$xml = simplexml_load_string($kmlFile, null, LIBXML_NOCDATA);
						
				if($xml){
					$ns = $xml->getDocNamespaces();
					if(isset($ns[""])){
 						$xml->registerXPathNamespace("default",$ns[""]);
					}
					$name = $xml->Document->name[0];
			
					$description = "";
					$placeMarks = $xml->xpath('//default:Placemark');
					if(sizeof($placeMarks) == 0){
						echo $i." no tiene placemarks";
						continue;
					}else if(sizeof($placeMarks) == 1){
						$description = $placeMarks[0]->description;
					}else{
						for($j = 0; $j < sizeof($placeMarks); $j++){
							$description .= $placeMarks[0]->name;
						}	
					}
					
					$coordinates = $xml->xpath('//default:coordinates');
					
					$bbox = getBoundingBox($coordinates);
					
					
					$kmlMap = new KmlMap("Wikiloc", $url, 
								$kmlFile, $name, $description,
								 $bbox[0], $bbox[1], $bbox[2], $bbox[3]);
								 
					if(! $kmlMap->exist($dbh)){
						$kmlMap->save($dbh);
					}
					
					
					//geotagging and semantical tagging
					
					
					//OpenCalais
					$entities = $oc->getEntities($kmlFile);

					//$entities is a key - value array, where
					//key is the entity type (person, url, place, etc)
					//and value is an array with many values as string
					
					
					foreach ($entities as $type => $values) {
						echo "<b>" . $type . "</b>";
						
						foreach ($values as $valueItem) {
							$mapKeyword = new MapKeyword($keyText, true);
							echo "<p>". $valueItem . "</p>";
							if(! $mapKeyword->exist($dbh)){
								$mapKeyword->save($dbh);
							}
							
							$relationship = new MapsKeywordRelationship($mapKeyword->gid, $kmlMap->gid, "KML");
							if(!$relationship->exist($dbh)){
								$relationship->save($dbh);
							}
						}//foreach valueItem
					}//foreach entities
					
					unset($mapKeyword);
					unset($relationship);
					
				// find all postal codes near by bbox centroid
					$xcent = ($bbox[0] + $bbox[2]) / 2;
					$ycent = ($bbox[1] + $bbox[3]) / 2;
					
					$postalCodes = $geo->findNearbyPostalCodes(array(
					    'lat'     => $ycent,
					    'lng'     => $xcent,
					    'radius'  => 10, // 10km
					    'maxRows' => 100
					));
					
					foreach ($postalCodes as $code) {
						
						
						$mapKeyword = new MapKeyword($code->placeName, true);
						
						echo "<p>". $code . "</p>";
						if(! $mapKeyword->exist($dbh)){
							$mapKeyword->save($dbh);
						}
						
						$relationship = new MapsKeywordRelationship($mapKeyword->gid, $kmlMap->gid, "KML");
						if(!$relationship->exist($dbh)){
							$relationship->save($dbh);
						}
					
						
						
					    printf(" - %s (%s)\n", $code->postalCode, $code->placeName);
					}
					
										
					unset($kmlFile);
					unset($bbox);
					unset($postalCodes);
					unset($entities);
					unset($coordinates);
					unset($placeMarks);
					unset($xcent);
					unset($ycent);
				
					sleep(2);
			
			}//if xml
		}//if kmlfile
	}	
}