<?php
include("include-scripts-headless.php");
require_once 'User.class.php';


//@see http://stackoverflow.com/questions/2687770/do-facebook-oauth-2-0-access-tokens-expire
//and https://developers.facebook.com/blog/post/500/
define('FACEBOOK_APP_ID', '154914597939036');
define('FACEBOOK_SECRET', 'aa9a1c5c6bd7384b7f5df3f8d84c49ad');


require_once 'Config.class.php';
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


	$response = parse_signed_request($_REQUEST['signed_request'],
												FACEBOOK_SECRET);

	 
	if(!isset($response["registration"]["name"])){
		die("El campo 'usuario' es obligatorio");
	}
	$userName = $response["registration"]["name"];
	
	
	
	if(!isset($response["registration"]["email"])){
		die("El campo 'mail' es obligatorio");
	}
	$email = $response["registration"]["email"];
	 
	
	
	if(isset($response["oauth_token"]))
		$oauthToken = $response["oauth_token"];
	else 
		$oauthToken = "";
		
	$oauthProvider = "facebook";
	 
	$user = new User($userName, $email, "", 1, $oauthToken, $oauthProvider);
	
	session_start();
	$_SESSION["user"] = $user;
	
	
	if(! $user->exist($dbh)){
		$user->save($dbh);
	}


}catch(PDOException $e){
	header("Location: " . "./searchmaps.php");
}
$dbh = null;

header("Location: " . "./searchmaps.php");

 


/*

Respuesta que llega cuando el usuario ha metido nombre y password, pero no estaba logado
en facebook

Array
(
[algorithm] => HMAC-SHA256
[issued_at] => 1320848781
[registration] => Array
(
[name] => dev4bloggers
[email] => dev4bloggers@gmail.com
)

[registration_metadata] => Array
(
[fields] => name,email
)

[user] => Array
(
[country] => es
[locale] => es_ES
)

)
* */





/***************************************************
 * Security facebook functions
 * *************************************************
 */
function parse_signed_request($signed_request, $secret) {

	list($encoded_sig, $payload) = explode('.', $signed_request, 2);

	// decode the data
	$sig = base64_url_decode($encoded_sig);
	$data = json_decode(base64_url_decode($payload), true);

	if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
		error_log('Unknown algorithm. Expected HMAC-SHA256');
		return null;
	}

	// check sig
	$expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
	if ($sig !== $expected_sig) {
		error_log('Bad Signed JSON signature!');
		return null;
	}

	return $data;
}

function base64_url_decode($input) {
	return base64_decode(strtr($input, '-_', '+/'));
}
?>

