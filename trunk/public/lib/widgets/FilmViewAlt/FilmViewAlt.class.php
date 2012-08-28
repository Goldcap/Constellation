<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/OrderHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/UserHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/CryptHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/ServiceHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Film_crud.php';
  
  class FilmViewAlt_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	  $this -> XMLForm -> item_forcehidden = true;
    parent::__construct( $context );
    
    $sxml = new XML();
    $sxml -> loadXML( sfConfig::get("sf_web_dir")."/xml/states.xml");
    $this -> widget_vars["states"] = $sxml -> query("//ASTATE");
    
    $sxml -> loadXML( sfConfig::get("sf_web_dir")."/xml/countries.xml");
    $this -> widget_vars["countries"] = $sxml -> query("//ACOUNTRY");
    
  }

	function parse() {
	
   //This removes users who are "temp" users
   clearUser( $this -> getUser() );
   
   if ($this -> getVar("op") == "list") {
    redirect("/");
    die();
   }
   $this -> widget_vars["program"] = false;
   
   if (! is_numeric($this -> getVar("op"))) {
    //$this -> showData();
    $program = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/FilmView/query/Program_list_datamap.xml");
    if($program["meta"]["totalresults"] > 0) {
      $this -> widget_vars["program"] = true;
      $this->setgetVar("op",$program["data"][0]["fk_film_id"]);
    } else {
      $this -> redirect("/");
    }
   }
   
   //dump($program["data"][0]["program_hostbyrequest_price"]);
   $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/FilmView/query/Film_list_datamap.xml");
  
   //If we have a program here, substitute those values
   if ($this -> widget_vars["program"] == true) {
    foreach ($program["data"][0] as $key => $value) {
      if ($key == "program_id") { 
        $list["data"][0][$key] = $value;
        continue ; 
      } if ($key == "program_still_image") { 
        $list["data"][0][$key] = $value;
        continue ; 
      } if ($key == "program_logo") { 
        $list["data"][0][$key] = $value;
        continue ; 
      } if ($key == "program_background_image") { 
        $list["data"][0][$key] = $value;
        continue ; 
      } else if ($key == "program_name") { 
        $list["data"][0][$key] = $value;
        continue ; 
      } else if ($key == "program_short_name") { 
        $list["data"][0][$key] = $value;
        continue ; 
      } else if ($key == "program_info") { 
        $list["data"][0][$key] = $value;
        continue ; 
      }else 
      if ($key == "program_start_date") { 
        $list["data"][0]["film_startdate"] = $value;
        continue ; 
      } else 
      if ($key == "program_end_date") { 
        $list["data"][0]["film_enddate"] = $value;
        continue ; 
      } elseif ($value != '') {
        $list["data"][0][str_replace("program","film",$key)] = $value;
      }
    }
   }
   
   $vars = unserialize($list["data"][0]["film_alternate_opts"]);
   $list["data"][0]["film_show_title"] = $vars["film_show_title"];
   $list["data"][0]["film_text_color"] = $vars["film_text_color"];
   
   $reviews = $list["data"][0]["film_user_reviews"];
   if (count($reviews) > 0) {
   foreach ($reviews as $review) {
    $drev = explode("|",$review);
    if (count($drev) > 0) {
      if ($drev[2] != '') {
        $this -> widget_vars["reviews"][] = '"'.$drev["0"].'" - '.$drev["2"];
      }}
   }}
   if ($list["data"][0]["film_review"] != '') {
    $frev = explode("|",$list["data"][0]["film_review"]);
    if (count($frev) > 0) {
    foreach ($frev as $review) {
     if ($review != '') {
        $this -> widget_vars["reviews"][] = '<strong>'.strip_tags($review).'</strong>';
     }}}
   }
   
   $this -> security = new TheaterSecurity_PageWidget( $this -> context );
   $this -> security -> checkPreUser( $list["data"][0]["film_id"] );
	 
   //Screening List
   //Add the Film Running time to "Midnight"
   //TSCeiling is a Timestamp Ceiling (i.e. 11:59:59 today)
   $starttime = formatDate(now(),'TSCeiling');
   $times = explode(":",$list["data"][0]["film_running_time"]);
   $totaltime = ($times[0] * 3600) + ($times[1] * 60) + ($times[2]);
   //Add Running time in seconds to "TSCeiling as Epoch"
   $endtime = (strtotime($starttime) + $totaltime);
   
   //Now until Midnight Plus Running Time
   //sfConfig::set("startdate","[".formatDate(now(),"W3XMLIN")." TO ".formatDate($endtime,"W3XMLIN")."]");
   sfConfig::set("startdate","[".formatDate(now(),"W3XMLIN")." TO * ]");
   
   //dump(sfConfig::get("startdate"));
   //$this -> showData(); 
   $screens = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/FilmView/query/ScreeningMember_list_datamap.xml");
   $i=0;
   if ($screens["meta"]["totalresults"] > 0) {
   foreach ($screens["data"] as $afilm) {
     $sql = "select count(audience_id) from audience where fk_screening_id = ".$afilm["screening_id"]." and audience_paid_status = 2";
     $res = $this -> propelQuery($sql);
     $val = $res -> fetchall();
     $screens["data"][$i]["screening_audience_count"] = $val[0][0];
     $i++;
   }}
   
	 $this -> widget_vars["screenings"] = $screens["data"];
   
   //For Customized Screenings
   if($list["data"][0]["film_sponsor_id"] > 1) {
    $sql = "select sponsor_domain_template, sponsor_domain_css, sponsor_domain_host_image from sponsor_domain where sponsor_domain_domain = ? limit 1";
    $args[0] = $_SERVER["HTTP_HOST"];
    $res = $this -> propelArgs($sql,$args);
    while ($row = $res -> fetch()) {
      $list["data"][0]["screening_sponsor_domain_template"] = $row[0];
      $list["data"][0]["screening_sponsor_domain_css"] = $row[1];
      $list["data"][0]["screening_sponsor_domain_image"] = $row[2];
    }
    $this -> addCss("/css/sponsor/".$list["data"][0]["screening_sponsor_domain_template"]."/sponsor_".$list["data"][0]["screening_sponsor_domain_css"].".css");
    //This template allows for "HOSTING", which is disabled for sponsors
    //$this -> setTemplate("FilmViewSponsor");
    //This template has no hosting, no purchase, and just an "Enter Code" Field
    $this -> setTemplate("FilmViewSponsor");
   }
   
   if ($list["data"][0]["film_use_sponsor_codes"] == 1) {
    $this -> addJs("https://".sfConfig::get("app_domain")."/js/sponsor_alt.js");
   } else {
    $this -> addJs("https://".sfConfig::get("app_domain")."/js/purchase_alt.js"); 
   }
   
   if ($list["data"][0]["film_alternate_template"] != 0) {
    //$this -> addCss("/css/alternate/alternate_".$list["data"][0]["film_alternate_template"].".css");
    //$this -> addJs("/js/alternate/alternate_".$list["data"][0]["film_alternate_template"].".js");
    
    $startdate = time();
    
    $times = explode(":",$list["data"][0]["film_running_time"]);
    $totaltime = ($times[0] * 3600) + ($times[1] * 60) + ($times[2]);
    $enddate = strtotime($_GET["date"]." 00:00:00") + 86400 + $totaltime;
  
    //sfConfig::set("startdate","[".formatDate($startdate,"W3XMLIN")." TO ".formatDate($enddate,"W3XMLIN")."]");
    sfConfig::set("startdate","[".formatDate($startdate,"W3XMLIN")." TO * ]");
    sfConfig::set("film_id",$list["data"][0]["film_id"]);
    
    //$this -> showData();
    $slist = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/ScreeningSponsor_list_datamap.xml");
    $i=0;
    if ($slist["meta"]["totalresults"] > 0) {
    foreach ($slist["data"] as $afilm) {
      $sql = "select count(audience_id) from audience where fk_screening_id = ".$afilm["screening_id"]." and audience_paid_status = 2";
      $res = $this -> propelQuery($sql);
      $val = $res -> fetchall();
      $slist["data"][$i]["screening_audience_count"] = $val[0][0];
      $i++;
    }}
    
    $this -> widget_vars["screen"] = $slist["data"][0];
    
    $this -> widget_vars["thistime"] = date("Y|m|d|H|i|s");
    $this -> widget_vars["counttime"] = date("Y|m|d|H|i|s",strtotime($this -> widget_vars["screen"]["screening_date"]));
    
    if (! is_null($this -> widget_vars["screen"]["screening_id"])) {
      $sql = "select count(audience_id) 
              from audience 
              inner join payment
              on fk_payment_id = payment_id
              where audience.fk_screening_id = ".$this -> widget_vars["screen"]["screening_id"]." 
              and payment.payment_status = 2";
      $res = $this -> propelQuery($sql);
      while ($row = $res -> fetch()) {
        $a_seatcount = $row[0];
      }
      $this -> widget_vars["seatcount"] = $this -> widget_vars["screen"]["screening_total_seats"] - $a_seatcount;
      $this -> widget_vars["screening"] = $this -> widget_vars["screen"]["screening_unique_key"];
    } else {
      $list["data"][0]["film_alternate_template"] = 0;
      //$this -> addJs("/js/upcoming.js");
    }
    //dump($this -> counttime);
    
   } else {
    //$this -> addJs("/js/upcoming.js");
   }
   
   //Next Screening
   //$this -> showData();
   //dump(sfConfig::get("startdate"));
   //dump(now());
	 $next = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/ScreeningFilmNext_list_datamap.xml");
	 
   $i=0;
   
	 if ($next["meta"]["totalresults"] > 0) {
   foreach ($next["data"] as $afilm) {
	  $sql = "select count(audience_id) from audience where fk_screening_id = ".$afilm["screening_id"]." and audience_paid_status = 2";
    $res = $this -> propelQuery($sql);
    $val = $res -> fetchall();
    $next["data"][$i]["screening_audience_count"] = $val[0][0];
    $i++;
    
   }}
   
   $this -> widget_vars["next"] = $next["data"];
  
   //Featured Screenings
   //$this -> showData();
   if ($next["meta"]["totalresults"] > 0) {
   	sfConfig::set("sid","".$next["data"][0]["screening_id"]);
   } else {
	 	sfConfig::set("sid",0);
	 }
   $carousel = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/ScreeningFilmFeaturedAlt_list_datamap.xml");
	 
   $i=0;
   
	 if ($carousel["meta"]["totalresults"] > 0) {
   foreach ($carousel["data"] as $afilm) {
	  $sql = "select count(audience_id) from audience where fk_screening_id = ".$afilm["screening_id"]." and audience_paid_status = 2";
    $res = $this -> propelQuery($sql);
    $val = $res -> fetchall();
    $carousel["data"][$i]["screening_audience_count"] = $val[0][0];
    $i++;
    
   }}
   
   $this -> widget_vars["carousel"] = $carousel["data"];
   
   if (($this -> getUser()) && ($this -> getUser() ->hasCredential(2))) {
      $list["data"][0]["film_ticket_price"]= 0;
      $list["data"][0]["film_setup_price"]= 0;
   }
    
   $security = new TheaterSecurity_PageWidget($this -> context);
   $this -> widget_vars["gbip"] = $security -> checkGeoBlock($list["data"][0]["film_geoblocking_type"],REMOTE_ADDR());
   $this -> widget_vars["film"] = $list["data"][0];
   
   $etoken = new TheaterAkamaiEtoken( $this -> context, null );
   $etoken -> streamType = "film";
   $etoken -> generateViewUrl( $list, "", 84600 );
   $this -> widget_vars["film"]["stream_url"] = $etoken -> viewingUrl;
   
   if (($list["data"][0]["film_startdate"] != '') && ($list["data"][0]["film_startdate"] != '12/31/69')) {
    $os = ceil((strtotime($list["data"][0]["film_startdate"]) - strtotime(now()))/86400);
    $this -> widget_vars["film_start_offset"] = ($os < 0 ) ? 0 : $list["data"][0]["film_startdate"];
   } else {
    $this -> widget_vars["film_start_offset"] = 0;
   }
   if ($this -> widget_vars["film_start_offset"] == 0) {
    $this -> widget_vars["film_start_offset"] =  date("Y|m|d|H|i");
   } else {
    $this -> widget_vars["film_start_offset"] = date("Y|m|d|H|i",strtotime($this -> widget_vars["film_start_offset"]));
   }
   
   if (($list["data"][0]["film_enddate"] != '') && ($list["data"][0]["film_enddate"] != '12/31/69')) {
    $os = ceil((strtotime($list["data"][0]["film_enddate"]) - strtotime(now()))/86400);
    $this -> widget_vars["film_end_offset"] = ($os < 0 ) ? 0 : $list["data"][0]["film_enddate"];
   } else {
    $this -> widget_vars["film_end_offset"] = 0;
   }
   
   if ($this -> widget_vars["film_end_offset"] == 0) {
    $date = date("Y-m-d");// current date
    $date = strtotime(date("Y-m-d", strtotime($date)) . " +12 months");
    $this -> widget_vars["film_end_offset"] =  date("Y|m|d|H|i",$date);
   } else {
    $this -> widget_vars["film_end_offset"] = date("Y|m|d|H|i",strtotime($this -> widget_vars["film_end_offset"]));
   }
   
   if ($this -> getVar("rev")) {
    $this -> widget_vars["screening"] = $this -> getVar("rev");
   }
   
   $user = getUserById( $this -> getUser() -> getAttribute("user_id") );
   
   if ((sfConfig::get("sf_environment") == "dev") || (sfConfig::get("sf_environment") == "stage")) {
    
      //TESTING DATA
      $this -> post["email"] = $this -> sessionVar("user_email");
      $this -> post["email_confirm"] = $this -> sessionVar("user_email");
      $this -> post["b_address1"] = "1 Main Street";
      $this -> post["b_address2"] = "";
      $this -> post["b_city"] = "San Jose";
      $this -> post["b_state"] = "CA";
      $this -> post["b_zipcode"] = "95131";
      $this -> post["b_country"] = "US";
      $this -> post["cc_exp_month"] = "3";
      $this -> post["cc_exp_year"] = "2012";
      $this -> post["card_number"] = "4220496880267261";
      $this -> post["security_code"] = "962";
   
   }
   
   if($user) {
      $this -> post["first_name"] = $user -> getUserFname();
      $this -> post["last_name"] = $user -> getUserLname();
      $this -> post["email"] = $user -> getUserEmail();
      $this -> post["email_confirm"] = $user -> getUserEmail();
      
      //Check for email in database
      $c = new Criteria();
      $c->add(PaymentPeer::FK_USER_ID,$user -> getUserId());
      $c -> setLimit(1);
      $c -> addDescendingOrderByColumn("payment_id");
      $payment = PaymentPeer::doSelect($c);
      if($payment) {
        
        if ($user -> getUserEmail() == "") {
          $this -> post["email"] = $payment[0] -> getPaymentEmail();
          $this -> post["email_confirm"] = $payment[0] -> getPaymentEmail();
        }
        $this -> post["b_address1"] = $payment[0] -> getPaymentBAddr1();
        $this -> post["b_address2"] = $payment[0] -> getPaymentBAddr2();
        $this -> post["b_city"] = $payment[0] -> getPaymentBCity();
        $this -> post["b_state"] = $payment[0] -> getPaymentBState();
        $this -> post["b_zipcode"] = $payment[0] -> getPaymentBZipcode();
        $this -> post["b_country"] = $payment[0] -> getPaymentBCountry();
      }
    }
    
   if ($this -> getVar("rev")) {
    $this -> widget_vars["screening"] = $this -> getVar("rev");
   }
   $this -> widget_vars["post"] = $this -> post;
   $chat = new TheaterChat( $this -> widget_vars, $this -> page_vars, $this -> context );
   $this -> widget_vars = $chat -> getHistoryInstance();
   
   $this -> inviter= new OpenInviter();
   $this -> widget_vars["oi_services"] = $this -> inviter->getPlugins();
   
   //http://developers.facebook.com/tools/debug
   $this -> setMeta( "og:title", "'" . $list["data"][0]["film_name"] . "' on Constellation.tv" );
   $this -> setMeta( "og:type", "Movie" );
   $this -> setMeta( "og:url", "http://".$_SERVER["SERVER_NAME"]."/film/".$list["data"][0]["film_id"] );
   $this -> setMeta( "og:image", "http://www.constellation.tv/uploads/screeningResources/".$list["data"][0]["film_id"]."/logo/poster" .$list["data"][0]["film_logo"] );
   $this -> setMeta( "og:site_name", $list["data"][0]["film_name"] . " on Constellation.tv" );
   $this -> setMeta( "og:description", strip_tags(str_replace("'","",$list["data"][0]["film_info"])) );
        
   return $this -> widget_vars;
   
  }

  function doPost(){
     
     if ($this -> XMLForm -> validateForm()) {
        switch ($this -> getFormMethod()) {
          case "submit":
          $this -> crud -> write();
          break;
          case "delete":
          $this -> crud -> remove();
          break;
        }
      }
    
  }

  function doGet(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      $this -> pushItem();
    }
    
  }

  function drawPage(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      return $this -> returnForm();
    } elseif ($this ->getOp() == "list" ) {
      return $this -> returnList();
    }
    
  }

	}

  ?>
