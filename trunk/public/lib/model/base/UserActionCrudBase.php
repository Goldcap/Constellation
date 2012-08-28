<?php
       
   class UserActionCrudBase extends Utils_PageWidget { 
   
    var $UserAction;
   
       var $user_action_id;
   var $fk_user_id;
   var $fk_action_id;
   var $user_action_ip_address;
   var $user_action_user_agent;
   var $user_action_date;
   var $user_action_data;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getUserActionId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->UserAction = UserActionPeer::retrieveByPK( $id );
    } else {
      $this ->UserAction = new UserAction;
    }
  }
  
  function hydrate( $id ) {
      $this ->UserAction = UserActionPeer::retrieveByPK( $id );
  }
  
  function getUserActionId() {
    if (($this ->postVar("user_action_id")) || ($this ->postVar("user_action_id") === "")) {
      return $this ->postVar("user_action_id");
    } elseif (($this ->getVar("user_action_id")) || ($this ->getVar("user_action_id") === "")) {
      return $this ->getVar("user_action_id");
    } elseif (($this ->UserAction) || ($this ->UserAction === "")){
      return $this ->UserAction -> getUserActionId();
    } elseif (($this ->sessionVar("user_action_id")) || ($this ->sessionVar("user_action_id") == "")) {
      return $this ->sessionVar("user_action_id");
    } else {
      return false;
    }
  }
  
  function setUserActionId( $str ) {
    $this ->UserAction -> setUserActionId( $str );
  }
  
  function getFkUserId() {
    if (($this ->postVar("fk_user_id")) || ($this ->postVar("fk_user_id") === "")) {
      return $this ->postVar("fk_user_id");
    } elseif (($this ->getVar("fk_user_id")) || ($this ->getVar("fk_user_id") === "")) {
      return $this ->getVar("fk_user_id");
    } elseif (($this ->UserAction) || ($this ->UserAction === "")){
      return $this ->UserAction -> getFkUserId();
    } elseif (($this ->sessionVar("fk_user_id")) || ($this ->sessionVar("fk_user_id") == "")) {
      return $this ->sessionVar("fk_user_id");
    } else {
      return false;
    }
  }
  
  function setFkUserId( $str ) {
    $this ->UserAction -> setFkUserId( $str );
  }
  
  function getFkActionId() {
    if (($this ->postVar("fk_action_id")) || ($this ->postVar("fk_action_id") === "")) {
      return $this ->postVar("fk_action_id");
    } elseif (($this ->getVar("fk_action_id")) || ($this ->getVar("fk_action_id") === "")) {
      return $this ->getVar("fk_action_id");
    } elseif (($this ->UserAction) || ($this ->UserAction === "")){
      return $this ->UserAction -> getFkActionId();
    } elseif (($this ->sessionVar("fk_action_id")) || ($this ->sessionVar("fk_action_id") == "")) {
      return $this ->sessionVar("fk_action_id");
    } else {
      return false;
    }
  }
  
  function setFkActionId( $str ) {
    $this ->UserAction -> setFkActionId( $str );
  }
  
  function getUserActionIpAddress() {
    if (($this ->postVar("user_action_ip_address")) || ($this ->postVar("user_action_ip_address") === "")) {
      return $this ->postVar("user_action_ip_address");
    } elseif (($this ->getVar("user_action_ip_address")) || ($this ->getVar("user_action_ip_address") === "")) {
      return $this ->getVar("user_action_ip_address");
    } elseif (($this ->UserAction) || ($this ->UserAction === "")){
      return $this ->UserAction -> getUserActionIpAddress();
    } elseif (($this ->sessionVar("user_action_ip_address")) || ($this ->sessionVar("user_action_ip_address") == "")) {
      return $this ->sessionVar("user_action_ip_address");
    } else {
      return false;
    }
  }
  
  function setUserActionIpAddress( $str ) {
    $this ->UserAction -> setUserActionIpAddress( $str );
  }
  
  function getUserActionUserAgent() {
    if (($this ->postVar("user_action_user_agent")) || ($this ->postVar("user_action_user_agent") === "")) {
      return $this ->postVar("user_action_user_agent");
    } elseif (($this ->getVar("user_action_user_agent")) || ($this ->getVar("user_action_user_agent") === "")) {
      return $this ->getVar("user_action_user_agent");
    } elseif (($this ->UserAction) || ($this ->UserAction === "")){
      return $this ->UserAction -> getUserActionUserAgent();
    } elseif (($this ->sessionVar("user_action_user_agent")) || ($this ->sessionVar("user_action_user_agent") == "")) {
      return $this ->sessionVar("user_action_user_agent");
    } else {
      return false;
    }
  }
  
  function setUserActionUserAgent( $str ) {
    $this ->UserAction -> setUserActionUserAgent( $str );
  }
  
  function getUserActionDate() {
    if (($this ->postVar("user_action_date")) || ($this ->postVar("user_action_date") === "")) {
      return $this ->postVar("user_action_date");
    } elseif (($this ->getVar("user_action_date")) || ($this ->getVar("user_action_date") === "")) {
      return $this ->getVar("user_action_date");
    } elseif (($this ->UserAction) || ($this ->UserAction === "")){
      return $this ->UserAction -> getUserActionDate();
    } elseif (($this ->sessionVar("user_action_date")) || ($this ->sessionVar("user_action_date") == "")) {
      return $this ->sessionVar("user_action_date");
    } else {
      return false;
    }
  }
  
  function setUserActionDate( $str ) {
    $this ->UserAction -> setUserActionDate( $str );
  }
  
  function getUserActionData() {
    if (($this ->postVar("user_action_data")) || ($this ->postVar("user_action_data") === "")) {
      return $this ->postVar("user_action_data");
    } elseif (($this ->getVar("user_action_data")) || ($this ->getVar("user_action_data") === "")) {
      return $this ->getVar("user_action_data");
    } elseif (($this ->UserAction) || ($this ->UserAction === "")){
      return $this ->UserAction -> getUserActionData();
    } elseif (($this ->sessionVar("user_action_data")) || ($this ->sessionVar("user_action_data") == "")) {
      return $this ->sessionVar("user_action_data");
    } else {
      return false;
    }
  }
  
  function setUserActionData( $str ) {
    $this ->UserAction -> setUserActionData( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->UserAction = UserActionPeer::retrieveByPK( $id );
    }
    
    if ($this ->UserAction ) {
       
    	       (is_numeric(WTVRcleanString($this ->UserAction->getUserActionId()))) ? $itemarray["user_action_id"] = WTVRcleanString($this ->UserAction->getUserActionId()) : null;
          (is_numeric(WTVRcleanString($this ->UserAction->getFkUserId()))) ? $itemarray["fk_user_id"] = WTVRcleanString($this ->UserAction->getFkUserId()) : null;
          (is_numeric(WTVRcleanString($this ->UserAction->getFkActionId()))) ? $itemarray["fk_action_id"] = WTVRcleanString($this ->UserAction->getFkActionId()) : null;
          (WTVRcleanString($this ->UserAction->getUserActionIpAddress())) ? $itemarray["user_action_ip_address"] = WTVRcleanString($this ->UserAction->getUserActionIpAddress()) : null;
          (WTVRcleanString($this ->UserAction->getUserActionUserAgent())) ? $itemarray["user_action_user_agent"] = WTVRcleanString($this ->UserAction->getUserActionUserAgent()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->UserAction->getUserActionDate())) ? $itemarray["user_action_date"] = formatDate($this ->UserAction->getUserActionDate('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->UserAction->getUserActionData())) ? $itemarray["user_action_data"] = WTVRcleanString($this ->UserAction->getUserActionData()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->UserAction = UserActionPeer::retrieveByPK( $id );
    } elseif (! $this ->UserAction) {
      $this ->UserAction = new UserAction;
    }
        
  	 ($this -> getUserActionId())? $this ->UserAction->setUserActionId( WTVRcleanString( $this -> getUserActionId()) ) : null;
    ($this -> getFkUserId())? $this ->UserAction->setFkUserId( WTVRcleanString( $this -> getFkUserId()) ) : null;
    ($this -> getFkActionId())? $this ->UserAction->setFkActionId( WTVRcleanString( $this -> getFkActionId()) ) : null;
    ($this -> getUserActionIpAddress())? $this ->UserAction->setUserActionIpAddress( WTVRcleanString( $this -> getUserActionIpAddress()) ) : null;
    ($this -> getUserActionUserAgent())? $this ->UserAction->setUserActionUserAgent( WTVRcleanString( $this -> getUserActionUserAgent()) ) : null;
          if (is_valid_date( $this ->UserAction->getUserActionDate())) {
        $this ->UserAction->setUserActionDate( formatDate($this -> getUserActionDate(), "TS" ));
      } else {
      $UserActionuser_action_date = $this -> sfDateTime( "user_action_date" );
      ( $UserActionuser_action_date != "01/01/1900 00:00:00" )? $this ->UserAction->setUserActionDate( formatDate($UserActionuser_action_date, "TS" )) : $this ->UserAction->setUserActionDate( null );
      }
    ($this -> getUserActionData())? $this ->UserAction->setUserActionData( WTVRcleanString( $this -> getUserActionData()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->UserAction ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->UserAction = UserActionPeer::retrieveByPK($id);
    }
    
    if (! $this ->UserAction ) {
      return;
    }
    
    $this ->UserAction -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('UserAction_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "UserActionPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $UserAction = UserActionPeer::doSelect($c);
    
    if (count($UserAction) >= 1) {
      $this ->UserAction = $UserAction[0];
      return true;
    } else {
      $this ->UserAction = new UserAction();
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
      $name = "UserActionPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $UserAction = UserActionPeer::doSelect($c);
    
    if (count($UserAction) >= 1) {
      $this ->UserAction = $UserAction[0];
      return true;
    } else {
      $this ->UserAction = new UserAction();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>