<?php


include_once $_SERVER["DOCUMENT_ROOT"]."php_scripts/exceptions/KmlNotRetrievedException.php";
include_once $_SERVER["DOCUMENT_ROOT"]."php_scripts/exceptions/NotKmlException.php";
include_once $_SERVER["DOCUMENT_ROOT"]."php_scripts/exceptions/KmlWithoutCoordinatesException.php";

include_once $_SERVER["DOCUMENT_ROOT"]."php_scripts/kml/KmlPlaceMark.php";

/*
include_once $_SERVER["DOCUMENT_ROOT"]."/poiserver/php_scripts/exceptions/KmlNotRetrievedException.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/poiserver/php_scripts/exceptions/NotKmlException.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/poiserver/php_scripts/exceptions/KmlWithoutCoordinatesException.php";

include_once $_SERVER["DOCUMENT_ROOT"]."/poiserver/php_scripts/kml/KmlPlaceMark.php";
*/


class KmlReader {



	public function __construct(){


	}


	public function loadKmlFromUrl($url, $cxContext = null){
		$kmlFile = null;

		if($cxContext == null){
			$kmlFile = file_get_contents($url);
		} else {
			$kmlFile = file_get_contents($url, false, $cxContext);
		}

		if($kmlFile){
			$kmlFile = mb_convert_encoding($kmlFile, 'UTF-8',mb_detect_encoding($kmlFile, 'UTF-8, ISO-8859-1', true));
			return $this->loadKmlFromText($kmlFile);
		}else{
			throw new KmlNotRetrievedException("No se ha podido acceder al archivo (puede ser un problema de red o de URL incorrecta)");
		}
	}

	public function loadKmlFromText($kmlFile){
		if(strpos($kmlFile, "<html xmlns='http://www.w3.org/1999/xhtml'>")){
			throw new NotKmlException("No es un documento KML / XML, sino un documento HTML");
		}

		$solution = array();
			
		$xml = simplexml_load_string($kmlFile, null, LIBXML_NOCDATA);
		if($xml){
			$ns = $xml->getDocNamespaces();
			if(isset($ns[""])){
				$xml->registerXPathNamespace("default",$ns[""]);
			}

			$placeMarks = $xml->xpath('//default:Placemark');
			if(sizeof($placeMarks) == 0){
				throw new KmlWithoutCoordinatesException("Documento KML sin PlaceMarks.");
			}

			$numPlaceMarks = sizeof($placeMarks);
			for($i = 0; $i < $numPlaceMarks; $i++){
				$aPlaceMark = $placeMarks[$i];

				$name = $aPlaceMark->name;
				$description = $aPlaceMark->description;

				$wktText = null;

				if($aPlaceMark->Point ){
						
					$coordinates = $this->_extractCoordinates($aPlaceMark->Point);
						
					$wktText = "POINT(".$coordinates[0][0]." ".$coordinates[0][1].")";
						
				}else if($aPlaceMark->LineString){
//					$wktText = $aPlaceMark->LineString->asXml();
					
					$coordinates = $this->_extractCoordinates($aPlaceMark->LineString);
						
					$wktText = "POINT(".$coordinates[0][0]." ".$coordinates[0][1].")";
				}else if($aPlaceMark->Polygon){
					//$wktText = $aPlaceMark->Polygon->asXml();
					$coordinates = $aPlaceMark->Polygon->outerBoundaryIs->LinearRing->coordinates;
					
					$coords = explode(" ", trim($coordinates));
					for($j = 0; $j < sizeof($coords);$j++){
						if($coords[$j] != "" && $coords[$j] != "\n"){
							$firstCoord = explode(",", $coords[0]);
							break;
						}
					}
					
						
					$wktText = "POINT(".$firstCoord[0]." ".$firstCoord[1].")";
				}else if($aPlaceMark->MultiGeometry){
//					$wktText = $aPlaceMark->MultiGeometry->asXml();

					$coordinates = $this->_extractCoordinates($aPlaceMark->MultiGeometry);
						
					$wktText = "POINT(".$coordinates[0][0]." ".$coordinates[0][1].")";
				}else if($aPlaceMark->LineString){
//					$wktText = $aPlaceMark->LineString->asXml();

					$coordinates = $this->_extractCoordinates($aPlaceMark->LineString);
						
					$wktText = "POINT(".$coordinates[0][0]." ".$coordinates[0][1].")";
				}

				$newPlaceMark = new KmlPlaceMark($name,
											$description,
											$wktText);

				array_push($solution, $newPlaceMark);

			}
			return $solution;
		}else{
			throw new NotKmlException("No se ha podido analizar el documento KML. Verifique que se trata de un XML bien formado");
		}

	}

	protected function _extractCoordinates($xml) {
		$coord_elements = $this->childElements($xml, 'coordinates');
		$coordinates = array();
//		$coord_sets = explode(' ', $coord_elements[0]->nodeValue);
		
		$coord_sets = explode(' ', $coord_elements[0]);
		
		foreach ($coord_sets as $set_string) {
			$set_string = trim($set_string);
			if ($set_string) {
				$set_array = explode(',',$set_string);
				if (count($set_array) >= 2) {
					$coordinates[] = $set_array;
				}
			}
		}

		return $coordinates;
	}

	protected function childElements($xml, $nodename = '') {
		$children = array();
		if($xml->children()){
			foreach($xml->children() as $child)	{
//		if ($xml->childNodes) {
//			foreach ($xml->childNodes as $child) {
				if ($child->getName() == $nodename) {
					$children[] = $child;
				}
			}
		}
		return $children;
	}

}