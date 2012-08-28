<?php 
$ttz = date_default_timezone_get();
$offset = (get_timezone_offset('UTC',$ttz) > 0) ? "-" : "+";
$tz = $offset.get_timezone_offset('UTC',$ttz);
$zones = zoneList();
?>

<div  class="buzz">
  <h5>Set Your Timezone</h5>
  <div style="padding-bottom: 20px;">
    <div id="jclock" class="jclock"></div>
  </div>
  <div style="clear: both;">
    <span style="float: left; margin-right: 10px;">
      <img id="timezone-select" class="timezone-clock" src="/images/clock-icon.png" width="18" height="18" alt="timezone" />
    </span>
    <span style="float: left;"><?php echo str_replace("_"," ",date_default_timezone_get());?> </span>
  </div>
  <script type="text/javascript">
    $(function($) {
      var optionsEST = {
        format: '%H:%M:%S %P <?php echo date("T");?>',
        utc: true,
        utc_offset: <?php echo $tz;?>
      }
      $('#jclock').jclock(optionsEST);
    });
  </script>
</div>
<div class="reqs" id="tz_offset"><?php echo getTzOffset() - getTzBase();?></div>

<div id="timezone-select-popup" class="pop_up" style="display: none">
  <div class="pop_mid">
  <div class="pop_top" id="timezone-popup-close"><a href="#">close</a></div>
	<div class="layout-1 clearfix">
    	<p> Please select your timezone.</p>  
      <p>NOTE: If your are not logged in, this will save your timezone option only for this session.</p>  
      <form id="timezone_form" name="timezone_form" action="/services/Timezone" method="POST" class="timezone_form">
      <div>
      <select name="tzSelector" id="tzSelector">
        <?php foreach (array_keys($zones) as $zone) {?>
        <optgroup label="<?php echo strtoupper($zone);?>">
          <?php foreach($zones[$zone] as $key => $atz) {?>
            <option value="<?php echo $key;?>" <?php if ($ttz == $key) {?>selected="selected"<?php } ?>>&nbsp;&nbsp;&nbsp;<?php echo $atz;?></option>
          <?php } ?>
        </optgroup>
        <?php } ?>
      </select>
      </div>
      <div>
      <input type="hidden" value="<?php echo $_SERVER["REQUEST_URI"];?>" name="source" />
      <input type="hidden" id="login_destination" value="<?php echo $_SERVER["REQUEST_URI"];?>" name="destination" />
      <input name="timezone" id="timezone-button" class="btn_small" value="set zone" type="button" />
      </div>
  </div>
  <div class="pop_bot"></div>
  </div>    
</div>
