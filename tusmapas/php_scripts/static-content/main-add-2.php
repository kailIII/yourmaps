<?php
	include("../include-scripts-headless.php");
	include_once "../Config.class.php";
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
		<link rel="stylesheet" href="../../../resources/css/blueprint/screen.css" type="text/css" media="screen, projection">
  		<link rel="stylesheet" href="../../../resources/css/blueprint/print.css" type="text/css" media="print"> 
  		<!--[if lt IE 8]>
    		<link rel="stylesheet" href="../../../resources/css/blueprint/ie.css" type="text/css" media="screen, projection">
  		<![endif]-->
<!--		<link rel="stylesheet" href="../../../resources/css/blueprint/plugins/screen.css" type="text/css" media="print"> -->
		
		<link rel="stylesheet" type="text/css" href="../../../resources/css/mapa.css" />
		<link rel="stylesheet" type="text/css" href="../../../resources/css/searchmaps.css" />

		
		<script type="text/javascript" language="javascript" src="../../../resources/js/jquery-1.6.1.js">
		</script>	
		
		<script type="text/javascript" src="../../../resources/js/jquery.corner.js?v2.11">
		</script>
		
		<link href="../../../resources/css/jquery-ui/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css"/>
	
		<?
			include($_SERVER["DOCUMENT_ROOT"]."/php_scripts/"."include-scripts.php");
		?>	
			
		<script>
	 	 $(document).ready(function(){
	 		<?
					include($_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/include-scripts-facebook.php");
					include($_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/include-scripts-uservoice.php");
					include($_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/include-scripts-map-metadata-dialog.php");
				?>
		  });
	    </script>	
<? 
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	echo "<meta name=\"keywords\" content=\"garmin, gps, pulsera, runners, forerunner 410, monitor de ritmo cardiaco\">";
	echo "<meta name=\"Description\" content=\"Descripcion de lookingformaps.com\" />"; 
	echo "<meta name=\"Author\" content=\"Alvaro Zabala Ordóñez - azabala@gmail.com\" />"; 
	echo "<meta name=\"Subject\" content=\"Garmin Forerunner, reloj GPS de pulsera para runners o aficionados al senderismo\" />"; 
	echo "<meta name=\"Robots\" content=\"index, follow\" />"; 
	//echo "<link rel=\"shortcut icon\" href=\"http://localhost/spainholydays/favicon.ico\">";
	echo "<link rel=\"copyright\" href=\"http://www.gnu.org/copyleft/fdl.html\">";

	echo "<title>Garmin Edge 800 - Navegador para bicicleta (GPS, pantalla táctil, cardiómetro + cadencia) - Looking for maps: search cities and maps of the world </title>";
?>	
		</head>
		
		<body>
			<a class="fixed_banner" href="http://www.amazon.es/gp/product/B00424LN5G/ref=as_li_ss_tl?ie=UTF8&tag=oposicionesti-21&linkCode=as2&camp=3626&creative=24822&creativeASIN=B00424LN5G">Comprar este producto en Amazon</a><img src="http://www.assoc-amazon.es/e/ir?t=oposicionesti-21&l=as2&o=30&a=B00424LN5G" width="1" height="1" border="0" alt="" style="border:none !important; margin:0px !important;" />
			
			<?php include($_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/menu-header.php");?>
			
			<div class="container">
				<div class="span-24 last">
					<script type="text/javascript">
						google_ad_client = "ca-pub-7845495201990236";
						/* lookingformaps2 */
						google_ad_slot = "9961918851";
						google_ad_width = 728;
						google_ad_height = 90;
						//
					</script>
					<script type="text/javascript"
					src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
					</script>
					<h2><a href="http://www.amazon.es/gp/product/B00424LN5G/ref=as_li_ss_tl?ie=UTF8&tag=oposicionesti-21&linkCode=as2&camp=3626&creative=24822&creativeASIN=B00424LN5G">Garmin Edge 800 - Navegador para bicicleta (GPS, pantalla táctil, cardiómetro + cadencia)</a><img src="http://www.assoc-amazon.es/e/ir?t=oposicionesti-21&l=as2&o=30&a=B00424LN5G" width="1" height="1" border="0" alt="" style="border:none !important; margin:0px !important;" /></h2>
				</div>
			</div>
		
			<div class="container">
				<hr></hr>
				<div class="span-8">
					<img src="https://images-na.ssl-images-amazon.com/images/I/51FXLVDB4eL._SL500_AA300_.jpg"></img>
				</div>
				<div class="span-16 last">
					<p>
					<h3>Descripción del producto</h3><br/>
					<h4>
					El GPS Edge800 de Garmin acompañará a los más deportistas en sus excursiones 
					a pié o en bicicleta. Este sistema de navegación te ayudará a hacer
					 un seguimiento dela distancia, la posición y la velocidad.   
					 El Edge800 es un auténtico entrenador que propone numerosas opciones para
					 calcular en la cantidad de calorías quemadas, la frecuencia cardiaca y 
					 te avisacuando estés alcanzando tu objetivo. 
					 Gracias a Garmin Connect, el GPS Edge 800 recibe el recorrido grabado por otro
					  ciclista, lo que te permitirá medirte con otros usuarios.   
					  El Edge 800 de Garmin viene con un cinturón para medir la frecuencia cardiaca 
					  HRM y un cadenciómetro.
					</h4>
					<br></br>
		
					</p>
				</div>
				
			</div>
			

<?
	include($_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/tailer-widget.php");		
}catch(PDOException $e){
	echo $e->getMessage();
}
$dbh = null;
?>

		</body>