<?php
       
   class CreditCrudBase extends Utils_PageWidget { 
   
    var $Credit;
   
       var $credit_id;
   var $fk_host_id;
   var $fk_screening_id;
   var $credit_audience_no;
   var $credit_amount;
   var $credit_paid_status;
   var $credit_created_at;
   var $credit_updated_at;
   var $credit_to_cc;
   var $credit_to_paypal;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getCreditId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Credit = CreditPeer::retrieveByPK( $id );
    } else {
      $this ->Credit = new Credit;
    }
  }
  
  function hydrate( $id ) {
      $this ->Credit = CreditPeer::retrieveByPK( $id );
  }
  
  function getCreditId() {
    if (($this ->postVar("credit_id")) || ($this ->postVar("credit_id") === "")) {
      return $this ->postVar("credit_id");
    } elseif (($this ->getVar("credit_id")) || ($this ->getVar("credit_id") === "")) {
      return $this ->getVar("credit_id");
    } elseif (($this ->Credit) || ($this ->Credit === "")){
      return $this ->Credit -> getCreditId();
    } elseif (($this ->sessionVar("credit_id")) || ($this ->sessionVar("credit_id") == "")) {
      return $this ->sessionVar("credit_id");
    } else {
      return false;
    }
  }
  
  function setCreditId( $str ) {
    $this ->Credit -> setCreditId( $str );
  }
  
  function getFkHostId() {
    if (($this ->postVar("fk_host_id")) || ($this ->postVar("fk_host_id") === "")) {
      return $this ->postVar("fk_host_id");
    } elseif (($this ->getVar("fk_host_id")) || ($this ->getVar("fk_host_id") === "")) {
      return $this ->getVar("fk_host_id");
    } elseif (($this ->Credit) || ($this ->Credit === "")){
      return $this ->Credit -> getFkHostId();
    } elseif (($this ->sessionVar("fk_host_id")) || ($this ->sessionVar("fk_host_id") == "")) {
      return $this ->sessionVar("fk_host_id");
    } else {
      return false;
    }
  }
  
  function setFkHostId( $str ) {
    $this ->Credit -> setFkHostId( $str );
  }
  
  function getFkScreeningId() {
    if (($this ->postVar("fk_screening_id")) || ($this ->postVar("fk_screening_id") === "")) {
      return $this ->postVar("fk_screening_id");
    } elseif (($this ->getVar("fk_screening_id")) || ($this ->getVar("fk_screening_id") === "")) {
      return $this ->getVar("fk_screening_id");
    } elseif (($this ->Credit) || ($this ->Credit === "")){
      return $this ->Credit -> getFkScreeningId();
    } elseif (($this ->sessionVar("fk_screening_id")) || ($this ->sessionVar("fk_screening_id") == "")) {
      return $this ->sessionVar("fk_screening_id");
    } else {
      return false;
    }
  }
  
  function setFkScreeningId( $str ) {
    $this ->Credit -> setFkScreeningId( $str );
  }
  
  function getCreditAudienceNo() {
    if (($this ->postVar("credit_audience_no")) || ($this ->postVar("credit_audience_no") === "")) {
      return $this ->postVar("credit_audience_no");
    } elseif (($this ->getVar("credit_audience_no")) || ($this ->getVar("credit_audience_no") === "")) {
      return $this ->getVar("credit_audience_no");
    } elseif (($this ->Credit) || ($this ->Credit === "")){
      return $this ->Credit -> getCreditAudienceNo();
    } elseif (($this ->sessionVar("credit_audience_no")) || ($this ->sessionVar("credit_audience_no") == "")) {
      return $this ->sessionVar("credit_audience_no");
    } else {
      return false;
    }
  }
  
  function setCreditAudienceNo( $str ) {
    $this ->Credit -> setCreditAudienceNo( $str );
  }
  
  function getCreditAmount() {
    if (($this ->postVar("credit_amount")) || ($this ->postVar("credit_amount") === "")) {
      return $this ->postVar("credit_amount");
    } elseif (($this ->getVar("credit_amount")) || ($this ->getVar("credit_amount") === "")) {
      return $this ->getVar("credit_amount");
    } elseif (($this ->Credit) || ($this ->Credit === "")){
      return $this ->Credit -> getCreditAmount();
    } elseif (($this ->sessionVar("credit_amount")) || ($this ->sessionVar("credit_amount") == "")) {
      return $this ->sessionVar("credit_amount");
    } else {
      return false;
    }
  }
  
  function setCreditAmount( $str ) {
    $this ->Credit -> setCreditAmount( $str );
  }
  
  function getCreditPaidStatus() {
    if (($this ->postVar("credit_paid_status")) || ($this ->postVar("credit_paid_status") === "")) {
      return $this ->postVar("credit_paid_status");
    } elseif (($this ->getVar("credit_paid_status")) || ($this ->getVar("credit_paid_status") === "")) {
      return $this ->getVar("credit_paid_status");
    } elseif (($this ->Credit) || ($this ->Credit === "")){
      return $this ->Credit -> getCreditPaidStatus();
    } elseif (($this ->sessionVar("credit_paid_status")) || ($this ->sessionVar("credit_paid_status") == "")) {
      return $this ->sessionVar("credit_paid_status");
    } else {
      return false;
    }
  }
  
  function setCreditPaidStatus( $str ) {
    $this ->Credit -> setCreditPaidStatus( $str );
  }
  
  function getCreditCreatedAt() {
    if (($this ->postVar("credit_created_at")) || ($this ->postVar("credit_created_at") === "")) {
      return $this ->postVar("credit_created_at");
    } elseif (($this ->getVar("credit_created_at")) || ($this ->getVar("credit_created_at") === "")) {
      return $this ->getVar("credit_created_at");
    } elseif (($this ->Credit) || ($this ->Credit === "")){
      return $this ->Credit -> getCreditCreatedAt();
    } elseif (($this ->sessionVar("credit_created_at")) || ($this ->sessionVar("credit_created_at") == "")) {
      return $this ->sessionVar("credit_created_at");
    } else {
      return false;
    }
  }
  
  function setCreditCreatedAt( $str ) {
    $this ->Credit -> setCreditCreatedAt( $str );
  }
  
  function getCreditUpdatedAt() {
    if (($this ->postVar("credit_updated_at")) || ($this ->postVar("credit_updated_at") === "")) {
      return $this ->postVar("credit_updated_at");
    } elseif (($this ->getVar("credit_updated_at")) || ($this ->getVar("credit_updated_at") === "")) {
      return $this ->getVar("credit_updated_at");
    } elseif (($this ->Credit) || ($this ->Credit === "")){
      return $this ->Credit -> getCreditUpdatedAt();
    } elseif (($this ->sessionVar("credit_updated_at")) || ($this ->sessionVar("credit_updated_at") == "")) {
      return $this ->sessionVar("credit_updated_at");
    } else {
      return false;
    }
  }
  
  function setCreditUpdatedAt( $str ) {
    $this ->Credit -> setCreditUpdatedAt( $str );
  }
  
  function getCreditToCc() {
    if (($this ->postVar("credit_to_cc")) || ($this ->postVar("credit_to_cc") === "")) {
      return $this ->postVar("credit_to_cc");
    } elseif (($this ->getVar("credit_to_cc")) || ($this ->getVar("credit_to_cc") === "")) {
      return $this ->getVar("credit_to_cc");
    } elseif (($this ->Credit) || ($this ->Credit === "")){
      return $this ->Credit -> getCreditToCc();
    } elseif (($this ->sessionVar("credit_to_cc")) || ($this ->sessionVar("credit_to_cc") == "")) {
      return $this ->sessionVar("credit_to_cc");
    } else {
      return false;
    }
  }
  
  function setCreditToCc( $str ) {
    $this ->Credit -> setCreditToCc( $str );
  }
  
  function getCreditToPaypal() {
    if (($this ->postVar("credit_to_paypal")) || ($this ->postVar("credit_to_paypal") === "")) {
      return $this ->postVar("credit_to_paypal");
    } elseif (($this ->getVar("credit_to_paypal")) || ($this ->getVar("credit_to_paypal") === "")) {
      return $this ->getVar("credit_to_paypal");
    } elseif (($this ->Credit) || ($this ->Credit === "")){
      return $this ->Credit -> getCreditToPaypal();
    } elseif (($this ->sessionVar("credit_to_paypal")) || ($this ->sessionVar("credit_to_paypal") == "")) {
      return $this ->sessionVar("credit_to_paypal");
    } else {
      return false;
    }
  }
  
  function setCreditToPaypal( $str ) {
    $this ->Credit -> setCreditToPaypal( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Credit = CreditPeer::retrieveByPK( $id );
    }
    
    if ($this ->Credit ) {
       
    	       (is_numeric(WTVRcleanString($this ->Credit->getCreditId()))) ? $itemarray["credit_id"] = WTVRcleanString($this ->Credit->getCreditId()) : null;
          (is_numeric(WTVRcleanString($this ->Credit->getFkHostId()))) ? $itemarray["fk_host_id"] = WTVRcleanString($this ->Credit->getFkHostId()) : null;
          (is_numeric(WTVRcleanString($this ->Credit->getFkScreeningId()))) ? $itemarray["fk_screening_id"] = WTVRcleanString($this ->Credit->getFkScreeningId()) : null;
          (is_numeric(WTVRcleanString($this ->Credit->getCreditAudienceNo()))) ? $itemarray["credit_audience_no"] = WTVRcleanString($this ->Credit->getCreditAudienceNo()) : null;
          (WTVRcleanString($this ->Credit->getCreditAmount())) ? $itemarray["credit_amount"] = WTVRcleanString($this ->Credit->getCreditAmount()) : null;
          (WTVRcleanString($this ->Credit->getCreditPaidStatus())) ? $itemarray["credit_paid_status"] = WTVRcleanString($this ->Credit->getCreditPaidStatus()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Credit->getCreditCreatedAt())) ? $itemarray["credit_created_at"] = formatDate($this ->Credit->getCreditCreatedAt('%Y-%m-%d %T'),"TS") : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Credit->getCreditUpdatedAt())) ? $itemarray["credit_updated_at"] = formatDate($this ->Credit->getCreditUpdatedAt('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->Credit->getCreditToCc())) ? $itemarray["credit_to_cc"] = WTVRcleanString($this ->Credit->getCreditToCc()) : null;
          (WTVRcleanString($this ->Credit->getCreditToPaypal())) ? $itemarray["credit_to_paypal"] = WTVRcleanString($this ->Credit->getCreditToPaypal()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Credit = CreditPeer::retrieveByPK( $id );
    } elseif (! $this ->Credit) {
      $this ->Credit = new Credit;
    }
        
  	 ($this -> getCreditId())? $this ->Credit->setCreditId( WTVRcleanString( $this -> getCreditId()) ) : null;
    ($this -> getFkHostId())? $this ->Credit->setFkHostId( WTVRcleanString( $this -> getFkHostId()) ) : null;
    ($this -> getFkScreeningId())? $this ->Credit->setFkScreeningId( WTVRcleanString( $this -> getFkScreeningId()) ) : null;
    ($this -> getCreditAudienceNo())? $this ->Credit->setCreditAudienceNo( WTVRcleanString( $this -> getCreditAudienceNo()) ) : null;
    ($this -> getCreditAmount())? $this ->Credit->setCreditAmount( WTVRcleanString( $this -> getCreditAmount()) ) : null;
    ($this -> getCreditPaidStatus())? $this ->Credit->setCreditPaidStatus( WTVRcleanString( $this -> getCreditPaidStatus()) ) : null;
          if (is_valid_date( $this ->Credit->getCreditCreatedAt())) {
        $this ->Credit->setCreditCreatedAt( formatDate($this -> getCreditCreatedAt(), "TS" ));
      } else {
      $Creditcredit_created_at = $this -> sfDateTime( "credit_created_at" );
      ( $Creditcredit_created_at != "01/01/1900 00:00:00" )? $this ->Credit->setCreditCreatedAt( formatDate($Creditcredit_created_at, "TS" )) : $this ->Credit->setCreditCreatedAt( null );
      }
          if (is_valid_date( $this ->Credit->getCreditUpdatedAt())) {
        $this ->Credit->setCreditUpdatedAt( formatDate($this -> getCreditUpdatedAt(), "TS" ));
      } else {
      $Creditcredit_updated_at = $this -> sfDateTime( "credit_updated_at" );
      ( $Creditcredit_updated_at != "01/01/1900 00:00:00" )? $this ->Credit->setCreditUpdatedAt( formatDate($Creditcredit_updated_at, "TS" )) : $this ->Credit->setCreditUpdatedAt( null );
      }
    ($this -> getCreditToCc())? $this ->Credit->setCreditToCc( WTVRcleanString( $this -> getCreditToCc()) ) : null;
    ($this -> getCreditToPaypal())? $this ->Credit->setCreditToPaypal( WTVRcleanString( $this -> getCreditToPaypal()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Credit ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Credit = CreditPeer::retrieveByPK($id);
    }
    
    if (! $this ->Credit ) {
      return;
    }
    
    $this ->Credit -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Credit_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "CreditPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Credit = CreditPeer::doSelect($c);
    
    if (count($Credit) >= 1) {
      $this ->Credit = $Credit[0];
      return true;
    } else {
      $this ->Credit = new Credit();
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
      $name = "CreditPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Credit = CreditPeer::doSelect($c);
    
    if (count($Credit) >= 1) {
      $this ->Credit = $Credit[0];
      return true;
    } else {
      $this ->Credit = new Credit();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>