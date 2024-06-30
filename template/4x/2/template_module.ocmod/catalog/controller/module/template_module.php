<?php

namespace Opencart\Catalog\Controller\Extension\TemplateModule\Module;

class TemplateModule extends \Opencart\System\Engine\Controller
{
	public function index(): string
	{
		$this->load->language('extension/template_module/module/template_module');

		$isShowDescription = $this->config->get('module_template_module_description_status');
		$isShowSubCategory = $this->config->get('module_template_module_subcategory_status');

		$data = [];

		$this->load->model('catalog/category');
		$this->load->model('catalog/product');

		$results = $this->model_catalog_category->getCategories();

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'))) {
				$image = $this->model_tool_image->resize(html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'), $this->config->get('module_template_module_image_width'), $this->config->get('module_template_module_image_height'));
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('module_template_module_image_width'), $this->config->get('module_template_module_image_height'));
			}

			$children = [];
			if ($isShowSubCategory) {
				$childrenResults = $this->model_catalog_category->getCategories($result['category_id']);
				foreach ($childrenResults as $child) {
					$children[] = [
						'name' => $child['name'],
						'href' => $this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&path=' . $result['category_id'] . '_' . $child['category_id']),
					];
				}
			}

			$category = [
				'category_id' => $result['category_id'],
				'parent_id' => $result['parent_id'],
				'name' => $result['name'],
				'description' => $result['description'] ? oc_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('config_product_description_length')) . '..' : '',
				'thumb' => $image,
				'href' => $this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&path=' . $result['category_id']),
				'isShowDescription' => $isShowDescription,
				'isShowSubCategory' => $isShowSubCategory,
				'children' => $children,
			];

			$data['categories'][] = $this->load->view('extension/template_module/module/ocn_category_thumb', $category);
		}

		return $this->load->view('extension/template_module/module/template_module', $data);
	}
}
