<div class="col-1-decorator">    
  <div class="col-1-wrap">
    <div id="filmposter-lottery" class="filmposter_box">		        	
      <a class="poster" href="/film/<?php echo $film_id;?>">		
      <img class="poster" src="/uploads/screeningResources/<?php echo $film_id;?>/logo/small_poster<?php echo $film_logo;?>" alt="<?php echo $film_name;?>" width="156" /</a>
      <h2><?php echo $title;?></h2>		 									
      
      <ul id="showtimes-container" class="showtimes">		
      <!-- BEGIN container for screening results --> 	 
      <?php if (count($screenings) > 0) {
      foreach ($screenings as $afilm) {
      if ($film["screening_unique_key"] == $afilm["screening_unique_key"]) {
        continue;
      }?>
      <li>  			
        <div class="container hover-title" id="trigger-<?php echo $afilm["screening_film_id"];?>" >  				
          <div class="date">  											
            <a class="host-info" id="trigger-<?php echo $afilm["screening_film_id"];?>"  href="/screening/<?php echo $afilm["screening_unique_key"];?>" >
              <p lang="mmm dd, yyyy h:MMTT" class="tzDate" name="1291942800"><?php echo formatDate($afilm["screening_date"],"prettyshort");?></p>
            </a>
            <a class="btn-join" href="/screening/<?php echo $afilm["screening_unique_key"];?>">
            <?php if ($afilm["screening_guest_image"] != '') {
            if (left($afilm["screening_guest_image"],4) == "http") {?>
              <img class="host-img" height="35" width="35"  src="<?php echo $afilm["screening_guest_image"];?>" />			
            <?php } else { ?>
              <img class="host-img" height="35" width="35"  src="/uploads/hosts/<?php echo $afilm["screening_guest_image"];?>" />			
            <?php }} elseif ($afilm["screening_user_image"] != '') {
            if (left($afilm["screening_user_image"],4) == "http") {?>
              <img class="host-img" height="35" width="35"  src="<?php echo $afilm["screening_user_image"];?>" />			
            <?php } else {?>
                <img class="host-img" height="35" width="35"  src="/uploads/hosts/<?php echo $afilm["screening_user_id"];?>/thumb_<?php echo $afilm["screening_user_image"];?>" />			
            <?php }} else {?>
              <img class="host-img" height="35" width="35"  src="/images/host-star.png" />			
            <?php } ?>
            </a>							 																		
            <a class="btn-join" href="/screening/<?php echo $afilm["screening_unique_key"];?>">
              <img height="19" width="42" alt="+join" src="/images/btn[-join].png"/></a>		 									
          </div>  				 								 				
          <div class="desc">  					
            <div class="hosted">Hosted by <?php echo $afilm["screening_user_full_name"];?>
            </div>
          </div>
        </div>
      </li>
      <?php }} ?>
      <!-- END container for screening results -->

      </ul>
    </div>                    
  </div>  
</div>
