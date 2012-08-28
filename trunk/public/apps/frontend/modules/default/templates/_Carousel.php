<?php 
if (count($screenings) == 0) {
   return;
}

if (count($screenings < 3)) { $default = count($screenings); } else { $default = 3; } 
if ($size == "film_") {
  $h=172;
  $w=113;
} else {
  $h=246;
  $w=173;
}
?>

<!-- /// CUSTOM NAVIGATION -->
<div id="barousel_thslide" class="barousel">
    
    <div class="barousel_top">
      <!-- IMAGE -->
      <div class="barousel_image">
          <?php $i=1;foreach ($screenings as $screen) {?>
          <img src="/uploads/screeningResources/<?php echo $screen["screening_film_id"];?>/screenings/<?php echo $size;?>screening_large_<?php echo $screen["screening_still_image"];?>" alt="" <?php if ($i==$default) {?>class="default" <?php }?> />
          <?php $i++; } ?>
      </div>
      
      <!-- CONTENT -->
      <div class="barousel_content">

          <?php $i=1;foreach ($screenings as $screen) {?>
          <div <?php if ($i == $default) {?>class="default"<? }?>>

              <table width="<?php echo ($w);?>" height="<?php echo ($h);?>" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td width="<?php echo ($w);?>" class="ogcar" valign="top">
                    <h2><a href="/film/<?php echo $screen["screening_film_id"];?>/detail/<?php echo $screen["screening_unique_key"];?>"><?php echo formatDate($screen["screening_date"],'h:i A T');?></a></h2>
                    <span class="screening_date"><a href="/film/<?php echo $screen["screening_film_id"];?>/detail/<?php echo $screen["screening_unique_key"];?>"><?php echo formatDate($screen["screening_date"],'DABBR');?></a></span>
                    <h4><a href="/film/<?php echo $screen["screening_film_id"];?>/detail/<?php echo $screen["screening_unique_key"];?>"><?php echo $screen["screening_film_name"];?></a></h4>
                    <?php if ($screen["screening_user_full_name"] != '') { ?><span class="screening_host"><a href="/film/<?php echo $screen["screening_film_id"];?>/detail/<?php echo $screen["screening_unique_key"];?>">HOSTED BY <?php echo $screen["screening_user_full_name"];?></a></span><?php } ?>
                    <span class="screening_info"><?php echo $screen["screening_description"];?></span>
                    <a class="joinlink" href="/film/<?php echo $screen["screening_film_id"];?>/detail/<?php echo $screen["screening_unique_key"];?>" title="<?php echo $screen["screening_unique_key"];?>">join</a>
                  </td>
                </tr>
              </table>

          </div>
          <?php $i++; } ?>
      </div>
    </div>
    
    <!-- NAV -->
    <div id="thslide_barousel_nav" class="thslide">

        <div class="thslide_nav_previous"><a href="#">&nbsp;</a></div>

        <div class="thslide_list">

            <ul>
                <?php $i=1;foreach ($screenings as $screen) {?>
                <li>
                  <a id="screening_<?php echo $i;?>" href="#" class="thslide_item">
                  <img class="ogcar_floatright <?php if ($i == $default) {?>default<?} else {?>other<?php }?>" src="/uploads/screeningResources/<?php echo $screen["screening_film_id"];?>/screenings/<?php echo $size;?>screening_small_<?php echo $screen["screening_still_image"];?>" alt="" />
                  <?php if ($size == "") {?>
                  <span class="<?php if ($i == $default) {?>default<?} else {?>other<?php }?>"><?php echo $screen["screening_film_name"];?></span>
                  <?php } ?>
                  </a>
                </li>                        
                <?php $i++; } ?>
           </ul>

        </div>

        <div class="thslide_nav_next"><a href="#">&nbsp;</a></div>
        
        <div class="thslide_hidden">
          <ul>
            <?php $i=1;foreach ($screenings as $screen) {?>
            <li>
              <a id="screening_hidden_<?php echo $i;?>" href="#" class="thslide_hidden_item">
                <img src="/images/spacer.gif" height="1" width="1" />
              </a>
            </li>                        
            <?php $i++; } ?>
           </ul>
         </div>
         
    </div>

</div>

<div id="barousel_running" class="reqs">1</div>
