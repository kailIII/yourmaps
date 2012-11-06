<?php

require_once 'MapKeyword.class.php';
require_once 'MapsKeywordRelationship.class.php';


class WmsMap {

	protected $gid;
	
	public function setGid($id){
		$this->gid = $id;
	}

	protected $userCreator;
	
	public function getUser(){
		return $this->userCreator;
	}
	
	public function setUser($user){
		$this->userCreator = $user;
	}
	
	
	protected $wms;//wms instance of simple-wms library


	public function getWms(){
		return $this->wms;
	}
	
	protected $keywords = array();
	
	public function setKeywords($aKeywords){
		$this->keywords = $aKeywords;
	}
	
	public function getKeywords(){
		return $this->keywords;
	}

	public function getKeywordsAsText(){
		$solution = "";
		$numKeywords = sizeof($this->keywords);
		
		if($numKeywords > 0){
			for($i = 0; $i < $numKeywords - 1; $i++){
				$aKeyword =  $this->keywords[$i];
				
				if($aKeyword instanceof  MapKeyword){
					$solution .= ($aKeyword->getKeyText() . ",");
				}else{
					$solution .= ($aKeyword . ",");
				}
			}
			
			$lastKeyword = $this->keywords[$numKeywords - 1];
			if($lastKeyword instanceof MapKeyword){
				$solution .= ($aKeyword->getKeyText()) ;
			}else{
				$solution .= $aKeyword;
			}
		}
		
		return $solution;
	}
	
	

	public function __construct($wms){

		$this->wms = $wms;
			
		$this->gid = -1;
	}
	
	
	public function update($pdo){
			
			$bbox = $this->wms->getLatLonBBox();
				
			$bb = "GeomFromText('POLYGON((". $bbox["minx"].
								" ".$bbox["miny"].",".$bbox["maxx"]." ".$bbox["miny"].",".$bbox["maxx"].
								" ".$bbox["maxy"].",".
								$bbox["minx"]." ".$bbox["maxy"].",".$bbox["minx"]." ".$bbox["miny"]."))')";
								
			$sql = "update WMS_SERVICES set service_title = '".$this->wms->getWmsTitle()."', ".
					"service_name = '".$this->wms->getWmsName()."',  service_abstract = '".$this->wms->getWmsAbstract()."' ".
					",keywords_list='".$this->wms->getKeywordsAsText()."', layer_names = '".$this->wms->getLayerNames()."', layer_titles='".$this->wms->getLayerTitles()."'".
					",crs =  '".$this->wms->getSrsAsText()."' where service_url = '".$this->wms->getUrl()."'";
				
			$stmt = $pdo->query($sql);
			return $stmt;		
	}

	
	public function save($pdo){
		if(! $this->exist($pdo)){
			
			$bbox = $this->wms->getLatLonBBox();
				
			$bb = "GeomFromText('POLYGON((". $bbox["minx"].
								" ".$bbox["miny"].",".$bbox["maxx"]." ".$bbox["miny"].",".$bbox["maxx"].
								" ".$bbox["maxy"].",".
								$bbox["minx"]." ".$bbox["maxy"].",".$bbox["minx"]." ".$bbox["miny"]."))')";
								
			$sql = "insert into WMS_SERVICES (service_title, ".
					"service_name, friendly_url, service_abstract, service_url, ".
					"keywords_list, layer_names, layer_titles, xmin, ymin, xmax, ymax, contact_person, ".
					" contact_organisation, contact_person_position, contact_address, contact_mail,".
					"contact_administrative_area, contact_city, contact_country, contact_online_resource, taxonomy_topic,".
					"crs, is_queryable, wms_version, BBOX, username_fk)".
					" VALUES(:service_title, :service_name, :friendly_url, ".
					":service_abstract, :service_url, :keywords_list, :layer_names, :layer_titles,
					:xmin, :ymin, :xmax, :ymax, :contact_person, :contact_organisation, :contact_person_position,".
					":contact_address,:contact_mail,:contact_administrative_area,:contact_city,:contact_country,".
			        ":contact_online_resource,:taxonomy_topic,:crs,:is_queryable,:wms_version,". $bb .",:username_fk)";
				
			$stmt = $pdo->prepare($sql);
			
			$title = $this->wms->getWmsTitle();
			$stmt->bindParam(':service_title',$title);
			
			$serviceName = $this->wms->getWmsName();
			$stmt->bindParam(':service_name', $serviceName );
			
			$friendlyUrl = $this->wms->getFriendlyUrl();
			$stmt->bindParam(':friendly_url', $friendlyUrl );
			
			$abstract = $this->wms->getWmsAbstract();
			$stmt->bindParam(':service_abstract', $abstract);
			
			$url = $this->wms->getUrl();
			$stmt->bindParam(':service_url', $url);
			
			$keywords = $this->wms->getKeywordsAsText();
			$stmt->bindParam(':keywords_list', $keywords );
			
			$layerNames = $this->wms->getLayerNames();
			$stmt->bindParam(':layer_names', $layerNames);
			
			$layerTitles = $this->wms->getLayerTitles();
			$stmt->bindParam(':layer_titles', $layerTitles );
				
			
				
			$stmt->bindParam(':xmin', $bbox["minx"]);
			$stmt->bindParam(':ymin', $bbox["miny"]);
			$stmt->bindParam(':xmax', $bbox["maxx"]);
			$stmt->bindParam(':ymax', $bbox["maxy"]);	
			
			$contactPerson = $this->wms->getContactPerson();
			$stmt->bindParam(':contact_person', $contactPerson);
			
			$contactOrganization = $this->wms->getContactOrganization();
			$stmt->bindParam(':contact_organisation', $contactOrganization );
			
			$contactPosition = $this->wms->getContactPosition();
			$stmt->bindParam(':contact_person_position',$contactPosition );
			
			$address = $this->wms->getAddress();
			$stmt->bindParam(':contact_address', $address );
			
			$mail = $this->wms->getMail();
			$stmt->bindParam(':contact_mail', $mail );
			
			$administrativeArea =  $this->wms->getStateOrProvince();
			$stmt->bindParam(':contact_administrative_area', $administrativeArea);
			
			$contactCity = $this->wms->getCity();
			$stmt->bindParam(':contact_city', $contactCity );
			
			$country  = $this->wms->getCountry();
			$stmt->bindParam(':contact_country', $country);
			
			$onlineResource = $this->wms->getWmsCapabilities();
			$stmt->bindParam(':contact_online_resource', $onlineResource );
			
			$topic = $this->wms->getTaxonomicTopic();
			$stmt->bindParam(':taxonomy_topic', $topic);
			
			$crs =  $this->wms->getSrsAsText();
			$stmt->bindParam(':crs', $crs);
			
			$isQueryable =  $this->wms->isQueryable();
			$stmt->bindParam(':is_queryable', $isQueryable);
			
			$version =  $this->wms->getWmsVersion();
			$stmt->bindParam(':wms_version', $version);
			
			$stmt->bindParam(':username_fk', $this->userCreator);
				
			$success = $stmt->execute();
			
			if(! $success)
				return;
				
			$sql = "SELECT PK_ID FROM WMS_SERVICES WHERE service_url = '".$this->wms->getUrl()."'";
			$statement = $pdo->query($sql);
			if($statement->execute()){
				if($row = $statement->fetch()){
					$this->gid = $row["PK_ID"];
				}
			}
				
			$keywords = $this->wms->getKeywords();
			if($keywords != null){
				$numKeywords = sizeof($keywords);
				for($j = 0; $j < $numKeywords; $j ++){
					$keyText = $keywords[$j];
					$mapKeyword = new MapKeyword($keyText, true);
					echo "<p>". $valueItem . "</p>";
					if(! $mapKeyword->exist($pdo)){
						$mapKeyword->save($pdo);
					}
						
					$relationship = new MapsKeywordRelationship($mapKeyword->getGid(), $this->gid, "WMS");
					if(!$relationship->exist($pdo)){
						$relationship->save($pdo);
					}
						
					unset($mapKeyword);
					unset($relationship);
						
				}//for keywords


			}//if keyword null
		}
	}
	
	
	public static function existUrl($url, $pdo){
		$query = "select * from WMS_SERVICES where service_url = '".$url."'";
		$statement = $pdo->query($query);
		if($statement->execute()){
			$numResults = $statement->rowCount();
			if ($numResults > 0){
				return true;
			}//if numResults>0
		}//if execute
		return false;
	}

	public function exist($pdo){
		$query = "select * from WMS_SERVICES where service_url = '".$this->wms->getUrl()."'";
		$statement = $pdo->query($query);
		if($statement->execute()){
			$numResults = $statement->rowCount();
			if ($numResults > 0){
				$sql = "select pk_id from WMS_SERVICES where service_url = '".$this->wms->getUrl() ."'";
				$statement = $pdo->query($sql);
				if($statement->execute()){
					if($row = $statement->fetch()){
						$this->gid = $row["pk_id"];
					}
					return true;
				}
			}//if numResults>0
		}//if execute
		return false;
	}

	public function searchOpenCalaisKeywords($pdo){
		
		$entities = null;
		$mapKeyword = null;
		$relationship = null;
		
		$solution = array();
		
		
		$text = $this->wms->getWmsTitle().".  ".
				$this->wms->getWmsAbstract().". ".
				$this->wms->getLayerTitles();
		
		
		$openCalais = Util::getOpenCalais();
		$entities = $openCalais->getEntities($text);
		


		//$entities is a key - value array, where
		//key is the entity type (person, url, place, etc)
		//and value is an array with many values as string
		
		if(is_array($entities)){

			foreach ($entities as $type => $values) {
				if($type == "Organization" || $type  == "Company" || $type == "URL" || $type == "IndustryTerm"  )
					continue;
				foreach ($values as $valueItem) {
					
					$mapKeyword = new MapKeyword($valueItem, true);
					
					if($pdo != null){
						if(! $mapKeyword->exist($pdo)){
							$mapKeyword->save($pdo);
						}
	
						$relationship = new MapsKeywordRelationship($mapKeyword->getGid(), $this->gid, "WMS");
						if(!$relationship->exist($pdo)){
							$relationship->save($pdo);
						}
						unset($relationship);
					}
					
					array_push($solution, $mapKeyword);
	
					unset($mapKeyword);
					
				}//foreach valueItem
			}//foreach entities
			
		}
			
		return $solution;	
	}


	public function searchGeonamesKeywords($pdo){
		
		$postalCodes = null;
		$mapKeyword = null;
		$relationship = null;
		$solution = array();

		$geo = Util::getGeoNames();
		
		$bbox = $this->wms->getLatLonBBox();
		
		$xcent = ($bbox["minx"] + $bbox["maxx"]) / 2;
		$ycent = ($bbox["miny"] + $bbox["maxy"]) / 2;
		
		$radius = $bbox["maxx"] - $bbox["minx"];
		
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
	
				if($pdo != null){
					if(! $mapKeyword->exist($pdo)){
						$mapKeyword->save($pdo);
					}
		
					$relationship = new MapsKeywordRelationship($mapKeyword->getGid(), $this->gid, "WMS");
					if(!$relationship->exist($pdo)){
						$relationship->save($pdo);
					}
					unset($relationship);
					
				}	
				
				array_push($solution, $mapKeyword);
				
				unset($mapKeyword);
	
			}//foreach
		}
		return $solution;
	}

}