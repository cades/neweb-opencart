<?php
require_once(DIR_APPLICATION . 'controller/AppController.php');

class ControllerPaymentNeweb extends AppController {
  public $conf_language = 'payment/neweb';
  public $conf_module_name = 'neweb';
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
    'neweb_merchantacct',
    'neweb_merchant_code',
    'neweb_cert',
    'neweb_env',                  // 測試or正式環境
    'neweb_pending_status_id',
    'neweb_processed_status_id',
    'neweb_geo_zone_id',
    'neweb_status',               // 啟用or停用
    'neweb_sort_order',
  );
  public $conf_this_file = 'payment/neweb';
  public $template = 'payment/neweb.tpl';

  
  private function validate() {
  }
}
?>
