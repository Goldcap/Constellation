<?php
       
   class WtvrMessageQueueLocalCrudBase extends Utils_PageWidget { 
   
    var $WtvrMessageQueueLocal;
   
       var $wtvr_message_queue_local_id;
   var $fk_wtvr_message_id;
   var $wtvr_message_queue_local_date_added;
   var $wtvr_message_queue_local_date_sent;
   var $wtvr_message_queue_local_bad_recipients;
   var $wtvr_message_queue_local_unsubscribed_recipients;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getWtvrMessageQueueLocalId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->WtvrMessageQueueLocal = WtvrMessageQueueLocalPeer::retrieveByPK( $id );
    } else {
      $this ->WtvrMessageQueueLocal = new WtvrMessageQueueLocal;
    }
  }
  
  function hydrate( $id ) {
      $this ->WtvrMessageQueueLocal = WtvrMessageQueueLocalPeer::retrieveByPK( $id );
  }
  
  function getWtvrMessageQueueLocalId() {
    if (($this ->postVar("wtvr_message_queue_local_id")) || ($this ->postVar("wtvr_message_queue_local_id") === "")) {
      return $this ->postVar("wtvr_message_queue_local_id");
    } elseif (($this ->getVar("wtvr_message_queue_local_id")) || ($this ->getVar("wtvr_message_queue_local_id") === "")) {
      return $this ->getVar("wtvr_message_queue_local_id");
    } elseif (($this ->WtvrMessageQueueLocal) || ($this ->WtvrMessageQueueLocal === "")){
      return $this ->WtvrMessageQueueLocal -> getWtvrMessageQueueLocalId();
    } elseif (($this ->sessionVar("wtvr_message_queue_local_id")) || ($this ->sessionVar("wtvr_message_queue_local_id") == "")) {
      return $this ->sessionVar("wtvr_message_queue_local_id");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageQueueLocalId( $str ) {
    $this ->WtvrMessageQueueLocal -> setWtvrMessageQueueLocalId( $str );
  }
  
  function getFkWtvrMessageId() {
    if (($this ->postVar("fk_wtvr_message_id")) || ($this ->postVar("fk_wtvr_message_id") === "")) {
      return $this ->postVar("fk_wtvr_message_id");
    } elseif (($this ->getVar("fk_wtvr_message_id")) || ($this ->getVar("fk_wtvr_message_id") === "")) {
      return $this ->getVar("fk_wtvr_message_id");
    } elseif (($this ->WtvrMessageQueueLocal) || ($this ->WtvrMessageQueueLocal === "")){
      return $this ->WtvrMessageQueueLocal -> getFkWtvrMessageId();
    } elseif (($this ->sessionVar("fk_wtvr_message_id")) || ($this ->sessionVar("fk_wtvr_message_id") == "")) {
      return $this ->sessionVar("fk_wtvr_message_id");
    } else {
      return false;
    }
  }
  
  function setFkWtvrMessageId( $str ) {
    $this ->WtvrMessageQueueLocal -> setFkWtvrMessageId( $str );
  }
  
  function getWtvrMessageQueueLocalDateAdded() {
    if (($this ->postVar("wtvr_message_queue_local_date_added")) || ($this ->postVar("wtvr_message_queue_local_date_added") === "")) {
      return $this ->postVar("wtvr_message_queue_local_date_added");
    } elseif (($this ->getVar("wtvr_message_queue_local_date_added")) || ($this ->getVar("wtvr_message_queue_local_date_added") === "")) {
      return $this ->getVar("wtvr_message_queue_local_date_added");
    } elseif (($this ->WtvrMessageQueueLocal) || ($this ->WtvrMessageQueueLocal === "")){
      return $this ->WtvrMessageQueueLocal -> getWtvrMessageQueueLocalDateAdded();
    } elseif (($this ->sessionVar("wtvr_message_queue_local_date_added")) || ($this ->sessionVar("wtvr_message_queue_local_date_added") == "")) {
      return $this ->sessionVar("wtvr_message_queue_local_date_added");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageQueueLocalDateAdded( $str ) {
    $this ->WtvrMessageQueueLocal -> setWtvrMessageQueueLocalDateAdded( $str );
  }
  
  function getWtvrMessageQueueLocalDateSent() {
    if (($this ->postVar("wtvr_message_queue_local_date_sent")) || ($this ->postVar("wtvr_message_queue_local_date_sent") === "")) {
      return $this ->postVar("wtvr_message_queue_local_date_sent");
    } elseif (($this ->getVar("wtvr_message_queue_local_date_sent")) || ($this ->getVar("wtvr_message_queue_local_date_sent") === "")) {
      return $this ->getVar("wtvr_message_queue_local_date_sent");
    } elseif (($this ->WtvrMessageQueueLocal) || ($this ->WtvrMessageQueueLocal === "")){
      return $this ->WtvrMessageQueueLocal -> getWtvrMessageQueueLocalDateSent();
    } elseif (($this ->sessionVar("wtvr_message_queue_local_date_sent")) || ($this ->sessionVar("wtvr_message_queue_local_date_sent") == "")) {
      return $this ->sessionVar("wtvr_message_queue_local_date_sent");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageQueueLocalDateSent( $str ) {
    $this ->WtvrMessageQueueLocal -> setWtvrMessageQueueLocalDateSent( $str );
  }
  
  function getWtvrMessageQueueLocalBadRecipients() {
    if (($this ->postVar("wtvr_message_queue_local_bad_recipients")) || ($this ->postVar("wtvr_message_queue_local_bad_recipients") === "")) {
      return $this ->postVar("wtvr_message_queue_local_bad_recipients");
    } elseif (($this ->getVar("wtvr_message_queue_local_bad_recipients")) || ($this ->getVar("wtvr_message_queue_local_bad_recipients") === "")) {
      return $this ->getVar("wtvr_message_queue_local_bad_recipients");
    } elseif (($this ->WtvrMessageQueueLocal) || ($this ->WtvrMessageQueueLocal === "")){
      return $this ->WtvrMessageQueueLocal -> getWtvrMessageQueueLocalBadRecipients();
    } elseif (($this ->sessionVar("wtvr_message_queue_local_bad_recipients")) || ($this ->sessionVar("wtvr_message_queue_local_bad_recipients") == "")) {
      return $this ->sessionVar("wtvr_message_queue_local_bad_recipients");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageQueueLocalBadRecipients( $str ) {
    $this ->WtvrMessageQueueLocal -> setWtvrMessageQueueLocalBadRecipients( $str );
  }
  
  function getWtvrMessageQueueLocalUnsubscribedRecipients() {
    if (($this ->postVar("wtvr_message_queue_local_unsubscribed_recipients")) || ($this ->postVar("wtvr_message_queue_local_unsubscribed_recipients") === "")) {
      return $this ->postVar("wtvr_message_queue_local_unsubscribed_recipients");
    } elseif (($this ->getVar("wtvr_message_queue_local_unsubscribed_recipients")) || ($this ->getVar("wtvr_message_queue_local_unsubscribed_recipients") === "")) {
      return $this ->getVar("wtvr_message_queue_local_unsubscribed_recipients");
    } elseif (($this ->WtvrMessageQueueLocal) || ($this ->WtvrMessageQueueLocal === "")){
      return $this ->WtvrMessageQueueLocal -> getWtvrMessageQueueLocalUnsubscribedRecipients();
    } elseif (($this ->sessionVar("wtvr_message_queue_local_unsubscribed_recipients")) || ($this ->sessionVar("wtvr_message_queue_local_unsubscribed_recipients") == "")) {
      return $this ->sessionVar("wtvr_message_queue_local_unsubscribed_recipients");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageQueueLocalUnsubscribedRecipients( $str ) {
    $this ->WtvrMessageQueueLocal -> setWtvrMessageQueueLocalUnsubscribedRecipients( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->WtvrMessageQueueLocal = WtvrMessageQueueLocalPeer::retrieveByPK( $id );
    }
    
    if ($this ->WtvrMessageQueueLocal ) {
       
    	       (is_numeric(WTVRcleanString($this ->WtvrMessageQueueLocal->getWtvrMessageQueueLocalId()))) ? $itemarray["wtvr_message_queue_local_id"] = WTVRcleanString($this ->WtvrMessageQueueLocal->getWtvrMessageQueueLocalId()) : null;
          (is_numeric(WTVRcleanString($this ->WtvrMessageQueueLocal->getFkWtvrMessageId()))) ? $itemarray["fk_wtvr_message_id"] = WTVRcleanString($this ->WtvrMessageQueueLocal->getFkWtvrMessageId()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->WtvrMessageQueueLocal->getWtvrMessageQueueLocalDateAdded())) ? $itemarray["wtvr_message_queue_local_date_added"] = formatDate($this ->WtvrMessageQueueLocal->getWtvrMessageQueueLocalDateAdded('%Y-%m-%d %T'),"TS") : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->WtvrMessageQueueLocal->getWtvrMessageQueueLocalDateSent())) ? $itemarray["wtvr_message_queue_local_date_sent"] = formatDate($this ->WtvrMessageQueueLocal->getWtvrMessageQueueLocalDateSent('%Y-%m-%d %T'),"TS") : null;
          (is_numeric(WTVRcleanString($this ->WtvrMessageQueueLocal->getWtvrMessageQueueLocalBadRecipients()))) ? $itemarray["wtvr_message_queue_local_bad_recipients"] = WTVRcleanString($this ->WtvrMessageQueueLocal->getWtvrMessageQueueLocalBadRecipients()) : null;
          (is_numeric(WTVRcleanString($this ->WtvrMessageQueueLocal->getWtvrMessageQueueLocalUnsubscribedRecipients()))) ? $itemarray["wtvr_message_queue_local_unsubscribed_recipients"] = WTVRcleanString($this ->WtvrMessageQueueLocal->getWtvrMessageQueueLocalUnsubscribedRecipients()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->WtvrMessageQueueLocal = WtvrMessageQueueLocalPeer::retrieveByPK( $id );
    } elseif (! $this ->WtvrMessageQueueLocal) {
      $this ->WtvrMessageQueueLocal = new WtvrMessageQueueLocal;
    }
        
  	 ($this -> getWtvrMessageQueueLocalId())? $this ->WtvrMessageQueueLocal->setWtvrMessageQueueLocalId( WTVRcleanString( $this -> getWtvrMessageQueueLocalId()) ) : null;
    ($this -> getFkWtvrMessageId())? $this ->WtvrMessageQueueLocal->setFkWtvrMessageId( WTVRcleanString( $this -> getFkWtvrMessageId()) ) : null;
          if (is_valid_date( $this ->WtvrMessageQueueLocal->getWtvrMessageQueueLocalDateAdded())) {
        $this ->WtvrMessageQueueLocal->setWtvrMessageQueueLocalDateAdded( formatDate($this -> getWtvrMessageQueueLocalDateAdded(), "TS" ));
      } else {
      $WtvrMessageQueueLocalwtvr_message_queue_local_date_added = $this -> sfDateTime( "wtvr_message_queue_local_date_added" );
      ( $WtvrMessageQueueLocalwtvr_message_queue_local_date_added != "01/01/1900 00:00:00" )? $this ->WtvrMessageQueueLocal->setWtvrMessageQueueLocalDateAdded( formatDate($WtvrMessageQueueLocalwtvr_message_queue_local_date_added, "TS" )) : $this ->WtvrMessageQueueLocal->setWtvrMessageQueueLocalDateAdded( null );
      }
          if (is_valid_date( $this ->WtvrMessageQueueLocal->getWtvrMessageQueueLocalDateSent())) {
        $this ->WtvrMessageQueueLocal->setWtvrMessageQueueLocalDateSent( formatDate($this -> getWtvrMessageQueueLocalDateSent(), "TS" ));
      } else {
      $WtvrMessageQueueLocalwtvr_message_queue_local_date_sent = $this -> sfDateTime( "wtvr_message_queue_local_date_sent" );
      ( $WtvrMessageQueueLocalwtvr_message_queue_local_date_sent != "01/01/1900 00:00:00" )? $this ->WtvrMessageQueueLocal->setWtvrMessageQueueLocalDateSent( formatDate($WtvrMessageQueueLocalwtvr_message_queue_local_date_sent, "TS" )) : $this ->WtvrMessageQueueLocal->setWtvrMessageQueueLocalDateSent( null );
      }
    ($this -> getWtvrMessageQueueLocalBadRecipients())? $this ->WtvrMessageQueueLocal->setWtvrMessageQueueLocalBadRecipients( WTVRcleanString( $this -> getWtvrMessageQueueLocalBadRecipients()) ) : null;
    ($this -> getWtvrMessageQueueLocalUnsubscribedRecipients())? $this ->WtvrMessageQueueLocal->setWtvrMessageQueueLocalUnsubscribedRecipients( WTVRcleanString( $this -> getWtvrMessageQueueLocalUnsubscribedRecipients()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->WtvrMessageQueueLocal ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->WtvrMessageQueueLocal = WtvrMessageQueueLocalPeer::retrieveByPK($id);
    }
    
    if (! $this ->WtvrMessageQueueLocal ) {
      return;
    }
    
    $this ->WtvrMessageQueueLocal -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('WtvrMessageQueueLocal_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "WtvrMessageQueueLocalPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $WtvrMessageQueueLocal = WtvrMessageQueueLocalPeer::doSelect($c);
    
    if (count($WtvrMessageQueueLocal) >= 1) {
      $this ->WtvrMessageQueueLocal = $WtvrMessageQueueLocal[0];
      return true;
    } else {
      $this ->WtvrMessageQueueLocal = new WtvrMessageQueueLocal();
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
      $name = "WtvrMessageQueueLocalPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $WtvrMessageQueueLocal = WtvrMessageQueueLocalPeer::doSelect($c);
    
    if (count($WtvrMessageQueueLocal) >= 1) {
      $this ->WtvrMessageQueueLocal = $WtvrMessageQueueLocal[0];
      return true;
    } else {
      $this ->WtvrMessageQueueLocal = new WtvrMessageQueueLocal();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>