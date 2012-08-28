<?php
       
   class PaypalTransactionCrudBase extends Utils_PageWidget { 
   
    var $PaypalTransaction;
   
       var $paypal_transaction_id;
   var $paypal_transaction_guid;
   var $paypal_transaction_amount;
   var $paypal_transaction_fee;
   var $paypal_transaction_net;
   var $paypal_transaction_email;
   var $paypal_transaction_name;
   var $paypal_transaction_date;
   var $paypal_transaction_timestamp;
   var $paypal_transaction_type;
   var $paypal_transaction_status;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getPaypalTransactionId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->PaypalTransaction = PaypalTransactionPeer::retrieveByPK( $id );
    } else {
      $this ->PaypalTransaction = new PaypalTransaction;
    }
  }
  
  function hydrate( $id ) {
      $this ->PaypalTransaction = PaypalTransactionPeer::retrieveByPK( $id );
  }
  
  function getPaypalTransactionId() {
    if (($this ->postVar("paypal_transaction_id")) || ($this ->postVar("paypal_transaction_id") === "")) {
      return $this ->postVar("paypal_transaction_id");
    } elseif (($this ->getVar("paypal_transaction_id")) || ($this ->getVar("paypal_transaction_id") === "")) {
      return $this ->getVar("paypal_transaction_id");
    } elseif (($this ->PaypalTransaction) || ($this ->PaypalTransaction === "")){
      return $this ->PaypalTransaction -> getPaypalTransactionId();
    } elseif (($this ->sessionVar("paypal_transaction_id")) || ($this ->sessionVar("paypal_transaction_id") == "")) {
      return $this ->sessionVar("paypal_transaction_id");
    } else {
      return false;
    }
  }
  
  function setPaypalTransactionId( $str ) {
    $this ->PaypalTransaction -> setPaypalTransactionId( $str );
  }
  
  function getPaypalTransactionGuid() {
    if (($this ->postVar("paypal_transaction_guid")) || ($this ->postVar("paypal_transaction_guid") === "")) {
      return $this ->postVar("paypal_transaction_guid");
    } elseif (($this ->getVar("paypal_transaction_guid")) || ($this ->getVar("paypal_transaction_guid") === "")) {
      return $this ->getVar("paypal_transaction_guid");
    } elseif (($this ->PaypalTransaction) || ($this ->PaypalTransaction === "")){
      return $this ->PaypalTransaction -> getPaypalTransactionGuid();
    } elseif (($this ->sessionVar("paypal_transaction_guid")) || ($this ->sessionVar("paypal_transaction_guid") == "")) {
      return $this ->sessionVar("paypal_transaction_guid");
    } else {
      return false;
    }
  }
  
  function setPaypalTransactionGuid( $str ) {
    $this ->PaypalTransaction -> setPaypalTransactionGuid( $str );
  }
  
  function getPaypalTransactionAmount() {
    if (($this ->postVar("paypal_transaction_amount")) || ($this ->postVar("paypal_transaction_amount") === "")) {
      return $this ->postVar("paypal_transaction_amount");
    } elseif (($this ->getVar("paypal_transaction_amount")) || ($this ->getVar("paypal_transaction_amount") === "")) {
      return $this ->getVar("paypal_transaction_amount");
    } elseif (($this ->PaypalTransaction) || ($this ->PaypalTransaction === "")){
      return $this ->PaypalTransaction -> getPaypalTransactionAmount();
    } elseif (($this ->sessionVar("paypal_transaction_amount")) || ($this ->sessionVar("paypal_transaction_amount") == "")) {
      return $this ->sessionVar("paypal_transaction_amount");
    } else {
      return false;
    }
  }
  
  function setPaypalTransactionAmount( $str ) {
    $this ->PaypalTransaction -> setPaypalTransactionAmount( $str );
  }
  
  function getPaypalTransactionFee() {
    if (($this ->postVar("paypal_transaction_fee")) || ($this ->postVar("paypal_transaction_fee") === "")) {
      return $this ->postVar("paypal_transaction_fee");
    } elseif (($this ->getVar("paypal_transaction_fee")) || ($this ->getVar("paypal_transaction_fee") === "")) {
      return $this ->getVar("paypal_transaction_fee");
    } elseif (($this ->PaypalTransaction) || ($this ->PaypalTransaction === "")){
      return $this ->PaypalTransaction -> getPaypalTransactionFee();
    } elseif (($this ->sessionVar("paypal_transaction_fee")) || ($this ->sessionVar("paypal_transaction_fee") == "")) {
      return $this ->sessionVar("paypal_transaction_fee");
    } else {
      return false;
    }
  }
  
  function setPaypalTransactionFee( $str ) {
    $this ->PaypalTransaction -> setPaypalTransactionFee( $str );
  }
  
  function getPaypalTransactionNet() {
    if (($this ->postVar("paypal_transaction_net")) || ($this ->postVar("paypal_transaction_net") === "")) {
      return $this ->postVar("paypal_transaction_net");
    } elseif (($this ->getVar("paypal_transaction_net")) || ($this ->getVar("paypal_transaction_net") === "")) {
      return $this ->getVar("paypal_transaction_net");
    } elseif (($this ->PaypalTransaction) || ($this ->PaypalTransaction === "")){
      return $this ->PaypalTransaction -> getPaypalTransactionNet();
    } elseif (($this ->sessionVar("paypal_transaction_net")) || ($this ->sessionVar("paypal_transaction_net") == "")) {
      return $this ->sessionVar("paypal_transaction_net");
    } else {
      return false;
    }
  }
  
  function setPaypalTransactionNet( $str ) {
    $this ->PaypalTransaction -> setPaypalTransactionNet( $str );
  }
  
  function getPaypalTransactionEmail() {
    if (($this ->postVar("paypal_transaction_email")) || ($this ->postVar("paypal_transaction_email") === "")) {
      return $this ->postVar("paypal_transaction_email");
    } elseif (($this ->getVar("paypal_transaction_email")) || ($this ->getVar("paypal_transaction_email") === "")) {
      return $this ->getVar("paypal_transaction_email");
    } elseif (($this ->PaypalTransaction) || ($this ->PaypalTransaction === "")){
      return $this ->PaypalTransaction -> getPaypalTransactionEmail();
    } elseif (($this ->sessionVar("paypal_transaction_email")) || ($this ->sessionVar("paypal_transaction_email") == "")) {
      return $this ->sessionVar("paypal_transaction_email");
    } else {
      return false;
    }
  }
  
  function setPaypalTransactionEmail( $str ) {
    $this ->PaypalTransaction -> setPaypalTransactionEmail( $str );
  }
  
  function getPaypalTransactionName() {
    if (($this ->postVar("paypal_transaction_name")) || ($this ->postVar("paypal_transaction_name") === "")) {
      return $this ->postVar("paypal_transaction_name");
    } elseif (($this ->getVar("paypal_transaction_name")) || ($this ->getVar("paypal_transaction_name") === "")) {
      return $this ->getVar("paypal_transaction_name");
    } elseif (($this ->PaypalTransaction) || ($this ->PaypalTransaction === "")){
      return $this ->PaypalTransaction -> getPaypalTransactionName();
    } elseif (($this ->sessionVar("paypal_transaction_name")) || ($this ->sessionVar("paypal_transaction_name") == "")) {
      return $this ->sessionVar("paypal_transaction_name");
    } else {
      return false;
    }
  }
  
  function setPaypalTransactionName( $str ) {
    $this ->PaypalTransaction -> setPaypalTransactionName( $str );
  }
  
  function getPaypalTransactionDate() {
    if (($this ->postVar("paypal_transaction_date")) || ($this ->postVar("paypal_transaction_date") === "")) {
      return $this ->postVar("paypal_transaction_date");
    } elseif (($this ->getVar("paypal_transaction_date")) || ($this ->getVar("paypal_transaction_date") === "")) {
      return $this ->getVar("paypal_transaction_date");
    } elseif (($this ->PaypalTransaction) || ($this ->PaypalTransaction === "")){
      return $this ->PaypalTransaction -> getPaypalTransactionDate();
    } elseif (($this ->sessionVar("paypal_transaction_date")) || ($this ->sessionVar("paypal_transaction_date") == "")) {
      return $this ->sessionVar("paypal_transaction_date");
    } else {
      return false;
    }
  }
  
  function setPaypalTransactionDate( $str ) {
    $this ->PaypalTransaction -> setPaypalTransactionDate( $str );
  }
  
  function getPaypalTransactionTimestamp() {
    if (($this ->postVar("paypal_transaction_timestamp")) || ($this ->postVar("paypal_transaction_timestamp") === "")) {
      return $this ->postVar("paypal_transaction_timestamp");
    } elseif (($this ->getVar("paypal_transaction_timestamp")) || ($this ->getVar("paypal_transaction_timestamp") === "")) {
      return $this ->getVar("paypal_transaction_timestamp");
    } elseif (($this ->PaypalTransaction) || ($this ->PaypalTransaction === "")){
      return $this ->PaypalTransaction -> getPaypalTransactionTimestamp();
    } elseif (($this ->sessionVar("paypal_transaction_timestamp")) || ($this ->sessionVar("paypal_transaction_timestamp") == "")) {
      return $this ->sessionVar("paypal_transaction_timestamp");
    } else {
      return false;
    }
  }
  
  function setPaypalTransactionTimestamp( $str ) {
    $this ->PaypalTransaction -> setPaypalTransactionTimestamp( $str );
  }
  
  function getPaypalTransactionType() {
    if (($this ->postVar("paypal_transaction_type")) || ($this ->postVar("paypal_transaction_type") === "")) {
      return $this ->postVar("paypal_transaction_type");
    } elseif (($this ->getVar("paypal_transaction_type")) || ($this ->getVar("paypal_transaction_type") === "")) {
      return $this ->getVar("paypal_transaction_type");
    } elseif (($this ->PaypalTransaction) || ($this ->PaypalTransaction === "")){
      return $this ->PaypalTransaction -> getPaypalTransactionType();
    } elseif (($this ->sessionVar("paypal_transaction_type")) || ($this ->sessionVar("paypal_transaction_type") == "")) {
      return $this ->sessionVar("paypal_transaction_type");
    } else {
      return false;
    }
  }
  
  function setPaypalTransactionType( $str ) {
    $this ->PaypalTransaction -> setPaypalTransactionType( $str );
  }
  
  function getPaypalTransactionStatus() {
    if (($this ->postVar("paypal_transaction_status")) || ($this ->postVar("paypal_transaction_status") === "")) {
      return $this ->postVar("paypal_transaction_status");
    } elseif (($this ->getVar("paypal_transaction_status")) || ($this ->getVar("paypal_transaction_status") === "")) {
      return $this ->getVar("paypal_transaction_status");
    } elseif (($this ->PaypalTransaction) || ($this ->PaypalTransaction === "")){
      return $this ->PaypalTransaction -> getPaypalTransactionStatus();
    } elseif (($this ->sessionVar("paypal_transaction_status")) || ($this ->sessionVar("paypal_transaction_status") == "")) {
      return $this ->sessionVar("paypal_transaction_status");
    } else {
      return false;
    }
  }
  
  function setPaypalTransactionStatus( $str ) {
    $this ->PaypalTransaction -> setPaypalTransactionStatus( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->PaypalTransaction = PaypalTransactionPeer::retrieveByPK( $id );
    }
    
    if ($this ->PaypalTransaction ) {
       
    	       (is_numeric(WTVRcleanString($this ->PaypalTransaction->getPaypalTransactionId()))) ? $itemarray["paypal_transaction_id"] = WTVRcleanString($this ->PaypalTransaction->getPaypalTransactionId()) : null;
          (WTVRcleanString($this ->PaypalTransaction->getPaypalTransactionGuid())) ? $itemarray["paypal_transaction_guid"] = WTVRcleanString($this ->PaypalTransaction->getPaypalTransactionGuid()) : null;
          (WTVRcleanString($this ->PaypalTransaction->getPaypalTransactionAmount())) ? $itemarray["paypal_transaction_amount"] = WTVRcleanString($this ->PaypalTransaction->getPaypalTransactionAmount()) : null;
          (WTVRcleanString($this ->PaypalTransaction->getPaypalTransactionFee())) ? $itemarray["paypal_transaction_fee"] = WTVRcleanString($this ->PaypalTransaction->getPaypalTransactionFee()) : null;
          (WTVRcleanString($this ->PaypalTransaction->getPaypalTransactionNet())) ? $itemarray["paypal_transaction_net"] = WTVRcleanString($this ->PaypalTransaction->getPaypalTransactionNet()) : null;
          (WTVRcleanString($this ->PaypalTransaction->getPaypalTransactionEmail())) ? $itemarray["paypal_transaction_email"] = WTVRcleanString($this ->PaypalTransaction->getPaypalTransactionEmail()) : null;
          (WTVRcleanString($this ->PaypalTransaction->getPaypalTransactionName())) ? $itemarray["paypal_transaction_name"] = WTVRcleanString($this ->PaypalTransaction->getPaypalTransactionName()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->PaypalTransaction->getPaypalTransactionDate())) ? $itemarray["paypal_transaction_date"] = formatDate($this ->PaypalTransaction->getPaypalTransactionDate('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->PaypalTransaction->getPaypalTransactionTimestamp())) ? $itemarray["paypal_transaction_timestamp"] = WTVRcleanString($this ->PaypalTransaction->getPaypalTransactionTimestamp()) : null;
          (WTVRcleanString($this ->PaypalTransaction->getPaypalTransactionType())) ? $itemarray["paypal_transaction_type"] = WTVRcleanString($this ->PaypalTransaction->getPaypalTransactionType()) : null;
          (WTVRcleanString($this ->PaypalTransaction->getPaypalTransactionStatus())) ? $itemarray["paypal_transaction_status"] = WTVRcleanString($this ->PaypalTransaction->getPaypalTransactionStatus()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->PaypalTransaction = PaypalTransactionPeer::retrieveByPK( $id );
    } elseif (! $this ->PaypalTransaction) {
      $this ->PaypalTransaction = new PaypalTransaction;
    }
        
  	 ($this -> getPaypalTransactionId())? $this ->PaypalTransaction->setPaypalTransactionId( WTVRcleanString( $this -> getPaypalTransactionId()) ) : null;
    ($this -> getPaypalTransactionGuid())? $this ->PaypalTransaction->setPaypalTransactionGuid( WTVRcleanString( $this -> getPaypalTransactionGuid()) ) : null;
    ($this -> getPaypalTransactionAmount())? $this ->PaypalTransaction->setPaypalTransactionAmount( WTVRcleanString( $this -> getPaypalTransactionAmount()) ) : null;
    ($this -> getPaypalTransactionFee())? $this ->PaypalTransaction->setPaypalTransactionFee( WTVRcleanString( $this -> getPaypalTransactionFee()) ) : null;
    ($this -> getPaypalTransactionNet())? $this ->PaypalTransaction->setPaypalTransactionNet( WTVRcleanString( $this -> getPaypalTransactionNet()) ) : null;
    ($this -> getPaypalTransactionEmail())? $this ->PaypalTransaction->setPaypalTransactionEmail( WTVRcleanString( $this -> getPaypalTransactionEmail()) ) : null;
    ($this -> getPaypalTransactionName())? $this ->PaypalTransaction->setPaypalTransactionName( WTVRcleanString( $this -> getPaypalTransactionName()) ) : null;
          if (is_valid_date( $this ->PaypalTransaction->getPaypalTransactionDate())) {
        $this ->PaypalTransaction->setPaypalTransactionDate( formatDate($this -> getPaypalTransactionDate(), "TS" ));
      } else {
      $PaypalTransactionpaypal_transaction_date = $this -> sfDateTime( "paypal_transaction_date" );
      ( $PaypalTransactionpaypal_transaction_date != "01/01/1900 00:00:00" )? $this ->PaypalTransaction->setPaypalTransactionDate( formatDate($PaypalTransactionpaypal_transaction_date, "TS" )) : $this ->PaypalTransaction->setPaypalTransactionDate( null );
      }
    ($this -> getPaypalTransactionTimestamp())? $this ->PaypalTransaction->setPaypalTransactionTimestamp( WTVRcleanString( $this -> getPaypalTransactionTimestamp()) ) : null;
    ($this -> getPaypalTransactionType())? $this ->PaypalTransaction->setPaypalTransactionType( WTVRcleanString( $this -> getPaypalTransactionType()) ) : null;
    ($this -> getPaypalTransactionStatus())? $this ->PaypalTransaction->setPaypalTransactionStatus( WTVRcleanString( $this -> getPaypalTransactionStatus()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->PaypalTransaction ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->PaypalTransaction = PaypalTransactionPeer::retrieveByPK($id);
    }
    
    if (! $this ->PaypalTransaction ) {
      return;
    }
    
    $this ->PaypalTransaction -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('PaypalTransaction_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "PaypalTransactionPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $PaypalTransaction = PaypalTransactionPeer::doSelect($c);
    
    if (count($PaypalTransaction) >= 1) {
      $this ->PaypalTransaction = $PaypalTransaction[0];
      return true;
    } else {
      $this ->PaypalTransaction = new PaypalTransaction();
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
      $name = "PaypalTransactionPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $PaypalTransaction = PaypalTransactionPeer::doSelect($c);
    
    if (count($PaypalTransaction) >= 1) {
      $this ->PaypalTransaction = $PaypalTransaction[0];
      return true;
    } else {
      $this ->PaypalTransaction = new PaypalTransaction();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>