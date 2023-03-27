<?php
class ControllerExtensionFeedSimpleYMLInstagram extends Controller {
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
			
			$fp = fopen("feed_instagram.xml", "w");
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
			$output .= '<title>'.$this->url->link('product/product', 'product_id='.$product['product_id'], true).'</title>';
			$output .= '<description><![CDATA['.html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8').']]></description>';
			$output .= '<link>'.$this->url->link('product/product', 'product_id='.$product['product_id'], true).'</link>';
			$output .= '<condition>new</condition>';
			$output .= '<availability>in stock</availability>';
			if (isset($product['special'])) {
				$output .= '<price>'.number_format($product['special'],2) . ' ' . $this->config->get('config_currency').'</price>';
				$output .= '<oldprice>'.number_format($product['price'],2) . ' ' . $this->config->get('config_currency').'</oldprice>';
			} else {
				$output .= '<price>'.number_format($product['price'],2) . ' ' .$this->config->get('config_currency').'</price>';
			}			
			$output .= '<image>'.HTTPS_SERVER.'image/'.$product['image'].'</image>';
			$productImages=$this->model_extension_feed_simple_yml->getProductImages($product['product_id']);
			foreach ($productImages as $productImage) {
				$output .= '<additional_image_link>'.HTTPS_SERVER.'image/'.$productImage['image'].'</additional_image_link>';
			}
			$output .= '<google_product_category>'.$product['category_id'].'</google_product_category>';
			$output .= '</item>';
		}

		return $output;
	}

}