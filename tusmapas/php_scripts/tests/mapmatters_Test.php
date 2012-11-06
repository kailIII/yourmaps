<?php
include "../simple_html_dom/simple_html_dom.php";
 

$htmlDom = file_get_html("http://www.mapmatters.org/server/4047");



//revisar simplehtmldom.sourceforge.net/manual.htm
$elements = $htmlDom->find("dd a");

$url = $elements[0]->href;


echo $htmlDom;