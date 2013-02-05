<?php

//include_once $_SERVER["DOCUMENT_ROOT"]."/poiserver/php_scripts/json/JSON.php";
include_once $_SERVER["DOCUMENT_ROOT"]."php_scripts/json/JSON.php";

$json = new Services_JSON();
print $json->encode(array("questions"=>$questions));

?>
