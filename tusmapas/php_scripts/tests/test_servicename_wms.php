<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts/wms-simple/"."WMS.class.php";


$url = "http://urgell.terrassa.cat/arcgis/services/ajt_1ar1es/mapserver/wmsserver?";

$wms = new WMS($url,"curl");