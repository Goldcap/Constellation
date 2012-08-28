<?php 
$film = $vars["Host"]["column1"][0]["film"];
?>	 		 	   	 		
          
<!-- CURRENTTIME -->
<div class="buzz">
	<h5>Host A Screening</h5>
	<div><?php echo $film["film_name"];?></div>
</div>

<!-- TIMEZONE -->
<?php include_component('default', 
                        'Timezone')?>
<!-- TIMEZONE -->	 		

<!-- CURRENTTIME -->
<?php include_component('default', 
                        'Currenttime')?>
