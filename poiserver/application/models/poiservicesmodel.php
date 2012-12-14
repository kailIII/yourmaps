<?php

include_once $_SERVER["DOCUMENT_ROOT"]."php_scripts/exceptions/PoiTableCreationException.php";
include_once $_SERVER["DOCUMENT_ROOT"]."php_scripts/exceptions/TableCreationException.php";

class Poiservicesmodel extends CI_Model{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}


	/*
	 SCHEMA CREATION CODE
	 * */

	public function createPoiServerDatabase(){
		$sql = "CREATE DATABASE IF NOT EXISTS `poiserver` /*!40100 DEFAULT CHARACTER SET utf8 */;";
		return $this->db->query($sql);
	}


	public function createPoiServiceTable(){
		$sql = "CREATE TABLE `POI_SERVICES` ( ".
			"`id` INT(10) NOT NULL AUTO_INCREMENT, ".
			"`table_name` TINYTEXT NULL, ".
			"`geometry_column_name` TINYTEXT NULL, ".
			"`service_name` TINYTEXT NULL, ".
			"PRIMARY KEY (`id`)) ".
			"COLLATE='utf8_general_ci' ENGINE=MyISAM ROW_FORMAT=DEFAULT";
		if(! $this->db->query($sql)){
			throw new PoiTableCreationException("No se ha podido crear la tabla POI_SERVICES");
		}
		return true;
	}
	 
	 
	public function createUserTable(){
		$sql = "CREATE TABLE `users` (`id` INT(10) NULL AUTO_INCREMENT,	".
			"`alias` TINYTEXT NULL DEFAULT NULL, `password` TINYTEXT NULL DEFAULT NULL, ".
			"`security_answer` TINYTEXT NULL DEFAULT NULL, 	`security_question_code` INT NULL ".	
			"UNIQUE INDEX `alias` (`alias`(250)), PRIMARY KEY (`id`)) COLLATE='utf8_general_ci' ENGINE=MyISAM ROW_FORMAT=DEFAULT;";

		if(! $this->db->query($sql)){
			throw new TableCreationException("No se ha podido crear la tabla 'users'");
		}
		return true;
	}
	 
	 
	public function createSecurityQuestionsTable(){
		$sql = "CREATE TABLE `security_questions` (	`id` INT(10) NULL AUTO_INCREMENT, ".
			"`en_question` TEXT NULL DEFAULT NULL, `es_question` TEXT NULL DEFAULT NULL, ".
			" PRIMARY KEY (`id`)) COLLATE='utf8_general_ci' ENGINE=MyISAM ROW_FORMAT=DEFAULT";
		if(! $this->db->query($sql)){
			throw new TableCreationException("No se ha podido crear la tabla 'security_answers'");
		}
		return true;
	}
	 
	 
	/*
	 insert and select code
	 * */
	 
	 
	public function inserPoiService($tableName, $geometryColumnName, $serviceName = ''){
		$data = array(
   			'table_name' => $tableName,
   			'geometry_column_name' => $geometryColumnName,
   			'service_name' => $serviceName
		);

		return $this->db->insert('POI_SERVICES', $data);
	}


	public function getPoiService($id){
		$data = array();
		$query = $this->db->get_where("POI_SERVICES", array("id" => $id), 1);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}
		$query->free_result();
		return $data;
	}
	 
	public function listPoiServices(){
		$query = $this->db->get('POI_SERVICES');
	 return $query->result();
	}
	
	
	
/*
 * Users
 */
	 
	/**
	 * Insert a new user in users database
	 *
	 * @param string $alias
	 * @param string $password
	 * @param string $securityAnswer
	 */
	public function insertUser($alias, $password, $securityAnswer, $securityQuestionCode){
		if($this->existUser($alias))
			return false;
				
		$data = array(
   			'alias' => $alias,
   			'password' => $password,
   			'security_question_code' => $securityQuestionCode,
   			'security_answer' => $securityAnswer
		);

		return $this->db->insert('users', $data);
	}
	 
	 
	 
	public function existUser($alias){
		$this->db->select('id');
		$this->db->where("alias", $alias);
		$query = $this->db->get("users");
		return $query->num_rows() > 0;
	}
	 
	public function login($alias, $password){
		$this->db->select('id');
		$this->db->where("alias", $alias);
		$this->db->where("password", $password);
		$query = $this->db->get("users");
		return $query->num_rows() > 0;
	}
	 
	 
	public function resetPassword($securityQuestionCode, $securityAnswer, $newPassword){
		$data = array(
               'password' => $newPassword,
		);
		$this->db->where('security_question_code', $securityQuestionCode);
		$this->db->where('security_answer', $securityAnswer);
		$this->db->update('users', $data);
		
		return $this->db->affected_rows() > 0;
	}
	 

/*
 Security Questions
 * */
	
	
	
	public function listSecurityQuestions(){
		$query = $this->db->get('security_questions');
	 	return $query->result();
	}
	
	
	public function addSecurityQuestion($en_question = '', $es_question = ''){
		
		if($this->existUserQuestion($en_question, $es_question) )
			return false;
			
		
		$data = array(
   			'en_question' => $en_question,
   			'es_question' => $es_question
		);

		return $this->db->insert('security_questions', $data);
	}
	
	public function existUserQuestion($en_question = '', $es_question = ''){
		$this->db->select('id');
		$this->db->where('en_question', $en_question);
		$this->db->where('es_question', $es_question);
		$query = $this->db->get("security_questions");
		return $query->num_rows() > 0;
	}

}