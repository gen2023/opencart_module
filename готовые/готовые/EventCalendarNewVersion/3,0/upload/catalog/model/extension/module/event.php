<?php
class ModelExtensionModuleEvent extends Model {

	public function getEvent($event_id) {
		
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "gen_events i LEFT JOIN " . DB_PREFIX . "gen_events_description id ON (i.event_id = id.event_id) WHERE i.event_id = '" . (int)$event_id . "' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "' and status = '1' ");
		
		if ($query->num_rows) {
			return array(
				'event_id'         => $query->row['event_id'],
				'image'            => $query->row['image'],
				'product_id'       => $query->row['product_id'],
				'date_from'        => $query->row['date_from'],
				'date_to'          => $query->row['date_to'],
				'time_from'        => $query->row['time_from'],
				'time_to'          => $query->row['time_to'],
				'title'            => $query->row['title'],
				'mindescription'   => $query->row['mindescription'],
				'description'      => $query->row['description'],
				'status'           => $query->row['status']
			);
		} else {
			return false;
		}
	}

    public function getEvents($data = array()) {
		$this->repetEvent();
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "gen_events i LEFT JOIN " . DB_PREFIX . "gen_events_description id ON (i.event_id = id.event_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' and status = '1'  ORDER BY sort_order";

			$query = $this->db->query($sql);

			return $query->rows;
		} else {
			$event_data = $this->cache->get('event.' . (int)$this->config->get('config_language_id'));

			if (!$event_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "event i LEFT JOIN " . DB_PREFIX . "gen_events_description id ON (i.event_id = id.event_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");

				$event_data = $query->rows;

				$this->cache->set('event.' . (int)$this->config->get('config_language_id'), $event_data);
			}

			return $event_data;
		}
	}

	public function repetEvent(){
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gen_events WHERE date_from < CURRENT_DATE() AND `status`=1 ");
	
		if ($query->num_rows){
			
			$arr=$query->rows;

			for ($i=0;$i<$query->num_rows;$i++){
				$arr[$i]['events_description']=$this->getEventsDescriptions($arr[$i]['event_id']);
				$arr[$i]['event_color']=$arr[$i]['color'];
				//$data['events_description']=$arr[$i]['color'];
				$resultKeyword=$this->getKeyword($arr[$i]['event_id']);
				$arr[$i]['keyword ']='';
				
				if ($arr[$i]['repeatEvent']!=0){				
				
					//$arr[$i]['license']='26028713';
					if ($arr[$i]['repeatEvent']==1){
						$arr[$i]['date_to']=date('Y-m-d',strtotime('+1 YEAR',strtotime($arr[$i]['date_to'])));
						$arr[$i]['date_from']=date('Y-m-d',strtotime('+1 YEAR',strtotime($arr[$i]['date_from'])));					
					} else {if ($arr[$i]['repeatEvent']==2){
						$arr[$i]['date_to']=date('Y-m-d',strtotime('+1 MONTH',strtotime($arr[$i]['date_to'])));
						$arr[$i]['date_from']=date('Y-m-d',strtotime('+1 MONTH',strtotime($arr[$i]['date_from'])));
					} else {if ($arr[$i]['repeatEvent']==3){
						$arr[$i]['date_to']=date('Y-m-d',strtotime('+7 DAY',strtotime($arr[$i]['date_to'])));
						$arr[$i]['date_from']=date('Y-m-d',strtotime('+7 DAY',strtotime($arr[$i]['date_from'])));
					} else {if ($arr[$i]['repeatEvent']==4){
						$arr[$i]['date_to']=date('Y-m-d',strtotime('+1 DAY',strtotime($arr[$i]['date_to'])));
						$arr[$i]['date_from']=date('Y-m-d',strtotime('+1 DAY',strtotime($arr[$i]['date_from'])));
					} }}}
					
					$this->db->query("UPDATE " . DB_PREFIX . "gen_events SET repeatEvent = 0 WHERE event_id = '" . (int)$arr[$i]['event_id'] . "'");
						
					$this->addEvents($arr[$i]);
				}
			}
		}			
	}

	public function getTotalEvents() {
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "gen_events WHERE `status`=1");


	return $query->row['total'];
	}
	
	public function getEventSetting() {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gen_events_setting");
		$query2 = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `code` = 'setting_module_genEvents' AND `key`='rightMenu'");
		
		foreach ($query2->rows as $result) {			
			$results_settingNameRightMenu[$result['key']] = json_decode($result['value'], true);			
		}

	return array_merge($query->row,$results_settingNameRightMenu);
	}

	public function addEvents($data) {
	 
		$this->db->query("INSERT INTO " . DB_PREFIX . "gen_events SET status = '" . (int)$data['status'] . "', alternativeLink = '" . $this->db->escape($data['alternativeLink']). "', color = '" . $this->db->escape($data['event_color']). "',
		date_from = '" . $this->db->escape($data['date_from']) . "', date_to = '" . $this->db->escape($data['date_to']) . "', 
		repeatEvent = '" . (int)$data['repeatEvent'] . "', sort_order = '" . (int)$data['sort_order'] . "', time_from = '" . $this->db->escape($data['time_from']) . "', time_to = '" . $this->db->escape($data['time_to']) . "'");
	
		$event_id = $this->db->getLastId();
	
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gen_events SET image = '" . $this->db->escape($data['image']) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		
		if (isset($data['product'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gen_events SET product_id = '" .  $this->db->escape($data["product"][0]) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		
		if (isset($data['product_id'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gen_events SET product_id = '" .  $this->db->escape($data["product_id"]) . "' WHERE event_id = '" . (int)$event_id . "'");
			
		}
	
		foreach ($data['events_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "gen_events_description SET event_id = '" . (int)$event_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "',  mindescription = '" . $this->db->escape($value['mindescription']) . "',  description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "',  meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}
	
		if (isset($data['events_store'])) {
			foreach ($data['events_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "gen_events_to_store SET event_id = '" . (int)$event_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		
		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'event_id=" . (int)$event_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
	
		$this->cache->delete('events');
	}

	public function getKeyword($event_id) {
		$query=$this->db->query("SELECT `keyword`  FROM " . DB_PREFIX . "seo_url WHERE query = 'event_id=" . (int)$event_id . "'");
		
		return $query->row;
	}

	public function getEventsDescriptions($event_id) { 
		$events_description_data = array();
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gen_events_description WHERE event_id = '" . (int)$event_id . "'");
	
		foreach ($query->rows as $result) {
			$events_description_data[$result['language_id']] = array(
				'title'            	=> $result['title'],
				'mindescription'    => $result['mindescription'],
				'description'      	=> $result['description'],
				'meta_title'        => $result['meta_title'],
				'meta_h1'           => $result['meta_h1'],
				'meta_description' 	=> $result['meta_description'],
				'meta_keyword'		=> $result['meta_keyword'],
			);
		}
	
		return $events_description_data;
	}
}