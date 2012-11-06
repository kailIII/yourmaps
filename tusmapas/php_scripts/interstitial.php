<?php
	include("include-scripts-headless.php");
	include_once "Config.class.php";
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
			 
			
			 var count = 10;
			 countdown = setInterval(function(){
				 if(count >= 0){
			    	$("h3.countdown").html(count + " segundos restantes...");
				 }else {
					clearInterval(countdown);
					$("h3.countdown").html("Finalizada la espera. En breve comenzará la descarga");
				 }
				 if (count == 0) {
				      window.location = 'get-kml.php?mapa=<?=$requiredMap?>';
				 }
				 count--;
			 }, 1000);//setInterval
		}));			 
	  </script>
	  		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?
			$requiredMap = $_GET['mapa'];
			
			echo "<meta name=\"keywords\" content=\"mapas, maps, wms, cartografia, google maps, gogle, kml, cartography, turismo, viajes, hoteles, tourism, trips, journeys, vinos, gastronomia\">";
			echo "<meta name=\"Description\" content=\"Descarga de capas y mapas en formato KML. Mapas del mundo basados en formatos estándar: WMS, WFS, KML, GPX, DXF, etc.\" />"; 
			echo "<meta name=\"Author\" content=\"Alvaro Zabala Ordóñez - azabala@gmail.com\" />"; 
			echo "<meta name=\"Subject\" content=\"Mapas del mundo basados en servicios estándar: KML, WMS, WMS-T, etc.\" />"; 
			echo "<meta name=\"Robots\" content=\"index, follow\" />"; 
			//echo "<link rel=\"shortcut icon\" href=\"http://localhost/spainholydays/favicon.ico\">";
			
			
			echo "<link rel=\"copyright\" href=\"http://www.gnu.org/copyleft/fdl.html\">";
			
?>
		

			
  		<title>Thanks for visualizing our maps with Google Earth: starting your download - Looking for maps: cities and maps of the world</title> 
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
				
				<div class="span-24 last">
					<script type="text/javascript">
					<!--
					google_ad_client = "pub-7845495201990236";
					google_ad_width = 336;
					google_ad_height = 300;
					google_ad_slot = "3179454701";
					//-->
					</script>
					<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
					</script> 
					<script type="text/javascript"><!--
					google_ad_client = "pub-7845495201990236";
					google_ad_width = 336;
					google_ad_height = 300
					google_ad_slot = "3179454701";
					//-->
					</script>
					<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
					</script> 

					<h3 class="countdown large loud">Iniciando descarga...</h3>
				 	<h3 class="large loud">Si la descarga no comienza automáticamente, visite este 
				 	<a href='get-kml.php?mapa=<?=$requiredMap?>'>enlace</a> 
				 	o vuelva a la <a href="javascript:history.back(-1)">página del mapa</a>
				 	<p>Registrate para evitar estas esperas en las descargas de mapas.</p>
				 	
				 	</h3>
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