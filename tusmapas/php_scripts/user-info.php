<?php
	include("include-scripts-headless.php");
	include_once "Config.class.php";

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
	
		
?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
	<html dir="ltr" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<link rel="stylesheet" href="../resources/css/blueprint/screen.css" type="text/css" media="screen, projection">
  		<link rel="stylesheet" href="../resources/css/blueprint/print.css" type="text/css" media="print"> 
  		<!--[if lt IE 8]>
    		<link rel="stylesheet" href="../resources/css/blueprint/ie.css" type="text/css" media="screen, projection">
  		<![endif]-->
<!--		<link rel="stylesheet" href="../resources/css/blueprint/plugins/screen.css" type="text/css" media="print"> -->
		
		<link rel="stylesheet" type="text/css" href="../resources/css/mapa.css" />
		<link rel="stylesheet" type="text/css" href="../resources/css/searchmaps.css" />
		
		
		<link href="../resources/css/jquery-ui/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css"/>
									
<?
		include("include-scripts.php");
?>			
		
			
	<!-- **************************************************************************************************** -->	
		<script>
		$(document).ready(function() {
				<?
					include("include-scripts-facebook.php");
					include("include-scripts-uservoice.php"); 
				?>
		});
		</script>
<? 
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
		echo "<meta name=\"keywords\" content=\"mapas, maps, wms, cartografia, google maps, gogle\">";
		echo "<meta name=\"Author\" content=\"Alvaro Zabala Ordóñez - azabala@lookingformaps.com\" />"; 
		echo "<meta name=\"Subject\" content=\"Busca ciudades y mapas de todo el mundo: WMS, KML, KMZ, GPX\" />"; 
		echo "<meta name=\"Robots\" content=\"index, follow\" />"; 
		//echo "<link rel=\"shortcut icon\" href=\"http://localhost/spainholydays/favicon.ico\">";
		echo "<link rel=\"copyright\" href=\"http://www.gnu.org/copyleft/fdl.html\">";
	
		echo "<title>Looking for maps: search cities and maps of the world </title>";
?>	
	</head>
		
	<body>

			<?php include("menu-header.php")?>
			
			<div class="container">
				<div class="span-24 last">
					<script type="text/javascript"><!--
						google_ad_client = "ca-pub-7845495201990236";
						/* lookingformaps2 */
						google_ad_slot = "9961918851";
						google_ad_width = 728;
						google_ad_height = 90;
						//-->
					</script>
					
					<script type="text/javascript"
					src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
					</script>
					
				</div>
			</div>
			
			<div class="container" style="padding:5px 0px"> 
				
<?
	if($user == null){
?>		
<!--					<script>-->
<!--						function openLoginDialog(){-->
<!--							$('#login_dialog').dialog('open');-->
<!--						}-->
<!--					</script>-->
					<div class="span-24 last">
						<p class="alert large loud">Usuario no logado.
<!--						<br></br>Por favor, para acceder a su página de usuario de <em>Looking4Maps</em> ingrese utilizando su <a  href="javascript:openLoginDialog()" >cuenta de Facebook</a>-->
						<br></br>Por favor, para acceder a su página de usuario de <em>Looking4Maps</em> ingrese utilizando su <a  href="<?=$loginUrl?>" >cuenta de Facebook</a>
						</p>
					</div>
<?
	}else{
		$picture = $facebook->api('/me',array('fields' => 'picture','type' => 'large'));
		
		if(is_array($picture['picture']))
			 $pictureUrl = $picture['picture']['data']['url'];
		else 
			$pictureUrl = $picture['picture'];

		$numMaps = $user->getAddedMapsCount($dbh);
?>
					
					<div class="span-24 last">
						<div class="large"><h2><?= $fcbName ?></h2></div>
					</div>
					<div class="span-24 last">
						<p class="strong large loud"><a href="<?=$fcbLink?>"><img  src="<?=$pictureUrl?>"/></a> </p>
					</div>
					
					<div class="span-24 last">
						<p class="large"><h3>Mapas añadidos</h3></p>
						<h1><?=$numMaps?></h1>
<?
		if($numMaps > 0){
					
?>
			<h2><a href="mapsfoundbyuser.php?user=<?=$user->getUserName()?>">Tus mapas</a></h2>
<?
		}
?>
					</div>
					
					<!--by the moment you couldnt tag maps 
					<div class="span-24 last">
						<p class="large"><h3>Mapas etiquetados</h3></p>
					</div>
					 -->
					
<!--					<div class="span-24 last">-->
<!--						<p class="large"><h3>Comentarios</h3></p>-->
<!--					</div>-->
<!--<?
					$comments = $facebook->api('/me/comments/?ids=http://localhost/mapa.php');
					//facebook response has to arrays.
					//$comments['me'] and $comments['url']. Each array could have two "childrens", 'data' and page
					$myComments = $comments['me'];
					
					//http://developers.facebook.com/docs/reference/api/Comment/
					$commentsData = $myComments['comments']['data'];
					$pagingData = $myComments['paging'];//if sizeof(pagingData) > 0 numComments > 25
					$num = sizeof($commentsData);
					if($num > 0){
						for($i = 0; $i < $num; $i++){
							$comment = $commentsData[$i];	
?>
					<div class="span-24 last">
						<p class="added"><?= $comment["message"]?>, añadido el <?= $comment['created_time']?></p>
					</div>				
<?
						}//for
					}else {
?>
					<div class="span-24 last">
						<p class="large em push-3"><h2>No has comentado mapas.</h2></p>
					</div>
<?					
					}//else
?>
					--><!-- favourites not implemented yet 
					<div class="span-24 last">
						<p class="strong large loud">Favoritos</p>
					</div>
					 -->
					
<?
				if($user->getAdmin()){
?>

						<div class="span-24 last">
							<p class="large"><h3>Herramientas de administración</h3></p>
						</div>
                        <hr/>
						<div class="span-8">
							<input type="button"  
								class="green large button" 
								value="Indexar nuevos mapas" 
								onClick="javascript:window.location.href='add-maps-admin.php'" />
						</div>
						
						
						<div class="span-8">
							<input type="button"  
								class="blue large button" 
								value="Comprobar integridad de keywords" 
								onClick="javascript:window.location.href='check-keywords.php'" />
						</div>
<?
					}//if getAdmin
		}//else
?>			
	</div><!-- container -->
<?		
		include("tailer-widget.php");		
}catch(PDOException $e){
	echo $e->getMessage();
}
$dbh = null;
?>

		</body>
		
