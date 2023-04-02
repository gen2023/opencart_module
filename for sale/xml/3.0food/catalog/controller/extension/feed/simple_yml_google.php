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

			$output = '<?xml version="1.0"?>
			<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0"><channel>';
			$output .= '<title>'.$this->config->get('config_name').'</title>';
			$output .= '<link>'.$this->config->get('config_url').'</link>';
			$output .= '<updated>'. date('Y-m-d H:i').'</updated>';

			$output .= $this->getProducts();

			$output .= '</channel></rss>';

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
			$output .= '<item>';
			$output .= '<g:id>'. $product['product_id'].'</g:id>';
			$output .= '<g:title>'. $product['name'].'</g:title>';
			$output .= '<g:description><![CDATA['.html_entity_decode(substr($product['description'],0,4900), ENT_QUOTES, 'UTF-8').']]></g:description>';
			$output .= '<g:link>'.$this->url->link('product/product', 'product_id='.$product['product_id'], true).'</g:link>';
			$output .= '<g:condition>new</g:condition>';
			$output .= '<g:availability>in stock</g:availability>';
			if (isset($product['special'])) {
				$output .= '<g:price>'.number_format($product['price'],2) . ' ' . $this->config->get('config_currency').'</g:price>';
				$output .= '<g:sale_price>'.number_format($product['special'],2) . ' ' . $this->config->get('config_currency').'</g:sale_price>';
			} else {
				$output .= '<g:price>'.number_format($product['price'],2) . ' ' .$this->config->get('config_currency').'</g:price>';
			}			
			$output .= '<g:image_link>'.HTTPS_SERVER.'image/'.$product['image'].'</g:image_link>';
			$productImages=$this->model_extension_feed_simple_yml->getProductImages($product['product_id']);
			foreach ($productImages as $productImage) {
				$output .= '<g:additional_image_link>'.HTTPS_SERVER.'image/'.$productImage['image'].'</g:additional_image_link>';
			}
			$output .= '<g:google_product_category>1755</g:google_product_category>';
			$output .= '</item>';
		}

		return $output;
	}

}