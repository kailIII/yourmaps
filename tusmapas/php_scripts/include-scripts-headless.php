<?php
require_once 'facebook-sdk/facebook.php';
include_once "Config.class.php";
//define('FACEBOOK_APP_ID', '154914597939036');
//define('FACEBOOK_SECRET', 'aa9a1c5c6bd7384b7f5df3f8d84c49ad');

$config = Config::singleton();

$facebookAppId = $config->facebookAppId;
$facebookSecret = $config->facebookSecret;


define('FACEBOOK_APP_ID', $facebookAppId);
define('FACEBOOK_SECRET', $facebookSecret);


$facebook = new Facebook(array(
  'appId' => FACEBOOK_APP_ID,
  'secret' => FACEBOOK_SECRET,
  'cookie' => true

));

$currentUrl = $facebook->getCurrentUrl();
?>

<script type="text/javascript">
  //Google Analytics code 	
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-33972514-1']);
  _gaq.push(['_setDomainName', 'lookingformaps.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
