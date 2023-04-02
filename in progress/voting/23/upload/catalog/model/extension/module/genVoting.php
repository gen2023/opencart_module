<?php
class ModelExtensionModuleGenVoting extends Model {
	public function editValue($data){
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `module_id` = '" . (int)$data['module_id'] . "'");

		$new_data=json_decode($query->row['setting'], true);
		//var_dump($new_data['voting_attributes'][0]);
		for ($i=0;$i<count($data['checked']);$i++){
			$new_data['voting_attributes'][$data['checked'][$i]]['value']+=1;
		}
		$summ=0;
		foreach($new_data['voting_attributes'] as $value){
			$summ += $value['value'];
		}
		
		for ($i=0;$i<count($new_data['voting_attributes']);$i++){
			$new_data['voting_attributes'][$i]['persent']=number_format($new_data['voting_attributes'][$i]['value']/$summ*100,2);
		}
		
		$this->db->query("UPDATE `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($new_data['name']) . "', `setting` = '" . $this->db->escape(json_encode($new_data)) . "' WHERE `module_id` = '" . (int)$data['module_id'] . "'");
		
		//return $new_data['view_user'];
 
	}
	
	public function getResult($data){
		
		$query = $this->db->query("SELECT `setting` FROM `" . DB_PREFIX . "module` WHERE `module_id` = '" . (int)$data['module_id'] . "'");
		
		$arr=json_decode($query->row['setting'], true);	
		$result=array();

		foreach($arr['voting_attributes'] as $key => $value){
			$result[$key]= $value['persent'];
		}		
		
		return $result;
	}
		
}