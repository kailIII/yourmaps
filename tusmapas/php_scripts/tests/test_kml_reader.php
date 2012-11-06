<?php

include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/Config.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/Util.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/MapAlreadyExistException.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/NotAuthorizedException.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/KmlWithoutCoordinatesException.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/WmsWithoutBBoxException.class.php";
include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/user_exceptions/UnknownMapException.class.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/kml/KmlReader.class.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/wms-simple/WmsReader.class.php";

include_once  $_SERVER["DOCUMENT_ROOT"]."/php_scripts"."/ReaderFactory.class.php";

$aMap = "http://www.ikimap.com/querykml?nid=1982&dl=t&old=0";
		
	try {
		$aMapReader = new KmlReader();
		$aMapReader->loadMap($aMap, $user, "ikimap.com", $dbh);
		
		$messageText .= "El mapa ".$aMap." cargado correctamente<br/></br>";
		
		
		unset($aMapReader);
		

	}catch (MapAlreadyExistException $e){
		$alreadyExist++;
	}catch (NotKmlException $e){
		$messageText .= "El mapa ".$aMap." no es del tipo KML" . "<br/><br/>";
		$wrongKml++;
	} catch (NotAuthorizedException $e){
		$messageText .= "El mapa ".$aMap." está securizado" . "<br/><br/>";
	}catch (KmlWithoutCoordinatesException $e){
		$messageText .= "El mapa ".$aMap." es un KML sin coordenadas" . "<br/><br/>";
		$kmlWithoutCoords++;
	}catch (UnknownMapException $e){
		$messageText .= "El mapa ".$aMap." es de tipo desconocido" . "<br/><br/>";
		$unknownType++;
	}catch(XmlException $e){
		$messageText .= "El mapa ".$aMap." no es XML bien formado" . "<br/><br/>";
		$wrongXml ++;
	}catch(PDOException $e){
		$messageText .= "Problemas con la BBDD MySQL ".$aMap." <br/><br/>";
	}
	