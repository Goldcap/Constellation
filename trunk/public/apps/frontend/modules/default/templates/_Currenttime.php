<div class="buzz">
  <h5>Current Time</h5>
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
