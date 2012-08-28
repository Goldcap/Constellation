<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/OrderHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/UserHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/LoginHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/CryptHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/MaxmindHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/CouponHelper.php"); 
  include_once(sfConfig::get('sf_lib_dir')."/helper/TrackHelper.php");  
  include_once(sfConfig::get('sf_lib_dir')."/helper/FacebookHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Download_crud.php';
  //Note: for paypal reference:
  //https://www.paypal.com/cgi-bin/webscr?cmd=p/pdn/howto_checkout-outside
  
  //PFPro Gateway Results
  //PFPro_Result codes are:
  //  3 :: Captured
  //  2 :: All Good
  //  1 :: Approved with AVS Errors
  //  0 :: Fraud Review
  //  -1 :: Declined
  //  -2 :: User Data Error
  //  -3 :: Fraud Error
  //  -4 :: Voice Auth Suggested
  //  -5 :: Fraud Service Issue, Voice Auth Suggested (These are approved)
  //  -6 :: Fraud Service Issue, Voice Auth Suggested (These are approved)
  //  -7 :: Cancelled
  //  -8 :: Unknown Declined
  //  -10 :: Network Error
  //  -20 :: Dismissed
  
  class OrderManager_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	var $product;
	var $form;
	var $orderarray;
	var $orderguid;
	var $orderuser;
	var $ordermaxmind;
	var $order_result_code;
	var $orderitems;
	var $ordershipping;
	var $orderskus;
	var $ordertotal;
	var $orderdownload;
	var $ordernewuser;
	var $order_dupe;
	
	var $order_array;
	var $orderitems_array;
	var $coupon_value;
	var $coupon_code;
	var $returnURL;
	
	var $user_id;
	
	function __construct( $wvars, $pvars, $context, $XMLForm, $user_id=0 ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> XMLForm = $XMLForm;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> crud = new PaymentCrud( $context );
    $this -> ordershipping = 0;
    $this -> ordernewuser = 0;
    $this -> coupon_value = 0;
    $this -> coupon_code = "";
    $this -> order_dupe = false;
    
    if ($user_id == 0) {
      $this -> user_id = $this -> sessionVar("user_id");
    } else {
      $this -> user_id = $user_id;
    }
    parent::__construct( $context );
    
  }

	function parse() {
	  
    return false;
    
  }
  
  function getOrder( $order_id=false ) {
    
    if (! $order_id) {
      $order_id = $this -> getVar("id");
    }
    
    if (! is_numeric($order_id)) {
    	//We're using the GUID
    	$c = new Criteria;
    	$c -> add(PaymentPeer::PAYMENT_UNIQUE_CODE,$order_id);
    	$rs = PaymentPeer::doSelect($c);
    	if (count($rs) > 0) {
    	 $order_id = $rs[0] -> getPaymentId();
      } else {
        return false;
      }
    }
    sfConfig::set("payment_id",$payment_id);
    $this -> orderarray = $this -> dataMap( sfConfig::get('sf_lib_dir')."/widgets/OrderManager/query/Payment_list_datamap.xml");
    
    return array("payment"=>$this -> orderarray["data"][0]);
    
  }
  
  function hydrateOrder( $payment_id=false ) {
    if (! $payment_id) {
      //$payment_id = decrypt($this -> cookieVar("last_order"));
      $payment_id = $this -> getVar("id");
    }
    
    $this -> crud = new PaymentCrud( $this -> context, $payment_id );
    if (! is_numeric($payment_id)) {
      $this -> crud -> populate("payment_id",$payment_id);
    }
    $this -> orderguid = $this -> crud -> Payment -> getPaymentUniqueCode();
    $this -> ordertotal = $this -> crud -> Payment -> getPaymentAmount();
  }
  
  function hydrateItems( $id, $type='subscription' ) {
    switch ($type){
      case "subscription":
        $crud = new SubscriptionCrud( $this -> context, $id );
        if (is_numeric($id)) {
          $crud -> populate("subscription_id",$id);
        } else {
          $crud -> populate("subscription_unique_key",$id);
        }
        $this -> orderskus[] = $crud -> Subscription -> getSubscriptionUniqueKey();
        break;
      case "host":
        $crud = new ScreeningCrud( $this -> context, $id );
        if (is_numeric($id)) {
          $crud -> populate("screening_id",$id);
        } else {
          $crud -> populate("screening_unique_key",$id);
        }
        $this -> orderskus[] = $crud -> Screening -> getScreeningUniqueKey();
        break;
      case "screening":
        $crud = new AudienceCrud( $this -> context, $id );
        if (is_numeric($id)) {
          $crud -> populate("audience_id",$id);
        } else {
          $crud -> populate("audience_invite_code",$id);
        }
        $this -> orderskus[] = $crud -> Audience -> getAudienceInviteCode();
        break;
    }
    
    $this -> orderitems[] = $crud;
    
  }
  
  function hydrateuser( $user_id=false ) {
    if (! $user_id) {
      $user_id =$this -> crud -> Payment -> getFkUserId();
    }
    $this -> orderuser = UserPeer::retrieveByPk( $user_id );
    
  }
  
  //This is for "Sponsored" users
  //They may have a null email, so "createSponsorUser" checks Username
  function createOrderUser( $fname, $lname, $email, $username, $userid, $sponsor_code_id, $unlimited=0, $film ) {
    
    //If the user is in session, use that user
    $user = getUserById( $userid );
    
    if ((($unlimited == 0) || (is_null($unlimited))) && (count($user) == 0) && ($email != "")) {
    	//If the user already has an account, use that one
    	$userObj = new UserCrud( null, null );
      $userObj -> populate("user_email",$email);
      if ($userObj -> User -> getUserId() > 0) {
    		//Necessary, as we want the User to be an object, not an array
    		$user = $userObj;
			}
		} elseif (count($user) > 0) {
      $userObj = new UserCrud( null, null );
      $userObj -> hydrate($user -> getUserId());
      $user = $userObj;
    }
    
		if ($unlimited == 1) {
      $email = "code_".$sponsor_code_id."-".time()."@constellation.tv";
      //$username = $film["data"][0]["film_name"] . " Viewer";
      $username = "Viewer ".time();
      $this -> orderuser = createSponsorUser( $this -> context, 
                          $fname,
                          $lname,
                          $email,
                          $username,
                          generatePassword(),
													$sponsor_code_id);
      setUser( $this -> context -> getUser(), $this -> orderuser );
      return $this -> orderuser -> User -> getUserId();
    } else if (count($user) == 0) {
			$this -> orderuser = createSponsorUser( $this -> context, 
                          $fname,
                          $lname,
                          $email,
                          $username,
                          generatePassword(),
													$sponsor_code_id);
      setUser( $this -> context -> getUser(), $this -> orderuser );
      return $this -> orderuser -> User -> getUserId();
    } else {
      
      //If there is no user email, we're takin' it!
      // NOTE:: NOT!
      //if ($user -> getUserEmail == '') {
        //$user -> setUserEmail($this -> postVar("email_recipient"));
        //$user -> save();
      //}
      
      $this -> orderuser = $user;
      setUser( $this -> context -> getUser(), $this -> orderuser );
      return $this -> orderuser -> User -> getUserId();
    }
    
  }
  
  function postOrderUser( $update=false ) {
    
    $user = getUserById( $this -> sessionVar("user_id") );
     
    if (count($user) == 0) {
      //$fname,$lname,$email,$username,$password,$name=null,$birthday=null,$ual=1
      $this -> orderuser = createUser( $this -> context, 
                          $this -> postVar("first_name"),
                          $this -> postVar("last_name"),
                          $this -> postVar("email"),
                          $this -> postVar("email"),
                          generatePassword());
      setUser( $this -> context -> getUser(), $this -> orderuser );
      //sendJoinEmail( $this -> orderuser, $this -> context );
      $this -> ordernewuser = 1;
      
    } else {
      
      //If there is no user email, we're takin' it!
      // NOTE:: NOT!
      //if ($user -> getUserEmail == '') {
        //$user -> setUserEmail($this -> postVar("email_recipient"));
        //$user -> save();
      //}
      
      $this -> orderuser = $user;
      
      if ($update) {
        
        if($this->orderuser->getUserFname() == "") {
          $fname = $this -> postVar("first_name");
          $this -> orderuser -> setUserFname($this -> postVar("first_name"));
        } else {
          $fname = $this->orderuser->getUserFname();
        }
        if($this->orderuser->getUserLname() == "") {
          $lname = $this -> postVar("last_name");
          $this -> orderuser -> setUserLname($this -> postVar("last_name"));
        } else {
          $lname = $this->orderuser->getUserLname();
        }
        if($this->orderuser->getUserEmail() == "") {
          $email = $this -> postVar("email");
          $this -> orderuser -> setUserEmail($this -> postVar("last_name"));
        } else {
          $email = $this->orderuser->getUserEmail();
        }
        
        $this -> orderuser = updateUser( $this -> context, 
                            $this -> orderuser,
                            $fname,
                            $lname,
                            $email,
                            $this -> postVar("b_address1"),
                            $this -> postVar("b_address2"),
                            $this -> postVar("b_city"),
                            $this -> postVar("b_state"),
                            $this -> postVar("b_zipcode"),
                            $this -> postVar("b_country") );
       }                  
      /*
      if (! $this -> user_id) {
        setUser( $this -> context -> getUser(), $this -> orderuser );
      }
      */
      
      $this -> ordernewuser = 0;
    }
   
  }
  
  /*********************************
   * ALL THE PPWPP Stuff
   * *******************************/     
  //this checks if a user has purchsed a prior screening
  function checkPrior( $type, $id, $key, $date=null, $time=null) {
     $pp = new PaypalWppOrders( $this );
     return $pp -> checkPrior( $type, $id, $key, $date, $time );
  }
  
  //this checks if an audience member has purchsed a prior screening
  function checkPriorSeat( $id, $key, $date=null, $time=null) {
     $pp = new PaypalWppOrders( $this );
     return $pp -> checkPriorSeat( $id, $key, $date, $time );
  }
  
  //this checks if a user has purchsed a prior screening
  function checkPriorOwner( $id, $key, $date=null, $time=null) {
     $pp = new PaypalWppOrders( $this );
     return $pp -> checkPriorOwner( $id, $key, $date, $time );
  }
  
  function postOrder( $type = "screening" ) {
    $pp = new PaypalWppOrders( $this );
    $pp -> postOrder( $type );
  }
  
  function insertOrder( $user, $film, $type = "screening" ) {
    $pp = new PaypalWppOrders( $this );
    $pp -> insertOrder( $user, $film, $type );
  }
  
  //In case we have a two-step order, as in subscriptions
  function updateOrder( $type = "subscription" ) {
    $pp = new PaypalWppOrders( $this );
    $pp -> updateOrder( $type );
  }
  
  function postItem( $obj=null, $type="screening") {
    $pp = new PaypalWppOrders( $this );
    $pp -> postItem( $obj, $type );
  }
  
  //**************************************************************
  // These two functions are for the "SPONSOR CODE" insertion
  function insertHostItem( $user=null, $obj=null, $date, $time ) {
    $pp = new PaypalWppOrders( $this );
    $pp -> insertHostItem( $user, $obj, $date, $time  );
  }
  
  function insertScreeningItem( $obj=null, $code ) {
    $pp = new PaypalWppOrders( $this );
    return $pp -> insertScreeningItem( $obj, $code );
  }
  //**************************************************************
  
  //**************************************************************
  //Creates a Payment and Audience Item
  function instantPurchase( $user_id, $screening_id, $processor ) {
	 $pp = new PaypalWppOrders( $this );
    return $pp -> instantPurchase( $user_id, $screening_id, $processor );
	}
 	//**************************************************************
	  
  function confirmItem( $obj=null, $type="screening") {
    $pp = new PaypalWppOrders( $this );
    return $pp -> confirmItem( $obj, $type );
  }
  
  function confirmOrder( $obj=null, $type="screening") {
    $pp = new PaypalWppOrders( $this );
    $pp -> confirmOrder( $obj, $type );
  }
  
  function postAdminScreeningItem( $obj=null ) {
    $pp = new PaypalWppOrders( $this );
    return $pp -> postAdminScreeningItem( $obj );
  }
  
  /*********************************
   * ALL THE PAYPAL EXPRESS Stuff
   * *******************************/
  function checkPriorPaypal( $trans, $prod ) {
    $pp = new PaypalExpressOrders( $this );
    return $pp -> checkPriorOrder( $id, $key );
  }
  
  function postPaypalOrder( $data, $obj, $type = "screening", $enforcing=true, $admin=false ) {
    $pp = new PaypalExpressOrders( $this );
    $pp -> postOrder( $data, $obj, $type, $enforcing, $admin );
  }
  
  function postPaypalItem( $data, $obj, $type="screening" ) {
    $pp = new PaypalExpressOrders( $this );
    $pp -> postItem( $data, $obj, $type );
  }
  
  function confirmPaypalItem( $data, $obj=null, $type="screening") {
    $pp = new PaypalExpreeOrders( $this );
    $pp -> postItem( $data, $obj, $type );
  }
  
  /***********************
   * ALL PPWPP Transaction Management
   * ***********************/     
  function processPPWPPOrder( $type = "ticket" ) {
    
    if ($this -> orderskus == null) {
      return false;
    }
    
    //$this -> checkOrderVelocity( $this -> crud -> Payment -> getFkUserId() );
    
    /*************************
     *Skipping Maxmind for Now
     *************************/
    //$this -> ordermaxmind["score"] = 0;
    //$this -> ordermaxmind["explanation"] = "Not Currently Using Maxmind";
          
    if ($this -> postVar("credit_card_number") == "371449635398431") {
      $this -> ordermaxmind["score"] = 0;
      //$this -> ordermaxmind = a:29:{s:8:"distance";s:1:"0";s:12:"countryMatch";s:2:"No";s:11:"countryCode";s:0:"";s:8:"freeMail";s:3:"Yes";s:14:"anonymousProxy";s:2:"No";s:5:"score";s:4:"5.00";s:8:"binMatch";s:3:"Yes";s:10:"binCountry";s:2:"US";s:3:"err";s:12:"IP_NOT_FOUND";s:10:"proxyScore";s:4:"0.00";s:9:"ip_region";s:0:"";s:7:"ip_city";s:0:"";s:11:"ip_latitude";s:0:"";s:12:"ip_longitude";s:0:"";s:7:"binName";s:0:"";s:6:"ip_isp";s:0:"";s:6:"ip_org";s:0:"";s:12:"binNameMatch";s:2:"NA";s:13:"binPhoneMatch";s:2:"NA";s:8:"binPhone";s:0:"";s:21:"custPhoneInBillingLoc";s:2:"No";s:15:"highRiskCountry";s:2:"No";s:16:"queriesRemaining";s:4:"6841";s:15:"cityPostalMatch";s:3:"Yes";s:19:"shipCityPostalMatch";s:0:"";s:9:"maxmindID";s:8:"CPKRKZXX";s:11:"carderEmail";s:2:"No";s:9:"riskScore";s:4:"5.90";s:11:"explanation";s:496:"You should review this order carefully, as it is considered high risk. We suggest you be very cautious about accepting this order. The order is slightly riskier because the e-mail domain, gmail.com, is a free e-mail provider. The order is slightly riskier because the phone number supplied by the user is not located within the zip code of the billing address for the credit card. The order is higher risk because the billing country and the country in which the IP address is located don't match";}
    } else {
      //Check for Maxmind Fraud
      $this -> ordermaxmind = MaxmindCheck($this -> crud -> Payment -> getPaymentBCity(),
                            $this -> crud -> Payment -> getPaymentBState(),
                            $this -> crud -> Payment -> getPaymentBZipcode(),
                            $this -> crud -> Payment -> getPaymentBCountry(),
                            "",
                            $this -> crud -> Payment -> getPaymentEmail(),
                            $this -> postVar("credit_card_number"),
                            $this -> crud -> Payment -> getPaymentUniqueCode()
                            );
    }
    
    //Due to the port forwarding on the LB in SSL, the IP is passed in a session variable
    //Kinda messed up, but what can you do?
    if (left(REMOTE_ADDR(),8) == "184.75.46") {
      $fuip=true;
    }
    if (left(REMOTE_ADDR(),9) == "69.175.63") {
      $fuip=true;
    }
    if (left(REMOTE_ADDR(),9) == "10.32.165") {
      $fuip=true;
    } 
    if (left(REMOTE_ADDR(),9) == "192.168.2") {
      $fuip=true;
    }
    
    //Maxmind can be a terminal error, so do this prior to running to Payflow;
    if ((! $fuip) && ($this -> ordermaxmind["score"] > (sfConfig::get("app_maxmind_threshold")))) {
      
      $pflo = false;
      $PPWPP = new PaypalWPP_Gateway();
      $PPWPP -> PPWPP_Result_Code = -3;
      if ($this -> ordermaxmind["score"] > (sfConfig::get("app_block_threshold"))) {
        $this -> blockIP();
      }
    } else {
      
      $ppwpp = true;
      //Send Order to Paypal
      //$discount = ($this -> crud -> Payment -> getUserOrderDiscount() > 0) ? $this -> crud -> Payment -> getUserOrderDiscount() : 0;
      $discount = 0;
      
      $PPWPP = new PaypalWPP_Gateway( $this -> context );
      $PPWPP -> action = "DoDirectPayment";
      $PPWPP -> amount = $this -> ordertotal - $discount;
      //$PPWPP -> amount = "900.00";
      $PPWPP -> order_num = $this -> orderguid;
      //These are taken from POST variables, since we don't save them.
      $PPWPP -> card_num = $this -> postVar("credit_card_number");
      $PPWPP -> cvv2 = cvv2Mask($this -> postVar("credit_card_number"),$this -> postVar("card_verification_number"));       // 123
      $PPWPP -> card = cctypeMask($this -> postVar("credit_card_number"));
      
      //These come from the Crud
      $PPWPP -> expiry = sprintf("%04d",$this -> crud -> Payment -> getPaymentCcExp()); // We only use a 2-digit year.  Need this due to bug in PHP on the date function.
      $PPWPP -> fname = $this -> crud -> Payment -> getPaymentFirstName();
      $PPWPP -> lname = $this -> crud -> Payment -> getPaymentLastName();
      $PPWPP -> email = $this -> crud -> Payment -> getPaymentEmail();
      $PPWPP -> addr1 = $this -> crud -> Payment -> getPaymentBAddr1();
      $PPWPP -> addr2 = $this -> crud -> Payment -> getPaymentBAddr2();
      $PPWPP -> city = $this -> crud -> Payment -> getPaymentBCity();
      $PPWPP -> state = $this -> crud -> Payment -> getPaymentBState();
      $PPWPP -> zip = $this -> crud -> Payment -> getPaymentBZipcode();
      $PPWPP -> country = $this -> crud -> Payment -> getPaymentBCountry();// 3-digits ISO code
      $PPWPP -> cust_ip = $this -> crud -> Payment -> getPaymentIp();
      
      //Alternate Details
      //On a per-type basis
      if ( $type == "ticket" ) {
        $PPWPP -> custom1 = "Screening: ". implode(",",$this -> orderskus)." | Seat: ".$this -> orderitems[0] -> Audience -> getAudienceInviteCode()." | Cost: ".$this -> ordertotal;
        $PPWPP -> ldescn = $this -> orderitems[0] -> Audience -> getAudienceInviteCode();
       	$PPWPP -> lnumn = "User: " . $this -> orderitems[0] -> Audience -> getFkUserId();
      } elseif ( $type == "host" ) {
        $PPWPP -> custom1 = "Film: ". $this -> orderitems[0] -> Screening -> getFkFilmId() ." | Screening: ".implode(",",$this -> orderskus)." | Cost: ".$this -> ordertotal;
        $PPWPP -> ldescn = $this -> orderitems[0] -> Screening -> getScreeningUniqueKey();
       	$PPWPP -> lnumn = "Host: " . $this -> orderitems[0] -> Screening -> getFkHostId();
      } elseif ( $type == "subscription" ) {
        $PPWPP -> custom1 = "Subscription: ". $this -> orderitems[0] -> Subscription -> getSubscriptionId() ." | Cost: ".$this -> ordertotal;
        $PPWPP -> ldescn = $this -> orderitems[0] -> Subscription -> getSubscriptionType() . " Tickets: " . $this -> orderitems[0] -> Subscription -> getSubscriptionTotalTickets() . " " .$this -> orderitems[0] -> Subscription -> getSubscriptionTerm() . " " . $this -> orderitems[0] -> Subscription -> getSubscriptionPeriod();
       	$PPWPP -> lnumn = "User: " . $this -> orderitems[0] -> Subscription -> getFkUserId();
      }
      
      $PPWPP -> custom2 = "M:".$this -> ordermaxmind["score"]." ".substr($this -> ordermaxmind["explanation"],0,50);
      $PPWPP -> lamtn = $this -> ordertotal;
     	$PPWPP -> lnamen = implode(",",$this -> orderskus);
     	$PPWPP -> lqtyn = count($this -> orderskus);
      //dump($PPWPP);
      
      $PPWPP -> commit();
      
    }
    //dump($PPWPP -> PPWPP_Result_Code);
      
    $this -> order_result_code = $PPWPP -> PPWPP_Result_Code;
    
    $this -> context ->getLogger()->info("{Order Manager} Order:: ".$this -> orderguid."\" Result:: ".$PPWPP -> PPWPP_Result_Code."\"");
          
          
    //$this -> order_result_code = -1;
    //Order Totals aren't terminal, but do force a "review" status
    /*Not using this for Fraud RIght now
    if ((! $this -> checkOrderTotal($this -> ordertotal + $this -> ordershipping)) && ($this -> order_result_code > 0)) {
      $this -> order_result_code = 1;
    }
    
    //Order Address Mismatches aren't terminal, but do force a "review" status
    if ((! $download) && (! $this -> checkOrderAddress()) && ($this -> order_result_code > 0)) {
      $this -> order_result_code = 1;
    }
    */
    
    //Record Result
    //$this -> crud -> setUserOrderShipping( $this -> ordershipping );
    //$this -> crud -> setUserOrderSubtotal( $this -> ordertotal - $discount);
    //$this -> crud -> setUserOrderTotal( $this -> ordertotal + $this -> ordershipping - $discount );
    //$this -> crud -> setUserOrderCcType( cctypeMask(decrypt($this -> crud -> Payment -> getUserOrderCcNumber())) );
    
    $this -> crud -> setPaymentFraudScore( $this -> ordermaxmind["score"] );
    $this -> crud -> setPaymentMaxmindObject( serialize($this -> ordermaxmind) );
    $this -> crud -> setPaymentUpdatedAt( now() );
    //We're not allowed to save CVV2 by PCI DSS
    $this -> crud -> setPaymentCvv2( null );
    $this -> crud -> setPaymentOrderProcessor( "Paypal Web Payments Pro" );
    $this -> crud -> setPaymentStatus( $this -> order_result_code );
    $this -> crud -> setPaymentTransactionId( $PPWPP -> nvpArray["TRANSACTIONID"] );
    $this -> crud -> save();
    
    if ($ppwpp) {
      $trans = new TransactionCrud( $this -> context );
      $trans -> setFkPaymentId( $this -> crud ->Payment -> getPaymentId());
      $trans -> setTransactionNumber( $PPWPP -> nvpArray["TRANSACTIONID"] );
      $trans -> setTransactionResponseObject( serialize($PPWPP -> nvpArray) );
      $trans -> setTransactionGatewayEnvironment( $PPWPP -> pfpro_environment );
      $trans -> setTransactionFraudAlert( $PPWPP ->nvpArray["POSTFPSMSG"] );
      $trans -> setTransactionAuthCode( $PPWPP ->nvpArray["CORRELATIONID"] );
      $trans -> setTransactionStatus( $this -> order_result_code );
      $trans -> setTransactionCreatedAt( now() );
      $trans -> save();
    }
    
    if ($this -> order_result_code == 2) {
      return true;
    }
    return false;
  }
  
  function voidPFOrder( $id ) {
  
    $this -> hydrateOrder( $id );
    
    if ($this -> crud -> Payment -> getUserOrderPaymentProcessor() == "Verisign") {
      $PFPro = new PFPro_Gateway();
      $PFPro -> action = "VoidTransaction";
      $PFPro -> trans_id = $this -> crud -> Payment -> getUserOrderTransactionNumber();
      $PFPro -> commit();
      if ($PFPro -> nvpArray["RESULT"] == 0) {
        $this -> crud -> Payment -> setUserOrderStatus( -7 );
        $this -> crud -> Payment -> setUserOrderConfNum( serialize($PFPro -> nvpArray) );
        $this -> crud -> Payment -> save();
        $this -> sendDeclineNotification();
        
        $solr = new SolrManager_PageWidget(null, null, $this -> context);    
        $solr -> execute("add","order",$this -> crud -> Payment -> getUserOrderId());
        
        return "This order was cancelled.";
      } else {
        $this -> crud -> Payment -> setUserOrderStatus( -7 );
        $this -> crud -> Payment -> setUserOrderConfNum( serialize($PFPro -> nvpArray) );
        $this -> crud -> Payment -> save();
        $this -> sendDeclineNotification();
        
        $solr = new SolrManager_PageWidget(null, null, $this -> context);    
        $solr -> execute("add","order",$this -> crud -> Payment -> getUserOrderId());
        
        return $PFPro -> nvpArray["RESPMSG"];
      }
    } elseif ($this -> crud -> Payment -> getUserOrderPaymentProcessor() == "MerchantOne") {
      $MOne = new MerchantOne_Gateway();
      $MOne -> action = "VoidTransaction";
      $MOne -> trans_id = $this -> crud -> Payment -> getUserOrderTransactionNumber();
      $MOne -> commit();
      
      if ($MOne -> nvpArray["response_code"] == 100) {
        $this -> crud -> Payment -> setUserOrderStatus( -7 );
        $this -> crud -> Payment -> setUserOrderConfNum( serialize($MOne -> nvpArray) );
        $this -> crud -> Payment -> save();
        $this -> sendDeclineNotification();
        
        $solr = new SolrManager_PageWidget(null, null, $this -> context);    
        $solr -> execute("add","order",$this -> crud -> Payment -> getUserOrderId());
        
        return "This order was cancelled.";
      } else {
        $this -> crud -> Payment -> setUserOrderStatus( -7 );
        $this -> crud -> Payment -> setUserOrderConfNum( serialize($MOne -> nvpArray) );
        $this -> crud -> Payment -> save();
        $this -> sendDeclineNotification();
        
        $solr = new SolrManager_PageWidget(null, null, $this -> context);    
        $solr -> execute("add","order",$this -> crud -> Payment -> getUserOrderId());
        
        return $MOne -> nvpArray["responsetext"];
      }
      
    }
    
  }
  
  function approvePFOrder( $id, $force=false ) {
    
    $this -> hydrateOrder( $id );
    
    if ($this -> crud -> Payment -> getUserOrderPaymentProcessor() == "Verisign") {
      
      $verisignobj = unserialize($this -> crud -> Payment -> getUserOrderResponseObject() );
      
      if ((($verisignobj["RESULT"] == 126 ) || ($verisignobj["RESULT"] == 127)) && (! $force)) {
        if ($this -> crud -> Payment -> getUserOrderStatus() == 1) {
          return "This order is on hold with FPS, so you'll need to go to <a href='http://manager.paypal.com'>Paypal</a> and approve manually.<br /><br />Once you're done, click <a href='/orders/force/".$id."'>here</a> to finalize the order.";
        } else {
          return "This order has a result of ".$verisignobj["RESULT"].", please contact the system administrator.";
        }
      }
      $this -> crud -> setUserOrderStatus( 2 );
      $this -> crud -> save();
      
      $solr = new SolrManager_PageWidget(null, null, $this -> context);    
      $solr -> execute("add","order",$this -> crud -> Payment -> getUserOrderId());
      
      return "This order was approved.";
    } elseif ($this -> crud -> Payment -> getUserOrderPaymentProcessor() == "MerchantOne") {
      if ($force) {
        
        $this -> crud -> setUserOrderStatus( 2 );
        $this -> crud -> save();
        
        $solr = new SolrManager_PageWidget(null, null, $this -> context);    
        $solr -> execute("add","order",$this -> crud -> Payment -> getUserOrderId());
        
        return "This order was approved.";
      }
        
      return "This feature not available for Merchant One orders. Please contact the system administrator.";
    }
  }
  
  
  function refundPFOrder( $id ) {
  
    $this -> hydrateOrder( $id );
    
    if ($this -> crud -> Payment -> getUserOrderPaymentProcessor() == "Verisign") {
      $PFPro = new PFPro_Gateway();
      $PFPro -> action = "RefundTransaction";
      $PFPro -> trans_id = $this -> crud -> Payment -> getUserOrderTransactionNumber();
      $PFPro -> commit();
      if ($PFPro -> nvpArray["RESULT"] == 0) {
        $this -> crud -> Payment -> setUserOrderStatus( -7 );
        $this -> crud -> Payment -> setUserOrderConfNum( serialize($PFPro -> nvpArray) );
        $this -> crud -> Payment -> save();
          
        $solr = new SolrManager_PageWidget(null, null, $this -> context);    
        $solr -> execute("add","order",$this -> crud -> Payment -> getUserOrderId());
      
        return "This order was cancelled.";
      } else {
        $PFPro -> action = "CreditTransaction";
        $PFPro -> trans_id = array(decrypt($this -> crud -> Payment -> getUserOrderCcNumber()),$this -> crud -> Payment -> getUserOrderCcExp(),$this -> crud -> Payment -> getUserOrderTotal());
        $PFPro -> commit();
          
        $this -> crud -> Payment -> setUserOrderStatus( -7 );
        $this -> crud -> Payment -> setUserOrderConfNum( serialize($PFPro -> nvpArray) );
        $this -> crud -> Payment -> save();
        
        $solr = new SolrManager_PageWidget(null, null, $this -> context);    
        $solr -> execute("add","order",$this -> crud -> Payment -> getUserOrderId());
        
        return $PFPro -> nvpArray["RESPMSG"];
      }
    } elseif ($this -> crud -> Payment -> getUserOrderPaymentProcessor() == "MerchantOne") {
      $MOne = new MerchantOne_Gateway();
      $MOne -> action = "RefundTransaction";
      $MOne -> trans_id = $this -> crud -> Payment -> getUserOrderTransactionNumber();
      $MOne -> commit();
      if ($MOne -> nvpArray["response_code"] == 100) {
        $this -> crud -> Payment -> setUserOrderStatus( -7 );
        $this -> crud -> Payment -> setUserOrderConfNum( serialize($MOne -> nvpArray) );
        $this -> crud -> Payment -> save();
          
        $solr = new SolrManager_PageWidget(null, null, $this -> context);    
        $solr -> execute("add","order",$this -> crud -> Payment -> getUserOrderId());
      
        return "This order was cancelled.";
      } else {
        $MOne -> action = "RefundTransaction";
        $MOne -> trans_id = array(decrypt($this -> crud -> Payment -> getUserOrderCcNumber()),$this -> crud -> Payment -> getUserOrderCcExp(),$this -> crud -> Payment -> getUserOrderTotal());
        $MOne -> commit();
          
        $this -> crud -> Payment -> setUserOrderStatus( -7 );
        $this -> crud -> Payment -> setUserOrderConfNum( serialize($MOne -> nvpArray) );
        $this -> crud -> Payment -> save();
        
        $solr = new SolrManager_PageWidget(null, null, $this -> context);    
        $solr -> execute("add","order",$this -> crud -> Payment -> getUserOrderId());
        
        return $MOne -> nvpArray["responsetext"];
      }
    }
  }
  
  function dismissPFOrder( $id ) {
  
    $this -> hydrateOrder( $id );
    $this -> crud -> Payment -> setUserOrderStatus( -20 );
    $this -> crud -> Payment -> save();
    
    $solr = new SolrManager_PageWidget(null, null, $this -> context);    
    $solr -> execute("add","order",$this -> crud -> Payment -> getUserOrderId());
    
    return "This order was dismissed.";
   
  }
  
  function voiceAuthPFOrder( $id, $code ) {
    $this -> hydrateOrder( $id );
    
    if ($this -> crud -> Payment -> getUserOrderPaymentProcessor() == "Verisign") {
      $PFPro = new PFPro_Gateway();
      $PFPro -> action = "VoiceAuthTransaction";
      $PFPro -> trans_id = $this -> crud -> Payment -> getUserOrderTransactionNumber();
      $PFPro -> auth_code = $code;
      $PFPro -> commit();
      if ($PFPro -> nvpArray["RESULT"] == 0) {
        $this -> crud -> Payment -> setUserOrderStatus( 3 );
        $this -> crud -> Payment -> setUserOrderConfNum( serialize($PFPro -> nvpArray) );
        $this -> crud -> Payment -> save();
        
        $solr = new SolrManager_PageWidget(null, null, $this -> context);    
        $solr -> execute("add","order",$this -> crud -> Payment -> getUserOrderId());
      
        return "This order was authorized.";
      } else {
        $this -> crud -> Payment -> setUserOrderConfNum( serialize($PFPro -> nvpArray) );
        $this -> crud -> Payment -> save();
        
        $solr = new SolrManager_PageWidget(null, null, $this -> context);    
        $solr -> execute("add","order",$this -> crud -> Payment -> getUserOrderId());
        
        return $PFPro -> nvpArray["RESPMSG"];
      }
    } elseif ($this -> crud -> Payment -> getUserOrderPaymentProcessor() == "MerchantOne") {
      return "This feature not available for Merchant One orders. Please contact the system administrator.";
    }
  }
  
  function readPFOrder( $id ) {
    $this -> hydrateOrder( $id );
    $PFPro = new PFPro_Gateway();
    $PFPro -> action = "ReadTransaction";
    $PFPro -> trans_id = $this -> crud -> Payment -> getUserOrderTransactionNumber();
    $PFPro -> commit();
    if ($PFPro -> nvpArray["RESULT"] == 0) {
      $result = serialize($PFPro -> nvpArray);
      return $result;
    } else {
      return $PFPro -> nvpArray["RESPMSG"];
    }
  }
  
  function shipOrder( $id ) {
  
    $this -> hydrateOrder( $id );
    $this -> crud -> Payment -> setUserOrderProcess( 1 );
    $this -> crud -> Payment -> save();
    
    $solr = new SolrManager_PageWidget(null, null, $this -> context);    
    $solr -> execute("add","order",$this -> crud -> Payment -> getUserOrderId());
    
    return "This order was shipped.";
   
  }
  
  function sendOrderNotification( $film ) {
    
    #putLog("Payment Status: ".$this -> crud -> Payment -> getPaymentStatus());
    
    if ($this -> crud -> Payment -> getPaymentStatus() == 2) {
      sendOrderEmail( $this -> orderuser, $this -> crud -> Payment, $this -> orderitems, $film, $this -> context );
    }
    
  }
  
  function sendHostNotification( $film, $ticket ) {
    
    #putLog("Payment Status: ".$this -> crud -> Payment -> getPaymentStatus());
    
    if ($this -> crud -> Payment -> getPaymentStatus() == 2) {
      sendHostEmail( $this -> orderuser, $this -> crud -> Payment, $this -> orderitems, $film, $ticket, $this -> context );
    }
    
  }
  
  function sendDeclineNotification() {
    sendDeclineEmail( $this -> orderuser, $this -> orderarray["data"][0], $this -> orderitems_array["data"], $this -> context );
  }
  
  public function postSolrOrder( $order=false,$audience=false,$screening=false,$user=false) {
    
    //$subject = "Order Posting to SOLR at ".now()."<br />";
    //Update the SOLR Search Engine
    $solr = new SolrManager_PageWidget(null, null, $this -> context);
    if (($order) && (is_numeric($order))) {
      $message = "\$order::"." ".$order."<br />";
      $oid = $order;
      $solr -> execute("add","payment",$order);
    } elseif ($this -> crud -> Payment -> getPaymentId() > 0) {
      $message = "\$this -> crud -> Payment -> getUserOrderId()::"." ".$this -> crud -> Payment -> getUserOrderId()."<br />";
      $oid = $this -> crud -> Payment -> getPaymentId();
      $solr -> execute("add","payment",$this -> crud -> Payment -> getPaymentId());
    } elseif ($this -> orderarray["data"][0]["user_payment_id"] > 0) {
      $message = "\$this -> orderarray[\"data\"][0][\"payment_id\"]::"." ".$this -> orderarray["data"][0]["payment_id"]."<br />";
      $oid = $this -> orderarray["data"][0]["payment_id"];
      $solr -> execute("add","payment",$this -> orderarray["data"][0]["payment_id"]);
    } else {
      $message = "No Payment"."<br />";
      return false;
      //die("No Order!");
    }
    
    if ($audience) {
      $solr -> execute("add","audience",$audience);
    } else {
      $solr -> execute("add","audience",$this -> crud -> Payment -> getFkAudienceId());
    }
    
    if ($screening) {
      $solr -> execute("add","screening",$screening);
    } else {
      $solr -> execute("add","screening",$this -> crud -> Payment -> getFkScreeningId());
    }
    
    if ($user) {
      $solr -> execute("add","user",$user);
    } else {
      $solr -> execute("add","user",$this -> crud -> Payment -> getFkUserId());
    }
  
    //$d = new WTVRData( null );
    //$dconvertSql = "UPDATE user_order set user_order_solr_post = 1  where user_payment_id = ".$oid.";";
    //$d -> propelQuery($dconvertSql);
    
    //QAMail( $message , $subject );
    
  }
  
  public function checkOrderTotal( $amount ) {
    if (($amount > sfConfig::get("app_order_total_threshold")) && (! forTesting())) {return false;}
    return true;
  }
  
  public function checkOrderAddress() {
    if (! $this -> crud) return false;
    $ba=strtolower(trim($this -> crud -> Payment -> getUserOrderBillingAddress()) . 
        trim($this -> crud -> Payment -> getUserOrderBillingAddress2()) . 
        trim($this -> crud -> Payment -> getUserOrderBillingCity()) . 
        trim($this -> crud -> Payment -> getUserOrderBillingState()) . 
        trim($this -> crud -> Payment -> getUserOrderBillingZip()));
    $sa=strtolower(trim($this -> crud -> Payment -> getUserOrderShipAddress()) . 
        trim($this -> crud -> Payment -> getUserOrderShipAddress2()) . 
        trim($this -> crud -> Payment -> getUserOrderShipCity()) . 
        trim($this -> crud -> Payment -> getUserOrderShipState()) . 
        trim($this -> crud -> Payment -> getUserOrderShipZip()));
    $ba=preg_replace("/[^a-zA-Z0-9]/", "", $ba);
    $sa=preg_replace("/[^a-zA-Z0-9]/", "", $sa);
    
    if ($ba != $sa) {
      return false;
    }
    return true;
    
  }
  
  public function checkOrderVelocity( $user_id ) {
    $sql = "select count(payment_id) from payment where fk_user_id = ".$user_id." and payment_created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY);";
    $res = $this -> propelQuery($sql);
    
    while ($res->next()) { $count = $res -> get(1); }
    if (($count > sfConfig::get("app_order_velocity_threshold")) && (! forTesting())) {
      $this -> blockIP();
      return true;
    }
    return false;
  }
  
  function validateCoupon( $cart ) {  
    if ($this -> postVar("user_order_coupon")) {
      $this -> coupon_value = validatePromotionValue( $cart, $this -> postVar("user_order_coupon"), $this -> orderuser -> getUserId() );
    }
  }
  
  public function createCouponCode() {
    $this -> coupon_code = createPromotion( $this -> orderuser -> getUserId(), $this -> orderuser -> getUserEmail(), $this -> orderitems[0] -> getFkProductSku() );
  }
  
  public function getCouponCode( $user_id, $sku ) {
    $sql = "select product_promotion_code_value from product_promotion_code where fk_user_id = " . $user_id . " and product_promotion_unique_key = '".$sku."' order by product_promotion_code_id limit 1";
    $res = $this -> propelQuery($sql);
    if ($res) {
      while ($res -> next()) {
      	return $res -> get(1);
      }
    }
    return "";
  }
  
  public function recordPromoUse( $user_id, $code, $film, $audience, $screening, $created ) {
    $promoc = new PromoCodeCrud( $this -> context );
    $vars = array("promo_code_code"=>$code,"fk_film_id"=>$film);
    $promoc -> checkUnique( $vars );
    if ($promoc -> PromoCode -> getPromoCodeId() > 0) {
      $pcu = new PromoCodeUseCrud( $this -> context );
      $pcu -> setPromoCodeUseCode($code);
      $pcu -> setFkPromoCodeId($promoc -> PromoCode -> getPromoCodeId());
      $pcu -> setFkUserId($user_id);
      $pcu -> setFkFilmId($film);
      $pcu -> setFkAudienceId($audience);
      $pcu -> setFkScreeningId($screening);
      $pcu -> setPromoCodeUseDate($created);
      $pcu -> save();
      
      $promoc -> PromoCode -> setPromoCodeUses($promoc -> PromoCode -> getPromoCodeUses() + 1);
    	$promoc -> PromoCode -> save();
    
     }
     
  }
  
  public function recordReferer( $user_id, $referer, $film, $audience, $screening, $created ) {
    $promoc = new PurchaseRefererCrud( $this -> context );
    $vars = array("fk_user_id"=>$user_id,"fk_screening_id"=>$screening);
    $promoc -> checkUnique( $vars );
    if ($promoc -> PurchaseReferer -> getPurchaseRefererId() < 1) {
      $promoc -> setPurchaseRefererReferer($referer);
      $promoc -> setFkUserId($user_id);
      $promoc -> setFkFilmId($film);
      $promoc -> setFkAudienceId($audience);
      $promoc -> setFkScreeningId($screening);
      $promoc -> setFkAudienceDate($created);
      $promoc -> save();
     }
     
  }
  
}

?>
