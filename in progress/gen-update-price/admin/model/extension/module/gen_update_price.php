<?php
class ModelExtensionModuleGenUpdatePrice extends Model {
	
	public function install() {
		$this->db->query("ALTER TABLE " . DB_PREFIX . "product ADD currentPrice decimal(15,4)");
		$this->db->query("ALTER TABLE " . DB_PREFIX . "product_special ADD currentSpecialPrice decimal(15,4)");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gen_history_price` (`id` int(11) NOT NULL auto_increment, `info` LONGTEXT COLLATE utf8_general_ci default NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");

		$this->updatePrice();
	}
	public function uninstall() {
		$data=$this->resetPrice();
		
		$this->db->query("DROP TABLE " . DB_PREFIX . "gen_history_price");
		$this->db->query("ALTER TABLE " . DB_PREFIX . "product DROP COLUMN currentPrice");
		$this->db->query("ALTER TABLE " . DB_PREFIX . "product_special DROP COLUMN currentSpecialPrice");
	}

	public function getProductsPrice() {
		$query = $this->db->query("SELECT `product_id`,`price`,`currentPrice` FROM `" . DB_PREFIX . "product`");

		return $query->rows;
	}
	public function getProductsSpecialPrice() {
		$query = $this->db->query("SELECT `product_special_id`,`price`,`currentSpecialPrice` FROM `" . DB_PREFIX . "product_special`");

		return $query->rows;
	}

	public function getProductsPriceById($product_id) {
		$query = $this->db->query("SELECT `price` FROM `" . DB_PREFIX . "product` WHERE product_id = '" . (int)$product_id . "'");

		return $query->row;
	}

	public function getProductsSpecialPriceById($product_id) {
		$query = $this->db->query("SELECT `price` FROM `" . DB_PREFIX . "product_special` WHERE product_id = '" . (int)$product_id . "'");

		return $query->row;
	}

	public function applyPrice($data) {
		$count=0;
		$oldPriceArr=array();
		$newPriceArr=array();

		foreach ($data['checkedProducts'] as $product_id){
				$resultPrice = $this->getProductsPriceById($product_id);
				
				$price=$resultPrice['price'];
				if ($data['action2']=='persent'){
					switch ($data['action']) {
						case '+':
							$newPrice=(float)$price+(float)$price*((float)$data['number']/100);
							break;
						case '-':
							$newPrice=(float)$price-(float)$price*((float)$data['number']/100);
							break;
						case '/':
							$newPrice=(float)$price/(float)$price*((float)$data['number']/100);
							break;
						case '*':
							$newPrice=(float)$price*(float)$price*((float)$data['number']/100);
							break;
					}
				}else{
					switch ($data['action']) {
						case '+':
							$newPrice=(float)$price+(float)$data['number'];
							break;
						case '-':
							$newPrice=(float)$price-(float)$data['number'];
							break;
						case '/':
							$newPrice=(float)$price/(float)$data['number'];
							break;
						case '*':
							$newPrice=(float)$price*(float)$data['number'];
							break;
					}
				}
				$oldPriceArr[$count]=(float)$price;
				$newPriceArr[$count]=(float)$newPrice;
			$this->db->query("UPDATE " . DB_PREFIX . "product SET price = '" . (float)$newPrice . "' WHERE product_id = '" . (int)$product_id . "'");
			
			$count++;
		}
		$data['oldPrice']=$oldPriceArr;
		$data['newPrice']=$newPriceArr;		
		$this->addHistory($data);
		return $count;
	}

	public function applySpecialPrice($data) {
		$count=0;
		$oldPriceArr=array();
		$newPriceArr=array();
		foreach ($data['checkedProducts'] as $product_id){
				$resultPrice = $this->getProductsSpecialPriceById($product_id);
				
				$price=$resultPrice['price'];
				if ($data['action2']=='persent'){
					switch ($data['action']) {
						case '+':
							$newPrice=(float)$price+(float)$price*((float)$data['number']/100);
							break;
						case '-':
							$newPrice=(float)$price-(float)$price*((float)$data['number']/100);
							break;
						case '/':
							$newPrice=(float)$price/(float)$price*((float)$data['number']/100);
							break;
						case '*':
							$newPrice=(float)$price*(float)$price*((float)$data['number']/100);
							break;
					}
				}else{
					switch ($data['action']) {
						case '+':
							$newPrice=(float)$price+(float)$data['number'];
							break;
						case '-':
							$newPrice=(float)$price-(float)$data['number'];
							break;
						case '/':
							$newPrice=(float)$price/(float)$data['number'];
							break;
						case '*':
							$newPrice=(float)$price*(float)$data['number'];
							break;
					}
				}
				$oldPriceArr[$count]=(float)$price;
				$newPriceArr[$count]=(float)$newPrice;
			$this->db->query("UPDATE " . DB_PREFIX . "product_special SET price = '" . (float)$newPrice . "' WHERE product_id = '" . (int)$product_id . "'");
			$count++;
		}
		$data['oldPrice']=$oldPriceArr;
		$data['newPrice']=$newPriceArr;
		$this->addHistory($data);
	return $count;
	}

	public function resetPrice() {
		$price=$this->getProductsPrice();
		
		foreach ($price as $result) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET price = '" . (float)$result['currentPrice'] . "' WHERE product_id = '" . (int)$result['product_id'] . "'");
		}
		$specialPrice=$this->getProductsSpecialPrice();
		
		foreach ($specialPrice as $result) {
			$this->db->query("UPDATE " . DB_PREFIX . "product_special SET price = '" . (float)$result['currentSpecialPrice'] . "' WHERE product_special_id = '" . (int)$result['product_special_id'] . "'");
		}
		$this->deleteHistory();
	}
	public function updatePrice() {
	
		$price = $this->getProductsPrice();
		foreach ($price as $result) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET currentPrice = '" . (float)$result['price'] . "' WHERE product_id = '" . (int)$result['product_id'] . "'");
		}
		$specialPrice = $this->getProductsSpecialPrice();
		foreach ($specialPrice as $result) {
			$this->db->query("UPDATE " . DB_PREFIX . "product_special SET currentSpecialPrice = '" . (float)$result['price'] . "' WHERE	product_special_id = '" . (int)$result['product_special_id'] . "'");
		}	
	}
	public function getHistory() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gen_history_price`");
		return $query->rows;
	}
	public function addHistory($data) {
		$date=date("Y-m-d H:i:s");
		$typePrice=$data['typePrice'];
		$product_id=implode(",", $data['checkedProducts']);
		$price_old=implode(",",$data['oldPrice']);
		$price_new=implode(",",$data['newPrice']);
		$info=$date . ';' . $typePrice . ';' . $product_id . ';' . $price_old . ';' . $price_new;
		$this->db->query("INSERT INTO " . DB_PREFIX . "gen_history_price SET info='" . $this->db->escape($info) . "'");
	}
	public function deleteHistory() {
		$this->db->query("TRUNCATE TABLE " . DB_PREFIX . "gen_history_price");
	}
	

}
?>