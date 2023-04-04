<?php
class ModelCommonNewsletter extends Model {
	
	public function createNewsletter()
	{
			
		$res0 = $this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."gen_newsletter'");
		if($res0->num_rows == 0){
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `".DB_PREFIX."gen_newsletter` (
				  `news_id` int(11) NOT NULL AUTO_INCREMENT,
				  `news_email` varchar(255) NOT NULL,
				  `news_name` varchar(255) NOT NULL,
				  `news_town` varchar(255) NOT NULL,
				  PRIMARY KEY (`news_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
			");
		}
		
		
	}
	
	public function getNewsLetter() {
		$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."gen_newsletter"); 

		return $query->rows;
	}
	
	public function getEmail() {
		$query = $this->db->query("SELECT news_email FROM ". DB_PREFIX ."gen_newsletter"); 

		return $query->rows;
	}
	
	public function deleteNewsletter($news_id) { 

		$this->db->query("DELETE FROM " . DB_PREFIX . "gen_newsletter WHERE news_id = '" . (int)$news_id . "'");
			
		$this->cache->delete('gen_newsletter');
	}
	
	public function getTown() {
		$query = $this->db->query("SELECT DISTINCT news_town FROM ". DB_PREFIX ."gen_newsletter"); 

		return $query->rows;
	}
	
		public function getEmailTown($town) {
		$query = $this->db->query("SELECT news_email FROM ". DB_PREFIX ."gen_newsletter WHERE news_town = '" . $town . "'"); 

		return $query->rows;
	}
}