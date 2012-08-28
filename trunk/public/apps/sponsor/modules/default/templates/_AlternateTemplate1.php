<!--http://s3.amazonaws.com/cdn.constellation.tv/prod-->
<div class="alternate_container">
  <div id="image_gallery_alternate" class="image_gallery small" style="display:none"></div>
  
  <div id="today_screenings" class="alternate">
    
    <?php if ($film["film_show_title"] == 1) {?>
    <h4 id="alternate_film_name" style="color: #<?php echo $film["film_text_color"]?>"><?php echo $film["film_name"];?></h4>
    <?php } else {?>
    <h4></h4>
    <br />
    <br />
    <br />
    <?php } ?>
    
    <?php if ($screening["screening_description"] != '') {?>
      <p><?php echo $screening["screening_description"];?></p>
      <!--<?php echo $film["film_maker_message"];?>-->
    <?php } else {?>
      <p>Followed by a live Q+A with the director.</p>
    <?php }?>
    
    <p>
      <span id="alternate_date"><?php echo date("l, F d, Y",strtotime($screening["screening_date"]));?></span>
      <br />
      <span id="alternate_time"><?php echo date("g:iA T",strtotime($screening["screening_date"]));?></span>
    </p>
    
    <p class="begins" style="color: #<?php echo $film["film_text_color"]?>">BEGINS IN</p>
    
    <h4 id="countdown" style="color: #<?php echo $film["film_text_color"]?>">00:00:00:00</h4>
    
    <div>
      <?php if ($sponsor) {?>
      <a class="purchase_link" title="<?php echo $screening["screening_unique_key"];?>" href="/film/<?php echo $film["film_id"];?>/detail/<?php echo $screening["screening_unique_key"];?>" id="get ticket now"><button class="btn_sponsor">GET TICKET NOW</button></a>
      <? } else { ?>
      <a class="screening_link" title="<?php echo $screening["screening_unique_key"];?>" href="/film/<?php echo $film["film_id"];?>/detail/<?php echo $screening["screening_unique_key"];?>" id="get ticket now"><button class="btn_sponsor">GET TICKET NOW</button></a>
      <? } ?>
    </div>
    
    <div style="color: #<?php echo $film["film_text_color"]?>">
      <?php echo $seatcount;?> Seats Remaining
    </div>
    
    <div id="ocb" class="reqs">
       <h3>Screening in Progress</h3>
       You can still get a ticket, but the movie will be in progress when you enter the theater.
    </div>
    
    <div id="alt_screening" class="reqs"><?php echo $screening["screening_unique_key"];?></div>
    <div id="thistime" class="reqs"><?php echo $thistime;?></div>
    <div id="counttime" class="reqs"><?php echo $counttime;?></div>
    <div id="time_<?php echo $screening["screening_unique_key"];?>" class="reqs"><?php echo formatDate($screening["screening_date"],"prettyshort");?></div>
    <div id="host_<?php echo $screening["screening_unique_key"];?>" class="reqs"><?php echo $screening["screening_user_full_name"];?></div>
    
  </div>
  
  <style>
  .ui-datepicker { font-size: 10px; }
  .specialDate .ui-state-default {color: white;background: url("/js/jquery/ui/themes/smoothness/images/ui-bg_glass_75_93A7BE_1x400.png") repeat-x scroll 50% 50% #93A7BE !important;}
  </style> 
  <h4 class="future_showtimes">future showtimes</h4>
  <input type="hidden" id="featured_datepicker_alternate" style="visibility: hidden" />
</div>

<div id="last_screening" class="reqs"></div>
<div id="alt_template" class="reqs">1</div>
