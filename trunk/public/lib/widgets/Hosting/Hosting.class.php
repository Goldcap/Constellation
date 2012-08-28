<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  include_once(sfConfig::get('sf_lib_dir')."/helper/OrderHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/ContentHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/UserHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/CryptHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/ServiceHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Hosting_crud.php';
  
   class Hosting_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
    
    $sxml = new XML();
    $sxml -> loadXML( sfConfig::get("sf_web_dir")."/xml/states.xml");
    $this -> widget_vars["states"] = $sxml -> query("//ASTATE");
    
    $sxml -> loadXML( sfConfig::get("sf_web_dir")."/xml/countries.xml");
    $this -> widget_vars["countries"] = $sxml -> query("//ACOUNTRY");
    
  }

	function parse() {
		
		if (! $this -> getVar("id")) {
			$this -> setTemplate("HostingSelect");
	    //$this -> showData();
	    $films = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Hosting/query/HostingFilm_list_datamap.xml");
   		
	    //Upcoming Screenings
	    //$this -> showData();
	    sfConfig::set("startdate","[".formatDate(now(),"W3XMLIN")." TO ".formatDate(formatDate(now(),'TSCeiling'),"W3XMLIN")."]");
	    $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Body/query/Screening_list_datamap.xml");
	    
			$this -> widget_vars["fid"] = $this -> getVar("fid");
    	$this -> widget_vars["json"] = jsonFilms( $this -> context, $films, $list );
    	$this -> widget_vars["films"] = $films["data"];
   		
			return $this -> widget_vars;
		}
		
   //dump($program["data"][0]["program_hostbyrequest_price"]);
   $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/FilmView/query/Film_list_datamap.xml");
   
	 //GeoBlock Settings
   $security = new TheaterSecurity_PageWidget($this -> context);
   $this -> widget_vars["gbip"] = $security -> checkGeoBlock($list["data"][0]["film_geoblocking_type"],REMOTE_ADDR());
   $this -> widget_vars["film"] = $list["data"][0];
   
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
   
   if (($this -> getUser()) && ($this -> getUser() ->hasCredential(2))) {
      $list["data"][0]["film_ticket_price"]= 0;
      $list["data"][0]["film_setup_price"]= 0;
   }
    
	 $this -> widget_vars["film"] = $list["data"][0];
	 
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
      
  	 	$user = new UserPayment_PageWidget( $this -> widget_vars, $this -> page_vars, $this -> context);
  	 	$this -> widget_vars["post"] = $user -> parse();
    }
    
   if ($this -> getVar("rev")) {
    $this -> widget_vars["screening"] = $this -> getVar("rev");
   }
   
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
}

?>
