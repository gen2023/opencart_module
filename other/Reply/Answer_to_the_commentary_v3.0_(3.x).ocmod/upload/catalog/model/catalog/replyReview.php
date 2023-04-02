<?php
class ModelCatalogReplyReview extends Model {
	public function addReview($review_id, $data) {
		//var_dump($data);
		if (!$data['feedback']) {
			
			$data['feedback']='---------';}
		$this->db->query("INSERT INTO " . DB_PREFIX . "reviewReply SET author = '" . $this->db->escape($data['name']) . "', customer_id = '" . (int)$this->customer->getId() . "', review_id = '" . (int)$review_id . "', text = '" . $this->db->escape($data['text']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', feedback = '" . $this->db->escape($data['feedback']) . "', date_added = NOW()");

		$review_id = $this->db->getLastId();

		if (in_array('review', (array)$this->config->get('config_mail_alert'))) {
			$this->load->language('mail/review');
			$this->load->model('catalog/product');
			
			$product_info = $this->model_catalog_product->getProduct($product_id);

			$subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

			$message  = $this->language->get('text_waiting') . "\n";
			$message .= sprintf($this->language->get('text_product'), html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8')) . "\n";
			$message .= sprintf($this->language->get('text_reviewer'), html_entity_decode($data['name'], ENT_QUOTES, 'UTF-8')) . "\n";
			$message .= $this->language->get('text_review') . "\n";
			$message .= html_entity_decode($data['text'], ENT_QUOTES, 'UTF-8') . "\n\n";

			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject($subject);
			$mail->setText($message);
			$mail->send();

			// Send to additional alert emails
			$emails = explode(',', $this->config->get('config_mail_alert_email'));

			foreach ($emails as $email) {
				if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}
	}
	public function getReview($review_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "review WHERE review_id = '" . (int)$review_id . "' AND status = '1' ORDER BY date_added DESC ");

		return $query->row;
	}

	public function getReplyByReviewId($review_id,$start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}
		
		$result=$this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "reviewReply'");

		if ($result->num_rows === 0) {
			$this->db->query("CREATE TABLE " . DB_PREFIX . "reviewReply ( reviewReply_id int(11) NOT NULL AUTO_INCREMENT, review_id int(11) NOT NULL, customer_id int(11) NOT NULL, author varchar(64) NOT NULL, admin_author varchar(64) NOT NULL, feedback text NOT NULL, answer varchar(64) NOT NULL, text text NOT NULL, email text NOT NULL, telephone text NOT NULL, status tinyint(1) NOT NULL DEFAULT '1', date_added datetime NOT NULL, date_modified datetime NOT NULL, PRIMARY KEY (reviewReply_id), KEY review_id (review_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		} 

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "reviewReply WHERE review_id = '" . (int)$review_id . "' AND status = '1' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalReplyByReviewsId($review_id) {

		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "reviewReply rr LEFT JOIN " . DB_PREFIX . "review r ON (rr.review_id = r.review_id) WHERE r.review_id = '" . (int)$review_id . "' AND r.status = '1' AND rr.status = '1' ");

		return $query->row['total'];
	}
}