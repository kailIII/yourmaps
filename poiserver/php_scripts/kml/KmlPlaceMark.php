<?php
class KmlPlaceMark {
	
	private $name;
	
	public function getName(){
		return $this->name;
	}
	
	private $description;
	public function getDescription(){
		return $this->description;
	}
	
	private $wktText;
	public function getWktText(){
		return $this->wktText;
	}
	
	private $source;
	public function getSource(){
		return $this->source;
	}
	
	private $urlSource;
	public function getUrlSource(){
		return $this->urlSource;
	}
	
	private $photoUrl;

	public function getPhotoUrl(){
		return $this->photoUrl;
	}
	
	
	private $webUrl;
	public function getWebUrl(){
		return $this->webUrl;
	}
	
	public function __construct($name = "", $description = "", 
								$wktText, $source = "", $urlSource = "", 
								$photoUrl = "" , $webUrl = "" ){
									
									
		$this->name = $name;
		$this->description = $description;
		$this->wktText = $wktText;
		$this->source = $source;
		$this->urlSource = $urlSource;
		$this->photoUrl = $photoUrl;
		$this->webUrl = $webUrl;					
									
	
	}
	
	
	
	
	
}