<?php
       
   class WtvrMessageRecipientCrudBase extends Utils_PageWidget { 
   
    var $WtvrMessageRecipient;
   
       var $wtvr_message_recipient__id;
   var $fk_wtvr_message_id;
   var $wtvr_message_recipient_email;
   var $wtvr_message_recipient_fname;
   var $wtvr_message_recipient_lname;
   var $wtvr_message_recipient_date_added;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getWtvrMessageRecipientId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->WtvrMessageRecipient = WtvrMessageRecipientPeer::retrieveByPK( $id );
    } else {
      $this ->WtvrMessageRecipient = new WtvrMessageRecipient;
    }
  }
  
  function hydrate( $id ) {
      $this ->WtvrMessageRecipient = WtvrMessageRecipientPeer::retrieveByPK( $id );
  }
  
  function getWtvrMessageRecipientId() {
    if (($this ->postVar("wtvr_message_recipient__id")) || ($this ->postVar("wtvr_message_recipient__id") === "")) {
      return $this ->postVar("wtvr_message_recipient__id");
    } elseif (($this ->getVar("wtvr_message_recipient__id")) || ($this ->getVar("wtvr_message_recipient__id") === "")) {
      return $this ->getVar("wtvr_message_recipient__id");
    } elseif (($this ->WtvrMessageRecipient) || ($this ->WtvrMessageRecipient === "")){
      return $this ->WtvrMessageRecipient -> getWtvrMessageRecipientId();
    } elseif (($this ->sessionVar("wtvr_message_recipient__id")) || ($this ->sessionVar("wtvr_message_recipient__id") == "")) {
      return $this ->sessionVar("wtvr_message_recipient__id");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageRecipientId( $str ) {
    $this ->WtvrMessageRecipient -> setWtvrMessageRecipientId( $str );
  }
  
  function getFkWtvrMessageId() {
    if (($this ->postVar("fk_wtvr_message_id")) || ($this ->postVar("fk_wtvr_message_id") === "")) {
      return $this ->postVar("fk_wtvr_message_id");
    } elseif (($this ->getVar("fk_wtvr_message_id")) || ($this ->getVar("fk_wtvr_message_id") === "")) {
      return $this ->getVar("fk_wtvr_message_id");
    } elseif (($this ->WtvrMessageRecipient) || ($this ->WtvrMessageRecipient === "")){
      return $this ->WtvrMessageRecipient -> getFkWtvrMessageId();
    } elseif (($this ->sessionVar("fk_wtvr_message_id")) || ($this ->sessionVar("fk_wtvr_message_id") == "")) {
      return $this ->sessionVar("fk_wtvr_message_id");
    } else {
      return false;
    }
  }
  
  function setFkWtvrMessageId( $str ) {
    $this ->WtvrMessageRecipient -> setFkWtvrMessageId( $str );
  }
  
  function getWtvrMessageRecipientEmail() {
    if (($this ->postVar("wtvr_message_recipient_email")) || ($this ->postVar("wtvr_message_recipient_email") === "")) {
      return $this ->postVar("wtvr_message_recipient_email");
    } elseif (($this ->getVar("wtvr_message_recipient_email")) || ($this ->getVar("wtvr_message_recipient_email") === "")) {
      return $this ->getVar("wtvr_message_recipient_email");
    } elseif (($this ->WtvrMessageRecipient) || ($this ->WtvrMessageRecipient === "")){
      return $this ->WtvrMessageRecipient -> getWtvrMessageRecipientEmail();
    } elseif (($this ->sessionVar("wtvr_message_recipient_email")) || ($this ->sessionVar("wtvr_message_recipient_email") == "")) {
      return $this ->sessionVar("wtvr_message_recipient_email");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageRecipientEmail( $str ) {
    $this ->WtvrMessageRecipient -> setWtvrMessageRecipientEmail( $str );
  }
  
  function getWtvrMessageRecipientFname() {
    if (($this ->postVar("wtvr_message_recipient_fname")) || ($this ->postVar("wtvr_message_recipient_fname") === "")) {
      return $this ->postVar("wtvr_message_recipient_fname");
    } elseif (($this ->getVar("wtvr_message_recipient_fname")) || ($this ->getVar("wtvr_message_recipient_fname") === "")) {
      return $this ->getVar("wtvr_message_recipient_fname");
    } elseif (($this ->WtvrMessageRecipient) || ($this ->WtvrMessageRecipient === "")){
      return $this ->WtvrMessageRecipient -> getWtvrMessageRecipientFname();
    } elseif (($this ->sessionVar("wtvr_message_recipient_fname")) || ($this ->sessionVar("wtvr_message_recipient_fname") == "")) {
      return $this ->sessionVar("wtvr_message_recipient_fname");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageRecipientFname( $str ) {
    $this ->WtvrMessageRecipient -> setWtvrMessageRecipientFname( $str );
  }
  
  function getWtvrMessageRecipientLname() {
    if (($this ->postVar("wtvr_message_recipient_lname")) || ($this ->postVar("wtvr_message_recipient_lname") === "")) {
      return $this ->postVar("wtvr_message_recipient_lname");
    } elseif (($this ->getVar("wtvr_message_recipient_lname")) || ($this ->getVar("wtvr_message_recipient_lname") === "")) {
      return $this ->getVar("wtvr_message_recipient_lname");
    } elseif (($this ->WtvrMessageRecipient) || ($this ->WtvrMessageRecipient === "")){
      return $this ->WtvrMessageRecipient -> getWtvrMessageRecipientLname();
    } elseif (($this ->sessionVar("wtvr_message_recipient_lname")) || ($this ->sessionVar("wtvr_message_recipient_lname") == "")) {
      return $this ->sessionVar("wtvr_message_recipient_lname");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageRecipientLname( $str ) {
    $this ->WtvrMessageRecipient -> setWtvrMessageRecipientLname( $str );
  }
  
  function getWtvrMessageRecipientDateAdded() {
    if (($this ->postVar("wtvr_message_recipient_date_added")) || ($this ->postVar("wtvr_message_recipient_date_added") === "")) {
      return $this ->postVar("wtvr_message_recipient_date_added");
    } elseif (($this ->getVar("wtvr_message_recipient_date_added")) || ($this ->getVar("wtvr_message_recipient_date_added") === "")) {
      return $this ->getVar("wtvr_message_recipient_date_added");
    } elseif (($this ->WtvrMessageRecipient) || ($this ->WtvrMessageRecipient === "")){
      return $this ->WtvrMessageRecipient -> getWtvrMessageRecipientDateAdded();
    } elseif (($this ->sessionVar("wtvr_message_recipient_date_added")) || ($this ->sessionVar("wtvr_message_recipient_date_added") == "")) {
      return $this ->sessionVar("wtvr_message_recipient_date_added");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageRecipientDateAdded( $str ) {
    $this ->WtvrMessageRecipient -> setWtvrMessageRecipientDateAdded( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->WtvrMessageRecipient = WtvrMessageRecipientPeer::retrieveByPK( $id );
    }
    
    if ($this ->WtvrMessageRecipient ) {
       
    	       (is_numeric(WTVRcleanString($this ->WtvrMessageRecipient->getWtvrMessageRecipientId()))) ? $itemarray["wtvr_message_recipient__id"] = WTVRcleanString($this ->WtvrMessageRecipient->getWtvrMessageRecipientId()) : null;
          (is_numeric(WTVRcleanString($this ->WtvrMessageRecipient->getFkWtvrMessageId()))) ? $itemarray["fk_wtvr_message_id"] = WTVRcleanString($this ->WtvrMessageRecipient->getFkWtvrMessageId()) : null;
          (WTVRcleanString($this ->WtvrMessageRecipient->getWtvrMessageRecipientEmail())) ? $itemarray["wtvr_message_recipient_email"] = WTVRcleanString($this ->WtvrMessageRecipient->getWtvrMessageRecipientEmail()) : null;
          (WTVRcleanString($this ->WtvrMessageRecipient->getWtvrMessageRecipientFname())) ? $itemarray["wtvr_message_recipient_fname"] = WTVRcleanString($this ->WtvrMessageRecipient->getWtvrMessageRecipientFname()) : null;
          (WTVRcleanString($this ->WtvrMessageRecipient->getWtvrMessageRecipientLname())) ? $itemarray["wtvr_message_recipient_lname"] = WTVRcleanString($this ->WtvrMessageRecipient->getWtvrMessageRecipientLname()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->WtvrMessageRecipient->getWtvrMessageRecipientDateAdded())) ? $itemarray["wtvr_message_recipient_date_added"] = formatDate($this ->WtvrMessageRecipient->getWtvrMessageRecipientDateAdded('%Y-%m-%d %T'),"TS") : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->WtvrMessageRecipient = WtvrMessageRecipientPeer::retrieveByPK( $id );
    } elseif (! $this ->WtvrMessageRecipient) {
      $this ->WtvrMessageRecipient = new WtvrMessageRecipient;
    }
        
  	 ($this -> getWtvrMessageRecipientId())? $this ->WtvrMessageRecipient->setWtvrMessageRecipientId( WTVRcleanString( $this -> getWtvrMessageRecipientId()) ) : null;
    ($this -> getFkWtvrMessageId())? $this ->WtvrMessageRecipient->setFkWtvrMessageId( WTVRcleanString( $this -> getFkWtvrMessageId()) ) : null;
    ($this -> getWtvrMessageRecipientEmail())? $this ->WtvrMessageRecipient->setWtvrMessageRecipientEmail( WTVRcleanString( $this -> getWtvrMessageRecipientEmail()) ) : null;
    ($this -> getWtvrMessageRecipientFname())? $this ->WtvrMessageRecipient->setWtvrMessageRecipientFname( WTVRcleanString( $this -> getWtvrMessageRecipientFname()) ) : null;
    ($this -> getWtvrMessageRecipientLname())? $this ->WtvrMessageRecipient->setWtvrMessageRecipientLname( WTVRcleanString( $this -> getWtvrMessageRecipientLname()) ) : null;
          if (is_valid_date( $this ->WtvrMessageRecipient->getWtvrMessageRecipientDateAdded())) {
        $this ->WtvrMessageRecipient->setWtvrMessageRecipientDateAdded( formatDate($this -> getWtvrMessageRecipientDateAdded(), "TS" ));
      } else {
      $WtvrMessageRecipientwtvr_message_recipient_date_added = $this -> sfDateTime( "wtvr_message_recipient_date_added" );
      ( $WtvrMessageRecipientwtvr_message_recipient_date_added != "01/01/1900 00:00:00" )? $this ->WtvrMessageRecipient->setWtvrMessageRecipientDateAdded( formatDate($WtvrMessageRecipientwtvr_message_recipient_date_added, "TS" )) : $this ->WtvrMessageRecipient->setWtvrMessageRecipientDateAdded( null );
      }
          
    $this -> save();
     
  }
  
  function save() {
    $this ->WtvrMessageRecipient ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->WtvrMessageRecipient = WtvrMessageRecipientPeer::retrieveByPK($id);
    }
    
    if (! $this ->WtvrMessageRecipient ) {
      return;
    }
    
    $this ->WtvrMessageRecipient -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('WtvrMessageRecipient_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "WtvrMessageRecipientPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $WtvrMessageRecipient = WtvrMessageRecipientPeer::doSelect($c);
    
    if (count($WtvrMessageRecipient) >= 1) {
      $this ->WtvrMessageRecipient = $WtvrMessageRecipient[0];
      return true;
    } else {
      $this ->WtvrMessageRecipient = new WtvrMessageRecipient();
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
      $name = "WtvrMessageRecipientPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $WtvrMessageRecipient = WtvrMessageRecipientPeer::doSelect($c);
    
    if (count($WtvrMessageRecipient) >= 1) {
      $this ->WtvrMessageRecipient = $WtvrMessageRecipient[0];
      return true;
    } else {
      $this ->WtvrMessageRecipient = new WtvrMessageRecipient();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>