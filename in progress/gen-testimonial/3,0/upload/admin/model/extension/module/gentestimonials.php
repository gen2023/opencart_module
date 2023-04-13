<?php
class ModelExtensionModuleGentestimonials extends Model {
	
	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gentestimonial` (`testimonial_id` int(11) NOT NULL auto_increment, `rating` int(1) NOT NULL default '5',`status` int(1) NOT NULL default '0', `userLink` VARCHAR(255) COLLATE utf8_general_ci default NULL, `image` VARCHAR(255) COLLATE utf8_general_ci default NULL, `urlCompany` VARCHAR(255) COLLATE utf8_general_ci default NULL, `date` date default NULL, `sort_order` int(3) default NULL,  PRIMARY KEY (`testimonial_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");			
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gentestimonial_description` (`testimonial_id` int(11) NOT NULL default '0', `language_id` int(11) NOT NULL default '0', `user` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `testimonial_title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,`company` VARCHAR(255) COLLATE utf8_general_ci default NULL, `job_title` VARCHAR(255) COLLATE utf8_general_ci default NULL,  `meta_title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,  `meta_h1` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `meta_description` VARCHAR(255) COLLATE utf8_general_ci NOT NULL, `meta_keyword` varchar(255) COLLATE utf8_general_ci NOT NULL, PRIMARY KEY (`testimonial_id`,`language_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");	
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gentestimonial_setting` (`testimonial_value_id` int(11) NOT NULL auto_increment,`value1` VARCHAR(255) COLLATE utf8_general_ci default NULL, `value2` VARCHAR(255) COLLATE utf8_general_ci default NULL, `template` VARCHAR(255) COLLATE utf8_general_ci default NULL, PRIMARY KEY (`testimonial_value_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		
		$query3=$this->db->query("SELECT * FROM `" . DB_PREFIX . "gentestimonial_setting` WHERE value1='26028713'");
		$num_rows3 = $query3->num_rows;
		if ($num_rows3==0)	{
			$this->db->query("INSERT INTO `" . DB_PREFIX . "gentestimonial_setting` SET value1 = '26028713', value2 = '', template='1'");
		}
		
}

	public function addTestimonial($data) {
	//var_dump($data);
		$this->db->query("UPDATE " . DB_PREFIX . "gentestimonial_setting SET value2 = '" . $this->db->escape($data['license']) . "' WHERE testimonial_value_id = '1'");
		 
		$this->db->query("INSERT INTO " . DB_PREFIX . "gentestimonial SET rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "',userLink = '" . $this->db->escape($data['userLink']). "',  date = now(), sort_order = '" . (int)$data['sort_order'] . "', urlCompany = '" . $this->db->escape($data['urlCompany']) . "'");
	
		$testimonial_id = $this->db->getLastId();
	
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gentestimonial SET image = '" . $this->db->escape($data['image']) . "' WHERE testimonial_id = '" . (int)$testimonial_id . "'");
		}
		
		if (isset($data['date'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gentestimonial SET date = '" . $this->db->escape($data['date']) . "' WHERE testimonial_id = '" . (int)$testimonial_id . "'");
		}		
		
		foreach ($data['testimonial_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "gentestimonial_description SET testimonial_id = '" . (int)$testimonial_id . "', language_id = '" . (int)$language_id . "', user = '" . $this->db->escape($value['user']) . "',  testimonial_title = '" . $this->db->escape($value['testimonial_title']) . "',  job_title = '" . $this->db->escape($value['job_title']) . "',  company = '" . $this->db->escape($value['company']) . "',  description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "',  meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}
	
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'testimonial_id=" . (int)$testimonial_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
	
		$this->cache->delete('gentestimonial');
	}

	public function editTestimonial($testimonial_id, $data) {
//var_dump($data);
		$this->db->query("UPDATE " . DB_PREFIX . "gentestimonial SET rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "',userLink = '" . $this->db->escape($data['userLink']). "', sort_order = '" . (int)$data['sort_order'] . "', image = '" . $this->db->escape($data['image']) . "', urlCompany = '" . $this->db->escape($data['urlCompany']) . "', date = '" . $this->db->escape($data['date_to']) . "' WHERE testimonial_id = '" . (int)$testimonial_id . "'");
			
		$this->db->query("DELETE FROM " . DB_PREFIX . "gentestimonial_description WHERE testimonial_id = '" . (int)$testimonial_id . "'");
	
		foreach ($data['testimonial_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "gentestimonial_description SET testimonial_id = '" . (int)$testimonial_id . "', language_id = '" . (int)$language_id . "', user = '" . $this->db->escape($value['user']) . "', testimonial_title = '" . $this->db->escape($value['testimonial_title']) . "', job_title = '" . $this->db->escape($value['job_title']) . "', company = '" . $this->db->escape($value['company']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "',  meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'testimonial_id=" . (int)$testimonial_id . "'");
	
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'testimonial_id=" . (int)$testimonial_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
	
		$this->cache->delete('gentestimonial');
	}

	public function deleteTestimonial($testimonial_id) { 
		$this->db->query("DELETE FROM " . DB_PREFIX . "gentestimonial WHERE testimonial_id = '" . (int)$testimonial_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "gentestimonial_description WHERE testimonial_id = '" . (int)$testimonial_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'testimonial_id=" . (int)$testimonial_id . "'");
	
		$this->cache->delete('gentestimonial');
	}

	public function getTestimonialList($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "gentestimonial n LEFT JOIN " . DB_PREFIX . "gentestimonial_description nd ON (n.testimonial_id = nd.testimonial_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$sort_data = array(
				'nd.user',
				'n.date'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY nd.user";
			}

			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}

				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}

			$query = $this->db->query($sql);

			return $query->rows;
		} else {
			$testimonial_data = $this->cache->get('gentestimonial.' . (int)$this->config->get('config_language_id'));

			if (!$testimonial_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gentestimonial n LEFT JOIN " . DB_PREFIX . "events_description nd ON (n.testimonial_id = nd.testimonial_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY nd.user");

				$testimonial_data = $query->rows;

				$this->cache->set('gentestimonial.' . (int)$this->config->get('config_language_id'), $testimonial_data);
			}

			return $testimonial_data;
		}
	}

	public function getTestimonialsStory($testimonial_id) { 
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "seo_url WHERE query = 'testimonial_id=" . (int)$testimonial_id . "') AS keyword FROM " . DB_PREFIX . "gentestimonial n LEFT JOIN " . DB_PREFIX . "gentestimonial_description nd ON (n.testimonial_id = nd.testimonial_id) WHERE n.testimonial_id = '" . (int)$testimonial_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
		return $query->row;
	}

	public function getTestimonialDescriptions($testimonial_id) { 
		$testimonial_description_data = array();
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gentestimonial_description WHERE testimonial_id = '" . (int)$testimonial_id . "'");
	
		foreach ($query->rows as $result) {
			$testimonial_description_data[$result['language_id']] = array(
				'user'            	=> $result['user'],
				'testimonial_title'    => $result['testimonial_title'],
				'description'      	=> $result['description'],
				'company'    => $result['company'],
				'job_title'    => $result['job_title'],
				'meta_title'        => $result['meta_title'],
				'meta_h1'           => $result['meta_h1'],
				'meta_description' 	=> $result['meta_description'],
				'meta_keyword'		=> $result['meta_keyword'],
			);
		}
	
		return $testimonial_description_data;
	}

	public function getTestimonialsStores($testimonial_id) { 
	/*	$eventspage_store_data = array();
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "events_to_store WHERE testimonial_id = '" . (int)$testimonial_id . "'");
		
		foreach ($query->rows as $result) {
			$eventspage_store_data[] = $result['store_id'];
		}
	
		return $eventspage_store_data;*/
	}

	public function getTotalTestimonial() { 

     	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "gentestimonial");
	
		return $query->row['total'];
	}

	public function setTestimonialsSetting($data) {

		$queryV = $this->db->query("SELECT value1,value2 FROM " . DB_PREFIX . "gentestimonial_setting WHERE `testimonial_value_id` = '1'");
		$value=$queryV->rows;
		$query = $this->db->query("UPDATE `oc_gentestimonial_setting` SET `testimonial_value_id`=1,`value1`='" . $value[0]['value1'] ."',`value2`='" . $value[0]['value1'] ."',`template`='" . $this->db->escape($data['template']) . "' WHERE `testimonial_value_id` = '1'");
	}

	public function getTestimonialsSetting() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gentestimonial_setting ");

		return $query->row;
	}
	
	public function getValue() {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gentestimonial_setting WHERE `testimonial_value_id`=1");

		return $query->row;
	}
	
	public function getValue2() {
		
		$query = $this->db->query("SELECT value2 FROM " . DB_PREFIX . "gentestimonial_setting WHERE `testimonial_value_id`=1");
		$a=$query->row;

		return $a['value2'];
	}
	
	public function copyTestimonial($testimonial_id) {

		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "gentestimonial WHERE testimonial_id = '" . (int)$testimonial_id . "'");

		if ($query->num_rows) {
			$data = $query->row;
			
			$data['keyword'] = '';
			$data['status'] = '0';
			
			//$data['color_text'] = $query->row["color"];

			$data['testimonial_description'] = $this->getTestimonialDescriptions($testimonial_id);
			$data['license'] = $this->getValue2();

			$this->addTestimonial($data);
		}
	}
}
?>