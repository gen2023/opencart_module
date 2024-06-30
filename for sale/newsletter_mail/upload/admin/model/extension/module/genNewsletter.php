<?php
class ModelExtensionModuleGenNewsletter extends Model {
	public function install() {
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gen_newsletter` (`newsletter_id` int(11) NOT NULL auto_increment, `email` VARCHAR(255) COLLATE utf8_general_ci default NULL, `name` VARCHAR(255) COLLATE utf8_general_ci default NULL,`town` VARCHAR(255) COLLATE utf8_general_ci default NULL,`phone` VARCHAR(255) COLLATE utf8_general_ci default NULL, PRIMARY KEY (`newsletter_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");			
	}

	public function uninstall() { 
		$this->db->query("DROP TABLE `" . DB_PREFIX . "gen_newsletter`");
	}
	
	public function getNewsLetter() {
		$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."gen_newsletter"); 

		return $query->rows;
	}
	
	public function getEmail() {
		$query = $this->db->query("SELECT email FROM ". DB_PREFIX ."gen_newsletter"); 

		return $query->rows;
	}
	
	public function deleteNewsletter($newsletter_id) { 

		$this->db->query("DELETE FROM " . DB_PREFIX . "gen_newsletter WHERE newsletter_id = '" . (int)$newsletter_id . "'");
			
		$this->cache->delete('gen_newsletter');
	}
	
	public function getTown() {
		$query = $this->db->query("SELECT DISTINCT town FROM ". DB_PREFIX ."gen_newsletter"); 

		return $query->rows;
	}
	
		public function getEmailTown($town) {
		$query = $this->db->query("SELECT email FROM ". DB_PREFIX ."gen_newsletter WHERE town = '" . $town . "'"); 

		return $query->rows;
	}
}