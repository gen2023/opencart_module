<?php
class ModelExtensionModuleGenpopup extends Model {
	public function addView($data){
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `module_id` = '" . (int)$data['module_id'] . "'");

		$new_data=json_decode($query->row['setting'], true);
		++$new_data['view_user'];
		
		$this->db->query("UPDATE `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($new_data['name']) . "', `setting` = '" . $this->db->escape(json_encode($new_data)) . "' WHERE `module_id` = '" . (int)$data['module_id'] . "'");
		
		return $new_data['view_user'];
 
	}
		
}