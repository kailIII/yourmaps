<?php
?>

	
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
						window.location.href="<?=$currentUrl?>";
				    	  });
	    		   } else {
	    		     console.log('User cancelled login or did not fully authorize.');
	    		   }         
	 });
};

// Load the SDK Asynchronously
(function(d){
     var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     d.getElementsByTagName('head')[0].appendChild(js);
}(document));



//facebook social comments widget
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/es_ES/all.js#xfbml=1";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

//facebook login dialog initialization code
  var options = {
			autoOpen: false,
			width: 600,
			buttons: {
				"Ok": function() { 
					$(this).dialog("close"); 
				}
			},
			zIndex:99999
	};
	$("#login_dialog").dialog(options);
	

	// Dialog Link
	$('#login_dialog_link').click(function(){
		$('#login_dialog').dialog('open');
		return false;
	});
	
	$('#login_dialog_link, ul#icons li').hover(
		function() { $(this).addClass('menu-header.a'); }, 
		function() { $(this).removeClass('menu-header.a'); }
	);
