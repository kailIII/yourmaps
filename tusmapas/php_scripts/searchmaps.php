<?php

	include("Config.class.php");
	$config = Config::singleton();
	$username = $config->username;
	$hostname = $config->hostname;
	$password = $config->password;
	
	
	try {
		$dbh = new PDO("mysql:host=$hostname;dbname=tusmapas",
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
			$("#keywords_textfield").autocomplete("http://localhost/tusmapas/php_scripts/keywords-search-jquery.php");
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
	echo "<meta name=\"keywords\" content=\"".$keywords."mapas, maps, wms, cartografia, google maps, gogle\">";
	echo "<meta name=\"Description\" content=\"".$serviceAbstract."\" />"; 
	echo "<meta name=\"Author\" content=\"Alvaro Zabala Ordóñez - azabala@gmail.com\" />"; 
	echo "<meta name=\"Subject\" content=\"Busca ciudades y mapas de todo el mundo: WMS, KML, KMZ, GPX\" />"; 
	echo "<meta name=\"Robots\" content=\"index, follow\" />"; 
	//echo "<link rel=\"shortcut icon\" href=\"http://localhost/spainholydays/favicon.ico\">";
	echo "<link rel=\"copyright\" href=\"http://www.gnu.org/copyleft/fdl.html\">";

	echo "<title>Looking for maps: search cities and maps of the world </title>";
?>	
		</head>
		
		<body>

			<?php include("menu-header.php")?>
			
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
						 ej: <a title="protección de áreas naturales" href="mapsfoundbykeyword.php?keywords=protección de áreas naturales" class="inverse" onclick="">Protecci&oacute;n de &aacute;reas naturales</a>, 
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

<?
 		include("keywords-widget.php");
		include("producer-widget.php");
		include("tailer-widget.php");
}catch(PDOException $e){
	echo $e->getMessage();
}
$dbh = null;
?>

		</body>
		
