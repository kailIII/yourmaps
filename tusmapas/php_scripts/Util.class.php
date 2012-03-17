<?php
class Util {


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
		return $result <= 50 ? "tag6" : "";
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

}