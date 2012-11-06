<?php
	include("include-scripts-headless.php");
	include_once 'Config.class.php';
	
	
	
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
	
		
?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
	<html dir="ltr" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<link rel="stylesheet" href="../resources/css/blueprint/screen.css" type="text/css" media="screen, projection">
  		<link rel="stylesheet" href="../resources/css/blueprint/print.css" type="text/css" media="print"> 
  		<!--[if lt IE 8]>
    		<link rel="stylesheet" href="../resources/css/blueprint/ie.css" type="text/css" media="screen, projection">
  		<![endif]-->
		<!--		<link rel="stylesheet" href="../resources/css/blueprint/plugins/screen.css" type="text/css" media="print"> -->
		
		<link rel="stylesheet" type="text/css" href="../resources/css/mapa.css" />
		<link rel="stylesheet" type="text/css" href="../resources/css/searchmaps.css" />
		<link rel="stylesheet" type="text/css" href="../resources/css/jquery.autocomplete.css" />
		
		<link href="../resources/css/jquery-ui/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css"/>
									
<?
			include("include-scripts.php");
?>			
		
			
	<!-- **************************************************************************************************** -->	
			<script>
			$(document).ready(function() {
					<?
						include("include-scripts-facebook.php");
						include("include-scripts-uservoice.php"); 
						include("include-scripts-map-metadata-dialog.php");
					?>
//					$("#keywords_textfield").autocomplete("http://localhost/tusmapas/php_scripts/keywords-search-jquery.php");
					$("#keywords_textfield").autocomplete("./keywords-search-jquery.php");
					$("#search-box").corner();
					$("#introduction").corner();
			});

			 function fillForm(keyword){
				  $("#keywords_textfield").val(keyword);

			  }

			  function submitForm(){
				  document.forms['home_search_form'].submit();
			  }
			</script>
<? 
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	echo "<meta name=\"keywords\" content=\"mapas, maps, wms, cartografia, google maps, gogle\">";
	echo "<meta name=\"Author\" content=\"Alvaro Zabala Ordóñez - azabala@lookingformaps.com\" />"; 
	echo "<meta name=\"Subject\" content=\"Busca ciudades y mapas de todo el mundo: WMS, KML, KMZ, GPX\" />"; 
	echo "<meta name=\"Robots\" content=\"index, follow\" />"; 
	//echo "<link rel=\"shortcut icon\" href=\"favicon.ico\">";
	echo "<link rel=\"copyright\" href=\"http://www.gnu.org/copyleft/fdl.html\">";

	echo "<title>Looking for maps: search cities and maps of the world </title>";
?>	
		</head>
		
		<body>
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
			
			<div class="container"> 
				<div class="span-11" id="search-box">
					
					<form 
						  id="home_search_form" 
		 				  class="search_destination" 
		 				  method="get" 
		 				  action="mapsfoundbykeyword.php" 
		 				  onsubmit="javascript:checkDestination();return false;"
		 			>
		  				<label> Descubre nuevos mapas</label> 
		  				<input 
		  					id="keywords_textfield" 
		  					type="text" 
		  					class="field auto_border_jstwo" 
		  					name="keywords" 
		  					style="color:#999"  
		  					value="" 
		  					autocomplete="off" 
		  					tabindex="1">
		  					
		 				<input 
		 					id="home_search_box_button" 
		 					type="button" 
		 					value="Buscar" 
		 					class="btn search_button" 
		 					onclick="javascript:submitForm()">
		 					
		 				<div style="width:400px; margin: 6px 0pt 0pt; font-family: Arial,Helvetica; font-style: normal; font-variant: normal; font-weight: normal; font-size: 14px; line-height: normal; font-size-adjust: none; font-stretch: normal; -x-system-font: none; float: left;">
						 ej: <a title="15-M" href="mapsfoundbykeyword.php?keywords=15m" class="inverse" onclick="">Movimiento 15-M</a>, 
						 	 <a title="aguas" href="mapsfoundbykeyword.php?keywords=aguas" class="inverse" onclick="">aguas</a>, 
						 	 <a title="balnearios" href="mapsfoundbykeyword.php?keywords=balnearios" class="inverse" onclick="">balnearios</a> 
					 	 </div>
					 	 
		 			</form>
				</div>
				
				<div class="span-10 preppend-3 last" id="introduction">
					<h2 class="left">
						¿Qué es <em>lookingformaps.com</em>?
					</h2>
					<p style="text-shadow: 1px 1px 2px #90BADD;">
					<strong>lookingformaps.com</strong> es una aplicación en internet para buscar mapas de la misma forma que buscas con Google.
					</p>
					<hr class="space"></hr>
					<p >
					¿Sabías que en la web hay miles de mapas sobre cualquier temática, 
					además de los mapas de Google? 
					<a href="http://www.openstreetmap.org">OpenStreetMap</a>, 
					<a href="http://www.inspire-geoportal.eu/index.cfm/pageid/341">Unión Europea</a>,
					<a href="http://www.idee.es/clientesIGN/wmsGenericClient/index.html?lang=ES">Estados</a>, 
					<a href="http://www.ideandalucia.es/IDEAvisor/proyectosig/mapviewer.jsp">Comunidades Autónomas</a> y 
					<a href="http://es.wikiloc.com/wikiloc/home.do">voluntarios</a> publican gratuítamente sus mapas
					en Internet.
					<hr class="space"/>
					Solo tienes que introducir en nuestro buscador términos como 
					<a href="javascript:fillForm('playas');">playas</a>, 
					<a href="javascript:fillForm('montes');">montes</a>, 
					<a href="javascript:fillForm('rio');">rio</a>, etc.
					para localizarlos.
					</p>
				</div>
				
			</div><!-- container -->
			
			<div class="container">
				<div class="span-24 last">
					<?php 	include("most-popular-resume-widget.php");?>
				</div>
				
<!--				<div class="span-12 last">-->
<!--					Apuntate a nuestra <strong><a href="http://eepurl.com/qkYKP" target="_blank">lista de correo</a></strong> y recibe toda la documentación del curso "Bases de datos espaciales con PostGIS y MySQL"-->
<!--					<input type="button" -->
<!--						    class="medium green button" -->
<!--							value="Recibe el curso de PostGIS"-->
<!--							onClick="javascript:window.location.href='http://eepurl.com/qkYKP'"-->
<!--							style="font-size:24px"-->
<!--					/><img class="aligncenter" alt="Apuntate a nuestra lista de correo y recibirás el material del curso sobre PostGIS y Bases de Datos Espaciales" src="/resources/images/stock_elephant_128.png">-->
<!--				</div>-->
			</div>
			
			<div class="container">
				<div class="span-24 last">
<?
 		include("keywords-widget.php");
?> 		
      			</div>
      		</div>
      		<br></br>
      		<div class="container">
      			<div class="span-24 last">
<?
		include("producer-widget.php");
?>
				</div>
		</div>
		<br></br>
<?		
		include("tailer-widget.php");
}catch(PDOException $e){
	echo $e->getMessage();
}
$dbh = null;
?>

		</body>
		
