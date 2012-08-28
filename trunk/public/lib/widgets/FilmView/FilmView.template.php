<?php 
$ttz = date_default_timezone_get();
$zones = zoneList(); 
?>

<?php 
if ($film["film_background_image"] != ''){?>
<!--http://s3.amazonaws.com/cdn.constellation.tv/prod-->
<style>
body {
background: #000000 url("/uploads/screeningResources/<?php echo $film["film_id"];?>/background/<?php echo $film["film_background_image"];?>") no-repeat center top !important;
}
</style>
<?php } ?>

<?php 
if ((isset($film["program_background_image"])) && ($film["program_background_image"] != '')){?>
<!--http://s3.amazonaws.com/cdn.constellation.tv/prod-->
<style>
body {
background: #000000 url("/uploads/programResources/<?php echo $film["program_id"];?>/background/<?php echo $film["program_background_image"];?>") no-repeat center top !important;
}
</style>
<?php } ?>

<?php /* in the interest of not changing too much, putting some JS directly in here */ ?>
<script type="text/javascript">
	$(document).ready(function() {
		$("select.cc-country-drop").change(function () {
			var code = $(this).val();
			var country = $("option:selected", this).text();
			
			if(code == "US") {
				$("select.cc-state-drop").show();
				$("input.cc-state-text").hide();
			} else {
				$("select.cc-state-drop").hide();
				$("input.cc-state-text").show();
				$("input.cc-state-text").val(country);
			}
		});
	});
</script>

<?php if ($film["film_alternate_template"] != 0) {?>
<!-- SCREENING CAROUSEL -->

<?php include_component('default', 
                        "AlternateTemplate".$film["film_alternate_template"], 
                        array('film'=>$film,
                        'sponsor'=>false,
                        'seatcount'=>$seatcount,
                        'screening'=>$screen,
                        'thistime'=>$thistime,
                        'counttime'=>$counttime
                        ))?>
<!-- SCREENING CAROUSEL -->
<?php } else {?>
<div class="image_gallery small">
  <?php if (count($carousel) > 0) {?>
  <!-- SCREENING CAROUSEL -->
  <?php include_component('default', 
                          'Carousel', 
                          array('size'=>'film_',
                                'screenings'=>$carousel))?>
  <!-- SCREENING CAROUSEL -->
  <?php } ?>
</div>
<div class="showtimes" id="today_screenings">
<!-- SCREENINGLLIST -->
<?php include_component('default', 
                        'Screeninglist', 
                        array('title'=>'join today\'s showtimes',
                        'screenings'=>$screenings))?>
<!-- SCREENINGLLIST -->
</div>
<div>
<style>
.ui-datepicker { font-size: 10px; }
.specialDate .ui-state-default {color: white;background: url("/js/jquery/ui/themes/smoothness/images/ui-bg_glass_75_93A7BE_1x400.png") repeat-x scroll 50% 50% #93A7BE !important;}
</style> 
<h4 class="future_showtimes">future showtimes</h4>
<input type="hidden" id="featured_datepicker" style="visibility: hidden" />
</div>
<?php //}
} ?>

<div id="host" class="reqs"><?php echo $chat_instance_host;?></div>
<div id="port" class="reqs"><?php echo $chat_instance_port_base;?></div>


<?php if (($sf_user -> isAuthenticated()) && (($film["film_allow_user_hosting"]== 1) || ($film["film_allow_hostbyrequest"]))) {?>
<!-- HOST POPUPS -->
<?php include_component('default', 
                        'Host', 
                        array('film'=>$film,
                              'states'=>$states,
															'countries'=>$countries,
															'oi_services'=>$oi_services,
															'zones'=>$zones,
															'post'=>$post))?>
<!-- HOST POPUPS -->
<?php } ?>

<?php if ($sf_user -> isAuthenticated()) {?>
<!-- PURCHASE POPUPS -->
<?php include_component('default', 
                        'Purchase', 
                        array('film'=>$film,
                              'states'=>$states,
															'countries'=>$countries,
															'oi_services'=>$oi_services,
															'zones'=>$zones,
															'post'=>$post))?>
<!-- PURCHASE POPUPS -->
<?php } ?>

<?php if ($film["film_use_sponsor_codes"] == 1) {?>
<!-- PURCHASE POPUPS -->
<?php include_component('default', 
                        'WBR', 
                        array('film'=>$film))?>
<!-- PURCHASE POPUPS -->
<?php } ?>

<?php if ($sf_user -> isAuthenticated()) {?>
<!-- GEOIP POPUPS -->
<?php include_component('default', 
                        'GEOIP')?>
<!-- GEOIP POPUPS -->
<?php } ?>

<?php if ($sf_user -> isAuthenticated()) {?>
<!-- INVITE POPUPS -->
<?php include_component('default', 
                        'Invites')?>
<!-- INVITE POPUPS -->
<?php } ?>

<div id="gbip" style="display:none"><?php echo $gbip;?></div>
<div id="host_cost" style="display:none"><?php echo $film["film_setup_price"];?></div>
<div id="ticket_cost" style="display:none"><?php echo $film["film_ticket_price"];?></div>
<div id="domain" style="display:none"><?php echo sfConfig::get("app_domain");?></div>
<div id="film" style="display:none"><?php if (isset($film["film_id"])) {echo $film["film_id"];}?></div>
<div id="film_start_offset" style="display:none"><?php echo $film_start_offset;?></div>
<div id="film_end_offset" style="display:none"><?php echo $film_end_offset;?></div>
<div id="screening" style="display:none"><?php if (isset($screening)) {echo $screening;}?></div>
<div id="current_date" style="display:none"></div>
<?php if ($film["film_allow_hostbyrequest"] == 1) {?>
  <div id="dohbr_ticket_price" style="display:none"><?php if (isset($film["film_hostbyrequest_price"])) {echo $film["film_hostbyrequest_price"];}?></div>
<?php } ?>
