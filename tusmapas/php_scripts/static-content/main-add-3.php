<?php
	include("../include-scripts-headless.php");
	include_once "../Config.class.php";
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
		<link rel="stylesheet" href="../../../resources/css/blueprint/screen.css" type="text/css" media="screen, projection">
  		<link rel="stylesheet" href="../../../resources/css/blueprint/print.css" type="text/css" media="print"> 
  		<!--[if lt IE 8]>
    		<link rel="stylesheet" href="../../../resources/css/blueprint/ie.css" type="text/css" media="screen, projection">
  		<![endif]-->
<!--		<link rel="stylesheet" href="../../../resources/css/blueprint/plugins/screen.css" type="text/css" media="print"> -->
		
		<link rel="stylesheet" type="text/css" href="../../../resources/css/mapa.css" />
		<link rel="stylesheet" type="text/css" href="../../../resources/css/searchmaps.css" />

		
		<script type="text/javascript" language="javascript" src="../../../resources/js/jquery-1.6.1.js">
		</script>	
		
		<script type="text/javascript" src="../../../resources/js/jquery.corner.js?v2.11">
		</script>
		
		<link href="../../../resources/css/jquery-ui/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css"/>
	
		<?
			include($_SERVER["DOCUMENT_ROOT"]."/php_scripts/"."include-scripts.php");
		?>	
			
		<script>
	 	 $(document).ready(function(){
	 		<?
					include($_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/include-scripts-facebook.php");
					include($_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/include-scripts-uservoice.php");
					include($_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/include-scripts-map-metadata-dialog.php");
				?>
		  });
	    </script>	
<? 
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	echo "<meta name=\"keywords\" content=\"Kindle, e-reader, e-book, ebook, wifi integrado, pantalla de tinta electrónica, E Ink\">";
	echo "<meta name=\"Description\" content=\"Descripcion de lookingformaps.com\" />"; 
	echo "<meta name=\"Author\" content=\"Alvaro Zabala Ordóñez - azabala@gmail.com\" />"; 
	echo "<meta name=\"Subject\" content=\"Kindle: e-reader con wifi integrado y pantalla de tinta electrónica E Ink de 15 cm (6 pulgadas)\" />"; 
	echo "<meta name=\"Robots\" content=\"index, follow\" />"; 
	//echo "<link rel=\"shortcut icon\" href=\"http://localhost/spainholydays/favicon.ico\">";
	echo "<link rel=\"copyright\" href=\"http://www.gnu.org/copyleft/fdl.html\">";

	echo "<title>Kindle: e-reader con wifi integrado y pantalla de tinta electrónica E Ink de 15 cm (6 pulgadas) - Looking for maps: search cities and maps of the world </title>";
?>	
		</head>
		
		<body>
			<a class="fixed_banner" href="http://www.amazon.es/gp/product/B0051QVF7A/ref=as_li_ss_tl?ie=UTF8&tag=oposicionesti-21&linkCode=as2&camp=3626&creative=24822&creativeASIN=B0051QVF7A">Compra este producto en Amazon</a><img src="http://www.assoc-amazon.es/e/ir?t=oposicionesti-21&l=as2&o=30&a=B0051QVF7A" width="1" height="1" border="0" alt="" style="border:none !important; margin:0px !important;" />

			<?php include($_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/menu-header.php");?>
			
			<div class="container">
				<div class="span-24 last">
					<script type="text/javascript">
						google_ad_client = "ca-pub-7845495201990236";
						/* lookingformaps2 */
						google_ad_slot = "9961918851";
						google_ad_width = 728;
						google_ad_height = 90;
						//
					</script>
					<script type="text/javascript"
					src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
					</script>
					<h2><a  href="http://www.amazon.es/gp/product/B0051QVF7A/ref=as_li_ss_tl?ie=UTF8&tag=oposicionesti-21&linkCode=as2&camp=3626&creative=24822&creativeASIN=B0051QVF7A">Kindle: e-reader con wifi integrado y pantalla de tinta electrónica E Ink de 15 cm (6 pulgadas)</a><img src="http://www.assoc-amazon.es/e/ir?t=oposicionesti-21&l=as2&o=30&a=B0051QVF7A" width="1" height="1" border="0" alt="" style="border:none !important; margin:0px !important;" />
</h2>
				</div>
			</div>
		
			<div class="container">
				<hr></hr>
				<div class="span-13">
					<img src="http://g-ecx.images-amazon.com/images/G/30/kindle/tequila/dp/KT-slate-main-lg._V161940598_.jpg"></img>
				</div>
				<div class="span-11 last">
					<p>
					<h3>Kindle de Amazon, ebook más vendido</h3><br/>
					<h4>
					El Kindle de Amazon es un dispositivo de lectura, cómodo y fácil de transportar, que permite la descarga de libros por wifi.

					Diseñamos Kindle con una idea en mente: la lectura. La pantalla de tinta electrónica E Ink emplea tinta de verdad, de manera que la imagen parece una página tradicional. No tiene nada que ver con la experiencia de lectura en una pantalla de ordenador. Con Kindle podrás leer en todas partes sin reflejos, incluso a plena luz del sol.

					El Kindle está pensado para desaparecer en tus manos, para que te olvides de él y te sumerjas en el mundo del autor. Es tan fino como una revista, más ligero que un libro de bolsillo y puede contener hasta 1.400 libros, para que puedas llevar tu biblioteca a todas partes. La batería de larga duración te permite leer hasta un mes sin tener que volver a cargarla.

					¿Has terminado de leer un libro? Con el wifi integrado del Kindle podrás descargar el siguiente en menos de 60 segundos, directamente desde el dispositivo y sin necesidad de conectarte a un ordenador. Es fácil de usar desde el primer día, no tienes que instalar ningún software ni sincronizarlo. 
					</h4>
					<br></br>
					<h3>Opiniones de compradores - Navegante</h3><br/>
					<h4>
					Hace ya un par de meses que tengo el nuevo Kindle, que compré en Amazon.com antes de que se pudiera comprar en España.

La verdad es que se trata de un producto prácticamente perfecto para una cosa: leer libros. Entiendo que hay otros ebooks de otras marcas (y de Amazon, que aún no se comercializa en España) con más características, que pueden ser útiles para algunas personas... pero yo la verdad lo único que quiero de un dispositivo como éste es leer libros de la forma más parecida al papel físico, en un dispositivo lo más fácil de llevar posible. Y para eso creo que este Kindle funciona a la perfección.

La construcción es muy robusta. La carcasa es de calidad y da la sensación de que no se romperá fácilmente. En algunas webs comentan que es de Aluminio. No puedo confirmar ese extremo porque es difícil saber el material, pero ciertamente la diferencia es notable con otros lectores que son de plástico 'cutre'. Además, las dimensiones del dispositivo son muy reducidas, y pesa muy poco, factores que contribuyen a leer con mucha comodidad en él.

Los botones de pasar página en los laterales son increíblemente cómodos de usar, y al estar en ambos laterales permiten usar el lector con una sola mano con bastante facilidad, cambiando de mano cuando una se cansa. No son excesivamente duros, pero al mismo tiempo es difícil de presionarlos por error.

La pantalla se merece un capítulo aparte. Como ya debéis saber, se trata de una pantalla E Ink, que a día de hoy es el tipo de pantalla de mayor calidad del mercado. Ciertamente, se ve muy pero que muy bien en comparación con pantallas de tinta electrónica normales, con mucho más contraste y negros más puros, aunque como todas las pantallas de tinta electrónica hay que leer en un lugar con luz suficiente ya que no son retroiluminadas, y es bastante frágil, así que hay que proteger el Kindle con una funda para evitar que cualquier objeto pueda dañarla (llaves, etc) e intentar en lo posible que no caiga de forma que algo pueda golpear en la pantalla, cosa que podría ocasionar marcas permanentes.

El Kindle dispone de Wifi, cosa que facilita enormemente añadir libros nuevos. Cuando compras el dispositivo, Amazon crea una cuenta de email personalizada para tu Kindle, y solo con enviar tus libros a esa dirección de email los recibirás en tu dispositivo. No hay que estar conectándolo al PC cada vez que quieres añadir libros nuevos. Además tienes la opción de comprarlos directamente en la Kindle Store desde el dispositivo, aunque al no tener teclado físico es una operación un poco incómoda (se selecciona la letra con el botón central de 4 direcciones, y se confirma con el botón central, y así letra a letra). Éste es posiblemente el mayor inconveniente del Kindle estándar respecto al Kindle Touch/Keyboard, aunque a decir verdad yo compro los libros siempre por la web, es mucho más cómodo y rápido. Y siempre tienes la opción de comprarlo en el dispositivo aunque sea de manera más lenta si no tienes acceso a un PC. Tanto si lo haces de una manera como de otra, los libros comprados en la Kindle Store se envían automáticamente a tu dispositivo.

La capacidad es de 2GB. Éso representa una reducción respecto al anterior modelo, el Kindle Keyboard. Pero sinceramente creo que es más que suficiente para la mayoría de gente, a menos que sólo leas PDF muy grandes, que en ése caso puede ser poco. Pero usando PDFs de tamaño estándar, y otros formatos de libro electrónico, será difícil quedarse sin espacio.

La duración de la batería es muy buena. Según Amazon es de 1 mes, y parece una estimación bastante realista, apenas lo he tenido que cargar desde que lo tengo. Eso si, recomiendo desactivar Wifi cuando no se esté usando, ya que la duración se reduce cuando está activo (a 3 semanas), y generalmente no es necesario tenerlo activo cuando estamos leyendo un libro, sólo cuando nos interese recibir nuevo contenido.

Cómo veis estoy muy contento con el Kindle, aunque nada es perfecto y el Kindle no es una excepción. Mi mayor queja es que el Kindle no soporta nativamente el formato EPUB. Si bien es cierto que se pueden convertir los libros fácilmente con el programa Calibre, EPUB es un formato muy extendido y es prácticamente inaceptable que el Kindle no lo soporte. Está claro que quieren potenciar su formato propietario, pero creo que éso no es incompatible con ofrecer soporte a uno de los formatos más utilizados.

Realmente por mi parte no hay mucha cosa más que criticar al Kindle, ya que la falta de teclado físico (o táctil) no es nada importante para mí. No voy a comprar libros con el Kindle (para eso tengo el PC) y no voy a navegar con el Kindle (para eso tengo el smartphone, y el PC). Aunque entiendo que puede ser un problema para algunos.

De todos modos, considero que mi valoración global de 5/5 es justa para un dispositivo que cumple todas las expectativas que he puesto en él, y que aunque no es perfecto, si que creo que es uno de los mejores lectores de libros electrónicos en calidad/precio. Puede que pagando más dinero haya algo mejor, pero para mis necesidades y lo que me quería gastar el Kindle es simplemente la mejor opción.

					</h4>
					</p>
				</div>
				
			</div>
			

<?
	include($_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/tailer-widget.php");		
}catch(PDOException $e){
	echo $e->getMessage();
}
$dbh = null;
?>

		</body>
		
