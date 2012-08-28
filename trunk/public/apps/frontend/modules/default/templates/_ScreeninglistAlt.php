<div class="all_screens">

  <h2>Other Screenings</h2>
  
<?php if (count($screenings) > 0) {
  foreach ($screenings as $afilm) {?>
  <div class="all_screens_item">
    <div class="all_screens_content">
      <span class="all_screens_still">
        <?php if ($afilm["screening_still_image"] != '') {?>
        <img class="user_host_<?php echo $afilm["screening_unique_key"];?>" src="/uploads/screeningResources/<?php echo $afilm["screening_film_id"];?>/screenings/screening_large_<?php echo $afilm["screening_still_image"];?>" alt="" width="126" />
        <?php } else {?>
        <img class="user_host_<?php echo $afilm["screening_unique_key"];?>" src="/images/alt/all_screens_still.jpg" />
        <?php } ?>
        <?php if ($afilm["screening_user_full_name"] != '') {?>
        <div class="tooltip" style="display:none"><?php echo $afilm["screening_user_full_name"];?></div>
        <script type="text/javascript">
        $(document).ready(function() {
          $(".user_host_<?php echo $afilm["screening_unique_key"];?>" ).tooltip({ effect: 'slide', relative: true, position: 'center right' });
        });          
        </script>
        <?php } ?>
      </span>
      <span class="all_screens_title">
        <h5><?php if ($afilm["screening_user_full_name"] != '') {?><?php echo $afilm["screening_user_full_name"];?>'s Screening<?php } ?></h5>
        <p>Recently joined screening (<?php echo $afilm["screening_audience_count"];?> attending)</p>
        <script type="text/javascript">
        $(document).ready(function() {
          $.ajax({url:'/services/Screenings/users?screening=<?php echo $afilm["screening_id"];?>', 
                  type: "GET", 
                  cache: false, 
                  dataType: "json", 
                  success: function(response) {
                    if ((response != null) && (response.users != undefined)) {
                    for (var i = 0; i < response.users.length; i++) {
                      $(".user_images_<?php echo $afilm["screening_unique_key"];?>").append('<img class="user_image_<?php echo $afilm["screening_unique_key"];?>"  src="'+response.users[i].image+'" alt="'+response.users[i].username+'" width="40" /><div class="tooltip" style="display:none">'+response.users[i].username+'</div>');
                    }
                    $(".user_image_<?php echo $afilm["screening_unique_key"];?>" ).tooltip({ effect: 'slide', relative: true, position: 'center right' });
                    }
                  }, error: function(response) {
                      console.log("ERROR:", response);
                  }
          });
        });
        </script>
        <span class="user_images_<?php echo $afilm["screening_unique_key"];?>">
        </span>
      </span>
      <script type="text/javascript">
      $(document).ready(function() {
        countdown_alt.init('<?php echo $afilm["screening_unique_key"];?>','<?php echo date("Y|m|d|H|i|s",strtotime($afilm["screening_date"]));?>');
      });
      </script>
      <span class="all_screens_time">
        <p><?php echo formatDate($afilm["screening_date"],"monthtimezone");?><!--Sunday, August 8 @8:00 PM EST--></p>
        <table cellpadding="0" cellspacing="0">
          <tr>
            <td>&nbsp;</td>
            <td align="center" colspan="3" class="timekeeper" id="timekeeper_<?php echo $afilm["screening_unique_key"];?>">00:00:00</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="20%">&nbsp;</td>
            <td align="center" width="20%">DAYS</td>
            <td align="center" width="20%">HOURS</td>
            <td align="center" width="20%">MINUTES</td>
            <td width="20%">&nbsp;</td>
          </tr>
        </table>
        
        <?php if ((strtotime($afilm["screening_date"])) < (strtotime( now()))) {?>
          <a class="screening_link" href="/film/<?php echo $afilm["screening_film_id"];?>/detail/<?php echo $$afilm["screening_unique_key"];?>" title="<?php echo $afilm["screening_unique_key"];?>"><img src="/images/alt/in_progress.png" border="0" /></a>
        <?php } else { 
          if ($afilm["screening_audience_count"] >= $afilm["screening_film_total_seats"]) {?>
           <a class="screening_link" href="/film/<?php echo $afilm["screening_film_id"];?>/detail/<?php echo $afilm["screening_unique_key"];?>" title="<?php echo $afilm["screening_unique_key"];?>"><img src="/images/alt/sold_out.png" border="0" /></a> 
          <?php } else {?>
          <a class="screening_link" title="<?php echo $afilm["screening_unique_key"];?>" href="/film/<?php echo $afilm["screening_film_id"];?>/detail/<?php echo $afilm["screening_unique_key"];?>"><img src="/images/alt/join_screening.jpg" border="0" /></a>
        <?php }} ?>
        
      </span>
    </div>
    <div id="time_<?php echo $afilm["screening_unique_key"];?>" style="display:none"><?php echo formatDate($afilm["screening_date"],"prettyshort");?></div>
    <div id="cost_<?php echo $afilm["screening_unique_key"];?>" style="display:none"><?php echo $afilm["screening_film_ticket_price"];?></div>
    <div id="host_<?php echo $afilm["screening_unique_key"];?>" style="display:none"><?php echo $afilm["screening_user_full_name"];?></div>
  </div>
  <?php }
  }  else {?>
  <div class="all_screens_item">
    <div class="all_screens_content">
      <span class="all_screens_still">
        <img src="/images/alt/all_screens_still.jpg" />
      </span>
      <span class="all_screens_title">
        <h5>No Screenings Available At This Time</h5>
        
      </span>
    </div>
  </div>
  <? } ?>

</div>
