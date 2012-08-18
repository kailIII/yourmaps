<?php

class User{
	private $id;

	private $email;
	private $username;

	private $oauth_token;

	private $oauth_provider;


	//for web form users
	private $password;
	private $active;
	private $admin;
	
	
	public function __construct($username, $email, $password, $active, $oauth_token, $oauth_provider){

		$this->username = $username;
		$this->email = $email;

		$this->oauth_token = $oauth_token;
		$this->oauth_provider = $oauth_provider;

		$this->password = $password;
		$this->active = $active;
		
		$this->admin = false;
	}
	
	
	public function getId(){
		return $this->id;
	}
	
	
	public function getUserName(){
		return $this->username;
	}
	
	public function getMail(){
		return $this->email;
	}
	
	
	public function getAdmin(){
		return $this->admin;
	}
	
	
	public function getAddedMapsCount($pdo){
		$sql = "select count(*) as cnt from (select 'WMS' As Type, WMS_SERVICES.pk_id, friendly_url, contact_organisation, service_url, service_title, service_abstract, xmin, ymin, xmax, ymax from WMS_SERVICES where username_fk = '".$this->username.
		"' union all select 'KML' As Type, pk_gid, friendly_url, origen, url_origen, document_name, description, xmin, ymin, xmax, ymax from KML_SERVICES  where username_fk = '".$this->username."') as maps"; 
		$stmt = $pdo->query($sql);	
		
		if($stmt->execute()){
			if($row = $stmt->fetch()){
				return $row["cnt"];
			}
		}//if
		return 0;
	
	}


	public function save($pdo){
		if(!$this->exist($pdo)){
			$sql = "INSERT INTO Users (email, oauth_uid, oauth_provider, username, password, active)  ";
			$sql .= "VALUES ('".$this->email."', '".$this->oauth_token."', '".$this->oauth_provider."', '".$this->username."', '".$this->password."',".$this->active." )";
			$stmt = $pdo->query($sql);	
		}
		
		$sql = "SELECT ID FROM Users WHERE email = :mail";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':mail', $this->email);
		if($stmt->execute()){
			if($row = $stmt->fetch()){
				$this->id = $row["ID"];
			}
		}//if
	}

	public function exist($pdo){
//		$query = "select id, username, email, admin from Users where  email = :mail";
		$query = "select id, username, email, admin from Users where  username = :username";
		$stmt = $pdo->prepare($query);
//		$stmt->bindParam(':mail', $this->email);
		$stmt->bindParam(':username', $this->username);
			
		if($stmt->execute()){
			if($row = $stmt->fetch()){
				$this->id = $row["id"];
				$this->username = $row['username'];
				$this->admin = $row['admin'];
				return true;
			}
		}
		return false;
	}
	
	public function existMail($mail){
		$query = "select email from Users where email = :mail";
		$stmt = $pdo->prepare($query);
		$stmt->bindParam(':mail', $this->email);
			
		if($stmt->execute()){
			if($row = $stmt->fetch()){
				return true;
			}
		}
		return false;
	}





}