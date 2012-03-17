<?php 
	require_once 'Util.class.php';
?>	
	
	
	<div class='antepie'>
			<div class="container">
			<h4><b>Productores:</b></h4>
<?
		//primera pasada, contamos cuantas ocurrencias tienen las 20 primeras palabras
		$statement = $dbh->query("select count(Tag) As counter from (select WMS_SERVICES.pk_id, friendly_url, contact_organisation As Tag, service_url, service_title, service_abstract, xmin, ymin, xmax, ymax from WMS_SERVICES UNION all select pk_gid, friendly_url, origen, url_origen, document_name, description, xmin, ymin, xmax, ymax from KML_SERVICES ) As counter_table group by Tag order by counter desc LIMIT 10");
		$statement->execute(); 
		$sumCounter = 0;
		while($row = $statement->fetch()){
			$counter = $row['counter'];
			$sumCounter += $counter;
		}
		
		//segunda pasada, obtenemos las palabras
		
		$statement = $dbh->query("select count(Tag) As counter, Tag from (select WMS_SERVICES.pk_id, friendly_url, contact_organisation As Tag, service_url, service_title, service_abstract, xmin, ymin, xmax, ymax from WMS_SERVICES UNION all select pk_gid, friendly_url, origen, url_origen, document_name, description, xmin, ymin, xmax, ymax from KML_SERVICES ) As counter_table group by Tag order by counter desc LIMIT 10");
		$statement->execute(); 
		while($row = $statement->fetch()){
			$tag = $row['Tag'];
			$counter = $row['counter'];
			echo "<a class=\"".Util::getTagClass($counter, $sumCounter)."\"href=\"mapsfoundbyproducer.php?keywords=".$tag."\">".$tag."</a>&nbsp;&nbsp;";
		}
?>
			</div>
		</div>	