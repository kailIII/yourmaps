<?php
require_once 'facebook-sdk/facebook.php';

$facebook = new Facebook(array(
  'appId' => FACEBOOK_APP_ID,
  'secret' => FACEBOOK_SECRET,
));

$facebook->destroySession();




session_start();
unset($_SESSION["user"]);


		
$dest = $_GET['dest'];
//if(isset($dest))
//	header("Location: " . $dest);
//else
//	header("Location:searchmaps.php");

if(isset($dest))
{
?>
	<script>
	window.location.href="<?=$dest?>";
	</script>
<?	
}else{
?>	
	<script>
	window.location.href="searchmaps.php";
	</script>
	
<?	
}
?>