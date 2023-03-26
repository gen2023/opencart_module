<?php
class ModelExtensionModuleEvents extends Model {
	
	public function install() {
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gen_events` (`event_id` int(11) NOT NULL auto_increment, `status` int(1) NOT NULL default '0', `alternativeLink` VARCHAR(255) COLLATE utf8_general_ci default NULL, `image` VARCHAR(255) COLLATE utf8_general_ci default NULL, `color` VARCHAR(255) COLLATE utf8_general_ci default NULL, `product_id` int(11) default NULL, `date_from` date default NULL, `date_to` date default NULL,`sort_order` int(3) default NULL, `time_to` time default NULL, `time_from` time default NULL, `repeatEvent` int(1) NOT NULL default '0', PRIMARY KEY (`event_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");			
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gen_events_description` (`event_id` int(11) NOT NULL default '0', `language_id` int(11) NOT NULL default '0', `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `mindescription` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,  `meta_title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,  `meta_h1` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `meta_description` VARCHAR(255) COLLATE utf8_general_ci NOT NULL, `meta_keyword` varchar(255) COLLATE utf8_general_ci NOT NULL, PRIMARY KEY (`event_id`,`language_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");	
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gen_events_to_store` (`event_id` int(11) NOT NULL, `store_id` int(11) NOT NULL, PRIMARY KEY (`event_id`, `store_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");

		$query1=$this->db->query("SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE `query`='extension/module/event_list'");
		$num_rows1 = $query1->num_rows;
		if ($num_rows1==0)	{
			$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'extension/module/event_list', `keyword` = 'event_list'");
		}
		
		$query1=$this->db->query("SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE `query`='extension/module/event_detail'");
		$num_rows1 = $query1->num_rows;
		if ($num_rows1==0)	{
			$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'extension/module/event_detail', `keyword` = 'event_detail'");
		}

		$query2=$this->db->query("SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE `query`='extension/module/event'");
		$num_rows2 = $query2->num_rows;
		if ($num_rows2==0)	{
			$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'extension/module/event', `keyword` = 'event'");
		}

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gen_events_setting` (`event_value_id` int(11) NOT NULL auto_increment,`value` VARCHAR(255) COLLATE utf8_general_ci default NULL, `status` int(1) NOT NULL default '0', `rightColumnMenu` VARCHAR(255) COLLATE utf8_general_ci default NULL, `initialView` VARCHAR(255) COLLATE utf8_general_ci default NULL, `firstDay` int(1) COLLATE utf8_general_ci default 1, `dayMaxEvents` int(11) COLLATE utf8_general_ci default 1, `event_share` int(1) NOT NULL, PRIMARY KEY (`event_value_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "gen_events_setting` SET `event_value_id` = '1', `rightColumnMenu`='dayGridMonth', `initialView`='dayGridMonth', `firstDay`=1, `dayMaxEvents`=1, `status`=0 ");		
	}

	public function uninstall() { 
		$this->db->query("DROP TABLE `" . DB_PREFIX . "gen_events`");
		$this->db->query("DROP TABLE `" . DB_PREFIX . "gen_events_description`");
		$this->db->query("DROP TABLE `" . DB_PREFIX . "gen_events_to_store`");
		$this->db->query("DROP TABLE `" . DB_PREFIX . "gen_events_setting`");
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
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'event_id=" . (int)$event_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
	
		$this->cache->delete('events');
	}

	public function editEvents($event_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "gen_events SET status = '" . (int)$data['status'] . "', alternativeLink = '" . $this->db->escape($data['alternativeLink']). "', color = '" . $this->db->escape($data['event_color']). "', date_from = '" . $this->db->escape($data['date_from']) . "', date_to = '" . $this->db->escape($data['date_to']) . "', repeatEvent = '" . (int)$data['repeatEvent'] . "',time_from = '" . $this->db->escape($data['time_from']) . "',time_to = '" . $this->db->escape($data['time_to']) . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE event_id = '" . (int)$event_id . "'");
			
		if (isset($data['product'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gen_events SET product_id = '" . $this->db->escape($data["product"][0]) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		else{
			$this->db->query("UPDATE " . DB_PREFIX . "gen_events SET product_id = '0' WHERE event_id = '" . (int)$event_id . "'");
		}
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "gen_events SET image = '" . $this->db->escape($data['image']) . "' WHERE event_id = '" . (int)$event_id . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "gen_events_description WHERE event_id = '" . (int)$event_id . "'");
	
		foreach ($data['events_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "gen_events_description SET event_id = '" . (int)$event_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', mindescription = '" . $this->db->escape($value['mindescription']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "',  meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "gen_events_to_store WHERE event_id = '" . (int)$event_id . "'");
	
		if (isset($data['events_store'])) {		
			foreach ($data['events_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "gen_events_to_store SET event_id = '" . (int)$event_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'event_id=" . (int)$event_id . "'");
	
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'event_id=" . (int)$event_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
	
		$this->cache->delete('events');
	}

	public function deleteEvents($event_id) { 
		$this->db->query("DELETE FROM " . DB_PREFIX . "gen_events WHERE event_id = '" . (int)$event_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "gen_events_description WHERE event_id = '" . (int)$event_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "gen_events_to_store WHERE event_id = '" . (int)$event_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'event_id=" . (int)$event_id . "'");
	
		$this->cache->delete('events');
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

	public function getKeyword($event_id) {
		$query=$this->db->query("SELECT `keyword`  FROM " . DB_PREFIX . "url_alias WHERE query = 'event_id=" . (int)$event_id . "'");
		
		return $query->row;
	}

	public function getEventsList($data = array()) {
		$this->repetEvent();
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "gen_events n LEFT JOIN " . DB_PREFIX . "gen_events_description nd ON (n.event_id = nd.event_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$sort_data = array(
				'nd.title',
				'n.date_to'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY nd.title";
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
			$events_data = $this->cache->get('events.' . (int)$this->config->get('config_language_id'));

			if (!$events_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gen_events n LEFT JOIN " . DB_PREFIX . "gen_events_description nd ON (n.event_id = nd.event_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY nd.title");

				$events_data = $query->rows;

				$this->cache->set('events.' . (int)$this->config->get('config_language_id'), $events_data);
			}

			return $events_data;
		}
	}

	public function getEventsStory($event_id) { 
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'event_id=" . (int)$event_id . "') AS keyword FROM " . DB_PREFIX . "gen_events n LEFT JOIN " . DB_PREFIX . "gen_events_description nd ON (n.event_id = nd.event_id) WHERE n.event_id = '" . (int)$event_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
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

	public function getEventsStores($event_id) { 
		$eventspage_store_data = array();
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gen_events_to_store WHERE event_id = '" . (int)$event_id . "'");
		
		foreach ($query->rows as $result) {
			$eventspage_store_data[] = $result['store_id'];
		}
	
		return $eventspage_store_data;
	}

	public function getTotalEvents() { 

     	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "gen_events");
	
		return $query->row['total'];
	}

	public function setEventsSetting($data) {

		if ($data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/event_list'");
			
			if ($query) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/event_list'");
				$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'extension/module/event_list', `keyword` = '" . $this->db->escape($data['eventslist_url']) . "'");
			}else{
				$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'extension/module/event_list', `keyword` = '" . $this->db->escape($data['eventslist_url']) . "'");
			}
		
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/event'");
			if ($query) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/event'");
				$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'extension/module/event', `keyword` = '" . $this->db->escape($data['events_url']) . "'");
			}else{
				$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'extension/module/event', `keyword` = '" . $this->db->escape($data['events_url']) . "'");
			}
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/event_detail'");
			
			if ($query) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/event_detail'");
				$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'extension/module/event_detail', `keyword` = '" . $this->db->escape($data['eventsDetail_url']) . "'");
			}else{
				$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'extension/module/event_detail', `keyword` = '" . $this->db->escape($data['eventsDetail_url']) . "'");
			}

			if (isset($data['rightMenu'])){			
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `code` = 'setting_module_genEvents' AND `key`='rightMenu'");
			
				if ($query) {
					$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code` = 'setting_module_genEvents' AND `key`='rightMenu'");
				}
				$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'setting_module_genEvents', `key`='rightMenu', `value` = '" . $this->db->escape(json_encode($data['rightMenu'])) . "', `serialized`='0'");

			} 
			
		} 
		if (isset($data['rightColumnMenu'])){
		$rightColumnMenu = implode(",", $data['rightColumnMenu']);
		}else{
			$rightColumnMenu = '';
		}
		if (isset($data['event_initialView'])){$event_initialView=$data['event_initialView'];}else {$event_initialView='dayGridMonth';}
		
		$this->db->query("UPDATE " . DB_PREFIX . "gen_events_setting SET event_share = '" . (int)$data['event_share'] . "', firstDay = '" . (int)$data['event_firstDay'] . "', dayMaxEvents = '" . (int)$data['dayMaxEvents'] . "', rightColumnMenu = '" . $this->db->escape($rightColumnMenu) . "', initialView = '" . $this->db->escape($event_initialView) . "', value = '" . $this->db->escape($data['value']) . "' WHERE event_value_id = '1'");
		
	}

	public function getEventsSetting() {
		$results_url= array();
		$query = $this->db->query("SELECT keyword as event FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/event' LIMIT 1");
		$results_url['event']=$query->row['event'];

		$query = $this->db->query("SELECT keyword as list FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/event_list' LIMIT 1");
		$results_url['list']=$query->row['list'];
		
		$query = $this->db->query("SELECT keyword as detail FROM " . DB_PREFIX . "url_alias WHERE query = 'extension/module/event_detail' LIMIT 1");
		$results_url['detail']=$query->row['detail'];
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gen_events_setting WHERE event_value_id ='1'");
		$results_setting=$query->row;
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `code` = 'setting_module_genEvents' AND `key`='rightMenu'");
		
		if ($query->num_rows!=0){
		
			foreach ($query->rows as $result) {			
				$results_settingNameRightMenu[$result['key']] = json_decode($result['value'], true);			
			}
		} else{$results_settingNameRightMenu=array('rightMenu'=>'');}
		
		return array_merge($results_url, $results_setting,$results_settingNameRightMenu);
	}
	
	public function getValue() {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gen_events_setting WHERE `event_value_id`=1");

		return $query->row;
	}
	public function getValue2() {
		
		$query = $this->db->query("SELECT value FROM " . DB_PREFIX . "gen_events_setting WHERE `event_value_id`=1");
		$val=$_SERVER['HTTP_HOST'];
		$val=strlen($val);
		if(($query->row['value']) && (strlen($query->row['value'])==$val)){
			return $query->row['value'];
		}else{
			return '';
		}
	}
	
	public function copyEvent($event_id) {

		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "gen_events WHERE event_id = '" . (int)$event_id . "'");
		if ($query->num_rows) {
			$data = $query->row;
			
			$data['keyword'] = '';
			$data['status'] = '0';
			
			$data['event_color'] = $query->row["color"];

			$data['events_description'] = $this->getEventsDescriptions($event_id);
			$data['license'] = $this->getValue2();
			$data['events_store'] = $this->getEventsStores($event_id);

			$this->addEvents($data);
		}
	}

	public function setHideOldEvent() {

		$query = $this->db->query("SELECT `event_id` FROM " . DB_PREFIX . "gen_events WHERE date_from < CURRENT_DATE()-1 AND `status`=1 ");
		
		if ($query->num_rows){
			$arrId=$query->rows;
		
			for ($i=0;$i<$query->num_rows;$i++){
				$this->db->query("UPDATE `" . DB_PREFIX . "gen_events` SET `status`=0 WHERE `event_id`='". $arrId[$i]['event_id'] ."'");
			}
			return $query->num_rows;
		} else {
			return 0;
		}		
	}
}
?>