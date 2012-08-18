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
	echo "<meta name=\"keywords\" content=\"maps, mapas, service terms, looking for maps, www.lookingformaps.com, garmin, gps, pulsera, runners, forerunner 410, monitor de ritmo cardiaco\">";
	echo "<meta name=\"Description\" content=\"Service terms of lookingformaps.com\" />"; 
	echo "<meta name=\"Author\" content=\"Alvaro Zabala Ordóñez - azabala@gmail.com\" />"; 
	echo "<meta name=\"Subject\" content=\"Service terms of www.lookingformaps.com\" />"; 
	echo "<meta name=\"Robots\" content=\"index, follow\" />"; 
	//echo "<link rel=\"shortcut icon\" href=\"http://localhost/spainholydays/favicon.ico\">";
	echo "<link rel=\"copyright\" href=\"http://www.gnu.org/copyleft/fdl.html\">";

	echo "<title>Service terms - Looking for maps: search cities and maps of the world </title>";
?>	
		</head>
		
		<body>
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
				</div>
			</div>
		
			<div class="container">
				<hr></hr>
				<div class="span-24 last">
					
					<h1>Acuerdos del servicio www.lookingformaps.com</h1>
					
					<h2>Política de privacidad</h2>
					<p>
					Por medio de nuestra política de privacidad le ponemos al tanto de las 
					debidas condiciones de uso de este sitio.
					La utilización de éste portal implica su aceptación plena y sin reservas a todas 
					y cada una de las disposiciones incluidas en este Aviso Legal, 
					por lo que si usted no está de acuerdo con cualquiera de las condiciones aquí 
					establecidas, no deberá usar u/o acceder a este sitio.

					Reservamos el derecho a modificar esta Declaración de Privacidad en 
					cualquier momento. Su uso continuo de cualquier porción de este sitio tras 
					la notificación o anuncio de tales modificaciones constituirá su aceptación 
					de tales cambios.
					</p>
					<h2>Cookies</h2>
					<p>
					Este sitio hace uso de cookies, que son pequeños ficheros de 
					datos que se generan en su ordenador, para enviar información sin proporcionar 
					referencias que permitan deducir datos personales de este.
					Usted podrá configurar su navegador para que notifique y rechace 
					la instalación de las cookies enviadas por este sitio, sin que ello perjudique 
					la posibilidad de acceder a los contenidos. Sin embargo, no nos responsabilizamos 
					de que la desactivación de los mismos impida el buen funcionamiento del sitio.
					</p>
					<h2>
					Marcas Web o Web Beacons.
					</h2>
					<p>
					Al igual que las cookies este sitio también puede contener web beacons,
					 un archivo electrónico gráfico que permite contabilizar a los usuarios que acceden 
					 al sitio o acceden a determinadas cookies del mismo, de esta manera, 
					 podremos ofrecerle una experiencia aún más personalizada.
					</p>
					<h2>Política De Privacidad De Publicidad y Fuentes de Rastreo Proporcionadas En Este Sitio:</h2>
					<p>
					<ul>
					<li>
					Google Adsense: http://www.google.com/intl/es_ALL/privacypolicy.html
					</li>
					<li>
					Google (Analytics): http://www.google.com/intl/es_ALL/privacypolicy.html
					</li>
					</ul>
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
		
