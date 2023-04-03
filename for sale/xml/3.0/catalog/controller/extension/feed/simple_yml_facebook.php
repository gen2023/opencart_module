<?php
class ControllerExtensionFeedSimpleYMLFacebook extends Controller {
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

			$output = '<?xml version="1.0" encoding="UTF-8"?><rss xmlns:g="http://base.google.com/ns/1.0"><channel>';
			$output .= '<title>'.$this->config->get('config_name').'</title>';
			$output .= '<link>'.$this->config->get('config_url').'</link>';
			$output .= '<description>'.$this->config->get('config_owner').'</description>';
			$output .= '<updated>'. date('Y-m-d H:i').'</updated>';

			$output .= $this->getProducts();

			$output .= '</channel></rss>';
			
			$fp = fopen("feed_facebook.xml", "w");
			fwrite($fp, $output);
			fclose($fp);

			$this->response->addHeader('Content-Type: application/xml');
			$this->response->setOutput($output);
			
		}
	}

	protected function getProducts() {
		$output = '';
		$products = $this->model_extension_feed_simple_yml->getProducts();
		foreach ($products as $product) {
			$output .= '<item>';
			$output .= '<id>'. $product['product_id'].'</id>';
			$output .= '<title>'. $product['name'].'</title>';
			$output .= '<description><![CDATA['.html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8').']]></description>';
			$output .= '<availability>in stock</availability>';
			$output .= '<condition>new</condition>';
			if (isset($product['special'])) {
				$output .= '<price>'.number_format($product['special'],2) . ' ' . $this->config->get('config_currency').'</price>';
				$output .= '<oldprice>'.number_format($product['price'],2) . ' ' . $this->config->get('config_currency').'</oldprice>';
			} else {
				$output .= '<price>'.number_format($product['price'],2) . ' ' .$this->config->get('config_currency').'</price>';
			}	
			$output .= '<link>'.$this->url->link('product/product', 'product_id='.$product['product_id'], true).'</link>';
			$output .= '<image_link>'.HTTPS_SERVER.'image/'.$product['image'].'</image_link>';
			$output .= '<brand>Ivory and Kate</brand>';		
			$output .= '<size>'.$productsOptionSize[0].'</size>';
			$output .= '<quantity_to_sell_on_facebook>100</quantity_to_sell_on_facebook>';
			$productImages=$this->model_extension_feed_simple_yml->getProductImages($product['product_id']);
			foreach ($productImages as $productImage) {
				$output .= '<additional_image_link>'.HTTPS_SERVER.'image/'.$productImage['image'].'</additional_image_link>';
			}
			$output .= '<google_product_category>2271</google_product_category>';
			$output .= '<fb_product_category>443</fb_product_category>';
			$output .= '</item>';
		}

		return $output;
	}

}