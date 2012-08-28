<?php 
if (isset ($audience) ) {?>
<table width="300" style="font-size: 14px; font-weight: bold">
  <tr>
    <td>
    Audience Username
    </td>
    <td>
    <a href="/user/detail/<?php echo $audience -> getFkUserId();?>" target="new"><?php echo $audience -> getAudienceUsername();?></a> (<?php echo $audience -> getFkUserId();?>)
    </td>
  </tr>
  <tr>
    <td>
    Screening
    </td>
    <td>
    <a href="/screening/detail/<?php echo $audience -> getFkScreeningId();?>" target="new"><?php echo $audience -> getFkScreeningUniqueKey();?></a>
    </td>
  </tr>
</table>
<br />
<br />
<? } ?>
<?php 
if (isset ($search_form) ) {echo $search_form;}
?>
<?php 
if (isset ($form) ) {echo $form;}
?>
