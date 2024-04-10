<?php
class ControllerExtensionModuleGentestimonials extends Controller
{
	private $error = array();

	public function install()
	{
		$this->load->model('extension/module/gentestimonials');
		$this->model_extension_module_gentestimonials->install();
	}
	public function uninstall()
	{
		$this->load->model('extension/module/gentestimonials');
		$this->model_extension_module_gentestimonials->uninstall();
	}

	public function index()
	{
		$this->load->language('extension/module/gentestimonials');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/module');
		$this->load->model('extension/module/gentestimonials');
		$this->document->addStyle('view/stylesheet/gentestimonials.css');

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

		$data['entry_display_avatar'] = $this->language->get('entry_display_avatar');

		$data['entry_display_client'] = $this->language->get('entry_display_client');
		$data['entry_display_rating'] = $this->language->get('entry_display_rating');
		$data['entry_display_date'] = $this->language->get('entry_display_date');

		$data['entry_direction'] = $this->language->get('entry_direction');
		$data['entry_effect'] = $this->language->get('entry_effect');
		$data['entry_enabled'] = $this->language->get('entry_enabled');
		$data['entry_followFinger'] = $this->language->get('entry_followFinger');
		$data['entry_loop'] = $this->language->get('entry_loop');
		$data['entry_preloadImages'] = $this->language->get('entry_preloadImages');
		$data['entry_longSwipesMs'] = $this->language->get('entry_longSwipesMs');
		$data['entry_autoplay'] = $this->language->get('entry_autoplay');
		$data['entry_viewnavigation'] = $this->language->get('entry_viewnavigation');
		$data['entry_class_image'] = $this->language->get('entry_class_image');


		$data['entry_spaceBetween'] = $this->language->get('entry_spaceBetween');
		$data['entry_spaceBetween320'] = $this->language->get('entry_spaceBetween320');
		$data['entry_spaceBetween425'] = $this->language->get('entry_spaceBetween425');
		$data['entry_spaceBetween768'] = $this->language->get('entry_spaceBetween768');
		$data['entry_spaceBetween1024'] = $this->language->get('entry_spaceBetween1024');

		$data['text_template_custom'] = $this->language->get('text_template_custom');
		$data['text_template_build'] = $this->language->get('text_template_build');

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
		$data['text_circle'] = $this->language->get('text_circle');
		$data['text_square'] = $this->language->get('text_square');

		$data['tab_setting_swiper'] = $this->language->get('tab_setting_swiper');
		$data['tab_setting_viev_slider'] = $this->language->get('tab_setting_viev_slider');

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
			$data['template'] = '1';
		}
		if (isset($this->request->post['display_date'])) {
			$data['display_date'] = $this->request->post['display_date'];
		} elseif (!empty($module_info)) {
			$data['display_date'] = $module_info['display_date'];
		} else {
			$data['display_date'] = '1';
		}
		if (isset($this->request->post['display_avatar'])) {
			$data['display_avatar'] = $this->request->post['display_avatar'];
		} elseif (!empty($module_info)) {
			$data['display_avatar'] = $module_info['display_avatar'];
		} else {
			$data['display_avatar'] = '1';
		}
		if (isset($this->request->post['display_client'])) {
			$data['display_client'] = $this->request->post['display_client'];
		} elseif (!empty($module_info)) {
			$data['display_client'] = $module_info['display_client'];
		} else {
			$data['display_client'] = '1';
		}
		if (isset($this->request->post['display_rating'])) {
			$data['display_rating'] = $this->request->post['display_rating'];
		} elseif (!empty($module_info)) {
			$data['display_rating'] = $module_info['display_rating'];
		} else {
			$data['display_rating'] = '1';
		}
		if (isset($this->request->post['display_userRating'])) {
			$data['display_userRating'] = $this->request->post['display_userRating'];
		} elseif (!empty($module_info)) {
			$data['display_userRating'] = $module_info['display_userRating'];
		} else {
			$data['display_userRating'] = '1';
		}
		if (isset($this->request->post['display_answer'])) {
			$data['display_answer'] = $this->request->post['display_answer'];
		} elseif (!empty($module_info)) {
			$data['display_answer'] = $module_info['display_answer'];
		} else {
			$data['display_answer'] = '1';
		}
		if (isset($this->request->post['class_image'])) {
			$data['class_image'] = $this->request->post['class_image'];
		} elseif (!empty($module_info)) {
			$data['class_image'] = $module_info['class_image'];
		} else {
			$data['class_image'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}
		if (isset($this->request->post['add_testimonial'])) {
			$data['add_testimonial'] = $this->request->post['add_testimonial'];
		} elseif (!empty($module_info)) {
			$data['add_testimonial'] = $module_info['add_testimonial'];
		} else {
			$data['add_testimonial'] = '';
		}
		if (isset($this->request->post['all_testimonial'])) {
			$data['all_testimonial'] = $this->request->post['all_testimonial'];
		} elseif (!empty($module_info)) {
			$data['all_testimonial'] = $module_info['all_testimonial'];
		} else {
			$data['all_testimonial'] = '';
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
		if (isset($this->request->post['count_slider'])) {
			$data['count_slider'] = $this->request->post['count_slider'];
		} elseif (!empty($module_info)) {
			$data['count_slider'] = $module_info['count_slider'];
		} else {
			$data['count_slider'] = '5';
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

		if (isset($this->request->post['autoplay'])) {
			$data['autoplay'] = $this->request->post['autoplay'];
		} elseif (!empty($module_info)) {
			$data['autoplay'] = $module_info['autoplay'];
		} else {
			$data['autoplay'] = 'false';
		}
		if (isset($this->request->post['status_newTestimonial'])) {
			$data['status_newTestimonial'] = $this->request->post['status_newTestimonial'];
		} elseif (!empty($module_info)) {
			$data['status_newTestimonial'] = $module_info['status_newTestimonial'];
		} else {
			$data['status_newTestimonial'] = '';
		}

		$data['templates'] = array(
			'0' => $data['text_template_build'],
			'1' => $data['text_template_custom']
		);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/gentestimonials', $data));
	}

	protected function validate()
	{
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