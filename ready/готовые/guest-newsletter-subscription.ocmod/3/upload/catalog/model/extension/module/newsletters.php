<?php
class ModelExtensionModuleNewsletters extends Model {
	public function subscribes($data) {
		$res = $this->db->query("select * from ". DB_PREFIX ."gen_newsletter where news_email='".$data['email']."'");
		if($res->num_rows == 1)
		{
			$res=2;
			return $res;
		}
		else
		{
		
			if($this->db->query("INSERT INTO " . DB_PREFIX . "gen_newsletter set news_email='" . $data['email'] . "', news_name= '" . $data['name'] . "', news_town='" . $data['town'] . "'"))

			{
				$res=1;
				return $res;
			}
			else
			{
				$res=0;
				return $res;
			}
		}
	}
		
}