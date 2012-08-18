<?php
$db_configuracion = parse_ini_file("database.ini");
$hostname = $db_configuracion['ip'];
$username = $db_configuracion['username'];
$password = $db_configuracion['password'];
$database = $db_configuracion['database'];

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
try {
		$dbh = new PDO("mysql:host=$hostname;dbname=$database", $username, $password, array(
   			 PDO::ATTR_PERSISTENT => true
		));
		$dbh->query("SET CHARACTER SET utf8");
		
		
		//FIXME añadir KML_SERVICES y las tablas de servicio que vayamos añadiendo
		$statement = $dbh->query("select service_title, friendly_url from WMS_SERVICES union all select document_name, friendly_url from KML_SERVICES");
		$statement->execute();
		while($row = $statement->fetch()){
			  echo "<url>\n";
			  echo "<loc>http://www.lookingformaps.com/mapa.php?mapa=".$row[1]."</loc>\n";
      		  echo "<changefreq>daily</changefreq>\n";
      		  echo "<priority>0.8</priority>\n";
   			  echo "</url>\n";
		}//while
	echo "</urlset>";
}catch(PDOException $e){
	echo $e->getMessage();
}