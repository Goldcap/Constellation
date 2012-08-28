<p align="right">
  <a href="http://www.tattoojohnny.com"><img src="http://ll.tattoojohnny.com/prod/images/email-logo.jpg" border="0"></a>
</p>
<p>Dear <?php echo $vendor -> getVendorName();?>,
</p>

<p>
<?php echo $message;?>
</p>

<p>Your information is below. If there is an error with any of this information, please contact customer service and have your information updated.</p>

<p>
<table border="0">
  <tr>
    <td>Name:</td><td><?php echo $vendor -> getVendorName();?></td>
  </tr>
  <tr>
    <td>Contact:</td><td><?php echo $vendor -> getVendorContact();?></td>
  </tr>
  <tr>
    <td>Address:</td><td><?php echo $vendor -> getVendorAddress();?></td>
  </tr>
  <tr>
    <td></td><td><?php echo $vendor -> getVendorAddress2();?></td>
  </tr>
  <tr>
    <td>City:</td><td><?php echo $vendor -> getVendorCity();?></td>
  </tr>
  <tr>
    <td>State:</td><td><?php echo $vendor -> getVendorState();?></td>
  </tr>
  <tr>
    <td>Zip:</td><td><?php echo $vendor -> getVendorPostal();?></td>
  </tr>
  <tr>
    <td>Phone:</td><td><?php echo $vendor -> getVendorPhone();?></td>
  </tr>
  <tr>
    <td>Email:</td><td><?php echo $vendor -> getVendorEmail();?></td>
  </tr>
</table>
</p>
