<?php 
if ($sf_user->getAttribute('login_error')) {?>
<span class="error">
<?php echo $sf_user->getAttribute('login_error');?>
</span>
<?
$sf_user->setAttribute('login_error',null);
}?>

<?php echo $form?>
