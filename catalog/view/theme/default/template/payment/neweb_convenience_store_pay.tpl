<table>
  <tr>
    <td><?php echo $text_payment ;?></td>
  </tr>
  <tr>
    <td><?php echo $text_instruction ;?><?php echo $text_description ;?></td>    
  </tr>
  <tr>
    <td><?php echo $text_total_title . $total . $text_total_desc;?></td>    
  </tr>
</table>
<form action="<?php echo $action; ?>" method="post" id="order-form">
  <input type="hidden" name="merchantnumber" value="<?php echo $merchantnumber; ?>">
  <input type="hidden" name="ordernumber"    value="<?php echo $ordernumber; ?>">
  <input type="hidden" name="amount"         value="<?php echo $amount; ?>">
  <input type="hidden" name="paymenttype"    value="<?php echo $paymenttype; ?>">
  <input type="hidden" name="paytitle"       value="<?php echo $paytitle; ?>">
  <input type="hidden" name="payname"        value="<?php echo $payname; ?>">
  <input type="hidden" name="payphone"       value="<?php echo $payphone; ?>">
  <input type="hidden" name="returnvalue"    value="<?php echo $returnvalue; ?>">
  <input type="hidden" name="nexturl"        value="<?php echo $nexturl; ?>">
  <input type="hidden" name="hash"           value="<?php echo md5($merchantnumber.$code.$amount.$ordernumber); ?>">
</form>

<div class="buttons">
  <div class="right">
    <input type="button" value="<?php echo $button_confirm; ?>" class="button" id="button-confirm" />
  </div>
</div>

<script type="text/javascript"><!--
$('#button-confirm').click(function() {
  $.ajax({
      type: 'get',
      url: 'index.php?route=payment/neweb_convenience_store_pay/confirm',
      success: function() {  $('#order-form').submit(); }
  });
});
//--></script>
