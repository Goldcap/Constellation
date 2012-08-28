<?php
       
   class TransactionCrudBase extends Utils_PageWidget { 
   
    var $Transaction;
   
       var $transaction_id;
   var $fk_payment_id;
   var $transaction_number;
   var $transaction_response_object;
   var $transaction_gateway_environment;
   var $transaction_fraud_alert;
   var $transaction_auth_code;
   var $transaction_status;
   var $transaction_created_at;
   var $transaction_updated_at;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getTransactionId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Transaction = TransactionPeer::retrieveByPK( $id );
    } else {
      $this ->Transaction = new Transaction;
    }
  }
  
  function hydrate( $id ) {
      $this ->Transaction = TransactionPeer::retrieveByPK( $id );
  }
  
  function getTransactionId() {
    if (($this ->postVar("transaction_id")) || ($this ->postVar("transaction_id") === "")) {
      return $this ->postVar("transaction_id");
    } elseif (($this ->getVar("transaction_id")) || ($this ->getVar("transaction_id") === "")) {
      return $this ->getVar("transaction_id");
    } elseif (($this ->Transaction) || ($this ->Transaction === "")){
      return $this ->Transaction -> getTransactionId();
    } elseif (($this ->sessionVar("transaction_id")) || ($this ->sessionVar("transaction_id") == "")) {
      return $this ->sessionVar("transaction_id");
    } else {
      return false;
    }
  }
  
  function setTransactionId( $str ) {
    $this ->Transaction -> setTransactionId( $str );
  }
  
  function getFkPaymentId() {
    if (($this ->postVar("fk_payment_id")) || ($this ->postVar("fk_payment_id") === "")) {
      return $this ->postVar("fk_payment_id");
    } elseif (($this ->getVar("fk_payment_id")) || ($this ->getVar("fk_payment_id") === "")) {
      return $this ->getVar("fk_payment_id");
    } elseif (($this ->Transaction) || ($this ->Transaction === "")){
      return $this ->Transaction -> getFkPaymentId();
    } elseif (($this ->sessionVar("fk_payment_id")) || ($this ->sessionVar("fk_payment_id") == "")) {
      return $this ->sessionVar("fk_payment_id");
    } else {
      return false;
    }
  }
  
  function setFkPaymentId( $str ) {
    $this ->Transaction -> setFkPaymentId( $str );
  }
  
  function getTransactionNumber() {
    if (($this ->postVar("transaction_number")) || ($this ->postVar("transaction_number") === "")) {
      return $this ->postVar("transaction_number");
    } elseif (($this ->getVar("transaction_number")) || ($this ->getVar("transaction_number") === "")) {
      return $this ->getVar("transaction_number");
    } elseif (($this ->Transaction) || ($this ->Transaction === "")){
      return $this ->Transaction -> getTransactionNumber();
    } elseif (($this ->sessionVar("transaction_number")) || ($this ->sessionVar("transaction_number") == "")) {
      return $this ->sessionVar("transaction_number");
    } else {
      return false;
    }
  }
  
  function setTransactionNumber( $str ) {
    $this ->Transaction -> setTransactionNumber( $str );
  }
  
  function getTransactionResponseObject() {
    if (($this ->postVar("transaction_response_object")) || ($this ->postVar("transaction_response_object") === "")) {
      return $this ->postVar("transaction_response_object");
    } elseif (($this ->getVar("transaction_response_object")) || ($this ->getVar("transaction_response_object") === "")) {
      return $this ->getVar("transaction_response_object");
    } elseif (($this ->Transaction) || ($this ->Transaction === "")){
      return $this ->Transaction -> getTransactionResponseObject();
    } elseif (($this ->sessionVar("transaction_response_object")) || ($this ->sessionVar("transaction_response_object") == "")) {
      return $this ->sessionVar("transaction_response_object");
    } else {
      return false;
    }
  }
  
  function setTransactionResponseObject( $str ) {
    $this ->Transaction -> setTransactionResponseObject( $str );
  }
  
  function getTransactionGatewayEnvironment() {
    if (($this ->postVar("transaction_gateway_environment")) || ($this ->postVar("transaction_gateway_environment") === "")) {
      return $this ->postVar("transaction_gateway_environment");
    } elseif (($this ->getVar("transaction_gateway_environment")) || ($this ->getVar("transaction_gateway_environment") === "")) {
      return $this ->getVar("transaction_gateway_environment");
    } elseif (($this ->Transaction) || ($this ->Transaction === "")){
      return $this ->Transaction -> getTransactionGatewayEnvironment();
    } elseif (($this ->sessionVar("transaction_gateway_environment")) || ($this ->sessionVar("transaction_gateway_environment") == "")) {
      return $this ->sessionVar("transaction_gateway_environment");
    } else {
      return false;
    }
  }
  
  function setTransactionGatewayEnvironment( $str ) {
    $this ->Transaction -> setTransactionGatewayEnvironment( $str );
  }
  
  function getTransactionFraudAlert() {
    if (($this ->postVar("transaction_fraud_alert")) || ($this ->postVar("transaction_fraud_alert") === "")) {
      return $this ->postVar("transaction_fraud_alert");
    } elseif (($this ->getVar("transaction_fraud_alert")) || ($this ->getVar("transaction_fraud_alert") === "")) {
      return $this ->getVar("transaction_fraud_alert");
    } elseif (($this ->Transaction) || ($this ->Transaction === "")){
      return $this ->Transaction -> getTransactionFraudAlert();
    } elseif (($this ->sessionVar("transaction_fraud_alert")) || ($this ->sessionVar("transaction_fraud_alert") == "")) {
      return $this ->sessionVar("transaction_fraud_alert");
    } else {
      return false;
    }
  }
  
  function setTransactionFraudAlert( $str ) {
    $this ->Transaction -> setTransactionFraudAlert( $str );
  }
  
  function getTransactionAuthCode() {
    if (($this ->postVar("transaction_auth_code")) || ($this ->postVar("transaction_auth_code") === "")) {
      return $this ->postVar("transaction_auth_code");
    } elseif (($this ->getVar("transaction_auth_code")) || ($this ->getVar("transaction_auth_code") === "")) {
      return $this ->getVar("transaction_auth_code");
    } elseif (($this ->Transaction) || ($this ->Transaction === "")){
      return $this ->Transaction -> getTransactionAuthCode();
    } elseif (($this ->sessionVar("transaction_auth_code")) || ($this ->sessionVar("transaction_auth_code") == "")) {
      return $this ->sessionVar("transaction_auth_code");
    } else {
      return false;
    }
  }
  
  function setTransactionAuthCode( $str ) {
    $this ->Transaction -> setTransactionAuthCode( $str );
  }
  
  function getTransactionStatus() {
    if (($this ->postVar("transaction_status")) || ($this ->postVar("transaction_status") === "")) {
      return $this ->postVar("transaction_status");
    } elseif (($this ->getVar("transaction_status")) || ($this ->getVar("transaction_status") === "")) {
      return $this ->getVar("transaction_status");
    } elseif (($this ->Transaction) || ($this ->Transaction === "")){
      return $this ->Transaction -> getTransactionStatus();
    } elseif (($this ->sessionVar("transaction_status")) || ($this ->sessionVar("transaction_status") == "")) {
      return $this ->sessionVar("transaction_status");
    } else {
      return false;
    }
  }
  
  function setTransactionStatus( $str ) {
    $this ->Transaction -> setTransactionStatus( $str );
  }
  
  function getTransactionCreatedAt() {
    if (($this ->postVar("transaction_created_at")) || ($this ->postVar("transaction_created_at") === "")) {
      return $this ->postVar("transaction_created_at");
    } elseif (($this ->getVar("transaction_created_at")) || ($this ->getVar("transaction_created_at") === "")) {
      return $this ->getVar("transaction_created_at");
    } elseif (($this ->Transaction) || ($this ->Transaction === "")){
      return $this ->Transaction -> getTransactionCreatedAt();
    } elseif (($this ->sessionVar("transaction_created_at")) || ($this ->sessionVar("transaction_created_at") == "")) {
      return $this ->sessionVar("transaction_created_at");
    } else {
      return false;
    }
  }
  
  function setTransactionCreatedAt( $str ) {
    $this ->Transaction -> setTransactionCreatedAt( $str );
  }
  
  function getTransactionUpdatedAt() {
    if (($this ->postVar("transaction_updated_at")) || ($this ->postVar("transaction_updated_at") === "")) {
      return $this ->postVar("transaction_updated_at");
    } elseif (($this ->getVar("transaction_updated_at")) || ($this ->getVar("transaction_updated_at") === "")) {
      return $this ->getVar("transaction_updated_at");
    } elseif (($this ->Transaction) || ($this ->Transaction === "")){
      return $this ->Transaction -> getTransactionUpdatedAt();
    } elseif (($this ->sessionVar("transaction_updated_at")) || ($this ->sessionVar("transaction_updated_at") == "")) {
      return $this ->sessionVar("transaction_updated_at");
    } else {
      return false;
    }
  }
  
  function setTransactionUpdatedAt( $str ) {
    $this ->Transaction -> setTransactionUpdatedAt( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Transaction = TransactionPeer::retrieveByPK( $id );
    }
    
    if ($this ->Transaction ) {
       
    	       (is_numeric(WTVRcleanString($this ->Transaction->getTransactionId()))) ? $itemarray["transaction_id"] = WTVRcleanString($this ->Transaction->getTransactionId()) : null;
          (is_numeric(WTVRcleanString($this ->Transaction->getFkPaymentId()))) ? $itemarray["fk_payment_id"] = WTVRcleanString($this ->Transaction->getFkPaymentId()) : null;
          (WTVRcleanString($this ->Transaction->getTransactionNumber())) ? $itemarray["transaction_number"] = WTVRcleanString($this ->Transaction->getTransactionNumber()) : null;
          (WTVRcleanString($this ->Transaction->getTransactionResponseObject())) ? $itemarray["transaction_response_object"] = WTVRcleanString($this ->Transaction->getTransactionResponseObject()) : null;
          (WTVRcleanString($this ->Transaction->getTransactionGatewayEnvironment())) ? $itemarray["transaction_gateway_environment"] = WTVRcleanString($this ->Transaction->getTransactionGatewayEnvironment()) : null;
          (WTVRcleanString($this ->Transaction->getTransactionFraudAlert())) ? $itemarray["transaction_fraud_alert"] = WTVRcleanString($this ->Transaction->getTransactionFraudAlert()) : null;
          (WTVRcleanString($this ->Transaction->getTransactionAuthCode())) ? $itemarray["transaction_auth_code"] = WTVRcleanString($this ->Transaction->getTransactionAuthCode()) : null;
          (WTVRcleanString($this ->Transaction->getTransactionStatus())) ? $itemarray["transaction_status"] = WTVRcleanString($this ->Transaction->getTransactionStatus()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Transaction->getTransactionCreatedAt())) ? $itemarray["transaction_created_at"] = formatDate($this ->Transaction->getTransactionCreatedAt('%Y-%m-%d %T'),"TS") : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Transaction->getTransactionUpdatedAt())) ? $itemarray["transaction_updated_at"] = formatDate($this ->Transaction->getTransactionUpdatedAt('%Y-%m-%d %T'),"TS") : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Transaction = TransactionPeer::retrieveByPK( $id );
    } elseif (! $this ->Transaction) {
      $this ->Transaction = new Transaction;
    }
        
  	 ($this -> getTransactionId())? $this ->Transaction->setTransactionId( WTVRcleanString( $this -> getTransactionId()) ) : null;
    ($this -> getFkPaymentId())? $this ->Transaction->setFkPaymentId( WTVRcleanString( $this -> getFkPaymentId()) ) : null;
    ($this -> getTransactionNumber())? $this ->Transaction->setTransactionNumber( WTVRcleanString( $this -> getTransactionNumber()) ) : null;
    ($this -> getTransactionResponseObject())? $this ->Transaction->setTransactionResponseObject( WTVRcleanString( $this -> getTransactionResponseObject()) ) : null;
    ($this -> getTransactionGatewayEnvironment())? $this ->Transaction->setTransactionGatewayEnvironment( WTVRcleanString( $this -> getTransactionGatewayEnvironment()) ) : null;
    ($this -> getTransactionFraudAlert())? $this ->Transaction->setTransactionFraudAlert( WTVRcleanString( $this -> getTransactionFraudAlert()) ) : null;
    ($this -> getTransactionAuthCode())? $this ->Transaction->setTransactionAuthCode( WTVRcleanString( $this -> getTransactionAuthCode()) ) : null;
    ($this -> getTransactionStatus())? $this ->Transaction->setTransactionStatus( WTVRcleanString( $this -> getTransactionStatus()) ) : null;
          if (is_valid_date( $this ->Transaction->getTransactionCreatedAt())) {
        $this ->Transaction->setTransactionCreatedAt( formatDate($this -> getTransactionCreatedAt(), "TS" ));
      } else {
      $Transactiontransaction_created_at = $this -> sfDateTime( "transaction_created_at" );
      ( $Transactiontransaction_created_at != "01/01/1900 00:00:00" )? $this ->Transaction->setTransactionCreatedAt( formatDate($Transactiontransaction_created_at, "TS" )) : $this ->Transaction->setTransactionCreatedAt( null );
      }
          if (is_valid_date( $this ->Transaction->getTransactionUpdatedAt())) {
        $this ->Transaction->setTransactionUpdatedAt( formatDate($this -> getTransactionUpdatedAt(), "TS" ));
      } else {
      $Transactiontransaction_updated_at = $this -> sfDateTime( "transaction_updated_at" );
      ( $Transactiontransaction_updated_at != "01/01/1900 00:00:00" )? $this ->Transaction->setTransactionUpdatedAt( formatDate($Transactiontransaction_updated_at, "TS" )) : $this ->Transaction->setTransactionUpdatedAt( null );
      }
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Transaction ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Transaction = TransactionPeer::retrieveByPK($id);
    }
    
    if (! $this ->Transaction ) {
      return;
    }
    
    $this ->Transaction -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Transaction_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "TransactionPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Transaction = TransactionPeer::doSelect($c);
    
    if (count($Transaction) >= 1) {
      $this ->Transaction = $Transaction[0];
      return true;
    } else {
      $this ->Transaction = new Transaction();
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
      $name = "TransactionPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Transaction = TransactionPeer::doSelect($c);
    
    if (count($Transaction) >= 1) {
      $this ->Transaction = $Transaction[0];
      return true;
    } else {
      $this ->Transaction = new Transaction();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>