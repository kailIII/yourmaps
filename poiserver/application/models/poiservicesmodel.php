<?php
class Poiservicesmodel extends CI_Model{
	
	public function __construct()
    {
        parent::__construct();
        $this->load->database();
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
   
   public function inserPoiService($tableName, $geometryColumnName, $serviceName = ''){
   		$data = array(
   			'table_name' => $tableName,
   			'geometry_column_name' => $geometryColumnName,
   			'service_name' => $serviceName
   		);
		
		$this->db->insert('POI_SERVICES', $data); 
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
	
}