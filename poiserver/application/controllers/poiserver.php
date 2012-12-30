<?php
class PoiServer extends CI_Controller {


	public function __construct() {
		parent::__construct();

		$this->load->helper('langdetection');
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
			$this->load->view("api_errors", array("message" => $messageKey));
		}
	}



	public function getpoisbytext($layer_id, $search_text){
		$this->load->model("poimodel");
		$this->load->model("poiservicesmodel");
		
		$search_text = urldecode($search_text);

		$data = $this->poiservicesmodel->getPoiService($layer_id);
			
		if(sizeof($data) > 0){

			$tableName = $data["table_name"];
			$pois = $this->poimodel->searchbytext($tableName, $search_text);

			$data = array("pois" => $pois);

			$this->load->view("poi_list", $data);


		}else{
			$messageKey = "no_se_ha_encontrado_layer";
			$this->load->view("api_errors", array("message" => $messageKey));
		}
	}



	public function getpoisbycategory($layer_id, $category){
			
	}


	public function suggestpoidescription($longitude, $latitude, $lang = "es"){
		$url = "http://nominatim.openstreetmap.org/reverse?format=json&lat=".$latitude."&lon=".$longitude."&accept-language=".$lang;
		$json_string =  file_get_contents($url);
		if (get_magic_quotes_gpc()) {
			$json_string = stripslashes($json_string);
		}
		
		$info = json_decode($json_string);
		//		$decoded_json = $json->decode($json_string,true);
		$solution = array(
			"poi_description" => $info->display_name
		);
		echo json_encode($solution);
			
	}


	public function addpoi($layer_id, $x, $y, $title, $description){
		try{
			$this->load->model("poimodel");
			$this->load->model("poiservicesmodel");

			$data = $this->poiservicesmodel->getPoiService($layer_id);

			if(sizeof($data) > 0){

				$tableName = $data["table_name"];
				$geometryColumn = $data["geometry_column_name"];

				$title = urldecode($title);
				$description = urldecode($description);

				$wktText = 'POINT('.$x.' '. $y.')';

				$newpoi_id = $this->poimodel->insertPoi($tableName, $geometryColumn, $title,
					$description, $wktText, "", "");

				if($newpoi_id){
					$data = array("message" => "ok",
								  "newpoi_id" => $newpoi_id);
					$this->load->view("api_messages", array("message"=> $data));
				}else{
					$data = array("message" => "No se ha insertar el POI ".$title." ".$description." " .$this->db->_error_message());
					$this->load->view("api_errors", array("message" => $data ));
				}

			}else{
				$message = "No se ha encontrado la capa ".$layer_id;
				$this->load->view("api_errors", array("message" => $message));
			}

		}catch(Exception $e){
			$data = array("message" => "Se ha producido una excepción al añadir el poi ".$title." ".$description);
			$this->load->view("api_errors", $data );
		}
	}



	public function checkin($layer_id, $poi_id, $user_alias, $password, $description){
		try{
			$this->load->model("poimodel");
			$this->load->model("poiservicesmodel");

			$description = urldecode($description);
			$alias = urldecode($user_alias);
			$password = urldecode($password);

			if($this->poiservicesmodel->login($alias, $password)){
					
				$ok = $this->poimodel->checkin($layer_id, $poi_id, $alias, $description);
				if($ok){
					$data = array("message" => "ok");
					$this->load->view("api_messages", $data);
				}else{
					$data = array("message" => "No se ha podido hacer checkin " .$this->db->_error_message());
					$this->load->view("api_errors", $data );
				}
					
			}else{
				$data = array("message" => "No se ha podido hacer checkin porque la password ".$password." no es del usuario ".$alias);
				$this->load->view("api_errors", $data );
			}

		}catch(Exception $e){
			$data = array("message" => "Se ha producido una excepción al hacer el checkin ");
			$this->load->view("api_errors", $data );
		}
	}

	public function getcheckinsbypoi($layer_id, $poi_id){;
	try{
		$this->load->model("poimodel");
		$checkins = $this->poimodel->getCheckinsByPoi($layer_id, $poi_id);

		if($checkins){
			$data = array("checkins" => $checkins);
			$this->load->view("checkins_list", $data);
		}else{
			$data = array("message" => "No se han podido recuperar los checkins de ".$layer_id." ".$poi_id ." ".$this->db->_error_message());
			$this->load->view("api_errors", $data );
		}

	}catch(Exception $e){
		$data = array("message" => "Se ha producido una excepción al obtener los checkins del poi ".$poi_id." de la capa ".$layer_id);
		$this->load->view("api_errors", $data );
	}
	}

	public function getcheckinsbyuser($alias){
		try{
			$this->load->model("poimodel");
			$alias = urldecode($alias);
			$checkins = $this->poimodel->getCheckinsByUser($alias);

			if($checkins){
				$data = array("checkins" => $checkins);
				$this->load->view("checkins_list", $data);
			}else{
				$data = array("message" => "No se han podido recuperar los checkins de ".$alias." ".$this->db->_error_message());
				$this->load->view("api_errors", $data );
			}

		}catch(Exception $e){
			$data = array("message" => "Se ha producido una excepción al obtener los checkins del poi ".$poi_id." de la capa ".$layer_id);
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
					$data = array("message" => "El kml ".$kmlUrl." no estaba accesible (404)");
					$this->load->view("api_errors", $data );
				}catch(NotKmlException $notKml){
					$data = array("message" => "El kml ".$kmlUrl." no es valido");
					$this->load->view("api_errors", $data );
				}catch(KmlWithoutCoordinatesException $withoutCoords){
					$data = array("message" => "El kml ".$kmlUrl." no tiene coordenadas");
					$this->load->view("api_errors", $data );
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


	public function createPoiLayer($layerName, $geometryColumn){
		try{
			$this->load->model("poimodel");
			if($this->poimodel->createPoiTable($layerName, $geometryColumn)){
				$this->load->model('poiservicesmodel');
				if($this->poiservicesmodel->inserPoiService($layerName, $geometryColumn)){
					$data = array("message" => "Creada layer ".$layerName);
					$this->load->view("api_messages", $data);
				}else{
					$e = $this->db->_error_message();
					$data = array("message" => "Se ha producido una excepción al insertar ".$layerName." en la tabla de metadatos: ".$e);
					$this->load->view("api_errors", $data );
				}

			}else {
				$e = $this->db->_error_message();
				$data = array("message" => "Se ha producido una excepción al crear la tabla ".$layerName." ".$e);
				$this->load->view("api_errors", $data );
			}

		}catch(PoiTableCreationException $e){
			$data = array("message" => "Se ha producido una excepción al crear la tabla ".$layerName." ".$e);
			$this->load->view("api_errors", $data );
		}
	}


	public function listsecurityquestions(){
		try{
			$this->load->model("poiservicesmodel");
			$securityQuestions = $this->poiservicesmodel->listSecurityQuestions();

			$data = array("questions" => $securityQuestions);
			$this->load->view("security_questions", $data );

		}catch(Exception $e){
			$data = array("message" => "Se ha producido una excepción al crear la tabla ".$layerName." ".$e);
			$this->load->view("api_errors", $data );
		}
	}

	public function addsecurityquestion($en_question = "", $es_question = ""){
		try{
			$this->load->model("poiservicesmodel");

			$en_question = urldecode($en_question);
			$es_question = urldecode($es_question);

			$ok = $this->poiservicesmodel->addSecurityQuestion($en_question, $es_question);

			if($ok){
				$data = array("message" => "ok");
				$this->load->view("api_messages", $data);
			}else{
				$data = array("message" => "No se ha conseguido añadir la pregunta secreta ". $this->db->_error_message());
				$this->load->view("api_errors", $data );
			}


		}catch(Exception $e){
			$data = array("message" => "Se ha producido una excepción al añadir la pregunta secreta ".$es_question);
			$this->load->view("api_errors", $data );
		}
	}

	public function insertuser($securityQuestionCode, $alias, $password, $securityAnswer){
		try{
			$this->load->model("poiservicesmodel");


			$alias = urldecode($alias);
			$password = urldecode($password);
			$securityAnswer = urldecode($securityAnswer);


			$ok = $this->poiservicesmodel->insertUser($alias, $password, $securityAnswer, $securityQuestionCode);

			if($ok){
				$data = array("message" => "ok");
				$this->load->view("api_messages", array("message"=>$data));
			}else{
				$data = array("message" => "No se ha conseguido añadir el usuario ". $alias . " ". $this->db->_error_message());
				$this->load->view("api_errors", array("message"=>$data) );
			}
		}catch(Exception $e){
			$data = array("message" => "Se ha producido una excepción al añadir el usuario ".$alias. " ".$this->db->_error_message());
			$this->load->view("api_errors", array("message"=>$data) );
		}
	}

	public function userexist($alias){
		try{
			$this->load->model("poiservicesmodel");


			$alias = urldecode($alias);

			$exist = $this->poiservicesmodel->existUser($alias);

			$data = array("message" => $exist);
			$this->load->view("api_messages", array("message"=>$data));

		}catch(Exception $e){
			$data = array("message" => "Se ha producido una excepción al verificar el usuario ".$alias. " ".$this->db->_error_message());
			$this->load->view("api_errors", array("message"=>$data) );
		}
	}
	
	
	/**
	 * Llamadas al API de administración con interfaz de usuario.
	 * 
	 * Mover a otro Controller
	 * */
	
	
	public function admin(){
		$this->load->model("poiservicesmodel");
		$poiServices = $this->poiservicesmodel->listPoiServices();
		$data = array("poi_layers" => $poiServices);
		$this->load->view("admin", $data);
	}

}