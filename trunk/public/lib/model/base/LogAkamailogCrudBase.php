<?php
       
   class LogAkamailogCrudBase extends Utils_PageWidget { 
   
    var $LogAkamailog;
   
       var $log_akamailog_id;
   var $log_akamailog_date;
   var $log_akamailog_time;
   var $log_akamailog_ip;
   var $log_akamailog_method;
   var $log_akamailog_uri;
   var $log_akamailog_status;
   var $log_akamailog_bytes;
   var $log_akamailog_timetaken;
   var $log_akamailog_referer;
   var $log_akamailog_user_agent;
   var $log_akamailog_cookie;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getLogAkamailogId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->LogAkamailog = LogAkamailogPeer::retrieveByPK( $id );
    } else {
      $this ->LogAkamailog = new LogAkamailog;
    }
  }
  
  function hydrate( $id ) {
      $this ->LogAkamailog = LogAkamailogPeer::retrieveByPK( $id );
  }
  
  function getLogAkamailogId() {
    if (($this ->postVar("log_akamailog_id")) || ($this ->postVar("log_akamailog_id") === "")) {
      return $this ->postVar("log_akamailog_id");
    } elseif (($this ->getVar("log_akamailog_id")) || ($this ->getVar("log_akamailog_id") === "")) {
      return $this ->getVar("log_akamailog_id");
    } elseif (($this ->LogAkamailog) || ($this ->LogAkamailog === "")){
      return $this ->LogAkamailog -> getLogAkamailogId();
    } elseif (($this ->sessionVar("log_akamailog_id")) || ($this ->sessionVar("log_akamailog_id") == "")) {
      return $this ->sessionVar("log_akamailog_id");
    } else {
      return false;
    }
  }
  
  function setLogAkamailogId( $str ) {
    $this ->LogAkamailog -> setLogAkamailogId( $str );
  }
  
  function getLogAkamailogDate() {
    if (($this ->postVar("log_akamailog_date")) || ($this ->postVar("log_akamailog_date") === "")) {
      return $this ->postVar("log_akamailog_date");
    } elseif (($this ->getVar("log_akamailog_date")) || ($this ->getVar("log_akamailog_date") === "")) {
      return $this ->getVar("log_akamailog_date");
    } elseif (($this ->LogAkamailog) || ($this ->LogAkamailog === "")){
      return $this ->LogAkamailog -> getLogAkamailogDate();
    } elseif (($this ->sessionVar("log_akamailog_date")) || ($this ->sessionVar("log_akamailog_date") == "")) {
      return $this ->sessionVar("log_akamailog_date");
    } else {
      return false;
    }
  }
  
  function setLogAkamailogDate( $str ) {
    $this ->LogAkamailog -> setLogAkamailogDate( $str );
  }
  
  function getLogAkamailogTime() {
    if (($this ->postVar("log_akamailog_time")) || ($this ->postVar("log_akamailog_time") === "")) {
      return $this ->postVar("log_akamailog_time");
    } elseif (($this ->getVar("log_akamailog_time")) || ($this ->getVar("log_akamailog_time") === "")) {
      return $this ->getVar("log_akamailog_time");
    } elseif (($this ->LogAkamailog) || ($this ->LogAkamailog === "")){
      return $this ->LogAkamailog -> getLogAkamailogTime();
    } elseif (($this ->sessionVar("log_akamailog_time")) || ($this ->sessionVar("log_akamailog_time") == "")) {
      return $this ->sessionVar("log_akamailog_time");
    } else {
      return false;
    }
  }
  
  function setLogAkamailogTime( $str ) {
    $this ->LogAkamailog -> setLogAkamailogTime( $str );
  }
  
  function getLogAkamailogIp() {
    if (($this ->postVar("log_akamailog_ip")) || ($this ->postVar("log_akamailog_ip") === "")) {
      return $this ->postVar("log_akamailog_ip");
    } elseif (($this ->getVar("log_akamailog_ip")) || ($this ->getVar("log_akamailog_ip") === "")) {
      return $this ->getVar("log_akamailog_ip");
    } elseif (($this ->LogAkamailog) || ($this ->LogAkamailog === "")){
      return $this ->LogAkamailog -> getLogAkamailogIp();
    } elseif (($this ->sessionVar("log_akamailog_ip")) || ($this ->sessionVar("log_akamailog_ip") == "")) {
      return $this ->sessionVar("log_akamailog_ip");
    } else {
      return false;
    }
  }
  
  function setLogAkamailogIp( $str ) {
    $this ->LogAkamailog -> setLogAkamailogIp( $str );
  }
  
  function getLogAkamailogMethod() {
    if (($this ->postVar("log_akamailog_method")) || ($this ->postVar("log_akamailog_method") === "")) {
      return $this ->postVar("log_akamailog_method");
    } elseif (($this ->getVar("log_akamailog_method")) || ($this ->getVar("log_akamailog_method") === "")) {
      return $this ->getVar("log_akamailog_method");
    } elseif (($this ->LogAkamailog) || ($this ->LogAkamailog === "")){
      return $this ->LogAkamailog -> getLogAkamailogMethod();
    } elseif (($this ->sessionVar("log_akamailog_method")) || ($this ->sessionVar("log_akamailog_method") == "")) {
      return $this ->sessionVar("log_akamailog_method");
    } else {
      return false;
    }
  }
  
  function setLogAkamailogMethod( $str ) {
    $this ->LogAkamailog -> setLogAkamailogMethod( $str );
  }
  
  function getLogAkamailogUri() {
    if (($this ->postVar("log_akamailog_uri")) || ($this ->postVar("log_akamailog_uri") === "")) {
      return $this ->postVar("log_akamailog_uri");
    } elseif (($this ->getVar("log_akamailog_uri")) || ($this ->getVar("log_akamailog_uri") === "")) {
      return $this ->getVar("log_akamailog_uri");
    } elseif (($this ->LogAkamailog) || ($this ->LogAkamailog === "")){
      return $this ->LogAkamailog -> getLogAkamailogUri();
    } elseif (($this ->sessionVar("log_akamailog_uri")) || ($this ->sessionVar("log_akamailog_uri") == "")) {
      return $this ->sessionVar("log_akamailog_uri");
    } else {
      return false;
    }
  }
  
  function setLogAkamailogUri( $str ) {
    $this ->LogAkamailog -> setLogAkamailogUri( $str );
  }
  
  function getLogAkamailogStatus() {
    if (($this ->postVar("log_akamailog_status")) || ($this ->postVar("log_akamailog_status") === "")) {
      return $this ->postVar("log_akamailog_status");
    } elseif (($this ->getVar("log_akamailog_status")) || ($this ->getVar("log_akamailog_status") === "")) {
      return $this ->getVar("log_akamailog_status");
    } elseif (($this ->LogAkamailog) || ($this ->LogAkamailog === "")){
      return $this ->LogAkamailog -> getLogAkamailogStatus();
    } elseif (($this ->sessionVar("log_akamailog_status")) || ($this ->sessionVar("log_akamailog_status") == "")) {
      return $this ->sessionVar("log_akamailog_status");
    } else {
      return false;
    }
  }
  
  function setLogAkamailogStatus( $str ) {
    $this ->LogAkamailog -> setLogAkamailogStatus( $str );
  }
  
  function getLogAkamailogBytes() {
    if (($this ->postVar("log_akamailog_bytes")) || ($this ->postVar("log_akamailog_bytes") === "")) {
      return $this ->postVar("log_akamailog_bytes");
    } elseif (($this ->getVar("log_akamailog_bytes")) || ($this ->getVar("log_akamailog_bytes") === "")) {
      return $this ->getVar("log_akamailog_bytes");
    } elseif (($this ->LogAkamailog) || ($this ->LogAkamailog === "")){
      return $this ->LogAkamailog -> getLogAkamailogBytes();
    } elseif (($this ->sessionVar("log_akamailog_bytes")) || ($this ->sessionVar("log_akamailog_bytes") == "")) {
      return $this ->sessionVar("log_akamailog_bytes");
    } else {
      return false;
    }
  }
  
  function setLogAkamailogBytes( $str ) {
    $this ->LogAkamailog -> setLogAkamailogBytes( $str );
  }
  
  function getLogAkamailogTimetaken() {
    if (($this ->postVar("log_akamailog_timetaken")) || ($this ->postVar("log_akamailog_timetaken") === "")) {
      return $this ->postVar("log_akamailog_timetaken");
    } elseif (($this ->getVar("log_akamailog_timetaken")) || ($this ->getVar("log_akamailog_timetaken") === "")) {
      return $this ->getVar("log_akamailog_timetaken");
    } elseif (($this ->LogAkamailog) || ($this ->LogAkamailog === "")){
      return $this ->LogAkamailog -> getLogAkamailogTimetaken();
    } elseif (($this ->sessionVar("log_akamailog_timetaken")) || ($this ->sessionVar("log_akamailog_timetaken") == "")) {
      return $this ->sessionVar("log_akamailog_timetaken");
    } else {
      return false;
    }
  }
  
  function setLogAkamailogTimetaken( $str ) {
    $this ->LogAkamailog -> setLogAkamailogTimetaken( $str );
  }
  
  function getLogAkamailogReferer() {
    if (($this ->postVar("log_akamailog_referer")) || ($this ->postVar("log_akamailog_referer") === "")) {
      return $this ->postVar("log_akamailog_referer");
    } elseif (($this ->getVar("log_akamailog_referer")) || ($this ->getVar("log_akamailog_referer") === "")) {
      return $this ->getVar("log_akamailog_referer");
    } elseif (($this ->LogAkamailog) || ($this ->LogAkamailog === "")){
      return $this ->LogAkamailog -> getLogAkamailogReferer();
    } elseif (($this ->sessionVar("log_akamailog_referer")) || ($this ->sessionVar("log_akamailog_referer") == "")) {
      return $this ->sessionVar("log_akamailog_referer");
    } else {
      return false;
    }
  }
  
  function setLogAkamailogReferer( $str ) {
    $this ->LogAkamailog -> setLogAkamailogReferer( $str );
  }
  
  function getLogAkamailogUserAgent() {
    if (($this ->postVar("log_akamailog_user_agent")) || ($this ->postVar("log_akamailog_user_agent") === "")) {
      return $this ->postVar("log_akamailog_user_agent");
    } elseif (($this ->getVar("log_akamailog_user_agent")) || ($this ->getVar("log_akamailog_user_agent") === "")) {
      return $this ->getVar("log_akamailog_user_agent");
    } elseif (($this ->LogAkamailog) || ($this ->LogAkamailog === "")){
      return $this ->LogAkamailog -> getLogAkamailogUserAgent();
    } elseif (($this ->sessionVar("log_akamailog_user_agent")) || ($this ->sessionVar("log_akamailog_user_agent") == "")) {
      return $this ->sessionVar("log_akamailog_user_agent");
    } else {
      return false;
    }
  }
  
  function setLogAkamailogUserAgent( $str ) {
    $this ->LogAkamailog -> setLogAkamailogUserAgent( $str );
  }
  
  function getLogAkamailogCookie() {
    if (($this ->postVar("log_akamailog_cookie")) || ($this ->postVar("log_akamailog_cookie") === "")) {
      return $this ->postVar("log_akamailog_cookie");
    } elseif (($this ->getVar("log_akamailog_cookie")) || ($this ->getVar("log_akamailog_cookie") === "")) {
      return $this ->getVar("log_akamailog_cookie");
    } elseif (($this ->LogAkamailog) || ($this ->LogAkamailog === "")){
      return $this ->LogAkamailog -> getLogAkamailogCookie();
    } elseif (($this ->sessionVar("log_akamailog_cookie")) || ($this ->sessionVar("log_akamailog_cookie") == "")) {
      return $this ->sessionVar("log_akamailog_cookie");
    } else {
      return false;
    }
  }
  
  function setLogAkamailogCookie( $str ) {
    $this ->LogAkamailog -> setLogAkamailogCookie( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->LogAkamailog = LogAkamailogPeer::retrieveByPK( $id );
    }
    
    if ($this ->LogAkamailog ) {
       
    	       (is_numeric(WTVRcleanString($this ->LogAkamailog->getLogAkamailogId()))) ? $itemarray["log_akamailog_id"] = WTVRcleanString($this ->LogAkamailog->getLogAkamailogId()) : null;
          (WTVRcleanString($this ->LogAkamailog->getLogAkamailogDate())) ? $itemarray["log_akamailog_date"] = WTVRcleanString($this ->LogAkamailog->getLogAkamailogDate()) : null;
          (WTVRcleanString($this ->LogAkamailog->getLogAkamailogTime())) ? $itemarray["log_akamailog_time"] = WTVRcleanString($this ->LogAkamailog->getLogAkamailogTime()) : null;
          (WTVRcleanString($this ->LogAkamailog->getLogAkamailogIp())) ? $itemarray["log_akamailog_ip"] = WTVRcleanString($this ->LogAkamailog->getLogAkamailogIp()) : null;
          (WTVRcleanString($this ->LogAkamailog->getLogAkamailogMethod())) ? $itemarray["log_akamailog_method"] = WTVRcleanString($this ->LogAkamailog->getLogAkamailogMethod()) : null;
          (WTVRcleanString($this ->LogAkamailog->getLogAkamailogUri())) ? $itemarray["log_akamailog_uri"] = WTVRcleanString($this ->LogAkamailog->getLogAkamailogUri()) : null;
          (is_numeric(WTVRcleanString($this ->LogAkamailog->getLogAkamailogStatus()))) ? $itemarray["log_akamailog_status"] = WTVRcleanString($this ->LogAkamailog->getLogAkamailogStatus()) : null;
          (is_numeric(WTVRcleanString($this ->LogAkamailog->getLogAkamailogBytes()))) ? $itemarray["log_akamailog_bytes"] = WTVRcleanString($this ->LogAkamailog->getLogAkamailogBytes()) : null;
          (WTVRcleanString($this ->LogAkamailog->getLogAkamailogTimetaken())) ? $itemarray["log_akamailog_timetaken"] = WTVRcleanString($this ->LogAkamailog->getLogAkamailogTimetaken()) : null;
          (WTVRcleanString($this ->LogAkamailog->getLogAkamailogReferer())) ? $itemarray["log_akamailog_referer"] = WTVRcleanString($this ->LogAkamailog->getLogAkamailogReferer()) : null;
          (WTVRcleanString($this ->LogAkamailog->getLogAkamailogUserAgent())) ? $itemarray["log_akamailog_user_agent"] = WTVRcleanString($this ->LogAkamailog->getLogAkamailogUserAgent()) : null;
          (WTVRcleanString($this ->LogAkamailog->getLogAkamailogCookie())) ? $itemarray["log_akamailog_cookie"] = WTVRcleanString($this ->LogAkamailog->getLogAkamailogCookie()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->LogAkamailog = LogAkamailogPeer::retrieveByPK( $id );
    } elseif (! $this ->LogAkamailog) {
      $this ->LogAkamailog = new LogAkamailog;
    }
        
  	 ($this -> getLogAkamailogId())? $this ->LogAkamailog->setLogAkamailogId( WTVRcleanString( $this -> getLogAkamailogId()) ) : null;
    ($this -> getLogAkamailogDate())? $this ->LogAkamailog->setLogAkamailogDate( WTVRcleanString( $this -> getLogAkamailogDate()) ) : null;
    ($this -> getLogAkamailogTime())? $this ->LogAkamailog->setLogAkamailogTime( WTVRcleanString( $this -> getLogAkamailogTime()) ) : null;
    ($this -> getLogAkamailogIp())? $this ->LogAkamailog->setLogAkamailogIp( WTVRcleanString( $this -> getLogAkamailogIp()) ) : null;
    ($this -> getLogAkamailogMethod())? $this ->LogAkamailog->setLogAkamailogMethod( WTVRcleanString( $this -> getLogAkamailogMethod()) ) : null;
    ($this -> getLogAkamailogUri())? $this ->LogAkamailog->setLogAkamailogUri( WTVRcleanString( $this -> getLogAkamailogUri()) ) : null;
    ($this -> getLogAkamailogStatus())? $this ->LogAkamailog->setLogAkamailogStatus( WTVRcleanString( $this -> getLogAkamailogStatus()) ) : null;
    ($this -> getLogAkamailogBytes())? $this ->LogAkamailog->setLogAkamailogBytes( WTVRcleanString( $this -> getLogAkamailogBytes()) ) : null;
    ($this -> getLogAkamailogTimetaken())? $this ->LogAkamailog->setLogAkamailogTimetaken( WTVRcleanString( $this -> getLogAkamailogTimetaken()) ) : null;
    ($this -> getLogAkamailogReferer())? $this ->LogAkamailog->setLogAkamailogReferer( WTVRcleanString( $this -> getLogAkamailogReferer()) ) : null;
    ($this -> getLogAkamailogUserAgent())? $this ->LogAkamailog->setLogAkamailogUserAgent( WTVRcleanString( $this -> getLogAkamailogUserAgent()) ) : null;
    ($this -> getLogAkamailogCookie())? $this ->LogAkamailog->setLogAkamailogCookie( WTVRcleanString( $this -> getLogAkamailogCookie()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->LogAkamailog ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->LogAkamailog = LogAkamailogPeer::retrieveByPK($id);
    }
    
    if (! $this ->LogAkamailog ) {
      return;
    }
    
    $this ->LogAkamailog -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('LogAkamailog_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "LogAkamailogPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $LogAkamailog = LogAkamailogPeer::doSelect($c);
    
    if (count($LogAkamailog) >= 1) {
      $this ->LogAkamailog = $LogAkamailog[0];
      return true;
    } else {
      $this ->LogAkamailog = new LogAkamailog();
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
      $name = "LogAkamailogPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $LogAkamailog = LogAkamailogPeer::doSelect($c);
    
    if (count($LogAkamailog) >= 1) {
      $this ->LogAkamailog = $LogAkamailog[0];
      return true;
    } else {
      $this ->LogAkamailog = new LogAkamailog();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>