<?php

require_once 'Util.class.php';
class MapKeyword {
	protected $gid;	
	protected $keyText;
	protected $friendlyUrlText;
	protected $computed;
	
	
	public function getGid(){
		return $this->gid;
	}
	
	
	public function __construct($keyText, $computed){
		$this->keyText = $keyText;
		$this->friendlyUrlText = Util::text2url($keyText);
		$this->computed = $computed;
	}
	
	public function save($pdo){
			if(! $this->exist($pdo)){
				
				$sql = "INSERT INTO Wms_Keywords (text, friendly_url_text, computed)".
								" VALUES(:keyText, ";
				$sql .= "'".$this->friendlyUrlText."'," .
						"'".$this->computed."')";
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':keyText', $this->keyText);
				
				$success = $stmt->execute();
				
				$sql = "SELECT PK_ID FROM Wms_Keywords WHERE text = :keyText";
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':keyText', $this->keyText);
				if($stmt->execute()){
					if($row = $stmt->fetch()){
						$this->gid = $row["PK_ID"];
					}
				}//if
			}
		}
		
		public function exist($pdo){
			$query = "select * from Wms_Keywords where text = :keyText";
			$stmt = $pdo->prepare($query);
			$stmt->bindParam(':keyText', $this->keyText);
			
			if($stmt->execute()){
				if($row = $stmt->fetch()){
						$this->gid = $row["pk_id"];
						return true;
				}
			}
			return false;
		}
}
