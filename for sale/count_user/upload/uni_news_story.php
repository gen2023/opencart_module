<?php
class ControllerInformationUniNewsStory extends Controller {

	public function index() {
		$uniset = $this->config->get('config_unishop2');
		$lang_id = $this->config->get('config_language_id');
		
		$data['shop_name'] = $this->config->get('config_name');
		
		$route = isset($this->request->get['route']) ? $this->request->get['route'] : '';
		$menu_schema = isset($uniset['menu_schema']) ? $uniset['menu_schema'] : [];
		$data['menu_expanded'] = ($uniset['menu_type'] == 1 && in_array($route, $menu_schema)) ? true : false;
		$data['hide_last_breadcrumb'] = isset($uniset['breadcrumbs']['hide']['last']) ? true : false;
		
		$this->load->language('information/uni_news');
		$this->load->language('product/product');
		$this->load->language('extension/module/uni_othertext');
		
		$this->load->model('tool/image');
		$this->load->model('extension/module/uni_news');
		
		$this->document->addStyle('catalog/view/theme/unishop2/stylesheet/news.css');
		
		if(isset($uniset['socialbutton'])) {
			$this->document->addStyle('catalog/view/theme/unishop2/stylesheet/goodshare.css');
			$this->document->addScript('catalog/view/theme/unishop2/js/goodshare.min.js');
		}
		
		$settings = $this->config->get('uni_news');
		
		$news_id = isset($this->request->get['news_id']) ? (int)$this->request->get['news_id'] : 0;

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'href'		=> $this->url->link('common/home'),
			'text'		=> $this->language->get('text_home')
		];
		
		if (!isset($this->request->get['news_path'])) {
			//original idea https://github.com/OpenCart-Club/opencart
			
			$category_id = $this->model_extension_module_uni_news->getCategoryNews($news_id);
			
			if ($category_id) {
				$parts = $this->model_extension_module_uni_news->getPathByCategory($category_id);
				
				if($parts) {
					$this->request->get['news_path'] = implode('_', $parts);
				}
			}
		}
		
		$path = '';
		
		if (isset($this->request->get['news_path'])) {
			$parts = explode('_', (string)$this->request->get['news_path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}

				$category_info = $this->model_extension_module_uni_news->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = [
						'text' => $category_info['name'],
						'href' => $this->url->link('information/uni_news', 'news_path=' . $path)
					];
				}
			}
			
			$category_info = $this->model_extension_module_uni_news->getCategory($category_id);

			if ($category_info) {
				$url = '';

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}

				$data['breadcrumbs'][] = [
					'text' => $category_info['name'],
					'href' => $this->url->link('information/uni_news', 'news_path=' . $this->request->get['news_path'] . $url)
				];
			}
		}

		$news_info = $this->model_extension_module_uni_news->getNewsStory($news_id);

		if ($news_info) {
			$this->document->setTitle($news_info['name']);
			
			if ($news_info['meta_title']) {
				$this->document->setTitle($news_info['meta_title']);
			} else {
				$this->document->setTitle($news_info['name']);
			}
			
			$this->document->setDescription($news_info['meta_description']);
			$this->document->setKeywords($news_info['meta_keyword']);
			$this->document->addLink($this->url->link('information/uni_news_story', 'news_id='.$this->request->get['news_id']), 'canonical');
			
			//$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
			//$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
			
			$data['breadcrumbs'][] = [
				'text'	=> $news_info['name'],
				'href'	=> $this->url->link('information/uni_news_story', 'news_id='.$news_id),
			];
		
     		$data['news_info'] = $news_info;

			if ($news_info['meta_h1']) {
				$data['heading_title'] = $news_info['meta_h1'];
			} else {
				$data['heading_title'] = $news_info['name'];
			}
			
			$data['description'] = html_entity_decode($news_info['description'], ENT_QUOTES, 'UTF-8');
			$data['min_height'] = $this->config->get('news_thumb_height');
			$data['addthis'] = isset($settings['addthis']) ? $settings['addthis'] : '';
			$data['socialbutton'] = isset($uniset['socialbutton']) ? array_values($uniset['socialbutton']) : [];
			
			if($news_info['image']) {
				$thumb = $this->model_tool_image->resize($news_info['image'], $settings['thumb_width'], $settings['thumb_height']);
				
				$data['thumb'] = isset($settings['image']) ? $thumb : '';
				
				if(method_exists('document', 'setOgImage')) {
					$this->document->setOgImage($thumb);
				}
			} else {
				$data['thumb'] = '';
			}
			
			$data['popup'] = $this->model_tool_image->resize($news_info['image'], $settings['popup_width'], $settings['popup_height']);
			$data['viewed'] = $news_info['viewed'];
			$data['posted'] = date($this->language->get('date_format_short'), strtotime($news_info['date_added']));
			$data['news'] = $this->url->link('information/uni_news', 'news_path='.$news_info['category_id'], true);
			$data['continue'] = $this->url->link('common/home');
			
			if ($this->request->server['HTTPS']) {
				$server = $this->config->get('config_ssl');
			} else {
				$server = $this->config->get('config_url');
			}
			
			$logo = $this->config->get('config_logo');
			
			if(is_array($logo)) {
				$logo = $logo[$lang_id];
			}
			
			if (isset($news_info['meta_h1']) && $news_info['meta_h1'] != '') {
				$name = $news_info['meta_h1'];
			} else {
				$name = $news_info['name'];
			}
			
			$data['microdata'] = [
				'title'				=> str_replace(['"', '&quot;'], '', $name),
				'image'				=> $this->model_tool_image->resize($news_info['image'], $settings['popup_width'], $settings['popup_height']),
				'date' 				=> $news_info['date_added'],
				'short_description'	=> $news_info['meta_description'],
				'description' 		=> trim(str_replace(["\r\n", "\r", "\n", '"', '&nbsp;'], ' ',  strip_tags(html_entity_decode($news_info['description'], ENT_QUOTES, 'UTF-8')))),
				'url' 				=> $this->url->link('information/uni_news_story', 'news_id='.$this->request->get['news_id'], true),
				'publisher_name'	=> $this->config->get('config_name'),
				'publisher_url'		=> $server,
				'publisher_logo'	=> (is_file(DIR_IMAGE . $logo)) ?  $server.'image/' . $logo : ''
			];
			
			//related products
			$data['type_view'] = isset($settings['related_product_type_view']) ? 'grid' : 'carousel';
			$data['related_products_title'] = isset($settings['related_product_title'][$lang_id]) ? $settings['related_product_title'][$lang_id] : $this->language->get('text_related_product_title');
			$data['show_quick_order_text'] = isset($uniset['show_quick_order_text']) ? $uniset['show_quick_order_text'] : '';			
			$data['quick_order_icon'] = isset($uniset['show_quick_order']) ? $uniset[$lang_id]['quick_order_icon'] : '';
			$data['quick_order_title'] = isset($uniset['show_quick_order']) ? $uniset[$lang_id]['quick_order_title'] : '';
			$data['rating_type'] = $this->config->get('config_review_status') ? $uniset['catalog']['rating']['type'] : '';
			$data['wishlist_btn_disabled'] = isset($uniset['wishlist']['disabled']) ? true : false;
			$data['compare_btn_disabled'] = isset($uniset['compare']['disabled']) ? true : false;
			
			$data['prevnext'] = $this->getPrevNextNews($news_id, $news_info['date_added'], $news_info['category_id']);
			
			$data = array_merge($data, $this->getRelatedProduct());

			$this->model_extension_module_uni_news->updateViewed($news_id);
			
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			
			$this->response->setOutput($this->load->view('information/uni_news_story', $data));
	  	} else {
			$this->document->setTitle($this->language->get('text_error_news'));
			
			$data['heading_title'] = $this->language->get('text_error_news');
			$data['text_error'] = $this->language->get('text_error_news');
				
			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');
				
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
				
			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}
	
	private function getPrevNextNews($news_id, $date_added, $category_id) {
		$news = [];
		
		$results = $this->model_extension_module_uni_news->getPrevNextNews($news_id, $date_added, $category_id);
		
		foreach($results as $key => $result){
			if($result) {
				$news[$key] = [ 
					'name'	=> $result['name'],
					'date' 	=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'href'  => $this->url->link('information/uni_news_story', 'news_path='.$category_id.'&news_id='.$result['news_id'], true),
				];
			}
		}

		return $news;
	}
	
	private function getRelatedProduct() {
		$uniset = $this->config->get('config_unishop2');
		$lang_id = $this->config->get('config_language_id');
		$news_id = (int)$this->request->get['news_id'];
		
		$this->load->model('extension/module/uni_new_data');
		$this->load->model('catalog/product');
		
		$this->load->language('extension/module/uni_reviews');
		
		$stock_warning = (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')) ? true : false;
		
		$data['products'] = [];
			
		$related_products = $this->model_extension_module_uni_news->getNewsStoryRelatedProduct($news_id);
			
		if($related_products) {
			$img_width = $this->config->get('theme_'.$this->config->get('config_theme') . '_image_product_width');
			$img_height = $this->config->get('theme_'.$this->config->get('config_theme') . '_image_product_height');

			$currency = $this->session->data['currency'];
			$config_tax = $this->config->get('config_tax'); 
			
			foreach ($related_products as $result) {		
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $img_width, $img_height);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $img_width, $img_height);
				}
				
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
				} else {
					$price = false;
				}
							
				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
				} else {
					$special = false;
				}
				
				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
			
				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $currency);
				} else {
					$tax = false;
				}
					
				$new_data = $this->model_extension_module_uni_new_data->getNewData($result, ['width' => $img_width, 'height' => $img_height]);
				
				if($new_data['special_date_end']) {
					$data['show_timer'] = true;
				}
				
				if((int)$result['reviews']) {
					$data['show_rating'] = true;
				}
						
				$data['products'][] = [
					'product_id' 		=> $result['product_id'],
					'thumb'   	 		=> $image,
					'name'    			=> $result['name'],
					'description' 		=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_'.$this->config->get('config_theme') . '_product_description_length')) . '..',
					'tax'         		=> $tax,
					'minimum' 			=> $result['minimum'],
					'price'   	 		=> $price,
					'special' 	 		=> $special,
					'rating'     		=> $rating,
					'rating_new'		=> $result['rating'],
					'text_reviews'		=> $new_data['text_reviews'],
					'href'    	 		=> $this->url->link('product/product', 'product_id='.$result['product_id']),
					'minimum' 			=> $result['minimum'],
					'maximum'			=> $stock_warning ? $result['quantity'] : 100000,
					'quantity_indicator'=> $new_data['quantity_indicator'],
					'price_value' 		=> $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $config_tax), $currency, false, false),
					'special_value' 	=> $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $config_tax), $currency, false, false),
					'model'				=> $new_data['model'],
					'additional_image'	=> $new_data['additional_image'],
					'stickers' 			=> $new_data['stickers'],
					'special_date_end' 	=> $new_data['special_date_end'],
					'discounts'			=> $new_data['discounts'],
					'attributes' 		=> $new_data['attributes'],
					'options'			=> $new_data['options'],
					'show_description'	=> $new_data['show_description'],
					'show_quantity'		=> $new_data['show_quantity'],
					'quantity_indicator'=> $new_data['quantity_indicator'],
					'cart_btn_icon'		=> $new_data['cart_btn_icon'],
					'cart_btn_text'		=> $new_data['cart_btn_text'],
					'cart_btn_class'	=> $new_data['cart_btn_class'],
					'quick_order'		=> $new_data['quick_order']
				];
			}
		}
			
		return $data;
	}
}
?>