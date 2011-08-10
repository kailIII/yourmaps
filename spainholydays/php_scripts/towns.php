<?php

require_once('../magpierss-0.72/rss_fetch.inc');
require_once 'Town.class.php';

$db_configuracion = parse_ini_file("database.ini");
/*** mysql hostname ***/
$hostname = $db_configuracion['ip'];
$username = $db_configuracion['username'];
$password = $db_configuracion['password'];

$requiredTown = $_GET['municipio'];

try {
		$dbh = new PDO("mysql:host=$hostname;dbname=visitspain", $username, $password);
		$dbh->query("SET CHARACTER SET utf8");
		$statement = $dbh->query("select j_NOMBRE, J_nombrepro, codINEMuni, GID,WIKIPEDIA_ES, WIKITRAVEL_ES, WOEID, xmin, ymin, xmax, ymax from municipios where j_NOMBRE like '".$requiredTown."'");
		$statement->execute();
		
		$municipio = null;
		if($row = $statement->fetch()){
			$municipio = new Town($row);
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
echo "<html dir=\"ltr\" xml:lang=\"es\" xmlns=\"http://www.w3.org/1999/xhtml\">";
echo "<head>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">";
echo "<meta name=\"keywords\" content=\"".$municipio->nombre.",".$municipio->provincia.",España, Turismo, Viajes, Casa Rural, Hoteles, Spain\">";
echo "<link rel=\"shortcut icon\" href=\"http://localhost/spainholydays/favicon.ico\">";
echo "<link rel=\"copyright\" href=\"http://www.gnu.org/copyleft/fdl.html\">";

echo "<title>".$municipio->nombre .",". $municipio->provincia . ", España - TusMapas: ciudades y mapas de España </title>";

echo "<style type=\"text/css\" media=\"screen,projection\">";
echo "/*<![CDATA[*/ @import \"plantilla1.css\"; /*]]>*/";
echo "</style>";
echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"handheld\" href=\"plantilla1.css\">";

echo "<style type=\"text/css\">";

//echo "#mapContainer {";
//echo  	"height: 400px;";
//echo    "width: 530px;";
//echo    "border: 3px solid #C0C0C0";
//echo  "}";
//
//echo "#mapAttribution {";
//echo  "padding: 15px;";  
//echo "}";
echo "</style>"; 	
echo "</head>";


	//1. El tiempo en Yahoo
	$url = "http://weather.yahooapis.com/forecastrss?w=".$municipio->yahoo_woeid."&u=c&language=es";
	$rss = fetch_rss( $url );
	
	echo  $rss->channel['title'] . "<p>";
	
	//FIXME Revisar el objeto RSS para ver como se puede presentar
	//mejor la información sobre el tiempo
	
	//FIXME Ver si es muy lento leyendo RSS dinamicamente. Si si, crearemos un PHP para cada
	//RSS enlazables desde la página principal de la ciudad.
	echo "<ul>";
	foreach ($rss->items as $item) {
		$href = $item['link'];
		$title = $item['title'];
		echo "<td class=\"listaCosas\"<li><a href=$href>$title</a></li></td>";
	}
	echo "</ul>";
	
	
	//2. Enlaces en Delicious
	$url = "http://feeds.delicious.com/v2/rss/tag/".$municipio->nombre;
	$rss = fetch_rss( $url );
	
	echo  $rss->channel['title'] . "<p>";
	echo "<ul>";
	foreach ($rss->items as $item) {
		$href = $item['link'];
		$title = $item['title'];
		echo "<td class=\"listaCosas\"<li><a href=$href>$title</a></li></td>";
	}
	echo "</ul>";
	
	//3. Busqueda de blogs de Google
	$url = "http://blogsearch.google.es/blogsearch_feeds?hl=es&lr=&q=".$municipio->nombre."&ie=utf-8&num=300&output=rss";
	$rss = fetch_rss( $url );
	
	echo  $rss->channel['title'] . "<p>";
	echo "<ul>";
	foreach ($rss->items as $item) {
		$href = $item['link'];
		$title = $item['title'];
		echo "<td class=\"listaCosas\"<li><a href=$href>$title</a></li></td>";
	}
	echo "</ul>";
	
	//4. Noticias de la ciudad en Google News 
	$url = "http://news.google.es/news?q=".$municipio->nombre."&hl=es&ie=utf-8&num=30&output=rss";
	$rss = fetch_rss( $url );
	
	echo  $rss->channel['title'] . "<p>";
	echo "<ul>";
	foreach ($rss->items as $item) {
		$href = $item['link'];
		$title = $item['title'];
		echo "<td class=\"listaCosas\"<li><a href=$href>$title</a></li></td>";
	}
	echo "</ul>";
		}else{
			
		}
}catch(PDOException $e){
	echo $e->getMessage();
}
?>