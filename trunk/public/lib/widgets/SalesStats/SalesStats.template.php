<?php 
if (isset ($total) ) {?>

<div id="total_sales">
  <h2 style="color: green; font-weight: bold;">Total Sales: $<?php echo sprintf("%.02f",$total);?></h2>
  <span> 
  <?php if (isset ($total_paypal) ) {?>
  (Paypal: $<?php echo sprintf("%.02f",$total_paypal);?>) 
  <?php } ?> 
  <?php if (isset ($total_paypal_wpp) ) {?>
  (Paypal WPP: $<?php echo sprintf("%.02f",$total_paypal_wpp);?>) 
  <?php } ?> 
  </span>
</div>

<?}?>

<?php 
if (isset ($searchform) ) {echo $searchform;}
?>

<?php 
if (isset ($form) ) {echo $form;}
?>
