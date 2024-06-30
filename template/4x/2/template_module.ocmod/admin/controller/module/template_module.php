<?php

namespace Opencart\Admin\Controller\Extension\TemplateModule\Module;

class TemplateModule extends \Opencart\System\Engine\Controller
{
	public function index(): void
	{
		$this->load->language('extension/template_module/module/template_module');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [
			[
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
			],
			[
				'text' => $this->language->get('text_extension'),
				'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
			],
			[
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/template_module/module/template_module', 'user_token=' . $this->session->data['user_token'])
			]
		];

		$data['save'] = $this->url->link('extension/template_module/module/template_module.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

		$data['module_template_module_status'] = $this->config->get('module_template_module_status');
		$data['module_template_module_description_status'] = $this->config->get('module_template_module_description_status');
		$data['module_template_module_subcategory_status'] = $this->config->get('module_template_module_subcategory_status');

		if ($this->config->get('module_template_module_image_width')) {
			$data['module_template_module_image_width'] = $this->config->get('module_template_module_image_width');
		} else {
			$data['module_template_module_image_width'] = 80;
		}

		if ($this->config->get('module_template_module_image_height')) {
			$data['module_template_module_image_height'] = $this->config->get('module_template_module_image_height');
		} else {
			$data['module_template_module_image_height'] = 80;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/template_module/module/template_module', $data));
	}

	public function save(): void
	{
		$this->load->language('extension/template_module/module/template_module');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/template_module/module/template_module')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['module_template_module_image_width'] || !$this->request->post['module_template_module_image_height']) {
			$json['error']['image_category'] = $this->language->get('error_image_category');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('module_template_module', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function install(): void
	{
		if ($this->user->hasPermission('modify', 'extension/module')) {
			$this->load->model('extension/template_module/module/template_module');

			$this->model_extension_template_module_module_template_module->install();

			$this->load->model('setting/setting');
			$data = [
				'module_template_module_status' => 0,
				'module_template_module_description_status' => 0,
				'module_template_module_subcategory_status' => 0,
				'module_template_module_image_width' => 240,
				'module_template_module_image_height' => 240
			];
			$this->model_setting_setting->editSetting('module_template_module', $data);
		}
	}

	public function uninstall(): void
	{
		if ($this->user->hasPermission('modify', 'extension/module')) {
			$this->load->model('extension/template_module/module/template_module');

			$this->model_extension_template_module_module_template_module->uninstall();
		}
	}
}
