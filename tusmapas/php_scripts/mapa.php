<?php

// FIXME: Cuando el srs es 23030, no valen ni las capas base ni epsg:4326

/*
 * Pasos:
 * 
 * a) Ordenar los SRS. Quedarnos por los que empiezan por 4 (tienen coordenadas geograficas)
 * 
 * b) Si no aparecen en geograficos, pasar la peticion en algún SRS disponible
 * 
 * c) Nivel de zoom mas cercano al usuario (geolocation?)
 * 
 * d)  Mapa base (open street map)
 * 
 */
	
include("Config.class.php");
$config = Config::singleton();
$username = $config->username;
$hostname = $config->hostname;
$password = $config->password;


$api = "openlayers";
$requiredMap = $_GET['mapa'];


$height = 356;
$width = 350;
try {
		$dbh = new PDO("mysql:host=$hostname;dbname=tusmapas",
		 $username, $password, 
		 array(PDO::ATTR_PERSISTENT => true));
		 
		$dbh->query("SET CHARACTER SET utf8");
		$statement = $dbh->query("select * from WMS_SERVICES where friendly_url = '".$requiredMap ."'");
		$statement->execute();
		if($row = $statement->fetch()){
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
			
<!--			  <script src='../resources/js/jquery.expander.js' type='text/javascript'></script>-->
   			
<!--			<script type="text/javascript">-->
<!--				$(document).ready(function() {-->
<!--						 $('div.expandable p').expander({-->
<!--							    slicePoint:    220,  -->
<!--							    expandText:  'Leer más...', -->
<!--							    collapseTimer:   0, -->
<!--							    userCollapseText: '[^]'  // default is '[collapse expanded text]'-->
<!--						  });-->
<!--				  });-->
<!--			</script>-->
			
  					
   			<script src='../resources/js/scripts.js' type='text/javascript'>
   			</script>
   			
   			<script type="text/javascript" src="../resources/js/jquery.corner.js?v2.11">
			</script>
		
		
			<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
		  		{lang: 'es'};
			</script>
	
			
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		
<? 	
			$url = $row['service_url'];
			
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
			
			echo "<meta name=\"keywords\" content=\"".$keywords."mapas, maps, wms, cartografia, google maps, gogle\">";
			echo "<meta name=\"Description\" content=\"".$serviceAbstract."\" />"; 
			echo "<meta name=\"Author\" content=\"Alvaro Zabala Ordóñez - azabala@gmail.com\" />"; 
			echo "<meta name=\"Subject\" content=\"Mapas del mundo basados en servicios estándar: KML, WMS, etc.\" />"; 
			echo "<meta name=\"Robots\" content=\"index, follow\" />"; 
			//echo "<link rel=\"shortcut icon\" href=\"http://localhost/spainholydays/favicon.ico\">";
			echo "<link rel=\"copyright\" href=\"http://www.gnu.org/copyleft/fdl.html\">";

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
		
		<body onload="addFrame('map-container','<?= $url?>','<?= $serviceTitle?>', <?= $height?>,<?= $width?> ,<?= $xmin?> ,<?= $ymin?> ,<?= $xmax?> ,<?= $ymax?>,'<?= $layerNames?>','<?= $layerTitles?>','<?= $crs?>',<?= $isQueryable?>,'<?= $wmsVersion?>' )"> 
		
			<?php include("menu-header.php")?>
			
			<div class="container"> 
						<div class="span-15"><!-- este div mete la indentacion -->
								<a href="mapamaximizado.php" class="maximizar_mapa" title="Pantalla completa" > </a>
								
								<h2 class="titulo" style="font-size: 15px;">
									<div style="width:550px">
									<!-- word-wrap: break-word;white-space: pre; white-space: -moz-pre-wrap; white-space: pre-wrap; -->
										 <em style="width:30px;font-size: 1.2em; font-style: normal; color: rgb(51, 102, 153); font-weight: normal;">
										 	<?=$serviceTitle?>
										 </em>
									</div>
								</h2>
							
								<div id="map-container">
								</div>
									
								<!-- AddThis Button BEGIN -->
								<div class="addthis_toolbox addthis_default_style " style="margin:7px 0px 7px 0px">
									<a href="#descargar kml" title="#descargar kml" style="padding-left:4px;margin-left:3px">
										<img src="../resources/images/google_earth_15x15.png" />
										Ver en Google Earth
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
								
							</div><!-- cuerpo -->
							
							
									
							<div class="span-8 last">
								<h4>Publicador: <?=$productor?></h4>
								<hr class="space"/>
								<h4>Mapa:  <?=$serviceTitle?></h4>
								<hr class="space"/>
								
								
<?
			$strlen = strlen($serviceAbstract);
			if($strlen > 350){
//				echo "<div class='expandable' style='overflow:hidden; z-index: 3000'>";
//				echo "<p>";
//				echo $serviceAbstract;
//				echo "</p>";
//				echo "</div>";

			
				
				echo "<p>";
				echo substr($serviceAbstract, 0, 250)."..."."";
				
				echo "<div id='dialog' title='".$serviceTitle."'style='display:none'>";
				echo "".$serviceAbstract;			
				echo "</div>";				
				
				echo "<a <a href='#' id='dialog_link'>Ver resto</a>";
				echo "</p>";
				
				
			}else{ 
				echo $serviceAbstract;	
			}
?>
						</div><!-- span-4 -->
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
					function() { $(this).addClass('ui-state-hover'); }, 
					function() { $(this).removeClass('ui-state-hover'); }
				);
				
			});
		</script>
		
		<?
		/*
		 Consulta para meter palabras clave de este servicio: 
		 select text, computed from WMS_SERVICES, Wms_Keywords, Keywords_Services where Keywords_Services.fk_keyword_id = Wms_Keywords.pk_id and Keywords_Services.fk_wms_id = WMS_SERVICES.pk_id and WMS_SERVICES.pk_id = 102   Order by WMS_SERVICES.pk_id ASC
		 * */
			include("keywords-widget.php");
			include("producer-widget.php");
			include("tailer-widget.php");
		
		}else{
			
			echo "No se ha podido encontrar el mapa ".$requiredMap;
		}
		
}catch(PDOException $e){
	echo $e->getMessage();
}
$dbh = null;
?>
	</body>
</html>