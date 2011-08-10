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

	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
	echo "<html dir=\"ltr\" xml:lang=\"es\" xmlns=\"http://www.w3.org/1999/xhtml\">";
	echo "<head>";
?>
	<link rel="stylesheet" type="text/css" href="../resources/css/mapa.css" />
	<link rel="stylesheet" type="text/css" href="../resources/css/searchmaps.css" />
	<link rel="stylesheet" type="text/css" href="../resources/css/jquery.autocomplete.css" />
	<script type="text/javascript" language="javascript" src="../resources/js/jquery-1.6.1.js">
	</script>	
	<script type="text/javascript" language="javascript" src="../resources/js/jquery.autocomplete.js">
	</script>
	
	
	<script>
 	 $(document).ready(function(){
		$("#home_search_box").autocomplete("http://localhost/tusmapas/php_scripts/keywords-search-jquery.php");
  	  });
  </script>

		
	</script>
	
<? 
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			
	echo "<meta name=\"keywords\" content=\"".$keywords."mapas, maps, wms, cartografia, google maps, gogle\">";
	echo "<meta name=\"Description\" content=\"".$serviceAbstract."\" />"; 
	echo "<meta name=\"Author\" content=\"Alvaro Zabala Ordóñez - azabala@gmail.com\" />"; 
	echo "<meta name=\"Subject\" content=\"Mapas del mundo basados en servicios según el estándar WMS\" />"; 
	echo "<meta name=\"Robots\" content=\"index, follow\" />"; 
	//echo "<link rel=\"shortcut icon\" href=\"http://localhost/spainholydays/favicon.ico\">";
	echo "<link rel=\"copyright\" href=\"http://www.gnu.org/copyleft/fdl.html\">";

	echo "<title>".$serviceTitle ." - TusMapas: ciudades y mapas de todo el mundo </title>";
?>	
		</head>
		
		<body>
		 
		 <!--  PASAR ESTO A UN PHP E INCLUIRLO  -->
		 
		<div id="contenedor" style="z-index:0">
			<div id="postcabecera">
				<a href="#">Registrate</a>&nbsp;
				<a href="#">Entrar</a>&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="#">Reportar error</a>
				<a href="#">Sobre "Tus Mapas"</a>
				<a href="#">Ver mapas indexados</a>
			</div>
			
			
		<!-- PASAR A UN PHP E INCLUIRLO -->	
			
		<div id="cabecera">
			<!-- <a href="."><img class="cabecera" src="../resources/images/logob1-round.png"/></a> -->
			<a ><img class ="cabecera" src="../resources/images/lupa-map.-64x64.png"/></a>
			<h4>Tus Mapas:</h4> <h6>mucho m&aacute;s que Google Maps con la cartograf&iacute;a de la web 2.0</h6>
		</div>
		   
		<div class="mdshadow"></div>	
		<div class="modular" style="background:transparent; background-color: transparent; _background-color:#fff;">
 			<form id="home_search_form" class="search_destination" method="post" action="keywords-search.php" onsubmit="javascript:checkDestination();return false;">
  				<label> Descubre nuevos mapas</label> <input id="home_search_box" type="text" class="field auto_border_jstwo" name="search_query" style="color:#999"  value="" autocomplete="off" tabindex="1">
 				<input id="home_search_box_button" type="button" value="Buscar" class="btn unit116" onclick="">
 				<span style="width:400px; margin: 6px 0pt 0pt; font-family: Arial,Helvetica; font-style: normal; font-variant: normal; font-weight: normal; font-size: 14px; line-height: normal; font-size-adjust: none; font-stretch: normal; -x-system-font: none; float: left;">
				 ej: <a title="protección de áreas naturales" href="mapsfoundbykeyword.php?keywords=protección de áreas naturales" class="inverse" onclick="">Protecci&oacute;n de &aacute;reas naturales</a>, <a title="aguas" href="mapsfoundbykeyword.php?keywords=aguas" class="inverse" onclick="">aguas</a>, <a title="balnearios" href="mapsfoundbykeyword.php?keywords=balnearios" class="inverse" onclick="">balnearios</a> </span>
 			</form>
 

<!--  
			 <ul class="statistics">
				 <li>
				 <strong>128.527</strong> <a title="viajeros" href="http://viajeros.minube.com" class="inverse">viajeros</a>
				 </li>
				 <li>
				 <strong>176.013</strong> <a title="rincones" href="http://www.minube.com/rincones" class="inverse">rincones</a>
				 </li>
				 <li>
				 <strong>1.108</strong> <a title="blogs de viaje" href="http://www.minube.com/blogs-viaje" class="inverse">blogs de viaje</a>
				 </li>
				 <li>
				 <strong>844.623</strong> <a title="fotos" href="http://www.minube.com/fotos" class="inverse">fotos</a> y 8.660 <a title="vídeos" href="http://www.minube.com/videos" class="inverse">vídeos</a>
				 </li>
				 <li>
				 <strong>22.965</strong> <a title="ciudades" href="http://www.minube.com/destinos" class="inverse">ciudades</a> y 205 <a title="países" href="http://www.minube.com/destinos" class="inverse">países</a>
				 </li>
			 </ul>
-->
 		</div>

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
		
