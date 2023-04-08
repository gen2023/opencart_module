<?php
class ControllerExtensionFeedSimpleYML extends Controller {
	private $currency_code;
	private $currency_rate;
	
	public function index() {
		if ($this->config->get('feed_simple_yml_status')) {

			set_time_limit(0);
			ignore_user_abort(true);
			while(ob_get_level()) ob_end_clean();
			ob_implicit_flush(true);

			$this->load->model('extension/feed/simple_yml');
			$this->load->model('localisation/currency');

			$output = '<?xml version="1.0" encoding="UTF-8"?><yml_catalog date="' . date('Y-m-d H:i') . '"><shop>';
			$output .= '<name>'.$this->config->get('config_name').'</name>';
			$output .= '<company>'.$this->config->get('config_owner').'</company>';
			$output .= '<url>'.$this->config->get('config_url').'</url>';

			$output .= $this->getCurrencies();
			$output .= $this->getCategories();
			$output .= $this->getProducts();

			$output .= '</shop></yml_catalog>';

			$this->response->addHeader('Content-Type: application/xml');
			$this->response->setOutput($output);
			
		}
	}

	protected function getCurrencies() {
		$currency = $this->config->get('config_currency');
		$currencies = array('RUR', 'RUB', 'UAH', 'BYN', 'KZT', 'USD', 'EUR');
		$default_currency = $this->model_localisation_currency->getCurrencyByCode($currency);
		if (in_array($currency, $currencies)) {
			$currency_data = $default_currency;
		} else {
			$shop_currencies = $this->model_localisation_currency->getCurrencies();
			foreach ($shop_currencies as $sc) {
				if (in_array($sc['code'], $currencies) && $sc['value'] == 1) {
					$currency_data = $sc;
					break;
				}
			}
			if (!isset($currency_data)) {
				foreach ($shop_currencies as $sc) {
					if (in_array($sc['code'], $currencies)) {
						$currency_data = array(
							'code' => $sc['code'],
							'value' => $sc['value'] / $default_currency['value']
						);
						break;
					}
				}
				if (!isset($currency_data)) exit('You must add one of these currencies to System-Localisation-Currencies: RUR, RUB, UAH, BYN, KZT, USD, EUR');
			}
		}

		$this->currency_code = $currency_data['code'];
		$this->currency_rate = $currency_data['value'];
			
		$output = '<currencies>';
		$output .= '<currency id="'.$currency_data['code'].'" rate="1"/>';
		$output .= '</currencies>';
		return $output;
	}

	protected function getCategories() {
		$output = '<categories>';
		$categories= $this->model_extension_feed_simple_yml->getCategories();
		foreach ($categories as $category) {
			$output .= '<category id="'.$category['category_id'].'"'.(($category['parent_id']) ? ' parentId="'.$category['parent_id'].'"' : '').'>'.$category['name'].'</category>';
		}
		$output .= '</categories>';
		return $output;
	}

	protected function getProducts() {
		$output = '<offers>';
		$products = $this->model_extension_feed_simple_yml->getProducts();
		foreach ($products as $product) {
			$output .= '<offer id="'.$product['product_id'].'" available="'.(($product['quantity']>0) ? 'true' : 'false').'">';
			$output .= '<url>'.$this->url->link('product/product', 'product_id='.$product['product_id'], true).'</url>';
			$output .= '<name>'.$product['name'].'</name>';
			if (isset($product['special'])) {
				$output .= '<price>'.$product['special'] * $this->currency_rate.'</price>';
				$output .= '<oldprice>'.$product['price'] * $this->currency_rate.'</oldprice>';
			} else {
				$output .= '<price>'.$product['price'] * $this->currency_rate.'</price>';
			}
			$output .= '<currencyId>'.$this->currency_code.'</currencyId>';
			$output .= '<categoryId>'.$product['category_id'].'</categoryId>';
			$output .= '<description><![CDATA['.html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8').']]></description>';
			$output .= '<picture>'.HTTPS_SERVER.'image/'.$product['image'].'</picture>';
			$output .= '</offer>';
		}
		$output .= '</offers>';

		return $output;
	}

}