<?php
include("Config.class.php");
include("MapUtils.class.php");
$config = Config::singleton();
$username = $config->username;
$hostname = $config->hostname;
$password = $config->password;


$api = "openlayers";
$requiredMap = $_GET['mapa'];

try {
		$dbh = new PDO("mysql:host=$hostname;dbname=tusmapas",
		 								$username, $password, 
							 array(PDO::ATTR_PERSISTENT => true));
		 
		$dbh->query("SET CHARACTER SET utf8");
		
		$statement = $dbh->query("SELECT service_url,friendly_url, xmin, ymin, xmax, ymax, keywords_list, service_title, service_abstract, contact_organisation, layer_names, layer_titles, crs, is_queryable, wms_version, 'WMS' as type, pk_id FROM WMS_SERVICES where friendly_url = '".$requiredMap."' UNION ALL SELECT url_origen,friendly_url, xmin, ymin, xmax, ymax, '', document_name, description, origen, '', '','EPSG:4326',1,'','KML' as type, pk_gid FROM KML_SERVICES where friendly_url = '".$requiredMap."'");
		
		$statement->execute();
		if($row = $statement->fetch()){
?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
		<html dir="ltr" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
		<head>		
			<style type="text/css">
	            html, body, #map-container {
	                margin: 0;
	                width: 100%;
	                height: 100%;
	            }
        	</style>
        	
        	<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
		  		{lang: 'es'};
			</script>
			
			<script src='../resources/js/scripts.js' type='text/javascript'>
   			</script>
			
			<script type="text/javascript" language="javascript" src="../resources/js/jquery-1.6.2.min.js">
			</script>
			
			<script type="text/javascript" language="javascript" src="../resources/js/jquery-ui-1.8.16.custom.min.js">
			</script>	
				
			<script>
			$(document).ready(function() {
				  //feedback widget 
				  var uvOptions = {};
				  (function() {
				    var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
				    uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/t5F9fQHfLGeZLFLVvHvo4w.js';
				    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
				  })();

				  doAddFrame();
			});
			</script>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		
<? 	
			$width = 0;
			$height = 0;		

			$url = $row['service_url'];
			
			$xmin = $row['xmin'];
			$ymin = $row['ymin'];
			$xmax = $row['xmax'];
			$ymax = $row['ymax'];
			
			$friendlyUrl = $row['friendly_url'];
			
			$type = $row['type'];
			
			$serviceTitle = $row ['service_title'];
			$serviceAbstract = $row ['service_abstract'];
			$productor = $row ['contact_organisation'];
			
			$layerNames = $row['layer_names'];
			$layerTitles = $row['layer_titles'];
			$crs = $row['crs'];
			$isQueryable = $row['is_queryable'];
			$wmsVersion = $row['wms_version'];
			
			$mapUtil = MapUtils::singleton();
			if($type == "KML"){	
				$keywords = $mapUtil->getKeywords($dbh, $row['pk_id'], "KML" );
				
				if($productor == "Wikiloc"){
							
							if($serviceTitle == "KML document generated at Wikiloc - http://www.wikiloc.com"){
								$serviceTitle = "Wikiloc: ";
								
								$numKeywords = sizeof($keywords);
								for($j = 0; $j < $numKeywords - 1; $j++ ){
									if($mapUtil->startsWith($keywords[$j]["text"],"http"))
										continue;
									$serviceTitle .= $keywords[$j]["text"] . ", ";
								}//for
								$serviceTitle .= $keywords[$numKeywords - 1]["text"];
							}//if title
				}//if wikiloc
			}else{
				$keywords2 = $row['keywords_list'];
				$keywords = $mapUtil->getKeywords($dbh, $row['pk_id'], "WMS" );
			}	
			
			$numKeywords = sizeof($keywords);
			$keywordStr = '';
			for($j = 0; $j < $numKeywords - 1; $j++ ){
				$keywordStr .= $keywords[$j]["text"] . ", ";
			}//forsubject
			$keywordStr .= $keywords[$numKeywords - 1]["text"];
			
			
			echo "<meta name=\"keywords\" content=\"".$keywordStr."mapas, maps, wms, cartografia, google maps, gogle\">";
			echo "<meta name=\"Description\" content=\"".strip_tags($serviceAbstract)."\" />"; 
			echo "<meta name=\"Author\" content=\"Alvaro Zabala Ordóñez - azabala@gmail.com\" />"; 
			echo "<meta name=\"Subject\" content=\"Mapas del mundo basados en servicios estándar: KML, WMS, WMS-T, etc.\" />"; 
			echo "<meta name=\"Robots\" content=\"index, follow\" />"; 
			//echo "<link rel=\"shortcut icon\" href=\"http://localhost/spainholydays/favicon.ico\">";
			echo "<link rel=\"copyright\" href=\"http://www.gnu.org/copyleft/fdl.html\">";
			

			$serviceTitle = str_replace("'", "`", $serviceTitle);
			echo "<title>".$serviceTitle ." - Looking for maps: cities and maps of the world </title>";
			
			if($api == "ide-e")
				include("./map_engines/idee-api.php");
			else if($api == "mapea")
				include("./map_engines/mapea.php");
			else if($api == "leaflet")
				include("./map_engines/leaflet.php");
			else 	
				include("./map_engines/openlayers-api.php"); 
?>						
<?
			if($type == "KML"){		
?>
			<script>
			function doAddFrame(){
				var div = "map-container";
				var url = "<?= $friendlyUrl?>";
				var serviceTitle = "<?= $serviceTitle?>";
				var height = <?= $height?>;
				var width = <?= $width?>;
				var xmin = <?= $xmin?>;
				var ymin = <?= $ymin?>;
				var xmax = <?= $xmax?>;
				var ymax = <?= $ymax?>;
			

				addFrameKml(div,url, serviceTitle, 
						height, width, 
						xmin, ymin, xmax, ymax); 
			}	
		</script>		
	</head>			
<?	
			}else if($type == "WMS"){
?>
		<script>
			function doAddFrame(){
				var div = "map-container";
				var url = "<?= $url?>";
				var serviceTitle = "<?= $serviceTitle?>";
				var height = <?= $height?>;
				var width = <?= $width?>;
				var xmin = <?= $xmin?>;
				var ymin = <?= $ymin?>;
				var xmax = <?= $xmax?>;
				var ymax = <?= $ymax?>;
				var layerNames = <?= json_encode($layerNames)?>;
				var layerTitles = <?= json_encode($layerTitles)?>;
				var crs = "<?= $crs?>";
				var isQueryable = <?= $isQueryable?>;
				var wmsVersion = "<?= $wmsVersion?>";

				addFrame(div,url, serviceTitle, 
						height, width, 
						xmin, ymin, xmax, ymax,
						layerNames, layerTitles, 
						crs, isQueryable, wmsVersion); 
			}
		</script>		
		</head>			
<?
			}
?>
			<body>
 			<div style='position:absolute;bottom:0px;left:0px;right:0px;padding:0;margin:0;border:0;opacity:0.85;display:block;z-index: 2000;font: normal 30px "PT Sans Bold";color: white;background-color:black'>
			 	<div style="left:40px;display:inline">
			 	<?=$serviceTitle?>
			 	<br></br>
			 	
			 	
			 	<g:plusone   size="medium" style="position:relative;left:5px;right:0px;padding:0;margin:0;border:0;display:inline;z-index: 2000"></g:plusone>
				
				 <a href="interstitial.php?mapa=<?=$requiredMap?>" title="descargar kml" style="position:relative;left:14px;right:0px;padding:0;margin:0;border:0;display:inline;z-index: 2000;font: normal 17px 'PT Sans Bold';color:yellow">
				 Ver en Google Earth
				</a>
									
				 <a href="mapa.php?mapa=<?=$requiredMap?>" title="volver" style="position:relative;left:34px;right:0px;padding:0;margin:0;border:0;display:inline;z-index: 2000;font: normal 17px 'PT Sans Bold';color:yellow">
				 Volver a mapa normal
				</a>
				
				<a href="javascript:goMapsAroundMe()" title="+ mapas" style="position:relative;left:54px;right:0px;padding:0;margin:0;border:0;display:inline;z-index: 2000;font: normal 17px 'PT Sans Bold';color:yellow">
				 Más mapas de la zona
				</a>	
				
				
				</div>
			 </div>
			 
			<div id="map-container">
			</div>	
<?

//			<div style='position:absolute;bottom:240px;left:260px;padding:0;margin:0;border:0;opacity:0.7;display:block;z-index:2000'>
//				<script type="text/javascript"><!--
//				google_ad_client = "ca-pub-7845495201990236";
//				/* 728x90, creado 19/10/10 */
//				google_ad_slot = "9293002252";
//				google_ad_width = 728;
//				google_ad_height = 90;
//				//-->
//				</script>
//				<script type="text/javascript"
//				src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
//				</script>
//			 </div>
		
		}else{
			
			echo "<div class='highlight large'>No se ha podido encontrar el mapa ".$requiredMap."</div>";
			include("adsense.php");
		}
		
}catch(PDOException $e){
	echo $e->getMessage();
}
$dbh = null;
?>
	</body>
</html>