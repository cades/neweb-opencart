<?php
class AppController extends Controller {
  public $error = array(); 
  public $conf_language = '';
  public $conf_module_name = '';
  public $conf_extension = ''; // link use it
  public $text_strings_default = array(
    'heading_title',
    'text_enabled',
    'text_disabled',
    'text_content_top',
    'text_content_bottom',
    'text_column_left',
    'text_column_right',
    'entry_layout',
    'entry_limit',
    'entry_image',
    'entry_position',
    'entry_status',
    'entry_sort_order',
    'button_save',
    'button_cancel',
    'button_add_module',
    'button_remove',
  );
  public $config_data = array(); //this becomes available in our view
  public $conf_this_file = '';
  public $template = '';
  public $children = array(
    'common/header',
    'common/footer',
  );
  
  public function index() {   
    //Load the language file for this module
    $this->load->language($this->conf_language);

    //Set the title from the language file $_['heading_title'] string
    $this->document->setTitle($this->language->get('heading_title'));
		
    //Load the settings model. You can also add any other models you want to load here.
    $this->load->model('setting/setting');
		
    //Save the settings if the user has submitted the admin form (ie if someone has pressed save).
    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      $this->model_setting_setting->editSetting($this->conf_module_name, $this->request->post);		
					
      $this->session->data['success'] = $this->language->get('text_success');
						
      $this->redirect($this->url->link($this->conf_extension, 'token=' . $this->session->data['token'], 'SSL'));
    }

    //This is how the language gets pulled through from the language file.
    //
    // If you want to use any extra language items - ie extra text on your admin page for any reason,
    // then just add an extra line to the $text_strings array with the name you want to call the extra text,
    // then add the same named item to the $_[] array in the language file.
    //
    // 'my_module_example' is added here as an example of how to add - see admin/language/english/module/my_module.php for the
    // other required part.
		
    $text_strings = array_merge($this->text_strings_default, $this->text_strings);
    foreach ($text_strings as $text) {
      $this->data[$text] = $this->language->get($text);
    }
    //END LANGUAGE
		
    //The following code pulls in the required data from either config files or user
    //submitted data (when the user presses save in admin). Add any extra config data
    // you want to store.
    //
    // NOTE: These must have the same names as the form data in your my_module.tpl file
    //
    foreach ($this->config_data as $conf) {
      if (isset($this->request->post[$conf])) {
	$this->data[$conf] = $this->request->post[$conf];
      } else {
	$this->data[$conf] = $this->config->get($conf);
      }
    }

    // SELECTION-MENU 把列表傳給 view 以製作下拉式選單
    // 1. geo zone
    $this->load->model('localisation/geo_zone');
    $this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

    // 2. order status
    $this->load->model('localisation/order_status');
    $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
    // END SELECTION-MENU
    
    
    
    //This creates an error message. The error['warning'] variable is set by the call to function validate() in this controller (below)
    if (isset($this->error['warning'])) {
      $this->data['error_warning'] = $this->error['warning'];
    } else {
      $this->data['error_warning'] = '';
    }
		
    //SET UP BREADCRUMB TRAIL. YOU WILL NOT NEED TO MODIFY THIS UNLESS YOU CHANGE YOUR MODULE NAME.
    $this->data['breadcrumbs'] = array();

    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => false
    );

    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_payment'),
      'href'      => $this->url->link($this->conf_extension, 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => ' :: '
    );
		
    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link($this->conf_this_file, 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => ' :: '
    );
		
    $this->data['action'] = $this->url->link($this->conf_this_file, 'token=' . $this->session->data['token'], 'SSL');
		
    $this->data['cancel'] = $this->url->link($this->conf_extension, 'token=' . $this->session->data['token'], 'SSL');

    //Send the output.
    $this->response->setOutput($this->render());
  }
	
  /*
   * 
   * This function is called to ensure that the settings chosen by the admin user are allowed/valid.
   * You can add checks in here of your own.
   * 
   */
  private function validate() {
    if (!$this->user->hasPermission('modify', $this->conf_this_file)) {
      $this->error['warning'] = $this->language->get('error_permission');
    }
		
    if (!$this->error) {
      return TRUE;
    } else {
      return FALSE;
    }	
  }

}