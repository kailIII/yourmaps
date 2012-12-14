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
	//echo "<link rel=\"shortcut icon\" href=\"http://localhost/spainholydays/favicon.ico\">";
	echo "<link rel=\"copyright\" href=\"http://www.gnu.org/copyleft/fdl.html\">";

	echo "<title>Results for search ".$keyword." - Looking for maps: cities and maps of the world </title>";
	
	try {
		
		$dbh = new PDO("mysql:host=$hostname;dbname=$database", $username, $password, array(
   			 PDO::ATTR_PERSISTENT => true
		));
//		$statement = $dbh->query("select * from WMS_SERVICES where match(service_title,service_abstract, keywords_list, layer_names, layer_titles) against ('".$keyword."') IN NATURAL LANGUAGE MODE");
		
		$dbh->query("SET CHARACTER SET utf8");
		//FIXME Review the query, sometimes returns duplicates
		/**
        there werent duplicates. the reason was that some keywords contained others: 'andalucia' and 'fuentes de andalucia'. 
        If a service have two keywords like that, it was listed two times.
        
        We must do a union with two queries:
        
        	select * from WMS_SERVICES, Wms_Keywords, Keywords_Services where Keywords_Services.fk_wms_id = WMS_SERVICES.pk_id and Keywords_Services.fk_keyword_id = Wms_Keywords.pk_id and Wms_Keywords.text like '%andalucia%' group by WMS_SERVICES.pk_id
		 
		and
		
			select * from KML_SERVICES,Wms_Keywords, Keywords_Services where Keywords_Services.fk_wms_id = KML_SERVICES.pk_gid and Keywords_Services.fk_keyword_id = Wms_Keywords.pk_id and Keywords_Services.service_type = 'KML' and Wms_Keywords.text like 'Sant Feliu De Guixols' group by PK_GID
		 */
		//FIXME En vez de text en la where no se deberia utilizar frienlyurl-txt??
//		$query = "select 
//					WMS_SERVICES.pk_id, 
//					friendly_url, 
//					contact_organisation, 
//					service_url, 
//					service_title, 
//					service_abstract, xmin, ymin, xmax, ymax, Wms_Keywords.pk_id, Wms_Keywords.text, Wms_Keywords.friendly_url_text, Wms_Keywords.computed, Keywords_Services.fk_keyword_id, Keywords_Services.fk_wms_id, Keywords_Services.service_type from WMS_SERVICES, Wms_Keywords, Keywords_Services where Keywords_Services.fk_wms_id = WMS_SERVICES.pk_id and Keywords_Services.fk_keyword_id = Wms_Keywords.pk_id and Keywords_Services.service_type = 'WMS' and (Wms_Keywords.friendly_url_text like '".$keyword."' or Wms_Keywords.text like '".$keyword."' or service_title like '%".$keyword."%' or service_abstract like '%".$keyword."%' )group by WMS_SERVICES.PK_ID".
//		" union all select pk_gid, friendly_url, origen, url_origen, document_name, description, xmin, ymin, xmax, ymax, Wms_Keywords.pk_id, Wms_Keywords.text, Wms_Keywords.friendly_url_text, Wms_Keywords.computed, Keywords_Services.fk_keyword_id, Keywords_Services.fk_wms_id, Keywords_Services.service_type  from KML_SERVICES , Wms_Keywords, Keywords_Services where Keywords_Services.fk_wms_id = KML_SERVICES.pk_gid and Keywords_Services.fk_keyword_id = Wms_Keywords.pk_id and Keywords_Services.service_type = 'KML' and ( Wms_Keywords.friendly_url_text like '".$keyword."'  or Wms_Keywords.text like '".$keyword."' or document_name like '%".$keyword."%' or description like '%".$keyword."%') group by PK_GID";
		
		
		 $query = "select WMS_SERVICES.pk_id, friendly_url, contact_organisation, service_url, service_title, service_abstract, xmin, ymin, xmax, ymax ".
				  " from WMS_SERVICES, Wms_Keywords, Keywords_Services ". 
				  "where Keywords_Services.fk_wms_id = WMS_SERVICES.pk_id and Keywords_Services.fk_keyword_id = Wms_Keywords.pk_id and ". 
					"Keywords_Services.service_type = 'WMS' and (Wms_Keywords.text like '".$keyword."' or Wms_Keywords.friendly_url_text like '".$keyword."' or ". 	 
	     			"MATCH(service_title, service_abstract, service_url ) AGAINST( '".$keyword."' IN NATURAL LANGUAGE MODE) ".
					") group by WMS_SERVICES.PK_ID ".
					" union all ". 
					" select pk_gid, friendly_url, origen, url_origen, document_name, description, xmin, ymin, xmax, ymax from KML_SERVICES , ". 
     				" Wms_Keywords,  Keywords_Services ". 
					" where Keywords_Services.fk_wms_id = KML_SERVICES.pk_gid and Keywords_Services.fk_keyword_id = Wms_Keywords.pk_id and ". 
     				" Keywords_Services.service_type = 'KML' and ". 
					" ( Wms_Keywords.text like '".$keyword."' or Wms_Keywords.friendly_url_text like '".$keyword."' or MATCH(url_origen, document_name, description ) AGAINST('".$keyword."' IN NATURAL LANGUAGE MODE) ".
					") group by PK_GID";
//					" ( MATCH(Wms_Keywords.text, Wms_Keywords.friendly_url_text) AGAINST('".$keyword."' IN NATURAL LANGUAGE MODE) or  MATCH(url_origen, document_name, description ) AGAINST('".$keyword."' IN NATURAL LANGUAGE MODE) ".
//					") group by PK_GID";
		 
		$statement = $dbh->query($query);
	?>	
		</head>
		
		<body> 
			<?php include("menu-header.php")?>
			<!-- FIXME Add a BREADCRUMB 
			Añadir también cuadro de entrada de texto para hacer otra búsqueda
			
			
			-->
		
		
		<div class="container">
	<?	
		if($statement){
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
//			$stmt2 = $dbh->query($query. " LIMIT ".$from.", ".$to);
			$stmt2 = $dbh->query($query. " LIMIT ".$from.", ".$perPage);
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
			<div class="span-24 last" id="search-result-message" >
						<p class="added">
						Mapas relacionados con la búsqueda <strong><i><?=$keyword?></i></strong>. <?= $numResults?> resultados.
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
					
					$abstract = str_get_html($abstract)->plaintext;
					
					
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
		?>
				<div class='highlight large'>No se han encontrado resultados para el término buscado</div>
				<div class='large'>Ahora mismo, estos son los mapas que despiertan mayor interés en Looking4Maps.</div>
		<?
				include("most-populars-widget.php");	
			
				
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
//		        include("keywords-widget.php");
//				include("producer-widget.php");
				include("tailer-widget.php");
			?>	
<?
		}else{
			echo "problemas con la bbdd";
		}
}catch(PDOException $e){
	echo $e->getMessage();
}
?>