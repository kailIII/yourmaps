<?php
//FIXME Add UPDATE KML_SERVICES SET friendly_url = CONCAT( ORIGEN,'-',PK_GID )
class KmlMap {
	
	protected $gid;
	protected $origen;
	protected $urlOrigen;
	protected $kmlContent;
	protected $documentName;
	protected $description;
	protected $xmin;
	protected $ymin;
	protected $xmax;
	protected $ymax;
	
	
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
	
	
	public function save($pdo){
		if(! $this->exist($pdo)){
			
			$sql = "INSERT INTO KML_SERVICES (origen, url_origen, kml_content, document_name, description, xmin, ymin, xmax, ymax)".
							" VALUES(:origen, :urlOrigen, :kmlContent, :documentName, :description ".
			",:xmin, :ymin, :xmax, :ymax)";
			
			
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