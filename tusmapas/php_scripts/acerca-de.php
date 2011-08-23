<?php
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
		
		<script type="text/javascript" language="javascript" src="../resources/js/jquery-1.6.1.js">
		</script>	
		
		<script type="text/javascript" language="javascript" src="../resources/js/jquery.autocomplete.js">
		</script>
		
		<script type="text/javascript" src="../resources/js/jquery.corner.js?v2.11">
		</script>
		
		
		<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
	  		{lang: 'es'};
		</script>
		
		<script>
	 	 $(document).ready(function(){
			$("#introduction").corner();
		  });
	    </script>	
<? 
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	echo "<meta name=\"keywords\" content=\"mapas, maps, wms, cartografia, google maps, gogle\">";
	echo "<meta name=\"Description\" content=\"Descripcion de lookingformaps.com\" />"; 
	echo "<meta name=\"Author\" content=\"Alvaro Zabala Ordóñez - azabala@gmail.com\" />"; 
	echo "<meta name=\"Subject\" content=\"Busca ciudades y mapas de todo el mundo: WMS, KML, KMZ, GPX\" />"; 
	echo "<meta name=\"Robots\" content=\"index, follow\" />"; 
	//echo "<link rel=\"shortcut icon\" href=\"http://localhost/spainholydays/favicon.ico\">";
	echo "<link rel=\"copyright\" href=\"http://www.gnu.org/copyleft/fdl.html\">";

	echo "<title>About - Looking for maps: search cities and maps of the world </title>";
?>	
		</head>
		
		<body>

			<?php include("menu-header.php")?>
			
			<div class="container"> 
				
				<div class="span-10 preppend-3 last" id="introduction">
					<h2 class="left">
						¿Qué es <em>lookingformaps.com</em>?
					</h2>
					<p style="text-shadow: 1px 1px 2px #90BADD;">
					<strong>lookingformaps.com</strong> es una aplicación en internet para buscar mapas de la misma forma que buscas con Google.
					</p>
					<hr class="space"></hr>
					<p >
					¿Sabías que en la web hay miles de mapas 
					sobre cualquier temática, además de los mapas de Google? 
					<hr class="space"/>
					<a href="http://www.openstreetmap.org">OpenStreetMap</a> es un mapa de todo el mundo elaborado por voluntarios, como Wikipedia,
					y que en muchos casos 
					<a href="http://tools.geofabrik.de/mc/?mt0=mapnik&mt1=googlemap&lon=-5.97115&lat=37.3897&zoom=16">
					mejora a los mapas de Google.
					</a>
					<hr class="space"/>
					Las Administraciones Públicas, los principales productores de cartografía, 
					</p>
				</div>
				
			</div><!-- container -->

<?
 		include("keywords-widget.php");
		include("producer-widget.php");
		include("tailer-widget.php");
?>

		</body>
		
