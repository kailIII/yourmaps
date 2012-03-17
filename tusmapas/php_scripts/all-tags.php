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
<html dir="ltr" xml:lang="es" lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="stylesheet" href="../resources/css/blueprint/screen.css" type="text/css" media="screen, projection">
  	<link rel="stylesheet" href="../resources/css/blueprint/print.css" type="text/css" media="print"> 
	<!--[if lt IE 8]>
    <link rel="stylesheet" href="../resources/css/blueprint/ie.css" type="text/css" media="screen, projection">
  	<![endif]-->
	<link rel="stylesheet" type="text/css" href="../resources/css/mapa.css" />
	
	<link href="../resources/css/jquery-ui/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css"/>
			
<?
			include("include-scripts.php");
?>	
		
	<script language="javascript">
		$(document).ready((function(){
<?
			include("include-scripts-facebook.php");
			include("include-scripts-uservoice.php");
?>
		}));			 
	  </script>
	  
	  		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?		
			echo "<meta name=\"keywords\" content=\"mapas, maps, wms, cartografia, google maps, gogle, kml, cartography, turismo, viajes, hoteles, tourism, trips, journeys, vinos, gastronomia\">";
			echo "<meta name=\"Description\" content=\"Descarga de capas y mapas en formato KML. Mapas del mundo basados en formatos estándar: WMS, WFS, KML, GPX, DXF, etc.\" />"; 
			echo "<meta name=\"Author\" content=\"Alvaro Zabala Ordóñez - azabala@gmail.com\" />"; 
			echo "<meta name=\"Subject\" content=\"Mapas del mundo basados en servicios estándar: KML, WMS, WMS-T, etc.\" />"; 
			echo "<meta name=\"Robots\" content=\"index, follow\" />"; 
			//echo "<link rel=\"shortcut icon\" href=\"http://localhost/spainholydays/favicon.ico\">";
			
			
			echo "<link rel=\"copyright\" href=\"http://www.gnu.org/copyleft/fdl.html\">";
			
?>
		

			
  		<title>All map producers registered in looking4maps - Looking for maps: cities and maps of the world</title> 
</head>

<body>
		<?php include("menu-header.php")?>
			<div class="container">
				<div class="span-24 last">
					PAGINA PARA TODAS LAS ETIQUETAS
				
				<div class="span-24 last">
					

				
				</div>
			</div>
<?
			include("keywords-widget.php");
			include("tailer-widget.php");

		
}catch(PDOException $e){
	echo $e->getMessage();
}
$dbh = null;
?>			
</body>
</html>