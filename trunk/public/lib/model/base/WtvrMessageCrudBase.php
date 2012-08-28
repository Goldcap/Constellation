<?php
       
   class WtvrMessageCrudBase extends Utils_PageWidget { 
   
    var $WtvrMessage;
   
       var $wtvr_message_id;
   var $wtvr_message_identifier;
   var $wtvr_message_scope;
   var $wtvr_message_send_user_id;
   var $wtvr_message_send_email;
   var $wtvr_message_send_fname;
   var $wtvr_message_send_lname;
   var $wtvr_message_subject;
   var $wtvr_message_body;
   var $wtvr_message_text;
   var $wtvr_message_date;
   var $wtvr_message_template;
   var $wtvr_message_key1;
   var $wtvr_message_key2;
   var $wtvr_message_key3;
   var $wtvr_message_key4;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getWtvrMessageId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->WtvrMessage = WtvrMessagePeer::retrieveByPK( $id );
    } else {
      $this ->WtvrMessage = new WtvrMessage;
    }
  }
  
  function hydrate( $id ) {
      $this ->WtvrMessage = WtvrMessagePeer::retrieveByPK( $id );
  }
  
  function getWtvrMessageId() {
    if (($this ->postVar("wtvr_message_id")) || ($this ->postVar("wtvr_message_id") === "")) {
      return $this ->postVar("wtvr_message_id");
    } elseif (($this ->getVar("wtvr_message_id")) || ($this ->getVar("wtvr_message_id") === "")) {
      return $this ->getVar("wtvr_message_id");
    } elseif (($this ->WtvrMessage) || ($this ->WtvrMessage === "")){
      return $this ->WtvrMessage -> getWtvrMessageId();
    } elseif (($this ->sessionVar("wtvr_message_id")) || ($this ->sessionVar("wtvr_message_id") == "")) {
      return $this ->sessionVar("wtvr_message_id");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageId( $str ) {
    $this ->WtvrMessage -> setWtvrMessageId( $str );
  }
  
  function getWtvrMessageIdentifier() {
    if (($this ->postVar("wtvr_message_identifier")) || ($this ->postVar("wtvr_message_identifier") === "")) {
      return $this ->postVar("wtvr_message_identifier");
    } elseif (($this ->getVar("wtvr_message_identifier")) || ($this ->getVar("wtvr_message_identifier") === "")) {
      return $this ->getVar("wtvr_message_identifier");
    } elseif (($this ->WtvrMessage) || ($this ->WtvrMessage === "")){
      return $this ->WtvrMessage -> getWtvrMessageIdentifier();
    } elseif (($this ->sessionVar("wtvr_message_identifier")) || ($this ->sessionVar("wtvr_message_identifier") == "")) {
      return $this ->sessionVar("wtvr_message_identifier");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageIdentifier( $str ) {
    $this ->WtvrMessage -> setWtvrMessageIdentifier( $str );
  }
  
  function getWtvrMessageScope() {
    if (($this ->postVar("wtvr_message_scope")) || ($this ->postVar("wtvr_message_scope") === "")) {
      return $this ->postVar("wtvr_message_scope");
    } elseif (($this ->getVar("wtvr_message_scope")) || ($this ->getVar("wtvr_message_scope") === "")) {
      return $this ->getVar("wtvr_message_scope");
    } elseif (($this ->WtvrMessage) || ($this ->WtvrMessage === "")){
      return $this ->WtvrMessage -> getWtvrMessageScope();
    } elseif (($this ->sessionVar("wtvr_message_scope")) || ($this ->sessionVar("wtvr_message_scope") == "")) {
      return $this ->sessionVar("wtvr_message_scope");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageScope( $str ) {
    $this ->WtvrMessage -> setWtvrMessageScope( $str );
  }
  
  function getWtvrMessageSendUserId() {
    if (($this ->postVar("wtvr_message_send_user_id")) || ($this ->postVar("wtvr_message_send_user_id") === "")) {
      return $this ->postVar("wtvr_message_send_user_id");
    } elseif (($this ->getVar("wtvr_message_send_user_id")) || ($this ->getVar("wtvr_message_send_user_id") === "")) {
      return $this ->getVar("wtvr_message_send_user_id");
    } elseif (($this ->WtvrMessage) || ($this ->WtvrMessage === "")){
      return $this ->WtvrMessage -> getWtvrMessageSendUserId();
    } elseif (($this ->sessionVar("wtvr_message_send_user_id")) || ($this ->sessionVar("wtvr_message_send_user_id") == "")) {
      return $this ->sessionVar("wtvr_message_send_user_id");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageSendUserId( $str ) {
    $this ->WtvrMessage -> setWtvrMessageSendUserId( $str );
  }
  
  function getWtvrMessageSendEmail() {
    if (($this ->postVar("wtvr_message_send_email")) || ($this ->postVar("wtvr_message_send_email") === "")) {
      return $this ->postVar("wtvr_message_send_email");
    } elseif (($this ->getVar("wtvr_message_send_email")) || ($this ->getVar("wtvr_message_send_email") === "")) {
      return $this ->getVar("wtvr_message_send_email");
    } elseif (($this ->WtvrMessage) || ($this ->WtvrMessage === "")){
      return $this ->WtvrMessage -> getWtvrMessageSendEmail();
    } elseif (($this ->sessionVar("wtvr_message_send_email")) || ($this ->sessionVar("wtvr_message_send_email") == "")) {
      return $this ->sessionVar("wtvr_message_send_email");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageSendEmail( $str ) {
    $this ->WtvrMessage -> setWtvrMessageSendEmail( $str );
  }
  
  function getWtvrMessageSendFname() {
    if (($this ->postVar("wtvr_message_send_fname")) || ($this ->postVar("wtvr_message_send_fname") === "")) {
      return $this ->postVar("wtvr_message_send_fname");
    } elseif (($this ->getVar("wtvr_message_send_fname")) || ($this ->getVar("wtvr_message_send_fname") === "")) {
      return $this ->getVar("wtvr_message_send_fname");
    } elseif (($this ->WtvrMessage) || ($this ->WtvrMessage === "")){
      return $this ->WtvrMessage -> getWtvrMessageSendFname();
    } elseif (($this ->sessionVar("wtvr_message_send_fname")) || ($this ->sessionVar("wtvr_message_send_fname") == "")) {
      return $this ->sessionVar("wtvr_message_send_fname");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageSendFname( $str ) {
    $this ->WtvrMessage -> setWtvrMessageSendFname( $str );
  }
  
  function getWtvrMessageSendLname() {
    if (($this ->postVar("wtvr_message_send_lname")) || ($this ->postVar("wtvr_message_send_lname") === "")) {
      return $this ->postVar("wtvr_message_send_lname");
    } elseif (($this ->getVar("wtvr_message_send_lname")) || ($this ->getVar("wtvr_message_send_lname") === "")) {
      return $this ->getVar("wtvr_message_send_lname");
    } elseif (($this ->WtvrMessage) || ($this ->WtvrMessage === "")){
      return $this ->WtvrMessage -> getWtvrMessageSendLname();
    } elseif (($this ->sessionVar("wtvr_message_send_lname")) || ($this ->sessionVar("wtvr_message_send_lname") == "")) {
      return $this ->sessionVar("wtvr_message_send_lname");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageSendLname( $str ) {
    $this ->WtvrMessage -> setWtvrMessageSendLname( $str );
  }
  
  function getWtvrMessageSubject() {
    if (($this ->postVar("wtvr_message_subject")) || ($this ->postVar("wtvr_message_subject") === "")) {
      return $this ->postVar("wtvr_message_subject");
    } elseif (($this ->getVar("wtvr_message_subject")) || ($this ->getVar("wtvr_message_subject") === "")) {
      return $this ->getVar("wtvr_message_subject");
    } elseif (($this ->WtvrMessage) || ($this ->WtvrMessage === "")){
      return $this ->WtvrMessage -> getWtvrMessageSubject();
    } elseif (($this ->sessionVar("wtvr_message_subject")) || ($this ->sessionVar("wtvr_message_subject") == "")) {
      return $this ->sessionVar("wtvr_message_subject");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageSubject( $str ) {
    $this ->WtvrMessage -> setWtvrMessageSubject( $str );
  }
  
  function getWtvrMessageBody() {
    if (($this ->postVar("wtvr_message_body")) || ($this ->postVar("wtvr_message_body") === "")) {
      return $this ->postVar("wtvr_message_body");
    } elseif (($this ->getVar("wtvr_message_body")) || ($this ->getVar("wtvr_message_body") === "")) {
      return $this ->getVar("wtvr_message_body");
    } elseif (($this ->WtvrMessage) || ($this ->WtvrMessage === "")){
      return $this ->WtvrMessage -> getWtvrMessageBody();
    } elseif (($this ->sessionVar("wtvr_message_body")) || ($this ->sessionVar("wtvr_message_body") == "")) {
      return $this ->sessionVar("wtvr_message_body");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageBody( $str ) {
    $this ->WtvrMessage -> setWtvrMessageBody( $str );
  }
  
  function getWtvrMessageText() {
    if (($this ->postVar("wtvr_message_text")) || ($this ->postVar("wtvr_message_text") === "")) {
      return $this ->postVar("wtvr_message_text");
    } elseif (($this ->getVar("wtvr_message_text")) || ($this ->getVar("wtvr_message_text") === "")) {
      return $this ->getVar("wtvr_message_text");
    } elseif (($this ->WtvrMessage) || ($this ->WtvrMessage === "")){
      return $this ->WtvrMessage -> getWtvrMessageText();
    } elseif (($this ->sessionVar("wtvr_message_text")) || ($this ->sessionVar("wtvr_message_text") == "")) {
      return $this ->sessionVar("wtvr_message_text");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageText( $str ) {
    $this ->WtvrMessage -> setWtvrMessageText( $str );
  }
  
  function getWtvrMessageDate() {
    if (($this ->postVar("wtvr_message_date")) || ($this ->postVar("wtvr_message_date") === "")) {
      return $this ->postVar("wtvr_message_date");
    } elseif (($this ->getVar("wtvr_message_date")) || ($this ->getVar("wtvr_message_date") === "")) {
      return $this ->getVar("wtvr_message_date");
    } elseif (($this ->WtvrMessage) || ($this ->WtvrMessage === "")){
      return $this ->WtvrMessage -> getWtvrMessageDate();
    } elseif (($this ->sessionVar("wtvr_message_date")) || ($this ->sessionVar("wtvr_message_date") == "")) {
      return $this ->sessionVar("wtvr_message_date");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageDate( $str ) {
    $this ->WtvrMessage -> setWtvrMessageDate( $str );
  }
  
  function getWtvrMessageTemplate() {
    if (($this ->postVar("wtvr_message_template")) || ($this ->postVar("wtvr_message_template") === "")) {
      return $this ->postVar("wtvr_message_template");
    } elseif (($this ->getVar("wtvr_message_template")) || ($this ->getVar("wtvr_message_template") === "")) {
      return $this ->getVar("wtvr_message_template");
    } elseif (($this ->WtvrMessage) || ($this ->WtvrMessage === "")){
      return $this ->WtvrMessage -> getWtvrMessageTemplate();
    } elseif (($this ->sessionVar("wtvr_message_template")) || ($this ->sessionVar("wtvr_message_template") == "")) {
      return $this ->sessionVar("wtvr_message_template");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageTemplate( $str ) {
    $this ->WtvrMessage -> setWtvrMessageTemplate( $str );
  }
  
  function getWtvrMessageKey1() {
    if (($this ->postVar("wtvr_message_key1")) || ($this ->postVar("wtvr_message_key1") === "")) {
      return $this ->postVar("wtvr_message_key1");
    } elseif (($this ->getVar("wtvr_message_key1")) || ($this ->getVar("wtvr_message_key1") === "")) {
      return $this ->getVar("wtvr_message_key1");
    } elseif (($this ->WtvrMessage) || ($this ->WtvrMessage === "")){
      return $this ->WtvrMessage -> getWtvrMessageKey1();
    } elseif (($this ->sessionVar("wtvr_message_key1")) || ($this ->sessionVar("wtvr_message_key1") == "")) {
      return $this ->sessionVar("wtvr_message_key1");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageKey1( $str ) {
    $this ->WtvrMessage -> setWtvrMessageKey1( $str );
  }
  
  function getWtvrMessageKey2() {
    if (($this ->postVar("wtvr_message_key2")) || ($this ->postVar("wtvr_message_key2") === "")) {
      return $this ->postVar("wtvr_message_key2");
    } elseif (($this ->getVar("wtvr_message_key2")) || ($this ->getVar("wtvr_message_key2") === "")) {
      return $this ->getVar("wtvr_message_key2");
    } elseif (($this ->WtvrMessage) || ($this ->WtvrMessage === "")){
      return $this ->WtvrMessage -> getWtvrMessageKey2();
    } elseif (($this ->sessionVar("wtvr_message_key2")) || ($this ->sessionVar("wtvr_message_key2") == "")) {
      return $this ->sessionVar("wtvr_message_key2");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageKey2( $str ) {
    $this ->WtvrMessage -> setWtvrMessageKey2( $str );
  }
  
  function getWtvrMessageKey3() {
    if (($this ->postVar("wtvr_message_key3")) || ($this ->postVar("wtvr_message_key3") === "")) {
      return $this ->postVar("wtvr_message_key3");
    } elseif (($this ->getVar("wtvr_message_key3")) || ($this ->getVar("wtvr_message_key3") === "")) {
      return $this ->getVar("wtvr_message_key3");
    } elseif (($this ->WtvrMessage) || ($this ->WtvrMessage === "")){
      return $this ->WtvrMessage -> getWtvrMessageKey3();
    } elseif (($this ->sessionVar("wtvr_message_key3")) || ($this ->sessionVar("wtvr_message_key3") == "")) {
      return $this ->sessionVar("wtvr_message_key3");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageKey3( $str ) {
    $this ->WtvrMessage -> setWtvrMessageKey3( $str );
  }
  
  function getWtvrMessageKey4() {
    if (($this ->postVar("wtvr_message_key4")) || ($this ->postVar("wtvr_message_key4") === "")) {
      return $this ->postVar("wtvr_message_key4");
    } elseif (($this ->getVar("wtvr_message_key4")) || ($this ->getVar("wtvr_message_key4") === "")) {
      return $this ->getVar("wtvr_message_key4");
    } elseif (($this ->WtvrMessage) || ($this ->WtvrMessage === "")){
      return $this ->WtvrMessage -> getWtvrMessageKey4();
    } elseif (($this ->sessionVar("wtvr_message_key4")) || ($this ->sessionVar("wtvr_message_key4") == "")) {
      return $this ->sessionVar("wtvr_message_key4");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageKey4( $str ) {
    $this ->WtvrMessage -> setWtvrMessageKey4( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->WtvrMessage = WtvrMessagePeer::retrieveByPK( $id );
    }
    
    if ($this ->WtvrMessage ) {
       
    	       (is_numeric(WTVRcleanString($this ->WtvrMessage->getWtvrMessageId()))) ? $itemarray["wtvr_message_id"] = WTVRcleanString($this ->WtvrMessage->getWtvrMessageId()) : null;
          (WTVRcleanString($this ->WtvrMessage->getWtvrMessageIdentifier())) ? $itemarray["wtvr_message_identifier"] = WTVRcleanString($this ->WtvrMessage->getWtvrMessageIdentifier()) : null;
          (WTVRcleanString($this ->WtvrMessage->getWtvrMessageScope())) ? $itemarray["wtvr_message_scope"] = WTVRcleanString($this ->WtvrMessage->getWtvrMessageScope()) : null;
          (is_numeric(WTVRcleanString($this ->WtvrMessage->getWtvrMessageSendUserId()))) ? $itemarray["wtvr_message_send_user_id"] = WTVRcleanString($this ->WtvrMessage->getWtvrMessageSendUserId()) : null;
          (WTVRcleanString($this ->WtvrMessage->getWtvrMessageSendEmail())) ? $itemarray["wtvr_message_send_email"] = WTVRcleanString($this ->WtvrMessage->getWtvrMessageSendEmail()) : null;
          (WTVRcleanString($this ->WtvrMessage->getWtvrMessageSendFname())) ? $itemarray["wtvr_message_send_fname"] = WTVRcleanString($this ->WtvrMessage->getWtvrMessageSendFname()) : null;
          (WTVRcleanString($this ->WtvrMessage->getWtvrMessageSendLname())) ? $itemarray["wtvr_message_send_lname"] = WTVRcleanString($this ->WtvrMessage->getWtvrMessageSendLname()) : null;
          (WTVRcleanString($this ->WtvrMessage->getWtvrMessageSubject())) ? $itemarray["wtvr_message_subject"] = WTVRcleanString($this ->WtvrMessage->getWtvrMessageSubject()) : null;
          (WTVRcleanString($this ->WtvrMessage->getWtvrMessageBody())) ? $itemarray["wtvr_message_body"] = WTVRcleanString($this ->WtvrMessage->getWtvrMessageBody()) : null;
          (WTVRcleanString($this ->WtvrMessage->getWtvrMessageText())) ? $itemarray["wtvr_message_text"] = WTVRcleanString($this ->WtvrMessage->getWtvrMessageText()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->WtvrMessage->getWtvrMessageDate())) ? $itemarray["wtvr_message_date"] = formatDate($this ->WtvrMessage->getWtvrMessageDate('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->WtvrMessage->getWtvrMessageTemplate())) ? $itemarray["wtvr_message_template"] = WTVRcleanString($this ->WtvrMessage->getWtvrMessageTemplate()) : null;
          (is_numeric(WTVRcleanString($this ->WtvrMessage->getWtvrMessageKey1()))) ? $itemarray["wtvr_message_key1"] = WTVRcleanString($this ->WtvrMessage->getWtvrMessageKey1()) : null;
          (is_numeric(WTVRcleanString($this ->WtvrMessage->getWtvrMessageKey2()))) ? $itemarray["wtvr_message_key2"] = WTVRcleanString($this ->WtvrMessage->getWtvrMessageKey2()) : null;
          (is_numeric(WTVRcleanString($this ->WtvrMessage->getWtvrMessageKey3()))) ? $itemarray["wtvr_message_key3"] = WTVRcleanString($this ->WtvrMessage->getWtvrMessageKey3()) : null;
          (is_numeric(WTVRcleanString($this ->WtvrMessage->getWtvrMessageKey4()))) ? $itemarray["wtvr_message_key4"] = WTVRcleanString($this ->WtvrMessage->getWtvrMessageKey4()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->WtvrMessage = WtvrMessagePeer::retrieveByPK( $id );
    } elseif (! $this ->WtvrMessage) {
      $this ->WtvrMessage = new WtvrMessage;
    }
        
  	 ($this -> getWtvrMessageId())? $this ->WtvrMessage->setWtvrMessageId( WTVRcleanString( $this -> getWtvrMessageId()) ) : null;
    ($this -> getWtvrMessageIdentifier())? $this ->WtvrMessage->setWtvrMessageIdentifier( WTVRcleanString( $this -> getWtvrMessageIdentifier()) ) : null;
    ($this -> getWtvrMessageScope())? $this ->WtvrMessage->setWtvrMessageScope( WTVRcleanString( $this -> getWtvrMessageScope()) ) : null;
    ($this -> getWtvrMessageSendUserId())? $this ->WtvrMessage->setWtvrMessageSendUserId( WTVRcleanString( $this -> getWtvrMessageSendUserId()) ) : null;
    ($this -> getWtvrMessageSendEmail())? $this ->WtvrMessage->setWtvrMessageSendEmail( WTVRcleanString( $this -> getWtvrMessageSendEmail()) ) : null;
    ($this -> getWtvrMessageSendFname())? $this ->WtvrMessage->setWtvrMessageSendFname( WTVRcleanString( $this -> getWtvrMessageSendFname()) ) : null;
    ($this -> getWtvrMessageSendLname())? $this ->WtvrMessage->setWtvrMessageSendLname( WTVRcleanString( $this -> getWtvrMessageSendLname()) ) : null;
    ($this -> getWtvrMessageSubject())? $this ->WtvrMessage->setWtvrMessageSubject( WTVRcleanString( $this -> getWtvrMessageSubject()) ) : null;
    ($this -> getWtvrMessageBody())? $this ->WtvrMessage->setWtvrMessageBody( WTVRcleanString( $this -> getWtvrMessageBody()) ) : null;
    ($this -> getWtvrMessageText())? $this ->WtvrMessage->setWtvrMessageText( WTVRcleanString( $this -> getWtvrMessageText()) ) : null;
          if (is_valid_date( $this ->WtvrMessage->getWtvrMessageDate())) {
        $this ->WtvrMessage->setWtvrMessageDate( formatDate($this -> getWtvrMessageDate(), "TS" ));
      } else {
      $WtvrMessagewtvr_message_date = $this -> sfDateTime( "wtvr_message_date" );
      ( $WtvrMessagewtvr_message_date != "01/01/1900 00:00:00" )? $this ->WtvrMessage->setWtvrMessageDate( formatDate($WtvrMessagewtvr_message_date, "TS" )) : $this ->WtvrMessage->setWtvrMessageDate( null );
      }
    ($this -> getWtvrMessageTemplate())? $this ->WtvrMessage->setWtvrMessageTemplate( WTVRcleanString( $this -> getWtvrMessageTemplate()) ) : null;
    ($this -> getWtvrMessageKey1())? $this ->WtvrMessage->setWtvrMessageKey1( WTVRcleanString( $this -> getWtvrMessageKey1()) ) : null;
    ($this -> getWtvrMessageKey2())? $this ->WtvrMessage->setWtvrMessageKey2( WTVRcleanString( $this -> getWtvrMessageKey2()) ) : null;
    ($this -> getWtvrMessageKey3())? $this ->WtvrMessage->setWtvrMessageKey3( WTVRcleanString( $this -> getWtvrMessageKey3()) ) : null;
    ($this -> getWtvrMessageKey4())? $this ->WtvrMessage->setWtvrMessageKey4( WTVRcleanString( $this -> getWtvrMessageKey4()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->WtvrMessage ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->WtvrMessage = WtvrMessagePeer::retrieveByPK($id);
    }
    
    if (! $this ->WtvrMessage ) {
      return;
    }
    
    $this ->WtvrMessage -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('WtvrMessage_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "WtvrMessagePeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $WtvrMessage = WtvrMessagePeer::doSelect($c);
    
    if (count($WtvrMessage) >= 1) {
      $this ->WtvrMessage = $WtvrMessage[0];
      return true;
    } else {
      $this ->WtvrMessage = new WtvrMessage();
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
      $name = "WtvrMessagePeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $WtvrMessage = WtvrMessagePeer::doSelect($c);
    
    if (count($WtvrMessage) >= 1) {
      $this ->WtvrMessage = $WtvrMessage[0];
      return true;
    } else {
      $this ->WtvrMessage = new WtvrMessage();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>