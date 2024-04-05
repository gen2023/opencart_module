<?php
class ModelExtensionModuleGentestimonials extends Model
{
	public function getTestimonials($filter)
	{

		$sql = "SELECT * FROM " . DB_PREFIX . "gentestimonial WHERE status = '1'  ORDER BY sort_order DESC LIMIT " . $filter['count_slider'];

		$query = $this->db->query($sql);

		return $query->rows;
	}
	public function getTestimonialsAll()
	{

		$sql = "SELECT * FROM " . DB_PREFIX . "gentestimonial WHERE status = '1'";

		$query = $this->db->query($sql);

		return $query->rows;
	}
	public function getTotal() {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "gentestimonial`";
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	public function addTestimonial($data)
	{
		$sort = $this->getTotal() + 1;

		// $this->db->query("INSERT INTO " . DB_PREFIX . "gentestimonial SET rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "',userLink = '" . $this->db->escape($data['userLink']). "',  date = now() ");
		$this->db->query("INSERT INTO " . DB_PREFIX . "gentestimonial SET 
			rating = '" . (int) $data['rating'] . "', 
			status = '" . (int) $data['status_newTestimonial'] . "', 
			userLink = '" . $this->db->escape($data['userLink']) . "',
			description = '" . $this->db->escape($data['text']) . "',
			user = '" . $this->db->escape($data['name']) . "',  
			image = '', 
			date = now(), 
			sort_order = '" . (int) $sort . "'");

		// $testimonial_id = $this->db->getLastId();

		// if (isset($data['image'])) {
		// 	$this->db->query("UPDATE " . DB_PREFIX . "gentestimonial SET image = '" . $this->db->escape($data['image']) . "' WHERE testimonial_id = '" . (int)$testimonial_id . "'");
		// }


		$this->cache->delete('gentestimonial');
	}

	public function updateTestomonialRating($data)
	{

		if (isset($data['positive'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gentestimonial SET positive = '" . (int)$data['positive'] . "' WHERE testimonial_id = '" . (int)$data['testimonial_id'] . "'");
		}
		if (isset($data['negative'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gentestimonial SET negative = '" . (int)$data['negative'] . "' WHERE testimonial_id = '" . (int)$data['testimonial_id'] . "'");
		}

	}

}