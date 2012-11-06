<?php
$db_configuracion = parse_ini_file("database.ini");
$hostname = $db_configuracion['ip'];
$username = $db_configuracion['username'];
$password = $db_configuracion['password'];
$database = $db_configuracion['database'];


 $page = $_GET['page'];

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
try {
		$dbh = new PDO("mysql:host=$hostname;dbname=$database", $username, $password, array(
   			 PDO::ATTR_PERSISTENT => true
		));
		$dbh->query("SET CHARACTER SET utf8");
		
		
		$query = "select service_title, friendly_url from WMS_SERVICES union all select document_name, friendly_url from KML_SERVICES";
		
		$offset = $page * 25000;
		
		if($offset > 0){
			$query .= " LIMIT ".$offset.", 25000";
		}
		
		
	
		
		//FIXME añadir KML_SERVICES y las tablas de servicio que vayamos añadiendo
		$statement = $dbh->query($query);
		if($statement){
			while($row = $statement->fetch()){
	?>
				  <url>
					  <loc>http://www.lookingformaps.com/mapa.php?mapa=<?=$row[1]?></loc>
		      		  <changefreq>monthly</changefreq>
		      		  <priority>0.8</priority>
	   			  </url>
	   			  
	   			    <url>
					  <loc>http://www.lookingformaps.com/mapamaximizado.php?mapa=<?=$row[1]?></loc>
		      		  <changefreq>monthly</changefreq>
		      		  <priority>0.8</priority>
	   			  </url>
	
	<?
			}//while
		}//if
	echo "</urlset>";
}catch(PDOException $e){
	echo $e->getMessage();
}