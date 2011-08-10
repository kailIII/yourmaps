<?php
$db_configuracion = parse_ini_file("database.ini");
$hostname = $db_configuracion['ip'];
$username = $db_configuracion['username'];
$password = $db_configuracion['password'];


echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
try {
		$dbh = new PDO("mysql:host=$hostname;dbname=tusmapas", $username, $password);
		$dbh->query("SET CHARACTER SET utf8");
		$statement = $dbh->query("select service_title, friendly_url from WMS_SERVICES");
		$statement->execute();
		while($row = $statement->fetch()){
			  echo "<url>";
			  echo "<loc>http://www.tusmapas.net/mapas/".$row[1]."</loc>";
      		  echo "<changefreq>monthly</changefreq>";
      		  echo "<priority>0.8</priority>";
   			  echo "</url>";
		}//while
	echo "</urlset>";
}catch(PDOException $e){
	echo $e->getMessage();
}