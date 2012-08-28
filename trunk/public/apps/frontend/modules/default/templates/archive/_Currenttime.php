<div class="screening-activity-box">
  <h2>Current Time</h2>
  <div id="jclock"></div> 					
  <script type="text/javascript">
    $(function($) {
      var optionsEST = {
        format: '%H:%M:%S %P EST',
        utc: true,
        utc_offset: -5
      }
      $('#jclock').jclock(optionsEST);
    });
  </script> 	
</div>
