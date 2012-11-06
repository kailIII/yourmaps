<?php

include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts/kml"."/KmlReader.class.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts/kml"."/KmzReader.class.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts/wms-simple"."/WmsReader.class.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/UnknownMapException.class.php";



class ReaderFactory {
	
	
	
	public static function createMapReaderFor($mapType){
		if($mapType == "WMS"){
			return new WmsReader();
		}else if($mapType == "KML"){
			return new KmlReader();
		}
	}

	
	/**
	 *
	 * Loads a map in looking4maps database fron an online resource
	 * 
	 * @param string $url online resource
	 * 
	 */
	public static function createMapReader($url){
		$contentType = Util::get_url_mime_type($url);
//application/vnd.ogc.se_xml;charset=UTF-8
		if($contentType == "application/vnd.google-earth.kml+xml" || $contentType == "application/vnd.google-earth.kml+xml; charset=UTF-8"){
			return new KmlReader();	
					
		}else if($contentType == "application/vnd.google-earth.kmz"){
			return new KmzReader();

		}else if($contentType == "application/vnd.ogc.wms_xml"){
			return new WmsReader();
			
		}else if($contentType == "text/xml"){
			return new WmsReader();
		}
		/*
			//preprocess to ensure a GetCapabilities request
			if( strpos($url, "?") === false){
				$url = $url . "?";
			}
			
			if( MapUtils::singleton()->endsWith($map,"?"))
					$url .=  "service=WMS&request=GetCapabilities";
			
			
			$contentType = Util::get_url_mime_type($url);
		*/

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
		else{
			//before throwing the exception, try to request a getcapabilities
			return new WmsReader();
//			throw new UnknownMapException("Tipo de mapa desconocido ".$url);
		}
	}
}
