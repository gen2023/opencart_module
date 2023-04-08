<?php
class ControllerExtensionModuleGentestimonials extends Controller {
	private $error = array();
	
		public function index() {
		$this->load->language('extension/module/gentestimonials');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/module');
		$this->load->model('extension/module/gentestimonials');	
		$this->model_extension_module_gentestimonials->install();

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('gentestimonials', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}
		
		$data['entry_template'] = $this->language->get('entry_template');
		$data['entry_viewTitle'] = $this->language->get('entry_viewTitle');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_display_company'] = $this->language->get('entry_display_company');
		$data['entry_display_avatar'] = $this->language->get('entry_display_avatar');
		$data['entry_display_quotes'] = $this->language->get('entry_display_quotes');
		$data['entry_display_client'] = $this->language->get('entry_display_client');
		$data['entry_display_job'] = $this->language->get('entry_display_job');
		$data['entry_direction'] = $this->language->get('entry_direction');
		$data['entry_effect'] = $this->language->get('entry_effect');
		$data['entry_enabled'] = $this->language->get('entry_enabled');
		$data['entry_followFinger'] = $this->language->get('entry_followFinger');
		$data['entry_loop'] = $this->language->get('entry_loop');
		$data['entry_preloadImages'] = $this->language->get('entry_preloadImages');
		$data['entry_longSwipesMs'] = $this->language->get('entry_longSwipesMs');
		$data['entry_spaceBetween'] = $this->language->get('entry_spaceBetween');
		$data['entry_autoplay'] = $this->language->get('entry_autoplay');
		$data['entry_viewnavigation'] = $this->language->get('entry_viewnavigation');
		$data['entry_viewPagination'] = $this->language->get('entry_viewPagination');
		
		$data['text_template2'] = $this->language->get('text_template2');
		$data['text_template1'] = $this->language->get('text_template1');
		$data['text_template3'] = $this->language->get('text_template3');
		$data['text_template4'] = $this->language->get('text_template4');
		$data['text_template5'] = $this->language->get('text_template5');
		$data['text_extension'] = $this->language->get('text_extension');
		$data['text_vertical'] = $this->language->get('text_vertical');
		$data['text_slide'] = $this->language->get('text_slide');
		$data['text_fade'] = $this->language->get('text_fade');
		$data['text_cube'] = $this->language->get('text_cube');
		$data['text_coverflow'] = $this->language->get('text_coverflow');
		$data['text_flip'] = $this->language->get('text_flip');
		$data['text_creative'] = $this->language->get('text_creative');
		$data['text_cards'] = $this->language->get('text_cards');
		$data['text_horizontal'] = $this->language->get('text_horizontal');
		
		$data['tab_setting_swiper'] = $this->language->get('tab_setting_swiper');
		
		$data['help_followFinger'] = $this->language->get('help_followFinger');
		$data['help_preloadImages'] = $this->language->get('help_preloadImages');
		$data['help_longSwipesMs'] = $this->language->get('help_longSwipesMs');
		$data['help_spaceBetween'] = $this->language->get('help_spaceBetween');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/gentestimonials', 'user_token=' . $this->session->data['user_token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/gentestimonials', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/gentestimonials', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/gentestimonials', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}
		if (isset($this->request->post['viewTitle'])) {
			$data['viewTitle'] = $this->request->post['viewTitle'];
		} elseif (!empty($module_info)) {
			$data['viewTitle'] = $module_info['viewTitle'];
		} else {
			$data['viewTitle'] = '';
		}
		
		if (isset($this->request->post['template'])) {
			$data['template'] = $this->request->post['template'];
		} elseif (!empty($module_info)) {
			$data['template'] = $module_info['template'];
		} else {
			$data['template'] = '';
		}	
		if (isset($this->request->post['display_avatar'])) {
			$data['display_avatar'] = $this->request->post['display_avatar'];
		} elseif (!empty($module_info)) {
			$data['display_avatar'] = $module_info['display_avatar'];
		} else {
			$data['display_avatar'] = '';
		}
		if (isset($this->request->post['display_quotes'])) {
			$data['display_quotes'] = $this->request->post['display_quotes'];
		} elseif (!empty($module_info)) {
			$data['display_quotes'] = $module_info['display_quotes'];
		} else {
			$data['display_quotes'] = '';
		}
		if (isset($this->request->post['display_client'])) {
			$data['display_client'] = $this->request->post['display_client'];
		} elseif (!empty($module_info)) {
			$data['display_client'] = $module_info['display_client'];
		} else {
			$data['display_client'] = '';
		}
		if (isset($this->request->post['display_job'])) {
			$data['display_job'] = $this->request->post['display_job'];
		} elseif (!empty($module_info)) {
			$data['display_job'] = $module_info['display_job'];
		} else {
			$data['display_job'] = '';
		}
		if (isset($this->request->post['display_company'])) {
			$data['display_company'] = $this->request->post['display_company'];
		} elseif (!empty($module_info)) {
			$data['display_company'] = $module_info['display_company'];
		} else {
			$data['display_company'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}
		
		if (isset($this->request->post['direction'])) {
			$data['direction'] = $this->request->post['direction'];
		} elseif (!empty($module_info)) {
			$data['direction'] = $module_info['direction'];
		} else {
			$data['direction'] = '';
		}
		if (isset($this->request->post['effect'])) {
			$data['effect'] = $this->request->post['effect'];
		} elseif (!empty($module_info)) {
			$data['effect'] = $module_info['effect'];
		} else {
			$data['effect'] = '';
		}
		if (isset($this->request->post['enabled'])) {
			$data['enabled'] = $this->request->post['enabled'];
		} elseif (!empty($module_info)) {
			$data['enabled'] = $module_info['enabled'];
		} else {
			$data['enabled'] = '';
		}
		if (isset($this->request->post['followFinger'])) {
			$data['followFinger'] = $this->request->post['followFinger'];
		} elseif (!empty($module_info)) {
			$data['followFinger'] = $module_info['followFinger'];
		} else {
			$data['followFinger'] = '';
		}
		if (isset($this->request->post['longSwipesMs'])) {
			$data['longSwipesMs'] = $this->request->post['longSwipesMs'];
		} elseif (!empty($module_info)) {
			$data['longSwipesMs'] = $module_info['longSwipesMs'];
		} else {
			$data['longSwipesMs'] = '300';
		}
		if (isset($this->request->post['loop'])) {
			$data['loop'] = $this->request->post['loop'];
		} elseif (!empty($module_info)) {
			$data['loop'] = $module_info['loop'];
		} else {
			$data['loop'] = '';
		}
		if (isset($this->request->post['preloadImages'])) {
			$data['preloadImages'] = $this->request->post['preloadImages'];
		} elseif (!empty($module_info)) {
			$data['preloadImages'] = $module_info['preloadImages'];
		} else {
			$data['preloadImages'] = '';
		}
		if (isset($this->request->post['spaceBetween'])) {
			$data['spaceBetween'] = $this->request->post['spaceBetween'];
		} elseif (!empty($module_info)) {
			$data['spaceBetween'] = $module_info['spaceBetween'];
		} else {
			$data['spaceBetween'] = '0';
		}
		if (isset($this->request->post['autoplay'])) {
			$data['autoplay'] = $this->request->post['autoplay'];
		} elseif (!empty($module_info)) {
			$data['autoplay'] = $module_info['autoplay'];
		} else {
			$data['autoplay'] = 'false';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/gentestimonials', $data));
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/gentestimonials')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}
}
	

?>