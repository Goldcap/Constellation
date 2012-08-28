<div id="timer_panel">
<strong><?php echo $vars["Theater"]["column2"][0]["film"]["screening_film_name"];?></strong><br />
begins in
<div class="timer" id="countdown"></div>
</div> 

<span class="reqs" id="startdate"><?php echo $vars["Theater"]["column2"][0]["film_start_date"];?></span>
<span class="reqs" id="currentdate"><?php echo formatDate(now(),"prettyshort");?></span>
<span class="reqs" id="thistime"><?php echo $vars["Theater"]["column2"][0]["thistime"];?></span>
<span class="reqs" id="counttime"><?php echo $vars["Theater"]["column2"][0]["counttime"];?></span>
<span class="reqs" id="runtime"><?php echo $vars["Theater"]["column2"][0]["runtime"];?></span>
<span class="reqs" id="starttime"><?php echo $vars["Theater"]["column2"][0]["film_start_time"] - 6;?></span>
<span class="reqs" id="blockentrytime"><?php echo $vars["Theater"]["column2"][0]["film_end_time"] - 150;?></span>
<span class="reqs" id="reviewtime"><?php echo $vars["Theater"]["column2"][0]["film_end_time"] + 2;?></span>
<span class="reqs" id="promotime"><?php echo $vars["Theater"]["column2"][0]["film_end_time"] + 62;?></span>
<span class="reqs" id="qatime"><?php echo $vars["Theater"]["column2"][0]["film_end_time"] + 122;?></span> 
<span class="reqs" id="endtime"><?php echo $vars["Theater"]["column2"][0]["film_end_time"] + 1800;?></span> 
<span class="reqs" id="tz_offset"><?php echo $tz_offset;?></span> 

<span class="reqs" id="csrc"><?php echo $vars["Theater"]["column2"][0]["film"]["screening_film_cdn"];?></span>
