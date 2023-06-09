<?php
class ModelExtensionFeedGenGoogleXml extends Model {

	public function getProducts() {
		$cat_query = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "product_to_category LIKE 'main_category'");
		if ($cat_query->num_rows){
			$query = $this->db->query("SELECT p.product_id, p.price, p.quantity, p.image, pd.name, pd.description, p2c.category_id, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special  FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE p.status = '1' AND p.price > 0 AND p.date_available <= NOW() AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p2c.main_category = '1'");
		} else {
			$query = $this->db->query("SELECT p.product_id, p.price, p.quantity, p.image, pd.name, pd.description, p2c.category_id, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special  FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE p.status = '1' AND p.price > 0 AND p.date_available <= NOW() AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY p.product_id");
		}

		return $query->rows;
	}

	public function getCategorie($product_id) {
		$query = $this->db->query("SELECT `category_id` FROM `" . DB_PREFIX . "product_to_category` WHERE `product_id`='".$product_id."'");
		$category_id=$query->row['category_id'];
		$query = $this->db->query("SELECT `google_category_id` FROM `" . DB_PREFIX . "gen_google_xml_category` WHERE `category_id`='".$category_id."'");
		
		return $query->row['google_category_id'];
	}
	
	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

/*	public function getOption($product_id,$name) {
		$query_Option_id = $this->db->query("SELECT `option_id` FROM `" . DB_PREFIX . "option_description` WHERE `name`='" . $name . "'");
		$option_id=$query_Option_id->row['option_id']; // id size /id option

		$query_option_value=$this->db->query("SELECT `option_value_id` FROM `" . DB_PREFIX . "product_option_value` WHERE `product_id`='" . (int)$product_id . "'  AND `option_id`='" . $option_id . "'"); //опции у продукта по цвету или размеру

		$result=array();
		foreach ($query_option_value->rows as $id){
			$query_result=$this->db->query("SELECT `name` FROM `" . DB_PREFIX . "option_value_description` WHERE `option_value_id`='". (int)$id['option_value_id'] ."'");
			//var_dump($query_result->num_rows);
			if ($query_result->num_rows) {
				array_push($result, $query_result->row['name']);
			}
			
		}
		//var_dump($result);
//$result=[1,2];
		return $result;
	}*/

}