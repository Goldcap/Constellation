<?php

  include_once(sfConfig::get('sf_lib_dir')."/helper/ImageHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/TrackHelper.php"); 

  include_once(sfConfig::get('sf_lib_dir')."/helper/OrderHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/ContentHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/UserHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/CryptHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/ServiceHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/PartnerHelper.php");

class Checkout_PageWidget extends Widget_PageWidget 
{
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> context = $context;
    $this -> user = $this -> getUser();
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));

    parent::__construct( $context );

  }

	function parse() {

		$sxml = new XML();
    $sxml -> loadXML( sfConfig::get("sf_web_dir")."/xml/states.xml");
    $this -> widget_vars["states"] = $sxml -> query("//ASTATE");
    
    $sxml -> loadXML( sfConfig::get("sf_web_dir")."/xml/countries.xml");
    $this -> widget_vars["countries"] = $sxml -> query("//ACOUNTRY");
		
    if (($this -> getVar("dohbr")) && ($this -> getVar("film") > 0)) {
			$this -> film = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Checkout/query/Film_list_datamap.xml"); 
    	$filmid = $this -> film["data"][0]["screening_film_id"];
    	$security = new TheaterSecurity_PageWidget($this -> context);
	  	$this -> widget_vars["gbip"] = $security -> checkGeoBlock($this -> film["data"][0]["film_geoblocking_type"],REMOTE_ADDR());
			if ($this -> widget_vars["gbip"]) {
				$this -> redirect('/film/'.$this -> film["data"][0]["screening_film_id"]);
			}
		} else {
			$this -> film = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Checkout/query/Screening_list_datamap.xml"); 
			$filmid = $this -> film["data"][0]["screening_film_id"];
			$security = new TheaterSecurity_PageWidget($this -> context);
	  	$this -> widget_vars["gbip"] = $security -> checkGeoBlock($this -> film["data"][0]["screening_film_geoblocking_type"],REMOTE_ADDR());
			if ($this -> widget_vars["gbip"]) {
				$this -> redirect('/film/'.$this -> film["data"][0]["screening_film_id"].'?code=gb');
			}
      
		}
    if(! $this -> user -> isAuthenticated()){
      $this -> addJs("/js/CTV.Login.js");
      $this -> setTemplate('Login');
    }

		
    if(!$this -> checkSoldOut()){
        $this -> redirect('/theater/'.$this -> film["data"][0]["screening_unique_key"]);
    } else if($this -> getVar("id") == '21jumpstlive'){
      $audience = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Checkout/query/Audience_list_datamap.xml");      
      if ($audience ["data"][0]["audience_payment_id"] > 0) {
        $this -> redirect($audience ["data"][0]["audience_short_url"]);
      } 

      $facebook = new Facebook_PageWidget( $this -> widget_vars, $this -> page_vars, $this -> context);
      $this -> widget_vars["facebookFriends"] = $facebook -> getFriends();
      $this -> widget_vars["film"] = $this -> film["data"][0];
      $this -> widget_vars["fBeacon"] = "?".getBeaconByType( $this -> sessionVar("user_id"), 2);
      $this -> widget_vars["tBeacon"] = "?".getBeaconByType( $this -> sessionVar("user_id"), 3);      
      $this -> setTemplate('Confirmation');
      
	    $order = new OrderManager_PageWidget( $this -> widget_vars, $this -> page_vars, $this -> context, null );
	    $audience = $order ->instantPurchase( $this -> sessionVar("user_id"), $this -> getVar("id"), $this -> film, "21jump" );
			
    } else {
			if(!$this -> getVar("dohbr")){
        $audience = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Checkout/query/Audience_list_datamap.xml");      
      }
			
			if ($this -> getUser() ->hasCredential(2) && !empty($this -> film['data'])) {
        $this -> film["data"][0]["screening_film_ticket_price"] = 0;
        $this -> film["data"][0]["screening_film_setup_price"] = 0;
      }
      $this -> widget_vars["film"] = $this -> film["data"][0];

      $user = new UserPayment_PageWidget( $this -> widget_vars, $this -> page_vars, $this -> context);
      $this -> widget_vars["post"] = $user -> parse();   
    	$this -> widget_vars["dohbr"] = $this -> getVar("dohbr");
      
	    if (($this -> film["data"][0]["screening_film_allow_hostbyrequest"] == 0) && ($this -> getVar("dohbr")) || empty($this -> film['data'])) {
			  redirect("/");
			}
			if (($this -> getVar("rev") != "confirm") && ($audience ["data"][0]["audience_payment_id"] > 0)) {
			  $this -> redirect($audience ["data"][0]["audience_short_url"]);
			} 
      
      $this -> widget_vars['isConfirmation'] = false;
      $this -> widget_vars["audience"] = null;
      if($this -> getVar("paypal")){
        $this -> widget_vars["audience"] = $audience;
        $this -> widget_vars['isConfirmation'] = true;
      } 
		}
		
		$this -> widget_vars["partner"] = checkPartner($this -> context,false,$filmid);
		
		return $this -> widget_vars;
    
  }

  function checkSoldOut() {

    if (($this -> user) && ($this -> user -> hasCredential(2))) {
      return true;
    }
    if ($this -> film["data"][0]["screening_highlighted"] == "true") {

      $sql = "select count(fk_user_id)
            from payment
            inner join `user`
            on user.user_id = fk_user_id
            where fk_screening_id = ". $this -> film["data"][0]["screening_id"]."
            and payment.payment_status = 2";
      $res = $this -> propelQuery($sql);
      while( $row = $res-> fetch()) {
        $count = $row[0];
      }
      
      if ($this -> film["data"][0]["screening_total_seats"] > 0) {  
        if ($this -> film["data"][0]["screening_total_seats"] <= $count) {
          return false; 
        }
      } elseif ($this -> film["data"][0]["screening_film_total_seats"] > 0) {
        if ($this -> film["data"][0]["screening_film_total_seats"] <= $count) {
          return false; 
        }
      }
    }
    return true;
  }

}
