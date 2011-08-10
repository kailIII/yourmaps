<?php
class Town {
	
	var $nombre;
	
	var $provincia;
	
	var $codigoIneMunicipio;
	
	var $gid;
	
	var $wikipedia_es;
	
	var $wikitravel_es;
	
	var $yahoo_woeid;
	
	var $xmin, $ymin, $xmax, $ymax;
	
	
	public function __construct($row) {
		if(is_array($row)){
			//Ojo!!! los atributos no llevan dolar en php
			$this->nombre =   $row['j_NOMBRE'];
			$this->provincia = $row['J_nombrepro'];	
			$this->codigoIneMunicipio = $row['codINEMuni'];
			$this->gid = $row['gid'];
			$this->wikipedia_es = $row['WIKIPEDIA_ES'];
			$this->wikitravel_es = $row['WIKITRAVEL_ES'];
			$this->yahoo_woeid = $row['WOEID'];
			
			$this->xmin = $row['xmin'];
			$this->ymin = $row['ymin'];
			
			$this->xmax = $row['xmax'];
			$this->ymax = $row['ymax'];
			
		}
		
	}
}
