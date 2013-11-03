<?php
/* Moneybrace online payment
 *
 * @version 1.0
 * @date 11/03/2012
 * @author george zheng <xinhaozheng@gmail.com>
 * @more info available on mzcart.com
 */
class ControllerPaymentNewebConvenienceStorePay extends Controller {
  public $index_base = 2000000000;
  
  protected function index() {
    $this->language->load('payment/neweb_convenience_store_pay');
    $this->data['button_confirm'] = $this->language->get('button_confirm');

    $this->load->model('checkout/order');
    $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

    $currency = $_SESSION['currency'];

    // form data
    $this->data['action']          = $this->actionurl();
    $this->data['merchantnumber']  = $this->config->get('neweb_convenience_store_pay_merchantacct');
    $this->data['ordernumber']     = $order_info['order_id'] + $this->index_base; // 加上offset (為了與信用卡付款訂單編號區隔)
    $this->data['amount']          = $this->currency->format($order_info['total'], $currency , false, false);
    $this->data['paymenttype']     = 'MMK';
    $this->data['paytitle']        = 'test';
    $this->data['payname']         = $this->customer->getLastName() . ' ' . $this->customer->getFirstName();
    $this->data['payphone']        = $this->customer->getTelephone();;
    $this->data['returnvalue']     = '0';
    $this->data['nexturl']         = $this->url->link('checkout/success');
    $this->data['code']            = $this->merchant_code();
    
    // text to user
    $this->data['text_payment']     = $this->language->get('text_payment');
    $this->data['text_instruction'] = $this->language->get('text_instruction');
    $this->data['text_description'] = $this->language->get('text_description');
    $this->data['text_total_title'] = $this->language->get('text_total_title');
    $this->data['text_total_desc']  = $this->language->get('text_total_desc'); 				
    $this->data['total']            = intval(round($order_info['total']));		

    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/neweb_convenience_store_pay.tpl')) {
      $this->template = $this->config->get('config_template') . '/template/payment/neweb_convenience_store_pay.tpl';
    } else {
      $this->template = 'default/template/payment/neweb_convenience_store_pay.tpl';
    }
 
    $this->render();
  }
  
  public function confirm() {
    $this->load->model('checkout/order');
    $this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('neweb_convenience_store_pay_processed_status_id'));
  }

  
  private function isInSandbox() {
    return $this->config->get('neweb_convenience_store_pay_env') == 'sandbox';
  }

  private function actionurl() {
    return $this->isInSandbox() ? 'http://maple2.neweb.com.tw/CashSystemFrontEnd/Payment' : 'https://aquarius.neweb.com.tw/CashSystemFrontEnd/Payment';
  }

  private function merchant_code() {
    return $this->isInSandbox() ? 'abcd1234' : $this->config->get('neweb_convenience_store_pay_merchant_code');
  }
  
  private function log_and_die($text, $data) {
      $this->log->write($text.', data = ' . var_export($data, true));
      header('HTTP/1.0 405 Method Not Allowed', true, 405);
      die($text);
  }

}
?>

