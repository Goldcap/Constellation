<?php 
if (isset ($form) ) {echo $form;}
?>

<div id="bandwidth_wrapper">
<?php if (isset($vars["Theater"])) {?>
<div id="cud" class="reqs"><?php echo ($vars["Theater"]["column2"][0]["cud"]);?></div>
<?php } ?>
<div id="bandwidth_test"></div>
</div>
