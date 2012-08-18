<?php

include_once "KmlMap.class.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts/"."MapsKeywordRelationship.class.php"; 
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts/"."Util.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/MapAlreadyExistException.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/NotKmlException.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/KmlWithoutCoordinatesException.class.php";

class KmzReader extends KmlReader {
	
	public static function readKml($kmzFile, $url, $source, $dbh){
		$zip = new ZipArchive;
    	 $res = $zip->open("my_zip_file.zip");
     	if ($res === TRUE) {
        	 
         	 $zip->close();
         
     	}else{
     		//FIXME throw new CannotOpenZipFileException
     	}
	}

	/*

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
			$oc = Util::getOpenCalais();
			$entities = $oc->getEntities($kmlFile);

			

			$keywords = array();
			
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
				}//foreach
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
				
				
			return $kmlMap;
		}else{
			throw new NotKmlException("El archivo KML no cumple la especificacion XML");
		}
	}
	*/

	}