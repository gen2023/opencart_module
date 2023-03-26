<?php
class ModelExtensionModuleGenpopup extends Model {
	
	public function setLastId() {
		$max_id=$this->db->query("SELECT max(`module_id`) as last FROM `" . DB_PREFIX . "module`");
		$module_id=$max_id->row['last'];

		$query=$this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `module_id` = '" . (int)$module_id . "'");
		
		if ($query->row) {
			$data= json_decode($query->row['setting'], true);
			//var_dump($data);
			$data['module_id']=$module_id;
		}
		//var_dump($data);
		$this->db->query("UPDATE `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($data['name']) . "', `setting` = '" . $this->db->escape(json_encode($data)) . "' WHERE `module_id` = '" . (int)$module_id . "'");
	}
	
	/*public function editModule($module_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($data['name']) . "', `setting` = '" . $this->db->escape(json_encode($data)) . "' WHERE `module_id` = '" . (int)$module_id . "'");
	}*/
}
?>