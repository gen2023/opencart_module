<?php
class ModelExtensionModuleCountUser extends Model {
	public function getUser(){
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gen_count_user`");	
		
		return $query->rows;
	}
	public function addUser($current_user,$time){
		$this->db->query("INSERT INTO " . DB_PREFIX . "gen_count_user SET ip_user = '" . $this->db->escape($current_user) . "', time = '" . (int)$time . "', count=1, countNow=1 ");
	}
	
	public function updateUser($user_id,$count,$time,$countNow){
		$this->db->query("UPDATE `oc_gen_count_user` SET `count`='" . (int)$count . "', `countNow`='" . (int)$countNow . "', `time`='" . (int)$time . "' WHERE `user_id`='" . (int)$user_id . "'");
		
	}
		
}