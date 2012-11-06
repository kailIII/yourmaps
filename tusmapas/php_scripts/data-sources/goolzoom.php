<?php

require_once '../json/JSON.php';

$countries = array('es','co','mx','pt','ad','de','be','ca',
			'us','au','br','ar','ec','uy','no','it','fr','cz','nl','co.uk','ch',
			'at','dk','pl','eu','lv','se','pe','ro','ve','cl','hr',
			'fi','ie');

$json = new Services_JSON_Ext();
for($i = 0; $i < sizeof($countries);$i++){
	$url = "http://".$countries[$i].".goolzoom.com/recursos/mapas/ObtenerMapas.aspx?country=".$countries[$i];	
	$json_string =  file_get_contents($url);

	if (get_magic_quotes_gpc()) {
		$json_string = stripslashes($json_string);
	}

	

	

//	$json_string = str_ireplace("[", "", $json_string);
//	$json_string = str_ireplace("]", "", $json_string);
	
	
	$decoded_json = $json->decode($json_string,true);
	
	$numMaps = sizeof($decoded_json);
	for($j = 0; $j < $numMaps; $j++){
		$map = $decoded_json[$j];
		$wmsUrl = $map->u;
		echo $wmsUrl;
		die();
	}
	
}

