<?php
	include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts/include-scripts-headless.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts/Config.class.php";
	
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
			
			<!-- Put the following javascript before the closing </head> tag. -->
			<script>
			  (function() {
			    var cx = '012893902416075237093:xbcnxlgmx84';
			    var gcse = document.createElement('script'); gcse.type = 'text/javascript'; gcse.async = true;
			    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
			        '//www.google.com/cse/cse.js?cx=' + cx;
			    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(gcse, s);
			  })();
			</script>
<? 
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	echo "<meta name=\"keywords\" content=\"curso de postgis, mapas, maps, wms, cartografia, google maps, gogle\">";
	echo "<meta name=\"Author\" content=\"Alvaro Zabala Ordóñez - azabala@lookingformaps.com, Borja Mañas Alvarez - fmalvarez@lookingformaps.com\" />"; 
	echo "<meta name=\"Subject\" content=\"Documentación gratuita del curso Bases de Datos Espaciales con PostGIS y MySQL\" />"; 
	echo "<meta name=\"Robots\" content=\"index, follow\" />"; 
	//echo "<link rel=\"shortcut icon\" href=\"favicon.ico\">";
	echo "<link rel=\"copyright\" href=\"http://www.gnu.org/copyleft/fdl.html\">";

	echo "<title>Documentación del curso 'Bases de datos espaciales con PostGIS y MySQL' - Looking for maps: search cities and maps of the world </title>";
?>	
		</head>
		
		<body>
			<?php include("menu-header.php")?>

			
			<div class="container"> 
				<div class="span-13">
					<h1>Consigue el manual <strong>"Bases de datos espaciales con PostGIS"</strong></h1>
					<hr class="space"></hr>
					<h2>Apuntate a nuestra <strong><a href="http://eepurl.com/qkYKP" target="_blank">lista de correo</a></strong> y recibe toda la documentación del curso "Bases de datos espaciales con PostGIS y MySQL"</h2>
					<hr class="space"></hr>
					<input type="button" 
						    class="large green button" 
							value="Recibe el curso de PostGIS"
							onClick="javascript:window.location.href='http://eepurl.com/qkYKP'"
							style="font-size:24px"
					/>
									
				</div>
				<div class="prepend-1 span-10 last">
					<img class="aligncenter" alt="Apuntate a nuestra lista de correo y recibirás el material del curso sobre PostGIS y Bases de Datos Espaciales" src="/resources/images/stock_elephant.png">
				</div>
			</div><!-- container -->
			
		<br></br>
<?		
		include("tailer-widget.php");
}catch(PDOException $e){
	echo $e->getMessage();
}
$dbh = null;
?>

		</body>
		
