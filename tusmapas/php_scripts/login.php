<?php
//probamos a ver si los datos de los usuarios ya están cargados???

//require_once 'facebook-sdk/facebook.php';
//
//define('FACEBOOK_APP_ID', '154914597939036');
//define('FACEBOOK_SECRET', 'aa9a1c5c6bd7384b7f5df3f8d84c49ad');
//
//
//
//$facebook = new Facebook(array(
//  'appId' => FACEBOOK_APP_ID,
//  'secret' => FACEBOOK_SECRET,
//));
//
//// Get User ID
//$user = $facebook->getUser();
//
//
//if ($user) {
//  try {
//    $user_profile = $facebook->api('/me');
//  } catch (FacebookApiException $e) {
//    error_log($e);
//    $user = null;
//  }
//}
//
//// Login or logout url will be needed depending on current user state.
//if ($user) {
//  $logoutUrl = $facebook->getLogoutUrl();
//} else {
//  $loginUrl = $facebook->getLoginUrl();
//}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" xml:lang="es" lang="es" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb='http://www.facebook.com/2008/fbml'  >
<head>
	<script type="text/javascript" language="javascript" src="../resources/js/jquery-1.6.2.min.js">
	</script>
	
	 <meta http-equiv="Content-Type" content="text/html; charset=utf-8">		
	 <meta name="keywords" content="mapas, maps, wms, cartografia, google maps, gogle, kml, cartography, turismo, viajes, hoteles, tourism, trips, journeys, vinos, gastronomia">
	 <meta name="Description" content="Descarga de capas y mapas en formato KML. Mapas del mundo basados en formatos estándar: WMS, WFS, KML, GPX, DXF, etc." />
	 <meta name="Author" content="Alvaro Zabala Ordóñez - azabala@gmail.com" />
	 <meta name="Subject" content="Mapas del mundo basados en servicios estándar: KML, WMS, WMS-T, etc." />
	 <meta name="Robots" content="index, follow" />
<!--	 <link rel="shortcut icon" href="http://localhost/spainholydays/favicon.ico">-->
			
			
	<link rel="copyright" href="http://www.gnu.org/copyleft/fdl.html">
	<link rel="stylesheet" href="../resources/css/blueprint/screen.css" type="text/css" media="screen, projection">
  	<link rel="stylesheet" href="../resources/css/blueprint/print.css" type="text/css" media="print"> 
	<!--[if lt IE 8]>
	 <link rel="stylesheet" href="../resources/css/blueprint/ie.css" type="text/css" media="screen, projection">
	 <![endif]-->
	<link rel="stylesheet" type="text/css" href="../resources/css/mapa.css" />

			
  		<title>Thanks for contributing with new maps - Looking for maps: cities and maps of the world</title> 
</head>

<body>
	<!-- facebook login button -->
	<div id="fb-root"></div>
	 <script src="http://connect.facebook.net/en_US/all.js"></script>
		<script>
		  window.fbAsyncInit = function() {
		    FB.init({
		      appId      : '154914597939036', // App ID
		      channelURL : '//http://localhost/tusmapas/php_scripts/facebook-sdk/channel.php', // Channel File
		      status     : true, // check login status
		      cookie     : true, // enable cookies to allow the server to access the session
		      oauth      : true, // enable OAuth 2.0
		      xfbml      : true  // parse XFBML
		    });


		    FB.Event.subscribe('auth.login', function(response) {

		    	
		    		   if (response.authResponse) {
			    		     FB.api('/me', function(response) {
								var userName = response.name;
								var userLogin = response.username;
								var email = response.email;
								window.location.href="login-facebook.php?referer=login&userName="+userName+"&email="+email;
			    		     });
		    		   } else {
		    		     console.log('User cancelled login or did not fully authorize.');
		    		   }
			    
//				if(response["status"] == 'connected'){
//					//se supone que ya el servidor debe ser capaz de leer la sesion
//					var authResponse = response["authResponse"];
//
//					var accessToken = authResponse["accessToken"];
//					var expiresIn = authResponse["expiresIn"];
//					var signedRequest = authResponse["signedRequest"];
//					var userID = authResponse["userID"];
//
//
//		
//
//
//					
//					window.location.href="login-facebook.php?signed_request="+signedRequest;
//				}else{
//					alert("not connected");
//					window.location.href="login-facebook.php";
//				}


						         
		     });
		    
//	        FB.Event.subscribe('{% if current_user %}auth.logout{% else %}auth.login{% endif %}', function(response) {
//	          window.location.reload();
//	        });


			    
		    
		  };

		 
			  
		
		  // Load the SDK Asynchronously
		  (function(d){
		     var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
		     js = d.createElement('script'); js.id = id; js.async = true;
		     js.src = "//connect.facebook.net/en_US/all.js";
		     d.getElementsByTagName('head')[0].appendChild(js);
		   }(document));
		</script>
		
		
			<?php include("menu-header.php")?>			
			<div class="container">
				<div class="span-12">
					<form id="register_form" action="registering-done.php" method="post">
			          <fieldset>
			            <label>Si ya estás registrado</label>
			            <p>
			              <label for="user">Usuario ó Correo Electrónico</label><br>
			              <input type="text" class="title" name="user" id="user" value="">
			            </p>
			           
			            <p>
			              <label for="password1">Contraseña</label><br>
			              <input type="password" class="title" id="password1" name="password1" value="">
			            </p>
			
			            <p>
			              <input type="button" class="medium green button" value="Entrar" onClick="javascript:document.forms[0].submit()">
			            </p>
			          </fieldset>
	        		</form>
        		</div>
        		
        		

				<script>
//				function updateButton(response) {
//				  var button = document.getElementById('fb-auth');
//				
//				  if (response.session) {
//				    button.innerHTML = 'Logout';
//				    button.onclick = function() {
//				      FB.logout(function(response) {
//				        Log.info('FB.logout callback', response);
//				      });
//				    };
//				  } else {
//				    button.innerHTML = 'Login';
//				    button.onclick = function() {
//				      FB.login(function(response) {
//				        Log.info('FB.login callback', response);
//				    
//				    if (response.status == 'connected') {
//				      FB.getLoginStatus(function(response) {
//				        Log.info('FB.getLoginStatus callback', response);
//				        if (response.session) {
//				          Log.info('User is logged in');
//				        } else {
//				          Log.info('User is logged out');
//				        }
//				      }, true);
//				    } else {
//				          Log.info('User is logged out');
//				        }
//				      });
//				    };
//				  }
//				}
//
//				// run it once with the current status and also whenever the status changes
//				FB.getLoginStatus(updateButton);
//				FB.Event.subscribe('auth.statusChange', updateButton);
				</script>
        		
        		<div class="span-12 last">
			            <label>O utiliza tu cuenta de Facebook</label>
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
        		
			</div>
</body>

