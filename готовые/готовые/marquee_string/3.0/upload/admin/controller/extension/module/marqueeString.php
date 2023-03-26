<?php
class ControllerExtensionModuleMarqueeString extends Controller
{
  private $error = array();

  public function index()
  {
    $this->load->language('extension/module/marqueeString');
    $this->document->setTitle($this->language->get('heading_title'));
    $this->load->model('setting/module');
	
    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      if (!isset($this->request->get['module_id'])) {
        $this->model_setting_module->addModule('marqueeString', $this->request->post);
      } else {
        $this->model_setting_module->editModule( $this->request->get['module_id'], $this->request->post );
      }
      $this->session->data['success'] = $this->language->get('text_success');
      $this->response->redirect($this->url->link( 'marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true ));
    }
    $data['heading_title'] = $this->language->get('heading_title');
    $data['button_save'] = $this->language->get('button_save');
    $data['button_cancel'] = $this->language->get('button_cancel');
    if (isset($this->error['warning'])) {
      $data['error_warning'] = $this->error['warning'];
    } else {
      $data['error_warning'] = '';
    }

    $data['breadcrumbs'] = array();
	
    $data['breadcrumbs'][] = array('text' =>
    $this->language->get('text_home'), 'href' =>
    $this->url->link('common/dashboard', 'user_token=' .
      $this->session->data['user_token'], true));
	  
    $data['breadcrumbs'][] = array( 'text' => $this->language->get('text_extension'), 'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true) );
	
    if (!isset($this->request->get['module_id'])) {
      $data['breadcrumbs'][] = array( 'text' => $this->language->get('heading_title'), 'href' => $this->url->link('extension/module/marqueeString', 'user_token=' . $this->session->data['user_token'], true)
      );
    } else {
      $data['breadcrumbs'][] = array('text' => $this->language->get('heading_title'), 'href' => $this->url->link('extension/module/marqueeString', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true));
    }
    if (!isset($this->request->get['module_id'])) {
      $data['action'] = $this->url->link('extension/module/marqueeString', 'user_token=' . $this->session->data['user_token'], true);
    } else {
      $data['action'] = $this->url->link('extension/module/marqueeString', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
    }
    $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
    if ( isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST') ) {
      $module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
    }
	
	$this->load->model('localisation/language');

	$data['languages'] = $this->model_localisation_language->getLanguages();
		
    if (isset($this->request->post['name'])) {
      $data['name'] = $this->request->post['name'];
    } elseif (!empty($module_info)) {
      $data['name'] =
        $module_info['name'];
    } else {
      $data['name'] = '';
    }
	
    if (isset($this->request->post['string'])) {
      $data['string'] = $this->request->post['string'];
    } elseif (!empty($module_info)) {
      $data['string'] = $module_info['string'];
    } else {
      $data['string'] = '';
    }
	
    if (isset($this->request->post['delayBeforeStart'])) {
      $data['delayBeforeStart'] = $this->request->post['delayBeforeStart'];
    } elseif (!empty($module_info)) {
      $data['delayBeforeStart'] = $module_info['delayBeforeStart'];
    } else {
      $data['delayBeforeStart'] = '';
    }
	
    if (isset($this->request->post['direction'])) {
      $data['direction'] = $this->request->post['direction'];
    } elseif (!empty($module_info)) {
      $data['direction'] = $module_info['direction'];
    } else {
      $data['direction'] = '';
    }
	
    if (isset($this->request->post['duplicated'])) {
      $data['duplicated'] = $this->request->post['duplicated'];
    } elseif (!empty($module_info)) {
      $data['duplicated'] = $module_info['duplicated'];
    } else {
      $data['duplicated'] = '';
    }
	
    if (isset($this->request->post['duration'])) {
      $data['duration'] = $this->request->post['duration'];
    } elseif (!empty($module_info)) {
      $data['duration'] = $module_info['duration'];
    } else {
      $data['duration'] = '';
    }
	
    if (isset($this->request->post['speed'])) {
      $data['speed'] = $this->request->post['speed'];
    } elseif (!empty($module_info)) {
      $data['speed'] = $module_info['speed'];
    } else {
      $data['speed'] = '';
    }
	
    if (isset($this->request->post['gap'])) {
      $data['gap'] = $this->request->post['gap'];
    } elseif (!empty($module_info)) {
      $data['gap'] =
        $module_info['gap'];
    } else {
      $data['gap'] = '';
    }
	
    if (isset($this->request->post['pauseOnHover'])) {
      $data['pauseOnHover'] = $this->request->post['pauseOnHover'];
    } elseif (!empty($module_info)) {
      $data['pauseOnHover'] = $module_info['pauseOnHover'];
    } else {
      $data['pauseOnHover'] = '';
    }
	
    if (isset($this->request->post['pauseOnCycle'])) {
      $data['pauseOnCycle'] = $this->request->post['pauseOnCycle'];
    } elseif (!empty($module_info)) {
      $data['pauseOnCycle'] = $module_info['pauseOnCycle'];
    } else {
      $data['pauseOnCycle'] = '';
    }
	
    if (isset($this->request->post['startVisible'])) {
      $data['startVisible'] = $this->request->post['startVisible'];
    } elseif (!empty($module_info)) {
      $data['startVisible'] = $module_info['startVisible'];
    } else {
      $data['startVisible'] = '';
    }
	
    if (isset($this->request->post['width'])) {
      $data['width'] = $this->request->post['width'];
    } elseif (!empty($module_info)) {
      $data['width'] = $module_info['width'];
    } else {
      $data['width'] = 200;
    }
	
    if (isset($this->request->post['height'])) {
      $data['height'] = $this->request->post['height'];
    } elseif (!empty($module_info)) {
      $data['height'] = $module_info['height'];
    } else {
      $data['height'] = 200;
    }
	
    if (isset($this->request->post['status'])) {
      $data['status'] = $this->request->post['status'];
    } elseif (!empty($module_info)) {
      $data['status'] = $module_info['status'];
    } else {
      $data['status'] = '';
    }
	
    if (isset($this->request->post['textBg'])) {
      $data['textBg'] = $this->request->post['textBg'];
    } elseif (!empty($module_info)) {
      $data['textBg'] = $module_info['textBg'];
    } else {
      $data['textBg'] = '';
    }
	
    if (isset($this->request->post['fontSize'])) {
      $data['fontSize'] = $this->request->post['fontSize'];
    } elseif (!empty($module_info)) {
      $data['fontSize'] = $module_info['fontSize'];
    } else {
      $data['fontSize'] = '14';
    }
	
	if (isset($this->request->post['amount'])) {
      $data['amount'] = $this->request->post['amount'];
    } elseif (!empty($module_info)) {
      $data['amount'] = $module_info['amount'];
    } else {
      $data['amount'] = '';
    }
	
	if (isset($this->request->post['textColor'])) {
      $data['textColor'] = $this->request->post['textColor'];
    } elseif (!empty($module_info)) {
      $data['textColor'] = $module_info['textColor'];
    } else {
      $data['textColor'] = '';
    }
	
	if (isset($this->request->post['css'])) {
      $data['css'] = $this->request->post['css'];
    } elseif (!empty($module_info)) {
      $data['css'] = $module_info['css'];
    } else {
      $data['css'] = '';
    }
	
    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');
    $this->response->setOutput($this->load->view('extension/module/marqueeString', $data ));
  }
  
  protected function validate()
  {
    if (!$this->user->hasPermission('modify', 'extension/module/marqueeString')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }

    return !$this->error;
  }
}
