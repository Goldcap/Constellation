<h4>Up Next Today</h4>
<?php if (count($featured_films) > 0 ) {?>
<div class="screening_list">
<ul>
  <?php foreach($featured_films as $film) {?>
  <li class="clearfix">
  	<a href="/film/<?php echo $film["screening_film_id"];?>/detail/<?php echo $film["screening_unique_key"];?>">join</a> 
    <span class="subscreen_title"><?php echo $film["screening_film_name"];?></span>
    <span class="subscreen"><?php echo $film["screening_date"];?><br />
    <?php if ($film["screening_user_full_name"] != '') {?>hosted by <?php echo $film["screening_user_full_name"];?><? } ?>
    </span>
  </li>
  <?php }?>
</ul>
</div>
<?php } ?>
