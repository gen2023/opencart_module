<?php
class ModelExtensionModuleCountUser extends Model {
	
	public function install() {
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gen_count_user` (`user_id` int(11) NOT NULL auto_increment, `allPage` int(11) COLLATE utf8_general_ci default 0, `news_id` int(11) COLLATE utf8_general_ci default 0, `manufacturer_id` int(11) COLLATE utf8_general_ci default 0, `ip_user` VARCHAR(255) COLLATE utf8_general_ci default NULL,`product_id` int(11) COLLATE utf8_general_ci default 0, `time` int(11) COLLATE utf8_general_ci default NULL, PRIMARY KEY (`user_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");			
		
	}

	public function uninstall() { 
		$this->db->query("DROP TABLE `" . DB_PREFIX . "gen_count_user`");
	}
	
	public function getTotal() {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "gen_count_user` WHERE `allPage`=1";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	public function getTotalOnline() {
		$sql = "SELECT * FROM `" . DB_PREFIX . "gen_count_user` WHERE `allPage`=1";
		$query = $this->db->query($sql);

		return $query->rows;
	}

	
}
?>