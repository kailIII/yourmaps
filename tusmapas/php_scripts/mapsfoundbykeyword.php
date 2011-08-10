<?php

include("Config.class.php");
$config = Config::singleton();
$username = $config->username;
$hostname = $config->hostname;
$password = $config->password;

$keyword = $_GET['keywords'];

	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
	echo "<html dir=\"ltr\" xml:lang=\"es\" xmlns=\"http://www.w3.org/1999/xhtml\">";
	echo "<head>";
?>
	<link rel="stylesheet" type="text/css" href="../resources/css/mapa.css" />
	<link rel="stylesheet" type="text/css" href="../resources/css/searchmaps.css" />
	<script type="text/javascript" language="javascript" src="../resources/js/jquery-1.6.1.js">
	</script>	
	<script type="text/javascript" language="javascript" src="../resources/js/scripts.js">
	</script>
<? 
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			
	echo "<meta name=\"keywords\" content=\"".$keywords."mapas, maps, wms, cartografia, google maps, gogle\">";
	echo "<meta name=\"Description\" content=\"".$serviceAbstract."\" />"; 
	echo "<meta name=\"Author\" content=\"Alvaro Zabala Ordóñez - azabala@gmail.com\" />"; 
	echo "<meta name=\"Subject\" content=\"Mapas del mundo basados en servicios según el estándar WMS\" />"; 
	echo "<meta name=\"Robots\" content=\"index, follow\" />"; 
	//echo "<link rel=\"shortcut icon\" href=\"http://localhost/spainholydays/favicon.ico\">";
	echo "<link rel=\"copyright\" href=\"http://www.gnu.org/copyleft/fdl.html\">";

	echo "<title>".$serviceTitle ." - TusMapas: ciudades y mapas de todo el mundo </title>";
	
	try {
		
		$dbh = new PDO("mysql:host=$hostname;dbname=tusmapas", $username, $password);
		//$statement = $dbh->query("select * from WMS_SERVICES where match(service_title,service_abstract, keywords_list, layer_names, layer_titles) against ('".$keyword."') IN NATURAL LANGUAGE MODE");
		
		$dbh->query("SET CHARACTER SET utf8");
		$statement = $dbh->query("select * from WMS_SERVICES, Wms_Keywords, Keywords_Services where Keywords_Services.fk_wms_id = WMS_SERVICES.pk_id and Keywords_Services.fk_keyword_id = Wms_Keywords.pk_id and Wms_Keywords.text like '%".$keyword."%'");
		$statement->execute();
		
		$numResults = $statement->rowCount();
		
		//TODO Generar un snapshop para cada WMS, para que se pueda mostrar desde el cliente
?>	
		</head>
		
		<body>
		 
		 <!--  PASAR ESTO A UN PHP E INCLUIRLO  -->
		 
		<div id="contenedor">
			<div id="postcabecera">
			<a href="#">Registrate</a>&nbsp;
			<a href="#">Entrar</a>&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="#">Reportar error</a>
			<a href="#">Sobre "Tus Mapas"</a>
			<a href="#">Ver mapas indexados</a>
		</div>
			
			
		<!-- PASAR A UN PHP E INCLUIRLO -->	
			
		<div id="cabecera">
			<!-- <a href="."><img class="cabecera" src="../resources/images/logob1-round.png"/></a> -->
			<a ><img class ="cabecera" src="../resources/images/lupa-map.-64x64.png"/></a>
			<h4>Tus Mapas:</h4> <h6>mucho m&aacute;s que Google Maps con la cartograf&iacute;a de la web 2.0</h6>
		</div>
		
		
		<div id="resultadosBusqueda">
		Mapas relacionados con la búsqueda <strong><i><?=$keyword?></i></strong>. <?= $numResults?> resultados.
		
		</div>
<?
		echo "<ul class='trail-list'>";
		while($r = $statement->fetch()) {
			echo "<li class='clearfix'>";
			$title = $r['service_title'];
			$friendlyUrl = $r['friendly_url'];
			$serviceUrl = $r['service_url'];
			$abstract = $r['service_abstract'];
			$wmsVersion = $r['wms_version'];
?>			
			<div class="info">
						<h3><a href="mapa.php?mapa=<?= $friendlyUrl?>" 
						title="<?= $title ?>" 
						class="trail-title"><?= $title?></a>
						</h3>
						
						<!--  espacio para meter un mapa?? -->
						<p class="description">
							<strong></strong> 
<?
							$strlen = strlen($abstract);
							if($strlen > 150){
								echo substr($abstract, 0, 150)."...";
							}else{ 
								echo $abstract;
							}
?>
						</p>					
		    </div> 
<?
		    echo "</li>";
        }//while
        echo "</ul>";
        
        include("keywords-widget.php");
		include("producer-widget.php");
		include("tailer-widget.php");
}catch(PDOException $e){
	echo $e->getMessage();
}
?>