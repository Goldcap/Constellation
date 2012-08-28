<!-- START MAIN CONTENT -->
<div id="lobby_panel" class="panel">  	

	<?php if($sponsor > 1) { ?>
		<h1>THEATER LOBBY</h1>
	<?php } else { ?>
		<h1>YOU HAVE BEEN DIRECTED INTO THE LOBBY</h1>
  <?php } ?>
	
  <?php if (isset($film)) { ?>
  <div class="filminfo">
    
    <p id="startdate"><?php echo formatDate($film["screening_date"],"prettyshort");?></span></p> 
    
    <p>Showing of "<?php echo $film["screening_film_name"];?>"<br />
    <?php if ($film["screening_user_full_name"] != "") { ?>Hosted by: <?php echo $film["screening_user_full_name"];?></p><?php } ?>
    
  </div>
  <?php } else { ?>
  <div class="filminfo">
    
    <p id="startdate">CONSTELLATION.TV screens the worlds greatest movies directly to your laptop or home computer.</span></p> 
    
    <p>Host your own screenings of the latest popular and award-winning films, and invite friends to join you.</p>
    
  </div>
  
  <?php } ?>
  
  <?php if ($_GET["code"] == "block") {?>
  
    <!-- MESSAGE FOR BLOCKED USER -->  
	  <div class="errormessage">
    
      	<p> You have been blocked from this screening by the system administrator.</p>
    
    </div>
  <?php } elseif ($_GET["code"] == "exit") {?>
  
    <!-- MESSAGE FOR EXPIRED SCREENING -->  
    <div class="errormessage">
      	
      	<p>You have exited the theater. Click below to re-enter.</p>
      	
      	<button class="btn_medium" onclick="adminmessage.captureSeat()">Enter Theater</button>
    
    </div>
    
  <?php } elseif ($_GET["code"] == "exp") {?>
  
    <!-- MESSAGE FOR EXPIRED SCREENING -->  
    <div class="errormessage">
  			<p>This screening is now over.  Thank you for coming!</p>
    </div>
    
  <?php } elseif($sponsor > 1) { ?>
	
    <?php if (($film) && ($sf_user -> isAuthenticated()) && ($owner == $sf_user -> getAttribute("user_id"))) {?>
		<!-- MESSAGE FOR SPONSOR USERS WHO OWN SEAT -->
    <div class="errormessage">
    
      <button class="btn_medium" onclick="adminmessage.captureSeat()">Re-enter Theater</button>
    
    </div>
    <?php } else {?>
    <!-- MESSAGE FOR SPONSOR USERS WHO DON'T OWN SEAT -->
    <div class="errormessage">
    
      <p>Your session has expired, or this is not your seat.</p>
      
      <p>Please re-enter your code, or join the screening as a different user.</p>
      
      <a href="/film/<?php echo $film["screening_film_id"];?>/detail/<?php echo $film["screening_unique_key"];?>"><button class="btn_medium" onclick="window.location.href='/film/<?php echo $film["screening_film_id"];?>/detail/<?php echo $film["screening_unique_key"];?>';">Re-enter Code</button></a>
      <!--<a href="/theater/<?php echo $film["screening_unique_key"];?>/<?php echo $vars["seat"]->getAudienceInviteCode() ?>"><button class="btn_medium" onclick="window.location.href='/theater/<?php echo $film["screening_unique_key"];?>/<?php echo $vars["seat"]->getAudienceInviteCode() ?>';">Re-enter Theater</button></a>-->
    
    </div>
    <?php } ?>
    
  <?php } elseif (($film) && ($sf_user -> isAuthenticated()) && ($owner == $sf_user -> getAttribute("user_id"))) {?>

    <!-- MESSAGE FOR SOMEONE WHO OWNS SEAT -->
    <div class="errormessage">
      	
      	<p>The seat you purchased is already in use, or was left vacant by a user leaving the theater.</p>
      	
      	<p>Click here to recapture this ticket, and remove anyone other than yourself from that seat. Note, anyone currently using this seat will be redirected to the lobby.</p>
      	
      	<button class="btn_medium" onclick="adminmessage.captureSeat()">Enter Theater</button>
    </div>
  
  <?php } elseif (($film) && ($seat) && ($surrender)) {?>
    
    <!-- MESSAGE FOR SOMEONE WHO HAS SURRENDERED -->
    <div class="errormessage">
      	
      	<p>You have been removed from the thater because the purchaser of this ticket has taken the seat you were using.</p>
      	
      	<p>Click here to buy a ticket to a screening of this film.</p>
      	
      	<a href="/film/<?php echo $film["screening_film_id"];?>/detail/<?php echo $film["screening_unique_key"];?>"><button class="btn_medium">Buy Ticket</button></a>
    </div>
  
  <?php } elseif (($film) && ($film)) {?>
    
    <!-- MESSAGE FOR SOMEONE WHO IS KICKED -->
    <div class="errormessage">
      	
      	<p>You have been removed from the thater because the seat you requested is already in use.</p>
      	
      	<p>Click here to request this seat from the current occupant, or purchase an additional ticket.</p>
      	
      	<p>
        <button class="btn_medium" onclick="adminmessage.requestSeat()">Request Seat</button>
      	<a href="/film/<?php echo $film["screening_film_id"];?>/detail/<?php echo $film["screening_unique_key"];?>"><button class="btn_medium" onclick="window.location.href='/film/<?php echo $film["screening_film_id"];?>/detail/<?php echo $film["screening_unique_key"];?>';">Buy Additional Ticket</button></a>
        </p>
        
        <p>If you are the purchaser of this seat, please login, and you'll be allowed to re-occupy the seat.</p>
      	
      	<button class="btn_medium" onclick="login.showpopup()">Login</button>
      	
    </div>
  
  <?php } else {?>
    <!-- GENERIC MESSAGE FOR ANYONE, NO FILM -->
    <div class="errormessage">
      	
      	<?php if ($_GET["code"] == "exp") {?>
      	<p> This screening occurred on <?php print formatDate($film["screening_film_date"],"pretty");?>, and is now closed. Click below to purchase a ticket to another screening of this film.</p>
      	<a href="/"><button class="btn_medium">Buy Tickets</button></a>
        <?php } elseif ($_GET["code"] == "gb") {?>
      	<p> This screening is not available in your area.</p>
      	<?php } else { ?>
      	<p>To view upcoming screenings, and to purchase tickets, click the button below.</p>
      	<a href="/"><button class="btn_medium">Buy Tickets</button></a>
      	<?php } ?>
      	
      	
    </div>
  <?php } ?>
  

</div>
<!-- END MAIN CONTENT -->

<span class="reqs" id="starttime">0</span>
<span class="reqs" id="reviewtime">0</span>
<span class="reqs" id="promotime">0</span>
<span class="reqs" id="qatime">0</span> 
