<?php
class PoiServerAdmin extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->helper('langdetection');
	}
	
	
	public function start(){
		$this->load->view("admin/admin-main-page");
	}
	
	public function uploadKml(){
		
	}

	public function loadpois(){
		$this->load->model("poiservicesmodel");
		$poiServices = $this->poiservicesmodel->listPoiServices();
		$data = array("poi_layers" => $poiServices);
		$this->load->view("admin/loadpois", $data);
	}
	
	
	public function createdatabaseschema($admin,$password){
		
	}
}