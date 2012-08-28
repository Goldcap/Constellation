<?php 
if (isset ($form) ) {echo $form;}
?>
<?php 
if (isset ($file) ) {?>

<div class="styroform">

<?php if (isset($_POST["startdate"])) {?>
<?php echo $_POST["startdate"];?> to <?php echo $_POST["enddate"];?>   <br /> <br />
<?php } ?>
Download <a href="/report/download?file=<?php echo $file;?>" style="color: green"><?php echo $file;?></a>

</div>

<? } ?>