<?php

include_once "WMS.class.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts/"."WmsMap.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/IMapReader.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/open-calais/opencalais.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/Geonames/Services/Exception.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/MapAlreadyExistException.class.php";

class WmsReader implements IMapReader {


	public function loadMap($url, $userCreator, $source, $pdo){
		return WmsReader::loadWms($url, $userCreator,$pdo);
	}


	
	public static function refreshCache($url, $pdo){
		if(strpos($url,"?") === false)
		$url .= "?";

		if($pdo != null){	
				$wms = new WMS($url,"curl");
				$wrap = new WmsMap($wms);
				$wrap->update($pdo);
		}				
		return $wrap;
	}
	
	
	public static function loadWms($url, $userCreator, $pdo){

		if(strpos($url,"?") === false)
		$url .= "?";

		$wmsWords = null;
		$openCalaisWords = array();
		$geonamesWords = array();
		$mergedKeywords = array();

		if($pdo != null){
			if(! WmsMap::existUrl($url, $pdo)){
				$wms = new WMS($url,"curl");
				$wrap = new WmsMap($wms);
				$wrap->setUser($userCreator);
				$wrap->save($pdo);
				$wmsWords = $wrap->getWms()->getKeywords();
				try{
					$openCalaisWords = $wrap->searchOpenCalaisKeywords($pdo);
						
				}//FIXME process OpenCalaisException and Services_Geonames_Exception
				catch(OpenCalaisException $e){
				}
				try{
					$geonamesWords = $wrap->searchGeonamesKeywords($pdo);
				}catch(Services_GeoNames_Exception $e){
				}
					
				$mergedKeywords = array_merge($wmsWords,$openCalaisWords,$geonamesWords);

				$wrap->setKeywords($mergedKeywords);

				unset($openCalaisWords);
				unset($geonamesWords);
				unset($wmsWords);
				unset($mergedKeywords);
			}//if ! existUrl
			else{
				throw new MapAlreadyExistException($url." ya existe en Looking4maps");
			}

		}

		return $wrap;
	}

	public function loadMapFromDB($mapId, $pdo){
			
		$solution = null;
			
		$query = "select service_url from WMS_SERVICES where pk_id = ".$mapId;

		$statement = $pdo->query($query);
		if($statement){
			if($statement->execute()){
				if($row = $statement->fetch()){
					$url = $row["service_url"];
					$wms = new WMS($url,"curl");
					$solution = new WmsMap($wms);
					$solution->setGid($mapId);
				}//if fetch
			}//if execute
		}//if statement
			
		return $solution;
	}


}