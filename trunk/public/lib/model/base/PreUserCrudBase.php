<?php
       
   class PreUserCrudBase extends Utils_PageWidget { 
   
    var $PreUser;
   
       var $pre_user_id;
   var $pre_user_email;
   var $pre_user_date_added;
   var $pre_user_status;
   var $pre_user_code;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getPreUserId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->PreUser = PreUserPeer::retrieveByPK( $id );
    } else {
      $this ->PreUser = new PreUser;
    }
  }
  
  function hydrate( $id ) {
      $this ->PreUser = PreUserPeer::retrieveByPK( $id );
  }
  
  function getPreUserId() {
    if (($this ->postVar("pre_user_id")) || ($this ->postVar("pre_user_id") === "")) {
      return $this ->postVar("pre_user_id");
    } elseif (($this ->getVar("pre_user_id")) || ($this ->getVar("pre_user_id") === "")) {
      return $this ->getVar("pre_user_id");
    } elseif (($this ->PreUser) || ($this ->PreUser === "")){
      return $this ->PreUser -> getPreUserId();
    } elseif (($this ->sessionVar("pre_user_id")) || ($this ->sessionVar("pre_user_id") == "")) {
      return $this ->sessionVar("pre_user_id");
    } else {
      return false;
    }
  }
  
  function setPreUserId( $str ) {
    $this ->PreUser -> setPreUserId( $str );
  }
  
  function getPreUserEmail() {
    if (($this ->postVar("pre_user_email")) || ($this ->postVar("pre_user_email") === "")) {
      return $this ->postVar("pre_user_email");
    } elseif (($this ->getVar("pre_user_email")) || ($this ->getVar("pre_user_email") === "")) {
      return $this ->getVar("pre_user_email");
    } elseif (($this ->PreUser) || ($this ->PreUser === "")){
      return $this ->PreUser -> getPreUserEmail();
    } elseif (($this ->sessionVar("pre_user_email")) || ($this ->sessionVar("pre_user_email") == "")) {
      return $this ->sessionVar("pre_user_email");
    } else {
      return false;
    }
  }
  
  function setPreUserEmail( $str ) {
    $this ->PreUser -> setPreUserEmail( $str );
  }
  
  function getPreUserDateAdded() {
    if (($this ->postVar("pre_user_date_added")) || ($this ->postVar("pre_user_date_added") === "")) {
      return $this ->postVar("pre_user_date_added");
    } elseif (($this ->getVar("pre_user_date_added")) || ($this ->getVar("pre_user_date_added") === "")) {
      return $this ->getVar("pre_user_date_added");
    } elseif (($this ->PreUser) || ($this ->PreUser === "")){
      return $this ->PreUser -> getPreUserDateAdded();
    } elseif (($this ->sessionVar("pre_user_date_added")) || ($this ->sessionVar("pre_user_date_added") == "")) {
      return $this ->sessionVar("pre_user_date_added");
    } else {
      return false;
    }
  }
  
  function setPreUserDateAdded( $str ) {
    $this ->PreUser -> setPreUserDateAdded( $str );
  }
  
  function getPreUserStatus() {
    if (($this ->postVar("pre_user_status")) || ($this ->postVar("pre_user_status") === "")) {
      return $this ->postVar("pre_user_status");
    } elseif (($this ->getVar("pre_user_status")) || ($this ->getVar("pre_user_status") === "")) {
      return $this ->getVar("pre_user_status");
    } elseif (($this ->PreUser) || ($this ->PreUser === "")){
      return $this ->PreUser -> getPreUserStatus();
    } elseif (($this ->sessionVar("pre_user_status")) || ($this ->sessionVar("pre_user_status") == "")) {
      return $this ->sessionVar("pre_user_status");
    } else {
      return false;
    }
  }
  
  function setPreUserStatus( $str ) {
    $this ->PreUser -> setPreUserStatus( $str );
  }
  
  function getPreUserCode() {
    if (($this ->postVar("pre_user_code")) || ($this ->postVar("pre_user_code") === "")) {
      return $this ->postVar("pre_user_code");
    } elseif (($this ->getVar("pre_user_code")) || ($this ->getVar("pre_user_code") === "")) {
      return $this ->getVar("pre_user_code");
    } elseif (($this ->PreUser) || ($this ->PreUser === "")){
      return $this ->PreUser -> getPreUserCode();
    } elseif (($this ->sessionVar("pre_user_code")) || ($this ->sessionVar("pre_user_code") == "")) {
      return $this ->sessionVar("pre_user_code");
    } else {
      return false;
    }
  }
  
  function setPreUserCode( $str ) {
    $this ->PreUser -> setPreUserCode( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->PreUser = PreUserPeer::retrieveByPK( $id );
    }
    
    if ($this ->PreUser ) {
       
    	       (is_numeric(WTVRcleanString($this ->PreUser->getPreUserId()))) ? $itemarray["pre_user_id"] = WTVRcleanString($this ->PreUser->getPreUserId()) : null;
          (WTVRcleanString($this ->PreUser->getPreUserEmail())) ? $itemarray["pre_user_email"] = WTVRcleanString($this ->PreUser->getPreUserEmail()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->PreUser->getPreUserDateAdded())) ? $itemarray["pre_user_date_added"] = formatDate($this ->PreUser->getPreUserDateAdded('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->PreUser->getPreUserStatus())) ? $itemarray["pre_user_status"] = WTVRcleanString($this ->PreUser->getPreUserStatus()) : null;
          (WTVRcleanString($this ->PreUser->getPreUserCode())) ? $itemarray["pre_user_code"] = WTVRcleanString($this ->PreUser->getPreUserCode()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->PreUser = PreUserPeer::retrieveByPK( $id );
    } elseif (! $this ->PreUser) {
      $this ->PreUser = new PreUser;
    }
        
  	 ($this -> getPreUserId())? $this ->PreUser->setPreUserId( WTVRcleanString( $this -> getPreUserId()) ) : null;
    ($this -> getPreUserEmail())? $this ->PreUser->setPreUserEmail( WTVRcleanString( $this -> getPreUserEmail()) ) : null;
          if (is_valid_date( $this ->PreUser->getPreUserDateAdded())) {
        $this ->PreUser->setPreUserDateAdded( formatDate($this -> getPreUserDateAdded(), "TS" ));
      } else {
      $PreUserpre_user_date_added = $this -> sfDateTime( "pre_user_date_added" );
      ( $PreUserpre_user_date_added != "01/01/1900 00:00:00" )? $this ->PreUser->setPreUserDateAdded( formatDate($PreUserpre_user_date_added, "TS" )) : $this ->PreUser->setPreUserDateAdded( null );
      }
    ($this -> getPreUserStatus())? $this ->PreUser->setPreUserStatus( WTVRcleanString( $this -> getPreUserStatus()) ) : null;
    ($this -> getPreUserCode())? $this ->PreUser->setPreUserCode( WTVRcleanString( $this -> getPreUserCode()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->PreUser ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->PreUser = PreUserPeer::retrieveByPK($id);
    }
    
    if (! $this ->PreUser ) {
      return;
    }
    
    $this ->PreUser -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('PreUser_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "PreUserPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $PreUser = PreUserPeer::doSelect($c);
    
    if (count($PreUser) >= 1) {
      $this ->PreUser = $PreUser[0];
      return true;
    } else {
      $this ->PreUser = new PreUser();
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
      $name = "PreUserPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $PreUser = PreUserPeer::doSelect($c);
    
    if (count($PreUser) >= 1) {
      $this ->PreUser = $PreUser[0];
      return true;
    } else {
      $this ->PreUser = new PreUser();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>