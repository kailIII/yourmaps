<?php

include_once 'open-calais/opencalais.php';
include_once 'Geonames/Services/GeoNames.php';


class Util {

	private static $openCalaisApiKey = "q5nfs3a72xqnsqv9g866r5za";
	private static $openCalais;
	
	public static function getOpenCalais(){
//		 if (!isset(self::$openCalais)) {
//	            self::$openCalais  = new OpenCalais(self::$openCalaisApiKey);
//	     }
	     self::$openCalais  = new OpenCalais(self::$openCalaisApiKey);
	     return self::$openCalais;
     
	}
	
	private static $geonamesUser = "alvaro.zabala";
	private static $geonames;
	
	public static function getGeoNames(){
		 if (!isset(self::$geonames)) {
	            self::$geonames  = new Services_GeoNames(self::$geonamesUser);
	     }
	     
	     return self::$geonames;
     
	}
	
	
	public static function text2url($string) {
			
		$spacer = "-";
		$string = trim($string);
		$string = strtolower($string);
		$string = trim(ereg_replace("[^ A-Za-z0-9_]", " ", $string));
		$string = ereg_replace("[ \t\n\r]+", "-", $string);
		$string = str_replace(" ", $spacer, $string);
		$string = ereg_replace("[ -]+", "-", $string);
		return $string;

	}


	public static function getTagClass($count, $numKeywords){
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
		return $result <= 50 ? "tag6" : "tag7";
	}


	public static function curPageURL() {
		$pageURL = 'http';
		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
	
	
	public static function get_url_mime_type($url){

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); //for images
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_COOKIESESSION, true);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$ret = curl_exec($ch);
		if(! $ret){
			$error =  curl_error($ch);
			echo $error;
			//throw an exception
		}
		$info = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);	
		curl_close($ch);
		return $info;	
	}
	
	public static function get_url_file($url){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		curl_exec($ch);
		return curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
	}
}