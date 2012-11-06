<?php
include("include-scripts-headless.php");	
include_once "Config.class.php";
include("MapUtils.class.php");
include_once "Pager/Pager.php";


$config = Config::singleton();
$username = $config->username;
$hostname = $config->hostname;
$password = $config->password;
$database = $config->database;


$xmin = $_GET['xmin'];
$ymin = $_GET['ymin'];
$xmax = $_GET['xmax'];
$ymax = $_GET['ymax'];



try {
		$dbh = new PDO("mysql:host=$hostname;dbname=$database",
		 								$username, $password, 
							 array(PDO::ATTR_PERSISTENT => true));
		 
		$dbh->query("SET CHARACTER SET utf8");
		
		$sql = "select * from (SELECT service_url,friendly_url, xmin, ymin, xmax, ymax, (xmax-xmin) width, keywords_list, service_title, service_abstract, contact_organisation, layer_names, layer_titles, crs, is_queryable, wms_version, 'WMS' as type, pk_id FROM WMS_SERVICES WHERE MBRIntersects(GeomFromText('POLYGON(($xmin $ymin, $xmax $ymin, $xmax $ymax, $xmin $ymax, $xmin $ymin  ))'),BBOX) ".
			"UNION ALL ".
            " SELECT url_origen,friendly_url, xmin, ymin, xmax, ymax, (xmax-xmin) width,'', document_name, description, origen, '', '','EPSG:4326',1,'','KML' as type, pk_gid FROM KML_SERVICES WHERE MBRIntersects(GeomFromText('POLYGON(($xmin $ymin, $xmax $ymin, $xmax $ymax, $xmin $ymax, $xmin $ymin ))'),BBOX) ) as maps order by width asc";
		
		$statement = $dbh->query($sql);
	
		if($row = $statement->fetch()){
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
	echo "<meta name=\"keywords\" content=\"mapas, maps, wms, cartografia, google maps, gogle\">";
	echo "<meta name=\"Description\" content=\" Mapas asociados a la palabra clave ".$keyword."\" />"; 
	echo "<meta name=\"Author\" content=\"Alvaro Zabala Ordóñez - azabala@gmail.com\" />"; 
	echo "<meta name=\"Subject\" content=\"Busca ciudades y mapas de todo el mundo: : WMS, KML, KMZ, GPX\" />"; 
	echo "<meta name=\"Robots\" content=\"index, follow\" />"; 
	//echo "<link rel=\"shortcut icon\" href=\"http://localhost/spainholydays/favicon.ico\">";
	echo "<link rel=\"copyright\" href=\"http://www.gnu.org/copyleft/fdl.html\">";

	echo "<title>Maps around of  $xmin $ymin $xmax  $ymax - Looking for maps: cities and maps of the world </title>";
?>
		</head>
		
		<body> 
			<?php include("menu-header.php")?>
			<!-- FIXME Add a BREADCRUMB -->
		
		
		<div class="container">
	<?	
			$numResults = $statement->rowCount();
		
			$params = array("totalItems" => $numResults,
							"perPage" => 5,
							"delta" => 2,
							"mode" => "Sliding");
			$pagerFactory = new Pager();
			$pager = & $pagerFactory->factory($params);
			
			$links = $pager->getLinks();
			

			// offset setup
			list($from, $to) = $pager->getOffsetByPageId();
			$from = $from - 1;
			$perPage = $params['perPage'];
			
			// 2nd query based on 1st with LIMIT – this will be displaying data per page
			$stmt2 = $dbh->query($sql. " LIMIT ".$from.", ".$perPage);
			$stmt2->execute();
	?>
	
			<div class="span-24 last" id="search-result-message" >
						<p class="added">
						Mapas en el entorno de las coordenadas <strong><i><?=$xmin?>, <?=$ymin?>, <?=$xmax?>, <?=$ymax?></i></strong>. <?= $numResults?> resultados.
						</p>
			</div>
				
			<div class="span-20">
					<ul id="search-results">
	<?		
			if($numResults > 0){
				while ($r = $stmt2->fetch()){
					$title = $r['service_title'];
					$serviceUrl = $r['service_url'];
					$abstract = $r['service_abstract'];
					$friendlyUrl = $r['friendly_url'];	
					
					
					$serviceType = $r['service_type'];//WMS, KML
					
					
					$contactOrganization = $r['contact_organisation'];
					
					
					$serviceLogo = "";
					$echoAbstract = "";
					
					$mapUtil = MapUtils::singleton();
					
					$strlen = strlen($abstract);
					if($strlen > 180){
						$echoAbstract = substr($abstract, 0, 180)."...";
					}else{ 
						$echoAbstract = $abstract;
					}
					
					if($serviceType == "KML"){
						$keywords = $mapUtil->getKeywords($dbh, $r[0], "KML" );
						
						if($contactOrganization == "Wikiloc"){
							$serviceLogo = 
							"<a href=\"http://www.wikiloc.com\"><img src=\"http://www.wikiloc.com/wikiloc/images/wikiloc.png\" alt=\"Wikiloc\"></a>";
							
							if($title == "KML document generated at Wikiloc - http://www.wikiloc.com"){
								$title = "Wikiloc: ";
								
								$numKeywords = sizeof($keywords);
								for($j = 0; $j < $numKeywords - 1; $j++ ){
									$title .= $keywords[$j]["text"] . ", ";
								}//for
								$title .= $keywords[$numKeywords - 1]["text"];
							}//if title
							
							if(strpos($abstract, "<table") === 0){
								$results = array();
								//$pattern = "%<;table[^>;]*>;<;tr[^>;]*>;<;td[^>;]*>;<;a[^>;]*>;<;img[^>;]*>;<;/a>;<;/td>;<;/tr>;<;tr[^>;]*>;<;td[^>;]*>;(.*?)<;/td>;<;/tr>;.*?$%";
								$pattern = "%<table[^>]*><tr[^>]*><td[^>]*><a[^>]*><img[^>]*></a></td></tr><tr[^>]*><td[^>]*>(.*?)</td></tr>.*?$%";
								$numMatches = preg_match($pattern, $abstract, $results);
								$echoAbstract = $results[1] ;
							}else {
								$echoAbstract =  $abstract;
							}
						}//if wikiloc
					}else if($serviceType = "WMS"){
						$keywords = $mapUtil->getKeywords($dbh, $r[0], "WMS" );
					}
											
		?>								
						<li class="box">
							<?=$serviceLogo?>
							<h3>
								<a href="mapa.php?mapa=<?= $friendlyUrl?>" 
								   title="<?= $title ?>" 
								   id="trail-title"><?= $title?>
								 </a>
							</h3>
				
							<p class="description">
								<?=$echoAbstract?>
							</p>	
		<?					
							$numKeywords = sizeof($keywords);
							if($numKeywords > 0){
								$kwTxt = "Palabras clave: ";
								for($j = 0; $j < $numKeywords - 1; $j++ ){
										$text = $keywords[$j]["text"];
										$fUrl = $keywords[$j]["friendly_url_text"];
										$computed = $keywords[$j]["computed"];
										
										$kwTxt .= "<a href='mapsfoundbykeyword.php?keywords=".$fUrl."'><i>".$text."</i></a> , ";
								}//for
							
								$text = $keywords[$numKeywords - 1]["text"];
								$fUrl = $keywords[$numKeywords - 1]["friendly_url_text"];
								$computed = $keywords[$numKeywords - 1]["computed"];
									
								$kwTxt .= "<a href='mapsfoundbykeyword.php?keywords=".$text."'><i>".$text."</i></a>";
								echo "<br><p class='description'>".$kwTxt."</p>";
							}		
		?>									
				   		</li> 				
		<?
		        }//while
		        
		       echo "</ul>";
		       echo $links["all"];
			}else{
				echo "<div class='highlight large'>No se han encontrado resultados para el término buscado</div>";
				include("adsense.php");
			}
		 ?>   	
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
		        include("keywords-widget.php");
				include("producer-widget.php");
				include("tailer-widget.php");
			?>	
<?
		}else{
		echo "<div class='highlight large'>No se han encontrado resultados en el entorno $xmin $ymin $xmax $ymax</div>";
		include("adsense.php");
		
		}
}catch(PDOException $e){
	echo $e->getMessage();
}
$dbh = null;
?>
	</body>
</html>