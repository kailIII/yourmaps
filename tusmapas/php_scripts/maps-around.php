<?php	
include("Config.class.php");
include("MapUtils.class.php");
$config = Config::singleton();
$username = $config->username;
$hostname = $config->hostname;
$password = $config->password;


$api = "openlayers";
$xmin = $_GET['xmin'];
$ymin = $_GET['ymin'];
$xmax = $_GET['xmax'];
$ymax = $_GET['ymax'];


$height = 356;
$width = 350;
try {
		$dbh = new PDO("mysql:host=$hostname;dbname=tusmapas",
		 								$username, $password, 
							 array(PDO::ATTR_PERSISTENT => true));
		 
		$dbh->query("SET CHARACTER SET utf8");
		
		$sql = "SELECT service_url,friendly_url, xmin, ymin, xmax, ymax, (xmax-xmin) width, keywords_list, service_title, service_abstract, contact_organisation, layer_names, layer_titles, crs, is_queryable, wms_version, 'WMS' as type, pk_id FROM WMS_SERVICES WHERE MBRIntersects(GeomFromText('POLYGON((:xmin :ymin, :xmax :ymin, :xmax :ymax, :xmin :ymax, :xmin :ymin  ))'),BBOX) order by width asc".
			"UNION ALL ".
            "SELECT SELECT url_origen,friendly_url, xmin, ymin, xmax, ymax, '', document_name, description, origen, '', '','EPSG:4326',1,'','KML' as type, pk_gid FROM KML_SERVICES WHERE MBRIntersects(GeomFromText('POLYGON((:xmin :ymin, :xmax :ymin, :xmax :ymax, :xmin :ymax, :xmin :ymin ))'),BBOX) order by width asc";
		
		$stmt = $dbh->prepare($sql);
		
		$stmt->bindParam(':xmin', $xmin);
		$stmt->bindParam(':xmax', $xmax);
		$stmt->bindParam(':ymin', $ymin);
		$stmt->bindParam(':ymax', $ymax);
		
		$stmt->execute();
		if($row = $stmt->fetch()){
?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
		<html dir="ltr" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<link rel="stylesheet" href="../resources/css/blueprint/screen.css" type="text/css" media="screen, projection">
  			<link rel="stylesheet" href="../resources/css/blueprint/print.css" type="text/css" media="print"> 
	  		<!--[if lt IE 8]>
	    		<link rel="stylesheet" href="../resources/css/blueprint/ie.css" type="text/css" media="screen, projection">
	  		<![endif]-->
		
			<link rel="stylesheet" type="text/css" href="../resources/css/mapa.css" />
			<link rel="stylesheet" type="text/css" href="../resources/css/searchmaps.css" />
			
			<link rel="stylesheet" type="text/css" href="../resources/css/jquery.autocomplete.css" />
			
			<link href="../resources/css/jquery-ui/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css"/>
			
		
			<script type="text/javascript" language="javascript" src="../resources/js/jquery-1.6.2.min.js">
			</script>
			
			<script type="text/javascript" language="javascript" src="../resources/js/jquery-ui-1.8.16.custom.min.js">
			</script>	
		
			<script type="text/javascript" language="javascript" src="../resources/js/jquery.autocomplete.js">
			</script>
			
			
			<script>
			$(document).ready(function() {
				  var uvOptions = {};
				  (function() {
				    var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
				    uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/t5F9fQHfLGeZLFLVvHvo4w.js';
				    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
				  })();
			});
			</script>
						
   			<script src='../resources/js/scripts.js' type='text/javascript'>
   			</script>
   			
   			<script type="text/javascript" src="../resources/js/jquery.corner.js?v2.11">
			</script>
		
		
			<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
		  		{lang: 'es'};//FIXME esto se leera de la variable de entorno 
			</script>
	
			
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		
<? 	
		
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
			
			
			$keywords;
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
			
			
 
			//javascript generador del mapa
			if($api == "ide-e")
				include("./map_engines/idee-api.php");
			else if($api == "mapea")
				include("./map_engines/mapea.php");
			else if($api == "leaflet")
				include("./map_engines/leaflet.php");
			else 	
				include("./map_engines/openlayers-api.php"); 
?>				
		</head>
		
<?
	if($type == "KML"){
			
?>
	<body onload="addFrameKml('map-container','<?= $friendlyUrl?>','<?= $serviceTitle?>', <?= $height?>,<?= $width?> ,<?= $xmin?> ,<?= $ymin?> ,<?= $xmax?> ,<?= $ymax?> )">
<?	
	}else if($type == "WMS"){
?>
			
		<body onload="addFrame('map-container','<?= $url?>','<?= $serviceTitle?>', <?= $height?>,<?= $width?> ,<?= $xmin?> ,<?= $ymin?> ,<?= $xmax?> ,<?= $ymax?>,'<?= $layerNames?>','<?= $layerTitles?>','<?= $crs?>',<?= $isQueryable?>,'<?= $wmsVersion?>' )"> 
<?
	}
?>
			<?php include("menu-header.php")?>
			
			
			<div class="container">
			
				<div class="span-24 last">
					<script type="text/javascript"><!--
						google_ad_client = "ca-pub-7845495201990236";
						/* lookingformaps2 */
						google_ad_slot = "9961918851";
						google_ad_width = 728;
						google_ad_height = 90;
						//-->
					</script>
					<script type="text/javascript"
					src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
					</script>
				</div>
			</div>
			
			<div class="container" style="padding:5px 0px"> 
						<div class="span-15"><!-- este div mete la indentacion -->	
								<div class="span-13">
										<em style="font-size: 1.5em; font-style: normal; color: rgb(51, 102, 153); font-weight: normal">
												 	<?=$serviceTitle?>
										</em>
								</div>
								
								<div class="span-2 last">
									<a href="mapamaximizado.php?mapa=<?=$requiredMap?>" class="maximizar_mapa"  title="Pantalla completa" > </a>
								</div>
								
								<div id="map-container" class="span-15 last">
								</div>
									
									
								<div id="social-bar" class="span-15 last">
									<!-- AddThis Button BEGIN -->
									<div class="addthis_toolbox addthis_default_style " style="margin:7px 0px 7px 0px">
										
										<a href="#" title="Reportar problema" style="padding-left:4px;margin-left:3px">
											Notificar problema con el mapa
										</a>
										<a class="addthis_button_preferred_1"></a>
										<a class="addthis_button_preferred_2"></a>
										<a class="addthis_button_preferred_3"></a>
										<a class="addthis_button_preferred_4"></a>
										<a class="addthis_button_compact"></a>
										<a class="addthis_counter addthis_bubble_style"></a>
									</div>
									<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4e4193c01ebec8a5"></script>
									<!-- AddThis Button END -->
								</div>
									
								<!--  facebook comments -->
								
								
								<div class="span-15 last">
									<em style="font-size: 1.5em; font-style: normal; color: rgb(51, 102, 153); font-weight: normal">Comenta esta mapa en Facebook</em>
									
									<div class="fb-comments" 
										data-href="<?=Util::curPageURL()?>" 
										data-num-posts="3" 
										data-width="900">
									</div>
									
								</div>
									
								
						</div><!-- span-15 -->
							
							
									
							<div class="span-8 last">
								<h4><b>Fuente</b>: <?=$productor?></h4>
								<hr class="space"/>
								<h4><b>Mapa</b>:  <?=$serviceTitle?></h4>
								<hr class="space"/>
								
								
<?
								$strlen = strlen($serviceAbstract);
								if($strlen > 350){
					
									$serviceAbstractShortened = strip_tags($serviceAbstract);
								
									
									echo "<p>";
									echo substr($serviceAbstractShortened, 0, 350)."..."."";
									
									echo "<div id='dialog' title='".$serviceTitle."'style='display:none'>";
									echo "".$serviceAbstract;			
									echo "</div>";				
									
									echo "<a href='#' id='dialog_link'>Ver resto</a>";
									echo "</p>";
									
									
								}else{ 
									echo $serviceAbstract;	
								}
?>
								
								<hr class="space"/>
								<h4><b>Capas</b></h4>
								<div id="map_layer_switch"></div>
								
								<hr class="space"/>
								<h4><b>Etiquetas</b></h4>
<?								
								if($numKeywords > 0){
									for($j = 0; $j < $numKeywords ; $j++ ){
										$text = $keywords[$j]["text"];
										$link = $keywords[$j]["friendly_url_text"];
										$computed = $keywords[$j]["computed"];
										
										echo "<a class='map-label' href='mapsfoundbykeyword.php?keywords=".$link."'>".$text."</a>";
									}
								}else{
									echo "No hay etiquetas disponibles";
								}	
								//include(nearmaps-widget.php);mapas próximos a este
?>
								<hr class="space"></hr>
								
								<input type="button" 
									class="large green button" 
									value="Ver en Google Earth" 
									onClick="javascript:window.location.href='interstitial.php?mapa=<?=$requiredMap?>'" />
							
							
							
									
								<input type="button" 
									class="large blue button" 
									value="Mapas cerca de mí" 
									onClick="javascript:goMapsAroundMe()" />
									
<!--										<input type="button" -->
<!--									class="large blue button" -->
<!--									value="Fotografías del entorno" -->
<!--									onClick="javascript:window.location.href="around-me.php" />-->
						</div><!-- span-8 -->
		</div><!-- container -->
		
		<script type="text/javascript">
			$(function(){
	
				var options = {
						autoOpen: false,
						width: 600,
						buttons: {
							"Ok": function() { 
								$(this).dialog("close"); 
							}
						}
				};
				$("#dialog").dialog(options);
				

				// Dialog Link
				$('#dialog_link').click(function(){
					$('#dialog').dialog('open');
					return false;
				});
				
				//hover states on the static widgets
				$('#dialog_link, ul#icons li').hover(
					function() { $(this).addClass('menu-header.a'); }, 
					function() { $(this).removeClass('menu-header.a'); }
				);
				
			});
		</script>
			
		
		<?
		/*
		 Consulta para meter palabras clave de este servicio: 
		 select text, computed from WMS_SERVICES, Wms_Keywords, Keywords_Services where Keywords_Services.fk_keyword_id = Wms_Keywords.pk_id and Keywords_Services.fk_wms_id = WMS_SERVICES.pk_id and WMS_SERVICES.pk_id = 102   Order by WMS_SERVICES.pk_id ASC
		 * */
		
//			include("keywords-widget.php");
			include("tailer-widget.php");
		
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