<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  include_once(sfConfig::get('sf_lib_dir')."/helper/OrderHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/UserHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/CryptHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/ServiceHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/PartnerHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/FilmMarquee_crud.php';
  
   class FilmMarquee_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
	 
	 
	 checkPartner($this -> context,false,$this -> getVar("op"));
	   
	 //This removes users who are "temp" users
   clearUser( $this -> getUser() );
   
   if ($this -> getVar("op") == "list") {
    redirect("/");
    die();
   }
   $this -> widget_vars["program"] = false;
   
   if (! is_numeric($this -> getVar("op"))) {
    //$this -> showData();
    $program = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/FilmMarquee/query/Program_list_datamap.xml");
    if($program["meta"]["totalresults"] > 0) {
      $this -> widget_vars["program"] = true;
      $this->setgetVar("op",$program["data"][0]["fk_film_id"]);
    } else {
      $this -> redirect("/");
    }
   }
   
   //dump($program["data"][0]["program_hostbyrequest_price"]);
   $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/FilmMarquee/query/Film_list_datamap.xml");
   
   if ($list["data"][0]["film_status"] != 1) {
	 	redirect('/');
	 	die();
	 }
   
   $filmstartdate = strtotime($list["data"][0]["film_startdate"]);
   $filmenddate = strtotime($list["data"][0]["film_enddate"]);
   
   if (($filmstartdate > time()) || ($filmenddate < time())) {
    putLog("USER:: ".$this -> user." | MESSAGE:: STARTDATE-" . $filmstartdate . " ENDDATE-".$filmenddate." TIME:". time());
    QAMAil("USER:: ".$this -> user." | MESSAGE:: STARTDATE-" . $filmstartdate . " ENDDATE-".$filmenddate." TIME:". time());
   // die();
   }
   
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
   
   $this -> security = new TheaterSecurity_PageWidget( $this -> context );
   $this -> security -> checkPreUser( $list["data"][0]["film_id"] );
	 
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
    //$this -> setTemplate("FilmViewSponsor");
   }
   
   if ($list["data"][0]["film_use_sponsor_codes"] == 1) {
    $this -> addJs("https://".sfConfig::get("app_domain")."/js/sponsor_alt.js");
   } else {
    //$this -> addJs("https://".sfConfig::get("app_domain")."/js/purchase_alt.js"); 
   }
   
   $list["data"][0]["film_alternate_template"] = 0;
   
   if (($this -> getUser()) && ($this -> getUser() ->hasCredential(2))) {
      $list["data"][0]["film_ticket_price"]= 0;
      $list["data"][0]["film_setup_price"]= 0;
   }
    
   //GeoBlock Settings
   $security = new TheaterSecurity_PageWidget($this -> context);
   $this -> widget_vars["gbip"] = $security -> checkGeoBlock($list["data"][0]["film_geoblocking_type"],REMOTE_ADDR());
   $this -> widget_vars["film"] = $list["data"][0];
   
   //Trailer URL
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
   
   //Credit Card Prefill
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
   
   //Invites
   $this -> inviter= new OpenInviter();
   $this -> widget_vars["oi_services"] = $this -> inviter->getPlugins();
   
   //http://developers.facebook.com/tools/debug
   $this -> widget_vars["title"] = "Constellation | Your Online Movie Theater | '" . $list["data"][0]["film_name"] . "'";
   $this -> setMeta( "og:title", "'" . $list["data"][0]["film_name"] . "' on Constellation.tv" );
   $this -> setMeta( "og:type", "Movie" );
   $this -> setMeta( "og:url", "http://".$_SERVER["SERVER_NAME"]."/film/".$list["data"][0]["film_id"] );
   $this -> setMeta( "og:image", "http://www.constellation.tv/uploads/screeningResources/".$list["data"][0]["film_id"]."/logo/poster" .$list["data"][0]["film_logo"] );
   $this -> setMeta( "og:site_name", $list["data"][0]["film_name"] . " on Constellation.tv" );
   $this -> setMeta( "og:description", strip_tags(str_replace("'","",$list["data"][0]["film_info"])) );
	 
	 return $this -> widget_vars;
   
  }

}

?>
