<?php
/* Moneybrace online payment
 *
 * @version 1.0
 * @date 11/03/2012
 * @author george zheng <xinhaozheng@gmail.com>
 * @more info available on mzcart.com
 */
class ControllerPaymentNeweb extends Controller {
  public $index_base = 1000000000;
  
  protected function index() {
    $this->language->load('payment/neweb');
    $this->data['button_confirm'] = $this->language->get('button_confirm');

    $this->load->model('checkout/order');
    $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

    $currency = $_SESSION['currency'];

    // form data
    $this->data['action']          = $this->actionurl();
    $this->data['MerchantNumber']  = $this->config->get('neweb_merchantacct');
    $this->data['OrderNumber']     = $order_info['order_id'] + $this->index_base; // 加上offset (為了與官網訂單區隔)
    $this->data['Amount']          = $this->currency->format($order_info['total'], $currency , false, false);
    $this->data['OrgOrderNumber']  = date('His') . $this->session->data['order_id'];
    $this->data['ApproveFlag']     = 1;
    $this->data['DepositFlag']     = 0;
    $this->data['Englishmode']     = 0;
    $this->data['iphonepage']      = 0;
    $this->data['OrderURL']        = $this->url->link('payment/neweb/callback');
    $this->data['ReturnURL']       = $this->url->link('payment/neweb/receive');
    $this->data['code']            = $this->merchant_code();
    
    // text to user
    $this->data['text_payment']     = $this->language->get('text_payment');
    $this->data['text_instruction'] = $this->language->get('text_instruction');
    $this->data['text_description'] = $this->language->get('text_description');
    $this->data['text_total_title'] = $this->language->get('text_total_title');
    $this->data['text_total_desc'] = $this->language->get('text_total_desc'); 				
    $this->data['total'] = intval(round($order_info['total']));		

    //$this->data['callbackurl'] = $this->url->link('payment/neweb/callback');
    //$this->data['browserbackurl'] = $this->url->link('checkout/success');
    //$this->data['accessurl'] = 'https://payment.moneybrace.coms';
    //$this->data['orderdate'] = date('YmdHis');
    //$this->data['currency'] = $order_info['currency_code'];
 
    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/neweb.tpl')) {
      $this->template = $this->config->get('config_template') . '/template/payment/neweb.tpl';
    } else {
      $this->template = 'default/template/payment/neweb.tpl';
    }
 
    $this->render();
  }
  
  public function callback() {
    $data = array_merge($this->request->post,$this->request->get);
    $must_have_keys = array('PRC', 'SRC', 'MerchantNumber', 'OrderNumber', 'ApprovalCode', 'BankResponseCode', 'Amount', 'CheckSum');
    
    foreach($must_have_keys as $key) {
      if (!isset($data[$key]))
	$this->log_and_die('Invalid access', $data);
    }
    
    foreach ($data as $key => $value) {
      ${$key} = $value;
    }    
    
    $this->load->model('checkout/order');

    $signature = md5($MerchantNumber.$OrderNumber.$PRC.$SRC.$this->merchant_code().$Amount);
    if ( $CheckSum != $signature) {
      $order_status_id = $this->config->get('neweb_pending_status_id');
      $this->model_checkout_order->confirm($order_id, $order_status_id); // set order status to pending for admin to resolve.
      $this->log_and_die('Data validate failed', $data);
    }

    $order_id = $OrderNumber - $this->index_base; // 扣掉offset
    $order_info = $this->model_checkout_order->getOrder($order_id);
    if (!$order_info) {
      $this->log_and_die('Invalid order id', $data);
    }

    if ($PRC != 0) {
      $this->log_and_die('PRC != 0 : Transaction fail', $data);
    }

    //payment was made succ
    echo 'Success';
    $order_status_id = $this->config->get('neweb_processed_status_id');
    if (!$order_info['order_status_id'] || $order_info['order_status_id'] != $order_status_id) {
      $this->model_checkout_order->confirm($order_id, $order_status_id);
    } else {
      $this->model_checkout_order->update($order_id, $order_status_id);
    }

  }

  public function receive() {
    /*
      先把工作告一段落, 去寫組語作業.
      看來藍新不會因為回傳405而中止, 那麼在第一次callback檢查也沒什麼意義了.
      又不太想把第一次callback的結果存在db (session也無法用)
      因此其實錯誤檢查應該在這裡做才對. 如果完全沒問題, redirect($this->url->link('checkout/success'))
      如有問題, 則秀出錯誤訊息, 並設定正確的訂單狀態.
     */

    $data = array_merge($this->request->post,$this->request->get);
    $must_have_keys = array('final_result', 'P_MerchantNumber', 'P_OrderNumber', 'P_Amount', 'P_CheckSum',
			    'final_return_PRC', 'final_return_SRC',
			    'final_return_ApproveCode', 'final_return_BankRC', 'final_return_BatchNumber');

    /*
     * 第一階段：安全性檢查。
     */

    // 確認 POST 參數符合文件規格
    foreach($must_have_keys as $key) {
      if (!isset($data[$key]))
	$this->log_and_die('Invalid access', $data);
    }
    
    foreach ($data as $key => $value) {
      ${$key} = $value;
    }
    
    $this->load->model('checkout/order');

    // 檢查 checksum
    $signature = md5($P_MerchantNumber.$P_OrderNumber.$final_result.$final_return_PRC.
		     $this->merchant_code().$final_return_SRC.$P_Amount);
    if ($P_CheckSum != $signature) {
      $order_status_id = $this->config->get('neweb_pending_status_id');
      $this->model_checkout_order->confirm($order_id, $order_status_id); // set order status to pending for admin to resolve.
      $this->log_and_die('Data validate failed', $data);
    }

    // 確認訂單存在
    $order_id = $P_OrderNumber - $this->index_base; // 扣掉offset
    $order_info = $this->model_checkout_order->getOrder($order_id);
    if (!$order_info) {
      $this->log_and_die('Invalid order id', $data);
    }

    
    /*
     * 第二階段：處理交易結果
     * 處理各式 PRC/SRC 狀況。
     */

    // 交易失敗
    if (!$final_result || $final_return_PRC != 0) {
      $this->redirect($this->url->link('checkout/neweb_failure' .
				       '&PRC='    . $final_return_PRC .
				       '&SRC='    . $final_return_SRC .
				       '&BankRC=' . $final_return_BankRC));
      $this->log_and_die('PRC != 0 : Transaction fail', $data);
    }

    // 交易成功，更新訂單狀態
    $order_status_id = $this->config->get('neweb_processed_status_id');
    if (!$order_info['order_status_id'] || $order_info['order_status_id'] != $order_status_id) {
      $this->model_checkout_order->confirm($order_id, $order_status_id);
    } else {
      $this->model_checkout_order->update($order_id, $order_status_id);
    }

    // 重導向，交給系統內建程式收拾（清空購物車一類的事）
    $this->redirect($this->url->link('checkout/success'));
  }
  
  /*
   * readibility helper functions
   */
  private function isInSandbox() {
    return $this->config->get('neweb_env') == 'sandbox';
  }

  private function actionurl() {
    return $this->isInSandbox() ? 'https://maple2.neweb.com.tw/NewebmPP/cdcard.jsp' : 'https://mpp.neweb.com.tw/NewebmPP/cdcard.jsp';
  }

  private function merchant_code() {
    return $this->isInSandbox() ? 'abcd1234' : $this->config->get('neweb_merchant_code');
  }
  
  private function log_and_die($text, $data) {
      $this->log->write($text.', data = ' . var_export($data, true));
      header('HTTP/1.0 405 Method Not Allowed', true, 405);
      die($text);
  }

}
?>

