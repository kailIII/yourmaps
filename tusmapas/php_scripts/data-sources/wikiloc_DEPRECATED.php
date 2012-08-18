<?php
/*
 * Very important: PHP.INI must have ENABLED allow_url_fopen = On
 * 
 * */
//Esta continuamente actualizandose (1000 tracks diarios!!!!)
//
//Aquí se listan los resultados:
//
//http://es.wikiloc.com/wikiloc/find.do
//
//
//y se pueden descargar aqui en formato KML:
//
//http://es.wikiloc.com/wikiloc/geoServer.do?format=kml&id=1&includeDisplayed=true
//
//al 
//411684
//
//http://es.wikiloc.com/wikiloc/geoServer.do?format=kml&id=410053&includeDisplayed=true

include('../Config.class.php');
include ('../open-calais/opencalais.php');
include ('../Geonames/Services/GeoNames.php');

require_once '../kml/KmlMap.class.php';
require_once '../MapKeyword.class.php';
require_once '../MapsKeywordRelationship.class.php';

function getBoundingBox($coordinates){
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


$apikey = "q5nfs3a72xqnsqv9g866r5za";
$oc = new OpenCalais($apikey);

$geo = new Services_GeoNames("alvaro.zabala");

$config = Config::singleton();
$username = $config->username;
$hostname = $config->hostname;
$password = $config->password;

try {
	
			$aContext = array(
    			'http' => array(
        		'proxy' => 'gw-par-rp.rel.com.ua:3128',
        		'request_fulluri' => true,
    			),
			);
			$cxContext = stream_context_create($aContext);
	
		
		$dbh = new PDO("mysql:host=$hostname;dbname=tusmapas", $username, $password);
//		$statement = $dbh->query("select * from WMS_SERVICES where match(service_title,service_abstract, keywords_list, layer_names, layer_titles) against ('".$keyword."') IN NATURAL LANGUAGE MODE");
		
		$dbh->query("SET CHARACTER SET utf8");
		
		for($i = 799; $i <= 411684; $i ++){
		
			$url = "http://es.wikiloc.com/wikiloc/geoServer.do?format=kml&id=".$i."&includeDisplayed=true";
			
//			$kmlFile = file_get_contents($url);
			
			$kmlFile = file_get_contents($url, False, $cxContext);
			
			if($kmlFile){
				
				if(strpos($kmlFile, "<html xmlns='http://www.w3.org/1999/xhtml'>")){
					echo "no se ha encontrado ".$i."<br>";
					sleep(3);
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
						echo $i." no tiene placemarks"."<br>";
						
						sleep(15);
						
						continue;
					}else if(sizeof($placeMarks) == 1){
						$description = $placeMarks[0]->description;
					}else{
						$lastDescription = "";
						for($j = 0; $j < sizeof($placeMarks); $j++){
							
							//Revisar para que no aparezcan duplicados
							if( $placeMarks[0]->name != $lastDescription)
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
					}else{
						echo $name . " ya existia en bbdd";
					}
					
					
					//geotagging and semantical tagging
					
					
					//OpenCalais
					try{
						$entities = $oc->getEntities($kmlFile);
	
						//$entities is a key - value array, where
						//key is the entity type (person, url, place, etc)
						//and value is an array with many values as string
						
						
						foreach ($entities as $type => $values) {
							echo "<b>" . $type . "</b>";
							
							foreach ($values as $valueItem) {
								$mapKeyword = new MapKeyword($valueItem, true);
								echo "<p>". $valueItem . "</p>";
								if(! $mapKeyword->exist($dbh)){
									$mapKeyword->save($dbh);
								}
								
								$relationship = new MapsKeywordRelationship($mapKeyword->getGid(), $kmlMap->getGid(), "KML");
								if(!$relationship->exist($dbh)){
									$relationship->save($dbh);
								}
							}//foreach valueItem
						}//foreach entities
						
						unset($mapKeyword);
						unset($relationship);
					}catch(OpenCalaisException $e){
						echo $e->getMessage();
    				}
					
				// find all postal codes near by bbox centroid
					$xcent = ($bbox[0] + $bbox[2]) / 2;
					$ycent = ($bbox[1] + $bbox[3]) / 2;
					
					$postalCodes = $geo->findNearbyPostalCodes(array(
					    'lat'     => $ycent,
					    'lng'     => $xcent,
					    'radius'  => 4, // 10km
					    'maxRows' => 10
					));
					
					foreach ($postalCodes as $code) {
						
						
						$mapKeyword = new MapKeyword($code->placeName, true);
						
						echo "<p>". $code->placeName . "</p>";
						if(! $mapKeyword->exist($dbh)){
							$mapKeyword->save($dbh);
						}
						
						$relationship = new MapsKeywordRelationship($mapKeyword->getGid(), $kmlMap->getGid(), "KML");
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
				
					sleep(15);
				}
			}else{
				echo "connection time out ".$i."<br>";
			
				sleep(15);
				
				continue;
			}
			
		}
	}catch(PDOException $e){
		echo $e->getMessage();
    }
?>;	
		
					
