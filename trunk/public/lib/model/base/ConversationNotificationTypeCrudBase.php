<?php
       
   class ConversationNotificationTypeCrudBase extends Utils_PageWidget { 
   
    var $ConversationNotificationType;
   
       var $conversation_notification_type_id;
   var $conversation_notification_type_name;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getConversationNotificationTypeId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->ConversationNotificationType = ConversationNotificationTypePeer::retrieveByPK( $id );
    } else {
      $this ->ConversationNotificationType = new ConversationNotificationType;
    }
  }
  
  function hydrate( $id ) {
      $this ->ConversationNotificationType = ConversationNotificationTypePeer::retrieveByPK( $id );
  }
  
  function getConversationNotificationTypeId() {
    if (($this ->postVar("conversation_notification_type_id")) || ($this ->postVar("conversation_notification_type_id") === "")) {
      return $this ->postVar("conversation_notification_type_id");
    } elseif (($this ->getVar("conversation_notification_type_id")) || ($this ->getVar("conversation_notification_type_id") === "")) {
      return $this ->getVar("conversation_notification_type_id");
    } elseif (($this ->ConversationNotificationType) || ($this ->ConversationNotificationType === "")){
      return $this ->ConversationNotificationType -> getConversationNotificationTypeId();
    } elseif (($this ->sessionVar("conversation_notification_type_id")) || ($this ->sessionVar("conversation_notification_type_id") == "")) {
      return $this ->sessionVar("conversation_notification_type_id");
    } else {
      return false;
    }
  }
  
  function setConversationNotificationTypeId( $str ) {
    $this ->ConversationNotificationType -> setConversationNotificationTypeId( $str );
  }
  
  function getConversationNotificationTypeName() {
    if (($this ->postVar("conversation_notification_type_name")) || ($this ->postVar("conversation_notification_type_name") === "")) {
      return $this ->postVar("conversation_notification_type_name");
    } elseif (($this ->getVar("conversation_notification_type_name")) || ($this ->getVar("conversation_notification_type_name") === "")) {
      return $this ->getVar("conversation_notification_type_name");
    } elseif (($this ->ConversationNotificationType) || ($this ->ConversationNotificationType === "")){
      return $this ->ConversationNotificationType -> getConversationNotificationTypeName();
    } elseif (($this ->sessionVar("conversation_notification_type_name")) || ($this ->sessionVar("conversation_notification_type_name") == "")) {
      return $this ->sessionVar("conversation_notification_type_name");
    } else {
      return false;
    }
  }
  
  function setConversationNotificationTypeName( $str ) {
    $this ->ConversationNotificationType -> setConversationNotificationTypeName( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->ConversationNotificationType = ConversationNotificationTypePeer::retrieveByPK( $id );
    }
    
    if ($this ->ConversationNotificationType ) {
       
    	       (is_numeric(WTVRcleanString($this ->ConversationNotificationType->getConversationNotificationTypeId()))) ? $itemarray["conversation_notification_type_id"] = WTVRcleanString($this ->ConversationNotificationType->getConversationNotificationTypeId()) : null;
          (WTVRcleanString($this ->ConversationNotificationType->getConversationNotificationTypeName())) ? $itemarray["conversation_notification_type_name"] = WTVRcleanString($this ->ConversationNotificationType->getConversationNotificationTypeName()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->ConversationNotificationType = ConversationNotificationTypePeer::retrieveByPK( $id );
    } elseif (! $this ->ConversationNotificationType) {
      $this ->ConversationNotificationType = new ConversationNotificationType;
    }
        
  	 ($this -> getConversationNotificationTypeId())? $this ->ConversationNotificationType->setConversationNotificationTypeId( WTVRcleanString( $this -> getConversationNotificationTypeId()) ) : null;
    ($this -> getConversationNotificationTypeName())? $this ->ConversationNotificationType->setConversationNotificationTypeName( WTVRcleanString( $this -> getConversationNotificationTypeName()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->ConversationNotificationType ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->ConversationNotificationType = ConversationNotificationTypePeer::retrieveByPK($id);
    }
    
    if (! $this ->ConversationNotificationType ) {
      return;
    }
    
    $this ->ConversationNotificationType -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('ConversationNotificationType_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "ConversationNotificationTypePeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $ConversationNotificationType = ConversationNotificationTypePeer::doSelect($c);
    
    if (count($ConversationNotificationType) >= 1) {
      $this ->ConversationNotificationType = $ConversationNotificationType[0];
      return true;
    } else {
      $this ->ConversationNotificationType = new ConversationNotificationType();
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
      $name = "ConversationNotificationTypePeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $ConversationNotificationType = ConversationNotificationTypePeer::doSelect($c);
    
    if (count($ConversationNotificationType) >= 1) {
      $this ->ConversationNotificationType = $ConversationNotificationType[0];
      return true;
    } else {
      $this ->ConversationNotificationType = new ConversationNotificationType();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>