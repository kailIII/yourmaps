<?php 
	require_once 'Util.class.php';
	require_once "MapUtils.class.php";
?>
		<div class='antepie'>
			<div class="container">
<?
		//primera pasada, contamos cuantas ocurrencias tienen las 20 primeras palabras
		$query = "SELECT * FROM (SELECT pk_id, friendly_url, contact_organisation, service_url, service_title, service_abstract, xmin, ymin, xmax, ymax, COUNTER ".
		"FROM WMS_SERVICES ORDER BY COUNTER DESC LIMIT 10) AS WMS UNION ALL ".
		"SELECT * FROM (SELECT pk_gid, friendly_url, origen, url_origen, document_name, ".
		"description, xmin, ymin, xmax, ymax, COUNTER FROM KML_SERVICES ORDER BY ".
		"COUNTER DESC  LIMIT 10)AS KML ORDER BY COUNTER DESC";
		$statement = $dbh->query($query);
?>
		<div class="span-20">
					<ul id="search-results">
	<?		
				while ($r = $statement->fetch()){
					$title = $r['service_title'];
					$serviceUrl = $r['service_url'];
					$abstract = $r['service_abstract'];
					$friendlyUrl = $r['friendly_url'];	
					
					$mapUtil = MapUtils::singleton();
					
					$strlen = strlen($abstract);
					if($strlen > 180){
						$echoAbstract = substr($abstract, 0, 180)."...";
					}else{ 
						$echoAbstract = $abstract;
					}
					
											
		?>								
						<li class="box">
							<h3>
								<a href="mapa.php?mapa=<?= $friendlyUrl?>" 
								   title="<?= $title ?>" 
								   id="trail-title"><?= $title?>
								 </a>
							</h3>
				
							<p class="description">
								<?=$echoAbstract?>
							</p>										
				   		</li> 				
		<?
		        }//while
		        
		       echo "</ul>";
		 ?>   	
		      </div><!-- span-20 -->
			</div>
		</div>
		