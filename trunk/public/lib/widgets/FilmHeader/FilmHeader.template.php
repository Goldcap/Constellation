<?php 
$ttz = date_default_timezone_get();
$zones = zoneList(); 
?>

<div id="host" class="reqs"><?php echo $chat_instance_host;?></div>
<div id="port" class="reqs"><?php echo $chat_instance_port_base;?></div>

<?php if ($film["film_use_sponsor_codes"] == 1) {?>
<?php include_component('default', 
                        'PurchaseFilmSponsorAlt', 
                        array('sf_user'=>$sf_user,
															'film'=>$film,
                              'states'=>$states,
															'countries'=>$countries,
															'post'=>$post))?>
<!-- END PURCHASE POPUPS -->
<?php } else { ?>
<!-- PURCHASE POPUPS -->
<?php include_component('default', 
                        'PurchaseFilmAlt', 
                        array('sf_user'=>$sf_user,
															'film'=>$film,
                              'states'=>$states,
															'countries'=>$countries,
															'post'=>$post))?>
<!-- END PURCHASE POPUPS -->
<?php } ?>

<!-- WBR POPUPS -->
<?php include_component('default', 
                        'WBRFilmAlt', 
                        array('sf_user'=>$sf_user,
															'film'=>$film))?>
<!-- END WBR POPUPS -->

<!-- INVITES POPUP -->
<?php include_component('default', 
                        'Invites')?>
<!-- END INVITES POPUP -->

<div id="gbip" style="display:none"><?php echo $gbip;?></div>
<div id="host_cost" style="display:none"><?php echo $film["film_setup_price"];?></div>
<div id="ticket_cost" style="display:none"><?php echo $film["film_ticket_price"];?></div>
<div id="domain" style="display:none"><?php echo sfConfig::get("app_domain");?></div>
<div id="film" style="display:none"><?php if (isset($film["film_id"])) {echo $film["film_id"];}?></div>
<div id="film_start_offset" style="display:none"><?php echo $film_start_offset;?></div>
<div id="film_end_offset" style="display:none"><?php echo $film_end_offset;?></div>
<div id="screening" style="display:none"><?php if (isset($screening)) {echo $screening;}?></div>
<div id="current_date" style="display:none"></div>
<div id="thistime" style="display:none"><?php if (isset($thistime)) {echo $thistime;}?></div>

<?php if ($film["film_allow_hostbyrequest"] == 1) {?>
  <div id="dohbr_ticket_price" style="display:none"><?php if (isset($film["film_hostbyrequest_price"])) {echo $film["film_hostbyrequest_price"];}?></div>
<?php } ?>
<?php if($gbip):?>
<div class="inner_container clear clearfix">
      <div class="block block-restrictions clear">
            <h4>We're sorry, this film cannot be streamed in your current location.<br/>
            <!-- For more information on Constellation international policy, <a ahref="/policy">click here</a>.</h4> -->
      </div>
</div>
<?php endif;?>