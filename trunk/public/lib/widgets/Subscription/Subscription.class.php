<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  include_once(sfConfig::get('sf_lib_dir')."/helper/OrderHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/UserHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/CryptHelper.php");
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Subscription_crud.php';
  
   class Subscription_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	var $fields;
	var $detailfields;
  var $post;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> order_success = false;
    parent::__construct( $context );
    
    $this -> detailfields = array('gift_ticket_number',
                            'subscription_ticket_number',
                            'fld-subscription_date',
                            'fld-subscription_period');
    
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
    
    foreach ($this -> fields as $field) {
      $this -> post[$field] = '';
    }
    
    $this -> post["email"] = $this -> getUser() -> getAttribute("user_email");
    
    if (($this -> getVar("op") == "purchase") || ($this -> getVar("op") == "invite")) {
      sfConfig::set("subscription_unique_key",$this -> getVar("id"));
      $subscription = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Subscription/query/Subscription_list_datamap.xml");
    } else {
      $subscription = null;
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $this -> doPost( $subscription );
      $this -> doGet();
    }
    
    if ($this -> getUser() -> isAuthenticated()) {
      $user = getUserById( $this -> getUser() -> getAttribute("user_id") );
      if($user) {
        $this -> post["first_name"] = $user -> getUserFname();
        $this -> post["last_name"] = $user -> getUserLname();
      }  //TESTING DATA    
    }
    
    $this -> post["email"] = "amadsen@gmail.com";
    $this -> post["email_confirm"] = "amadsen@gmail.com";
    $this -> post["b_address1"] = "1 Main Street";
    $this -> post["b_address2"] = "";
    $this -> post["b_city"] = "San Jose";
    $this -> post["b_state"] = "CA";
    $this -> post["b_zipcode"] = "95131";
    $this -> post["card_number"] = "4220496880267261";
    $this -> post["security_code"] = "962";

    $this -> inviter= new OpenInviter();
    $this -> widget_vars["oi_services"] = $this -> inviter->getPlugins();
  
    $this -> widget_vars["post"] = $this -> post;
    
    return $this -> widget_vars;
  }
  
  /*
  'step' => string 'gift' (length=4)
  'gift_ticket_number' => string '20' (length=2)
  'subscription_ticket_number' => string '' (length=0)
  'fld-subscription_date' => string '' (length=0)
  'fld-subscription_term' => string 'weekly' (length=6)
  */
  
  function doPost( $subscription ){
    if ($this -> getVar("op") == "detail") {
      $this -> detail();
    } elseif ($this -> getVar("op") == "purchase") {
      $this -> pay( $subscription );
    }
  }
  
  function detail() {
    
    $valid = true;
    
    $errors = array();
    
    foreach ($this -> detailfields as $field) {
      $this -> post[$field] = $this -> postVar($field);
      if ($this -> postVar("step") == "gift") {
        if ($field == 'subscription_ticket_number') continue;
      } elseif ($this -> postVar("step") == "subscription") {
        if ($field == 'gift_ticket_number') continue;
      }
      if ($this -> postVar("step") == "gift") {
        if ($field == 'fld-subscription_date') continue;
        if ($field == 'fld-subscription_period') continue;
      }
      if ($this -> postVar($field) == '') {
        $errors[$field] = true;
        $valid = false;
      }
    }
    
    $order = new OrderManager_PageWidget( $this -> widget_vars, $this -> page_vars, $this -> context, null );
  
    //Check for this SKU and User, and redirect to invite if exists
    $oid = $order -> checkPrior( 'subscription', $this -> getUser() -> getAttribute("user_id"), $this -> getVar("op"), null, null );
    
    if ((! $oid) && ($valid)) {
      
      //Create the Payment, adding "true" for the "host" param
      $order -> postOrder( 'subscription' );
      
      //Put the Audience Reservation in the DB
      $order -> postItem( null, 'subscription' );
      
      $this -> outputStatus("success","Now enter your payment information.","",$order -> orderitems[0] -> Subscription -> getSubscriptionUniqueKey());
      //$this -> redirect("/subscription/purchase/". $order -> orderitems[0] -> Subscription -> getSubscriptionUniqueKey());
    
    } elseif (! $valid) {
    
      $this -> outputStatus("failure","We were unable to create this subscription, please check your information and try again.",$errors,"");
      //$this -> redirect("/subscription/detail");
      
    } else {
    
      $this -> outputStatus("success","Your subscription was not processed.","You've already created this subscription.",$oid);
      //$this -> redirect("/subscription/invite/". $oid);
    
    }
  }
  
  function pay( $subscription ) {
   
    $valid = true;
    
    $errors = array();
    
    foreach ($this -> fields as $field) {
      $this -> post[$field] = $this -> postVar($field);
      if ($field == 'b_address2') continue;
      if ($this -> postVar($field) == '') {
        $errors[$field] = true;
        $valid = false;
      }
    }
    
    if (cctypeMask($this -> postVar("credit_card_number"))=="Not A Valid") {
      $valid = false;
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

    if ($valid) {
      
      $this -> doPost = true;
      //Give them the "Please Wait" screen
      $order = new OrderManager_PageWidget( $this -> widget_vars, $this -> page_vars, $this -> context, null );
      
      //Put the stuff in the db!
      //Put the User In
      $order -> postOrderUser();
      
      //Check for this SKU and User, and redirect if exists
      //$oid = $order -> checkPriorHost( $this -> getUser() -> getAttribute("user_id"), $this -> getVar("op"), null, null );
      $oid = false;
      
      if (! $oid) {
        
        $order -> hydrateOrder( $subscription["data"][0]["fk_payment_id"] );
        $order -> hydrateItems( $subscription["data"][0]["subscription_id"] );
      
        $order -> updateOrder('subscription');
        
        //Send info to PayFlow
        if (! $order -> processPPWPPOrder( "subscription" )) {
          $this -> outputStatus("failure","Your subscription was not processed.","Your purchase was declined by the bank or credit card processor. Please check your data and try again.",$this -> getVar("id"));
          //$this -> redirect("/subscription?error=payment");
        }
        
        if ($order -> crud -> Payment -> getPaymentStatus() == 2) {
          
          $order -> confirmItem( $order -> orderitems, 'subscription' );
          
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
        //$order -> send();
        
      } else {
      
        $this -> widget_vars["order_id"] = $oid;
      
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
          $this -> outputStatus("failure","Your purchase was declined.","Please check your information and try again.",$order -> orderitems[0] -> Subscription -> getSubscriptionUniqueKey());
          //$this -> redirect("/subscription/purchase/". $order -> orderitems[0] -> Subscription -> getSubscriptionUniqueKey()."?err=cc");
        } else {
          $this -> outputStatus("success","Your purchase was confirmed.","",$order -> orderitems[0] -> Subscription -> getSubscriptionUniqueKey());
          //$this -> redirect("/subscription/invite/".$order -> orderitems[0] -> Subscription -> getSubscriptionUniqueKey());
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
      $this -> outputStatus("failure","Your purchase wasn't successful.",$this -> error_str,$this -> getVar("id"));
      //$this -> redirect("/subscription/".$this -> getVar("id")."/purchase?err=validate");
    } else {
      $this -> outputStatus("success","Your purchase was confirmed.","",$this -> getVar("id"));
      //$this -> redirect("/subscription/".$this -> getVar("id")."/invite");
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
  
  function outputStatus( $status, $result, $message, $screening ) {
    $res = new stdClass;
    $res -> subscriptionResponse = 
                        array("status"=>$status,
                              "result"=>$result,
                              "message"=>$message,
                              "screening"=> $screening);
    print(json_encode($res));
    die();
  }
}
?>
