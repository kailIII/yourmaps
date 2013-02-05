<?php

include_once $_SERVER["DOCUMENT_ROOT"]."php_scripts/exceptions/PoiTableCreationException.php";
include_once $_SERVER["DOCUMENT_ROOT"]."php_scripts/exceptions/TableCreationException.php";

//include_once $_SERVER["DOCUMENT_ROOT"]."/poiserver/php_scripts/exceptions/PoiTableCreationException.php";
//include_once $_SERVER["DOCUMENT_ROOT"]."/poiserver/php_scripts/exceptions/TableCreationException.php";


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
	
	//TODO
	public function createLayerTables($table_name, $geometryColumn = 'geom'){
		
		//pois
		$sql = "CREATE TABLE `".$table_name."` (".
				"`id` INT(10) NOT NULL AUTO_INCREMENT,".
				"`name` TEXT NULL,".
				"`description` LONGTEXT NULL,".
				"`".$geometryColumn."` GEOMETRY NOT NULL,".
				"`source` TEXT  NULL COMMENT 'Data source (looking for maps, added by user, etc)',".
				"`url_source` TEXT NULL COMMENT 'URL of the data source',".
				"`photo_url` TEXT NULL,".
				"`WEB_URL` TEXT NULL,".
				"PRIMARY KEY (`id`), ".
				"FULLTEXT INDEX `name_description` (`name`, `description`) 	) ".
				"COLLATE='utf8_general_ci'".
				"ENGINE=MyISAM ".
				"ROW_FORMAT=DEFAULT ".
				"AUTO_INCREMENT=0 ";
		if(! $this->db->query($sql)){
			throw new PoiTableCreationException("No se ha podido crear la tabla $table_name");
		}
		
		//checkins
		$checkinSql = 'CREATE TABLE `'.$table_name.'_CHECKIN` ('.
					'`id` INT(10) NOT NULL AUTO_INCREMENT,'.
					'`layer_id` INT(10) NOT NULL,'.
					'`poi_id` INT(10) NOT NULL,'.
					'`check_time` DATETIME NOT NULL,'.
					'`user_alias` TEXT NOT NULL,'.
					'`description` TEXT NOT NULL,'.
					'PRIMARY KEY (`id`)) '.
					'COLLATE=\'utf8_general_ci\' '.
					'ENGINE=MyISAM '.
					'ROW_FORMAT=DEFAULT AUTO_INCREMENT=0';
		if(! $this->db->query($checkinSql)){
			throw new PoiTableCreationException("No se ha podido crear la tabla $table_name"."_CHECKINS");
		}
		
		//chat
		$chatSql = 'CREATE TABLE `'.$table_name.'_CHAT` ('.
					'`id` INT(10) NOT NULL AUTO_INCREMENT,'.
					'`layer_id` INT(10) NOT NULL,'.
					'`check_time` DATETIME NOT NULL,'.
					'`geom` GEOMETRY NOT NULL,'.
					'`user_alias` TEXT NOT NULL,'.
					'`text_msg` TEXT NOT NULL,'.
					'PRIMARY KEY (`id`) ) '.
					'COLLATE=\'utf8_general_ci\''.
					'ENGINE=MyISAM '.
					'ROW_FORMAT=DEFAULT';
		
		if(! $this->db->query($chatSql)){
			throw new PoiTableCreationException("No se ha podido crear la tabla $table_name"."_CHECKINS");
		}
		
		
		
		
		return true;
	}
	 
	 
	public function createUserTable(){
		$sql = "CREATE TABLE `users` (`id` INT(10) NULL AUTO_INCREMENT,	".
			"`alias` TINYTEXT NULL DEFAULT NULL, `password` TINYTEXT NULL DEFAULT NULL, ".
			"`security_answer` TINYTEXT NULL DEFAULT NULL, 	`security_question_code` INT NULL ".
			"`poi_service_fk` INT(10) NOT NULL,".	
			"UNIQUE INDEX `alias` (`alias`(300)), PRIMARY KEY (`id`,`poi_service_fk')) COLLATE='utf8_general_ci' ENGINE=MyISAM ROW_FORMAT=DEFAULT;";

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
	 
	 
	public function inserPoiService($tableName, $geometryColumnName, 
		 $chat_table_name, $checkin_table_name, $serviceName = ''){
		$data = array(
   			'table_name' => $tableName,
   			'geometry_column_name' => $geometryColumnName,
			'checkin_table_name' => $checkin_table_name,
			'chat_table_name' => $chat_table_name,
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
	public function insertUser($layer_id, $alias, $password, $securityAnswer, $securityQuestionCode){
		if($this->existUser($layer_id, $alias))
			return false;
				
		$data = array(
			'poi_service_fk' => $layer_id,
   			'alias' => $alias,
   			'password' => $password,
   			'security_question_code' => $securityQuestionCode,
   			'security_answer' => $securityAnswer
		);

		return $this->db->insert('users', $data);
	}
	
	
	/**
	 * updates all user params, except 'alias'
	 * 
	 * question: is enought old password, or we must ask security questions
	 * 
	 * */
	public function updateuser($layer_id,  $alias, $password, $newPassword = '', $securityAnswer, $securityQuestionCode){
		$data = array(
               'security_question_code' => $securityQuestionCode,
               'security_answer' => $securityAnswer
        );
        
        if($newPassword != '')
        {
        	$data['password'] = $newPassword;
        }

		$this->db->where('poi_service_fk', $layer_id);
		$this->db->where('password', $password);
		
		
		$this->db->update('users', $data); 
		
		return $this->db->affected_rows() > 0;
	}
	 
	 
	 
	public function existUser($layer_id, $alias){
		$this->db->select('id');
		$this->db->where("alias", $alias);
		$this->db->where("poi_service_fk", $layer_id);
		$query = $this->db->get("users");
		return $query->num_rows() > 0;
	}
	
	
	
	public function createorupdateuser($layer_id, $alias, $password, $securityAnswer, $securityQuestionCode, $newPassword = '' ){
		if($this->existUser($layer_id, $alias)){
			return $this->updateuser($layer_id, $alias, $password, $newPassword, $securityAnswer, $securityQuestionCode);
		}else{
			return $this->insertUser($layer_id, $alias, $password, $securityAnswer, $securityQuestionCode);
		}
	}
	 
	public function login($layer_id, $alias, $password){
		$this->db->select('id');
		$this->db->where("poi_service_fk", $layer_id);
		$this->db->where("alias", $alias);
		$this->db->where("password", $password);
		$query = $this->db->get("users");
		return $query->num_rows() > 0;
	}
	 
	 
	public function resetPassword($alias, $securityQuestionCode, $securityAnswer, $newPassword){
		$data = array(
               'password' => $newPassword,
		);
		$this->db->where('alias',$alias);
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