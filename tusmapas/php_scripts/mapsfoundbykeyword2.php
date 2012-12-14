<?php
include("include-scripts-headless.php");
include_once "Config.class.php";
include_once "MapUtils.class.php";
include_once "Pager/Pager.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts/simple_html_dom/simple_html_dom.php";

$config = Config::singleton();
$username = $config->username;
$hostname = $config->hostname;
$password = $config->password;
$database = $config->database;

$keyword = $_GET['q'];

if(! isset($_GET['cx'])){
	$_GET['cx']= "012893902416075237093%3Axbcnxlgmx84";
}

if(! isset($_GET['cof'])){
	$_GET['cof']= "FORID:10";
}

if(! isset($_GET['ie'])){
	$_GET['ie']= "UTF-8";
}

if(! isset($_GET['sa'])){
	$_GET['sa']= "Buscar";
}

if(! isset($_GET['ss'])){
	$_GET['ss']= "2344j3042080j3";
}

?>

	<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
	<html dir="ltr" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<link rel="stylesheet" href="../resources/css/blueprint/screen.css" type="text/css" media="screen, projection">
  		<link rel="stylesheet" href="../resources/css/blueprint/print.css" type="text/css" media="print"> 
  		<!--[if lt IE 8]>
    		<link rel="stylesheet" href="../resources/css/blueprint/ie.css" type="text/css" media="screen, projection">
  		<![endif]-->
		
		<link rel="stylesheet" type="text/css" href="../resources/css/mapa.css" />
		<link rel="stylesheet" type="text/css" href="../resources/css/searchmaps.css" />
		<link href="../resources/css/jquery-ui/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css"/>	
<?
			include("include-scripts.php");
?>			
		
			<script>
			$(document).ready(function() {
					<?
						include("include-scripts-facebook.php");
						include("include-scripts-uservoice.php"); 
						include("include-scripts-map-metadata-dialog.php");
					?>
					$(".box").corner();
					
			});
			</script>
			
<? 
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			
	echo "<meta name=\"keywords\" content=\"".$keyword."mapas, maps, wms, cartografia, google maps, gogle\">";
	echo "<meta name=\"Description\" content=\" Mapas asociados a la palabra clave ".$keyword."\" />"; 
	echo "<meta name=\"Author\" content=\"Alvaro Zabala Ordóñez - azabala@gmail.com\" />"; 
	echo "<meta name=\"Subject\" content=\"Busca ciudades y mapas de todo el mundo: : WMS, KML, KMZ, GPX\" />"; 
	echo "<meta name=\"Robots\" content=\"index, follow\" />"; 
	echo "<link rel=\"copyright\" href=\"http://www.gnu.org/copyleft/fdl.html\">";

	echo "<title>Results for search ".$keyword." - Looking for maps: cities and maps of the world </title>";
	?>	
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
		</div>
		<div class="container">	
			<div class="span-24 last" id="search-result-message" >
						<p class="added">
						Mapas relacionados con la búsqueda <strong><i><?=$keyword?></i></strong>. 
						</p>
			</div>
				
			<div class="span-20">
  				<div id="cse-search-results"></div>
    			  <script type="text/javascript">
				      var googleSearchIframeName = "cse-search-results";
				      var googleSearchFormName = "cse-search-box";
				      var googleSearchFrameWidth = 600;
				      var googleSearchDomain = "www.google.com";
				      var googleSearchPath = "/cse";
   				  </script>
    			  <script type="text/javascript" src="http://www.google.com/afsonline/show_afs_search.js"></script>	
		   	 </div><!-- span-20 -->
		      
		      <div class="span-4 last" ><!-- ad slot -->
		       <div style="position:relative;left:20px;padding:0px 7px;">
					<script type="text/javascript"><!--
						google_ad_client = "pub-7845495201990236";
						/* 120x600, looking for maps 1 */
						google_ad_slot = "8783146355";
						google_ad_width = 165;
						google_ad_height = 600;
						//-->
				  	</script>
					<script type="text/javascript"
						src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
					</script>
				</div>
		      </div>
		      </div><!-- container -->
		   <?
//		        include("keywords-widget.php");
//				include("producer-widget.php");
				include("tailer-widget.php");
			?>	
