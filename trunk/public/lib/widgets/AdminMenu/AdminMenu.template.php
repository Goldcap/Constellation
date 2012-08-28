<div id="sidebar">
	<div class="menu">
		<div class="header">Navigation:</div>
		      
      <?php if ($cred["films"]):?>
		<div class="item"><a href="/film">Film Management</a></div>
	  <? endif ?>
	  <?php if ($cred["screenings"]):?>
      <div class="item"><a href="/screening">Screening Management</a></div>
			<? endif ?>			
      <?php if ($cred["programs"]):?>
      <div class="item"><a href="/program">Program Management</a></div>
			<? endif ?>
      <?php if ($cred["users"]):?>
      <div class="item"><a href="/user">User Management</a></div>
			<? endif ?>
      <?php if ($cred["administrators"]):?>
			<div class="item"><a href="/administrator">Admin Management</a></div>
      <? endif ?>
			<?php if ($cred["payments"]):?>
      <div class="item"><a href="/payment">Payment</a></div>
      <? endif ?>
	  <?php if ($cred["credits"]):?>
        <!--<div class="item"><a href="/credit">Credit Tracking</a></div>-->
	  <? endif ?>
      <?php if ($cred["promotions"]):?>
        <!--<div class="item"><a href="/promotions">Promotions</a></div>-->
	  <? endif ?>
	  <?php if ($cred["code"]):?>
        <div class="item"><a href="/code">Discount Codes</a></div>
			<? endif ?>
      <?php if ($cred["genres"]):?>
      <div class="item"><a href="/genres">Genres</a></div>
			<? endif ?>
      <?php if ($cred["preuser"]):?>
        <!--<div class="item"><a href="/preuser">Pre-Registrations</a></div>-->
			<? endif ?>
      <?php if ($cred["sponsoruser"]):?>
      <div class="item"><a href="/sponsor">Sponsored Users</a></div>
			<? endif ?>
      <?php if ($cred["reviews"]):?>
        <!--<div class="item"><a href="/review">Film Reviews</a></div>-->
			<? endif ?>
      <?php if ($cred["reports"]):?>
      <div class="item"><a href="/report">Usage Reports</a></div>
			<? endif ?>
      <?php if ($cred["feedback"]):?>
        <!--<div class="item"><a href="/feedback">Feedback</a></div>-->
			<? endif ?>
      <?php if ($cred["tests"]):?>
        <!--<div class="item"><a href="/test">Test</a></div>-->
			<? endif ?>  
      <?php if ($cred["log"]):?>
        <!--<div class="item"><a href="/log">Log Data</a></div>-->
			<? endif ?>
      <?php if ($cred["track"]):?>
      <div class="item"><a href="/track">Tracking</a></div>
			<? endif ?> 
      <?php if ($cred["metric"]):?>
      <div class="item"><a href="/metric">Metrics</a></div>
			<? endif ?>
  </div>
	<div class="menu">
		<div class="item"><a href="/docs">
			Documentation
		</a></div>
		<div class="item"><a href="/logout">
			Logout
		</a></div>
	</div>
</div>
