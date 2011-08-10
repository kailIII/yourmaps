<?php

include("Config.class.php");
$config = Config::singleton();
$username = $config->username;
$hostname = $config->hostname;
$password = $config->password;

/**
 * Para que esta función PHP funcione correctamente se debe haber creado un indice FULL-TEXT
 * en la bbdd MySQL:
 *    CREATE FULLTEXT INDEX NobreIndice ON NombreTabla(campo1, campo2, campo3);

      SET GLOBAL key_buffer_size = 600*1024*1024;
      LOAD INDEX INTO CACHE NombreTabla INDEX(NombreIndice)      ;
 * 
 */

$keyword = $_GET['keywords'];

try {
		$dbh = new PDO("mysql:host=$hostname;dbname=tusmapas", $username, $password);
		//$statement = $dbh->query("select * from WMS_SERVICES where match(service_title,service_abstract, keywords_list, layer_names, layer_titles) against ('".$keyword."') IN NATURAL LANGUAGE MODE");
		//$statement = $dbh->query("select * from WMS_SERVICES, Wms_Keywords, Keywords_Services where Keywords_Services.fk_wms_id = WMS_SERVICES.pk_id and Keywords_Services.fk_keyword_id = Wms_Keywords.pk_id and Wms_Keywords.text like '%".$keyword."'");
		$dbh->query("SET CHARACTER SET utf8");
		$statement = $dbh->query( "select text from  Wms_Keywords where text like '%".$keyword."%'");
		$statement->execute();
		
		//TODO Contar las filas, y si hay mas de 5 no devolver nada para que no se
		//muestre en el cliente DHTML
		
		//TODO Generar un snapshop para cada WMS, para que se pueda mostrar desde el cliente
		
		$rows = array();
		while($r = $statement->fetch()) {
		   array_push($rows, $r);
        }
        $numRows = sizeof($rows);
        if($numRows < 1){
        	print json_encode("{}");
        }else if ($numRows > 15){
        	print json_encode(array_slice($rows, 0, 15));
        }else{	
        	print json_encode($rows);
        }
}catch(PDOException $e){
	echo $e->getMessage();
}

$dbh = null;