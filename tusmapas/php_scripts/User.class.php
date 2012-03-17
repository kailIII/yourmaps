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
	
	
	public function __construct($username, $email, $password, $active, $oauth_token, $oauth_provider){

		$this->username = $username;
		$this->email = $email;

		$this->oauth_token = $oauth_token;
		$this->oauth_provider = $oauth_provider;

		$this->password = $password;
		$this->active = $active;
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


	public function save($pdo){
		$sql = "INSERT INTO Users (email, oauth_uid, oauth_provider, username, password, active)  ";
		$sql .= "VALUES ('".$this->email."', '".$this->oauth_token."', '".$this->oauth_provider."', '".$this->username."', '".$this->password."',".$this->active." )";
		$stmt = $pdo->query($sql);	
		$success = $stmt->execute();
		
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
		$query = "select id, username, email from Users where  email = :mail";
		$stmt = $pdo->prepare($query);
		$stmt->bindParam(':mail', $this->email);
			
		if($stmt->execute()){
			if($row = $stmt->fetch()){
				$this->id = $row["id"];
				$this->username = $row['username'];
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