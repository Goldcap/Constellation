<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/ServiceHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/ImageHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/TrackHelper.php"); 
  include_once(sfConfig::get('sf_lib_dir')."/helper/PartnerHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Theater_crud.php';
  
   class Theater_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	var $security;
	var $qa_timeout;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	  $this -> XMLForm -> item_forcehidden = true;
    $this -> newFilms = array(87,95,102,106);
    $this -> qa_timeout = 120;
		parent::__construct( $context );
    
    $this -> security = new TheaterSecurity_PageWidget( $context );
    
    $sxml = new XML();
    $sxml -> loadXML( sfConfig::get("sf_web_dir")."/xml/states.xml");
    $this -> widget_vars["states"] = $sxml -> query("//ASTATE");
    
    $sxml -> loadXML( sfConfig::get("sf_web_dir")."/xml/countries.xml");
    $this -> widget_vars["countries"] = $sxml -> query("//ACOUNTRY");
    
  }

	function parse() {
   
	 $sec = new SSL_PageWidget( $this -> context);
	 
   //if (! $this -> getUser() -> isAuthenticated()) {
		//$sec -> set443();
	 //}
	 
   $this -> film = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Theater/query/Screening_list_datamap.xml");
   //date_default_timezone_set("Europe/Berlin");
	 //kickdump(time()); 
	 /*
	 kickdump(date_default_timezone_get());
	 kickdump(date("Y-m-d H:i:s"));
	 kickdump(strtotime(date("Y-m-d H:i:s")));
	 kickdump($this -> film["data"][0]["screening_end_time"]);
	 kickdump(date("Y-m-d H:i:s",strtotime($this -> film["data"][0]["screening_end_time"])));
	 kickdump(strtotime($this -> film["data"][0]["screening_end_time"]));
	 die();
	 kickdump($this -> film["data"][0]["screening_date"]);
   dump($this -> film["data"][0]["screening_end_time"]);
	 */
	 
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

   if (($filmstartdate > strtotime(date("Y-m-d H:i:s"))) || ($filmenddate < strtotime(date("Y-m-d H:i:s")))) {
		//putLog("USER:: ".$this -> user." | MESSAGE:: STARTDATE-" . $filmstartdate . " ENDDATE-".$filmenddate." TIME:". time());
    //QAMAil("STARTDATE INCORRECT! USER:: ".$this -> user." | MESSAGE:: STARTDATE-" . $filmstartdate . " ENDDATE-".$filmenddate." TIME:". time());
   // die();
   } else {
	  //QAMAil("STARTDATE CORRECT! USER:: ".$this -> user." | MESSAGE:: STARTDATE-" . $filmstartdate . " ENDDATE-".$filmenddate." TIME:". time());
	 }
   
    
	 //Add Facebook and Twitter Beacons
	 $this -> widget_vars["fbeacon"] = "?".getBeaconByType( $this -> sessionVar("user_id"), 2);
   $this -> widget_vars["tbeacon"] = "?".getBeaconByType( $this -> sessionVar("user_id"), 3);
   
	 //For Customized Screenings
   if($this -> film["data"][0]["screening_film_sponsor_id"] > 1) {
    $sql = "select sponsor_domain_template, sponsor_domain_css, sponsor_domain_host_image from sponsor_domain where sponsor_domain_domain = ? limit 1";
    $args[0] = $_SERVER["HTTP_HOST"];
    $res = $this -> propelArgs($sql,$args);
    while ($row = $res -> fetch()) {
      
      $this -> film["data"][0]["screening_sponsor_domain_template"] = $row[0];
      $this -> film["data"][0]["screening_sponsor_domain_css"] = $row[1];
      $this -> film["data"][0]["screening_sponsor_domain_image"] = $row[2];
      
      $this -> widget_vars["screening_sponsor_domain_template"] = $row[0];
      $this -> widget_vars["screening_sponsor_domain_css"] = $row[1];
      $this -> widget_vars["screening_sponsor_domain_image"] = $row[2];
    }
      
    $this -> addCss("/css/sponsor/".$this -> widget_vars["screening_sponsor_domain_template"]."/sponsor_".$this -> widget_vars["screening_sponsor_domain_css"].".css");
    $this -> setTemplate("TheaterSponsor");
   }
   
   if (file_exists(sfConfig::get("sf_lib_dir")."/widgets/Theater/Theater_".$this -> film["data"][0]["screening_film_short_name"].".template.php")) {
	 	//$this -> setTemplate("Theater_".$this -> film["data"][0]["screening_film_short_name"]);
	 	 
	  $this -> addJs("/js/purchase_alt.js");
		$this -> addJs("vowpurchase.js");
	 	$this -> addJs("vowshare.js");
	 	$this -> addJs("/js/swfupload/swfupload.js");
		$this -> addJs("/js/jquery/jquery-asyncUpload-0.1.js");
		$this -> addCss("/css/pages/thevow_share.css");
	 }
   
   $this -> widget_vars["film_start_date"] = formatDate($this -> film["data"][0]["screening_date"],"prettyshort");
   $this -> widget_vars["film_start_time"] = strtotime($this -> film["data"][0]["screening_date"]);
   $this -> film["data"][0]["film_end_time"] = $this -> widget_vars["film_end_time"] = strtotime($this -> film["data"][0]["screening_end_time"]);
   $this -> film["data"][0]["film_block_time"] = strtotime($this -> film["data"][0]["screening_end_time"]) + $this -> qa_timeout * 60;
   
	 $this -> widget_vars["thistime"] = date("Y|m|d|H|i|s");
   $this -> widget_vars["counttime"] = date("Y|m|d|H|i|s",$this -> widget_vars["film_start_time"]);
   $this -> widget_vars["runtime"] = (strtotime(now()) - $this -> widget_vars["film_start_time"] > 0) ? (strtotime(now()) - $this -> widget_vars["film_start_time"]) : 0;
   
	 if (($this -> getUser()) && ($this -> getUser() ->hasCredential(2))) {
   	  $this -> film["data"][0]["film_ticket_price"]= 0;
      $this -> film["data"][0]["film_setup_price"]= 0;
   }
   $this -> widget_vars["film"] = $this -> film["data"][0];
   
   //kickdump(date_default_timezone_get());
   //kickdump($this -> widget_vars["thistime"]);
   //dump($this -> widget_vars["counttime"]);
   
   sfConfig::set("screening_id",$this -> film["data"][0]["screening_id"]);
	 $review = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Theater/query/Review_list_datamap.xml");
   $this -> widget_vars["review"] = $review["data"][0];
	 
   //$this -> showData();
	 //$promotions = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Theater/query/Promotion_list_datamap.xml");
   //$this -> widget_vars["promotions"] = $promotions["data"];
	 
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
   
   if ($this -> film["data"][0]["film_end_time"] < strtotime(now())) {
    $link = '/film/'.$this -> film["data"][0]["screening_film_id"];
   } else {
    $link = '/film/'.$this -> film["data"][0]["screening_film_id"].'/detail/'.$this -> film["data"][0]["screening_unique_key"];
   }
   
   if ($this -> film["data"][0]["screening_film_id"] == 106) {
    // $this -> addJs("/js/videoplayer_program.js"); 
   }
   
   if (! $this -> security -> confirm( $this -> film )) {
    //dump($this -> security -> state);
    switch($this -> security -> state) {
      case "Screening Time":
        $this -> context ->getLogger()->info("{Theater Token Class} Screening Over");
        $this -> widget_vars["auth_msg"] = "This screening is over.";
        $this -> widget_vars["auth_text"] = '<p>We weren\'t able to find a ticket for you for this screening. You can look in your <a href="/account/purchase" style="color: orange">purchase history</a> to see if you have a ticket to this screening in your account, or you may have purchased this ticket with another account.</p><br />';
        $this -> widget_vars["auth_link"] = '<p>If you need to get a ticket, you can <a href="'.$link.'" style="color: orange">purchase one here</a>.</p><br />';
        $this -> widget_vars["auth_display"] = false;
        $this -> redirect("/event/".$this -> film["data"][0]["screening_unique_key"]);
        // $this -> setTemplate('TheaterPre');
				//$this -> redirect("/film/".$this -> film["data"][0]["screening_film_id"]."?code=exp");
        break;
      case "Sold Out":
        $this -> context ->getLogger()->info("{Theater Token Class} Solt Out");
        $this -> widget_vars["auth_msg"] = "This screening is sold out.";
        $this -> widget_vars["auth_text"] = 'This screening has limited seating, and is sold out. If you already have a ticket, tou can use your <a href="/account/purchase" style="color: blue">purchase history</a> to find your Ticket Code, and use it to swap this ticket for another screening.';
        $this -> widget_vars["auth_link"] = '<p>If you\'d like to get a different ticket, you can <a href="'.$link.'" style="color: orange">purchase one here</a>.</p><br />';
        $this -> widget_vars["auth_display"] = false;
				//$this -> redirect("/film/".$this -> film["data"][0]["screening_film_id"]."?code=so");
        break;
      case "Not Logged In":
        $this -> context ->getLogger()->info("{Theater Token Class} Not Logged In");
        $this -> widget_vars["auth_msg"] = "Please login or sign-up to view this screening.";
        $this -> widget_vars["auth_text"] = '<p>If you\'re already a member of Constellation.tv, login or signup using the form below.';
        $this -> widget_vars["auth_link"] = 'If you need to get a ticket, you can purchase one on this page afterwards</a>.</p><br />';
        $this -> widget_vars["auth_display"] = true;
        $this -> setTemplate('TheaterPre');

        //$this -> redirect("/lobby/".$this -> film["data"][0]["screening_unique_key"]."/".$this -> security -> seat -> getAudienceInviteCode()."?code=exp");
        break;
      /*
			case "Ticket In Use":
        $this -> context ->getLogger()->info("{Theater Token Class} Ticket In Use");
        $this -> widget_vars["auth_msg"] = "This ticket is in use. Contact cusomer service if you feel this is incorrect.";
        $this -> widget_vars["auth_text"] = '<p>We weren\'t able to find a ticket for you for this screening. You can look in your <a href="/account/purchase" style="color: orange">purchase history</a> to see if you have a ticket to this screening in your account, or you may have purchased this ticket with another account.</p><br />';
        $this -> widget_vars["auth_link"] = '<p>If you need to get a ticket, you can <a href="'.$link.'" style="color: orange">purchase one here</a>.</p><br />';
        $this -> widget_vars["auth_display"] = true;
				//$this -> redirect("/lobby/".$this -> film["data"][0]["screening_unique_key"]."/".$this -> security -> seat -> getAudienceInviteCode());
        break;
      */
			/*
			case "Signature Incorrect":
        $this -> context ->getLogger()->info("{Theater Token Class} Signature Incorrect");
        $this -> widget_vars["auth_msg"] = "This ticket has an incorrect signature. Contact cusomer service if you feel this is incorrect.";
        $this -> widget_vars["auth_text"] = '<p>We weren\'t able to find a ticket for you for this screening. You can look in your <a href="/account/purchase" style="color: orange">purchase history</a> to see if you have a ticket to this screening in your account, or you may have purchased this ticket with another account.</p><br />';
        $this -> widget_vars["auth_link"] = '<p>If you need to get a ticket, you can <a href="'.$link.'" style="color: orange">purchase one here</a>.</p><br />';
        $this -> widget_vars["auth_display"] = true;
				//$this -> redirect("/lobby/".$this -> film["data"][0]["screening_unique_key"]."/".$this -> security -> seat -> getAudienceInviteCode());
        break;
      */
			case "Geo Block":
        $this -> context ->getLogger()->info("{Theater Token Class} Geo Blocked");
        $this -> widget_vars["auth_msg"] = "This screening isn't available in your area. Contact cusomer service if you feel this is incorrect.";
        $this -> widget_vars["auth_text"] = '<p>We weren\'t able to find a ticket for you for this screening. You can look in your <a href="/account/purchase" style="color: orange">purchase history</a> to see if you have a ticket to this screening in your account, or you may have purchased this ticket with another account.</p><br />';
        $this -> widget_vars["auth_link"] = '<p>If you need to get a ticket, you can <a href="'.$link.'" style="color: orange">purchase one here</a>.</p><br />';
        $this -> widget_vars["auth_display"] = true;
				// $this -> redirect("/film/".$this -> film["data"][0]["screening_film_id"]."?code=gb");
        $this -> redirect("/event/".$this -> film["data"][0]["screening_unique_key"]);
        break;
      /*
			case "Seat Blocked":
        $this -> context ->getLogger()->info("{Theater Token Class} Ticket Blocked");
        $this -> widget_vars["auth_msg"] = "This ticket is blocked. Contact cusomer service if you feel this is incorrect.";
        $this -> widget_vars["auth_text"] = '<p>We weren\'t able to find a ticket for you for this screening. You can look in your <a href="/account/purchase" style="color: orange">purchase history</a> to see if you have a ticket to this screening in your account, or you may have purchased this ticket with another account.</p><br />';
        $this -> widget_vars["auth_link"] = '<p>If you need to get a ticket, you can <a href="'.$link.'" style="color: orange">purchase one here</a>.</p><br />';
        $this -> widget_vars["auth_display"] = true;
				//$this -> redirect("/lobby/".$this -> film["data"][0]["screening_unique_key"]."/".$this -> security -> seat -> getAudienceInviteCode()."?code=block");
        break;
      */
			default:
        $this -> context ->getLogger()->info("{Theater Token Class} Ticket Not Purchased");
        $this -> widget_vars["auth_msg"] = "You'll need to purchase a ticket to this screening.";
        $this -> widget_vars["auth_text"] = '<p>We weren\'t able to find a ticket for you for this screening. You can look in your <a href="/account/purchase" style="color: orange">purchase history</a> to see if you have a ticket to this screening in your account, or you may have purchased this ticket with another account.</p><br />';
        $this -> widget_vars["auth_link"] = '<p>If you need to get a ticket, you can click here <a href="#" <a class="screening_link" title="'.$this -> film["data"][0]["screening_unique_key"].'" onclick="screening_room.pay()" style="color: orange">to purchase one</a>.</p><br />';
        $this -> widget_vars["auth_display"] = false;
				//$this -> redirect("/film/".$this -> film["data"][0]["screening_film_id"]."/invite/".$this -> getVar("op")."?err=validate");
        $this -> setTemplate('TheaterPre');
        break;
    }
    
		//$sec -> set443();
		//There was some issue, so the user "may" need to purchase...
   	//if ($this -> film["data"][0]["screening_film_use_sponsor_codes"] == 1) {
	  //  $this -> addJs("/js/sponsor_alt.js");
	  //} else {
	  //  $this -> addJs("/js/purchase_alt.js"); 
	  //}
	   
	 	//$this -> addJs("/js/bandwidth.js");
	 	$user = new UserPayment_PageWidget( $this -> widget_vars, $this -> page_vars, $this -> context);
	 	$this -> widget_vars["post"] = $user -> parse();
   
   } else {
    $sec -> set80();
    if ($this -> getVar("id") == "detail") {
    	$this -> redirect(preg_replace("/\/detail(.+)?/","",$_SERVER["REQUEST_URI"]));
		} 
	 }
	 
	 if($this -> getUser() -> hasCredential(3)) {
	 	 $this -> widget_vars["moderator"] = true;
	 }
	 
	 $this -> getChatInstance();
	 $this -> widget_vars["stream_url"] = $this -> security -> etoken -> viewingUrl;
   $this -> widget_vars["adt"] =  $this -> security -> etoken -> etoken;
   $this -> widget_vars["seat"] = $this -> security -> seat;

   //Switch for films in the new player
	 //if (in_array($this -> film["data"][0]["screening_film_id"],$this -> newFilms)) {
	 	$this -> widget_vars["video_data"] = $this -> security -> encodeAesData( time() );
	 //} else {
	 //	$this -> widget_vars["video_data"] = $this -> security -> encodeAesData();
	 //}
   //$this -> widget_vars["video_data_test"] = $this -> security -> decodeAesData($this -> widget_vars["video_data"]);
   
   $sql = "select count(audience_id) from audience where fk_screening_id = ".$this -> film["data"][0]["screening_id"]." and audience_paid_status = 2";
   $res = $this -> propelQuery($sql);
   $val = $res -> fetchall();
   $this -> widget_vars["screening_audience_count"] = $val[0][0];
   
   $sql = "select screening_chat_qanda_started from screening where screening_id = ".$this -> film["data"][0]["screening_id"];
   $res = $this -> propelQuery($sql);
   $val = $res -> fetchall();
   
   if($val[0][0] == 1) {
			$this -> widget_vars["screening_qanda_status"] = "running";
   } elseif ($val[0][0] == -1) {
			$this -> widget_vars["screening_qanda_status"] = "closed";
   } else {
			$this -> widget_vars["screening_qanda_status"] = "none";
	 }
	 
	 if ($this -> film["data"][0]["film_block_time"] < time()) {
	   $this -> widget_vars["screening_qanda_status"] = "closed";
	 }
   
   $this -> inviter= new OpenInviter();
   $this -> widget_vars["oi_services"] = $this -> inviter->getPlugins();
   
   $this -> widget_vars["gbip"] = $this -> security -> checkGeoBlock($this -> film["data"][0]["screening_film_geoblocking_type"],REMOTE_ADDR());
   
   if (file_exists(sfConfig::get("sf_app_dir")."/templates/text/facebook_".$this -> getVar("op").".txt")) {
		 	$d = file_get_contents(sfConfig::get("sf_app_dir")."/templates/text/facebook_".$this -> getVar("op").".txt");
			list($picture,$name,$synopsis,$page) = explode("|",$d);
			$this -> setMeta( "og:title", "'" . $name . "' on Constellation.tv" );
			$this -> setMeta( "og:type", "Movie" );
			$this -> setMeta( "og:url", "http://".$_SERVER["SERVER_NAME"]."/".$page );
			$this -> setMeta( "og:image", $picture );
			$this -> setMeta( "og:site_name", "'" .$name . "'' on Constellation.tv" );
			$this -> setMeta( "og:description", $synopsis );
	 } else {
		 $this -> setMeta( "og:title", "'" . $this -> film["data"][0]["screening_film_name"] . "' on Constellation.tv" );
	   $this -> setMeta( "og:type", "Movie" );
	   $this -> setMeta( "og:url", "http://".$_SERVER["SERVER_NAME"]."/film/".$this -> film["data"][0]["screening_film_id"] );
	   $this -> setMeta( "og:image", "http://www.constellation.tv/uploads/screeningResources/".$this -> film["data"][0]["screening_film_id"]."/logo/poster" .$this -> film["data"][0]["screening_film_logo"] );
	   $this -> setMeta( "og:site_name", $this -> film["data"][0]["screening_film_name"] . " on Constellation.tv" );
	   $this -> setMeta( "og:description", strip_tags(str_replace("'","",$this -> film["data"][0]["screening_film_info"])) );
	 }
	 
   if ($this -> film["data"][0]["screening_film_sponsor_id"] > 1) {
    $this -> setTemplate("TheaterSidebarSponsor");
   }
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

	 $this -> widget_vars["partner"] = checkPartner($this -> context,false,$this -> widget_vars["film"]["screening_film_id"]);



	 
   return $this -> widget_vars;
   
   
  }
  
  function getChatInstance() {
    $chat = new TheaterChat( $this -> widget_vars, $this -> page_vars, $this -> context );
    $chat -> film = $this -> film;
    $this -> widget_vars = $chat -> getChatInstance();
  }
}
?>
