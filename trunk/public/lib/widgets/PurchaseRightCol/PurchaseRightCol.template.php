<?php 
$film = $vars["Purchase"]["col02"][0]["film"];
$screenings = $vars["Purchase"]["col02"][0]["screenings"];
?>	 		 	   	 		
<div class="image_holder"><a href=""><img src="/images/temp_images/girl1.jpg" alt="" /></a></div>
  
<!-- TIMEZONE -->
<?php include_component('default', 
                        'Timezone')?>
<!-- TIMEZONE -->	 		

<!-- CURRENTTIME -->
<?php include_component('default', 
                        'Currenttime')?>
<!-- CURRENTTIME -->

<div class="buzz">
<h5>Buzz</h5>
<ul id="constellation_map">
  </ul>
</div>
<span id="room"><?php echo $room;?></span>
