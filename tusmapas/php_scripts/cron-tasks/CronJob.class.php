<?php
/**
 sql creation code:
 
 CREATE TABLE `CRON_JOBS` (
	`pk_id` INT(10) NOT NULL AUTO_INCREMENT,
	`job_type` VARCHAR(50) NULL DEFAULT NULL COLLATE 'latin1_spanish_ci',
	`date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`pk_id`)
)
COLLATE='latin1_spanish_ci'
ENGINE=MyISAM
ROW_FORMAT=DEFAULT
AUTO_INCREMENT=2


 */

class CronJob {
	
	protected $gid;
	
	public function setGid($id){
		$this->gid = $id;
	}
	
	public function getGid(){
		return $this->gid;
	}
	
	protected $jobType;
	
	public function setJobType($jobT){
		$this->jobType = $jobT;
	}
	
	public function getJobType(){
		return $this->jobType;
	}
	
	
	
	
	public function __construct($job){
		
		$this->jobType = $job;
	
								 
		$this->gid = -1;						 
	}
	
	
	public function save($pdo){
			$sql = "INSERT INTO CRON_JOBS (job_type) VALUES(:jobtype)";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':jobtype', $this->jobType);
			$success = $stmt->execute();
	}
	
}