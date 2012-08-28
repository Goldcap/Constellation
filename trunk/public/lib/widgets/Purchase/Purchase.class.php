<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  include_once(sfConfig::get('sf_lib_dir')."/helper/OrderHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/UserHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/CryptHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/ServiceHelper.php");
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Purchase_crud.php';
  
   class Purchase_PageWidget extends Widget_PageWidget {
	
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
    
    $this -> fields = array(
      'first_name',
      'last_name',
      'email',
      'confirm_email',
      'b_address1',
      'b_address2',
      'b_city',
      'b_state',
      'b_zipcode'
    );
    
    $sxml = new XML();
    $sxml -> loadXML( sfConfig::get("sf_web_dir")."/xml/states.xml");
    $this -> widget_vars["states"] = $sxml -> query("//ASTATE");
    
  }

	function parse() {

    //This removes users who are "temp" users
    clearUser( $this -> getUser() );
    
    if ($this -> getVar("op") == "list") {
      $this -> redirect("/");
    }
    
    //$this -> showXML();
    putLog("USER:: ".$this -> sessionVar("user_id"). " | MESSAGE:: Purchase for screening " . $this -> getVar("rev"));
    $film = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Purchase/query/Screening_list_datamap.xml");
    if ($this -> getVar("op") == "purchase") {
      $this -> redirect("/");
    }
    
    if ($film["data"][0]["screening_highlighted"] == "true") {
	    $sql = "select count(fk_user_id)
            from payment
            inner join `user`
            on user.user_id = fk_user_id
            where fk_screening_id = ".$film["data"][0]["screening_id"]."
            and payment.payment_status = 2";
	  	$res = $this -> propelQuery($sql);
	    while( $row = $res-> fetch()) {
	    	$count = $row[0];
	    }
	    
			if ($film["data"][0]["screening_total_seats"] > 0) {	
		    if ($film["data"][0]["screening_total_seats"] <= $count) {
					$this -> outputStatus("failure","This screening has limited seating, and is sold out.","Please try a different showtime.",$this -> getVar("rev"));
				}
			} elseif ($film["data"][0]["screening_film_total_seats"] > 0) {
		    if ($film["data"][0]["screening_film_total_seats"] <= $count) {
					$this -> outputStatus("failure","This screening has limited seating, and is sold out.","Please try a different showtime.",$this -> getVar("rev"));
				}
			}
		}
			
    /*
    sfConfig::set("film_id",$film["data"][0]["screening_film_id"]);
    sfConfig::set("startdate",now()."|".dateAddExt(now(),7,'d'));
    $screens = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Purchase/query/AllScreening_list_datamap.xml");
    */
    
    //dump($film["data"]);
    //$this -> widget_vars["screenings"] = $screens["data"];
    $this -> widget_vars["film"] = $film["data"][0];
    
    foreach ($this -> fields as $field) {
      $this -> post[$field] = '';
    }
    
    $this -> post["email"] = $this -> getUser() -> getAttribute("user_email");
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {  
      $this -> doPost($film);
      $this -> doGet();
    }
    
    $this -> outputStatus("failure","","","");
    
  }

  function doPost( $film ){
    //They are on the hosting step, and pressed "skip"
    $this -> pay( $film );
  }
  
  function pay( $film ) {
   
    $valid = true;
    
    $errors = array();
    
    $freebie = false;
    
    $order = new OrderManager_PageWidget( $this -> widget_vars, $this -> page_vars, $this -> context, null );
    
    if ($this -> postVar("dohbr") == "true") {
    	putLog("USER:: ".$this -> sessionVar("user_id")." | MESSAGE:: Purchase HBR");
      sfConfig::set("film",$this -> getVar("op"));
      $user = UserPeer::retrieveByPk( $this -> sessionVar("user_id") );
      $hostfilm = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Code/query/Film_list_datamap.xml");
      //Create new screening
      $thedate = strtotime(now()) + 60;
      $order -> insertHostItem( $user, $hostfilm, formatDate($thedate,"MDY-"), formatDate($thedate,"time"));
      $screen = $order -> orderitems[0];
      $this -> setgetVar("rev",$order -> orderitems[0] -> Screening -> getScreeningUniqueKey());
      putLog("USER:: ".$this -> sessionVar("user_id")." | MESSAGE:: Purchase - HBR Screening is ".$order -> orderitems[0] -> Screening -> getScreeningUniqueKey());
      
      sfConfig::set("screening_key",$order -> orderitems[0] -> Screening -> getScreeningUniqueKey());
      $film = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Purchase/query/ScreeningHBR_list_datamap.xml");
      $film["data"][0]["screening_date"] =  $film["data"][0]["screening_date"] ." ". $film["data"][0]["screening_time"]." ".$film["data"][0]["screening_default_timezone_id"];
    }
    
    if (($this -> getUser() -> hasCredential(2)) || ($film["data"][0]["screening_film_ticket_price"] == 0) || ($this -> postVar("ticket_price") == 0) || (sfConfig::get("sf_environment") != "prod")) {
      $freebie = true;
      $this -> fields = array(
        'first_name',
        'last_name',
        'email',
        'confirm_email',
        'b_city',
        'b_state'
      );
    }
    
    if ($film["data"][0]["screening_film_free_screening"] == 1) {
      $freebie = true;
      $valid = true;
      $this -> fields = array(
        'username',
        'email'
      );
    } elseif ($film["data"][0]["screening_film_free_screening"] == 2) {
       $promoc = new PromoCodeCrud( $this -> context );
		   $vars = array("promo_code_code" => trim($this -> postVar("promo_code")));
		   $promoc -> checkUnique($vars);
		   $valid = false;
       
			 if ($promoc -> PromoCode -> getPromoCodeId() > 0) {
		    	//If this is the wrong film
					if (($promoc -> PromoCode -> getFkFilmId() != 0) && ($film["data"][0]["screening_film_id"] != $promoc -> PromoCode -> getFkFilmId())) {
						$valid = false;
					//If the code has been used too often
					} else if (($promoc -> PromoCode -> getPromoCodeTotalUsage() > 0) && ($promoc -> PromoCode -> getPromoCodeUses() >= $promoc -> PromoCode -> getPromoCodeTotalUsage())) {
					 	$valid = false;
					//If this is the wrong type of discount
					} else if ($promoc -> PromoCode -> getPromoCodeType() != 3) {
				    $valid = false;
				  } else {
						$freebie = true;
						$valid = true;
      			$this -> fields = array(
              'username',
              'email'
            );
					}
		   }
		} else {
	    foreach ($this -> fields as $field) {
	      $this -> post[$field] = $this -> postVar($field);
	      if ($field == 'b_address2') continue;
	      if ($this -> postVar($field) == '') {
	        $errors[$field] = true;
	        $valid = false;
	      }
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
    
    if ($valid) {
     
      $this -> doPost = true;
      //Give them the "Please Wait" screen
      
      //Put the stuff in the db!
      //Put the User In
      $order -> postOrderUser( true );
      
      //Check for this SKU and User, and redirect to invite if exists
      $oid = $order -> checkPrior( 'screening', $this -> getUser() -> getAttribute("user_id"), $this -> getVar("rev") );
      
      if (! $oid) {
        
        //Create the Payment
        $order -> postOrder();
        
        //Put the Audience Reservation in the DB
        $order -> postItem( $film );
        
        /******* TODO *********/
        //Send info to PayFlow
        //And update the "Payment" with the status
        //"2" is complete
        //Send info to PayFlow
        if (! $freebie) {
          if (! $order -> processPPWPPOrder()) {
            $this -> outputStatus("failure","Your purchase wasn't successful.","Your purchase was declined by the bank or credit card processor. Please check your data and try again.",$this -> getVar("rev"));
            //$this -> redirect("/film/".$this -> getVar("op")."/purchase/".$this -> getVar("rev")."?error=payment");
          }
        } else {
          $order -> crud -> setPaymentStatus( 2 );
          $order -> crud -> save();
        }
        
        if ($order -> crud -> Payment -> getPaymentStatus() == 2) {
          
          $order -> confirmItem( $order -> orderitems );
          
          //Update the SOLR Search Engine
          $order -> postSolrOrder( $order -> crud -> Payment -> getPaymentId() );
          $this -> widget_vars["order_id"] = $order -> crud -> Payment -> getPaymentUniqueCode();
          if ($this -> postVar("promo_code") != "") {
            $order -> recordPromoUse( $this -> sessionVar("user_id"), $this -> postVar("promo_code"), $film["data"][0]["screening_film_id"], $order->orderitems[0]->Audience->getAudienceId(), $this -> getVar("rev"), $order->orderitems[0]->Audience->getAudienceCreatedAt() );
          }
          
          if ($this -> cookieVar("cs_referer") != "") {
            $order -> recordReferer( $this -> sessionVar("user_id"), $this -> cookieVar("cs_referer"), $film["data"][0]["screening_film_id"], $order->orderitems[0]->Audience->getAudienceId(), $this -> getVar("rev"), $order->orderitems[0]->Audience->getAudienceCreatedAt() );
          }
          
          //Note this Screening as a HBR Screening
          if ($this -> postVar("dohbr") == "true") {
            $screen -> Screening -> setFkPaymentId( $order -> crud -> Payment -> getPaymentId() );
            $screen -> save();
            $solr = new SolrManager_PageWidget(null, null, $this -> context);
            $solr -> execute("add","screening",$screen -> Screening -> getScreeningId());
          }
          
        } else {
        
          $valid = false;
          /******* TODO *********/
          //Get response from order object
          $errors["gateway_error"] = "Credit card not valid";
          
        }
        /******* TODO *********/
        //Send out Emails or errors, as appropriate
        $order -> sendOrderNotification( $film );
        
      } else {
        $this -> outputStatus("success","You have already bought a ticket to this screening.","You've already purchased this seat.",$this -> getVar("rev"));
        //$this -> redirect("/film/".$this -> getVar("op")."/confirm/".$this -> getVar("rev"));
      
      }
      
      if ($valid) {
        //CC Info is incorrect, do over!
        if (in_array($order -> crud -> Payment -> getPaymentStatus(),array(-1,-2,-3,-4,-5,-8))) {
          $this -> outputStatus("failure","Your purchase was declined.","Please check your information and try again.",$this -> getVar("rev"));
          //$this -> redirect("/film/".$this -> getVar("op")."/purchase/".$this -> getVar("rev")."?err=cc");
        } else {
          setScreeningTicket($this,$order->orderitems[0]->Audience->getAudienceInviteCode(),$this -> getVar("rev"));
          $this -> outputStatus("success","Your purchase was confirmed.","",$this -> getVar("rev"));
          //$this -> redirect("/film/".$this -> getVar("op")."/confirm/".$this -> getVar("rev"));
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
      //$this -> widget_vars["errors"] = $this -> error_str;
      $this -> outputStatus("failure","Your purchase wasn't successful.",$this -> error_str,$this -> getVar("rev"));
      //$this -> redirect("/film/".$this -> getVar("op")."/purchase/".$this -> getVar("rev"));
      //$this -> redirect("/screening/".$this -> getVar("op")."/purchase?err=validate");
    } else {
      $this -> outputStatus("success","Your purchase was confirmed.","",$this -> getVar("rev"));
      //$this -> redirect("/film/".$this -> getVar("op")."/invite");
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
    $res = array(
      "purchaseResponse" => array(
        "status"=>$status,
        "result"=>$result,
        "message"=>$message,
        "screening"=> $screening
      )
    );
    print(json_encode($res));
    die();
  }
}

?>
