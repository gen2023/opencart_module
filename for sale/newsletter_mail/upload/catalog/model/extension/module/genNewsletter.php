<?php
class ModelExtensionModuleGenNewsletter extends Model
{

	public function subscribes($required_field, $data)
	{
		if ($required_field == 0) {
			$res = $this->db->query("select * from " . DB_PREFIX . "gen_newsletter where email='" . $data['email'] . "'");
			if ($res->num_rows == 1) {
				return 2;
			}
		} else if ($required_field == 1) {
			$res = $this->db->query("select * from " . DB_PREFIX . "gen_newsletter where phone='" . $data['phone'] . "'");
			if ($res->num_rows == 1) {
				return 3;
			}
		}

		if (!isset($data['email'])) {
			$data['email'] = '';
		}
		if (!isset($data['name'])) {
			$data['name'] = '';
		}
		if (!isset($data['phone'])) {
			$data['phone'] = '';
		}
		if (!isset($data['town'])) {
			$data['town'] = '';
		}

		$this->db->query("INSERT INTO " . DB_PREFIX . "gen_newsletter set email='" . $data['email'] . "', name= '" . $data['name'] . "', phone= '" . $data['phone'] . "', town='" . $data['town'] . "'");
		return 1;

	}

	public function unsubscribes($data)
	{
		$res = $this->db->query("select * from " . DB_PREFIX . "gen_newsletter where email='" . $data['email'] . "'");

		if ($res->num_rows == 1) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "gen_newsletter WHERE email = '" . $data['email'] . "'");
			return 1;
		} else {
			return 0;
		}

	}

	public function is_subscribes($required_field, $data)
	{
		if ($required_field == 0) {
			$res = $this->db->query("select * from " . DB_PREFIX . "gen_newsletter where email='" . $data['email'] . "'");
			if ($res->num_rows == 1) {
				return 2;
			}
		} else if ($required_field == 1) {
			$res = $this->db->query("select * from " . DB_PREFIX . "gen_newsletter where phone='" . $data['phone'] . "'");
			if ($res->num_rows == 1) {
				return 3;
			}
		}

		return 1;

	}
	public function getTextForMail()
	{
		$query = $this->db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE store_id = 0 AND `code` = 'module_genNewsletter' AND `key` = 'module_genNewsletter_settings'");

		return $query->row;

	}

	public function addCoupon($email)
	{
		$code = 'module_genNewsletter';
		$store_id = 0;
		$setting_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int) $store_id . "' AND `code` = '" . $this->db->escape($code) . "'");

		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$setting_data[$result['key']] = $result['value'];
			} else {
				$setting_data[$result['key']] = json_decode($result['value'], true);
			}
		}
		function generateRandomString($length = 6)
		{
			return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
		}

		$setting_data['code'] = generateRandomString();
		$this->session->data['genNewsletter'] = $setting_data['code'];

		$setting_data['name'] = 'Coupon for user: ' . $email;
		$setting_data['date_end'] = date("y.m.d", strtotime("+" . (int) $setting_data['module_genNewsletter_uses_count_day'] . " days"));

		$this->db->query("INSERT INTO " . DB_PREFIX . "coupon SET name = '" . $this->db->escape($setting_data['name']) . "', code = '" . $this->db->escape($setting_data['code']) . "', discount = '" . (float) $setting_data['module_couponreguser_discount'] . "', type = '" . $this->db->escape($setting_data['module_couponreguser_type']) . "', total = '" . (float) $setting_data['module_couponreguser_total'] . "', logged = '" . (int) $setting_data['module_couponreguser_logged'] . "', shipping = '" . (int) $setting_data['module_couponreguser_shipping'] . "', date_start = NOW(), date_end = '" . $this->db->escape($setting_data['date_end']) . "', uses_total = '" . (int) $setting_data['module_couponreguser_uses_total'] . "', uses_customer = '" . $this->db->escape($setting_data['module_couponreguser_uses_customer']) . "', status = '" . (int) $setting_data['module_couponreguser_status'] . "', date_added = NOW()");

	}

}