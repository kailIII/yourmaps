<?php

//use another method to disallow downloads 
//	$ref = $_SERVER['HTTP_REFERER'];
//	if (stripos($ref, 'lookingformaps.com') !== FALSE || !$ref){
//	    header( 'Location: http://www.lookingformaps.com/403.php' );
//	    exit;
//	}
	

	$requiredMap = $_GET['mapa'];
	
	include_once "Config.class.php";
	include("MapUtils.class.php");
	$config = Config::singleton();
	$username = $config->username;
	$hostname = $config->hostname;
	$password = $config->password;
	$database = $config->database;
	
	
	try {
		$dbh = new PDO("mysql:host=$hostname;dbname=$database",
		 $username, $password, 
		 array(PDO::ATTR_PERSISTENT => true));
		 
		$dbh->query("SET CHARACTER SET utf8");
		
		
		$statement = $dbh->query("SELECT service_url,friendly_url, '' as kml_content, wms_version, xmin, ymin, xmax, ymax, keywords_list, service_title, service_abstract, contact_organisation, layer_names, layer_titles, crs, is_queryable, wms_version, 'WMS' as type FROM WMS_SERVICES where friendly_url = '".$requiredMap."' UNION ALL SELECT url_origen,friendly_url, kml_content, '' as wms_version, xmin, ymin, xmax, ymax, '', document_name, description, origen, '', '','EPSG:4326',1,'','KML' as type FROM KML_SERVICES where friendly_url = '".$requiredMap."'");
		
		$statement->execute();
		if($row = $statement->fetch()){
			
			$type = $row['type'];
			
			$url = $row['service_url'];
			
			$wmsVersion = $row['wms_version'];
			
			
			$xmin = $row['xmin'];
			$ymin = $row['ymin'];
			$xmax = $row['xmax'];
			$ymax = $row['ymax'];
				
			$keywords = $row['keywords_list'];
			$serviceTitle = $row ['service_title'];
			$serviceAbstract = $row ['service_abstract'];
			$productor = $row ['contact_organisation'];
			
			$layerNames = $row['layer_names'];
			$layerTitles = $row['layer_titles'];
			$crs = $row['crs'];
			$isQueryable = $row['is_queryable'];
			$wmsVersion = $row['wms_version'];
			
			
			if($type == "KML"){
				$kmlContent = $row['kml_content'];
				header('Content-type: application/vnd.google-earth.kml+xml');
				header('Content-Disposition: attachment; filename='.$serviceTitle.'.kml');
				echo $kmlContent;
			}else if($type == "WMS"){
				$xcenter = ( $xmin + $xmax ) / 2;
				$ycenter = ( $ymin + $ymax ) / 2;
				
				$wmsLayers = explode(";", $layerNames);
				
				
				$wmsUrl = $url;
				if(! MapUtils::singleton()->endsWith("?",$wmsUrl))
					$wmsUrl .= "?";
				
				header('Content-type: application/vnd.google-earth.kml+xml');
				header('Content-Disposition: attachment; filename='.$serviceTitle.'.kml');
					
				echo '<kml xmlns="http://earth.google.com/kml/2.2">';
?>
				<Folder xmlns="">
    				<name><?=$serviceTitle?></name>
    				<visibility/>
    				<description>
    					Looking for Maps is a web map repository which indexes main map sources in the web.
    					
    					For more information, visit <a href="http://www.lookingformaps.com">www.lookingformaps.com</a>
    				</description>
    
    				<LookAt>
      					<longitude><?= $xcenter?></longitude>
      					<latitude><?= $ycenter?></latitude>
      					<altitude>0</altitude>
      					<range>1000000</range>
      					<tilt>0</tilt>
      					<heading>0</heading>
    				</LookAt>
    
				    <Style>
				      <ListStyle>
				        <listItemType>check</listItemType>
				        <bgColor>00ffffff</bgColor>
				        <maxSnippetLines>2</maxSnippetLines>
				      </ListStyle>
				    </Style>
<?			
				$numWmsLayers = sizeof($wmsLayers);
				
				for($i = 0; $i < $numWmsLayers; $i++){
					$wmsLayerName = $wmsLayers[$i];
					
					
					$wmsUrl .= "service=wms&amp;VERSION=".$wmsVersion."&amp;REQUEST=GetMap&amp;SRS=EPSG:4326&amp;WIDTH=1024&amp;HEIGHT=1024&amp;LAYERS=".$wmsLayerName."&amp;TRANSPARENT=TRUE&amp;FORMAT=image/png; mode=24bit";
				
?>					
					<GroundOverlay>
      					<name><?=$wmsLayerName?></name>
      					<visibility>0</visibility>
      					<snippet/>	
      					<Snippet maxLines="0"/>
      					<description></description>
      
					    <LookAt>
	      					<longitude><?= $xcenter?></longitude>
	      					<latitude><?= $ycenter?></latitude>
	      					<altitude>0</altitude>
	      					<range>1000000</range>
	      					<tilt>0</tilt>
	      					<heading>0</heading>
    					</LookAt>
      
     					 <drawOrder><?= $i+1 ?></drawOrder>
      
					      <Icon>
					        <href><?=$wmsUrl?></href>
					        <viewRefreshMode>onStop</viewRefreshMode>
					      </Icon>
      
					      <LatLonBox>
					        <north><?=$ymax?></north>
					        <south><?=$ymin?></south>
					        <east><?=$xmin?></east>
					        <west><?=$xmax?></west>
					      </LatLonBox>
    					</GroundOverlay>
<?
				}//for
				echo "</Folder>";
				echo "</kml>";
			}//if
		}//if fetch
	}catch(Exception $e){
		echo $e->getMessage();
	}
?>