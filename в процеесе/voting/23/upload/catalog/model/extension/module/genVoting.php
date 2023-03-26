<?php
class ModelExtensionModuleGenVoting extends Model {
	public function editValue($data){
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `module_id` = '" . (int)$data['module_id'] . "'");

		$new_data=json_decode($query->row['setting'], true);
		//var_dump($new_data['voting_attributes'][0]);
		for ($i=0;$i<count($data['checked']);$i++){
			$new_data['voting_attributes'][$data['checked'][$i]]['value']+=1;
		}
		
		$this->db->query("UPDATE `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($new_data['name']) . "', `setting` = '" . $this->db->escape(json_encode($new_data)) . "' WHERE `module_id` = '" . (int)$data['module_id'] . "'");
		
		//return $new_data['view_user'];
 
	}
		
}