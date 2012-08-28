Dear <?php echo $vendor -> getVendorName();?>,

<?php echo $message;?>
 
***********************************************************************
Your information is below. If there is an error with any of this information, please contact customer service and have your information updated.

Name:<?php echo $vendor -> getVendorName();?>
Contact:<?php echo $vendor -> getVendorContact();?>
Address:<?php echo $vendor -> getVendorAddress();?>
<?php echo $vendor -> getVendorAddress2();?>
City:<?php echo $vendor -> getVendorCity();?>
State:<?php echo $vendor -> getVendorState();?>
Zip:<?php echo $vendor -> getVendorPostal();?>
Phone:<?php echo $vendor -> getVendorPhone();?>
Email:<?php echo $vendor -> getVendorEmail();?>
 
***********************************************************************
