<?php
class ModelExtensionModuleCountUser extends Model {
	public function getUserAllPage(){
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gen_count_user` WHERE `allPage`=1");	
		
		return $query->rows;
	}
	public function addUserAllPage($current_user,$time){
		$this->db->query("INSERT INTO " . DB_PREFIX . "gen_count_user SET ip_user = '" . $this->db->escape($current_user) . "', time = '" . (int)$time . "', allPage=1");
	}
	
	public function updateUser($user_id,$time){
		$this->db->query("UPDATE `oc_gen_count_user` SET `time`='" . (int)$time . "' WHERE `user_id`='" . (int)$user_id . "'");
		
	}
	
	public function getUserNews($news_id){
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gen_count_user` WHERE `news_id`='".$news_id."'");	
		
		return $query->rows;
	}
	
	public function addUserNews($current_user,$time,$news_id){
		$this->db->query("INSERT INTO " . DB_PREFIX . "gen_count_user SET ip_user = '" . $this->db->escape($current_user) . "', time = '" . (int)$time . "', news_id='".$news_id."'");
	}
	public function getUserManufacturer($manufacturer_id){
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gen_count_user` WHERE `manufacturer_id`='".$manufacturer_id."'");	
		
		return $query->rows;
	}
	
	public function addUserManufacturer($current_user,$time,$manufacturer_id){
		$this->db->query("INSERT INTO " . DB_PREFIX . "gen_count_user SET ip_user = '" . $this->db->escape($current_user) . "', time = '" . (int)$time . "', manufacturer_id='".$manufacturer_id."'");
	}
	
	public function getUserProduct($product_id){
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gen_count_user` WHERE `product_id`='".$product_id."'");	
		
		return $query->rows;
	}
	
	public function addUserProduct($current_user,$time,$product_id){
		$this->db->query("INSERT INTO " . DB_PREFIX . "gen_count_user SET ip_user = '" . $this->db->escape($current_user) . "', time = '" . (int)$time . "', product_id='".$product_id."'");
	}
		
}