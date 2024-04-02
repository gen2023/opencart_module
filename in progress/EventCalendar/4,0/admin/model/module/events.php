<?php
namespace Opencart\Admin\Model\Extension\GenModule\Module;
/**
 * Class Events
 *
 * @package Opencart\Admin\Controller\Extension\GenModule\Module
 */
class Events extends \Opencart\System\Engine\Model {

	/**
	 * @return void
	 */
	public function install(): void {
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gen_events` (`event_id` int(11) NOT NULL auto_increment, `status` int(1) NOT NULL default '0', `alternativeLink` VARCHAR(255) COLLATE utf8_general_ci default NULL, `image` VARCHAR(255) COLLATE utf8_general_ci default NULL, `color` VARCHAR(255) COLLATE utf8_general_ci default NULL, `product_id` int(11) default NULL, `date_to` date default NULL, `date_from` date default NULL,`sort_order` int(3) default NULL, `time_to` time default NULL, `time_from` time default NULL,`repeatEvent` int(1) NOT NULL default '0', PRIMARY KEY (`event_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");			
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gen_events_description` (`event_id` int(11) NOT NULL default '0', `language_id` int(11) NOT NULL default '0', `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `mindescription` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,  `meta_title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,  `meta_h1` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `meta_description` VARCHAR(255) COLLATE utf8_general_ci NOT NULL, `meta_keyword` varchar(255) COLLATE utf8_general_ci NOT NULL, PRIMARY KEY (`event_id`,`language_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");	
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gen_events_to_store` (`event_id` int(11) NOT NULL, `store_id` int(11) NOT NULL, PRIMARY KEY (`event_id`, `store_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gen_events_setting` (`event_value_id` int(11) NOT NULL auto_increment, `value` VARCHAR(255) COLLATE utf8_general_ci default NULL, `rightColumnMenu` VARCHAR(255) COLLATE utf8_general_ci default NULL, `initialView` VARCHAR(255) COLLATE utf8_general_ci default NULL, `firstDay` int(1) COLLATE utf8_general_ci default NULL, `dayMaxEvents` int(11) COLLATE utf8_general_ci default 1, `event_share` int(1) NOT NULL, `status` int(1) NOT NULL default '0', PRIMARY KEY (`event_value_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "gen_events_setting` SET `event_value_id` = '1', `rightColumnMenu`='dayGridMonth', `initialView`='dayGridMonth', `firstDay`=1, `dayMaxEvents`=1, `status`=0 ");
}

	/**
	 * @return void
	 */
	public function uninstall(): void { 
		$this->db->query("DROP TABLE `" . DB_PREFIX . "gen_events`");
		$this->db->query("DROP TABLE `" . DB_PREFIX . "gen_events_description`");
		$this->db->query("DROP TABLE `" . DB_PREFIX . "gen_events_to_store`");
		$this->db->query("DROP TABLE `" . DB_PREFIX . "gen_events_setting`");
	}

	/**
	 * @param int $data
	 * 
	 * @return void
	 */
	public function addEvents($data): void {
	 
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

	/**
	 * @param int $event_id
	 * @param int $data
	 * 
	 * @return void
	 */
	public function editEvents($event_id, $data): void {

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
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'event_id=" . (int)$event_id . "'");
	
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'event_id=" . (int)$event_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
	
		$this->cache->delete('events');
	}

	/**
	 * @param int $event_id
	 * 
	 * @return void
	 */
	public function deleteEvents($event_id): void { 
		$this->db->query("DELETE FROM " . DB_PREFIX . "gen_events WHERE event_id = '" . (int)$event_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "gen_events_description WHERE event_id = '" . (int)$event_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "gen_events_to_store WHERE event_id = '" . (int)$event_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'event_id=" . (int)$event_id . "'");
	
		$this->cache->delete('events');
	}

	/**
	 * @return void
	 */
	public function repetEvent(): void {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gen_events WHERE date_from < CURRENT_DATE() AND `status`=1 ");
	
		if ($query->num_rows){
			
			$arr=$query->rows;

			for ($i=0;$i<$query->num_rows;$i++){
				$arr[$i]['events_description']=$this->getEventsDescriptions($arr[$i]['event_id']);
				$arr[$i]['event_color']=$arr[$i]['color'];
				$resultKeyword=$this->getKeyword($arr[$i]['event_id']);
				$arr[$i]['keyword ']='';
				
				if ($arr[$i]['repeatEvent']!=0){				
				
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
					}else {if ($arr[$i]['repeatEvent']==5){
						$arr[$i]['date_to']=date('Y-m-d',strtotime('+2 DAY',strtotime($arr[$i]['date_to'])));
						$arr[$i]['date_from']=date('Y-m-d',strtotime('+2 DAY',strtotime($arr[$i]['date_from'])));
					} }}}}
					
					$this->db->query("UPDATE " . DB_PREFIX . "gen_events SET repeatEvent = 0 WHERE event_id = '" . (int)$arr[$i]['event_id'] . "'");
						
					$this->addEvents($arr[$i]);
				}
			}
		}			
	}
	
	/**
	 * @param int $event_id
	 *
	 * @return array
	 */
	public function getKeyword($event_id): array {
		$query=$this->db->query("SELECT `keyword`  FROM " . DB_PREFIX . "seo_url WHERE query = 'event_id=" . (int)$event_id . "'");
		
		return $query->row;
	}

	/**
	 * @param int $data
	 *
	 * @return array
	 */	
	public function getEventsList($data = array()): array {
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

	/**
	 * @param int $event_id
	 *
	 * @return array
	 */		
	public function getEventsStory($event_id): array { 
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "seo_url WHERE query = 'event_id=" . (int)$event_id . "') AS keyword FROM " . DB_PREFIX . "gen_events n LEFT JOIN " . DB_PREFIX . "gen_events_description nd ON (n.event_id = nd.event_id) WHERE n.event_id = '" . (int)$event_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
		return $query->row;
	}

	/**
	 * @param int $event_id
	 *
	 * @return array
	 */		
	public function getEventsDescriptions($event_id): array { 
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

	/**
	 * @param int $event_id
	 *
	 * @return array
	 */			
	public function getEventsStores($event_id): array { 
		$eventspage_store_data = array();
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gen_events_to_store WHERE event_id = '" . (int)$event_id . "'");
		
		foreach ($query->rows as $result) {
			$eventspage_store_data[] = $result['store_id'];
		}
	
		return $eventspage_store_data;
	}

	/**
	 * @return int
	 */	
	public function getTotalEvents(): int { 

     	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "gen_events");
	
		return (int)$query->row['total'];
	}

	/**
	 * @param int $data
	 *
	 * @return void
	 */	
	public function setEventsSetting($data): void {
		
		if ($data) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'route' AND `value` = 'extension/module/event_list'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'route' AND `value` = 'extension/module/event'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'route' AND `value` = 'extension/module/event_detail'");

		if (isset($data['events_url'])) {
			foreach ($data['events_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$store_id . "', `language_id` = '" . (int)$language_id . "', `key` = 'route', `value` = 'extension/module/event', `keyword` = '" . $this->db->escape($keyword) . "'");
				}
			}
		}

		if (isset($data['eventslist_url'])) {
			foreach ($data['eventslist_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$store_id . "', `language_id` = '" . (int)$language_id . "', `key` = 'route', `value` = 'extension/module/event_list', `keyword` = '" . $this->db->escape($keyword) . "'");
				}
			}
		}

		if (isset($data['eventsDetail_url'])) {
			foreach ($data['eventsDetail_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$store_id . "', `language_id` = '" . (int)$language_id . "', `key` = 'route', `value` = 'extension/module/event_detail', `keyword` = '" . $this->db->escape($keyword) . "'");
				}
			}
		}
			
			if (isset($data['rightMenu'])){			
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `code` = 'setting_module_genEvents' AND `key`='rightMenu'");
			
				if ($query) {
					$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code` = 'setting_module_genEvents' AND `key`='rightMenu'");
				}
				$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'setting_module_genEvents', `key`='rightMenu', `value` = '" . $this->db->escape(json_encode($data['rightMenu'])) . "', `serialized`='0'");
			} 
			$rightColumnMenu = '';
			if (isset($data['rightColumnMenu'])){
				foreach ($data['rightColumnMenu'] as $result) {
					
					if($result!='0'){
						$rightColumnMenu .= $result . ',';
					}
				}				
			}
			
			if (isset($data['event_initialView'])){$event_initialView=$data['event_initialView'];}else {$event_initialView='dayGridMonth';}
			//var_dump($data);
			$this->db->query("UPDATE " . DB_PREFIX . "gen_events_setting SET firstDay = '" . (int)$data['event_firstDay'] . "', dayMaxEvents = '" . (int)$data['dayMaxEvents'] . "', rightColumnMenu = '" . $this->db->escape($rightColumnMenu) . "', initialView = '" . $this->db->escape($event_initialView) . "', value = '" . $this->db->escape($data['value']). "', status = '" . $this->db->escape($data['status']) . "' WHERE event_value_id = '1'");
			
		} 
	}

	/**
	 *
	 * @return array
	 */	
	public function getEventsSetting(): array {
		$results_url= array();

		$results_url['events_url']=$this->getSeoUrls('extension/module/event');
		$results_url['eventslist_url']=$this->getSeoUrls('extension/module/event_list');
		$results_url['eventsDetail_url']=$this->getSeoUrls('extension/module/event_detail');
		
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

		/**
	 * @param string $type_page
	 *
	 * @return array
	 */
	public function getSeoUrls($type_page): array {
		$seo_url_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'route' AND `value` = '" . $type_page . "'");

		foreach ($query->rows as $result) {
			$seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $seo_url_data;
	}
	
	/**
	 *
	 * @return array
	 */		
	public function getValue():array {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gen_events_setting WHERE `event_value_id`=1");
		return $query->row;
	}
	
	/**
	 *
	 * @return string
	 */		
	public function getValue2(): string {
		
		$query = $this->db->query("SELECT value FROM " . DB_PREFIX . "gen_events_setting WHERE `event_value_id`=1");
		$val=$_SERVER['HTTP_HOST'];
		$val=strlen($val);
		if(($query->row['value']) && (strlen($query->row['value'])==$val)){
			return $query->row['value'];
		}else{
			return '';
		}
	}
		
	/**
	 *
	 * @return void
	 */	
	public function copyEvent($event_id): void {

		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "gen_events WHERE event_id = '" . (int)$event_id . "'");

		if ($query->num_rows) {
			$data = $query->row;
			
			$data['keyword'] = '';
			$data['status'] = '0';
			
			$data['event_color'] = $query->row["color"];

			$data['events_description'] = $this->getEventsDescriptions($event_id);
			$data['events_store'] = $this->getEventsStores($event_id);

			$this->addEvents($data);
		}
	}
	
	/**
	 *
	 * @return int
	 */		
	public function setHideOldEvent(): int {

		$query = $this->db->query("SELECT `event_id` FROM " . DB_PREFIX . "gen_events WHERE date_from < CURRENT_DATE() AND `status`=1 ");
		
		if ($query->num_rows){
			$arrId=$query->rows;
		
			for ($i=0;$i<$query->num_rows;$i++){
				$this->db->query("UPDATE `" . DB_PREFIX . "gen_events` SET `status`=0 WHERE `event_id`='". $arrId[$i]['event_id'] ."'");
			}
			return (int)$query->num_rows;
		} else {
			return 0;
		}		
	}
}
?>