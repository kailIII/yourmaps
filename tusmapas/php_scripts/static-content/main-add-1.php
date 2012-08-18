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

	echo "<title>Garmin Forerunner 410, Monitor de ritmo cardíaco, 124 x 95 Pixeles, 2.69 x 2.69 cm (1.06 x 1.06 pulgadas), 48 x 16 x 71 mm, 60 g, Windows XP Mac OS X, Negro, Plata - Looking for maps: search cities and maps of the world </title>";
?>	
		</head>
		
		<body>
			<a class="fixed_banner" href="http://www.amazon.es/gp/product/B0046BTK14/ref=as_li_ss_tl?ie=UTF8&tag=oposicionesti-21&linkCode=as2&camp=3626&creative=24822&creativeASIN=B0046BTK14">Comprar este producto en Amazon</a><img src="http://www.assoc-amazon.es/e/ir?t=oposicionesti-21&l=as2&o=30&a=B0046BTK14" width="1" height="1" border="0" alt="" style="border:none !important; margin:0px !important;" />
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
					<h2><a href="http://www.amazon.es/gp/product/B0046BTK14/ref=as_li_ss_tl?ie=UTF8&tag=oposicionesti-21&linkCode=as2&camp=3626&creative=24822&creativeASIN=B0046BTK14">Garmin Forerunner 410, Monitor de ritmo cardíaco, 124 x 95 Pixeles, 2.69 x 2.69 cm (1.06 x 1.06 "), 48 x 16 x 71 mm, 60 g, Windows XP Mac OS X, Negro, Plata</a><img src="http://www.assoc-amazon.es/e/ir?t=oposicionesti-21&l=as2&o=30&a=B0046BTK14" width="1" height="1" border="0" alt="" style="border:none !important; margin:0px !important;" />
</h2>
				</div>
			</div>
		
			<div class="container">
				<hr></hr>
				<div class="span-8">
					<img src="http://ecx.images-amazon.com/images/I/31lxTn3TUaL._SL500_AA300_.jpg"></img>
				</div>
				<div class="span-16 last">
					<p>
					<h3>Rápido y fácil de usar</h3><br/>
					<h4>
					Este avanzado reloj deportivo incluye GPS y registra con precisión el tiempo, 
					el ritmo, la distancia, la frecuencia cardiaca, la altura y mucho más. 
					La unidad Forerunner 410 luce un bisel táctil mejorado que te permite desplazarte 
					rápidamente y seleccionar las funciones sobre la marcha, bajo cualquier condición 
					meteorológica. Cuando termines de entrenar, la unidad Forerunner 410 seguirá trabajando. 
					Cargará de forma inalámbrica los datos a Garmin Connect™ cuando esté en el área de alcance de 
					tu ordenador, para que puedas revisar la carrera cuando lo desees. 
					Funciona mediante la tecnología inalámbrica ANT+™ y el USB Stick que incluye el reloj. 
					Sin cables, sin cargas manuales y sin tener que sudar.
					</h4>
					<br></br>
					<h3>Opiniones de compradores - Ruth</h3><br/>
					<h4>
					Llevo ya con este producto casi dos semanas, y estoy encantada. Después de tantas críticas que había leído del 
					bisel táctil tenía mucho miedo, pero efectivamente en cuestión de horas estaba todo controlado.
					Es cómodo, trae las dos muñequeras de repuesto, y la cinta pulsómetro (la premium de Garmin) es una maravilla, casi ni la notas. En cuanto al funcionamiento, es todo muy sencillo y muy configurable: entrenamientos avanazados, autopausa, captación de la señal de satélite muy rápida... Además el Training Center y el Garmin Connect son muy completos, y hacen que tengas tus datos tanto en tu ordenador (admite Mac, lo que para mí es un puntazo) como en la web de Garmin.
					Lo recomiendo al 100% a cualquier runner de nivel medio que se proponga seguir un poco en serio sus evoluciones.
					De la compra a Amazon, nada nuevo que decir, todo perfecto y en 24h lo tenía en mi casa, un lujazo.
					</h4>
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
		
