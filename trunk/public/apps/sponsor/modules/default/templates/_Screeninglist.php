<?php if (! $alternate) {?>
  <h4><?php echo $title;?></h4>
<?php } ?>
  
<?php if (count($screenings) > 0) {?>
  <div class="screening_list">
  <ul>
    <?php foreach ($screenings as $afilm) {?>
    <li class="clearfix">
    	<a class="screening_link" title="<?php echo $afilm["screening_unique_key"];?>" href="/film/<?php echo $afilm["screening_film_id"];?>/detail/<?php echo $afilm["screening_unique_key"];?>">join</a>
      <span id="title_<?php echo $afilm["screening_unique_key"];?>" class="subscreen_title"><?php echo $afilm["screening_film_name"];?></span>
      <span class="subscreen"><?php echo formatDate($afilm["screening_date"],"monthtimezone");?><br />
      <?php if ($afilm["screening_user_full_name"] != '') {?>hosted by <?php echo $afilm["screening_user_full_name"];?><?php } ?>
      </span>
      <div id="time_<?php echo $afilm["screening_unique_key"];?>" style="display:none"><?php echo formatDate($afilm["screening_date"],"prettyshort");?></div>
      <div id="cost_<?php echo $afilm["screening_unique_key"];?>" style="display:none"><?php echo $afilm["screening_film_ticket_price"];?></div>
      <div id="host_<?php echo $afilm["screening_unique_key"];?>" style="display:none"><?php echo $afilm["screening_user_full_name"];?></div>
    </li>
    <?php } ?>
  </ul>
  </div>
<?php } ?>
