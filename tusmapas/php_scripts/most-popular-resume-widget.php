<?php 
	require_once 'Util.class.php';
	require_once "MapUtils.class.php";
	include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts/simple_html_dom/simple_html_dom.php";
?>

		<div class='antepie'>
			<div class="container">
				<h4><b>Mapas más populares:</b><a href="most-populars.php" class="tag2"><b><i>...Ver más</i></b></a>
		 			</div></h4>
				<br/>
<?
		//primera pasada, contamos cuantas ocurrencias tienen las 20 primeras palabras
		$query = "SELECT * FROM (SELECT pk_id, friendly_url, contact_organisation, service_url, service_title, service_abstract, xmin, ymin, xmax, ymax, COUNTER ".
		"FROM WMS_SERVICES ORDER BY COUNTER DESC LIMIT 5) AS WMS UNION ALL ".
		"SELECT * FROM (SELECT pk_gid, friendly_url, origen, url_origen, document_name, ".
		"description, xmin, ymin, xmax, ymax, COUNTER FROM KML_SERVICES ORDER BY ".
		"COUNTER DESC  LIMIT 5)AS KML ORDER BY COUNTER DESC LIMIT 5";
		$statement = $dbh->query($query);
?>
		<div class="span-20">		
	<?		
				while ($r = $statement->fetch()){
					$title = $r['service_title'];
					$serviceUrl = $r['service_url'];
					
					$counter = $r['COUNTER'];
					
					
					$friendlyUrl = $r['friendly_url'];	
	?>			
					<a href="mapa.php?mapa=<?= $friendlyUrl?>"
					 title="<?= $title ?>" 
					 class="tag3"
					 ><?= $title?></a> - <?= $counter?> visitas	</br>					
	     <?
		        }//while
		          
		 ?> 
		      </div><!-- span-20 -->
			</div>
		</div>
		<hr class="space"/>

