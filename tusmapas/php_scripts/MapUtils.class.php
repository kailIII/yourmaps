<?php

class MapUtils {
	private static $instance;
	
 	private function __construct()
    {
    }
 
    public static function singleton()
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }
 
    public function __clone()
    {
        trigger_error('Clone no se permite.', E_USER_ERROR);
    }
    
    
	public function getLatLonBBox($coordinates){
		$xmin = 1000;
		$ymin = 1000;
		$xmax = -1000; 
		$ymax = -1000;
		
		$numCoordinates = sizeof($coordinates); 
		for($i = 0; $i < $numCoordinates ;$i++){
			$coordStr = $coordinates[$i];
			$coordsArray = split(",",$coordStr);
	
			$x = $coordsArray[0];
			$y = $coordsArray[1];
			
			if($x > $xmax)
				$xmax = $x;
			if($x < $xmin)
				$xmin = $x;
			if($y > $ymax)
				$ymax = $y;
			if($y < $ymin)
				$ymin = $y;
		}
		return array($xmin, $ymin,$xmax,$ymax);
	}
    
    public function getKeywords($pdo, $mapId, $mapType){
    	$solution = array();
    	$query = "select Wms_Keywords.text, Wms_Keywords.friendly_url_text, Wms_Keywords.computed from Wms_Keywords, Keywords_Services where Keywords_Services.fk_keyword_id = Wms_Keywords.pk_id and Keywords_Services.fk_wms_id = ".$mapId." and Keywords_Services.service_type = '".$mapType."'";  
		$statement = $pdo->query($query);
		if( $statement){
			
			if($statement->execute()){
					$i = 0;
					while ($row = $statement->fetch()){
						//process $row with text, friendly_url_text and
						array_push($solution, $row);
						$i++;
// bullsheet. MapUtil must returns all keywords. View widget has the responsability of truncate
//						if($i > 4)
//							break;
					}
			}
		}
		return $solution;
    }
    
    /*
     * string functions
     * */
    
    public function startsWith($haystack, $needle){
	    $length = strlen($needle);
	    return (substr($haystack, 0, $length) === $needle);
	}

	public function endsWith($haystack, $needle){
	    $length = strlen($needle);
	    $start  = $length * -1; //negative
	    return (substr($haystack, $start) === $needle);
	}
	
	
	public function utf8_uri_encode( $utf8_string, $length = 0 ) {
	    $unicode = '';
	    $values = array();
	    $num_octets = 1;
	    $unicode_length = 0;
	
	    $string_length = strlen( $utf8_string );
	    for ($i = 0; $i < $string_length; $i++ ) {
	
	        $value = ord( $utf8_string[ $i ] );
	
	        if ( $value < 128 ) {
	            if ( $length && ( $unicode_length >= $length ) )
	                break;
	            $unicode .= chr($value);
	            $unicode_length++;
	        } else {
	            if ( count( $values ) == 0 ) $num_octets = ( $value < 224 ) ? 2 : 3;
	
	            $values[] = $value;
	
	            if ( $length && ( $unicode_length + ($num_octets * 3) ) > $length )
	                break;
	            if ( count( $values ) == $num_octets ) {
	                if ($num_octets == 3) {
	                    $unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]) . '%' . dechex($values[2]);
	                    $unicode_length += 9;
	                } else {
	                    $unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]);
	                    $unicode_length += 6;
	                }
	
	                $values = array();
	                $num_octets = 1;
	            }
	        }
	    }
	
	    return $unicode;
	}


	function seems_utf8($str) {
	    $length = strlen($str);
	    for ($i=0; $i < $length; $i++) {
	        $c = ord($str[$i]);
	        if ($c < 0x80) $n = 0; # 0bbbbbbb
	        elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
	        elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
	        elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
	        elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
	        elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
	        else return false; # Does not match any model
	        for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
	            if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
	                return false;
	        }
	    }
	    return true;
	}


	
	//FIXME No funciona bien con los acentos y las tildes, que los sustituye por URL Encode
	//en vez de retirar el acento
	public function toPrettyUrl($title) {
	    $title = strip_tags($title);
	    // Preserve escaped octets.
	    $title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
	    // Remove percent signs that are not part of an octet.
	    $title = str_replace('%', '', $title);
	    // Restore octets.
	    $title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);
	
	    if ($this->seems_utf8($title)) {
	        if (function_exists('mb_strtolower')) {
	            $title = mb_strtolower($title, 'UTF-8');
	        }
	        $title = $this->utf8_uri_encode($title, 200);
	    }
	
	    $title = strtolower($title);
	    $title = preg_replace('/&.+?;/', '', $title); // kill entities
	    $title = str_replace('.', '-', $title);
	    $title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
	    $title = preg_replace('/\s+/', '-', $title);
	    $title = preg_replace('|-+|', '-', $title);
	    $title = trim($title, '-');
	
	    return $title;
	}
	
}

