<?php
class ControllerProductReplyReview extends Controller {
	private $error = array();
	public function review() {

		$data['entry_admin_author'] = $this->config->get('config_name');
		
		$this->load->language('product/product');
		$this->load->language('product/reviewReply');

		$this->load->model('catalog/review');
		$this->load->model('catalog/replyReview');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			if (isset($this->request->get['pageReview'])) {
				$page = $this->request->get['pageReview'];
			}else{
				$page = 1;
			}
		}
		
		if (isset($this->request->get['pageReply'])) {
			$pageReply = $this->request->get['pageReply'];
		} else {
			$pageReply = 1;
		}
		
		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} else {
			$data['telephone'] = '';
		}
		
		if ($this->config->get('config_review_guest') || $this->customer->isLogged()) {
				$data['review_guest'] = true;
			} else {
				$data['review_guest'] = false;
			}
			
		$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));
		
		if ($this->customer->isLogged()) {
				$data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
			} else {
				$data['customer_name'] = '';
			}
		
		$data['reviews'] = array();

		$results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);
		
		$data['reviewThisPage']='';
		
		/*--------------------------------------*/
		if (isset($this->request->get['reviewThisPage'])){
		
			$data['reviewThisPage']='';
		
			$arrayReview_id=explode('/', $this->request->get['reviewThisPage']);
		
			$results = array();
		
			for ($i=0; $i<count($arrayReview_id)-1; $i++)
			{
				$results[$i] = $this->model_catalog_replyReview->getReview($arrayReview_id[$i]);
			}
		
			foreach ($results as $result) {
				
				$data['reviews'][] = array(
				'review_id'			=> $result['review_id'],
				'admin_author'      => $result['admin_author'],
				'answer'       		=> $result['answer'],					
				'author'     		=> $result['author'],
				'text'       		=> nl2br($result['text']),
				'rating'     		=> (int)$result['rating'],
				'replyFromReview_id'=> ($this->request->get['review_id']==$result['review_id']) ? ($this->model_catalog_replyReview->getReplyByReviewId($result['review_id'], ($pageReply - 1) * 5, 5)) : ($this->model_catalog_replyReview->getReplyByReviewId($result['review_id'], 0, 5)),
				'reply_total' 		=> $this->model_catalog_replyReview->getTotalReplyByReviewsId($result['review_id']),
				'paginationReplyUrl'=> 'product/reply_review/review&product_id=' . $this->request->get['product_id'] . '&pageReview='. $page . '&review_id=' . $result['review_id'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
				);
				$data['reviewThisPage'] .= $result['review_id'] .'/';
			}
		}
		else
		{
			//var_dump($page);
		  foreach ($results as $result) {			
			$data['reviews'][] = array(
				'review_id'			=> $result['review_id'],
				'admin_author'      => $result['admin_author'],
				'answer'       		=> $result['answer'],					
				'author'     		=> $result['author'],
				'text'       		=> nl2br($result['text']),
				'rating'     		=> (int)$result['rating'],
				'replyFromReview_id'=> $this->model_catalog_replyReview->getReplyByReviewId($result['review_id'],0, 5),
				'reply_total' 		=> $this->model_catalog_replyReview->getTotalReplyByReviewsId($result['review_id']),
				'paginationReplyUrl'=> 'product/reply_review/review&product_id=' . $this->request->get['product_id'] . '&pageReview='. $page . '&review_id=' . $result['review_id'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
			
			$data['reviewThisPage'] .= $result['review_id'] .'/';
		  }
		}

		/*--------------------------------------*/
		//для js
		if ($data['reviews']){
			$data['reviewsId']=$data['reviews'][0]['review_id'];
		}
		else
		{
			$data['reviewsId']=0;
		}
		/*--------------------------------------*/
		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);

		for ($i=0;$i<count($data['reviews']);$i++){

			$key=$data['reviews'][$i]['review_id']; 
			$paginationReply[$key] = new Pagination();
			$paginationReply[$key]->total = $data['reviews'][$i]['reply_total'];
			if (isset($this->request->get['review_id'])){
				$paginationReply[$key]->page = ($this->request->get['review_id']==$data['reviews'][$i]['review_id']) ? $pageReply : 1;
			}else{
				$paginationReply[$key]->page = $pageReply;
			}
			$paginationReply[$key]->limit = 5;
			$paginationReply[$key]->url = $this->url->link($data['reviews'][$i]['paginationReplyUrl'] , '&reviewThisPage=' . $data['reviewThisPage'] . '&pageReply={page}');
			$data['paginationReply'][$key] = $paginationReply[$key]->render();
			
			$data['resultsReply'][$key] = sprintf($this->language->get('text_pagination'), ($data['reviews'][$i]['reply_total']) ? (($pageReply - 1) * 5) + 1 : 0, ((($pageReply - 1) * 5) > ($data['reviews'][$i]['reply_total'] - 5)) ? $data['reviews'][$i]['reply_total'] : ((($pageReply - 1) * 5) + 5), $data['reviews'][$i]['reply_total'], ceil($data['reviews'][$i]['reply_total'] / 5));
		}

		$pagination = new Pagination();
		$pagination->total = $review_total;
		if (isset($this->request->get['pageReview'])){
			$pagination->page = $this->request->get['pageReview'];
		}else{
			$pagination->page = $page;
		}
		$pagination->limit = 5;
		$pagination->url = $this->url->link('product/reply_review/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($review_total - 5)) ? $review_total : ((($page - 1) * 5) + 5), $review_total, ceil($review_total / 5));
		$this->response->setOutput($this->load->view('product/reviewReply', $data));
	}	

	public function writeRewiew() {
		$this->load->language('product/product');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}

			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}

			// Captcha
			if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

				if ($captcha) {
					$json['error'] = $captcha;
				}
			}

			if (!isset($json['error'])) {
				$this->load->model('catalog/replyReview');

				$this->model_catalog_replyReview->addReview($this->request->get['review_id'], $this->request->post);

				$json['success'] = $this->language->get('text_success');
				
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		
	}

	
}
