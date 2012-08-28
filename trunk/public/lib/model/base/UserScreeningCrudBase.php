<?php
       
   class UserScreeningCrudBase extends Utils_PageWidget { 
   
    var $UserScreening;
   
       var $user_screening_id;
   var $user_screening_username;
   var $user_screening_created_at;
   var $user_screening_updated_at;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getUserScreeningId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->UserScreening = UserScreeningPeer::retrieveByPK( $id );
    } else {
      $this ->UserScreening = new UserScreening;
    }
  }
  
  function hydrate( $id ) {
      $this ->UserScreening = UserScreeningPeer::retrieveByPK( $id );
  }
  
  function getUserScreeningId() {
    if (($this ->postVar("user_screening_id")) || ($this ->postVar("user_screening_id") === "")) {
      return $this ->postVar("user_screening_id");
    } elseif (($this ->getVar("user_screening_id")) || ($this ->getVar("user_screening_id") === "")) {
      return $this ->getVar("user_screening_id");
    } elseif (($this ->UserScreening) || ($this ->UserScreening === "")){
      return $this ->UserScreening -> getUserScreeningId();
    } elseif (($this ->sessionVar("user_screening_id")) || ($this ->sessionVar("user_screening_id") == "")) {
      return $this ->sessionVar("user_screening_id");
    } else {
      return false;
    }
  }
  
  function setUserScreeningId( $str ) {
    $this ->UserScreening -> setUserScreeningId( $str );
  }
  
  function getUserScreeningUsername() {
    if (($this ->postVar("user_screening_username")) || ($this ->postVar("user_screening_username") === "")) {
      return $this ->postVar("user_screening_username");
    } elseif (($this ->getVar("user_screening_username")) || ($this ->getVar("user_screening_username") === "")) {
      return $this ->getVar("user_screening_username");
    } elseif (($this ->UserScreening) || ($this ->UserScreening === "")){
      return $this ->UserScreening -> getUserScreeningUsername();
    } elseif (($this ->sessionVar("user_screening_username")) || ($this ->sessionVar("user_screening_username") == "")) {
      return $this ->sessionVar("user_screening_username");
    } else {
      return false;
    }
  }
  
  function setUserScreeningUsername( $str ) {
    $this ->UserScreening -> setUserScreeningUsername( $str );
  }
  
  function getUserScreeningCreatedAt() {
    if (($this ->postVar("user_screening_created_at")) || ($this ->postVar("user_screening_created_at") === "")) {
      return $this ->postVar("user_screening_created_at");
    } elseif (($this ->getVar("user_screening_created_at")) || ($this ->getVar("user_screening_created_at") === "")) {
      return $this ->getVar("user_screening_created_at");
    } elseif (($this ->UserScreening) || ($this ->UserScreening === "")){
      return $this ->UserScreening -> getUserScreeningCreatedAt();
    } elseif (($this ->sessionVar("user_screening_created_at")) || ($this ->sessionVar("user_screening_created_at") == "")) {
      return $this ->sessionVar("user_screening_created_at");
    } else {
      return false;
    }
  }
  
  function setUserScreeningCreatedAt( $str ) {
    $this ->UserScreening -> setUserScreeningCreatedAt( $str );
  }
  
  function getUserScreeningUpdatedAt() {
    if (($this ->postVar("user_screening_updated_at")) || ($this ->postVar("user_screening_updated_at") === "")) {
      return $this ->postVar("user_screening_updated_at");
    } elseif (($this ->getVar("user_screening_updated_at")) || ($this ->getVar("user_screening_updated_at") === "")) {
      return $this ->getVar("user_screening_updated_at");
    } elseif (($this ->UserScreening) || ($this ->UserScreening === "")){
      return $this ->UserScreening -> getUserScreeningUpdatedAt();
    } elseif (($this ->sessionVar("user_screening_updated_at")) || ($this ->sessionVar("user_screening_updated_at") == "")) {
      return $this ->sessionVar("user_screening_updated_at");
    } else {
      return false;
    }
  }
  
  function setUserScreeningUpdatedAt( $str ) {
    $this ->UserScreening -> setUserScreeningUpdatedAt( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->UserScreening = UserScreeningPeer::retrieveByPK( $id );
    }
    
    if ($this ->UserScreening ) {
       
    	       (is_numeric(WTVRcleanString($this ->UserScreening->getUserScreeningId()))) ? $itemarray["user_screening_id"] = WTVRcleanString($this ->UserScreening->getUserScreeningId()) : null;
          (WTVRcleanString($this ->UserScreening->getUserScreeningUsername())) ? $itemarray["user_screening_username"] = WTVRcleanString($this ->UserScreening->getUserScreeningUsername()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->UserScreening->getUserScreeningCreatedAt())) ? $itemarray["user_screening_created_at"] = formatDate($this ->UserScreening->getUserScreeningCreatedAt('%Y-%m-%d %T'),"TS") : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->UserScreening->getUserScreeningUpdatedAt())) ? $itemarray["user_screening_updated_at"] = formatDate($this ->UserScreening->getUserScreeningUpdatedAt('%Y-%m-%d %T'),"TS") : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->UserScreening = UserScreeningPeer::retrieveByPK( $id );
    } elseif (! $this ->UserScreening) {
      $this ->UserScreening = new UserScreening;
    }
        
  	 ($this -> getUserScreeningId())? $this ->UserScreening->setUserScreeningId( WTVRcleanString( $this -> getUserScreeningId()) ) : null;
    ($this -> getUserScreeningUsername())? $this ->UserScreening->setUserScreeningUsername( WTVRcleanString( $this -> getUserScreeningUsername()) ) : null;
          if (is_valid_date( $this ->UserScreening->getUserScreeningCreatedAt())) {
        $this ->UserScreening->setUserScreeningCreatedAt( formatDate($this -> getUserScreeningCreatedAt(), "TS" ));
      } else {
      $UserScreeninguser_screening_created_at = $this -> sfDateTime( "user_screening_created_at" );
      ( $UserScreeninguser_screening_created_at != "01/01/1900 00:00:00" )? $this ->UserScreening->setUserScreeningCreatedAt( formatDate($UserScreeninguser_screening_created_at, "TS" )) : $this ->UserScreening->setUserScreeningCreatedAt( null );
      }
          if (is_valid_date( $this ->UserScreening->getUserScreeningUpdatedAt())) {
        $this ->UserScreening->setUserScreeningUpdatedAt( formatDate($this -> getUserScreeningUpdatedAt(), "TS" ));
      } else {
      $UserScreeninguser_screening_updated_at = $this -> sfDateTime( "user_screening_updated_at" );
      ( $UserScreeninguser_screening_updated_at != "01/01/1900 00:00:00" )? $this ->UserScreening->setUserScreeningUpdatedAt( formatDate($UserScreeninguser_screening_updated_at, "TS" )) : $this ->UserScreening->setUserScreeningUpdatedAt( null );
      }
          
    $this -> save();
     
  }
  
  function save() {
    $this ->UserScreening ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->UserScreening = UserScreeningPeer::retrieveByPK($id);
    }
    
    if (! $this ->UserScreening ) {
      return;
    }
    
    $this ->UserScreening -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('UserScreening_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "UserScreeningPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $UserScreening = UserScreeningPeer::doSelect($c);
    
    if (count($UserScreening) >= 1) {
      $this ->UserScreening = $UserScreening[0];
      return true;
    } else {
      $this ->UserScreening = new UserScreening();
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
      $name = "UserScreeningPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $UserScreening = UserScreeningPeer::doSelect($c);
    
    if (count($UserScreening) >= 1) {
      $this ->UserScreening = $UserScreening[0];
      return true;
    } else {
      $this ->UserScreening = new UserScreening();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>