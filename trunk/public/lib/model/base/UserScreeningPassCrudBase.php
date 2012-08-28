<?php
       
   class UserScreeningPassCrudBase extends Utils_PageWidget { 
   
    var $UserScreeningPass;
   
       var $user_screening_pass_id;
   var $fk_user_id;
   var $user_screening_pass_password;
   var $user_screening_pass_salt;
   var $user_screening_pass_access_key;
   var $user_screening_pass_views;
   var $user_screening_pass_created_at;
   var $user_screening_pass_updated_at;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getUserScreeningPassId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->UserScreeningPass = UserScreeningPassPeer::retrieveByPK( $id );
    } else {
      $this ->UserScreeningPass = new UserScreeningPass;
    }
  }
  
  function hydrate( $id ) {
      $this ->UserScreeningPass = UserScreeningPassPeer::retrieveByPK( $id );
  }
  
  function getUserScreeningPassId() {
    if (($this ->postVar("user_screening_pass_id")) || ($this ->postVar("user_screening_pass_id") === "")) {
      return $this ->postVar("user_screening_pass_id");
    } elseif (($this ->getVar("user_screening_pass_id")) || ($this ->getVar("user_screening_pass_id") === "")) {
      return $this ->getVar("user_screening_pass_id");
    } elseif (($this ->UserScreeningPass) || ($this ->UserScreeningPass === "")){
      return $this ->UserScreeningPass -> getUserScreeningPassId();
    } elseif (($this ->sessionVar("user_screening_pass_id")) || ($this ->sessionVar("user_screening_pass_id") == "")) {
      return $this ->sessionVar("user_screening_pass_id");
    } else {
      return false;
    }
  }
  
  function setUserScreeningPassId( $str ) {
    $this ->UserScreeningPass -> setUserScreeningPassId( $str );
  }
  
  function getFkUserId() {
    if (($this ->postVar("fk_user_id")) || ($this ->postVar("fk_user_id") === "")) {
      return $this ->postVar("fk_user_id");
    } elseif (($this ->getVar("fk_user_id")) || ($this ->getVar("fk_user_id") === "")) {
      return $this ->getVar("fk_user_id");
    } elseif (($this ->UserScreeningPass) || ($this ->UserScreeningPass === "")){
      return $this ->UserScreeningPass -> getFkUserId();
    } elseif (($this ->sessionVar("fk_user_id")) || ($this ->sessionVar("fk_user_id") == "")) {
      return $this ->sessionVar("fk_user_id");
    } else {
      return false;
    }
  }
  
  function setFkUserId( $str ) {
    $this ->UserScreeningPass -> setFkUserId( $str );
  }
  
  function getUserScreeningPassPassword() {
    if (($this ->postVar("user_screening_pass_password")) || ($this ->postVar("user_screening_pass_password") === "")) {
      return $this ->postVar("user_screening_pass_password");
    } elseif (($this ->getVar("user_screening_pass_password")) || ($this ->getVar("user_screening_pass_password") === "")) {
      return $this ->getVar("user_screening_pass_password");
    } elseif (($this ->UserScreeningPass) || ($this ->UserScreeningPass === "")){
      return $this ->UserScreeningPass -> getUserScreeningPassPassword();
    } elseif (($this ->sessionVar("user_screening_pass_password")) || ($this ->sessionVar("user_screening_pass_password") == "")) {
      return $this ->sessionVar("user_screening_pass_password");
    } else {
      return false;
    }
  }
  
  function setUserScreeningPassPassword( $str ) {
    $this ->UserScreeningPass -> setUserScreeningPassPassword( $str );
  }
  
  function getUserScreeningPassSalt() {
    if (($this ->postVar("user_screening_pass_salt")) || ($this ->postVar("user_screening_pass_salt") === "")) {
      return $this ->postVar("user_screening_pass_salt");
    } elseif (($this ->getVar("user_screening_pass_salt")) || ($this ->getVar("user_screening_pass_salt") === "")) {
      return $this ->getVar("user_screening_pass_salt");
    } elseif (($this ->UserScreeningPass) || ($this ->UserScreeningPass === "")){
      return $this ->UserScreeningPass -> getUserScreeningPassSalt();
    } elseif (($this ->sessionVar("user_screening_pass_salt")) || ($this ->sessionVar("user_screening_pass_salt") == "")) {
      return $this ->sessionVar("user_screening_pass_salt");
    } else {
      return false;
    }
  }
  
  function setUserScreeningPassSalt( $str ) {
    $this ->UserScreeningPass -> setUserScreeningPassSalt( $str );
  }
  
  function getUserScreeningPassAccessKey() {
    if (($this ->postVar("user_screening_pass_access_key")) || ($this ->postVar("user_screening_pass_access_key") === "")) {
      return $this ->postVar("user_screening_pass_access_key");
    } elseif (($this ->getVar("user_screening_pass_access_key")) || ($this ->getVar("user_screening_pass_access_key") === "")) {
      return $this ->getVar("user_screening_pass_access_key");
    } elseif (($this ->UserScreeningPass) || ($this ->UserScreeningPass === "")){
      return $this ->UserScreeningPass -> getUserScreeningPassAccessKey();
    } elseif (($this ->sessionVar("user_screening_pass_access_key")) || ($this ->sessionVar("user_screening_pass_access_key") == "")) {
      return $this ->sessionVar("user_screening_pass_access_key");
    } else {
      return false;
    }
  }
  
  function setUserScreeningPassAccessKey( $str ) {
    $this ->UserScreeningPass -> setUserScreeningPassAccessKey( $str );
  }
  
  function getUserScreeningPassViews() {
    if (($this ->postVar("user_screening_pass_views")) || ($this ->postVar("user_screening_pass_views") === "")) {
      return $this ->postVar("user_screening_pass_views");
    } elseif (($this ->getVar("user_screening_pass_views")) || ($this ->getVar("user_screening_pass_views") === "")) {
      return $this ->getVar("user_screening_pass_views");
    } elseif (($this ->UserScreeningPass) || ($this ->UserScreeningPass === "")){
      return $this ->UserScreeningPass -> getUserScreeningPassViews();
    } elseif (($this ->sessionVar("user_screening_pass_views")) || ($this ->sessionVar("user_screening_pass_views") == "")) {
      return $this ->sessionVar("user_screening_pass_views");
    } else {
      return false;
    }
  }
  
  function setUserScreeningPassViews( $str ) {
    $this ->UserScreeningPass -> setUserScreeningPassViews( $str );
  }
  
  function getUserScreeningPassCreatedAt() {
    if (($this ->postVar("user_screening_pass_created_at")) || ($this ->postVar("user_screening_pass_created_at") === "")) {
      return $this ->postVar("user_screening_pass_created_at");
    } elseif (($this ->getVar("user_screening_pass_created_at")) || ($this ->getVar("user_screening_pass_created_at") === "")) {
      return $this ->getVar("user_screening_pass_created_at");
    } elseif (($this ->UserScreeningPass) || ($this ->UserScreeningPass === "")){
      return $this ->UserScreeningPass -> getUserScreeningPassCreatedAt();
    } elseif (($this ->sessionVar("user_screening_pass_created_at")) || ($this ->sessionVar("user_screening_pass_created_at") == "")) {
      return $this ->sessionVar("user_screening_pass_created_at");
    } else {
      return false;
    }
  }
  
  function setUserScreeningPassCreatedAt( $str ) {
    $this ->UserScreeningPass -> setUserScreeningPassCreatedAt( $str );
  }
  
  function getUserScreeningPassUpdatedAt() {
    if (($this ->postVar("user_screening_pass_updated_at")) || ($this ->postVar("user_screening_pass_updated_at") === "")) {
      return $this ->postVar("user_screening_pass_updated_at");
    } elseif (($this ->getVar("user_screening_pass_updated_at")) || ($this ->getVar("user_screening_pass_updated_at") === "")) {
      return $this ->getVar("user_screening_pass_updated_at");
    } elseif (($this ->UserScreeningPass) || ($this ->UserScreeningPass === "")){
      return $this ->UserScreeningPass -> getUserScreeningPassUpdatedAt();
    } elseif (($this ->sessionVar("user_screening_pass_updated_at")) || ($this ->sessionVar("user_screening_pass_updated_at") == "")) {
      return $this ->sessionVar("user_screening_pass_updated_at");
    } else {
      return false;
    }
  }
  
  function setUserScreeningPassUpdatedAt( $str ) {
    $this ->UserScreeningPass -> setUserScreeningPassUpdatedAt( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->UserScreeningPass = UserScreeningPassPeer::retrieveByPK( $id );
    }
    
    if ($this ->UserScreeningPass ) {
       
    	       (is_numeric(WTVRcleanString($this ->UserScreeningPass->getUserScreeningPassId()))) ? $itemarray["user_screening_pass_id"] = WTVRcleanString($this ->UserScreeningPass->getUserScreeningPassId()) : null;
          (is_numeric(WTVRcleanString($this ->UserScreeningPass->getFkUserId()))) ? $itemarray["fk_user_id"] = WTVRcleanString($this ->UserScreeningPass->getFkUserId()) : null;
          (WTVRcleanString($this ->UserScreeningPass->getUserScreeningPassPassword())) ? $itemarray["user_screening_pass_password"] = WTVRcleanString($this ->UserScreeningPass->getUserScreeningPassPassword()) : null;
          (WTVRcleanString($this ->UserScreeningPass->getUserScreeningPassSalt())) ? $itemarray["user_screening_pass_salt"] = WTVRcleanString($this ->UserScreeningPass->getUserScreeningPassSalt()) : null;
          (WTVRcleanString($this ->UserScreeningPass->getUserScreeningPassAccessKey())) ? $itemarray["user_screening_pass_access_key"] = WTVRcleanString($this ->UserScreeningPass->getUserScreeningPassAccessKey()) : null;
          (is_numeric(WTVRcleanString($this ->UserScreeningPass->getUserScreeningPassViews()))) ? $itemarray["user_screening_pass_views"] = WTVRcleanString($this ->UserScreeningPass->getUserScreeningPassViews()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->UserScreeningPass->getUserScreeningPassCreatedAt())) ? $itemarray["user_screening_pass_created_at"] = formatDate($this ->UserScreeningPass->getUserScreeningPassCreatedAt('%Y-%m-%d %T'),"TS") : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->UserScreeningPass->getUserScreeningPassUpdatedAt())) ? $itemarray["user_screening_pass_updated_at"] = formatDate($this ->UserScreeningPass->getUserScreeningPassUpdatedAt('%Y-%m-%d %T'),"TS") : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->UserScreeningPass = UserScreeningPassPeer::retrieveByPK( $id );
    } elseif (! $this ->UserScreeningPass) {
      $this ->UserScreeningPass = new UserScreeningPass;
    }
        
  	 ($this -> getUserScreeningPassId())? $this ->UserScreeningPass->setUserScreeningPassId( WTVRcleanString( $this -> getUserScreeningPassId()) ) : null;
    ($this -> getFkUserId())? $this ->UserScreeningPass->setFkUserId( WTVRcleanString( $this -> getFkUserId()) ) : null;
    ($this -> getUserScreeningPassPassword())? $this ->UserScreeningPass->setUserScreeningPassPassword( WTVRcleanString( $this -> getUserScreeningPassPassword()) ) : null;
    ($this -> getUserScreeningPassSalt())? $this ->UserScreeningPass->setUserScreeningPassSalt( WTVRcleanString( $this -> getUserScreeningPassSalt()) ) : null;
    ($this -> getUserScreeningPassAccessKey())? $this ->UserScreeningPass->setUserScreeningPassAccessKey( WTVRcleanString( $this -> getUserScreeningPassAccessKey()) ) : null;
    ($this -> getUserScreeningPassViews())? $this ->UserScreeningPass->setUserScreeningPassViews( WTVRcleanString( $this -> getUserScreeningPassViews()) ) : null;
          if (is_valid_date( $this ->UserScreeningPass->getUserScreeningPassCreatedAt())) {
        $this ->UserScreeningPass->setUserScreeningPassCreatedAt( formatDate($this -> getUserScreeningPassCreatedAt(), "TS" ));
      } else {
      $UserScreeningPassuser_screening_pass_created_at = $this -> sfDateTime( "user_screening_pass_created_at" );
      ( $UserScreeningPassuser_screening_pass_created_at != "01/01/1900 00:00:00" )? $this ->UserScreeningPass->setUserScreeningPassCreatedAt( formatDate($UserScreeningPassuser_screening_pass_created_at, "TS" )) : $this ->UserScreeningPass->setUserScreeningPassCreatedAt( null );
      }
          if (is_valid_date( $this ->UserScreeningPass->getUserScreeningPassUpdatedAt())) {
        $this ->UserScreeningPass->setUserScreeningPassUpdatedAt( formatDate($this -> getUserScreeningPassUpdatedAt(), "TS" ));
      } else {
      $UserScreeningPassuser_screening_pass_updated_at = $this -> sfDateTime( "user_screening_pass_updated_at" );
      ( $UserScreeningPassuser_screening_pass_updated_at != "01/01/1900 00:00:00" )? $this ->UserScreeningPass->setUserScreeningPassUpdatedAt( formatDate($UserScreeningPassuser_screening_pass_updated_at, "TS" )) : $this ->UserScreeningPass->setUserScreeningPassUpdatedAt( null );
      }
          
    $this -> save();
     
  }
  
  function save() {
    $this ->UserScreeningPass ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->UserScreeningPass = UserScreeningPassPeer::retrieveByPK($id);
    }
    
    if (! $this ->UserScreeningPass ) {
      return;
    }
    
    $this ->UserScreeningPass -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('UserScreeningPass_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "UserScreeningPassPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $UserScreeningPass = UserScreeningPassPeer::doSelect($c);
    
    if (count($UserScreeningPass) >= 1) {
      $this ->UserScreeningPass = $UserScreeningPass[0];
      return true;
    } else {
      $this ->UserScreeningPass = new UserScreeningPass();
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
      $name = "UserScreeningPassPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $UserScreeningPass = UserScreeningPassPeer::doSelect($c);
    
    if (count($UserScreeningPass) >= 1) {
      $this ->UserScreeningPass = $UserScreeningPass[0];
      return true;
    } else {
      $this ->UserScreeningPass = new UserScreeningPass();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>