<?php
require_once 'User.class.php';
//require_once 'facebook-sdk/facebook.php';
require_once 'Util.class.php';

$config = Config::singleton();
$username = $config->username;
$hostname = $config->hostname;
$password = $config->password;
$database = $config->database;


//pre-requisitos: el sdk de facebook requiere curl en el servidor php
$user = null;
$fcbName = null;
$fcbLink = null;

$currentUrl = Util::curPageURL();
$logoutUrl = "logout.php";

	
//define('FACEBOOK_APP_ID', '154914597939036');
//define('FACEBOOK_SECRET', 'aa9a1c5c6bd7384b7f5df3f8d84c49ad');
//
//$facebook = new Facebook(array(
//  'appId' => FACEBOOK_APP_ID,
//  'secret' => FACEBOOK_SECRET,
//));

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
    $facebook->destroySession();
  }
} //if $userId


?>
	<div id="fb-root"></div>
	<script>
	function trim(s){
		return rtrim(ltrim(s));
	}

	function ltrim(s){
		var l=0;
		while(l < s.length && s[l] == ' ')
		{	l++; }
		return s.substring(l, s.length);
	}

	function rtrim(s){
		var r=s.length -1;
		while(r > 0 && s[r] == ' ')
		{	r-=1;	}
		return s.substring(0, r+1);
	}
			  


	
		  function fbLogout(target)
		  {
		      FB.logout(function()
		      {
		          top.location.href = target;
		      });
		  }	


	  		function showSendingMap() {
				$("#sending").show();
			}
	
			function hideSendingMap() {
//		  		$("#sending").hide();
				$("#sending").css("display","none");
		    }


		    function resetDialog(){
			    $("#sending").empty();
		    	$("#sending").css("display","none");
		    	
		    	$("#messageBox").empty();
		    	$("#messageBox").css("display","none");
		    }
		  
		  function sendMap(){
			  showSendingMap();
			  var mapUri = $("#uri_field").val(); 
			  var mapType = $("input[@name=map_format]:checked").val();


			//checks
			if(trim(mapUri) == "http://" || trim(mapUri) == ""){
				hideSendingMap();
				$("#messageBox").html("<p class='error'>Introduce una dirección correcta</p>");
				$('#messageBox').show();
				return;
			}
			



			  
			  var user = '<?=$user != null?$user->getUserName():"anonymous"?>';
			  
			  $.ajax({
				  type: "GET",
				  url: "api/addmap-backend.php",
				  data: "map="+mapUri+"&type="+mapType+"&user="+user,
				  success: function( data ) {
							hideSendingMap();
							var messageArray = eval('(' + data + ')');

							var messageString = messageArray['message'];
							var extendedMessage = messageArray['extendedMessage'];
							
							var docTitle = messageArray['docName'];
							if(docTitle instanceof Array)
								docTitle = docTitle[0];
							
							var docAbstract = messageArray['description'];
							var keywords = messageArray['keywords'];

							if(undefined != docTitle){
								$("#messageBox").html("<p>Se ha añadido el mapa <b>'"+docTitle	+"'</b></p>");
							}else if(undefined != messageString){
								$("#messageBox").html("<p>"+messageString+"</p");
							}
							$("#messageBox").show();

							     
						},//function data
						error:function(data, textStatus, errorThrown){
							hideSendingMap();
							$("#messageBox").html("<p>"+textStatus+"</p");
							$("#messageBox").show();
						}
		     });//ajax



		   }	   
	</script>
	
	<div class="container">	
				<div class="span-7">
					<a href="./searchmaps.php" style="text-decoration:none">			
						<h2 style="background-image: url('../resources/images/compass-map-64x64.png');
							background-position:left;
							background-repeat: no-repeat;
							padding-left: 67px;float:left;padding-top:12px">Looking 4 Maps</h2> 
					</a>
				</div>

<?
				if($user == null)
				{
?>				
				
				<div class="span-2">
<!--					<a class="menu-header" href="#" id="login_dialog_link">Entrar</a>&nbsp;-->
				<fb:login-button data-show-faces="true" 
										 data-width="200" 
										 data-max-rows="1"
										autologoutlink="true"
										 size="small" 
										 onlogin="updateButton"
										 >	
						Entrar
						</fb:login-button>		
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
					<a class="menu-header" href="user-info.php"><?=$user->getUserName()?> </a>&nbsp;
				</div>	
				
				<div class="span-1 prepend-1">
						<a class="menu-header" href="javascript:fbLogout('<?=$logoutUrl?>');"> <center>Salir</center></a>&nbsp;
				
				</div>					
				
<?					
				}
?>		
				<div class="span-2">
					<a class="menu-header" href="#" id="add_map_dialog_link">Añadir mapa</a>
				</div>
				
				<div id="add_map_dialog" title="Añadir nuevos mapas" style="display: none">
					<label for="map_format">
						¿Deseas añadir <b>nuevos mapas</b> a Looking4Maps.com?
					</label> 
					<br/>
					<p>
						<input type="radio" name="map_format" value="wms" checked="checked">
						OGC WMS (Web Map Service)<br/>
						<input type="radio" name="map_format" value="kml"> 
						KML <br />
						<!-- <input type="radio" name="map_format"> Subir fichero <br/>  -->
					</p>
				
					<!--  uri field -->
					<p>
						<label for="uri_field">URL: </label><br>
						<input type="text" class="title" name="uri_field" id="uri_field" value="http://">
					</p>
				
					<!--  upload file 
					<p style="visibility:hidden;display:none">
						<label for="file_uploader">Sube un fichero</label>					
						<input type="file" multiple="multiple" name="file_uploader">
										
						<p>Soportamos kml y gpx. Próximamente shp y csv</p>
							
						<div class="progress">
								<p>Subiendo tu fichero...</p>
								<span class="progress" style="width: 5px;"></span>
						</div>
					</p>
					--> 
					
					<input type="button" class="large green button" value="Añadir mapa" onClick="sendMap()" />
					
					
					<div id="sending" style="padding-left:20px;display:none;  position:relative;  width:95%; height:95%">
  						<p><img style="padding-left:5px;padding-right:5px;" src="../resources/images/big-ajax-loader.gif" />
  							Añadiendo nuevo mapa...(puede tardar un poco)
  						</p>
					</div>
					
					<div id="messageBox" style="left:140px;display:none; position:relative; width:425px">
					</div>
				</div>
				
				

				<div class="span-3">
					<a class="menu-header" href="./mapsfoundbykeyword.php?keywords=">Mapas disponibles</a>
				</div>
				
	<!--  social buttons -->			
				
				<div class="span-2" style="padding-top:10px;margin-top:6px;float:left">
				 	<g:plusone  size="medium"></g:plusone>	
				</div>
				
				<div class="fb-like span-3" 
					style="padding-top:10px;margin-top:6px;float:left"
					data-href="http://www.lookingformaps.com" 
					data-send="false" 
					data-layout="button_count" 
					data-show-faces="false">
				</div>
				
				
				<div class="span-3 last" style="padding-top:10px;margin-top:6px;float:left">
					<a href="https://twitter.com/share" class="twitter-share-button" data-lang="es">Tweet</a>
				</div>

		</div>