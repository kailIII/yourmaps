<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('getLang'))
{
    function getLang(){
    	//after that, we try to detect browser language
    	
        if (!empty( $_SERVER['HTTP_ACCEPT_LANGUAGE'] )){
        	// explode languages into array
        	$accept_langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

       

        	// Check them all, until we find a match
        	foreach ($accept_langs as $lang)
        	{
            	// Turn en-gb into en or es-ES into es
            	$lang = substr($lang, 0, 2);

           		// Check its in the array. If so, break the loop, we have one!
            	if(in_array($lang, array_keys($config['supported_languages']))){
            	    break;
            	}
        	}
    	}

	    // If no language has been worked out - or it is not supported - use the default
	    if(empty($lang) or !in_array($lang, array_keys($config['supported_languages'])))
	    {
	        $lang = $config['default_language'];
	    }
	
	 
	    
	    return $lang;  
    }   
}



