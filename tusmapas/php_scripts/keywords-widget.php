<?php 
	function getTagClass($count, $numKeywords){
			$result = ($count  / $numKeywords) * 100;
            if ($result <= 2)
                return "tag1";
            if ($result <= 4)
                return "tag2";
            if ($result <= 5)
                return "tag3";
            if ($result <= 7)
                return "tag4";
            if ($result <= 9)
                return "tag5";
            if ($result <= 10)
                return "tag5";
            return $result <= 50 ? "tag6" : "";
		}

?>
		<div class='antepie'>
		Busca tus mapas por palabras clave:
<?
		//primera pasada, contamos cuantas ocurrencias tienen las 20 primeras palabras
		$statement = $dbh->query("select count(Tag) As counter from (Select  Text as Tag, Keywords_Services.fk_keyword_id, fk_wms_id as id from Wms_Keywords, Keywords_Services where Wms_Keywords.pk_id = Keywords_Services.fk_keyword_id) As counter_table group by Tag order by counter desc LIMIT 30");
		$statement->execute(); 
		$sumCounter = 0;
		while($row = $statement->fetch()){
			$counter = $row['counter'];
			$sumCounter += $counter;
		}
		
		//segunda pasada, obtenemos las palabras
		
		$statement = $dbh->query("select count(Tag) As counter, Tag from (Select  Text as Tag, Keywords_Services.fk_keyword_id, fk_wms_id as id from Wms_Keywords, Keywords_Services where Wms_Keywords.pk_id = Keywords_Services.fk_keyword_id) As counter_table group by Tag order by counter desc LIMIT 30");
		$statement->execute(); 
		while($row = $statement->fetch()){
			$tag = $row['Tag'];
			$counter = $row['counter'];
			echo "<a class=\"".getTagClass($counter, $sumCounter)."\"href=\"http://localhost/tusmapas/php_scripts/mapsfoundbykeyword.php?keywords=".$tag."\">".$tag."</a>&nbsp;&nbsp;";
		}
?>
		</div>
		