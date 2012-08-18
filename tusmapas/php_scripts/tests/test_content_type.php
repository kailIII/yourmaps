<?php
include_once '../Util.class.php';
include_once '../MapUtils.class.php';

$map = "http://mapas.igme.es/gis/services/Cartografia_Tematica/IGME_Neotectonico_1M/MapServer/WMSServer?";
$contentType = Util::get_url_mime_type($map);

if($contentType == "application/vnd.google-earth.kml+xml"){
	
}else if($contentType == "application/vnd.google-earth.kmz"){



}else if($contentType == "application/vnd.ogc.wms_xml"){
	
}else if($contentType == "text/xml"){
	if(! MapUtils::singleton()->endsWith($map,"?"))
		$url = $map . "?";
	$url .=  "service=WMS&request=GetCapabilities";
		
	$contentType = Util::get_url_mime_type($url);
	
	
	/*
	 El content-type de un getCapabilities siempre es text/xml.
	 
	 Por tanto, más cuenta trae verificar si el documento XML tiene el texto WMT_MS_CAPABILITIES
	 
				 stripos($text, "WMT_MS_CAPABILITIES") !== false
				 
				 
	Algoritmo de chequeo de mime type:
	
		a) Pedir el content-type. Si es uno de los 3 conocidos, está claro
		
		b) Si es text/xml, pedir el contenido, siempre que la extension no sea KMZ ni KML
		  Si el contenido tiene WMT_MS_CAPABILITIES, es un WMS.
		  Si tiene la etiqueta html, es un mensaje de error
	 
	 * */
	
	
	
	
	
		
		
}else if($contentType == "application/vnd.google-earth.kml+xml"){
	
}else if($contentType == "application/vnd.google-earth.kmz"){



}else if($contentType == "application/vnd.ogc.wms_xml"){
	
}
echo $contentType;