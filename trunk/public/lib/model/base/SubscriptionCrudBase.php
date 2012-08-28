<?php
       
   class SubscriptionCrudBase extends Utils_PageWidget { 
   
    var $Subscription;
   
       var $subscription_id;
   var $fk_user_id;
   var $fk_payment_id;
   var $fk_payment_status;
   var $subscription_unique_key;
   var $subscription_date_added;
   var $subscription_type;
   var $subscription_start_date;
   var $subscription_ticket_number;
   var $subscription_term;
   var $subscription_period;
   var $subscription_ticket_price;
   var $subscription_total_price;
   var $subscription_total_tickets;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getSubscriptionId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Subscription = SubscriptionPeer::retrieveByPK( $id );
    } else {
      $this ->Subscription = new Subscription;
    }
  }
  
  function hydrate( $id ) {
      $this ->Subscription = SubscriptionPeer::retrieveByPK( $id );
  }
  
  function getSubscriptionId() {
    if (($this ->postVar("subscription_id")) || ($this ->postVar("subscription_id") === "")) {
      return $this ->postVar("subscription_id");
    } elseif (($this ->getVar("subscription_id")) || ($this ->getVar("subscription_id") === "")) {
      return $this ->getVar("subscription_id");
    } elseif (($this ->Subscription) || ($this ->Subscription === "")){
      return $this ->Subscription -> getSubscriptionId();
    } elseif (($this ->sessionVar("subscription_id")) || ($this ->sessionVar("subscription_id") == "")) {
      return $this ->sessionVar("subscription_id");
    } else {
      return false;
    }
  }
  
  function setSubscriptionId( $str ) {
    $this ->Subscription -> setSubscriptionId( $str );
  }
  
  function getFkUserId() {
    if (($this ->postVar("fk_user_id")) || ($this ->postVar("fk_user_id") === "")) {
      return $this ->postVar("fk_user_id");
    } elseif (($this ->getVar("fk_user_id")) || ($this ->getVar("fk_user_id") === "")) {
      return $this ->getVar("fk_user_id");
    } elseif (($this ->Subscription) || ($this ->Subscription === "")){
      return $this ->Subscription -> getFkUserId();
    } elseif (($this ->sessionVar("fk_user_id")) || ($this ->sessionVar("fk_user_id") == "")) {
      return $this ->sessionVar("fk_user_id");
    } else {
      return false;
    }
  }
  
  function setFkUserId( $str ) {
    $this ->Subscription -> setFkUserId( $str );
  }
  
  function getFkPaymentId() {
    if (($this ->postVar("fk_payment_id")) || ($this ->postVar("fk_payment_id") === "")) {
      return $this ->postVar("fk_payment_id");
    } elseif (($this ->getVar("fk_payment_id")) || ($this ->getVar("fk_payment_id") === "")) {
      return $this ->getVar("fk_payment_id");
    } elseif (($this ->Subscription) || ($this ->Subscription === "")){
      return $this ->Subscription -> getFkPaymentId();
    } elseif (($this ->sessionVar("fk_payment_id")) || ($this ->sessionVar("fk_payment_id") == "")) {
      return $this ->sessionVar("fk_payment_id");
    } else {
      return false;
    }
  }
  
  function setFkPaymentId( $str ) {
    $this ->Subscription -> setFkPaymentId( $str );
  }
  
  function getFkPaymentStatus() {
    if (($this ->postVar("fk_payment_status")) || ($this ->postVar("fk_payment_status") === "")) {
      return $this ->postVar("fk_payment_status");
    } elseif (($this ->getVar("fk_payment_status")) || ($this ->getVar("fk_payment_status") === "")) {
      return $this ->getVar("fk_payment_status");
    } elseif (($this ->Subscription) || ($this ->Subscription === "")){
      return $this ->Subscription -> getFkPaymentStatus();
    } elseif (($this ->sessionVar("fk_payment_status")) || ($this ->sessionVar("fk_payment_status") == "")) {
      return $this ->sessionVar("fk_payment_status");
    } else {
      return false;
    }
  }
  
  function setFkPaymentStatus( $str ) {
    $this ->Subscription -> setFkPaymentStatus( $str );
  }
  
  function getSubscriptionUniqueKey() {
    if (($this ->postVar("subscription_unique_key")) || ($this ->postVar("subscription_unique_key") === "")) {
      return $this ->postVar("subscription_unique_key");
    } elseif (($this ->getVar("subscription_unique_key")) || ($this ->getVar("subscription_unique_key") === "")) {
      return $this ->getVar("subscription_unique_key");
    } elseif (($this ->Subscription) || ($this ->Subscription === "")){
      return $this ->Subscription -> getSubscriptionUniqueKey();
    } elseif (($this ->sessionVar("subscription_unique_key")) || ($this ->sessionVar("subscription_unique_key") == "")) {
      return $this ->sessionVar("subscription_unique_key");
    } else {
      return false;
    }
  }
  
  function setSubscriptionUniqueKey( $str ) {
    $this ->Subscription -> setSubscriptionUniqueKey( $str );
  }
  
  function getSubscriptionDateAdded() {
    if (($this ->postVar("subscription_date_added")) || ($this ->postVar("subscription_date_added") === "")) {
      return $this ->postVar("subscription_date_added");
    } elseif (($this ->getVar("subscription_date_added")) || ($this ->getVar("subscription_date_added") === "")) {
      return $this ->getVar("subscription_date_added");
    } elseif (($this ->Subscription) || ($this ->Subscription === "")){
      return $this ->Subscription -> getSubscriptionDateAdded();
    } elseif (($this ->sessionVar("subscription_date_added")) || ($this ->sessionVar("subscription_date_added") == "")) {
      return $this ->sessionVar("subscription_date_added");
    } else {
      return false;
    }
  }
  
  function setSubscriptionDateAdded( $str ) {
    $this ->Subscription -> setSubscriptionDateAdded( $str );
  }
  
  function getSubscriptionType() {
    if (($this ->postVar("subscription_type")) || ($this ->postVar("subscription_type") === "")) {
      return $this ->postVar("subscription_type");
    } elseif (($this ->getVar("subscription_type")) || ($this ->getVar("subscription_type") === "")) {
      return $this ->getVar("subscription_type");
    } elseif (($this ->Subscription) || ($this ->Subscription === "")){
      return $this ->Subscription -> getSubscriptionType();
    } elseif (($this ->sessionVar("subscription_type")) || ($this ->sessionVar("subscription_type") == "")) {
      return $this ->sessionVar("subscription_type");
    } else {
      return false;
    }
  }
  
  function setSubscriptionType( $str ) {
    $this ->Subscription -> setSubscriptionType( $str );
  }
  
  function getSubscriptionStartDate() {
    if (($this ->postVar("subscription_start_date")) || ($this ->postVar("subscription_start_date") === "")) {
      return $this ->postVar("subscription_start_date");
    } elseif (($this ->getVar("subscription_start_date")) || ($this ->getVar("subscription_start_date") === "")) {
      return $this ->getVar("subscription_start_date");
    } elseif (($this ->Subscription) || ($this ->Subscription === "")){
      return $this ->Subscription -> getSubscriptionStartDate();
    } elseif (($this ->sessionVar("subscription_start_date")) || ($this ->sessionVar("subscription_start_date") == "")) {
      return $this ->sessionVar("subscription_start_date");
    } else {
      return false;
    }
  }
  
  function setSubscriptionStartDate( $str ) {
    $this ->Subscription -> setSubscriptionStartDate( $str );
  }
  
  function getSubscriptionTicketNumber() {
    if (($this ->postVar("subscription_ticket_number")) || ($this ->postVar("subscription_ticket_number") === "")) {
      return $this ->postVar("subscription_ticket_number");
    } elseif (($this ->getVar("subscription_ticket_number")) || ($this ->getVar("subscription_ticket_number") === "")) {
      return $this ->getVar("subscription_ticket_number");
    } elseif (($this ->Subscription) || ($this ->Subscription === "")){
      return $this ->Subscription -> getSubscriptionTicketNumber();
    } elseif (($this ->sessionVar("subscription_ticket_number")) || ($this ->sessionVar("subscription_ticket_number") == "")) {
      return $this ->sessionVar("subscription_ticket_number");
    } else {
      return false;
    }
  }
  
  function setSubscriptionTicketNumber( $str ) {
    $this ->Subscription -> setSubscriptionTicketNumber( $str );
  }
  
  function getSubscriptionTerm() {
    if (($this ->postVar("subscription_term")) || ($this ->postVar("subscription_term") === "")) {
      return $this ->postVar("subscription_term");
    } elseif (($this ->getVar("subscription_term")) || ($this ->getVar("subscription_term") === "")) {
      return $this ->getVar("subscription_term");
    } elseif (($this ->Subscription) || ($this ->Subscription === "")){
      return $this ->Subscription -> getSubscriptionTerm();
    } elseif (($this ->sessionVar("subscription_term")) || ($this ->sessionVar("subscription_term") == "")) {
      return $this ->sessionVar("subscription_term");
    } else {
      return false;
    }
  }
  
  function setSubscriptionTerm( $str ) {
    $this ->Subscription -> setSubscriptionTerm( $str );
  }
  
  function getSubscriptionPeriod() {
    if (($this ->postVar("subscription_period")) || ($this ->postVar("subscription_period") === "")) {
      return $this ->postVar("subscription_period");
    } elseif (($this ->getVar("subscription_period")) || ($this ->getVar("subscription_period") === "")) {
      return $this ->getVar("subscription_period");
    } elseif (($this ->Subscription) || ($this ->Subscription === "")){
      return $this ->Subscription -> getSubscriptionPeriod();
    } elseif (($this ->sessionVar("subscription_period")) || ($this ->sessionVar("subscription_period") == "")) {
      return $this ->sessionVar("subscription_period");
    } else {
      return false;
    }
  }
  
  function setSubscriptionPeriod( $str ) {
    $this ->Subscription -> setSubscriptionPeriod( $str );
  }
  
  function getSubscriptionTicketPrice() {
    if (($this ->postVar("subscription_ticket_price")) || ($this ->postVar("subscription_ticket_price") === "")) {
      return $this ->postVar("subscription_ticket_price");
    } elseif (($this ->getVar("subscription_ticket_price")) || ($this ->getVar("subscription_ticket_price") === "")) {
      return $this ->getVar("subscription_ticket_price");
    } elseif (($this ->Subscription) || ($this ->Subscription === "")){
      return $this ->Subscription -> getSubscriptionTicketPrice();
    } elseif (($this ->sessionVar("subscription_ticket_price")) || ($this ->sessionVar("subscription_ticket_price") == "")) {
      return $this ->sessionVar("subscription_ticket_price");
    } else {
      return false;
    }
  }
  
  function setSubscriptionTicketPrice( $str ) {
    $this ->Subscription -> setSubscriptionTicketPrice( $str );
  }
  
  function getSubscriptionTotalPrice() {
    if (($this ->postVar("subscription_total_price")) || ($this ->postVar("subscription_total_price") === "")) {
      return $this ->postVar("subscription_total_price");
    } elseif (($this ->getVar("subscription_total_price")) || ($this ->getVar("subscription_total_price") === "")) {
      return $this ->getVar("subscription_total_price");
    } elseif (($this ->Subscription) || ($this ->Subscription === "")){
      return $this ->Subscription -> getSubscriptionTotalPrice();
    } elseif (($this ->sessionVar("subscription_total_price")) || ($this ->sessionVar("subscription_total_price") == "")) {
      return $this ->sessionVar("subscription_total_price");
    } else {
      return false;
    }
  }
  
  function setSubscriptionTotalPrice( $str ) {
    $this ->Subscription -> setSubscriptionTotalPrice( $str );
  }
  
  function getSubscriptionTotalTickets() {
    if (($this ->postVar("subscription_total_tickets")) || ($this ->postVar("subscription_total_tickets") === "")) {
      return $this ->postVar("subscription_total_tickets");
    } elseif (($this ->getVar("subscription_total_tickets")) || ($this ->getVar("subscription_total_tickets") === "")) {
      return $this ->getVar("subscription_total_tickets");
    } elseif (($this ->Subscription) || ($this ->Subscription === "")){
      return $this ->Subscription -> getSubscriptionTotalTickets();
    } elseif (($this ->sessionVar("subscription_total_tickets")) || ($this ->sessionVar("subscription_total_tickets") == "")) {
      return $this ->sessionVar("subscription_total_tickets");
    } else {
      return false;
    }
  }
  
  function setSubscriptionTotalTickets( $str ) {
    $this ->Subscription -> setSubscriptionTotalTickets( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Subscription = SubscriptionPeer::retrieveByPK( $id );
    }
    
    if ($this ->Subscription ) {
       
    	       (is_numeric(WTVRcleanString($this ->Subscription->getSubscriptionId()))) ? $itemarray["subscription_id"] = WTVRcleanString($this ->Subscription->getSubscriptionId()) : null;
          (is_numeric(WTVRcleanString($this ->Subscription->getFkUserId()))) ? $itemarray["fk_user_id"] = WTVRcleanString($this ->Subscription->getFkUserId()) : null;
          (is_numeric(WTVRcleanString($this ->Subscription->getFkPaymentId()))) ? $itemarray["fk_payment_id"] = WTVRcleanString($this ->Subscription->getFkPaymentId()) : null;
          (is_numeric(WTVRcleanString($this ->Subscription->getFkPaymentStatus()))) ? $itemarray["fk_payment_status"] = WTVRcleanString($this ->Subscription->getFkPaymentStatus()) : null;
          (WTVRcleanString($this ->Subscription->getSubscriptionUniqueKey())) ? $itemarray["subscription_unique_key"] = WTVRcleanString($this ->Subscription->getSubscriptionUniqueKey()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Subscription->getSubscriptionDateAdded())) ? $itemarray["subscription_date_added"] = formatDate($this ->Subscription->getSubscriptionDateAdded('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->Subscription->getSubscriptionType())) ? $itemarray["subscription_type"] = WTVRcleanString($this ->Subscription->getSubscriptionType()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Subscription->getSubscriptionStartDate())) ? $itemarray["subscription_start_date"] = formatDate($this ->Subscription->getSubscriptionStartDate('%Y-%m-%d %T'),"TS") : null;
          (is_numeric(WTVRcleanString($this ->Subscription->getSubscriptionTicketNumber()))) ? $itemarray["subscription_ticket_number"] = WTVRcleanString($this ->Subscription->getSubscriptionTicketNumber()) : null;
          (WTVRcleanString($this ->Subscription->getSubscriptionTerm())) ? $itemarray["subscription_term"] = WTVRcleanString($this ->Subscription->getSubscriptionTerm()) : null;
          (is_numeric(WTVRcleanString($this ->Subscription->getSubscriptionPeriod()))) ? $itemarray["subscription_period"] = WTVRcleanString($this ->Subscription->getSubscriptionPeriod()) : null;
          (is_numeric(WTVRcleanString($this ->Subscription->getSubscriptionTicketPrice()))) ? $itemarray["subscription_ticket_price"] = sprintf("%01.2f",WTVRcleanString($this ->Subscription->getSubscriptionTicketPrice())) : null;
          (is_numeric(WTVRcleanString($this ->Subscription->getSubscriptionTotalPrice()))) ? $itemarray["subscription_total_price"] = sprintf("%01.2f",WTVRcleanString($this ->Subscription->getSubscriptionTotalPrice())) : null;
          (is_numeric(WTVRcleanString($this ->Subscription->getSubscriptionTotalTickets()))) ? $itemarray["subscription_total_tickets"] = WTVRcleanString($this ->Subscription->getSubscriptionTotalTickets()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Subscription = SubscriptionPeer::retrieveByPK( $id );
    } elseif (! $this ->Subscription) {
      $this ->Subscription = new Subscription;
    }
        
  	 ($this -> getSubscriptionId())? $this ->Subscription->setSubscriptionId( WTVRcleanString( $this -> getSubscriptionId()) ) : null;
    ($this -> getFkUserId())? $this ->Subscription->setFkUserId( WTVRcleanString( $this -> getFkUserId()) ) : null;
    ($this -> getFkPaymentId())? $this ->Subscription->setFkPaymentId( WTVRcleanString( $this -> getFkPaymentId()) ) : null;
    ($this -> getFkPaymentStatus())? $this ->Subscription->setFkPaymentStatus( WTVRcleanString( $this -> getFkPaymentStatus()) ) : null;
    ($this -> getSubscriptionUniqueKey())? $this ->Subscription->setSubscriptionUniqueKey( WTVRcleanString( $this -> getSubscriptionUniqueKey()) ) : null;
          if (is_valid_date( $this ->Subscription->getSubscriptionDateAdded())) {
        $this ->Subscription->setSubscriptionDateAdded( formatDate($this -> getSubscriptionDateAdded(), "TS" ));
      } else {
      $Subscriptionsubscription_date_added = $this -> sfDateTime( "subscription_date_added" );
      ( $Subscriptionsubscription_date_added != "01/01/1900 00:00:00" )? $this ->Subscription->setSubscriptionDateAdded( formatDate($Subscriptionsubscription_date_added, "TS" )) : $this ->Subscription->setSubscriptionDateAdded( null );
      }
    ($this -> getSubscriptionType())? $this ->Subscription->setSubscriptionType( WTVRcleanString( $this -> getSubscriptionType()) ) : null;
          if (is_valid_date( $this ->Subscription->getSubscriptionStartDate())) {
        $this ->Subscription->setSubscriptionStartDate( formatDate($this -> getSubscriptionStartDate(), "TS" ));
      } else {
      $Subscriptionsubscription_start_date = $this -> sfDateTime( "subscription_start_date" );
      ( $Subscriptionsubscription_start_date != "01/01/1900 00:00:00" )? $this ->Subscription->setSubscriptionStartDate( formatDate($Subscriptionsubscription_start_date, "TS" )) : $this ->Subscription->setSubscriptionStartDate( null );
      }
    ($this -> getSubscriptionTicketNumber())? $this ->Subscription->setSubscriptionTicketNumber( WTVRcleanString( $this -> getSubscriptionTicketNumber()) ) : null;
    ($this -> getSubscriptionTerm())? $this ->Subscription->setSubscriptionTerm( WTVRcleanString( $this -> getSubscriptionTerm()) ) : null;
    ($this -> getSubscriptionPeriod())? $this ->Subscription->setSubscriptionPeriod( WTVRcleanString( $this -> getSubscriptionPeriod()) ) : null;
          (is_numeric($this ->getSubscriptionTicketPrice())) ? $this ->Subscription->setSubscriptionTicketPrice( (float) $this -> getSubscriptionTicketPrice() ) : null;
          (is_numeric($this ->getSubscriptionTotalPrice())) ? $this ->Subscription->setSubscriptionTotalPrice( (float) $this -> getSubscriptionTotalPrice() ) : null;
    ($this -> getSubscriptionTotalTickets())? $this ->Subscription->setSubscriptionTotalTickets( WTVRcleanString( $this -> getSubscriptionTotalTickets()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Subscription ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Subscription = SubscriptionPeer::retrieveByPK($id);
    }
    
    if (! $this ->Subscription ) {
      return;
    }
    
    $this ->Subscription -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Subscription_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "SubscriptionPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Subscription = SubscriptionPeer::doSelect($c);
    
    if (count($Subscription) >= 1) {
      $this ->Subscription = $Subscription[0];
      return true;
    } else {
      $this ->Subscription = new Subscription();
      return false;
    }
  }
  
    //Pass an array of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function checkUnique( $vals ) {
    $c = new Criteria();
    
    foreach ($vals as $key =>$value) {
      $name = "SubscriptionPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Subscription = SubscriptionPeer::doSelect($c);
    
    if (count($Subscription) >= 1) {
      $this ->Subscription = $Subscription[0];
      return true;
    } else {
      $this ->Subscription = new Subscription();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>