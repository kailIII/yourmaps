<?php
class PoiServer extends CI_Controller {
	
	
	public function __construct() {
		parent::__construct();
		
		$this->load->helper('langdetection');
    }
    
    
    public function test(){
    	$this->load->model("poiservicesmodel");
    	$this->poiservicesmodel->listPoiServices();
    }
    
    
   public function admin(){
   		$this->load->model("poiservicesmodel");
   		$poiServices = $this->poiservicesmodel->listPoiServices();
   		$data = array("poi_layers" => $poiServices);
   		$this->load->view("admin", $data);
   }
    
	
    public function getpoisbyposition($layer_id, $x, $y, $radius = 1000){
    	$this->load->model("poimodel");
    	$this->load->model("poiservicesmodel");
    	    	
    	$data = $this->poiservicesmodel->getPoiService($layer_id);
    	
    	if(sizeof($data) > 0){
    		
    		$tableName = $data["table_name"];
    		$geometryColumn = $data["geometry_column_name"];
    		
    		//0,016666667 Degrees = 1857 M.
    		
    		$radiusDegree = ($radius / 1857) * 0.016666667;
    		
    		$pois = $this->poimodel->searchByGeo($tableName, $geometryColumn, $x, $y, $radiusDegree );
    		
    		$data = array("pois" => $pois);
    		
    		$this->load->view("poi_list", $data);
    		
    		
    	}else{
    		$messageKey = "no_se_ha_encontrado_layer";
    		$this->load->view("api_errors", array($messageKey));
    	}
    }
    
    
    
    public function getpoisbytext($layer_id, $search_text){
    	
    }
    
    
    
    public function getpoisbycategory($layer_id, $category){
    	
    }
    
    
    public function suggestpoidescription($latitude, $longitude, $lang = "es"){
    	$url = "http://nominatim.openstreetmap.org/reverse?format=json&lat=".$latitude."&lon=".$longitude;
		$json_string =  file_get_contents($url);
		if (get_magic_quotes_gpc()) {
			$json_string = stripslashes($json_string);
		}
//		$decoded_json = $json->decode($json_string,true);
		echo $json_string;
    	
    }
    
    
    public function addpoi($layer_id, $x, $y, $title, $description){
    	
    }
    
    
    
    public function checkin($layer_id, $poi_id, $user_alias, $description){
    	
    }
    
    public function getcheckins($layer_id, $poi_id){
    	
    }
    
    
    
    
    public function createPoiLayer($layerName, $geometryColumn){
    	try{
    		$this->load->model("poimodel");
    		if($this->poimodel->createPoiTable($layerName, $geometryColumn)){
    			$this->load->model('poiservicesmodel');
    			$this->poiservicesmodel->inserPoiService($layerName, $geometryColumn);
    			$data = array("message" => "Creada layer ".$layerName);
    			$this->load->view("api_messages", $data);	
    		}else {
    			$data = array("message" => "Se ha producido una excepción al crear la tabla ".$layerName." ".$e);
    			$this->load->view("api_errors", $data );
    		}
    		
    	}catch(PoiTableCreationException $e){
    		$data = array("message" => "Se ha producido una excepción al crear la tabla ".$layerName." ".$e);
    		$this->load->view("api_errors", $data );
    	}	
    }
    
    
    
    public function loadManyKmls( $idPoiService, $lang = ""){
    	if($idPoiService != null ) {
    		$this->load->model("poiservicesmodel");
    		$this->load->model("poimodel");
    		$poiService = $this->poiservicesmodel->getPoiService($idPoiService);
    		
    		if(sizeof($poiService) > 0){
    			$tableName = $poiService["table_name"];
    			$geometryColumnName = $poiService["geometry_column_name"];
    			$serviceName = $poiService["service_name"];
    			
    			$kmlList = $this->input->post("kmls");
    			try{
    				$this->poimodel->addPoisFromManyKmls($tableName, $geometryColumnName, $kmlList);
    			}catch(Exception $e){
    				$data = array("message" => "Se ha producido un error:".$e);
    				$this->load->view("api_errors", $data );
    			}
    			
    		}else{
    			$data = array("message" => "No se ha podido localizar la capa ".$idPoiService);
    			$this->load->view("api_errors", $data );
    		}
    		
    		
    	}else {
				$data = array("message" => "Es necesario especificar el parametro idpoiservice ");
    			$this->load->view("api_errors", $data );
    	}
    }
    
    
    
    public function loadKml($lang = null, $idPoiService, $kmlUrl){
    	
    	if($idPoiService != null || $kmlUrl != null) {
    		$this->load->model("poiservicesmodel");
    		$this->load->model("poimodel");
    	
    		$poiService = $this->poiservicesmodel->getPoiService($idPoiService);
    		
    		if(sizeof($poiService) > 0){
    			$tableName = $poiService["table_name"];
    			$geometryColumnName = $poiService["geometry_column_name"];
    			$serviceName = $poiService["service_name"];
    			try{
    				$this->poimodel->addPoisFromKml($tableName, $geometryColumnName, $kmlUrl );
    				
    			}catch(KmlNotRetrievedException $notRetrieved){
    				
    			}catch(NotKmlException $notKml){
    				
    			}catch(KmlWithoutCoordinatesException $withoutCoords){
    				
    			}
    		
    		}else{
    			$data = array("message" => "No se ha podido localizar la capa ".$idPoiService);
    			$this->load->view("api_errors", $data );
    		}
    	}else {
				$data = array("message" => "Es necesario especificar el parametro idpoiservice");
    			$this->load->view("api_errors", $data );
    	}
    }
    
	
}