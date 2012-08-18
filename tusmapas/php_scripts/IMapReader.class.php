<?php
/**
 * 
 * Implementing class reads map sources and loads them to Looking4Maps database
 * 
 * 
 * @author azabala
 *
 */

interface IMapReader {
	public function loadMap($url, $userCreator, $source, $pdo);
	
	
	public function loadMapFromDB($mapId, $pdo);
}