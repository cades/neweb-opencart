<?php
require_once(DIR_APPLICATION . 'controller/AppController.php');

class ControllerPaymentNewebConvenienceStorePay extends AppController {
  public $conf_language = 'payment/neweb_convenience_store_pay';
  public $conf_module_name = 'neweb_convenience_store_pay';
  public $conf_extension = 'extension/payment';
  public $text_strings = array( // translations strings
    'entry_merchantacct',
    'entry_merchant_code',
    'entry_cert',
    'entry_env',
    'entry_pending_status',
    'entry_processed_status',
    'entry_geo_zone',
    'error_merchantacct',
    'error_cert',
    'text_all_zones',
    'text_env_regular',
    'text_env_sandbox',
  );
  public $config_data = array( // user configurable values. Would be save into database.
    'neweb_convenience_store_pay_merchantacct',
    'neweb_convenience_store_pay_merchant_code',
    'neweb_convenience_store_pay_cert',
    'neweb_convenience_store_pay_env',                  // 測試or正式環境
    'neweb_convenience_store_pay_pending_status_id',
    'neweb_convenience_store_pay_processed_status_id',
    'neweb_convenience_store_pay_geo_zone_id',
    'neweb_convenience_store_pay_status',               // 啟用or停用
    'neweb_convenience_store_pay_sort_order',
  );
  public $conf_this_file = 'payment/neweb_convenience_store_pay';
  public $template = 'payment/neweb_convenience_store_pay.tpl';

  
  private function validate() {
  }
}
?>
