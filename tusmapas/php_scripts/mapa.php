<?php

include("include-scripts-headless.php");
include_once "Config.class.php";
include_once "MapUtils.class.php";
$config = Config::singleton();
$username = $config->username;
$hostname = $config->hostname;
$password = $config->password;
$database = $config->database;


$api = "openlayers";
$requiredMap = $_GET['mapa'];


$height = 356;
$width = 350;
try {
		$dbh = new PDO("mysql:host=$hostname;dbname=$database",
		 								$username, $password, 
							 array(PDO::ATTR_PERSISTENT => true));
		 
		$dbh->query("SET CHARACTER SET utf8");
		
		$statement = $dbh->query("SELECT service_url,friendly_url, xmin, ymin, xmax, ymax, keywords_list, service_title, service_abstract, contact_organisation, layer_names, layer_titles, crs, is_queryable, wms_version, 'WMS' as type, pk_id FROM WMS_SERVICES where friendly_url = '".$requiredMap."' UNION ALL SELECT url_origen,friendly_url, xmin, ymin, xmax, ymax, '', document_name, description, origen, '', '','EPSG:4326',1,'','KML' as type, pk_gid FROM KML_SERVICES where friendly_url = '".$requiredMap."'");
		
		$statement->execute();
		if($row = $statement->fetch()){
			
			$url = $row['service_url'];
			
			$xmin = $row['xmin'];
			$ymin = $row['ymin'];
			$xmax = $row['xmax'];
			$ymax = $row['ymax'];
			
			$friendlyUrl = $row['friendly_url'];
			
			$type = $row['type'];
			
			$serviceTitle = $row ['service_title'];
			$serviceAbstract = $row ['service_abstract'];
			$productor = $row ['contact_organisation'];
			
			$layerNames = $row['layer_names'];
			$layerTitles = $row['layer_titles'];
			$crs = $row['crs'];
			if($crs == null)
				$crs = '';
			$isQueryable = $row['is_queryable'];
			if($isQueryable == null)
				$isQueryable = 0;
				
			$wmsVersion = $row['wms_version'];
			if($wmsVersion == null)
				$wmsVersion = '';
							
			
			$keywords;
			$mapUtil = MapUtils::singleton();
?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
		<html dir="ltr" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" >
		<head>
			<link rel="stylesheet" href="../resources/css/blueprint/screen.css" type="text/css" media="screen, projection">
  			<link rel="stylesheet" href="../resources/css/blueprint/print.css" type="text/css" media="print"> 
	  		<!--[if lt IE 8]>
	    		<link rel="stylesheet" href="../resources/css/blueprint/ie.css" type="text/css" media="screen, projection">
	  		<![endif]-->
		
			<link rel="stylesheet" type="text/css" href="../resources/css/mapa.css" />
			<link rel="stylesheet" type="text/css" href="../resources/css/searchmaps.css" />
			
			<link rel="stylesheet" type="text/css" href="../resources/css/jquery.autocomplete.css" />
			
			<link href="../resources/css/jquery-ui/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css"/>
			
<?
			include("include-scripts.php");
?>			
		
			
	<!-- **************************************************************************************************** -->	
			<script>

			function showLoading() {
				$("#loading").show();
			}
	
			function hideLoading() {
			  $("#loading").hide();
			}

			
			function reportMapProblem(){
				showLoading();

				$.ajax({
					  type: "GET",
					  url: "brokenmap.php",
					  data: "map=<?=$requiredMap?>&type=<?=$type?>",
					  success: function( data ) {
							    hideLoading();
								var messageArray = eval('(' + data + ')');
								var messageString = messageArray['message'];
	
								$("#messagesDialog").append("<p>"+messageString+"</p>");
								
								$( "#messagesDialog" ).dialog({
									height: 180,
									width:600,
									modal: true,
									zIndex:99999,
									buttons: {
										"Ok": function() { 
											$(this).dialog("close"); 
										}
									}
								});	     
							}//function data
			     });//ajax
			}//reportMapProblem

			

			$(document).ready(function() {
					<?
						include("include-scripts-facebook.php");
						include("include-scripts-uservoice.php"); 
						include("include-scripts-map-metadata-dialog.php");
					?>
					doAddFrame();
			});
			</script>
		
<!-- **************************************************************************************************** -->	
	
	
	
	
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		
<? 	
		
			
			if($type == "KML"){
				$keywords = $mapUtil->getKeywords($dbh, $row['pk_id'], "KML" );
				
				if($productor == "Wikiloc"){
							
							if($serviceTitle == "KML document generated at Wikiloc - http://www.wikiloc.com"){
								$serviceTitle = "Wikiloc: ";
								
								$numKeywords = sizeof($keywords);
								for($j = 0; $j < $numKeywords - 1; $j++ ){
									if($mapUtil->startsWith($keywords[$j]["text"],"http"))
										continue;
									$serviceTitle .= $keywords[$j]["text"] . ", ";
								}//for
								$serviceTitle .= $keywords[$numKeywords - 1]["text"];
							}//if title
				}//if wikiloc
			}else{
//				$keywords2 = $row['keywords_list'];
				$keywords = $mapUtil->getKeywords($dbh, $row['pk_id'], "WMS" );
			}	
			
			$numKeywords = sizeof($keywords);
			$keywordStr = '';
			for($j = 0; $j < $numKeywords - 1; $j++ ){
				$keywordStr .= $keywords[$j]["text"] . ", ";
			}//forsubject
			$keywordStr .= $keywords[$numKeywords - 1]["text"];
			echo "<meta name=\"keywords\" content=\"".$keywordStr."mapas, maps, wms, cartografia, google maps, gogle\">";
			echo "<meta name=\"Description\" content=\"".strip_tags($serviceAbstract)."\" />"; 
			echo "<meta name=\"Author\" content=\"Alvaro Zabala Ordóñez - azabala@gmail.com\" />"; 
			echo "<meta name=\"Subject\" content=\"Mapas del mundo basados en servicios estándar: KML, WMS, WMS-T, etc.\" />"; 
			echo "<meta name=\"Robots\" content=\"index, follow\" />"; 
			//echo "<link rel=\"shortcut icon\" href=\"http://localhost/spainholydays/favicon.ico\">";
			echo "<link rel=\"copyright\" href=\"http://www.gnu.org/copyleft/fdl.html\">";

			$serviceTitle = str_replace("'", "`", $serviceTitle);
			echo "<title>".$serviceTitle ." - Looking for maps: cities and maps of the world </title>";
			
			
 			//we include a map engine. actually openlayers
			if($api == "ide-e")
				include("./map_engines/idee-api.php");
			else if($api == "mapea")
				include("./map_engines/mapea.php");
			else if($api == "leaflet")
				include("./map_engines/leaflet.php");
			else 	
				include("./map_engines/openlayers-api.php"); 

		if($type == "KML"){		
?>
		<script>
			function doAddFrame(){
				var div = "map-container";
				var url = "<?= $friendlyUrl?>";
				var serviceTitle = "<?= $serviceTitle?>";
				var height = <?= $height?>;
				var width = <?= $width?>;
				var xmin = <?= $xmin?>;
				var ymin = <?= $ymin?>;
				var xmax = <?= $xmax?>;
				var ymax = <?= $ymax?>;
			

				addFrameKml(div,url, serviceTitle, 
						height, width, 
						xmin, ymin, xmax, ymax); 
			}
		
		</script>		
	</head>
<?	
		$counterSql = "UPDATE KML_SERVICES SET counter=counter+1 WHERE url_origen = '".$url."'";
		$dbh->query($counterSql);
	}else if($type == "WMS"){
?>
		<script>
			function doAddFrame(){
				var div = "map-container";
				var url = "<?= $url?>";
				var serviceTitle = "<?= $serviceTitle?>";
				var height = <?= $height?>;
				var width = <?= $width?>;
				var xmin = <?= $xmin?>;
				var ymin = <?= $ymin?>;
				var xmax = <?= $xmax?>;
				var ymax = <?= $ymax?>;
				var layerNames = <?= json_encode($layerNames)?>;
				var layerTitles = <?= json_encode($layerTitles)?>;
				var crs = "<?= $crs?>";
				var isQueryable = <?= $isQueryable?>;
				var wmsVersion = "<?= $wmsVersion?>";

				addFrame(div,url, serviceTitle, 
						height, width, 
						xmin, ymin, xmax, ymax,
						layerNames, layerTitles, 
						crs, isQueryable, wmsVersion); 
			}
		
		</script>		
		</head>	
<?
			$counterSql = "UPDATE WMS_SERVICES SET counter=counter+1 WHERE service_url = '".$url."'";
			$dbh->query($counterSql);
	}//else type WMS
?>
		<body>	
			<?php include("menu-header.php")?>
			
			<div id="messagesDialog" title="Aviso de Looking4Maps" >
			</div>			
			
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
			
			<div class="container" style="padding:5px 0px"> 
						<div class="span-15"><!-- este div mete la indentacion -->	
								<div class="span-13">
										<em style="font-size: 1.5em; font-style: normal; color: rgb(51, 102, 153); font-weight: normal">
												 	<?=$serviceTitle?>
										</em>
								</div>
								
<?
			if($user != null && $user->getAdmin() && $type == "WMS"){
?>
								<script>
									 function updateWms(){
										
										  var mapUri = "<?=$url?>"; 
									
										
									  
									  $.ajax({
										  type: "GET",
										  url: "api/update-cached-map.php",
										  data: "map="+encodeURIComponent(mapUri),
										  success: function( data ) {
										
													var messageArray = eval('(' + data + ')');
	
													var messageString = messageArray['message'];
													var extendedMessage = messageArray['extendedMessage'];
													
													var docTitle = messageArray['docName'];
													if(docTitle instanceof Array)
														docTitle = docTitle[0];
													
													var docAbstract = messageArray['description'];
													var keywords = messageArray['keywords'];
	
													if(undefined != docTitle){
														alert("<p>Se ha actualizado el mapa <b>'"+docTitle	+"'</b></p>");
													}else if(undefined != messageString){
														alert("<p>"+messageString+"</p");
													}

													window.location.reload();
													
	
													     
												},//function data
												error:function(data, textStatus, errorThrown){
													alert("<p>"+textStatus+"</p");
												}
								     });//ajax
	
								   }	   
								
								</script>	
								<div class="span-2 last">
									<a href="" target="_blank" class="refresh_wms"  title="Refrescar cache wms" onClick="javascript:updateWms()" > </a>
								</div>
<?
			}
?>

							
								
								<div class="span-2 last">
									<a href="mapamaximizado.php?mapa=<?=$requiredMap?>" target="_blank" class="maximizar_mapa"  title="Pantalla completa" > </a>
								</div>
								
								<div id="map-container" class="span-15 last">
								</div>
								
								<!-- imagen que se muestra cuando se notifica un problema con el mapa -->
								<div id="loading" style="display:none; position:relative;  width:100%; height:100%"; z-index:10000">
  											<p><img src="../resources/images/big-ajax-loader.gif" />
  											 Notificando problema con el mapa...
  											 </p>
								</div>
									
									
								<div id="social-bar" class="span-15 last">
									<!-- AddThis Button BEGIN -->
									<div class="addthis_toolbox addthis_default_style " style="margin:7px 0px 7px 0px">
										<a href="javascript:reportMapProblem()" title="Reportar problema" style="padding-left:4px;margin-left:3px">
											Notificar problema con el mapa
										</a>
										<a class="addthis_button_preferred_1"></a>
										<a class="addthis_button_preferred_2"></a>
										<a class="addthis_button_preferred_3"></a>
										<a class="addthis_button_preferred_4"></a>
										<a class="addthis_button_compact"></a>
										<a class="addthis_counter addthis_bubble_style"></a>
									</div>
									<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4e4193c01ebec8a5"></script>
									<!-- AddThis Button END -->
								</div>
									
								<!--  facebook comments -->
								
								
								<div class="span-15 last">
									<em style="font-size: 1.5em; font-style: normal; color: rgb(51, 102, 153); font-weight: normal">Comenta esta mapa en Facebook</em>
									
									<div class="fb-comments" 
										data-href="<?=Util::curPageURL()?>" 
										data-num-posts="5" 
										data-width="900">
									</div>
									
								</div>
									
								
						</div><!-- span-15 -->
														
							<div class="span-8 last">
								<h4><b>Fuente</b>: <a href="mapsfoundbyproducer.php?keywords=<?=$productor?>"><?=$productor?></a></h4>
								<hr class="space"/>
								<h4><b>Mapa</b>:  <?=$serviceTitle?></h4>
								<hr class="space"/>
								<h4><b>URL</b>: <a href='<?=$url?>'><?=$url?></a></h4>
								<hr class="space"/>
								
<?
								$strlen = strlen($serviceAbstract);
								if($strlen > 350){
					
									$serviceAbstractShortened = strip_tags($serviceAbstract);
								
									
									echo "<p>";
									echo substr($serviceAbstractShortened, 0, 350)."..."."";
									
									echo "<div id='dialog' title='".$serviceTitle."'style='display:none'>";
									echo "".$serviceAbstract;			
									echo "</div>";				
									
									echo "<a href='#' id='dialog_link'>Ver resto</a>";
									echo "</p>";
									
									
								}else{ 
									echo $serviceAbstract;	
								}
?>
								<hr class="space"/>
								<h4><b>Etiquetas</b></h4>
<?								
								$maxKeywords = 4;
								if($numKeywords > 0){
									for($j = 0; $j < $maxKeywords ; $j++ ){
										$text = $keywords[$j]["text"];
										$link = $keywords[$j]["friendly_url_text"];
//										$link = $keywords[$j]["text"];
										$computed = $keywords[$j]["computed"];
										
										if(! isset($text) && ! isset($link))
											continue;
?>										
										<a class='map-label' href='mapsfoundbykeyword.php?keywords=<?=$link?>'><?=$text?></a>
<?																		
									}//for
									if($numKeywords > 3){
?>
											
												... <a href='#' id='dialog_link_keywords'>Ver resto</a>
												<p>
												<div id='dialog_keywords' title='keywords'style='display:none'>
<?
												for($j = 0; $j < $numKeywords; $j++){
													$text = $keywords[$j]["text"];
													$link = $keywords[$j]["friendly_url_text"];
													$computed = $keywords[$j]["computed"];
?>										
													<a class='map-label' target="_blank" href='mapsfoundbykeyword.php?keywords=<?=$link?>'><?=$text?></a>

<?
												}//for
?>

										    	</div>			
												
											</p>
<?			
									}//if	
									
									//funciones de administrador
									
											
								}else{
									echo "No hay etiquetas disponibles";
								}	
								//include(nearmaps-widget.php);mapas próximos a este
?>
								<hr class="space"></hr>
								
								<input type="button" 
									class="large green button" 
									value="Ver en Google Earth" 
<?
if($user == null){
?>
									onClick="javascript:window.location.href='interstitial.php?mapa=<?=$requiredMap?>'" />
<?
}else{
?>							
									onClick="javascript:window.location.href='get-kml.php?mapa=<?=$requiredMap?>'" />

<?
}
?>								
									
								<input type="button" 
									class="large blue button" 
									value="+ Mapas de la zona" 
									onClick="goMapsAroundMe()" />
						</div><!-- span-8 -->
		</div><!-- container -->
				
		<?
		/*
		 Consulta para meter palabras clave de este servicio: 
		 select text, computed from WMS_SERVICES, Wms_Keywords, Keywords_Services where Keywords_Services.fk_keyword_id = Wms_Keywords.pk_id and Keywords_Services.fk_wms_id = WMS_SERVICES.pk_id and WMS_SERVICES.pk_id = 102   Order by WMS_SERVICES.pk_id ASC
		 * */
		
//			include("keywords-widget.php");
			include("tailer-widget.php");
			
			
		
		
		}else{
			
			echo "<div class='highlight large'>No se ha podido encontrar el mapa ".$requiredMap."</div>";
			include("adsense.php");
		}
		
}catch(PDOException $e){
	echo $e->getMessage();
}
$dbh = null;
?>
	</body>
</html>