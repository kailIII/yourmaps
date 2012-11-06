<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/MapKeyword.class.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/Util.class.php";

//FIXME Add UPDATE KML_SERVICES SET friendly_url = CONCAT( ORIGEN,'-',PK_GID )
class KmlMap {
	
	protected $gid;
	
	public function setGid($id){
		$this->gid = $id;
	}
	
	
	
	protected $origen;
	protected $urlOrigen;
	protected $kmlContent;
	
	public function getKmlContent(){
		return $this->kmlContent;
	}
	
	
	protected $documentName;
	
	public function getDocumentName(){
		return $this->documentName;
	}
	
	protected $description;
	
	public function getDescription(){
		return $this->description;
	}
	protected $xmin;
	
	public function getXmin(){
		return $this->xmin;
	}
	

	protected $ymin;
	
	public function getYmin(){
		return $this->ymin;
	}
	
	protected $xmax;
	
	public function getXmax(){
		return $this->xmax;
	}
	
	protected $ymax;
	
	public function getYmax(){
		return $this->ymax;
	}
	
	
	
	
	protected $user;
	
	public function setUser($auser){
		$this->user = $auser;
	}
	
	public function getUser(){
		return $this->user;
	}
	
	/**
	 * 
	 * Array of MapKeyword instances
	 * @var MapKeyword
	 */
	protected $keywords = null;
	
	
	public function setKeywords($aKeywords){
		$this->keywords = $aKeywords;
	}
	
	public function getKeywords(){
		return $this->keywords;
	}
	
	
	public function getKeywordsAsText(){
		$solution = "";
		
		$numKeywords = sizeof($this->keywords);
		
		if($numKeywords > 0){
			for($i = 0; $i < $numKeywords -1; $i++){
				$solution .= ($this->keywords[$i]->getKeyText() . ",");
			}
			$solution .= $this->keywords[$numKeywords - 1]->getKeyText();	
		}
		return $solution;
	}
	
	
	public function getGid(){
		return $this->gid;
	}
	
	public function __construct($origen, $urlOrigen, $kmlContent, 
				$documentName, $description, $xmin, $ymin, $xmax, $ymax){
		
		$this->origen = $origen;
		$this->urlOrigen = $urlOrigen;
		$this->kmlContent = $kmlContent;
		$this->documentName = $documentName;
		$this->description = $description;
		
		$this->xmin = $xmin;
		$this->ymin = $ymin;
		$this->xmax = $xmax;
		$this->ymax = $ymax;
								 
		$this->gid = -1;						 
	}
	
	/*
	 * a veces bbox almacena un valor null, cuando xmin, ymin, xmax, ymax
	 * tienen valores.
	 * 
	 * Hay que ver por qué, mientras tanto esta consulta lo arregla
	 
	 UPDATE KML_SERVICES SET BBOX = GeomFromText('POLYGON((xmin ymin, xmax, ymin, xmax, ymax, xmin, ymax, xmin, ymin)') where BBOX is null
	 */
	public function save($pdo){
		if(! $this->exist($pdo)){
			
			$count = 0;
			
			$sql = "SELECT COUNT(*) AS NUM FROM KML_SERVICES";
			$statement = $pdo->query($sql);
			if($statement->execute()){
				if($row = $statement->fetch()){
					$count = $row["NUM"];
				}
			}
			
			//$friendlyUrl = $this->origen."-".$count;
			
			$friendlyUrl = Util::text2url($this->documentName);
				
			$bb = "GeomFromText('POLYGON((". $this->xmin.
								" ".$this->ymin.",".$this->xmax." ".$this->ymin.",".$this->xmax.
								" ".$this->ymax.",".
								$this->xmin." ".$this->ymax.",".$this->xmin." ".$this->ymin."))')";
			
			$sql = "INSERT INTO KML_SERVICES (origen, url_origen, kml_content, document_name, description, xmin, ymin, xmax, ymax, friendly_url, BBOX, username_fk)".
							" VALUES(:origen, :urlOrigen, :kmlContent, :documentName, :description ".
			",:xmin, :ymin, :xmax, :ymax, :friendlyUrl,".$bb.",:username_fk)";
			
			
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':origen', $this->origen);
			$stmt->bindParam(':urlOrigen', $this->urlOrigen);
			$stmt->bindParam(':kmlContent', $this->kmlContent);
			$stmt->bindParam(':documentName', $this->documentName);
			$stmt->bindParam(':description', $this->description);
			$stmt->bindParam(':xmin', $this->xmin);
			$stmt->bindParam(':xmax', $this->xmax);
			$stmt->bindParam(':ymin', $this->ymin);
			$stmt->bindParam(':ymax', $this->ymax);
			$stmt->bindParam(':friendlyUrl', $friendlyUrl);
			
			$stmt->bindParam(':username_fk', $this->user);
			
			$success = $stmt->execute();
			
			
			
			$sql = "SELECT PK_GID FROM KML_SERVICES WHERE URL_ORIGEN = '".$this->urlOrigen."'";
			$statement = $pdo->query($sql);
			if($statement->execute()){
				if($row = $statement->fetch()){
					$this->gid = $row["PK_GID"];
				}
			}
		}
	}
	
	public function exist($pdo){
		$query = "select * from KML_SERVICES where url_origen = '".$this->urlOrigen."'";
		$statement = $pdo->query($query);
		if($statement->execute()){
			$numResults = $statement->rowCount();
			if ($numResults > 0){
				$sql = "SELECT PK_GID FROM KML_SERVICES WHERE URL_ORIGEN = '".$this->urlOrigen."'";
				$statement = $pdo->query($sql);
				if($statement->execute()){
					if($row = $statement->fetch()){
						$this->gid = $row["PK_GID"];
					}
				return true;
				}
			}
		}
		return false;
		
	}
	
}