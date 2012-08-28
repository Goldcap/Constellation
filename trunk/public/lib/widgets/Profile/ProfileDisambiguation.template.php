<div class="inner_container clearfix">
<div class="host_show_header">
    <h3>Chose the Profile</h3>

</div>

<div class="host_container">
  <div class="host_container_top"></div>
  <div class="host_container_center clearfix">
   <?php foreach($users as $user) {?>
	    <div id="user_ambig"><a href="/profile/<?php echo $user -> getUserId();?>"><?php echo $user -> getUserFullName();?></a></div>
	 <?php } ?>  
  </div>
  <div class="host_container_bottom"></div>
</div>
</div>
