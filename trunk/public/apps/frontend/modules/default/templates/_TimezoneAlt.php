<?php 
$ttz = date_default_timezone_get();
$offset = (get_timezone_offset('UTC',$ttz) > 0) ? "-" : "+";
$tz = $offset.get_timezone_offset('UTC',$ttz);
$zones = zoneList();
?>

<div id="timezone-select-popup" class="pops" style="display: none">
  	<h2>Select your Timezone.</h2>  
    <p>NOTE: If your are not logged in, this will save your timezone option only for this session.</p>  
    <form id="timezone_form" name="timezone_form" action="/services/Timezone" method="POST" class="timezone_form">
        <div class="form_row clear">
            <select name="tzSelectorPop" id="tzSelectorPop">
              <?php foreach (array_keys($zones) as $zone) {?>
              <optgroup label="<?php echo strtoupper($zone);?>">
                <?php foreach($zones[$zone] as $key => $atz) {?>
                  <option value="<?php echo $key;?>" <?php if ($ttz == $key) {?>selected="selected"<?php } ?>>&nbsp;&nbsp;&nbsp;<?php echo $atz;?></option>
                <?php } ?>
              </optgroup>
              <?php } ?>
            </select>
        </div>
        <div class="form_row ">
            <input type="hidden" id="timezone_source" value="<?php echo $_SERVER["REQUEST_URI"];?>" name="timezone_source" />
            <input type="hidden" id="timezone_destination" value="<?php echo $_SERVER["REQUEST_URI"];?>" name="timezone_destination" />
            <button type="submit"class="button button_orange uppercase" name="timezone" id="timezone-button">Set Zone</button>
        </div>
    </form>
</div>
