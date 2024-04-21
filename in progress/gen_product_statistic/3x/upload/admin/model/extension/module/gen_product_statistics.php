<?php
class ModelExtensionModuleGenProductStatistics extends Model
{

	public function getProductFields()
	{

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product LIMIT 1");

		return $query->row;
	}

	// public function editTestimonial($testimonial_id, $data)
	// {
	// 	//var_dump($data);
	// 	$this->db->query("UPDATE " . DB_PREFIX . "gentestimonial SET 
	// 	rating = '" . (int) $data['rating'] . "', 
	// 	status = '" . (int) $data['status'] . "',
	// 	userLink = '" . $this->db->escape($data['userLink']) . "', 
	// 	sort_order = '" . (int) $data['sort_order'] . "',
	// 	description = '" . $this->db->escape($data['description']) . "',
	// 	user = '" . $this->db->escape($data['user']) . "', 
	// 	image = '" . $this->db->escape($data['image']) . "', 
	// 	recomended_shop = '" . $this->db->escape($data['recomended_shop']) . "', 
	// 	date = '" . $this->db->escape($data['date']) . "' 
	// 	WHERE testimonial_id = '" . (int) $testimonial_id . "'");
	// 	$this->cache->delete('gentestimonial');
	// }

	// public function deleteTestimonial($testimonial_id)
	// {
	// 	$this->db->query("DELETE FROM " . DB_PREFIX . "gentestimonial WHERE testimonial_id = '" . (int) $testimonial_id . "'");

	// 	$this->cache->delete('gentestimonial');
	// }

	// public function getTestimonialList($data = array())
	// {
	// 	if ($data) {
	// 		// $sql = "SELECT * FROM " . DB_PREFIX . "gentestimonial n LEFT JOIN " . DB_PREFIX . "gentestimonial_description nd ON (n.testimonial_id = nd.testimonial_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
	// 		$sql = "SELECT * FROM " . DB_PREFIX . "gentestimonial ";

	// 		$sort_data = array(
	// 			'user',
	// 			'date'
	// 		);

	// 		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
	// 			$sql .= " ORDER BY " . $data['sort'];
	// 		} else {
	// 			$sql .= " ORDER BY user";
	// 		}

	// 		if (isset($data['order']) && ($data['order'] == 'DESC')) {
	// 			$sql .= " DESC";
	// 		} else {
	// 			$sql .= " ASC";
	// 		}

	// 		if (isset($data['start']) || isset($data['limit'])) {
	// 			if ($data['start'] < 0) {
	// 				$data['start'] = 0;
	// 			}

	// 			if ($data['limit'] < 1) {
	// 				$data['limit'] = 20;
	// 			}

	// 			$sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
	// 		}

	// 		$query = $this->db->query($sql);

	// 		return $query->rows;
	// 	}
	// }

	// public function getTestimonialsStory($testimonial_id)
	// {

	// 	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gentestimonial WHERE testimonial_id = '" . (int) $testimonial_id . "'");

	// 	return $query->row;
	// }

	// public function getTotalTestimonial()
	// {

	// 	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "gentestimonial");

	// 	return $query->row['total'];
	// }

	// public function setTestimonialsSetting($data)
	// {

	// 	$queryV = $this->db->query("SELECT value1,value2 FROM " . DB_PREFIX . "gentestimonial_setting WHERE `testimonial_value_id` = '1'");
	// 	$value = $queryV->rows;
	// 	$query = $this->db->query("UPDATE `oc_gentestimonial_setting` SET `testimonial_value_id`=1,`value1`='" . $value[0]['value1'] . "',`value2`='" . $value[0]['value1'] . "',`template`='" . $this->db->escape($data['template']) . "' WHERE `testimonial_value_id` = '1'");
	// }

	// public function getTestimonialsSetting()
	// {
	// 	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gentestimonial_setting ");

	// 	return $query->row;
	// }

	// public function getValue()
	// {

	// 	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gentestimonial_setting WHERE `testimonial_value_id`=1");

	// 	return $query->row;
	// }

	// public function getValue2()
	// {

	// 	$query = $this->db->query("SELECT value2 FROM " . DB_PREFIX . "gentestimonial_setting WHERE `testimonial_value_id`=1");
	// 	$a = $query->row;

	// 	return $a['value2'];
	// }

	// public function copyTestimonial($testimonial_id)
	// {

	// 	$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "gentestimonial WHERE testimonial_id = '" . (int) $testimonial_id . "'");

	// 	if ($query->num_rows) {
	// 		$data = $query->row;


	// 		$data['status'] = '0';

	// 		$data['license'] = $this->getValue2();

	// 		$this->addTestimonial($data);
	// 	}
	// }
}
?>