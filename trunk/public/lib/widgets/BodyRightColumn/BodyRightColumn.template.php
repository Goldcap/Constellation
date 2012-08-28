<?php 
$featured_films = $vars["Body"]["main"][0]["featured_films"];
?>
<div class="showtimes" id="today_screenings">
	<?php include_component('default', 
                      'Homescreenings', 
                       array('featured_films' => $featured_films)
                       )?>
</div>
<style>
.ui-datepicker { font-size: 10px; }
.specialDate .ui-state-default {color: white;background: url("/js/jquery/ui/themes/smoothness/images/ui-bg_glass_75_93A7BE_1x400.png") repeat-x scroll 50% 50% #93A7BE !important;}
</style> 
<h4 class="future">future showtimes</h4>
<input type="hidden" id="featured_datepicker" style="visibility: hidden" />
<!--<button class="date_icon" id="featured_datepicker_box">Jun 26</button>-->
<!--<a class="cal" title="click to add the date to your calendar" href="/services/iCal/<?php echo $film["screening_unique_key"];?>"></a>-->
