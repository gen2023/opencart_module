<?php
class ModelExtensionModuleCouponreguser extends Model {
	public function addCoupon($email) {
        $code='module_couponreguser';
        $store_id=0;
		$setting_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "'");

		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$setting_data[$result['key']] = $result['value'];
			} else {
				$setting_data[$result['key']] = json_decode($result['value'], true);
			}
		}
        //var_dump($setting_data);

        function generateRandomString($length = 6) {
            return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
        }
        
        //echo  generateRandomString();

        $setting_data['code']=generateRandomString();
		$this->session->data['Couponreguser']=$setting_data['code'];
		
        $setting_data['name']='Coupon for user: '.$email;
        //$setting_data['date_start']=date("y.m.d");
        $setting_data['date_end']=date("y.m.d", strtotime("+".(int)$setting_data['module_couponreguser_uses_count_day']." days"));
        //var_dump($setting_data['name']);

        $this->db->query("INSERT INTO " . DB_PREFIX . "coupon SET name = '" . $this->db->escape($setting_data['name']) . "', code = '" . $this->db->escape($setting_data['code']) . "', discount = '" . (float)$setting_data['module_couponreguser_discount'] . "', type = '" . $this->db->escape($setting_data['module_couponreguser_type']) . "', total = '" . (float)$setting_data['module_couponreguser_total'] . "', logged = '" . (int)$setting_data['module_couponreguser_logged'] . "', shipping = '" . (int)$setting_data['module_couponreguser_shipping'] . "', date_start = NOW(), date_end = '" . $this->db->escape($setting_data['date_end']) . "', uses_total = '" . (int)$setting_data['module_couponreguser_uses_total'] . "', uses_customer = '" . $this->db->escape($setting_data['module_couponreguser_uses_customer']) . "', status = '" . (int)$setting_data['module_couponreguser_status'] . "', date_added = NOW()");

	}
	public function getTextForMail() {
		$query = $this->db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE store_id = 0 AND `code` = 'module_couponreguser' AND `key` = 'module_couponreguser_mail'");	
		
		return $query->row;
		
	}
		
}