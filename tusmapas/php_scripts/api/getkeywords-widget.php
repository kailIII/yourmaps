<?php
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/Config.class.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/Util.class.php";

//database params
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



?>

	<div class='antepie'>
			<div class="container">
			<h4><b>Etiquetas:</b><a href="all-tags.php" class="tag2"><b><i>...Ver más</i></b></a></h4>
<?
		//primera pasada, contamos cuantas ocurrencias tienen las 20 primeras palabras
		$statement = $dbh->query("select count(Tag) As counter from (Select  Text as Tag, Keywords_Services.fk_keyword_id, fk_wms_id as id from Wms_Keywords, Keywords_Services where Wms_Keywords.pk_id = Keywords_Services.fk_keyword_id) As counter_table group by Tag order by counter desc LIMIT 10");
//		$statement->execute(); 
		$sumCounter = 0;
		while($row = $statement->fetch()){
			$counter = $row['counter'];
			$sumCounter += $counter;
		}
		
		//segunda pasada, obtenemos las palabras
		
		$statement = $dbh->query("select count(Tag) As counter, Tag from (Select  Text as Tag, Keywords_Services.fk_keyword_id, fk_wms_id as id from Wms_Keywords, Keywords_Services where Wms_Keywords.pk_id = Keywords_Services.fk_keyword_id) As counter_table group by Tag order by counter desc LIMIT 10");
//		$statement->execute(); 
		while($row = $statement->fetch()){
			$tag = $row['Tag'];
			$counter = $row['counter'];
			echo "<a class=\"".Util::getTagClass($counter, $sumCounter)."\"href=\"mapsfoundbykeyword.php?keywords=".$tag."\">".$tag."</a>&nbsp;&nbsp;";
		}
?>
			</div>
		</div>
<?		
}catch(PDOException $e){
	echo $e->getMessage();
}
$dbh = null;
?>
		