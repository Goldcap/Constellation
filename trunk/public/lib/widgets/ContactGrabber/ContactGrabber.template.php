<?php if (isset($content)) {
  echo $content;
} else { ?>

<style>
td {
  color: white;
}
</style>
<script type='text/javascript'>
	function toggleAll(element) 
	{
	var form = document.forms.openinviter, z = 0;
	for(z=0; z<form.length;z++)
		{
		if(form[z].type == 'checkbox')
			form[z].checked = element.checked;
	   	}
	}
</script>

<form action='' method='POST' name='openinviter'>

  <table align='center' class='thTable' cellspacing='2' cellpadding='0' style='border:none;'>
			<tr class='thTableRow'><td align='right'><label for='email'>Email</label></td><td><input class='thTextbox' type='text' name='email' value=''></td></tr>
			<tr class='thTableRow'><td align='right'><label for='password'>Password</label></td><td><input class='thTextbox' type='password' name='password' value=''></td></tr>
			<tr class='thTableRow'><td align='right'><label for='provider_box'>Email provider</label></td><td>
    <select class='thSelect' name='provider_box'>
    <option value=''></option>
		<?php foreach ($oi_services as $type=>$providers)	 {?>
			<optgroup label='<?php echo $inviter->pluginTypes[$type];?>'>
			<?php foreach ($providers as $provider=>$details) {?>
				<option value='<?php echo $provider;?>' <?php if ($_POST['provider_box']==$provider) { echo 'selected=\'selected\''; }?>><?php echo $details['name'];?></option>
			<?php } ?>
      </optgroup>
		<?php } ?>
		</select></td></tr>
		<tr class='thTableImportantRow'>
      <td colspan='2' align='center'><input class='thButton' type='submit' name='import' value='Import Contacts'></td></tr>
		</table>
  <input type='hidden' name='step' value='get_contacts'>
</form>
<?php } ?>
