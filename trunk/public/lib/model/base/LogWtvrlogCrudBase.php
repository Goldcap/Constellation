<?php
       
   class LogWtvrlogCrudBase extends Utils_PageWidget { 
   
    var $LogWtvrlog;
   
       var $wtvrlog_id;
   var $fk_user_id;
   var $wtvrlog_message;
   var $wtvrlog_date;
   var $wtvrlog_server;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getWtvrlogId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->LogWtvrlog = LogWtvrlogPeer::retrieveByPK( $id );
    } else {
      $this ->LogWtvrlog = new LogWtvrlog;
    }
  }
  
  function hydrate( $id ) {
      $this ->LogWtvrlog = LogWtvrlogPeer::retrieveByPK( $id );
  }
  
  function getWtvrlogId() {
    if (($this ->postVar("wtvrlog_id")) || ($this ->postVar("wtvrlog_id") === "")) {
      return $this ->postVar("wtvrlog_id");
    } elseif (($this ->getVar("wtvrlog_id")) || ($this ->getVar("wtvrlog_id") === "")) {
      return $this ->getVar("wtvrlog_id");
    } elseif (($this ->LogWtvrlog) || ($this ->LogWtvrlog === "")){
      return $this ->LogWtvrlog -> getWtvrlogId();
    } elseif (($this ->sessionVar("wtvrlog_id")) || ($this ->sessionVar("wtvrlog_id") == "")) {
      return $this ->sessionVar("wtvrlog_id");
    } else {
      return false;
    }
  }
  
  function setWtvrlogId( $str ) {
    $this ->LogWtvrlog -> setWtvrlogId( $str );
  }
  
  function getFkUserId() {
    if (($this ->postVar("fk_user_id")) || ($this ->postVar("fk_user_id") === "")) {
      return $this ->postVar("fk_user_id");
    } elseif (($this ->getVar("fk_user_id")) || ($this ->getVar("fk_user_id") === "")) {
      return $this ->getVar("fk_user_id");
    } elseif (($this ->LogWtvrlog) || ($this ->LogWtvrlog === "")){
      return $this ->LogWtvrlog -> getFkUserId();
    } elseif (($this ->sessionVar("fk_user_id")) || ($this ->sessionVar("fk_user_id") == "")) {
      return $this ->sessionVar("fk_user_id");
    } else {
      return false;
    }
  }
  
  function setFkUserId( $str ) {
    $this ->LogWtvrlog -> setFkUserId( $str );
  }
  
  function getWtvrlogMessage() {
    if (($this ->postVar("wtvrlog_message")) || ($this ->postVar("wtvrlog_message") === "")) {
      return $this ->postVar("wtvrlog_message");
    } elseif (($this ->getVar("wtvrlog_message")) || ($this ->getVar("wtvrlog_message") === "")) {
      return $this ->getVar("wtvrlog_message");
    } elseif (($this ->LogWtvrlog) || ($this ->LogWtvrlog === "")){
      return $this ->LogWtvrlog -> getWtvrlogMessage();
    } elseif (($this ->sessionVar("wtvrlog_message")) || ($this ->sessionVar("wtvrlog_message") == "")) {
      return $this ->sessionVar("wtvrlog_message");
    } else {
      return false;
    }
  }
  
  function setWtvrlogMessage( $str ) {
    $this ->LogWtvrlog -> setWtvrlogMessage( $str );
  }
  
  function getWtvrlogDate() {
    if (($this ->postVar("wtvrlog_date")) || ($this ->postVar("wtvrlog_date") === "")) {
      return $this ->postVar("wtvrlog_date");
    } elseif (($this ->getVar("wtvrlog_date")) || ($this ->getVar("wtvrlog_date") === "")) {
      return $this ->getVar("wtvrlog_date");
    } elseif (($this ->LogWtvrlog) || ($this ->LogWtvrlog === "")){
      return $this ->LogWtvrlog -> getWtvrlogDate();
    } elseif (($this ->sessionVar("wtvrlog_date")) || ($this ->sessionVar("wtvrlog_date") == "")) {
      return $this ->sessionVar("wtvrlog_date");
    } else {
      return false;
    }
  }
  
  function setWtvrlogDate( $str ) {
    $this ->LogWtvrlog -> setWtvrlogDate( $str );
  }
  
  function getWtvrlogServer() {
    if (($this ->postVar("wtvrlog_server")) || ($this ->postVar("wtvrlog_server") === "")) {
      return $this ->postVar("wtvrlog_server");
    } elseif (($this ->getVar("wtvrlog_server")) || ($this ->getVar("wtvrlog_server") === "")) {
      return $this ->getVar("wtvrlog_server");
    } elseif (($this ->LogWtvrlog) || ($this ->LogWtvrlog === "")){
      return $this ->LogWtvrlog -> getWtvrlogServer();
    } elseif (($this ->sessionVar("wtvrlog_server")) || ($this ->sessionVar("wtvrlog_server") == "")) {
      return $this ->sessionVar("wtvrlog_server");
    } else {
      return false;
    }
  }
  
  function setWtvrlogServer( $str ) {
    $this ->LogWtvrlog -> setWtvrlogServer( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->LogWtvrlog = LogWtvrlogPeer::retrieveByPK( $id );
    }
    
    if ($this ->LogWtvrlog ) {
       
    	       (is_numeric(WTVRcleanString($this ->LogWtvrlog->getWtvrlogId()))) ? $itemarray["wtvrlog_id"] = WTVRcleanString($this ->LogWtvrlog->getWtvrlogId()) : null;
          (is_numeric(WTVRcleanString($this ->LogWtvrlog->getFkUserId()))) ? $itemarray["fk_user_id"] = WTVRcleanString($this ->LogWtvrlog->getFkUserId()) : null;
          (WTVRcleanString($this ->LogWtvrlog->getWtvrlogMessage())) ? $itemarray["wtvrlog_message"] = WTVRcleanString($this ->LogWtvrlog->getWtvrlogMessage()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->LogWtvrlog->getWtvrlogDate())) ? $itemarray["wtvrlog_date"] = formatDate($this ->LogWtvrlog->getWtvrlogDate('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->LogWtvrlog->getWtvrlogServer())) ? $itemarray["wtvrlog_server"] = WTVRcleanString($this ->LogWtvrlog->getWtvrlogServer()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->LogWtvrlog = LogWtvrlogPeer::retrieveByPK( $id );
    } elseif (! $this ->LogWtvrlog) {
      $this ->LogWtvrlog = new LogWtvrlog;
    }
        
  	 ($this -> getWtvrlogId())? $this ->LogWtvrlog->setWtvrlogId( WTVRcleanString( $this -> getWtvrlogId()) ) : null;
    ($this -> getFkUserId())? $this ->LogWtvrlog->setFkUserId( WTVRcleanString( $this -> getFkUserId()) ) : null;
    ($this -> getWtvrlogMessage())? $this ->LogWtvrlog->setWtvrlogMessage( WTVRcleanString( $this -> getWtvrlogMessage()) ) : null;
          if (is_valid_date( $this ->LogWtvrlog->getWtvrlogDate())) {
        $this ->LogWtvrlog->setWtvrlogDate( formatDate($this -> getWtvrlogDate(), "TS" ));
      } else {
      $LogWtvrlogwtvrlog_date = $this -> sfDateTime( "wtvrlog_date" );
      ( $LogWtvrlogwtvrlog_date != "01/01/1900 00:00:00" )? $this ->LogWtvrlog->setWtvrlogDate( formatDate($LogWtvrlogwtvrlog_date, "TS" )) : $this ->LogWtvrlog->setWtvrlogDate( null );
      }
    ($this -> getWtvrlogServer())? $this ->LogWtvrlog->setWtvrlogServer( WTVRcleanString( $this -> getWtvrlogServer()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->LogWtvrlog ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->LogWtvrlog = LogWtvrlogPeer::retrieveByPK($id);
    }
    
    if (! $this ->LogWtvrlog ) {
      return;
    }
    
    $this ->LogWtvrlog -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('LogWtvrlog_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "LogWtvrlogPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $LogWtvrlog = LogWtvrlogPeer::doSelect($c);
    
    if (count($LogWtvrlog) >= 1) {
      $this ->LogWtvrlog = $LogWtvrlog[0];
      return true;
    } else {
      $this ->LogWtvrlog = new LogWtvrlog();
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
      $name = "LogWtvrlogPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $LogWtvrlog = LogWtvrlogPeer::doSelect($c);
    
    if (count($LogWtvrlog) >= 1) {
      $this ->LogWtvrlog = $LogWtvrlog[0];
      return true;
    } else {
      $this ->LogWtvrlog = new LogWtvrlog();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>