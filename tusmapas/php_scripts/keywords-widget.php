<?php 
	require_once 'Util.class.php';
?>
		<div class='antepie'>
			<div class="container">
			<h4><b>Etiquetas:</b></h4>
<?
		//primera pasada, contamos cuantas ocurrencias tienen las 20 primeras palabras
		$statement = $dbh->query("select count(Tag) As counter from (Select  Text as Tag, Keywords_Services.fk_keyword_id, fk_wms_id as id from Wms_Keywords, Keywords_Services where Wms_Keywords.pk_id = Keywords_Services.fk_keyword_id) As counter_table group by Tag order by counter desc LIMIT 10");
		$statement->execute(); 
		$sumCounter = 0;
		while($row = $statement->fetch()){
			$counter = $row['counter'];
			$sumCounter += $counter;
		}
		
		//segunda pasada, obtenemos las palabras
		
		$statement = $dbh->query("select count(Tag) As counter, Tag from (Select  Text as Tag, Keywords_Services.fk_keyword_id, fk_wms_id as id from Wms_Keywords, Keywords_Services where Wms_Keywords.pk_id = Keywords_Services.fk_keyword_id) As counter_table group by Tag order by counter desc LIMIT 10");
		$statement->execute(); 
		while($row = $statement->fetch()){
			$tag = $row['Tag'];
			$counter = $row['counter'];
			echo "<a class=\"".Util::getTagClass($counter, $sumCounter)."\"href=\"mapsfoundbykeyword.php?keywords=".$tag."\">".$tag."</a>&nbsp;&nbsp;";
		}
?>
			</div>
		</div>
		