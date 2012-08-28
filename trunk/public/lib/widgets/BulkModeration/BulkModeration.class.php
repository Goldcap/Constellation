<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/ServiceHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/ImageHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/BulkModeration_crud.php';
  
   class BulkModeration_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> newFilms = array(87,95,102,106);
    parent::__construct( $context );
    
    $this -> security = new TheaterSecurity_PageWidget( $context );
    
  }

	function parse() {
	 
	 
   $this -> film = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Theater/query/Screening_list_datamap.xml");
   if ((is_null($this -> film["data"])) ||($this -> film["meta"]["totalresults"] == 0)) {
    redirect("/");
    die();
   }
   
   if ($this -> film["data"][0]["screening_film_status"] != 1) {
    redirect("/");
    die();
   }
   
   $filmstartdate = strtotime($this -> film["data"][0]["screening_film_startdate"]);
   $filmenddate = strtotime($this -> film["data"][0]["screening_film_enddate"]);

	 if($this -> getUser() -> hasCredential(3)) {
	 	 $this -> widget_vars["moderator"] = true;
	 } else {
	 	$this -> redirect("/");
	 }
	 
	 $this -> widget_vars["film_start_date"] = formatDate($this -> film["data"][0]["screening_date"],"prettyshort");
   $this -> widget_vars["film_start_time"] = strtotime($this -> film["data"][0]["screening_date"]);
   $this -> film["data"][0]["film_end_time"] = $this -> widget_vars["film_end_time"] = strtotime($this -> film["data"][0]["screening_end_time"]);
   $this -> widget_vars["thistime"] = date("Y|m|d|H|i|s");
   $this -> widget_vars["counttime"] = date("Y|m|d|H|i|s",$this -> widget_vars["film_start_time"]);
   $this -> widget_vars["runtime"] = (strtotime(now()) - $this -> widget_vars["film_start_time"] > 0) ? (strtotime(now()) - $this -> widget_vars["film_start_time"]) : 0;
   
	 if (($this -> getUser()) && ($this -> getUser() ->hasCredential(2))) {
   	  $this -> film["data"][0]["film_ticket_price"]= 0;
      $this -> film["data"][0]["film_setup_price"]= 0;
   }
   $this -> widget_vars["film"] = $this -> film["data"][0];
   
	 
	 if (($this -> film["data"][0]["screening_user_id"] > 0) && ($this -> film["data"][0]["screening_live_webcam"] == 1)) {
		 $this -> getUser() -> setAttribute("host",$this -> film["data"][0]["screening_unique_key"]);
	   $hObj = new TheaterHosting_PageWidget( $this -> context );
    
	   if($this -> sessionVar("user_id") == $this -> film["data"][0]["screening_user_id"]) {
	    
	    $this -> widget_vars["host"] = $hObj -> as_host = true;
	    $hObj -> hostname = $this -> film["data"][0]["screening_user_full_name"];
	    
		 }
		 $hObj -> parse( $this -> film["data"][0]["screening_video_server_instance_id"] );
		 
		 $this -> widget_vars["tokbox_session"] = $hObj -> tokbox_sessionId;
		 $this -> widget_vars["tokbox_key"] = $hObj -> tokbox_key;
		 $this -> widget_vars["tokbox_token"] = $hObj -> tokbox_token;
		 
		 //$hObj -> genEmbed();
   }
   
   
   if (! $this -> security -> confirm( $this -> film, false )) {
    //dump($this -> security -> state);
    switch($this -> security -> state) {
      case "Screening Time":
        $this -> context ->getLogger()->info("{Theater Token Class} Screening Over");
        $this -> widget_vars["auth_msg"] = "This screening is over.";
        $this -> widget_vars["auth_text"] = '<p>We weren\'t able to find a ticket for you for this screening. You can look in your <a href="/account/purchase" style="color: orange">purchase history</a> to see if you have a ticket to this screening in your account, or you may have purchased this ticket with another account.</p><br />';
        $this -> widget_vars["auth_link"] = '<p>If you need to get a ticket, you can <a href="'.$link.'" style="color: orange">purchase one here</a>.</p><br />';
        $this -> widget_vars["auth_display"] = true;
				$this -> redirect("/film/".$this -> film["data"][0]["screening_film_id"]."?code=exp");
        break;
      case "Not Logged In":
        $this -> context ->getLogger()->info("{Theater Token Class} Not Logged In");
        $this -> widget_vars["auth_msg"] = "Please login or sign-up to view this screening.";
        $this -> widget_vars["auth_text"] = '<p>If you\'re already a member of Constellation.tv, login or signup using the form below.';
        $this -> widget_vars["auth_link"] = 'If you need to get a ticket, you can purchase one on this page afterwards</a>.</p><br />';
        $this -> widget_vars["auth_display"] = true;
				//$this -> redirect("/lobby/".$this -> film["data"][0]["screening_unique_key"]."/".$this -> security -> seat -> getAudienceInviteCode()."?code=exp");
        break;
      case "Sold Out":
        $this -> context ->getLogger()->info("{Theater Token Class} Solt Out");
        $this -> widget_vars["auth_msg"] = "This screening is sold out.";
        $this -> widget_vars["auth_text"] = 'This screening has limited seating, and is sold out. If you already have a ticket, tou can use your <a href="/account/purchase" style="color: blue">purchase history</a> to find your Ticket Code, and use it to swap this ticket for another screening.';
        $this -> widget_vars["auth_link"] = '<p>If you\'d like to get a different ticket, you can <a href="'.$link.'" style="color: orange">purchase one here</a>.</p><br />';
        $this -> widget_vars["auth_display"] = true;
				//$this -> redirect("/film/".$this -> film["data"][0]["screening_film_id"]."?code=so");
        break;
      case "Signature Incorrect":
        $this -> context ->getLogger()->info("{Theater Token Class} Signature Incorrect");
        $this -> widget_vars["auth_msg"] = "This ticket has an incorrect signature. Contact cusomer service if you feel this is incorrect.";
        $this -> widget_vars["auth_text"] = '<p>We weren\'t able to find a ticket for you for this screening. You can look in your <a href="/account/purchase" style="color: orange">purchase history</a> to see if you have a ticket to this screening in your account, or you may have purchased this ticket with another account.</p><br />';
        $this -> widget_vars["auth_link"] = '<p>If you need to get a ticket, you can <a href="'.$link.'" style="color: orange">purchase one here</a>.</p><br />';
        $this -> widget_vars["auth_display"] = true;
				//$this -> redirect("/lobby/".$this -> film["data"][0]["screening_unique_key"]."/".$this -> security -> seat -> getAudienceInviteCode());
        break;
      case "Geo Block":
        $this -> context ->getLogger()->info("{Theater Token Class} Geo Blocked");
        $this -> widget_vars["auth_msg"] = "This screening isn't available in your area. Contact cusomer service if you feel this is incorrect.";
        $this -> widget_vars["auth_text"] = '<p>We weren\'t able to find a ticket for you for this screening. You can look in your <a href="/account/purchase" style="color: orange">purchase history</a> to see if you have a ticket to this screening in your account, or you may have purchased this ticket with another account.</p><br />';
        $this -> widget_vars["auth_link"] = '<p>If you need to get a ticket, you can <a href="'.$link.'" style="color: orange">purchase one here</a>.</p><br />';
        $this -> widget_vars["auth_display"] = true;
				// $this -> redirect("/film/".$this -> film["data"][0]["screening_film_id"]."?code=gb");
        break;
      case "Seat Blocked":
        $this -> context ->getLogger()->info("{Theater Token Class} Ticket Blocked");
        $this -> widget_vars["auth_msg"] = "This ticket is blocked. Contact cusomer service if you feel this is incorrect.";
        $this -> widget_vars["auth_text"] = '<p>We weren\'t able to find a ticket for you for this screening. You can look in your <a href="/account/purchase" style="color: orange">purchase history</a> to see if you have a ticket to this screening in your account, or you may have purchased this ticket with another account.</p><br />';
        $this -> widget_vars["auth_link"] = '<p>If you need to get a ticket, you can <a href="'.$link.'" style="color: orange">purchase one here</a>.</p><br />';
        $this -> widget_vars["auth_display"] = true;
				//$this -> redirect("/lobby/".$this -> film["data"][0]["screening_unique_key"]."/".$this -> security -> seat -> getAudienceInviteCode()."?code=block");
        break;
      default:
        $this -> context ->getLogger()->info("{Theater Token Class} Ticket Not Purchased");
        $this -> widget_vars["auth_msg"] = "You'll need to purchase a ticket to this screening.";
        $this -> widget_vars["auth_text"] = '<p>We weren\'t able to find a ticket for you for this screening. You can look in your <a href="/account/purchase" style="color: orange">purchase history</a> to see if you have a ticket to this screening in your account, or you may have purchased this ticket with another account.</p><br />';
        $this -> widget_vars["auth_link"] = '<p>If you need to get a ticket, you can click here <a href="#" <a class="screening_link" title="'.$this -> film["data"][0]["screening_unique_key"].'" onclick="screening_room.pay()" style="color: orange">to purchase one</a>.</p><br />';
        $this -> widget_vars["auth_display"] = false;
				//$this -> redirect("/film/".$this -> film["data"][0]["screening_film_id"]."/invite/".$this -> getVar("op")."?err=validate");
        break;
    }
    
		
   }
	 
	 $this -> getChatInstance();
	 $this -> widget_vars["stream_url"] = $this -> security -> etoken -> viewingUrl;
   $this -> widget_vars["adt"] =  $this -> security -> etoken -> etoken;
   $this -> widget_vars["seat"] = $this -> security -> seat;

   $sql = "select count(audience_id) from audience where fk_screening_id = ".$this -> film["data"][0]["screening_id"]." and audience_paid_status = 2";
   $res = $this -> propelQuery($sql);
   $val = $res -> fetchall();
   $this -> widget_vars["screening_audience_count"] = $val[0][0];
   
   $this -> widget_vars["gbip"] = $this -> security -> checkGeoBlock($this -> film["data"][0]["screening_film_geoblocking_type"],REMOTE_ADDR());
   
   if ($this -> film["data"][0]["screening_user_id"] > 0){
    $this -> widget_vars["has_host"] = true;
   } else {
    $this -> widget_vars["has_host"] = false;
   }
  
   if(($this -> film["data"][0]["screening_user_id"] > 0)
    && ($this -> film["data"][0]["screening_live_webcam"] == 1)) {
    $this -> widget_vars["has_video"] = true;
   } else {
    $this -> widget_vars["has_video"] = false;
   }
   
   if(is_null($this -> widget_vars["film"]["screening_chat_moderated"])) {
    $this -> widget_vars["film"]["screening_chat_moderated"] = 0;
   }

   return $this -> widget_vars;
   
   
  }
  
  function getChatInstance() {
    $chat = new TheaterChat( $this -> widget_vars, $this -> page_vars, $this -> context );
    $chat -> film = $this -> film;
    $this -> widget_vars = $chat -> getChatInstance();
  }
	
}

?>