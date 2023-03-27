<?php
class ControllerExtensionFeedSimpleYMLGoogle extends Controller {
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

			$output = '<?xml version="1.0" encoding="UTF-8"?><feed xmlns="http://www.w3.org/2005/Atom" xmlns:g="http://base.google.com/ns/1.0">';
			$output .= '<title>'.$this->config->get('config_name').'</title>';
			$output .= '<link>'.$this->config->get('config_url').'</link>';
			$output .= '<updated>'. date('Y-m-d H:i').'</updated>';

			$output .= $this->getProducts();

			$output .= '</feed>';			

			$fp = fopen("feed_google.xml", "w");
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
			$output .= '<entry>';
			$output .= '<g:id>'. $product['product_id'].'</g:id>';
			$output .= '<g:title>'.$this->url->link('product/product', 'product_id='.$product['product_id'], true).'</g:title>';
			$output .= '<g:description><![CDATA['.html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8').']]></g:description>';
			$output .= '<g:link>'.$this->url->link('product/product', 'product_id='.$product['product_id'], true).'</g:link>';
			//$output .= '<g:brand>'.$product['category_id'].'</g:brand>';
			$output .= '<g:condition>new</g:condition>';
			$output .= '<g:availability>in stock</g:availability>';
			if (isset($product['special'])) {
				$output .= '<g:price>'.number_format($product['special'],2) . ' ' . $this->config->get('config_currency').'</g:price>';
				$output .= '<g:oldprice>'.number_format($product['price'],2) . ' ' . $this->config->get('config_currency').'</g:oldprice>';
			} else {
				$output .= '<g:price>'.number_format($product['price'],2) . ' ' .$this->config->get('config_currency').'</g:price>';
			}			
			$output .= '<g:image>'.HTTPS_SERVER.'image/'.$product['image'].'</g:image>';
			$productImages=$this->model_extension_feed_simple_yml->getProductImages($product['product_id']);
			foreach ($productImages as $productImage) {
				$output .= '<g:additional_image_link>'.HTTPS_SERVER.'image/'.$productImage['image'].'</g:additional_image_link>';
			}
			$output .= '<g:google_product_category>'.$product['category_id'].'</g:google_product_category>';
			$output .= '</entry>';
		}

		return $output;
	}

}