<?php
class ModelExtensionModuleGenUpdatePrice extends Model {
	
	public function install() {
		$this->db->query("ALTER TABLE " . DB_PREFIX . "product ADD currentPrice decimal(15,4)");
		
		$data = $this->getProductsPrice();
		foreach ($data as $result) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET currentPrice = '" . (float)$result['price'] . "' WHERE product_id = '" . (int)$result['product_id'] . "'");
		}
		
	}
	public function uninstall() {
		$data=$this->resetPrice();
			
		$this->db->query("ALTER TABLE " . DB_PREFIX . "product DROP COLUMN currentPrice");
	}

	public function getProductsPrice() {
		$query = $this->db->query("SELECT `product_id`,`price`,`currentPrice` FROM `" . DB_PREFIX . "product`");

		return $query->rows;
	}

	public function apply($data) {
	$results = $this->getProductsPrice();
	if ($data['action2']=='persent'){
		foreach ($results as $result) {
			switch ($data['action']) {
				case '+':
					$result['price']=(float)$result['price']+(float)$result['price']*((float)$data['number']/100);
					break;
				case '-':
					$result['price']=(float)$result['price']-(float)$result['price']*((float)$data['number']/100);
					break;
				case '/':
					$result['price']=(float)$result['price']/(float)$result['price']*((float)$data['number']/100);
					break;
				case '*':
					$result['price']=(float)$result['price']*(float)$result['price']*((float)$data['number']/100);
					break;
			}
			
			$this->db->query("UPDATE " . DB_PREFIX . "product SET price = '" . (float)$result['price'] . "' WHERE product_id = '" . (int)$result['product_id'] . "'");
		}
	}else{
		foreach ($results as $result) {
			switch ($data['action']) {
				case '+':
					$result['price']=(float)$result['price']+(float)$data['number'];
					break;
				case '-':
					$result['price']=(float)$result['price']-(float)$data['number'];
					break;
				case '/':
					$result['price']=(float)$result['price']/(float)$data['number'];
					break;
				case '*':
					$result['price']=(float)$result['price']*(float)$data['number'];
					break;
			}
			
			$this->db->query("UPDATE " . DB_PREFIX . "product SET price = '" . (float)$result['price'] . "' WHERE product_id = '" . (int)$result['product_id'] . "'");
		}
	}
		
	return 1;
	}
	public function resetPrice() {
		$data=$this->getProductsPrice();
		
		foreach ($data as $result) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET price = '" . (float)$result['currentPrice'] . "' WHERE product_id = '" . (int)$result['product_id'] . "'");
		}
	}
	
	public function updatePrice($data) {}

	
}
?>