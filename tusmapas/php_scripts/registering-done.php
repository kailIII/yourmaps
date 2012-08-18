<?php
require_once 'User.class.php';
require_once 'Config.class.php';
require_once('./recaptcha/recaptchalib.php');


$config = Config::singleton();
$username = $config->username;
$hostname = $config->hostname;
$password = $config->password;
$database = $config->database;


try {
	$dbh = new PDO("mysql:host=$hostname;dbname=$database",
	$username, $password,
	array(PDO::ATTR_PERSISTENT => true));

	$dbh->query("SET CHARACTER SET utf8");

	if(! isset($_REQUEST['user'])){
		die("El campo 'usuario' es obligatorio");
	}
	$userName = $_REQUEST['user'];
	
	if(! isset($_REQUEST['mail'])){
		die("El campo 'mail' es obligatorio");
	}
	$mail = $_REQUEST['mail'];
	
	if(! isset($_REQUEST['password1'])){
		die("El campo 'password1' es obligatorio");
	}
	$password = $_REQUEST['password1'];
	
	if(! isset($_REQUEST['password2'])){
		die("El campo 'password2' es obligatorio");
	}
	$password2 = $_REQUEST['password2'];
	
//COINCIDENT PASSWORDS
	
	if($password1 != $password2){
		die("Las passwords no son coincidentes");
	}

	
	
	//CAPTCHA VALIDATION
	/*
	 https://www.google.com/recaptcha/admin/site?siteid=315216623
	 
	  Se entra con los datos de la cuenta google
	 
	Domain Name: 	www.lookingformaps.com
	Public Key: 	6Lfv0skSAAAAAAxNY5DYtitJSZlYU-OYFDxIv7-S
	Private Key: 	6Lfv0skSAAAAAPQmfxg1pMmCRSBdaHwWEhKfat7X
	 */
	$privatekey = "6Lfv0skSAAAAAPQmfxg1pMmCRSBdaHwWEhKfat7X";
	$resp = recaptcha_check_answer ($privatekey,
						$_SERVER["REMOTE_ADDR"],
						$_POST["recaptcha_challenge_field"],
						$_POST["recaptcha_response_field"]);

	if (!$resp->is_valid) {
		// What happens when the CAPTCHA was entered incorrectly
		die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
         "(reCAPTCHA said: " . $resp->error . ")");
	}
	
	



	$user = new User($username, $email, null, 1, $oauthToken, $oauthProvider);

	session_start();
	$_SESSION["user"] = $user;


	if(! $user->exist($dbh)){
		if($user->existMail($email))
		
		
		
		$user->save($dbh);
	}else{
		echo "el usuario ya existe";
	}



}catch(PDOException $e){
	header("Location: " . "./searchmaps.php");
}
$dbh = null;

header("Location: " . "./serchmaps.php");