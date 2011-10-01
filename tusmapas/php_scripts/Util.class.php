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
}