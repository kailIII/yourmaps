<?php
	include("include-scripts-headless.php");
	include_once "Config.class.php";
	include_once "Pager/Pager.php";
	
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
		
		$sql = "select count(Tag) As counter, Tag from (Select  Text as Tag, Keywords_Services.fk_keyword_id, fk_wms_id as id from Wms_Keywords, Keywords_Services where Wms_Keywords.pk_id = Keywords_Services.fk_keyword_id) As counter_table group by Tag order by counter desc";
		$statement = $dbh->query($sql);

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
		

			
  		<title>All keywords registered in looking4maps ordered by frequency - Looking for maps: cities and maps of the world</title> 
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
<?	
			if($statement){
				$numResults = $statement->rowCount();
		
				$params = array("totalItems" => $numResults,
								"perPage" => 75,
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
	?>
	
			<div class="span-24 last" id="search-result-message" >
						<p class="added">
						Palabras clave (etiquetas o tags) asociadas a mapas en Looking4Maps. <?= $numResults?> resultados.
						</p>
			</div>
				
			<div class="span-24">
					<div class="span-8">
					<ul id="search-results">
	<?		
			if($numResults > 0){
				$counterKeys = 0;
				while ($r = $stmt2->fetch()){
					$counter = $r['counter'];
					$tag = $r['Tag'];
					
					$counterKeys++;
	?>							
					<li>	
					<a href="http://www.lookingformaps.com/mapsfoundbykeyword.php?keywords=<?=$tag?>" 
								   title="<?= $tag ?>" 
								   id="trail-title"><?= $tag?>
								 </a> - aparece en <?= $counter?> mapas.
					</li>
			
	<?	
					if($counterKeys == 26 ){
	?>				
					  </ul>
					</div>	
					<div class="span-8">
						<ul id="search-results">
	<?				
					}else	if($counterKeys == 51 ){
	?>				
					  </ul>
					</div>	
					<div class="span-8 last">
						<ul id="search-results">
	<?				
					}
					
		        }//while
	?>	        
		       		</ul>
		       		</div>
	<?	       		
		      
			}
		}
	?>		
			

			
			</div>
			
<?
			echo $links["all"];
?>
			<div class="span-24 last">
<?			
			include("most-popular-resume-widget.php");
?>			
			</div>
<?			
			include("producer-widget.php");
			include("tailer-widget.php");

		
}catch(PDOException $e){
	echo $e->getMessage();
}
$dbh = null;
?>			
</body>
</html>