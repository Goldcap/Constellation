<?php
       
   class WtvrMessageQueueGlobalCrudBase extends Utils_PageWidget { 
   
    var $WtvrMessageQueueGlobal;
   
       var $wtvr_message_queue_id;
   var $wtvr_message_recipient;
   var $wtvr_message_sender;
   var $wtvr_message_recipient_fname;
   var $wtvr_message_recipient_lname;
   var $wtvr_message_sender_fname;
   var $wtvr_message_sender_lname;
   var $wtvr_message_subject;
   var $wtvr_message_body;
   var $wtvr_message_text;
   var $wtvr_message_created;
   var $wtvr_message_sent;
   var $wtvr_message_type;
   var $wtvr_message_response;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getWtvrMessageQueueId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->WtvrMessageQueueGlobal = WtvrMessageQueueGlobalPeer::retrieveByPK( $id );
    } else {
      $this ->WtvrMessageQueueGlobal = new WtvrMessageQueueGlobal;
    }
  }
  
  function hydrate( $id ) {
      $this ->WtvrMessageQueueGlobal = WtvrMessageQueueGlobalPeer::retrieveByPK( $id );
  }
  
  function getWtvrMessageQueueId() {
    if (($this ->postVar("wtvr_message_queue_id")) || ($this ->postVar("wtvr_message_queue_id") === "")) {
      return $this ->postVar("wtvr_message_queue_id");
    } elseif (($this ->getVar("wtvr_message_queue_id")) || ($this ->getVar("wtvr_message_queue_id") === "")) {
      return $this ->getVar("wtvr_message_queue_id");
    } elseif (($this ->WtvrMessageQueueGlobal) || ($this ->WtvrMessageQueueGlobal === "")){
      return $this ->WtvrMessageQueueGlobal -> getWtvrMessageQueueId();
    } elseif (($this ->sessionVar("wtvr_message_queue_id")) || ($this ->sessionVar("wtvr_message_queue_id") == "")) {
      return $this ->sessionVar("wtvr_message_queue_id");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageQueueId( $str ) {
    $this ->WtvrMessageQueueGlobal -> setWtvrMessageQueueId( $str );
  }
  
  function getWtvrMessageRecipient() {
    if (($this ->postVar("wtvr_message_recipient")) || ($this ->postVar("wtvr_message_recipient") === "")) {
      return $this ->postVar("wtvr_message_recipient");
    } elseif (($this ->getVar("wtvr_message_recipient")) || ($this ->getVar("wtvr_message_recipient") === "")) {
      return $this ->getVar("wtvr_message_recipient");
    } elseif (($this ->WtvrMessageQueueGlobal) || ($this ->WtvrMessageQueueGlobal === "")){
      return $this ->WtvrMessageQueueGlobal -> getWtvrMessageRecipient();
    } elseif (($this ->sessionVar("wtvr_message_recipient")) || ($this ->sessionVar("wtvr_message_recipient") == "")) {
      return $this ->sessionVar("wtvr_message_recipient");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageRecipient( $str ) {
    $this ->WtvrMessageQueueGlobal -> setWtvrMessageRecipient( $str );
  }
  
  function getWtvrMessageSender() {
    if (($this ->postVar("wtvr_message_sender")) || ($this ->postVar("wtvr_message_sender") === "")) {
      return $this ->postVar("wtvr_message_sender");
    } elseif (($this ->getVar("wtvr_message_sender")) || ($this ->getVar("wtvr_message_sender") === "")) {
      return $this ->getVar("wtvr_message_sender");
    } elseif (($this ->WtvrMessageQueueGlobal) || ($this ->WtvrMessageQueueGlobal === "")){
      return $this ->WtvrMessageQueueGlobal -> getWtvrMessageSender();
    } elseif (($this ->sessionVar("wtvr_message_sender")) || ($this ->sessionVar("wtvr_message_sender") == "")) {
      return $this ->sessionVar("wtvr_message_sender");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageSender( $str ) {
    $this ->WtvrMessageQueueGlobal -> setWtvrMessageSender( $str );
  }
  
  function getWtvrMessageRecipientFname() {
    if (($this ->postVar("wtvr_message_recipient_fname")) || ($this ->postVar("wtvr_message_recipient_fname") === "")) {
      return $this ->postVar("wtvr_message_recipient_fname");
    } elseif (($this ->getVar("wtvr_message_recipient_fname")) || ($this ->getVar("wtvr_message_recipient_fname") === "")) {
      return $this ->getVar("wtvr_message_recipient_fname");
    } elseif (($this ->WtvrMessageQueueGlobal) || ($this ->WtvrMessageQueueGlobal === "")){
      return $this ->WtvrMessageQueueGlobal -> getWtvrMessageRecipientFname();
    } elseif (($this ->sessionVar("wtvr_message_recipient_fname")) || ($this ->sessionVar("wtvr_message_recipient_fname") == "")) {
      return $this ->sessionVar("wtvr_message_recipient_fname");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageRecipientFname( $str ) {
    $this ->WtvrMessageQueueGlobal -> setWtvrMessageRecipientFname( $str );
  }
  
  function getWtvrMessageRecipientLname() {
    if (($this ->postVar("wtvr_message_recipient_lname")) || ($this ->postVar("wtvr_message_recipient_lname") === "")) {
      return $this ->postVar("wtvr_message_recipient_lname");
    } elseif (($this ->getVar("wtvr_message_recipient_lname")) || ($this ->getVar("wtvr_message_recipient_lname") === "")) {
      return $this ->getVar("wtvr_message_recipient_lname");
    } elseif (($this ->WtvrMessageQueueGlobal) || ($this ->WtvrMessageQueueGlobal === "")){
      return $this ->WtvrMessageQueueGlobal -> getWtvrMessageRecipientLname();
    } elseif (($this ->sessionVar("wtvr_message_recipient_lname")) || ($this ->sessionVar("wtvr_message_recipient_lname") == "")) {
      return $this ->sessionVar("wtvr_message_recipient_lname");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageRecipientLname( $str ) {
    $this ->WtvrMessageQueueGlobal -> setWtvrMessageRecipientLname( $str );
  }
  
  function getWtvrMessageSenderFname() {
    if (($this ->postVar("wtvr_message_sender_fname")) || ($this ->postVar("wtvr_message_sender_fname") === "")) {
      return $this ->postVar("wtvr_message_sender_fname");
    } elseif (($this ->getVar("wtvr_message_sender_fname")) || ($this ->getVar("wtvr_message_sender_fname") === "")) {
      return $this ->getVar("wtvr_message_sender_fname");
    } elseif (($this ->WtvrMessageQueueGlobal) || ($this ->WtvrMessageQueueGlobal === "")){
      return $this ->WtvrMessageQueueGlobal -> getWtvrMessageSenderFname();
    } elseif (($this ->sessionVar("wtvr_message_sender_fname")) || ($this ->sessionVar("wtvr_message_sender_fname") == "")) {
      return $this ->sessionVar("wtvr_message_sender_fname");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageSenderFname( $str ) {
    $this ->WtvrMessageQueueGlobal -> setWtvrMessageSenderFname( $str );
  }
  
  function getWtvrMessageSenderLname() {
    if (($this ->postVar("wtvr_message_sender_lname")) || ($this ->postVar("wtvr_message_sender_lname") === "")) {
      return $this ->postVar("wtvr_message_sender_lname");
    } elseif (($this ->getVar("wtvr_message_sender_lname")) || ($this ->getVar("wtvr_message_sender_lname") === "")) {
      return $this ->getVar("wtvr_message_sender_lname");
    } elseif (($this ->WtvrMessageQueueGlobal) || ($this ->WtvrMessageQueueGlobal === "")){
      return $this ->WtvrMessageQueueGlobal -> getWtvrMessageSenderLname();
    } elseif (($this ->sessionVar("wtvr_message_sender_lname")) || ($this ->sessionVar("wtvr_message_sender_lname") == "")) {
      return $this ->sessionVar("wtvr_message_sender_lname");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageSenderLname( $str ) {
    $this ->WtvrMessageQueueGlobal -> setWtvrMessageSenderLname( $str );
  }
  
  function getWtvrMessageSubject() {
    if (($this ->postVar("wtvr_message_subject")) || ($this ->postVar("wtvr_message_subject") === "")) {
      return $this ->postVar("wtvr_message_subject");
    } elseif (($this ->getVar("wtvr_message_subject")) || ($this ->getVar("wtvr_message_subject") === "")) {
      return $this ->getVar("wtvr_message_subject");
    } elseif (($this ->WtvrMessageQueueGlobal) || ($this ->WtvrMessageQueueGlobal === "")){
      return $this ->WtvrMessageQueueGlobal -> getWtvrMessageSubject();
    } elseif (($this ->sessionVar("wtvr_message_subject")) || ($this ->sessionVar("wtvr_message_subject") == "")) {
      return $this ->sessionVar("wtvr_message_subject");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageSubject( $str ) {
    $this ->WtvrMessageQueueGlobal -> setWtvrMessageSubject( $str );
  }
  
  function getWtvrMessageBody() {
    if (($this ->postVar("wtvr_message_body")) || ($this ->postVar("wtvr_message_body") === "")) {
      return $this ->postVar("wtvr_message_body");
    } elseif (($this ->getVar("wtvr_message_body")) || ($this ->getVar("wtvr_message_body") === "")) {
      return $this ->getVar("wtvr_message_body");
    } elseif (($this ->WtvrMessageQueueGlobal) || ($this ->WtvrMessageQueueGlobal === "")){
      return $this ->WtvrMessageQueueGlobal -> getWtvrMessageBody();
    } elseif (($this ->sessionVar("wtvr_message_body")) || ($this ->sessionVar("wtvr_message_body") == "")) {
      return $this ->sessionVar("wtvr_message_body");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageBody( $str ) {
    $this ->WtvrMessageQueueGlobal -> setWtvrMessageBody( $str );
  }
  
  function getWtvrMessageText() {
    if (($this ->postVar("wtvr_message_text")) || ($this ->postVar("wtvr_message_text") === "")) {
      return $this ->postVar("wtvr_message_text");
    } elseif (($this ->getVar("wtvr_message_text")) || ($this ->getVar("wtvr_message_text") === "")) {
      return $this ->getVar("wtvr_message_text");
    } elseif (($this ->WtvrMessageQueueGlobal) || ($this ->WtvrMessageQueueGlobal === "")){
      return $this ->WtvrMessageQueueGlobal -> getWtvrMessageText();
    } elseif (($this ->sessionVar("wtvr_message_text")) || ($this ->sessionVar("wtvr_message_text") == "")) {
      return $this ->sessionVar("wtvr_message_text");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageText( $str ) {
    $this ->WtvrMessageQueueGlobal -> setWtvrMessageText( $str );
  }
  
  function getWtvrMessageCreated() {
    if (($this ->postVar("wtvr_message_created")) || ($this ->postVar("wtvr_message_created") === "")) {
      return $this ->postVar("wtvr_message_created");
    } elseif (($this ->getVar("wtvr_message_created")) || ($this ->getVar("wtvr_message_created") === "")) {
      return $this ->getVar("wtvr_message_created");
    } elseif (($this ->WtvrMessageQueueGlobal) || ($this ->WtvrMessageQueueGlobal === "")){
      return $this ->WtvrMessageQueueGlobal -> getWtvrMessageCreated();
    } elseif (($this ->sessionVar("wtvr_message_created")) || ($this ->sessionVar("wtvr_message_created") == "")) {
      return $this ->sessionVar("wtvr_message_created");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageCreated( $str ) {
    $this ->WtvrMessageQueueGlobal -> setWtvrMessageCreated( $str );
  }
  
  function getWtvrMessageSent() {
    if (($this ->postVar("wtvr_message_sent")) || ($this ->postVar("wtvr_message_sent") === "")) {
      return $this ->postVar("wtvr_message_sent");
    } elseif (($this ->getVar("wtvr_message_sent")) || ($this ->getVar("wtvr_message_sent") === "")) {
      return $this ->getVar("wtvr_message_sent");
    } elseif (($this ->WtvrMessageQueueGlobal) || ($this ->WtvrMessageQueueGlobal === "")){
      return $this ->WtvrMessageQueueGlobal -> getWtvrMessageSent();
    } elseif (($this ->sessionVar("wtvr_message_sent")) || ($this ->sessionVar("wtvr_message_sent") == "")) {
      return $this ->sessionVar("wtvr_message_sent");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageSent( $str ) {
    $this ->WtvrMessageQueueGlobal -> setWtvrMessageSent( $str );
  }
  
  function getWtvrMessageType() {
    if (($this ->postVar("wtvr_message_type")) || ($this ->postVar("wtvr_message_type") === "")) {
      return $this ->postVar("wtvr_message_type");
    } elseif (($this ->getVar("wtvr_message_type")) || ($this ->getVar("wtvr_message_type") === "")) {
      return $this ->getVar("wtvr_message_type");
    } elseif (($this ->WtvrMessageQueueGlobal) || ($this ->WtvrMessageQueueGlobal === "")){
      return $this ->WtvrMessageQueueGlobal -> getWtvrMessageType();
    } elseif (($this ->sessionVar("wtvr_message_type")) || ($this ->sessionVar("wtvr_message_type") == "")) {
      return $this ->sessionVar("wtvr_message_type");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageType( $str ) {
    $this ->WtvrMessageQueueGlobal -> setWtvrMessageType( $str );
  }
  
  function getWtvrMessageResponse() {
    if (($this ->postVar("wtvr_message_response")) || ($this ->postVar("wtvr_message_response") === "")) {
      return $this ->postVar("wtvr_message_response");
    } elseif (($this ->getVar("wtvr_message_response")) || ($this ->getVar("wtvr_message_response") === "")) {
      return $this ->getVar("wtvr_message_response");
    } elseif (($this ->WtvrMessageQueueGlobal) || ($this ->WtvrMessageQueueGlobal === "")){
      return $this ->WtvrMessageQueueGlobal -> getWtvrMessageResponse();
    } elseif (($this ->sessionVar("wtvr_message_response")) || ($this ->sessionVar("wtvr_message_response") == "")) {
      return $this ->sessionVar("wtvr_message_response");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageResponse( $str ) {
    $this ->WtvrMessageQueueGlobal -> setWtvrMessageResponse( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->WtvrMessageQueueGlobal = WtvrMessageQueueGlobalPeer::retrieveByPK( $id );
    }
    
    if ($this ->WtvrMessageQueueGlobal ) {
       
    	       (is_numeric(WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageQueueId()))) ? $itemarray["wtvr_message_queue_id"] = WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageQueueId()) : null;
          (WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageRecipient())) ? $itemarray["wtvr_message_recipient"] = WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageRecipient()) : null;
          (WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageSender())) ? $itemarray["wtvr_message_sender"] = WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageSender()) : null;
          (WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageRecipientFname())) ? $itemarray["wtvr_message_recipient_fname"] = WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageRecipientFname()) : null;
          (WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageRecipientLname())) ? $itemarray["wtvr_message_recipient_lname"] = WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageRecipientLname()) : null;
          (WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageSenderFname())) ? $itemarray["wtvr_message_sender_fname"] = WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageSenderFname()) : null;
          (WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageSenderLname())) ? $itemarray["wtvr_message_sender_lname"] = WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageSenderLname()) : null;
          (WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageSubject())) ? $itemarray["wtvr_message_subject"] = WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageSubject()) : null;
          (WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageBody())) ? $itemarray["wtvr_message_body"] = WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageBody()) : null;
          (WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageText())) ? $itemarray["wtvr_message_text"] = WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageText()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageCreated())) ? $itemarray["wtvr_message_created"] = formatDate($this ->WtvrMessageQueueGlobal->getWtvrMessageCreated('%Y-%m-%d %T'),"TS") : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageSent())) ? $itemarray["wtvr_message_sent"] = formatDate($this ->WtvrMessageQueueGlobal->getWtvrMessageSent('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageType())) ? $itemarray["wtvr_message_type"] = WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageType()) : null;
          (WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageResponse())) ? $itemarray["wtvr_message_response"] = WTVRcleanString($this ->WtvrMessageQueueGlobal->getWtvrMessageResponse()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->WtvrMessageQueueGlobal = WtvrMessageQueueGlobalPeer::retrieveByPK( $id );
    } elseif (! $this ->WtvrMessageQueueGlobal) {
      $this ->WtvrMessageQueueGlobal = new WtvrMessageQueueGlobal;
    }
        
  	 ($this -> getWtvrMessageQueueId())? $this ->WtvrMessageQueueGlobal->setWtvrMessageQueueId( WTVRcleanString( $this -> getWtvrMessageQueueId()) ) : null;
    ($this -> getWtvrMessageRecipient())? $this ->WtvrMessageQueueGlobal->setWtvrMessageRecipient( WTVRcleanString( $this -> getWtvrMessageRecipient()) ) : null;
    ($this -> getWtvrMessageSender())? $this ->WtvrMessageQueueGlobal->setWtvrMessageSender( WTVRcleanString( $this -> getWtvrMessageSender()) ) : null;
    ($this -> getWtvrMessageRecipientFname())? $this ->WtvrMessageQueueGlobal->setWtvrMessageRecipientFname( WTVRcleanString( $this -> getWtvrMessageRecipientFname()) ) : null;
    ($this -> getWtvrMessageRecipientLname())? $this ->WtvrMessageQueueGlobal->setWtvrMessageRecipientLname( WTVRcleanString( $this -> getWtvrMessageRecipientLname()) ) : null;
    ($this -> getWtvrMessageSenderFname())? $this ->WtvrMessageQueueGlobal->setWtvrMessageSenderFname( WTVRcleanString( $this -> getWtvrMessageSenderFname()) ) : null;
    ($this -> getWtvrMessageSenderLname())? $this ->WtvrMessageQueueGlobal->setWtvrMessageSenderLname( WTVRcleanString( $this -> getWtvrMessageSenderLname()) ) : null;
    ($this -> getWtvrMessageSubject())? $this ->WtvrMessageQueueGlobal->setWtvrMessageSubject( WTVRcleanString( $this -> getWtvrMessageSubject()) ) : null;
    ($this -> getWtvrMessageBody())? $this ->WtvrMessageQueueGlobal->setWtvrMessageBody( WTVRcleanString( $this -> getWtvrMessageBody()) ) : null;
    ($this -> getWtvrMessageText())? $this ->WtvrMessageQueueGlobal->setWtvrMessageText( WTVRcleanString( $this -> getWtvrMessageText()) ) : null;
          if (is_valid_date( $this ->WtvrMessageQueueGlobal->getWtvrMessageCreated())) {
        $this ->WtvrMessageQueueGlobal->setWtvrMessageCreated( formatDate($this -> getWtvrMessageCreated(), "TS" ));
      } else {
      $WtvrMessageQueueGlobalwtvr_message_created = $this -> sfDateTime( "wtvr_message_created" );
      ( $WtvrMessageQueueGlobalwtvr_message_created != "01/01/1900 00:00:00" )? $this ->WtvrMessageQueueGlobal->setWtvrMessageCreated( formatDate($WtvrMessageQueueGlobalwtvr_message_created, "TS" )) : $this ->WtvrMessageQueueGlobal->setWtvrMessageCreated( null );
      }
          if (is_valid_date( $this ->WtvrMessageQueueGlobal->getWtvrMessageSent())) {
        $this ->WtvrMessageQueueGlobal->setWtvrMessageSent( formatDate($this -> getWtvrMessageSent(), "TS" ));
      } else {
      $WtvrMessageQueueGlobalwtvr_message_sent = $this -> sfDateTime( "wtvr_message_sent" );
      ( $WtvrMessageQueueGlobalwtvr_message_sent != "01/01/1900 00:00:00" )? $this ->WtvrMessageQueueGlobal->setWtvrMessageSent( formatDate($WtvrMessageQueueGlobalwtvr_message_sent, "TS" )) : $this ->WtvrMessageQueueGlobal->setWtvrMessageSent( null );
      }
    ($this -> getWtvrMessageType())? $this ->WtvrMessageQueueGlobal->setWtvrMessageType( WTVRcleanString( $this -> getWtvrMessageType()) ) : null;
    ($this -> getWtvrMessageResponse())? $this ->WtvrMessageQueueGlobal->setWtvrMessageResponse( WTVRcleanString( $this -> getWtvrMessageResponse()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->WtvrMessageQueueGlobal ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->WtvrMessageQueueGlobal = WtvrMessageQueueGlobalPeer::retrieveByPK($id);
    }
    
    if (! $this ->WtvrMessageQueueGlobal ) {
      return;
    }
    
    $this ->WtvrMessageQueueGlobal -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('WtvrMessageQueueGlobal_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "WtvrMessageQueueGlobalPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $WtvrMessageQueueGlobal = WtvrMessageQueueGlobalPeer::doSelect($c);
    
    if (count($WtvrMessageQueueGlobal) >= 1) {
      $this ->WtvrMessageQueueGlobal = $WtvrMessageQueueGlobal[0];
      return true;
    } else {
      $this ->WtvrMessageQueueGlobal = new WtvrMessageQueueGlobal();
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
      $name = "WtvrMessageQueueGlobalPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $WtvrMessageQueueGlobal = WtvrMessageQueueGlobalPeer::doSelect($c);
    
    if (count($WtvrMessageQueueGlobal) >= 1) {
      $this ->WtvrMessageQueueGlobal = $WtvrMessageQueueGlobal[0];
      return true;
    } else {
      $this ->WtvrMessageQueueGlobal = new WtvrMessageQueueGlobal();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>