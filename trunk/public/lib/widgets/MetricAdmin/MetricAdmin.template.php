<?php if (isset ($form) ) {?>
<div id="topmenu">
	 <?php echo "<a href=\"/metric/export\">Download Excel File</a>";?> |
	 <?php echo "<a href=\"/metric/tickets\">Ticket Sales</a>";?>
</div>
<?php
 echo $form;
 }
?>

<?php 
if (isset ($filename) ) {?>
	Download <a href="/metric/download?file=<?php echo $filename;?>" style="color: green"><?php echo $filename;?></a>
<?php } ?>