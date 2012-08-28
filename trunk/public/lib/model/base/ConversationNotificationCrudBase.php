<?php
       
   class ConversationNotificationCrudBase extends Utils_PageWidget { 
   
    var $ConversationNotification;
   
       var $conversation_notification_id;
   var $fk_conversation_guid;
   var $conversation_notification_type;
   var $conversation_notification_date_created;
   var $conversation_notification_date_sent;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getConversationNotificationId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->ConversationNotification = ConversationNotificationPeer::retrieveByPK( $id );
    } else {
      $this ->ConversationNotification = new ConversationNotification;
    }
  }
  
  function hydrate( $id ) {
      $this ->ConversationNotification = ConversationNotificationPeer::retrieveByPK( $id );
  }
  
  function getConversationNotificationId() {
    if (($this ->postVar("conversation_notification_id")) || ($this ->postVar("conversation_notification_id") === "")) {
      return $this ->postVar("conversation_notification_id");
    } elseif (($this ->getVar("conversation_notification_id")) || ($this ->getVar("conversation_notification_id") === "")) {
      return $this ->getVar("conversation_notification_id");
    } elseif (($this ->ConversationNotification) || ($this ->ConversationNotification === "")){
      return $this ->ConversationNotification -> getConversationNotificationId();
    } elseif (($this ->sessionVar("conversation_notification_id")) || ($this ->sessionVar("conversation_notification_id") == "")) {
      return $this ->sessionVar("conversation_notification_id");
    } else {
      return false;
    }
  }
  
  function setConversationNotificationId( $str ) {
    $this ->ConversationNotification -> setConversationNotificationId( $str );
  }
  
  function getFkConversationGuid() {
    if (($this ->postVar("fk_conversation_guid")) || ($this ->postVar("fk_conversation_guid") === "")) {
      return $this ->postVar("fk_conversation_guid");
    } elseif (($this ->getVar("fk_conversation_guid")) || ($this ->getVar("fk_conversation_guid") === "")) {
      return $this ->getVar("fk_conversation_guid");
    } elseif (($this ->ConversationNotification) || ($this ->ConversationNotification === "")){
      return $this ->ConversationNotification -> getFkConversationGuid();
    } elseif (($this ->sessionVar("fk_conversation_guid")) || ($this ->sessionVar("fk_conversation_guid") == "")) {
      return $this ->sessionVar("fk_conversation_guid");
    } else {
      return false;
    }
  }
  
  function setFkConversationGuid( $str ) {
    $this ->ConversationNotification -> setFkConversationGuid( $str );
  }
  
  function getConversationNotificationType() {
    if (($this ->postVar("conversation_notification_type")) || ($this ->postVar("conversation_notification_type") === "")) {
      return $this ->postVar("conversation_notification_type");
    } elseif (($this ->getVar("conversation_notification_type")) || ($this ->getVar("conversation_notification_type") === "")) {
      return $this ->getVar("conversation_notification_type");
    } elseif (($this ->ConversationNotification) || ($this ->ConversationNotification === "")){
      return $this ->ConversationNotification -> getConversationNotificationType();
    } elseif (($this ->sessionVar("conversation_notification_type")) || ($this ->sessionVar("conversation_notification_type") == "")) {
      return $this ->sessionVar("conversation_notification_type");
    } else {
      return false;
    }
  }
  
  function setConversationNotificationType( $str ) {
    $this ->ConversationNotification -> setConversationNotificationType( $str );
  }
  
  function getConversationNotificationDateCreated() {
    if (($this ->postVar("conversation_notification_date_created")) || ($this ->postVar("conversation_notification_date_created") === "")) {
      return $this ->postVar("conversation_notification_date_created");
    } elseif (($this ->getVar("conversation_notification_date_created")) || ($this ->getVar("conversation_notification_date_created") === "")) {
      return $this ->getVar("conversation_notification_date_created");
    } elseif (($this ->ConversationNotification) || ($this ->ConversationNotification === "")){
      return $this ->ConversationNotification -> getConversationNotificationDateCreated();
    } elseif (($this ->sessionVar("conversation_notification_date_created")) || ($this ->sessionVar("conversation_notification_date_created") == "")) {
      return $this ->sessionVar("conversation_notification_date_created");
    } else {
      return false;
    }
  }
  
  function setConversationNotificationDateCreated( $str ) {
    $this ->ConversationNotification -> setConversationNotificationDateCreated( $str );
  }
  
  function getConversationNotificationDateSent() {
    if (($this ->postVar("conversation_notification_date_sent")) || ($this ->postVar("conversation_notification_date_sent") === "")) {
      return $this ->postVar("conversation_notification_date_sent");
    } elseif (($this ->getVar("conversation_notification_date_sent")) || ($this ->getVar("conversation_notification_date_sent") === "")) {
      return $this ->getVar("conversation_notification_date_sent");
    } elseif (($this ->ConversationNotification) || ($this ->ConversationNotification === "")){
      return $this ->ConversationNotification -> getConversationNotificationDateSent();
    } elseif (($this ->sessionVar("conversation_notification_date_sent")) || ($this ->sessionVar("conversation_notification_date_sent") == "")) {
      return $this ->sessionVar("conversation_notification_date_sent");
    } else {
      return false;
    }
  }
  
  function setConversationNotificationDateSent( $str ) {
    $this ->ConversationNotification -> setConversationNotificationDateSent( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->ConversationNotification = ConversationNotificationPeer::retrieveByPK( $id );
    }
    
    if ($this ->ConversationNotification ) {
       
    	       (is_numeric(WTVRcleanString($this ->ConversationNotification->getConversationNotificationId()))) ? $itemarray["conversation_notification_id"] = WTVRcleanString($this ->ConversationNotification->getConversationNotificationId()) : null;
          (WTVRcleanString($this ->ConversationNotification->getFkConversationGuid())) ? $itemarray["fk_conversation_guid"] = WTVRcleanString($this ->ConversationNotification->getFkConversationGuid()) : null;
          (WTVRcleanString($this ->ConversationNotification->getConversationNotificationType())) ? $itemarray["conversation_notification_type"] = WTVRcleanString($this ->ConversationNotification->getConversationNotificationType()) : null;
          (WTVRcleanString($this ->ConversationNotification->getConversationNotificationDateCreated())) ? $itemarray["conversation_notification_date_created"] = WTVRcleanString($this ->ConversationNotification->getConversationNotificationDateCreated()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->ConversationNotification->getConversationNotificationDateSent())) ? $itemarray["conversation_notification_date_sent"] = formatDate($this ->ConversationNotification->getConversationNotificationDateSent('%Y-%m-%d %T'),"TS") : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->ConversationNotification = ConversationNotificationPeer::retrieveByPK( $id );
    } elseif (! $this ->ConversationNotification) {
      $this ->ConversationNotification = new ConversationNotification;
    }
        
  	 ($this -> getConversationNotificationId())? $this ->ConversationNotification->setConversationNotificationId( WTVRcleanString( $this -> getConversationNotificationId()) ) : null;
    ($this -> getFkConversationGuid())? $this ->ConversationNotification->setFkConversationGuid( WTVRcleanString( $this -> getFkConversationGuid()) ) : null;
    ($this -> getConversationNotificationType())? $this ->ConversationNotification->setConversationNotificationType( WTVRcleanString( $this -> getConversationNotificationType()) ) : null;
    ($this -> getConversationNotificationDateCreated())? $this ->ConversationNotification->setConversationNotificationDateCreated( WTVRcleanString( $this -> getConversationNotificationDateCreated()) ) : null;
          if (is_valid_date( $this ->ConversationNotification->getConversationNotificationDateSent())) {
        $this ->ConversationNotification->setConversationNotificationDateSent( formatDate($this -> getConversationNotificationDateSent(), "TS" ));
      } else {
      $ConversationNotificationconversation_notification_date_sent = $this -> sfDateTime( "conversation_notification_date_sent" );
      ( $ConversationNotificationconversation_notification_date_sent != "01/01/1900 00:00:00" )? $this ->ConversationNotification->setConversationNotificationDateSent( formatDate($ConversationNotificationconversation_notification_date_sent, "TS" )) : $this ->ConversationNotification->setConversationNotificationDateSent( null );
      }
          
    $this -> save();
     
  }
  
  function save() {
    $this ->ConversationNotification ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->ConversationNotification = ConversationNotificationPeer::retrieveByPK($id);
    }
    
    if (! $this ->ConversationNotification ) {
      return;
    }
    
    $this ->ConversationNotification -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('ConversationNotification_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "ConversationNotificationPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $ConversationNotification = ConversationNotificationPeer::doSelect($c);
    
    if (count($ConversationNotification) >= 1) {
      $this ->ConversationNotification = $ConversationNotification[0];
      return true;
    } else {
      $this ->ConversationNotification = new ConversationNotification();
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
      $name = "ConversationNotificationPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $ConversationNotification = ConversationNotificationPeer::doSelect($c);
    
    if (count($ConversationNotification) >= 1) {
      $this ->ConversationNotification = $ConversationNotification[0];
      return true;
    } else {
      $this ->ConversationNotification = new ConversationNotification();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>