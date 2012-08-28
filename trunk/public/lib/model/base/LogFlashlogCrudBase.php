<?php
       
   class LogFlashlogCrudBase extends Utils_PageWidget { 
   
    var $LogFlashlog;
   
       var $flashlog_id;
   var $flashlog_ticket;
   var $flashlog_message;
   var $flashlog_date;
   var $flashlog_port;
   var $flashlog_error;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getFlashlogId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->LogFlashlog = LogFlashlogPeer::retrieveByPK( $id );
    } else {
      $this ->LogFlashlog = new LogFlashlog;
    }
  }
  
  function hydrate( $id ) {
      $this ->LogFlashlog = LogFlashlogPeer::retrieveByPK( $id );
  }
  
  function getFlashlogId() {
    if (($this ->postVar("flashlog_id")) || ($this ->postVar("flashlog_id") === "")) {
      return $this ->postVar("flashlog_id");
    } elseif (($this ->getVar("flashlog_id")) || ($this ->getVar("flashlog_id") === "")) {
      return $this ->getVar("flashlog_id");
    } elseif (($this ->LogFlashlog) || ($this ->LogFlashlog === "")){
      return $this ->LogFlashlog -> getFlashlogId();
    } elseif (($this ->sessionVar("flashlog_id")) || ($this ->sessionVar("flashlog_id") == "")) {
      return $this ->sessionVar("flashlog_id");
    } else {
      return false;
    }
  }
  
  function setFlashlogId( $str ) {
    $this ->LogFlashlog -> setFlashlogId( $str );
  }
  
  function getFlashlogTicket() {
    if (($this ->postVar("flashlog_ticket")) || ($this ->postVar("flashlog_ticket") === "")) {
      return $this ->postVar("flashlog_ticket");
    } elseif (($this ->getVar("flashlog_ticket")) || ($this ->getVar("flashlog_ticket") === "")) {
      return $this ->getVar("flashlog_ticket");
    } elseif (($this ->LogFlashlog) || ($this ->LogFlashlog === "")){
      return $this ->LogFlashlog -> getFlashlogTicket();
    } elseif (($this ->sessionVar("flashlog_ticket")) || ($this ->sessionVar("flashlog_ticket") == "")) {
      return $this ->sessionVar("flashlog_ticket");
    } else {
      return false;
    }
  }
  
  function setFlashlogTicket( $str ) {
    $this ->LogFlashlog -> setFlashlogTicket( $str );
  }
  
  function getFlashlogMessage() {
    if (($this ->postVar("flashlog_message")) || ($this ->postVar("flashlog_message") === "")) {
      return $this ->postVar("flashlog_message");
    } elseif (($this ->getVar("flashlog_message")) || ($this ->getVar("flashlog_message") === "")) {
      return $this ->getVar("flashlog_message");
    } elseif (($this ->LogFlashlog) || ($this ->LogFlashlog === "")){
      return $this ->LogFlashlog -> getFlashlogMessage();
    } elseif (($this ->sessionVar("flashlog_message")) || ($this ->sessionVar("flashlog_message") == "")) {
      return $this ->sessionVar("flashlog_message");
    } else {
      return false;
    }
  }
  
  function setFlashlogMessage( $str ) {
    $this ->LogFlashlog -> setFlashlogMessage( $str );
  }
  
  function getFlashlogDate() {
    if (($this ->postVar("flashlog_date")) || ($this ->postVar("flashlog_date") === "")) {
      return $this ->postVar("flashlog_date");
    } elseif (($this ->getVar("flashlog_date")) || ($this ->getVar("flashlog_date") === "")) {
      return $this ->getVar("flashlog_date");
    } elseif (($this ->LogFlashlog) || ($this ->LogFlashlog === "")){
      return $this ->LogFlashlog -> getFlashlogDate();
    } elseif (($this ->sessionVar("flashlog_date")) || ($this ->sessionVar("flashlog_date") == "")) {
      return $this ->sessionVar("flashlog_date");
    } else {
      return false;
    }
  }
  
  function setFlashlogDate( $str ) {
    $this ->LogFlashlog -> setFlashlogDate( $str );
  }
  
  function getFlashlogPort() {
    if (($this ->postVar("flashlog_port")) || ($this ->postVar("flashlog_port") === "")) {
      return $this ->postVar("flashlog_port");
    } elseif (($this ->getVar("flashlog_port")) || ($this ->getVar("flashlog_port") === "")) {
      return $this ->getVar("flashlog_port");
    } elseif (($this ->LogFlashlog) || ($this ->LogFlashlog === "")){
      return $this ->LogFlashlog -> getFlashlogPort();
    } elseif (($this ->sessionVar("flashlog_port")) || ($this ->sessionVar("flashlog_port") == "")) {
      return $this ->sessionVar("flashlog_port");
    } else {
      return false;
    }
  }
  
  function setFlashlogPort( $str ) {
    $this ->LogFlashlog -> setFlashlogPort( $str );
  }
  
  function getFlashlogError() {
    if (($this ->postVar("flashlog_error")) || ($this ->postVar("flashlog_error") === "")) {
      return $this ->postVar("flashlog_error");
    } elseif (($this ->getVar("flashlog_error")) || ($this ->getVar("flashlog_error") === "")) {
      return $this ->getVar("flashlog_error");
    } elseif (($this ->LogFlashlog) || ($this ->LogFlashlog === "")){
      return $this ->LogFlashlog -> getFlashlogError();
    } elseif (($this ->sessionVar("flashlog_error")) || ($this ->sessionVar("flashlog_error") == "")) {
      return $this ->sessionVar("flashlog_error");
    } else {
      return false;
    }
  }
  
  function setFlashlogError( $str ) {
    $this ->LogFlashlog -> setFlashlogError( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->LogFlashlog = LogFlashlogPeer::retrieveByPK( $id );
    }
    
    if ($this ->LogFlashlog ) {
       
    	       (is_numeric(WTVRcleanString($this ->LogFlashlog->getFlashlogId()))) ? $itemarray["flashlog_id"] = WTVRcleanString($this ->LogFlashlog->getFlashlogId()) : null;
          (WTVRcleanString($this ->LogFlashlog->getFlashlogTicket())) ? $itemarray["flashlog_ticket"] = WTVRcleanString($this ->LogFlashlog->getFlashlogTicket()) : null;
          (WTVRcleanString($this ->LogFlashlog->getFlashlogMessage())) ? $itemarray["flashlog_message"] = WTVRcleanString($this ->LogFlashlog->getFlashlogMessage()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->LogFlashlog->getFlashlogDate())) ? $itemarray["flashlog_date"] = formatDate($this ->LogFlashlog->getFlashlogDate('%Y-%m-%d %T'),"TS") : null;
          (is_numeric(WTVRcleanString($this ->LogFlashlog->getFlashlogPort()))) ? $itemarray["flashlog_port"] = WTVRcleanString($this ->LogFlashlog->getFlashlogPort()) : null;
          (WTVRcleanString($this ->LogFlashlog->getFlashlogError())) ? $itemarray["flashlog_error"] = WTVRcleanString($this ->LogFlashlog->getFlashlogError()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->LogFlashlog = LogFlashlogPeer::retrieveByPK( $id );
    } elseif (! $this ->LogFlashlog) {
      $this ->LogFlashlog = new LogFlashlog;
    }
        
  	 ($this -> getFlashlogId())? $this ->LogFlashlog->setFlashlogId( WTVRcleanString( $this -> getFlashlogId()) ) : null;
    ($this -> getFlashlogTicket())? $this ->LogFlashlog->setFlashlogTicket( WTVRcleanString( $this -> getFlashlogTicket()) ) : null;
    ($this -> getFlashlogMessage())? $this ->LogFlashlog->setFlashlogMessage( WTVRcleanString( $this -> getFlashlogMessage()) ) : null;
          if (is_valid_date( $this ->LogFlashlog->getFlashlogDate())) {
        $this ->LogFlashlog->setFlashlogDate( formatDate($this -> getFlashlogDate(), "TS" ));
      } else {
      $LogFlashlogflashlog_date = $this -> sfDateTime( "flashlog_date" );
      ( $LogFlashlogflashlog_date != "01/01/1900 00:00:00" )? $this ->LogFlashlog->setFlashlogDate( formatDate($LogFlashlogflashlog_date, "TS" )) : $this ->LogFlashlog->setFlashlogDate( null );
      }
    ($this -> getFlashlogPort())? $this ->LogFlashlog->setFlashlogPort( WTVRcleanString( $this -> getFlashlogPort()) ) : null;
    ($this -> getFlashlogError())? $this ->LogFlashlog->setFlashlogError( WTVRcleanString( $this -> getFlashlogError()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->LogFlashlog ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->LogFlashlog = LogFlashlogPeer::retrieveByPK($id);
    }
    
    if (! $this ->LogFlashlog ) {
      return;
    }
    
    $this ->LogFlashlog -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('LogFlashlog_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "LogFlashlogPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $LogFlashlog = LogFlashlogPeer::doSelect($c);
    
    if (count($LogFlashlog) >= 1) {
      $this ->LogFlashlog = $LogFlashlog[0];
      return true;
    } else {
      $this ->LogFlashlog = new LogFlashlog();
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
      $name = "LogFlashlogPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $LogFlashlog = LogFlashlogPeer::doSelect($c);
    
    if (count($LogFlashlog) >= 1) {
      $this ->LogFlashlog = $LogFlashlog[0];
      return true;
    } else {
      $this ->LogFlashlog = new LogFlashlog();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>