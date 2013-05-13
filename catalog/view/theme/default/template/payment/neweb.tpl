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
<form action="<?php echo $action; ?>" method="post">
  <input type="hidden" name="MerchantNumber" value="<?php echo $MerchantNumber; ?>">
  <input type="hidden" name="OrderNumber"    value="<?php echo $OrderNumber; ?>">
  <input type="hidden" name="Amount"         value="<?php echo $Amount; ?>">
  <input type="hidden" name="OrgOrderNumber" value="<?php echo $OrgOrderNumber; ?>">
  <input type="hidden" name="ApproveFlag"    value="<?php echo $ApproveFlag; ?>">
  <input type="hidden" name="DepositFlag"    value="<?php echo $DepositFlag; ?>">
  <input type="hidden" name="Englishmode"    value="<?php echo $Englishmode; ?>">                    
  <input type="hidden" name="iphonepage"     value="<?php echo $iphonepage; ?>">                    
  <input type="hidden" name="OrderURL"       value="<?php echo $OrderURL; ?>">
  <input type="hidden" name="ReturnURL"      value="<?php echo $ReturnURL; ?>">
  <input type="hidden" name="checksum"       value="<?php echo md5($MerchantNumber.$OrderNumber.$code.$Amount); ?>">
  <input type="hidden" name="op"             value="AcceptPayment">
  <div class="buttons">
    <div class="right">
      <input type="submit" value="<?php echo $button_confirm; ?>" class="button" />
    </div>
  </div>
</form>