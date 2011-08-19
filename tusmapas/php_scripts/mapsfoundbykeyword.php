<?php


include("Config.class.php");
include("Pager/Pager.php");

$config = Config::singleton();
$username = $config->username;
$hostname = $config->hostname;
$password = $config->password;

$keyword = $_GET['keywords'];

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
			
		<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
	  		{lang: 'es'}
		</script>
	
<? 
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			
	echo "<meta name=\"keywords\" content=\"".$keyword."mapas, maps, wms, cartografia, google maps, gogle\">";
	echo "<meta name=\"Description\" content=\" Mapas asociados a la palabra clave ".$keyword."\" />"; 
	echo "<meta name=\"Author\" content=\"Alvaro Zabala Ordóñez - azabala@gmail.com\" />"; 
	echo "<meta name=\"Subject\" content=\"Busca ciudades y mapas de todo el mundo: : WMS, KML, KMZ, GPX\" />"; 
	echo "<meta name=\"Robots\" content=\"index, follow\" />"; 
	//echo "<link rel=\"shortcut icon\" href=\"http://localhost/spainholydays/favicon.ico\">";
	echo "<link rel=\"copyright\" href=\"http://www.gnu.org/copyleft/fdl.html\">";

	echo "<title>Results for search ".$keyword." - Looking for maps: cities and maps of the world </title>";
	
	try {
		
		$dbh = new PDO("mysql:host=$hostname;dbname=tusmapas", $username, $password);
//		$statement = $dbh->query("select * from WMS_SERVICES where match(service_title,service_abstract, keywords_list, layer_names, layer_titles) against ('".$keyword."') IN NATURAL LANGUAGE MODE");
		
		$dbh->query("SET CHARACTER SET utf8");
		//FIXME Review the query, sometimes returns duplicates
		$query = "select * from WMS_SERVICES, Wms_Keywords, Keywords_Services where Keywords_Services.fk_wms_id = WMS_SERVICES.pk_id and Keywords_Services.fk_keyword_id = Wms_Keywords.pk_id and Wms_Keywords.text like '%".$keyword."%'";
		$statement = $dbh->query($query);
	?>	
		</head>
		
		<body> 
			<?php include("menu-header.php")?>
			<!-- FIXME Add a BREADCRUMB -->
		
		
		<div class="container">
	<?	
		if($statement->execute()){
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
			$stmt2 = $dbh->query($query. "LIMIT ".$from.", ".$to);
			$stmt2->execute();
	?>
	
			<div class="span-24 last" id="search-result-message" >
						<p class="added">
						Mapas relacionados con la búsqueda <strong><i><?=$keyword?></i></strong>. <?= $numResults?> resultados.
						</p>
			</div>
				
			<div class="span-21">
					<ul id="search-results">
	<?		
			if($numResults > 0){
			
				while ($r = $stmt2->fetch()){
					$title = $r['service_title'];
					$friendlyUrl = $r['friendly_url'];
					$serviceUrl = $r['service_url'];
					$abstract = $r['service_abstract'];
					$wmsVersion = $r['wms_version'];		
		?>								
						<li class="box">
							<h3><a href="mapa.php?mapa=<?= $friendlyUrl?>" 
								   title="<?= $title ?>" 
								   id="trail-title"><?= $title?>
								 </a>
							</h3>
					
							<!--  espacio para meter un mapa?? -->
							<p class="description">
								<strong></strong> 
		<?
					$strlen = strlen($abstract);
					if($strlen > 180){
						echo substr($abstract, 0, 180)."...";
					}else{ 
						echo $abstract;
					}
		?>
							</p>					
				   		</li> 
					
		<?
		        }//while
		        
		       echo "</ul>";
		       echo $links["all"];
			}else{
				echo "<div class='highlight large'>No se han encontrado resultados para el término buscado</div>";
			}
		 ?>   	
		      </div><!-- span-20 -->
		      
		      <div class="span-3 last"><!-- ad slot -->
				<script type="text/javascript"><!--
					google_ad_client = "pub-7845495201990236";
					/* 120x600, looking for maps 1 */
					google_ad_slot = "8783146355";
					google_ad_width = 120;
					google_ad_height = 600;
					//-->
			  	</script>
				<script type="text/javascript"
					src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
				</script>
		      </div>
		   <?
		        include("keywords-widget.php");
				include("producer-widget.php");
				include("tailer-widget.php");
			?>
		</div><!-- container -->
<?
		}else{
			echo "problemas con la bbdd";
		}
}catch(PDOException $e){
	echo $e->getMessage();
}
?>;