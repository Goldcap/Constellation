<?php

class PaypalExpressOrders extends Widget_PageWidget {

  var $order;
  var $context;
  
  public function __construct( $order=null,$context=null ) {
    if (! is_null($order)) {
      $this -> order = $order;
      parent::__construct( $order -> context );
    } elseif (! is_null($context)) {
      $this -> context = $context;
    } else {
      throw new Exception('Context unavailable in Paypal Express.');
        return false;
    }
  } 
  
  function checkPrior ( $type, $id, $key, $date=null, $time=null ) {
    switch ($type){
      case "screening":
        return $this -> checkPriorScreening( $id, $key );
        break;
      case "host":
        return $this -> checkPriorHost( $id, $key, $date, $time );
        break;
      case "subscription":
        return $this -> checkPriorSubscription( $id, $key, $date, $time );
        break;
    }
  }
  
  public function checkPriorScreening( $id, $key ) {
    //Update THis Function
    return false;
    die("UPDATE CHECK PRIOR PAYPAL ORDER");
    sfConfig::set("trans", $trans);
    sfConfig::set("prod", $prod);
    
    $sql = "select payment_id
            from payment
            inner join audience
            on payment.fk_audience_id = audience.audience_id
            where payment.fk_user_id = ".$id." 
            and audience.fk_screening_unique_key = '".$key."' 
            and payment_status > 1;";
    
    $res = $this -> propelQuery($sql);
    while ($row = $res->fetch()) {
      if($row[0] > 0){
        return $row[0];
      }
    }
    return false;
  }
  
  public function checkPriorHost( $id, $key, $date, $time ) {
    //Update This Function
    
    //die("UPDATE CHECK PRIOR PAYPAL ORDER ". $key);
    sfConfig::set("trans", $trans);
    sfConfig::set("prod", $prod);
    
    $sql = "select payment_id
            from payment
            inner join screening
            on payment.fk_screening_id = screening.screening_id
            where payment.fk_user_id = ".$id." 
            and screening.screening_unique_key = '".$key."' 
            and payment_status > 1;";
    
    $res = $this -> propelQuery($sql);
    while ($row = $res->fetch()) {
      if($row[0] > 0){
        return $row[0];
      }
    }
    return false;
  }
  
  public function checkPriorSubscription( $id, $key, $date, $time ) {
    //Update THis Function
    return false;
    die("UPDATE CHECK PRIOR PAYPAL ORDER");
    sfConfig::set("trans", $trans);
    sfConfig::set("prod", $prod);
    
    $sql = "select payment_id
            from payment
            inner join audience
            on payment.fk_audience_id = audience.audience_id
            where payment.fk_user_id = ".$id." 
            and audience.fk_screening_unique_key = '".$key."' 
            and payment_status > 1;";
    
    $res = $this -> propelQuery($sql);
    while ($row = $res->fetch()) {
      if($row[0] > 0){
        return $row[0];
      }
    }
    return false;
  }
  
  
  //This is step one of the Paypal Express Service
  public function callExpressService( $obj, $key, $item, $vars, $type="screening") {
    switch ($type){
      case "screening":
        $this -> callExpressScreening($obj, $key, $item, $vars);
        break;
      case "host":
        $this -> callExpressHost($obj, $key, $item, $vars);
        break;
      case "subscription":
        $this -> callExpressSubscription($obj, $key, $item, $vars);
        break;
    }
  }
  
  //Object is the Screening we want at ticket to
  //Key is the screeing unique key
  public function callExpressScreening($obj, $key, $item, $vars) {
    
    $PPWPP = new PaypalWPP_Gateway( $this -> context );
    $PPWPP -> action = "SetExpressCheckout";
    
    $theitem["name"] = "Constellation.tv Ticket for \"".$obj["screening_film_name"]."\"";
    $vals = explode("-",$vars);
    if (isset($vals[2])) {
      $theitem["amount"] = str_replace("_",".",$vals[2]);
    } else {
      $theitem["amount"] = $obj["screening_film_ticket_price"];
    }
    $theitem["quantity"] = 1;
    $theitem["description"] = strip_tags($obj["screening_film_synopsis"]);
    $theitem["sku"] = $key;
    $PPWPP -> items[] = $theitem;
    $PPWPP -> returnURL = "http://".sfConfig::get("app_domain")."/services/Paypal/return/screening?vars=".$vars;
    $PPWPP -> cancelURL = "http://".sfConfig::get("app_domain")."/services/Paypal/cancel/screening?vars=".$vars;
    $PPWPP -> commit();
    
    $this -> context ->getLogger()->info("{Paypal Response} SCREENING callExpressService\"".$PPWPP -> nvpArray["ACK"]."\"");
    
    if($PPWPP -> nvpArray["ACK"] =="Success"){
     $this -> redirect(sfConfig::get("app_paypal_url").$PPWPP->nvpArray["TOKEN"]);
    } else  {
     $this -> redirect("/boxoffice/screening/".$key."?err=validate");
    }

  }
  
  //Object is the Screening the user has created
  //Key is the screeing unique key
  public function callExpressHost($obj, $key, $item, $vars) {
    
    $PPWPP = new PaypalWPP_Gateway( $this -> context );
    $PPWPP -> action = "SetExpressCheckout";
    
    $theitem["name"] = "Constellation.tv Screening of \"".$obj["screening_film_name"]."\"";
    $vals = explode("-",$vars);
    if (isset($vals[2])) {
      $theitem["amount"] = str_replace("_",".",$vals[2]);
    } else {
      $theitem["amount"] = $obj["screening_film_setup_price"];
    }
    $theitem["quantity"] = 1;
    $theitem["description"] = $obj["screening_film_synopsis"];
    $theitem["sku"] = $key;
    $PPWPP -> items[] = $theitem;
    $PPWPP -> returnURL = "http://".sfConfig::get("app_domain")."/services/Paypal/return/host?vars=".$vars;
    $PPWPP -> cancelURL = "http://".sfConfig::get("app_domain")."/services/Paypal/cancel/host?vars=".$vars;
    $PPWPP -> commit();
    
    $this -> context ->getLogger()->info("{Paypal Response} HOST callExpressService\"".$PPWPP -> nvpArray["ACK"]."\"");
    
    if($PPWPP -> nvpArray["ACK"] =="Success"){
     $this -> redirect(sfConfig::get("app_paypal_url").$PPWPP->nvpArray["TOKEN"]);
    } else  {
     $this -> redirect("/film/".$film."/host_purchase/".$key."?err=validate");
    }
    
  }
  
  //Object here is the Subscription the user created
  //Key is the subscription unique key
  public function callExpressSubscription($obj, $key, $item, $vars) {
    
    $PPWPP = new PaypalWPP_Gateway( $this -> context );
    $PPWPP -> action = "SetExpressCheckout";
    
    $theitem["name"] = "Constellation.tv Tickets";
    if ($obj["subscription_type"] == "gift") {
      $theitem["description"] = $obj["subscription_total_tickets"]." tickets to any film of like value (".$obj["subscription_ticket_price"].") on Consetllation.tv";
      $theitem["quantity"] = $obj["subscription_total_tickets"];
      $theitem["amount"] = $obj["subscription_ticket_price"];
    } elseif ($obj["subscription_type"] == "subscription") {
      $theitem["amount"] = $obj["subscription_total_price"];
      $theitem["description"] = "Subscription (".$obj["subscription_term"].") :: Constellation.tv, ".$obj["subscription_ticket_number"]." tickets, ".$obj["subscription_period"]." ".$obj["subscription_term"].", $".$obj["subscription_ticket_price"];
      $theitem["quantity"] = 1;
    }
    $theitem["sku"] = $key;
    
    $PPWPP -> items[] = $theitem;
    $PPWPP -> returnURL = "http://".sfConfig::get("app_domain")."/services/Paypal/return/subscription?i=".$key;
    $PPWPP -> cancelURL = "http://".sfConfig::get("app_domain")."/services/Paypal/cancel/subscription?i=".$key;
    $PPWPP -> commit();
    
    $this -> context ->getLogger()->info("{Paypal Response} SUBSCRIPTION callExpressService\"".$PPWPP -> nvpArray["ACK"]."\"");
    
    if($PPWPP -> nvpArray["ACK"] =="Success"){
     $this -> redirect(sfConfig::get("app_paypal_url").$PPWPP->nvpArray["TOKEN"]);
    } else  {
     $this -> redirect("/subscription/purchase/".$key."?err=validate");
    }
  }
  
  //This is step one of the Paypal Express Service
  public function getExpressDetails( $obj, $key, $type="screening", $film) {
    switch ($type){
      case "screening":
        $this -> getExpressDetailsScreening($obj, $key, $type, $film);
        break;
      case "host":
        $this -> getExpressDetailsHost($obj, $key, $type, $film);
        break;
      case "subscription":
        $this -> getExpressDetailsSubscription($obj, $key, $type, $film);
        break;
    }
  }
  
  //Object is the Screening we want at ticket to
  //Key is the screeing unique key
  public function getExpressDetailsScreening($obj, $key, $type, $film) {
    
    $PPWPP = new PaypalWPP_Gateway( $this -> context );
    $PPWPP -> action = "GetExpressCheckoutDetails";
    $PPWPP -> token = $this -> getVar("token");
    $PPWPP -> payerId = $this -> getVar("PayerID");
    $PPWPP -> commit();
    
    //Order is complete at Paypal, Post to DB
    $this -> order = new OrderManager_PageWidget( $this -> widget_vars, $this -> page_vars, $this -> context, null );
    $this -> postOrder( $PPWPP -> nvpArray, $obj, $key);
    
    $this -> context ->getLogger()->info("{Paypal Response} SCREEENING getExpressDetails\"".$PPWPP -> nvpArray["ACK"]."\"");
    
    //We posted the data, and Paypal Accepted it, so let's get our money
    if($PPWPP -> nvpArray["ACK"] == "Success"){
      $PPWPP -> action = "DoExpressCheckoutPayment";
      //Need to save a "payment", then retrieve it
      $PPWPP -> token = $this -> getVar("token");
      $PPWPP -> payerId = $this -> getVar("PayerID");
      $vals = explode("-",$this -> getVar("vars"));
      if (isset($vals[2])) {
        $PPWPP -> amount = str_replace("_",".",$vals[2]);
      } else {
        $PPWPP -> amount = $obj["screening_film_ticket_price"];
      }
      $PPWPP -> cust_ip = REMOTE_ADDR();
      $PPWPP -> commit();
      
      $this -> context ->getLogger()->info("{Paypal Response} SCREEENING DoExpressCheckoutPayment\"".$PPWPP -> nvpArray["ACK"]."\"");
      if($PPWPP -> nvpArray["ACK"] == "Success"){
        $this -> postItem($PPWPP -> nvpArray,$obj,$key,$type);
        
        //Send the Ticket Notification Out
        $this -> setGetVar("rev",$key);
        $filmobj = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Purchase/query/Screening_list_datamap.xml");
        $this -> confirmItem( $filmobj, "screening");
        
        $this -> redirect("/boxoffice/screening/".$key."/confirm/paypal/true");
      } else {
        $this -> redirect("/boxoffice/screening/".$key."?err=".$PPWPP -> nvpArray["L_ERRORCODE0"]);
      }
    } else {
      $this -> redirect("/boxoffice/screening/".$key."?err=validate");
    }

  }
  
  public function getExpressDetailsHost($obj, $key, $type, $film) {
    
    $PPWPP = new PaypalWPP_Gateway( $this -> context );
    $PPWPP -> action = "GetExpressCheckoutDetails";
    $PPWPP -> token = $this -> getVar("token");
    $PPWPP -> payerId = $this -> getVar("PayerID");
    $PPWPP -> commit();
    
    //Order is complete at Paypal, Post to DB
    $this -> order = new OrderManager_PageWidget( $this -> widget_vars, $this -> page_vars, $this -> context, null );
    $this -> postOrder( $PPWPP -> nvpArray, $obj, $key, $type);
    
    $this -> context ->getLogger()->info("{Paypal Response} getExpressDetails\"".$PPWPP -> nvpArray["ACK"]."\"");
    
    //We posted the data, and Paypal Accepted it, so let's get our money
    if($PPWPP -> nvpArray["ACK"] == "Success"){
      $PPWPP -> action = "DoExpressCheckoutPayment";
      //Need to save a "payment", then retrieve it
      $PPWPP -> token = $this -> getVar("token");
      $PPWPP -> payerId = $this -> getVar("PayerID");
      $vals = explode("-",$this -> getVar("vars"));
      if (isset($vals[2])) {
        $PPWPP -> amount = str_replace("_",".",$vals[2]);
      } else {
        $PPWPP -> amount = $obj["screening_film_setup_price"];
      }
      $PPWPP -> cust_ip = REMOTE_ADDR();
      $PPWPP -> commit();
      
      $this -> context ->getLogger()->info("{Paypal Response} DoExpressCheckoutPayment\"".$PPWPP -> nvpArray["ACK"]."\"");
    
      if($PPWPP -> nvpArray["ACK"] == "Success"){
        $ticket = $this -> postItem($PPWPP -> nvpArray,$obj,$key,$type);
        
        //Send the Ticket Notification Out
        $this -> setGetVar("op",$film);
        $filmobj = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/FilmView/query/Film_list_datamap.xml");
        $this -> confirmItem( $filmobj, "host", $ticket );
        
        $this -> redirect("/film/".$film."/host_confirm/".$key);
      } else {
        $this -> redirect("/film/".$film."/host_purchase/".$key."?err=".$PPWPP -> nvpArray["L_ERRORCODE0"]);
      }
    } else {
      $this -> redirect("/film/".$film."/host_purchase/".$key."?err=validate");
    }
    
  }
  
  public function getExpressDetailsSubscription($obj, $key, $type, $film) {
    
    $PPWPP = new PaypalWPP_Gateway( $this -> context );
    $PPWPP -> action = "GetExpressCheckoutDetails";
    $PPWPP -> token = $this -> getVar("token");
    $PPWPP -> payerId = $this -> getVar("PayerID");
    $PPWPP -> commit();
    
    //Order is complete at Paypal, Post to DB
    $this -> order = new OrderManager_PageWidget( $this -> widget_vars, $this -> page_vars, $this -> context, null );
    $this -> postOrder( $PPWPP -> nvpArray, $obj, $key, $type);
    
    $this -> context ->getLogger()->info("{Paypal Response} SUBSCRIPTION getExpressDetails\"".$PPWPP -> nvpArray["ACK"]."\"");
    
    //We posted the data, and Paypal Accepted it, so let's get our money
    if($PPWPP -> nvpArray["ACK"] == "Success"){
      $PPWPP -> action = "DoExpressCheckoutPayment";
      //Need to save a "payment", then retrieve it
      $PPWPP -> token = $this -> getVar("token");
      $PPWPP -> payerId = $this -> getVar("PayerID");
      $PPWPP -> amount = $obj["subscription_total_price"];
      $PPWPP -> cust_ip = REMOTE_ADDR();
      $PPWPP -> commit();
      
      $this -> context ->getLogger()->info("{Paypal Response} SUBSCRIPTION DoExpressCheckoutPayment\"".$PPWPP -> nvpArray["ACK"]."\"");
    
      if($PPWPP -> nvpArray["ACK"] == "Success"){
        $this -> postItem($PPWPP -> nvpArray,$obj,$key,$type);
        //We use this step to Create Tickets
        //Based on the term and period in the Subscription
        $this -> confirmItem($obj,$type);
        $this -> redirect("/subscription/invite/".$key);
      } else {
        $this -> redirect("/subscription/purchase/".$key."?err=".$PPWPP -> nvpArray["L_ERRORCODE0"]);
      }
    } else {
      $this -> redirect("/subscription/purchase/".$key."?err=validate");
    }
    
  }
  
  public function postOrder( $data, $obj, $key, $type = "screening", $enforcing=true, $admin=false ) {
    
    //If there is a prior "last_order", with no status
    //Less than 15 minutes old, use IT instead
    //Note this is currently disabled
    $oid = $this -> checkPrior( $type, $this -> sessionVar("user_id"), $key );
    
    if ( $oid > 0 ) {
      $this -> order -> order_dupe = true;
      return $oid;
    }
    
    $this -> order -> hydrateuser( $this -> sessionVar("user_id") );
    
    $vars = array("fk_user_id"=>$this -> sessionVar("user_id"),"fk_screening_id"=>$obj["screening_id"]);
    $this -> order -> crud -> checkUnique( $vars );
    
    //Put Order Post Data in the database
    $this -> order -> crud -> setPaymentFirstName($data["FIRSTNAME"]);
    $this -> order -> crud -> setPaymentLastName($data["LASTNAME"]);
    $this -> order -> crud -> setPaymentEmail($data["EMAIL"]);
    $this -> order -> crud -> setPaymentBAddr1(null);
    $this -> order -> crud -> setPaymentBAddr2(null);
    $this -> order -> crud -> setPaymentBCity(null);
    $this -> order -> crud -> setPaymentBState(null);
    $this -> order -> crud -> setPaymentBZipcode(null);
    $this -> order -> crud -> setPaymentBCountry($data["COUNTRYCODE"]);
    //If we're hosting, we haven't created a screening yet.
    $this -> order -> crud ->  setPaymentType($type);
    if ($type == "screening") {
      $this -> order -> crud -> setFkScreeningId($obj["screening_id"]);
      $this -> order -> crud -> setFkScreeningName($obj["screening_name"]);
      $this -> order -> crud -> setFkFilmId($obj["screening_film_id"]);
    } elseif ( $type == "host" ) {
      $this -> order -> crud -> setFkFilmId($obj["fk_film_id"]);
    } elseif ( $type == "subscription" ) {
      $this -> order -> crud -> setFkSubscriptionId($obj["subscription_id"]);
    }
    //Since we're coming from Paypal, the "AMT" is set for both Seats and Hosting
    $this -> order -> crud -> setPaymentAmount($data["AMT"]);
    $this -> order -> crud -> setPaymentOrderProcessor("Paypal");
    $this -> order -> crud -> setPaymentTransactionId( $data["CORRELATIONID"] );
    //Generate the Order Id
    $this -> order -> orderguid = setUserOrderGuid();
    
    //Place the User in the Order
    if ($this -> sessionVar("user_id")) {
      $this -> order -> crud -> setFkUserId( $this -> sessionVar("user_id") );
    } else {
      throw new Exception('Expected User is unavailable.');
        return false;
    }
    
    $this -> order -> crud -> setPaymentUniqueCode( $this -> order -> orderguid );
    $this -> order -> crud -> setPaymentCreatedAt( now() );
    $this -> order -> crud -> setPaymentIp( REMOTE_ADDR() );
    $this -> order -> crud -> setPaymentSiteId( sfConfig::get("app_site_id") );
    $this -> order -> crud -> Payment -> setPaymentCardType( "Paypal" );
    
    //We are not saving CVV2 for the upcoming transaction, and are required to delete later by PCI DSS
    //$this -> crud -> setPaymentCvv2( $this -> postVar("card_verification_number") );
    
    $this -> order -> crud -> save();
    //Note, this is saved to SOLR in the Purchase Widget, not here
    
    if ($this -> order -> orderuser -> getUserEmail() == "") {
      $this -> order -> orderuser -> setUserEmail($data["EMAIL"]);
      $this -> order -> orderuser -> save();
      $solr = new SolrManager_PageWidget(null, null, $this -> context);
      $solr -> execute("add","user",$this -> order -> orderuser -> getUserId());
    }
    
  }
  
  public function postItem( $data, $obj, $key, $type="screening") {
    switch ($type){
      case "screening":
        return $this -> postScreeningItem($data, $obj, $key);
        break;
      case "host":
        return $this -> postHostItem($data, $obj, $key);
        break;
      case "subscription":
        return $this -> postSubscriptionItem($data, $obj, $key);
        break;
    }
  }
  
  public function postScreeningItem( $data, $obj, $key ) {
    
    if ($this -> order -> order_dupe) {
      return true;
    }
    
    //Add the item to the database
    $item = new AudienceCrud( $this -> context );
    $vars = array("fk_user_id"=>$this -> sessionVar("user_id"),"fk_payment_id"=>$this -> order -> crud -> Payment -> getPaymentId(),"fk_screening_unique_key"=>$key);
    $item -> checkUnique( $vars );
    $item -> setFkScreeningUniqueKey( $key );
    $item -> setFkScreeningId( $obj["screening_id"] );
    $item -> setFkUserId( $this -> sessionVar("user_id") );
    $item -> setAudienceTicketPrice( $obj["screening_film_ticket_price"] );
    $item -> setFkPaymentId( $this -> order -> crud -> Payment -> getPaymentId() );
    $item -> setAudiencePaidStatus( 2 );
    $item -> setAudienceIpAddr( REMOTE_ADDR() );
    $item -> setAudienceCreatedAt( now() );
    $item -> setAudienceStatus( 0 );
    $item -> setAudienceUsername( $this -> sessionVar("user_username") );
    $code = setUserOrderTicket();
    $item -> setAudienceInviteCode( $code );
    $item -> setAudienceShortUrl( '/theater/' .$key . '/' . $code );
    //$item -> setAudiencePaidStatus( 2 );
    
    $item -> save(); 
    
    $solr = new SolrManager_PageWidget(null, null, $this -> context);
    $solr -> execute("add","audience",$item -> Audience -> getAudienceId());
      
    //Cross reference the item in the payment
    $this -> order -> crud -> setFkAudienceId( $item -> Audience -> getAudienceId() );
    $this -> order -> crud -> setPaymentTransactionId( $data["TRANSACTIONID"] );
    $this -> order -> crud -> setPaymentStatus( 2 );
    $this -> order -> crud -> save();
    //Note, this is saved to SOLR in the Purchase Widget, not here
    
    if (forTesting()) {
      //dump($item -> UserOrderProduct);
    }
    
    $this -> order -> ordertotal = str_replace("$","",$screen["film_ticket_price"]);
    $this -> order -> orderskus[] = $key;
    $this -> order -> orderitems[] = $item;
    
  }
  
  //Note, most of this stuff is already in the database
  //So we just add some transactional status to it
  public function postHostItem( $data=null, $obj=null, $key=null ) {
    
    //Hydrate the prior Screening into a Propel Object
    $screening = new ScreeningCrud( $this -> order -> context );
    $vars = array("screening_id"=>$obj["screening_id"]);
    $screening->checkUnique( $vars );
    $screening->setScreeningPaidStatus( 2 );
    $screening -> save();
    
    $solr = new SolrManager_PageWidget(null, null, $this -> context);
    $solr -> execute("add","screening",$screening -> Screening -> getScreeningId());
      
    //Cross reference the item in the payment
    //We picked up the payment in "postOrder" above
    $this -> order -> crud -> setFkScreeningId( $screening -> Screening -> getScreeningId() );
    $this -> order -> crud -> setFkScreeningName( $screening -> Screening -> getScreeningName() );
    $this -> order -> crud -> save();
    
    $user = getUserById( $obj["fk_host_id"] );
    
    //Create a seat for the host:
    //Add the item to the database
    $item = new AudienceCrud( $this -> order -> context );
    $vars = array("fk_user_id"=> $obj["fk_host_id"],"fk_payment_id"=>$this -> order -> crud -> Payment -> getPaymentId(),"fk_screening_unique_key"=>$obj["screening_unique_key"]);
    $item -> checkUnique( $vars );
    $item -> setFkScreeningUniqueKey( $obj["screening_unique_key"] );
    $item -> setFkScreeningId( $obj["screening_id"] );
    $item -> setFkUserId(  $obj["fk_host_id"] );
    $item -> setAudienceTicketPrice( 0 );
    $item -> setFkPaymentId( $this -> order -> crud -> Payment -> getPaymentId() );
    $item -> setAudiencePaidStatus( 2 );
    $item -> setAudienceIpAddr( REMOTE_ADDR() );
    $item -> setAudienceCreatedAt( now() );
    $item -> setAudienceStatus( 0 );
    if ($user -> getUserUsername() != '') {
      $item -> setAudienceUsername( $user -> getUserUsername() );
    } else {
      $item -> setAudienceUsername( $this -> sessionVar("user_username") );
    }
    $code = setUserOrderTicket();
    $item -> setAudienceInviteCode( $code );
    $item -> setAudienceShortUrl( '/theater/' .$obj["screening_unique_key"] . '/' . $code );
    //$item -> setAudiencePaidStatus( 2 );
    
    $item -> save(); 
    
    $solr = new SolrManager_PageWidget(null, null, $this -> context);
    $solr -> execute("add","audience",$item -> Audience -> getAudienceId());
      
    //Cross reference the item in the payment
    $this -> order -> crud -> setFkAudienceId( $item -> Audience -> getAudienceId() );
    $this -> order -> crud -> setPaymentTransactionId( $data["TRANSACTIONID"] );
    $this -> order -> crud -> setPaymentStatus( 2 );
    $this -> order -> crud -> save();
    //Note, this is saved to SOLR in the Purchase Widget, not here
    
    $this -> order -> ordertotal = str_replace("$","",$this -> postVar("setup_price"));
    $this -> order -> orderskus[] = $this -> getVar("op");
    $this -> order -> orderitems[] = $screening;
    
    return $item -> Audience;
    
  }
  
  //Note, most of this stuff is already in the database
  //So we just add some transactional status to it
  public function postSubscriptionItem($data=null, $obj=null, $key=null) {
    
    //Hydrate the prior Subscription into a Propel Object
    $subscription = new SubscriptionCrud( $this -> context );
    $vars = array("subscription_id"=>$obj["subscription_id"]);
    $subscription->checkUnique( $vars );
    $subscription -> setFkPaymentstatus( $this -> order -> crud -> Payment -> getPaymentStatus() );
    $subscription -> save();
    
    //Cross reference the item in the payment
    $this -> order -> crud -> setFkSubscriptionId( $subscription -> Subscription -> getSubscriptionId() );
    $this -> order -> crud -> setPaymentTransactionId( $data["TRANSACTIONID"] );
    $this -> order -> crud -> save();
    //Note, this is saved to SOLR in the Purchase Widget, not here
    
    if (forTesting()) {
      //dump($item -> UserOrderProduct);
    }
    
    $this -> order -> ordertotal = $total;
    $this -> order -> orderskus[] = $subscription -> getSubscriptionId();
    $this -> order -> orderitems[] = $subscription;
  }
  
  
  //This updates the items in the Database, "complete..."
  public function confirmItem( $obj=null, $type="screening", $ticket=null) {
    switch ($type){
      case "screening":
        $this -> confirmScreeningItem($obj);
        break;
      case "host":
        $this -> confirmHostItem($obj, $ticket);
        break;
      case "subscription":
        $this -> confirmSubscriptionItem($obj);
        break;
    }
  }
  
  //If the order went through, send out the email message
  public function confirmScreeningItem( $obj ) {
    
    $this -> order -> sendOrderNotification( $obj );
        
  }
  
  //If the order went through, send out the email message
  public function confirmHostItem( $obj, $ticket ) {
    
    $this -> order -> sendHostNotification( $obj, $ticket );
    
  }
  
  //If the order went through, set the screening as "paid"
  public function confirmSubscriptionItem( $obj ) {
    
    $item = SubscriptionPeer::retrieveByPK( $obj["subscription_id"] );
    if ($item -> getSubscriptionType() == "gift") {
      $startdate = now();
    } elseif ($item -> getSubscriptionType() == "subscription") {
      $startdate = $this -> postVar("fld-subscription_date");
    }
    
    for ($i=0;$i<$item -> getSubscriptionPeriod();$i++) {
      for ($j=0;$j<$item -> getSubscriptionTicketNumber();$j++) {
        $ticket = new TicketCrud($this -> order -> context);
        $ticket->setTicketPaidStatus( $this -> order -> crud -> Payment -> getPaymentStatus() );
        $ticket->setFkUserId( $user );
        $ticket->setFkPaymentId( $this -> order -> crud -> Payment -> getPaymentId() );
        $ticket->setTicketInviteCode( setUserOrderTicket() );
        $ticket->setTicketIpAddr( REMOTE_ADDR() );
        $ticket->setTicketStartDate( $startdate );
        $ticket->setTicketCreatedAt( now() );
        $ticket->setTicketUpdatedAt( null );
        $ticket->setTicketStatus( 0 );
        $ticket->setTicketUsername( $this -> sessionVar("user_username") );
        $ticket->setTicketTicketPrice($item -> getSubscriptionTicketPrice() );
        $ticket->save();
      }
      if ($item -> getSubscriptionType() == "subscription") {
        switch ($item -> getSubscriptionTerm()) {
          case "weekly":
            $startdate = dateAddExt($startdate,7,'d');
            break;
          case "bi-weekly":
            $startdate = dateAddExt($startdate,14,'d');
            break;
          case "monthly":
            $startdate = dateAddExt($startdate,1,'m');
            break;
          case "yearly":
            $startdate = dateAddExt($startdate,1,'y');
            break;
        }
      }
    }
    
    //Commented out, but valid if needed. Redundant from postSubscriptionItem() above.
    //$obj[0] -> setFkPaymentstatus( $order -> crud -> Payment -> getPaymentStatus() );
    //$obj[0] -> save();
    
  }
}
?>
