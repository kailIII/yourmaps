<?php
require_once 'User.class.php';
require_once 'facebook-sdk/facebook.php';
require_once 'Util.class.php';

$config = Config::singleton();
$username = $config->username;
$hostname = $config->hostname;
$password = $config->password;


//pre-requisitos: el sdk de facebook requiere curl en el servidor php
$user = null;

$currentUrl = Util::curPageURL();
$logoutUrl = "logout.php";

	
define('FACEBOOK_APP_ID', '154914597939036');
define('FACEBOOK_SECRET', 'aa9a1c5c6bd7384b7f5df3f8d84c49ad');

$facebook = new Facebook(array(
  'appId' => FACEBOOK_APP_ID,
  'secret' => FACEBOOK_SECRET,
));

// Get User ID
$userId = $facebook->getUser();


if ($userId) {
  try {
  	
    $user_profile = $facebook->api('/me');
    $logoutUrl = $currentUrl;
    
    //at this point we create a user (User.class.php) object with $user_profile information
    $fcbName = $user_profile["name"];
    $fcbLink = $user_profile["link"];
    $fcbLocale = $user_profile["locale"];
    $fcbMail = $user_profile["email"];
    $fcbVerified = $user_profile["verified"];
    $user = new User($fcbName, $fcbMail, null, $fcbVerified, $facebook->getAccessToken(), "facebook");
    
    
	if(! $user->exist($dbh)){
		$user->save($dbh);
	}	    
  } catch (FacebookApiException $e) {
    error_log($e);
    $userId = null;
    $user = null;
  }
} 


?>
	<div id="fb-root"></div>
	<script>
		  function fbLogout(target)
		  {
		      FB.logout(function()
		      {
		          top.location.href = target;
		      });
		  }		   
	</script>
	
	<div class="container">	
				<div class="span-2">
					<a href="/tusmapas/php_scripts/searchmaps.php">
						<img src="../resources/images/compass-map-64x64.png" 
							 alt="Looking for maps icon" 
							 title="Looking for Maps"
							 style="float:left;margin-top:0px">
					</a>
				</div>
				
				<div class="span-5">
					<h2 style="float:left;padding-top: 11px;">Looking for Maps</h2> 
				</div>

<?
				if($user == null)
				{
?>				
				
				<div class="span-2">
					<a class="menu-header" href="#" id="login_dialog_link">Entrar</a>&nbsp;
				</div>
				
				<div id="login_dialog" title="Entrar en Looking4Maps" style="display:none">
					<label>Puedes utilizar para entrar tu cuenta de facebook</label>
						<br></br>
						<fb:login-button data-show-faces="true" 
										 data-width="200" 
										 data-max-rows="1"
										autologoutlink="true"
										 size="large" 
										 onlogin="updateButton"
										 >	
						Login with Facebook
						</fb:login-button>		
				</div>	
<?
				}else{
					
//TODO Decidir cuales son las opciones del usuario registrado, y cambiar un poco el estilo css
//ver como se hace en minube, wikiloc, panoramio, etc.	
//al menos el botón logout
					
					
?>
				<div class="span-2">
					<a class="menu-header" href="user-info.php"><?=$user->getUserName()?></a>&nbsp;
				</div>	
				
				<div class="span-2">
						<a class="menu-header" href="javascript:fbLogout('<?=$logoutUrl?>');">Salir</a>&nbsp;
				
				</div>					
				
<?					
				}
?>		
				<div class="span-2">
					<a class="menu-header" href="addmap.php">Añadir mapa</a>
				</div>
				
				<div class="span-4">
					<a class="menu-header" href="http://localhost/tusmapas/php_scripts/mapsfoundbykeyword.php?keywords=">Ver Todos los mapas</a>
				</div>
				
				<div class="span-2">
					<a class="menu-header" href="acerca-de.php">Acerca de</a>
				</div>
				
						<div class="span-4 prepend-1 last" style="padding-top:10px;margin-top:6px;float:left">
						 	<g:plusone  size="medium"></g:plusone>	
						</div>
				

		</div>