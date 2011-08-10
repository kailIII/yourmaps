<?php

//TODO HAY SERVICIOS QUE NO TIENEN CAPA BASE. PONER SIEMPRE UNA ORTO A NIVEL GLOBAL 
//(BUSCAR UN BUEN WMS BASE)

//OTROS DOS PROBLEMAS SON: TEXTO DE URL-AMIGABLE PARA KEYWORDS,
// Y SRS CUANDO NO ESTÉ EL LATITUD LONGITUD (POR EJEMPLO EN CHG)

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
		
			echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
			echo "<html dir=\"ltr\" xml:lang=\"es\" xmlns=\"http://www.w3.org/1999/xhtml\">";
			echo "<head>";
?>
			<link rel="stylesheet" type="text/css" href="../resources/css/mapa.css" />
<? 
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			
			$url = $row['service_url'];
			
			//FIXME Las xmin,ymin,xmax,ymax que estamos calculando no dan buenos resultados
			//suelen ser niveles de zoom demasiado grandes.
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

			echo "<title>".$serviceTitle ." - TusMapas: ciudades y mapas de todo el mundo </title>";
			
			include("expandable_js.php");//FIXME mejor un popup modal
			
			
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
		<div id="contenedor">
			
			<div id="postcabecera">
			<a href="#" class="inverse">Registrate</a>&nbsp;<a href="#" class="inverse">Entrar</a>&nbsp;<a href="#" class="inverse">Sobre "Tus Mapas"</a>&nbsp;&nbsp;&nbsp;<a href="#" class="inverse">link1</a>
			</div>
			
			<div id="cabecera">
				<!-- <a href="."><img class="cabecera" src="../resources/images/logob1-round.png"/></a> -->
				<a ><img class ="cabecera" src="../resources/images/lupa-map.-64x64.png"/></a>
				<h4>Tus Mapas:</h4> <h6>mucho m&aacute;s que Google Maps con la cartograf&iacute;a de la web 2.0</h6>
			</div>
			
			<div class="inverse">
			<h3>Productor: <?=$productor?> - Mapa:  <?=$serviceTitle?></h3><br><br>
			</div>
<?
			$strlen = strlen($serviceAbstract);
			if($strlen > 650){
				echo "<div class='expandable' style='overflow:hidden; z-index: 3000'>";
				echo "<p>";
				echo $serviceAbstract;
				echo "</p>";
				echo "</div>";
			}else 
				echo $serviceAbstract;	
?>
			<br/>
			<div id="contenido">
				<div id="cuerpo" class="cuerpo"><!-- este div mete la indentacion -->
				<a href="mapamaximizado.php" class="maximizar_mapa" title="Pantalla completa" > </a>
					<h2 class="titulo" style="font-size: 15px;">
						<div style="width:550px">
						<!-- word-wrap: break-word;white-space: pre; white-space: -moz-pre-wrap; white-space: pre-wrap; -->
							 <em style="width:30px;font-size: 1.2em; font-style: normal; color: rgb(51, 102, 153); font-weight: normal;">
							 	<?=$serviceTitle?>
							 </em>
						</div>
					</h2>
					
					<div id="map-container"></div>
					
					<div class="map_social">
						<!-- AddThis Button BEGIN -->
						<div class="addthis_toolbox addthis_default_style ">
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
			
			</div><!-- contenido -->
				</div><!-- cuerpo -->
			</div>
			 
			
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
		echo "</body>";	
}catch(PDOException $e){
	echo $e->getMessage();
}
$dbh = null;