<?php
/*
 Admin screen to add mapping sources to looking for maps database:

 map sources types are:

 -wms

 -kml (single url or full sites with a php crawler?)


 -csw catalogs??


 -etc

 * */
include("include-scripts-headless.php");
include_once "Config.class.php";
$config = Config::singleton();
$username = $config->username;
$hostname = $config->hostname;
$password = $config->password;
$database = $config->database;

//unique
$processId = uniqid('process_');

try {
	$dbh = new PDO("mysql:host=$hostname;dbname=$database",
	$username, $password,
	array(PDO::ATTR_PERSISTENT => true));

	$dbh->query("SET CHARACTER SET utf8");


	?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html dir="ltr" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="../resources/css/blueprint/screen.css"
	type="text/css" media="screen, projection">
<link rel="stylesheet" href="../resources/css/blueprint/print.css"
	type="text/css" media="print"><!--[if lt IE 8]>
    		<link rel="stylesheet" href="../resources/css/blueprint/ie.css" type="text/css" media="screen, projection">
  		<![endif]--> <!--		<link rel="stylesheet" href="../resources/css/blueprint/plugins/screen.css" type="text/css" media="print"> -->

<link rel="stylesheet" type="text/css" href="../resources/css/mapa.css" />
<link rel="stylesheet" type="text/css"
	href="../resources/css/searchmaps.css" />


<link
	href="../resources/css/jquery-ui/ui-lightness/jquery-ui-1.8.16.custom.css"
	rel="stylesheet" type="text/css" />

	<?
	include("include-scripts.php");
	?> <!-- **************************************************************************************************** -->
<script>
		$(document).ready(function() {
				<?
					include("include-scripts-facebook.php");
					include("include-scripts-uservoice.php"); 
					
					
				?>

				//add map dialog
				var dialogOptions = {
						autoOpen: false,
						width: 600,
						buttons: {},
						zIndex:99999
				};
				$("#addmap_batch_dialog").dialog(optionsMapDialog);
				
				
				$("#addmap_batch_dialog").bind('dialogclose', function(event) {
			    	resetDialog2();
			 	});
				
		});
		</script> <? 
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
		echo "<meta name=\"keywords\" content=\"mapas, maps, wms, cartografia, google maps, gogle\">";
		echo "<meta name=\"Author\" content=\"Alvaro Zabala Ordóñez - azabala@lookingformaps.com\" />";
		echo "<meta name=\"Subject\" content=\"Busca ciudades y mapas de todo el mundo: WMS, KML, KMZ, GPX\" />";
		echo "<meta name=\"Robots\" content=\"index, follow\" />";
		//echo "<link rel=\"shortcut icon\" href=\"http://localhost/spainholydays/favicon.ico\">";
		echo "<link rel=\"copyright\" href=\"http://www.gnu.org/copyleft/fdl.html\">";

		echo "<title>Add new maps - Looking for maps: search cities and maps of the world </title>";
		?> 
	

</head>

<body>

		<?php include("menu-header.php")?>
<script>
	var processId = '<?= $processId?>';
	function showSendingMap2() {
		$('#addmap_batch_dialog').dialog('open');
	}


	function hideSendingMap2(){
		
//	  		$("#sending").hide();
			$("#sending2").css("display","none");
	   
	}
	
    function resetDialog2(){
	    //$("#sending2").empty();
    	//$("#sending2").css("display","none");
    	
    	$("#messageBox2").empty();
    	//$("#messageBox2").css("display","none");

    	//$('#addmap_batch_dialog').dialog('close');
    }

    function addIkimaps(){
    	resetDialog2();
		showSendingMap2();

		var user = '<?=$user != null?$user->getUserName():"anonymous"?>';

		  //first we sent the request with maps
		 $.ajax({
			  type: "POST",
			  url: "api/import-ikimap-backend.php",
			  data: "user="+user+"&processid="+processId,
			  success: function( data ) {
						hideSendingMap2();
						var messageArray = data;
						var messageString = messageArray['message'];
						if(undefined != messageString){
							$("#messageBox2").html("<p>"+messageString+"</p");
						}
						$("#messageBox2").show();
						//resetDialog2();
						     
					},//function data
					error:function(data, textStatus, errorThrown){
						hideSendingMap2();
						$("#messageBox2").html("<p>"+textStatus+"</p");
						$("#messageBox2").show();
					}
	     });//ajax

	     poll();
     }



    function addGoolZoom(){
    	resetDialog2();
		showSendingMap2();

		var user = '<?=$user != null?$user->getUserName():"anonymous"?>';

		  //first we sent the request with maps
		 $.ajax({
			  type: "POST",
			  url: "api/import-goolzoom-backend.php",
			  data: "user="+user+"&processid="+processId,
			  success: function( data ) {
						hideSendingMap2();
						var messageArray = data;
						var messageString = messageArray['message'];
						if(undefined != messageString){
							$("#messageBox2").html("<p>"+messageString+"</p");
						}
						$("#messageBox2").show();
						//resetDialog2();
						     
					},//function data
					error:function(data, textStatus, errorThrown){
						hideSendingMap2();
						$("#messageBox2").html("<p>"+textStatus+"</p");
						$("#messageBox2").show();
					}
	     });//ajax

	     poll();
     }
  
  	

	
	function addMaps(){
		resetDialog2();
		showSendingMap2();
		
		var servers = $("#service_to_index").val();

		var user = '<?=$user != null?$user->getUserName():"anonymous"?>';

		 

		  //first we sent the request with maps
		 $.ajax({
			  type: "POST",
			  url: "api/addmap-batch-backend.php",
			  data: "maps="+encodeURIComponent(servers)+"&user="+user+"&processid="+processId,
			  success: function( data ) {
						hideSendingMap2();
						//var messageArray = eval('(' + data + ')');
						var messageArray = data;
						var messageString = messageArray['message'];
						if(undefined != messageString){
							$("#messageBox2").html("<p>"+messageString+"</p");
						}
						$("#messageBox2").show();
						//resetDialog2();
						     
					},//function data
					error:function(data, textStatus, errorThrown){
						hideSendingMap2();
						$("#messageBox2").html("<p>"+textStatus+"</p");
						$("#messageBox2").show();
					}
	     });//ajax

	     poll();

	     
		//after that we monitor the progress of the map creation proces
	 
	}

	function poll(){
		 setTimeout(function() {
			  $.ajax({ 
				  type: "POST",
				  url: "api/addmap-batch-backend-progress.php", 
				  data: "processid="+processId,
				  success: function(data) { 
					  	//hideSendingMap2();
//						var messageArray = eval('(' + data + ')');
						var messageArray = data;
						var messageString = messageArray['message'];
						if(undefined != messageString){
							$("#messageBox2").html("<p>"+messageString+"</p");
						}
						$("#messageBox2").show();
						//resetDialog2();
				  }, 
				  dataType: "json", 
				  complete: poll 
			   }); 
		   }, 30000); 

	}


	function sendMap2(mapUri){
		
		//checks
		if(trim(mapUri) == "http://" || trim(mapUri) == ""){
			hideSendingMap2();
			$("#messageBox2").html("<p class='error'>Introduce una dirección correcta</p>");
			$('#messageBox2').show();
			return;
		}

	  	var user = '<?=$user != null?$user->getUserName():"anonymous"?>';
	  
	  $.ajax({
		  type: "GET",
		  url: "api/addmap-backend.php",
		  data: "map="+mapUri+"&type="+mapType+"&user="+user,
		  success: function( data ) {
					hideSendingMap2();
					var messageArray = eval('(' + data + ')');

					var messageString = messageArray['message'];
					var extendedMessage = messageArray['extendedMessage'];
					
					var docTitle = messageArray['docName'];
					if(docTitle instanceof Array)
						docTitle = docTitle[0];
					
					var docAbstract = messageArray['description'];
					var keywords = messageArray['keywords'];

					if(undefined != docTitle){
						$("#messageBox2").html("<p>Se ha añadido el mapa <b>'"+docTitle	+"'</b></p>");
					}else if(undefined != messageString){
						$("#messageBox2").html("<p>"+messageString+"</p");
					}
					$("#messageBox2").show();

					     
				},//function data
				error:function(data, textStatus, errorThrown){
					hideSendingMap2();
					$("#messageBox2").html("<p>"+textStatus+"</p");
					$("#messageBox2").show();
				}
     });//ajax

   }	   
	</script>
<div class="container" style="padding: 5px 0px"><?
if($user == null){
	?> 
<!--	<script>-->
<!--						function openLoginDialog(){-->
<!--							$('#login_dialog').dialog('open');-->
<!--						}-->
<!--					</script>-->
<div class="span-24 last">
<p class="alert large loud">Usuario no logado. <br></br>
Por favor, para acceder a su página de usuario de <em>Looking4Maps</em>
ingrese utilizando su <a href="<?=$loginUrl?>">cuenta de Facebook</a>
</p>
</div>
	<?
}else if(! $user->getAdmin()){
	?>

<div class="span-24 last">
<p class="alert large loud">Usuario no administrador. <br></br>
A esta página solo puede acceder un usuario administrador.</p>
</div>
	<?


} else{
	$picture = $facebook->api('/me',array('fields' => 'picture','type' => 'large'));
	$pictureUrl = $picture['picture'];
	?>

<div class="span-10 last" style="padding-right: 5px">
<div class="large">
<h2>Indexar nuevos mapas</h2>
</div>
</div>
<form>
<div class="span-24 last"><label for="service_to_index">Mapas para
monitorizar (WMS ó KML)</label></div>
<br />
<div class="span-24 last"><textarea class="span-20 last"
	id="service_to_index" rows="15" cols="45"></textarea></div>
</form>
<div class="span-8"><br />
<input type="button" class="green medium button" value="Añadir mapas"
	onClick="javascript:addMaps()" />
</div>

<div class="span-8"><br />
	<input type="button"  
		class="red medium button" 
		value="Importar Ikimap" 
		onClick="javascript:addIkimaps()" />
</div>

<div class="span-8"><br />
	<input type="button"  
		class="blue medium button" 
		value="Importar GoolZoom" 
		onClick="javascript:addGoolZoom()" />
</div>

<div id="addmap_batch_dialog" title="Añadiendo nuevo lote de mapas"
	style="display: none">

	<div id="sending2"
		style="padding-left: 20px; position: relative; width: 95%; height: 95%">
	<p><img style="padding-left: 5px; padding-right: 5px;"
		src="../resources/images/big-ajax-loader.gif" /> Añadiendo nuevos
	mapas...(puede tardar un poco)</p>
	</div>

	<div id="messageBox2"
		style="left: 140px; display: none; position: relative; width: 425px; height: 300px; overflow: scroll"></div>
	</div>

</div>
<!-- container -->
	<?
}
include("tailer-widget.php");
}catch(PDOException $e){
	echo $e->getMessage();
}
$dbh = null;
?>

</body>