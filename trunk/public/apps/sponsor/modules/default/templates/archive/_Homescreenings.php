<?php if (count($featured_films) > 0 ) {foreach($featured_films as $film) {?>         
<li>            	
  <div class="thumbnail">				 					
    <a href="/screening/<?php echo $film["screening_unique_key"];?>">
      <?php if ($film["screening_guest_image"] != '') {
        if (left($film["screening_guest_image"],4) == "http") {?>
        <img src="<?php echo $film["screening_guest_image"];?>" width="53" height="53" alt="host photo" />
        <?php } else { ?>
        <img src="/uploads/hosts/<?php echo $film["screening_guest_image"];?>" width="53" height="53" alt="host photo" />
      <?php }} elseif ($film["screening_user_image"] != '') { ?>
        <img src="/uploads/hosts/<?php echo $film["screening_user_id"];?>/thumb_<?php echo $film["screening_user_image"];?>" width="53" height="53" alt="host photo" />			
      <?php } else {?>
        <img src="/images/host-star.png" width="53" height="53" alt="host photo" />				
      <?php } ?>
    </a>
  </div>				 				
  <div class="btn-join">					
    <a href="/screening/<?php echo $film["screening_unique_key"];?>">
      <img height="19" width="42" alt="+join" src="/images/btn[-join].png"/></a>				
  </div>						 				
  <div class="info">					
    <a href="/screening/<?php echo $film["screening_unique_key"];?>">						
      <span class="title"><?php echo $film["screening_film_name"];?>
      </span></a>					 					
    <a href="/screening/<?php echo $film["screening_unique_key"];?>">						
      <span class="tzDate" name="1291942800" lang="m/dd/yy h:MMtt"><?php echo $film["screening_date"];?>
      </span></a>					 					
    <a href="/screening/<?php echo $film["screening_unique_key"];?>">						
      <span class="host">Hosted by <?php echo $film["screening_user_full_name"];?>
      </span></a>					 					
    <a href="/screening/<?php echo $film["screening_unique_key"];?>">						
      <span class="description"><?php echo elipses($film["screening_film_info"],100);?>			
      </span></a>				
  </div>				
  <a class="cal" title="click to add the date to your calendar" href="/services/iCal/<?php echo $film["screening_unique_key"];?>"></a>				 								 				
  <div class="clear"></div>			
</li>
<?php }} ?>
