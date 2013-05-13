<?php
class ControllerCheckoutNewebFailure extends Controller { 
  public function index() { 	
    $this->language->load('checkout/neweb_failure');
		
    $this->document->setTitle($this->language->get('heading_title'));
		
    $this->data['breadcrumbs'] = array(); 

    $this->data['breadcrumbs'][] = array(
      'href'      => $this->url->link('common/home'),
      'text'      => $this->language->get('text_home'),
      'separator' => false
    ); 
		
    $this->data['breadcrumbs'][] = array(
      'href'      => $this->url->link('checkout/cart'),
      'text'      => $this->language->get('text_basket'),
      'separator' => $this->language->get('text_separator')
    );
				
    $this->data['breadcrumbs'][] = array(
      'href'      => $this->url->link('checkout/checkout', '', 'SSL'),
      'text'      => $this->language->get('text_checkout'),
      'separator' => $this->language->get('text_separator')
    );	
					
    $this->data['heading_title'] = $this->language->get('heading_title');

    // setup $PRC, $SRC, and $BankRC
    extract(array_merge($this->request->post,$this->request->get));
    
    if ($PRC == 15 && $SRC == 1018) {
      $this->data['text_message'] = '連線逾時，請稍後再試';
    } else if ($PRC == 34 && $SRC == 171) {
      $bank_codes = explode("/", $BankRC);
      $major_bank_code = intval($bank_codes[1]);
      if (in_array($major_bank_code, array(1, 2))) {
	$this->data['text_message'] = '交易失敗，請與發卡行聯絡';
      } else if ($major_bank_code == 51) {
	$this->data['text_message'] = '可用餘額不足';
      } else if (in_array($major_bank_code, array(4, 5, 14, 54))) {
	$this->data['text_message'] = '資料錯誤，請重新再試';
      } else if ($major_bank_code == 57) {
	$this->data['text_message'] = '交易失敗，請與發卡行聯絡。發卡行未提供持卡人該項交易之功能';
      } else {
	$this->data['text_message'] = '交易失敗，請與商城聯絡';
      }
    } else if ($PRC == 8 && $SRC == 204) {
      $this->data['text_message'] = '訂單重複，請重新下單';
    } else if ($SRC == 1015) {
      $this->data['text_message'] = '卡號資料有誤，請重新下單';
    } else if ($PRC == 7 && $SRC == 117) {
      $this->data['text_message'] = '金額格式有誤，請重新下單。如問題再次發生，請聯絡店家處理。';
    } else {
      $this->data['text_message'] = '交易系統異常，請稍後再試。請保留網址及交易時間，並與我們聯繫。';
    }

    $this->data['button_continue'] = $this->language->get('button_continue');

    $this->data['continue'] = $this->url->link('common/home');

    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
      $this->template = $this->config->get('config_template') . '/template/common/success.tpl';
    } else {
      $this->template = 'default/template/common/success.tpl';
    }
		
    $this->children = array(
      'common/column_left',
      'common/column_right',
      'common/content_top',
      'common/content_bottom',
      'common/footer',
      'common/header'			
    );
				
    $this->response->setOutput($this->render());
  }
}
?>
