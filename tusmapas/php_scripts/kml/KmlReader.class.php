<?php

include_once "KmlMap.class.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts/"."MapsKeywordRelationship.class.php"; 
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts/"."Util.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/MapAlreadyExistException.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/NotKmlException.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/KmlWithoutCoordinatesException.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/IMapReader.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/open-calais/opencalais.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/Geonames/Services/Exception.php";



class KmlReader implements IMapReader {

	/**
	 * Computes bounding box from a  wkt coordinate array
	 * @param unknown_type $coordinates
	 * @return array with xmin, ymin, xmax, ymax
	 */
	public static function getBoundingBox($coordinates){
		$xmin = 1000;
		$ymin = 1000;
		$xmax = -1000;
		$ymax = -1000;

		for($i = 0; $i < sizeof($coordinates);$i++){
			$coordStr = $coordinates[$i];
			$coordsArray = split(",",$coordStr);

			$x = $coordsArray[0];
			$y = $coordsArray[1];
				
			if($x > $xmax)
			$xmax = $x;
			if($x < $xmin)
			$xmin = $x;
			if($y > $ymax)
			$ymax = $y;
			if($y < $ymin)
			$ymin = $y;
		}

		return array($xmin, $ymin,$xmax,$ymax);
	}
	
	
	public function loadMap($url, $userCreator, $source, $pdo){
		return KmlReader::loadKml($url, null, $userCreator, $source, $pdo);
	}


	public static function readKml($kmlFile, $url, $source, $dbh){
		if(strpos($kmlFile, "<html xmlns='http://www.w3.org/1999/xhtml'>")){
			throw new NotKmlException("No es un documento KML / XML, sino un documento HTML");
		}
			
		$xml = simplexml_load_string($kmlFile, null, LIBXML_NOCDATA);


		$name = "";
		$description = "";
		$coordinates;
		$coordinates_bbox;

		if($xml){
				
			$ns = $xml->getDocNamespaces();
			if(isset($ns[""])){
				$xml->registerXPathNamespace("default",$ns[""]);
			}
			$name = $xml->Document->name[0];
			
			if($name == null)
				$name = "Documento sin nombre - ver descripción";
				
				
			$placeMarks = $xml->xpath('//default:Placemark');
			if(sizeof($placeMarks) == 0){
				throw new KmlWithoutCoordinatesException("Documento KML sin PlaceMarks.");
			}else if(sizeof($placeMarks) == 1){
				$description = $placeMarks[0]->description;
			}else{
				for($j = 0; $j < sizeof($placeMarks); $j++){
					$description .= ($placeMarks[$j]->name . "\n");
				}
			}
			
			if($description == null || $description == "")
				$description = "Documento sin descripción";
				
			$coordinates = $xml->xpath('//default:coordinates');
			
			if($coordinates == null || sizeof($coordinates) == 0)
				throw new KmlWithoutCoordinatesException("Documento KML sin coordenadas");
				
			$coordinates_bbox = KmlReader::getBoundingBox($coordinates);
				
				
			$kmlMap = new KmlMap($source,
				$url,
				$kmlFile,
				$name,
				$description,
				$coordinates_bbox[0],
				$coordinates_bbox[1],
				$coordinates_bbox[2],
				$coordinates_bbox[3]
			);
			$kmlMap->setUser("Admin");


			if($dbh != null){
				if(! $kmlMap->exist($dbh)){
					$kmlMap->save($dbh);
				}else{
					throw new MapAlreadyExistException();
				}
			}
				
				
			//OpenCALAIS tagging
			$mapKeyword = null;
			$relationship = null;
			$keywords = array();
			
			try{
				$oc = Util::getOpenCalais();
				$entities = $oc->getEntities($kmlFile);

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
				
								$relationship = new MapsKeywordRelationship($mapKeyword->getGid(), $kmlMap->getGid(), "KML");
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
			}	
				
				
			try{	

				//Geonames tagging
				// find all postal codes near by bbox centroid
					
				$geo = Util::getGeoNames();
					
				$xcent = ($coordinates_bbox[0] + $coordinates_bbox[2]) / 2;
				$ycent = ($coordinates_bbox[1] + $coordinates_bbox[3]) / 2;
				
				$radius = $coordinates_bbox[2] - $coordinates_bbox[0];
			
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
		
							$relationship = new MapsKeywordRelationship($mapKeyword->getGid(), $kmlMap->getGid(), "KML");
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
			}
				
			$kmlMap->setKeywords($keywords);
				

			unset($kmlFile);
			unset($bbox);
			unset($postalCodes);
			unset($entities);
			unset($coordinates);
			unset($placeMarks);
			unset($xcent);
			unset($ycent);
			
			unset($mapKeyword);
			unset($relationship);
				
				
			return $kmlMap;
		}else{
			throw new NotKmlException("El archivo KML no cumple la especificacion XML");
		}
	}

		/**
		 *
		 * Analyzes xml content of a given url and parses kml information
		 *
		 * @param string that represents a url $url
		 * @param if not null, proxy information with connection params $cxContent
		 * @param string that describes the web portal which publishes kml data (ej. "Wikiloc") $source
		 * @param boolean value, if true saves in database the created kmlmap instance $persist
		 *
		 * @throws Exception
		 */
		public static function loadKml($url, $cxContent, $userCreator, $source, $dbh){

			$kmlFile = null;

			if($cxContent == null){
				$kmlFile = file_get_contents($url);
			} else {
				$kmlFile = file_get_contents($url, False, $cxContext);
			}

			if($kmlFile){
					
				if(strpos($kmlFile, "<html xmlns='http://www.w3.org/1999/xhtml'>")){
					throw new NotKmlException("No es un documento KML / XML, sino un documento HTML");
				}
					
				$xml = simplexml_load_string($kmlFile, null, LIBXML_NOCDATA);


				$name = "";
				$description = "";
				$coordinates;
				$coordinates_bbox;

				if($xml){
						
					$ns = $xml->getDocNamespaces();
					if(isset($ns[""])){
						$xml->registerXPathNamespace("default",$ns[""]);
					}
					$name = $xml->Document->name[0];
					if($name == null)
						$name = "";
					
					$folders = $xml->xpath('//default:Folder');
					for($i = 0; $i < sizeof($folders); $i ++){
						if(strpos($folders[$i]->name, "Mapas www.ikiMap.com") === false)
						{
							$name .= $folders[$i]->name." ";
							$description .= $folders[$i]->description." ";	
						}
					}
						
					if($name == null)
						$name = Util::get_url_file($url);
						
					
						
						
					$placeMarks = $xml->xpath('//default:Placemark');
					if(sizeof($placeMarks) == 0){
						throw new KmlWithoutCoordinatesException("Documento KML sin PlaceMarks.");
					}
					
					if(($description == null || $description == "") && sizeof($placeMarks) == 1){
						$description = $placeMarks[0]->description;
					}else if($description == null && sizeof($placeMarks) == 1)  {
						for($j = 0; $j < sizeof($placeMarks); $j++){
							$description .= ($placeMarks[$j]->name . "\n");
						}
					}
						
					$coordinates = $xml->xpath('//default:coordinates');
					if($coordinates == null || sizeof($coordinates) == 0)
						throw new KmlWithoutCoordinatesException("Documento KML sin coordenadas");
					
					
					$coordinates_bbox = KmlReader::getBoundingBox($coordinates);
						
						
					$kmlMap = new KmlMap($source,
						$url,
						$kmlFile,
						$name,
						$description,
						$coordinates_bbox[0],
						$coordinates_bbox[1],
						$coordinates_bbox[2],
						$coordinates_bbox[3]
						);
					$kmlMap->setUser($userCreator);


					if($dbh != null){
						if(! $kmlMap->exist($dbh)){
							$kmlMap->save($dbh);
						}else{
							throw new MapAlreadyExistException($url." already exist");
						}
					}
						
						
					//OpenCALAIS tagging
					$mapKeyword = null;
					try{
						$oc = Util::getOpenCalais();
						$entities = $oc->getEntities($kmlFile);
	
						//$entities is a key - value array, where
						//key is the entity type (person, url, place, etc)
						//and value is an array with many values as string
							
						$keywords = array();
							
						if(is_array($entities)){
							foreach ($entities as $type => $values) {
		
								if($type == "Organization" || $type  == "Company"  || $type == "Industry Term" || $type == "Phone Number"  )
								continue;
		
								foreach ($values as $valueItem) {
									$mapKeyword = new MapKeyword($valueItem, true);
										
									if($dbh != null){
										if(! $mapKeyword->exist($dbh)){
											$mapKeyword->save($dbh);
										}
		
										$relationship = new MapsKeywordRelationship($mapKeyword->getGid(), $kmlMap->getGid(), "KML");
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
						unset($entities);
					}catch(OpenCalaisException $e){
					}
						
						
						

					//Geonames tagging
					// find all postal codes near by bbox centroid
					try{	
						$geo = Util::getGeoNames();
							
						$xcent = ($coordinates_bbox[0] + $coordinates_bbox[2]) / 2;
						$ycent = ($coordinates_bbox[1] + $coordinates_bbox[3]) / 2;
							
						$radius = $coordinates_bbox[2] - $coordinates_bbox[0];
			
						if($radius > 30)
							$radius = 30;
					
						$postalCodes = $geo->findNearbyPostalCodes(array(
							    'lat'     => $ycent,
						    	'lng'     => $xcent,
						    	'radius'  => $radius, // 10km
						    	'maxRows' => 10
						));
						
						
						if(is_array($postalCodes)){	
							foreach ($postalCodes as $code) {
		
								$mapKeyword = new MapKeyword($code->placeName, true);
		
								if($dbh != null){
									if(! $mapKeyword->exist($dbh)){
										$mapKeyword->save($dbh);
									}
		
									$relationship = new MapsKeywordRelationship($mapKeyword->getGid(), $kmlMap->getGid(), "KML");
									if(!$relationship->exist($dbh)){
										$relationship->save($dbh);
									}
								}//if persist
		
								array_push($keywords, $mapKeyword);
		
								unset($mapKeyword);
							}//foreach
						}
						}catch(Services_GeoNames_Exception $e){
					}
				
						
					$kmlMap->setKeywords($keywords);
						
					unset($xml);
					unset($kmlFile);
					unset($bbox);
					unset($postalCodes);
					unset($entities);
					unset($coordinates);
					unset($placeMarks);
					unset($xcent);
					unset($ycent);
					unset($values);
						
						
					return $kmlMap;

						
				}else{
					throw new NotKmlException("No se ha podido analizar el documento KML. Verifique que se trata de un XML bien formado");
				}
					
			}else{
				throw new NotKmlException("No se ha podido acceder al archivo (puede ser un problema de red o de URL incorrecta)");
			}

		}
		
		public function loadMapFromDB($mapId, $pdo){
			
			$solution = null;
			
			$query = "select origen, url_origen, kml_content, document_name, description, xmin, ymin, xmax, ymax from KML_SERVICES where pk_gid = ".$mapId;
		
			$statement = $pdo->query($query);
			if($statement){
				if($statement->execute()){
					if($row = $statement->fetch()){
						$origen = $row["origen"];
						$urlOrigen = $row["url_origen"];
						$kmlContent = $row["kml_content"];
						$documentName = $row["document_name"];
						$description = $row["description"];
						$xmin = $row["xmin"];
						$ymin = $row["ymin"];
						$xmax = $row["xmax"];
						$ymax = $row["ymax"];
						$solution = new KmlMap($origen, $urlOrigen, 
									$kmlContent, $documentName, 
									$description, $xmin, $ymin,
									 $xmax, $ymax);
						$solution->setGid($mapId);
					}//if fetch
				}//if execute
			}//if statement
			
			return $solution;
		}
	}