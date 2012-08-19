<?php

	require_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts/".'Config.class.php';
	
	include($_SERVER["DOCUMENT_ROOT"]."/php_scripts/include-scripts-headless.php");
	
	
	
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
		<link rel="stylesheet" href="./resources/css/blueprint/screen.css" type="text/css" media="screen, projection">
  		<link rel="stylesheet" href="./resources/css/blueprint/print.css" type="text/css" media="print"> 
  		<!--[if lt IE 8]>
    		<link rel="stylesheet" href="./resources/css/blueprint/ie.css" type="text/css" media="screen, projection">
  		<![endif]-->
<!--		<link rel="stylesheet" href="./resources/css/blueprint/plugins/screen.css" type="text/css" media="print"> -->
		
		<link rel="stylesheet" type="text/css" href="./resources/css/mapa.css" />
		<link rel="stylesheet" type="text/css" href="./resources/css/searchmaps.css" />

		
		<script type="text/javascript" language="javascript" src="./resources/js/jquery-1.6.1.js">
		</script>	
		
		<script type="text/javascript" src="./resources/js/jquery.corner.js?v2.11">
		</script>
		
		<link href="./resources/css/jquery-ui/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css"/>
			
	<?
			include ($_SERVER["DOCUMENT_ROOT"]."/php_scripts/include-scripts.php");
?>	
		
		<script>
	 	 $(document).ready(function(){
	 		<?
					include( $_SERVER["DOCUMENT_ROOT"]."/php_scripts/include-scripts-facebook.php");
					include( $_SERVER["DOCUMENT_ROOT"]."/php_scripts/include-scripts-uservoice.php"); 
				?>
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

	echo "<title>Error 400 - Looking for maps: search cities and maps of the world </title>";
?>	
		</head>
		
		<body>

			<?php 
			include($_SERVER["DOCUMENT_ROOT"]."/php_scripts/".'menu-header.php');
			?>
			
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
				
				<div class="span-8 preppend-3 last" id="introduction">
					<h2 class="left">
						Error 400
					</h2>
				</div>
				<div class="span-24 last">
				<?php 
					include($_SERVER["DOCUMENT_ROOT"]."/php_scripts/".'adsense.php');
				?>
				</div>
				
				<div class="span-24 last">
						<p class="alert large loud">
						<br></br>Se ha recibido una petición incorrecta en el servidor.
							Por favor, revise su enlace, o bien vuelva a la <a href="/">página de inicio </a>
							o si es usuario registrado a su <a  href="user-info.php" >página de entrada</a>. Puede registrarse con
							su <a  href="<?=$loginUrl?>" >cuenta de Facebook</a>
						</p>
				</div>
			</div><!-- container -->

<?
		include( $_SERVER["DOCUMENT_ROOT"]."/php_scripts/"."tailer-widget.php");
		
}catch(PDOException $e){
	echo $e->getMessage();
}
$dbh = null;
?>

		</body>
		
