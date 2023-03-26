<?php
class ModelExtensionModuleGenpricechart extends Model {
	
	public function install() {
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "genProductPrice` (`id` int(11) NOT NULL auto_increment, `product_id` int(11) NOT NULL, `price` int(11) NOT NULL, `date_modified` datetime default NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");			

}
	public function addPrice_Module($product_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "genProductPrice SET product_id = '" . (int)$product_id . "',price = '" . (float)$data['price'] . "', date_modified = NOW()");
	}
}
?>