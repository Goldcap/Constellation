<?php
       
   class UserSessionCrudBase extends Utils_PageWidget { 
   
    var $UserSession;
   
       var $sess_id;
   var $sess_data;
   var $sess_time;
   var $id;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> get();
    }
    
    if (($id) && ($id != 0)) {
      $this ->UserSession = UserSessionPeer::retrieveByPK( $id );
    } else {
      $this ->UserSession = new UserSession;
    }
  }
  
  function hydrate( $id ) {
      $this ->UserSession = UserSessionPeer::retrieveByPK( $id );
  }
  
  function getSessId() {
    if (($this ->postVar("sess_id")) || ($this ->postVar("sess_id") === "")) {
      return $this ->postVar("sess_id");
    } elseif (($this ->getVar("sess_id")) || ($this ->getVar("sess_id") === "")) {
      return $this ->getVar("sess_id");
    } elseif (($this ->UserSession) || ($this ->UserSession === "")){
      return $this ->UserSession -> getSessId();
    } elseif (($this ->sessionVar("sess_id")) || ($this ->sessionVar("sess_id") == "")) {
      return $this ->sessionVar("sess_id");
    } else {
      return false;
    }
  }
  
  function setSessId( $str ) {
    $this ->UserSession -> setSessId( $str );
  }
  
  function getSessData() {
    if (($this ->postVar("sess_data")) || ($this ->postVar("sess_data") === "")) {
      return $this ->postVar("sess_data");
    } elseif (($this ->getVar("sess_data")) || ($this ->getVar("sess_data") === "")) {
      return $this ->getVar("sess_data");
    } elseif (($this ->UserSession) || ($this ->UserSession === "")){
      return $this ->UserSession -> getSessData();
    } elseif (($this ->sessionVar("sess_data")) || ($this ->sessionVar("sess_data") == "")) {
      return $this ->sessionVar("sess_data");
    } else {
      return false;
    }
  }
  
  function setSessData( $str ) {
    $this ->UserSession -> setSessData( $str );
  }
  
  function getSessTime() {
    if (($this ->postVar("sess_time")) || ($this ->postVar("sess_time") === "")) {
      return $this ->postVar("sess_time");
    } elseif (($this ->getVar("sess_time")) || ($this ->getVar("sess_time") === "")) {
      return $this ->getVar("sess_time");
    } elseif (($this ->UserSession) || ($this ->UserSession === "")){
      return $this ->UserSession -> getSessTime();
    } elseif (($this ->sessionVar("sess_time")) || ($this ->sessionVar("sess_time") == "")) {
      return $this ->sessionVar("sess_time");
    } else {
      return false;
    }
  }
  
  function setSessTime( $str ) {
    $this ->UserSession -> setSessTime( $str );
  }
  
  function getId() {
    if (($this ->postVar("id")) || ($this ->postVar("id") === "")) {
      return $this ->postVar("id");
    } elseif (($this ->getVar("id")) || ($this ->getVar("id") === "")) {
      return $this ->getVar("id");
    } elseif (($this ->UserSession) || ($this ->UserSession === "")){
      return $this ->UserSession -> getId();
    } elseif (($this ->sessionVar("id")) || ($this ->sessionVar("id") == "")) {
      return $this ->sessionVar("id");
    } else {
      return false;
    }
  }
  
  function setId( $str ) {
    $this ->UserSession -> setId( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->UserSession = UserSessionPeer::retrieveByPK( $id );
    }
    
    if ($this ->UserSession ) {
       
    	       (WTVRcleanString($this ->UserSession->getSessId())) ? $itemarray["sess_id"] = WTVRcleanString($this ->UserSession->getSessId()) : null;
          (WTVRcleanString($this ->UserSession->getSessData())) ? $itemarray["sess_data"] = WTVRcleanString($this ->UserSession->getSessData()) : null;
          (WTVRcleanString($this ->UserSession->getSessTime())) ? $itemarray["sess_time"] = WTVRcleanString($this ->UserSession->getSessTime()) : null;
          (is_numeric(WTVRcleanString($this ->UserSession->getId()))) ? $itemarray["id"] = WTVRcleanString($this ->UserSession->getId()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->UserSession = UserSessionPeer::retrieveByPK( $id );
    } elseif (! $this ->UserSession) {
      $this ->UserSession = new UserSession;
    }
        
  	 ($this -> getSessId())? $this ->UserSession->setSessId( WTVRcleanString( $this -> getSessId()) ) : null;
    ($this -> getSessData())? $this ->UserSession->setSessData( WTVRcleanString( $this -> getSessData()) ) : null;
    ($this -> getSessTime())? $this ->UserSession->setSessTime( WTVRcleanString( $this -> getSessTime()) ) : null;
    ($this -> getId())? $this ->UserSession->setId( WTVRcleanString( $this -> getId()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->UserSession ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->UserSession = UserSessionPeer::retrieveByPK($id);
    }
    
    if (! $this ->UserSession ) {
      return;
    }
    
    $this ->UserSession -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('UserSession_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "UserSessionPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $UserSession = UserSessionPeer::doSelect($c);
    
    if (count($UserSession) >= 1) {
      $this ->UserSession = $UserSession[0];
      return true;
    } else {
      $this ->UserSession = new UserSession();
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
      $name = "UserSessionPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $UserSession = UserSessionPeer::doSelect($c);
    
    if (count($UserSession) >= 1) {
      $this ->UserSession = $UserSession[0];
      return true;
    } else {
      $this ->UserSession = new UserSession();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>