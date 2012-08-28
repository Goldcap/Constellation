<?php
       
   class UserPaymentsCrudBase extends Utils_PageWidget { 
   
    var $UserPayments;
   
       var $total;
   var $user_id;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> get();
    }
    
    if (($id) && ($id != 0)) {
      $this ->UserPayments = UserPaymentsPeer::retrieveByPK( $id );
    } else {
      $this ->UserPayments = new UserPayments;
    }
  }
  
  function hydrate( $id ) {
      $this ->UserPayments = UserPaymentsPeer::retrieveByPK( $id );
  }
  
  function getTotal() {
    if (($this ->postVar("total")) || ($this ->postVar("total") === "")) {
      return $this ->postVar("total");
    } elseif (($this ->getVar("total")) || ($this ->getVar("total") === "")) {
      return $this ->getVar("total");
    } elseif (($this ->UserPayments) || ($this ->UserPayments === "")){
      return $this ->UserPayments -> getTotal();
    } elseif (($this ->sessionVar("total")) || ($this ->sessionVar("total") == "")) {
      return $this ->sessionVar("total");
    } else {
      return false;
    }
  }
  
  function setTotal( $str ) {
    $this ->UserPayments -> setTotal( $str );
  }
  
  function getUserId() {
    if (($this ->postVar("user_id")) || ($this ->postVar("user_id") === "")) {
      return $this ->postVar("user_id");
    } elseif (($this ->getVar("user_id")) || ($this ->getVar("user_id") === "")) {
      return $this ->getVar("user_id");
    } elseif (($this ->UserPayments) || ($this ->UserPayments === "")){
      return $this ->UserPayments -> getUserId();
    } elseif (($this ->sessionVar("user_id")) || ($this ->sessionVar("user_id") == "")) {
      return $this ->sessionVar("user_id");
    } else {
      return false;
    }
  }
  
  function setUserId( $str ) {
    $this ->UserPayments -> setUserId( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->UserPayments = UserPaymentsPeer::retrieveByPK( $id );
    }
    
    if ($this ->UserPayments ) {
       
    	       (WTVRcleanString($this ->UserPayments->getTotal())) ? $itemarray["total"] = WTVRcleanString($this ->UserPayments->getTotal()) : null;
          (is_numeric(WTVRcleanString($this ->UserPayments->getUserId()))) ? $itemarray["user_id"] = WTVRcleanString($this ->UserPayments->getUserId()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->UserPayments = UserPaymentsPeer::retrieveByPK( $id );
    } elseif (! $this ->UserPayments) {
      $this ->UserPayments = new UserPayments;
    }
        
  	 ($this -> getTotal())? $this ->UserPayments->setTotal( WTVRcleanString( $this -> getTotal()) ) : null;
    ($this -> getUserId())? $this ->UserPayments->setUserId( WTVRcleanString( $this -> getUserId()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->UserPayments ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->UserPayments = UserPaymentsPeer::retrieveByPK($id);
    }
    
    if (! $this ->UserPayments ) {
      return;
    }
    
    $this ->UserPayments -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('UserPayments_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "UserPaymentsPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $UserPayments = UserPaymentsPeer::doSelect($c);
    
    if (count($UserPayments) >= 1) {
      $this ->UserPayments = $UserPayments[0];
      return true;
    } else {
      $this ->UserPayments = new UserPayments();
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
      $name = "UserPaymentsPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $UserPayments = UserPaymentsPeer::doSelect($c);
    
    if (count($UserPayments) >= 1) {
      $this ->UserPayments = $UserPayments[0];
      return true;
    } else {
      $this ->UserPayments = new UserPayments();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>