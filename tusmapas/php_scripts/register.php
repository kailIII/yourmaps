<?php
/*
CREATE TABLE `confirm` (  
	`id` int(11) NOT NULL auto_increment,  
	`userid` varchar(128) NOT NULL default '',  
	`key` varchar(128) NOT NULL default '',  
	`email` varchar(250) default NULL,  
	PRIMARY KEY  (`id`)  
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8; 
 
 */
require_once 'Config.class.php';



?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" xml:lang="es" lang="es" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb='http://www.facebook.com/2008/fbml'>
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
			<?php include("menu-header.php")?>			
			<div class="container">
				<div class="span-12">
					<form id="register_form" action="registering-done.php" method="post">
			          <fieldset>
			            <legend>Obtén una cuenta de usuario ¡Es gratis!</legend>
			            <p>
			              <label for="user">Usuario *</label><br>
			              <input type="text" class="title" name="user" id="user" value="">
			            </p>
			            
			             <p>
			              <label for="mail">e-mail *</label><br>
			              <input type="text" class="title" name="mail" id="mail" value="">
			            </p>
			
			            <p>
			              <label for="password1">Contraseña *</label><br>
			              <input type="password" class="title" id="password1" name="password1" value="">
			            </p>
			
			            <p>
			              <label for="password2">Repetir Contraseña*</label><br>
			              <input type="password" class="title" id="password2" name="password2" value="">
			            </p>
			
						<label for="recaptcha_challenge_field">Verificación de humano</label>
						<script type="text/javascript">
			 			var RecaptchaOptions = {
			 				theme : 'white',
			 				lang : 'es'
			 			};
						</script>
			
					   <script type="text/javascript" src="http://api.recaptcha.net/challenge?k=6Lfv0skSAAAAAAxNY5DYtitJSZlYU-OYFDxIv7-S"></script>
					   <noscript>
					   <iframe src="http://api.recaptcha.net/noscript?k=6Lfv0skSAAAAAAxNY5DYtitJSZlYU-OYFDxIv7-S" height="300" width="500" frameborder="0"></iframe><br/>
					   <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
					   <input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
					  </noscript>
			
		
			            <p>
			              <input type="button" class="medium green button" value="Solicitar Cuenta" onClick="javascript:document.forms[0].submit()">
			              <input type="reset" class="medium red button" value="Limpiar">
			            </p>
			
			          </fieldset>
	        		</form>
        		</div>
        		
        		<div class="span-12 last">
			            <label>O utiliza alguna de tus cuentas Facebook, Twitter o Google</label>

					<iframe src="https://www.facebook.com/plugins/registration.php?
					             client_id=154914597939036&
					             redirect_uri=http%3A%2F%2Flocalhost%2Ftusmapas%2Fphp_scripts%2Flogin-facebook.php&
					             fields=name,email"
					        scrolling="auto"
					        frameborder="no"
					        style="border:none"
					        allowTransparency="true"
					        width="100%"
					        height="330">
					</iframe>
					
				
        		</div>
        		
			</div>
</body>
