<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  include_once(sfConfig::get('sf_lib_dir')."/helper/OrderHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/UserHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/CryptHelper.php");
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Host_crud.php';
  
   class Host_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	var $fields;
	var $post;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> order_success = false;
    parent::__construct( $context );
    
    $this -> detailfields = array('fld-host_date','fld-host_time');
    
    $this -> fields = array('first_name',
                            'last_name',
                            'email',
                            'confirm_email',
                            'b_address1',
                            'b_address2',
                            'b_city',
                            'b_state',
                            'b_zipcode');
    
    $sxml = new XML();
    $sxml -> loadXML( sfConfig::get("sf_web_dir")."/xml/states.xml");
    $this -> widget_vars["states"] = $sxml -> query("//ASTATE");
    
  }

	function parse() {
    
    //This removes users who are "temp" users
    clearUser( $this -> getUser() );
    
    $film = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/FilmView/query/Film_list_datamap.xml");
    //$this -> widget_vars["film"] = $film["data"][0];
    
    foreach ($this -> fields as $field) {
      $this -> post[$field] = '';
    }
    
    $this -> post["email"] = $this -> getUser() -> getAttribute("user_email");
    
    if ($this -> getVar("id") == "purchase") {
      if (! $this -> getUser() -> isAuthenticated()) {
        redirect("film/".$this -> getVar("op"));
      }
      sfConfig::set("screening_unique_key",$this -> getVar("rev"));
      $screening = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Host/query/Screening_list_datamap.xml");
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {  
      $this -> doPost($film, $screening);
      $this -> doGet();
    }
    
    $this -> outputStatus("failure","","","");
    
  }

  function doPost( $film, $screening=null ){
    //dump($_POST);
    //They are on the hosting step, and pressed "skip"
    if ($this -> postVar("type") == "detail" ) {
      $this -> detail( $film );
    //They are on the payment step
    } else {
      $this -> pay( $film, $screening );
    }
  }
  
  function detail($film) {
    
    $valid = true;
    
    $errors = array();
    foreach ($this -> detailfields as $field) {
      $this -> post[$field] = $this -> postVar($field);
      if ($this -> postVar($field) == '') {
        $errors[$field] = true;
        $valid = false;
      }
    }
    
    if (($film["data"][0]["film_allow_user_hosting"] === 0) && ($film["data"][0]["film_allow_hostbyrequest"] === 0)) {
      $valid = false;
    }
    
		// check time
		$datestring = $this -> postVar("fld-host_date") . " " . $this -> postVar("fld-host_time") . " " . $this -> postVar("tzSelector");
		$timestamp = strtotime($datestring);
    /*
    echo "// STRING: " . $datestring . "\n";
		echo "// TIMESTAMP: " . $timestamp . "\n";
		echo "// FORMATTED: " . date("r", $timestamp) . "\n";
		echo "// CURRENT SERVER TIME: " . date("r") . "\n";
		*/
    if($timestamp < time()) {
      $this -> outputStatus("error","error","You must choose a hosting date and time that is later than the current date and time.", null);
			return;
		}
		
    $order = new OrderManager_PageWidget( $this -> widget_vars, $this -> page_vars, $this -> context, null );
  
    //Check for this SKU and User, and redirect to invite if exists
    $oid = $order -> checkPrior( 'host', $this -> getUser() -> getAttribute("user_id"), $this -> getVar("op"), null, null );
    
    if ((! $oid) && ($valid)) {
      
      //Create the Payment, adding "true" for the "host" param
      $order -> postOrder( 'host' );
      
      //Put the Audience Reservation in the DB
      $order -> postItem( $film["data"][0], 'host' );
      
      $date = $order -> orderitems[0] -> Screening -> getScreeningDate() . " " . $order -> orderitems[0] -> Screening -> getScreeningTime() . " " .  $order -> orderitems[0] -> Screening -> getScreeningDefaultTimezoneId();
      $this -> outputStatus("success","Now invite people to your screening.","",$order -> orderitems[0] -> Screening -> getScreeningUniqueKey(),formatDate($date,"prettyshort"));
      //$this -> redirect("/film/".$film["data"][0]["film_id"]."/host_invite/". $order -> orderitems[0] -> Screening -> getScreeningUniqueKey());
    
    } elseif (! $valid) {
      
      $this -> outputStatus("failure","We were unable to create this screening, please check your information and try again.",$errors,"");
      //$this -> redirect("/film/".$film["data"][0]["film_id"]."/detail");
      
    } else {
      
      $this -> outputStatus("success","Your screening was not processed.","You've already created this screening.",$oid);
      //$this -> redirect("/film/".$film["data"][0]["film_id"]."/post/". $oid);
    
    }
  }
  
  function pay( $film, $screening ) {
  
    $valid = true;
    
    $errors = array();
    
    $freebie = false;
    
    if (($screening["data"][0]["screening_film_setup_price"] == 0) || ($this -> postVar("ticket_price") == 0) || (sfConfig::get("sf_environment") != "prod")) {
      $freebie = true;
      $this -> fields = array('first_name',
                            'last_name',
                            'email',
                            'confirm_email');
    }
    
    foreach ($this -> fields as $field) {
      $this -> post[$field] = $this -> postVar($field);
      if ($field == 'b_address2') continue;
      if ($this -> postVar($field) == '') {
        $errors[$field] = true;
        $valid = false;
      }
    }
    
    if (! $freebie) {
      if (cctypeMask($this -> postVar("credit_card_number"))=="Not A Valid") {
        //$valid = false;
      }
      
      if ((cvv2Mask($this -> postVar("credit_card_number"),$this -> postVar("card_verification_number"))=="Not A Valid") || 
        (cvv2Mask($this -> postVar("credit_card_number"),$this -> postVar("card_verification_number"))=="0")) {
        $valid = false;
      }
      if ($this -> postVar("expiration_date_month")=="Select") {
        $valid = false;
      }
      if ($this -> postVar("expiration_date_year")=="Select") {
        $valid = false;
      }
      //$this -> trackAB( "download_submit" );
    }
    
    if (($film["data"][0]["film_allow_user_hosting"] === 0) && ($film["data"][0]["film_allow_hostbyrequest"] === 0)) {
      $valid = false;
    }
    
    if ($valid) {
      
      $this -> doPost = true;
      //Give them the "Please Wait" screen
      $order = new OrderManager_PageWidget( $this -> widget_vars, $this -> page_vars, $this -> context, null );
      
      //Put the stuff in the db!
      //Put the User In
      $order -> postOrderUser();
      
      //Check for this SKU and User, and redirect if exists
      $oid = $order -> checkPrior( 'host', $this -> getUser() -> getAttribute("user_id"), $this -> getVar("rev"), null, null );
      
      if (! $oid) {
        
        $order -> hydrateOrder( $screening["data"][0]["fk_payment_id"], 'host' );
        $order -> hydrateItems( $screening["data"][0]["screening_id"], 'host' );
        
        $order -> updateOrder('host');
        
        /******* TODO *********/
        //Send info to PayFlow
        if (! $freebie) {
          if (! $order -> processPPWPPOrder( 'host' )) {
            $this -> outputStatus("failure","Your screening was not processed.","Your purchase was declined by the bank or credit card processor. Please check your data and try again.",$this -> getVar("rev"));
            //$this -> redirect("/film/".$this -> getVar("op")."/host_purchase/".$screening["data"][0]["screening_unique_key"]."?error=payment");
          }
        } else {
          $order -> crud -> setPaymentStatus( 2 );
          $order -> crud -> save();
        }
        
        if ($order -> crud -> Payment -> getPaymentStatus() == 2) {
          
          $ticket = $order -> confirmItem( $order -> orderitems, 'host' );
          
          //Update the SOLR Search Engine
          $order -> postSolrOrder( $order -> crud -> Payment -> getPaymentId() );
          $this -> widget_vars["order_id"] = $order -> crud -> Payment -> getPaymentUniqueCode();
          
        } else {
          
          $valid = false;
          /******* TODO *********/
          //Get response from order object
          $errors["gateway_error"] = "Credit card not valid";
          
        }
        
        /******* TODO *********/
        //Send out Emails or errors, as appropriate
        $order -> sendHostNotification( $film, $ticket );
        
      } else {
        
        $this -> outputStatus("success","Your screening was not processed.","You've already purchased this screening.",$this -> getVar("rev"));
        //$this -> redirect("/film/".$this -> getVar("op")."/invite/".$oid);
        
      }
      
      /*
      $this -> widget_vars["proto"] = "";
      if (sfConfig::get("app_enforce_ssl")) {
        $this -> widget_vars["proto"] = "s";
      }
      */
      if ($valid) {
        //CC Info is incorrect, do over!
        if (in_array($order -> crud -> Payment -> getPaymentStatus(),array(-1,-2,-3,-4,-5,-8))) {
          $this -> outputStatus("failure","Your purchase was declined.","Please check your information and try again.",$order -> orderitems[0] -> Screening -> getScreeningUniqueKey());
          //$this -> redirect("/film/".$this -> getVar("op")."/host_purchase/".$order -> orderitems[0] -> Screening -> getScreeningUniqueKey()."?err=cc");
        } else {
          $this -> outputStatus("success","Your purchase was confirmed.","",$order -> orderitems[0] -> Screening -> getScreeningUniqueKey());
          //$this -> redirect("/film/".$this -> getVar("op")."/host_success/".$order -> orderitems[0] -> Screening -> getScreeningUniqueKey());
        }
      }
    }
    
    //Card Info Not Valid
    if (! $valid) {
      $this -> error_str = '';
      $this -> isError = true;
      foreach ($errors as $key => $error) {
        if ($key == "first_name") {
          $this -> error_str .= "Your First Name is empty.<br />";
        } 
        if ($key == "last_name") {
          $this -> error_str .= "Your Last Name is empty.<br />";
        }
        if ($key == "email") {
          $this -> error_str .= "Your Email is empty.<br />";
        }
        if (($this -> postVar("email") != $this -> postVar("confirm_email")) && ($key == "confirm_email")) {
          $this -> error_str .= "Your Email and Confirm Email don't match.<br />";
        }
        if ($key == "b_address1") {
          $this -> error_str .= "Your Address is empty.<br />";
        }
        if ($key == "b_city") {
          $this -> error_str .= "Your City is empty.<br />";
        }
        if ($key == "b_zipcode") {
          $this -> error_str .= "Your Zip Code is empty.<br />";
        }
        if ($key == "gateway_error") {
          $this -> error_str .= "There was a problem procesing your order.<br />";
        }
        
      }
      
      if (cctypeMask($this -> postVar("credit_card_number"))=="Not A Valid") {
        $this -> error_str .= "Your Credit Card Number is invalid<br />";
      }
      if (cvv2Mask($this -> postVar("card_verification_number"),$this -> postVar("credit_card_number"))=="Not A Valid") {
        $this -> error_str .= "Your Card Code is invalid<br />";
      }
      if ($this -> postVar("expiration_date_month")=="Select") {
        $this -> error_str .= "Your Card Code Expiration Month is invalid.<br />";
      }
      if ($this -> postVar("expiration_date_year")=="Select") {
        $this -> error_str .= "Your Card Code Expiration Year is invalid.<br />";
      }
    }
  }
  
  function doGet(){
    
    if ($this -> isError) {
      $this -> widget_vars["errors"] = $this -> error_str;
      $this -> outputStatus("failure","Your purchase wasn't successful.",$this -> error_str,$this -> getVar("rev"));
      //$this -> redirect("/host/".$this -> getVar("op")."/purchase?err=validate");
    } else {
      $this -> outputStatus("success","Your purchase was confirmed.","",$this -> getVar("rev"));
      //$this -> redirect("/film/".$this -> getVar("op")."/host_confirm");
    }
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      $this -> pushItem();
    }
    
  }
  
  function trackAB( $state ) {
    
    $ab = new ABTrack_PageWidget( null, null, $this -> context );
    $ab -> widgetname = $this -> widget_name;
    $ab -> trackHit( $state );
  }
  
  function outputStatus( $status, $result, $message, $screening, $alt=null ) {
    $res = new stdClass;
    $res -> hostResponse = 
                        array("status"=>$status,
                              "result"=>$result,
                              "message"=>$message,
                              "screening"=> $screening,
                              "alt"=>$alt);
    print(json_encode($res));
    die();
  }
  
}
?>
