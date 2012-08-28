<?php
       
   class XhrSeleniumLogCrudBase extends Utils_PageWidget { 
   
    var $XhrSeleniumLog;
   
       var $xhr_selenium_log_id;
   var $xhr_selenium_log_action;
   var $xhr_selenium_log_result;
   var $xhr_selenium_log_browser;
   var $xhr_selenium_log_date;
   var $xhr_selenium_log_group;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getXhrSeleniumLogId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->XhrSeleniumLog = XhrSeleniumLogPeer::retrieveByPK( $id );
    } else {
      $this ->XhrSeleniumLog = new XhrSeleniumLog;
    }
  }
  
  function hydrate( $id ) {
      $this ->XhrSeleniumLog = XhrSeleniumLogPeer::retrieveByPK( $id );
  }
  
  function getXhrSeleniumLogId() {
    if (($this ->postVar("xhr_selenium_log_id")) || ($this ->postVar("xhr_selenium_log_id") === "")) {
      return $this ->postVar("xhr_selenium_log_id");
    } elseif (($this ->getVar("xhr_selenium_log_id")) || ($this ->getVar("xhr_selenium_log_id") === "")) {
      return $this ->getVar("xhr_selenium_log_id");
    } elseif (($this ->XhrSeleniumLog) || ($this ->XhrSeleniumLog === "")){
      return $this ->XhrSeleniumLog -> getXhrSeleniumLogId();
    } elseif (($this ->sessionVar("xhr_selenium_log_id")) || ($this ->sessionVar("xhr_selenium_log_id") == "")) {
      return $this ->sessionVar("xhr_selenium_log_id");
    } else {
      return false;
    }
  }
  
  function setXhrSeleniumLogId( $str ) {
    $this ->XhrSeleniumLog -> setXhrSeleniumLogId( $str );
  }
  
  function getXhrSeleniumLogAction() {
    if (($this ->postVar("xhr_selenium_log_action")) || ($this ->postVar("xhr_selenium_log_action") === "")) {
      return $this ->postVar("xhr_selenium_log_action");
    } elseif (($this ->getVar("xhr_selenium_log_action")) || ($this ->getVar("xhr_selenium_log_action") === "")) {
      return $this ->getVar("xhr_selenium_log_action");
    } elseif (($this ->XhrSeleniumLog) || ($this ->XhrSeleniumLog === "")){
      return $this ->XhrSeleniumLog -> getXhrSeleniumLogAction();
    } elseif (($this ->sessionVar("xhr_selenium_log_action")) || ($this ->sessionVar("xhr_selenium_log_action") == "")) {
      return $this ->sessionVar("xhr_selenium_log_action");
    } else {
      return false;
    }
  }
  
  function setXhrSeleniumLogAction( $str ) {
    $this ->XhrSeleniumLog -> setXhrSeleniumLogAction( $str );
  }
  
  function getXhrSeleniumLogResult() {
    if (($this ->postVar("xhr_selenium_log_result")) || ($this ->postVar("xhr_selenium_log_result") === "")) {
      return $this ->postVar("xhr_selenium_log_result");
    } elseif (($this ->getVar("xhr_selenium_log_result")) || ($this ->getVar("xhr_selenium_log_result") === "")) {
      return $this ->getVar("xhr_selenium_log_result");
    } elseif (($this ->XhrSeleniumLog) || ($this ->XhrSeleniumLog === "")){
      return $this ->XhrSeleniumLog -> getXhrSeleniumLogResult();
    } elseif (($this ->sessionVar("xhr_selenium_log_result")) || ($this ->sessionVar("xhr_selenium_log_result") == "")) {
      return $this ->sessionVar("xhr_selenium_log_result");
    } else {
      return false;
    }
  }
  
  function setXhrSeleniumLogResult( $str ) {
    $this ->XhrSeleniumLog -> setXhrSeleniumLogResult( $str );
  }
  
  function getXhrSeleniumLogBrowser() {
    if (($this ->postVar("xhr_selenium_log_browser")) || ($this ->postVar("xhr_selenium_log_browser") === "")) {
      return $this ->postVar("xhr_selenium_log_browser");
    } elseif (($this ->getVar("xhr_selenium_log_browser")) || ($this ->getVar("xhr_selenium_log_browser") === "")) {
      return $this ->getVar("xhr_selenium_log_browser");
    } elseif (($this ->XhrSeleniumLog) || ($this ->XhrSeleniumLog === "")){
      return $this ->XhrSeleniumLog -> getXhrSeleniumLogBrowser();
    } elseif (($this ->sessionVar("xhr_selenium_log_browser")) || ($this ->sessionVar("xhr_selenium_log_browser") == "")) {
      return $this ->sessionVar("xhr_selenium_log_browser");
    } else {
      return false;
    }
  }
  
  function setXhrSeleniumLogBrowser( $str ) {
    $this ->XhrSeleniumLog -> setXhrSeleniumLogBrowser( $str );
  }
  
  function getXhrSeleniumLogDate() {
    if (($this ->postVar("xhr_selenium_log_date")) || ($this ->postVar("xhr_selenium_log_date") === "")) {
      return $this ->postVar("xhr_selenium_log_date");
    } elseif (($this ->getVar("xhr_selenium_log_date")) || ($this ->getVar("xhr_selenium_log_date") === "")) {
      return $this ->getVar("xhr_selenium_log_date");
    } elseif (($this ->XhrSeleniumLog) || ($this ->XhrSeleniumLog === "")){
      return $this ->XhrSeleniumLog -> getXhrSeleniumLogDate();
    } elseif (($this ->sessionVar("xhr_selenium_log_date")) || ($this ->sessionVar("xhr_selenium_log_date") == "")) {
      return $this ->sessionVar("xhr_selenium_log_date");
    } else {
      return false;
    }
  }
  
  function setXhrSeleniumLogDate( $str ) {
    $this ->XhrSeleniumLog -> setXhrSeleniumLogDate( $str );
  }
  
  function getXhrSeleniumLogGroup() {
    if (($this ->postVar("xhr_selenium_log_group")) || ($this ->postVar("xhr_selenium_log_group") === "")) {
      return $this ->postVar("xhr_selenium_log_group");
    } elseif (($this ->getVar("xhr_selenium_log_group")) || ($this ->getVar("xhr_selenium_log_group") === "")) {
      return $this ->getVar("xhr_selenium_log_group");
    } elseif (($this ->XhrSeleniumLog) || ($this ->XhrSeleniumLog === "")){
      return $this ->XhrSeleniumLog -> getXhrSeleniumLogGroup();
    } elseif (($this ->sessionVar("xhr_selenium_log_group")) || ($this ->sessionVar("xhr_selenium_log_group") == "")) {
      return $this ->sessionVar("xhr_selenium_log_group");
    } else {
      return false;
    }
  }
  
  function setXhrSeleniumLogGroup( $str ) {
    $this ->XhrSeleniumLog -> setXhrSeleniumLogGroup( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->XhrSeleniumLog = XhrSeleniumLogPeer::retrieveByPK( $id );
    }
    
    if ($this ->XhrSeleniumLog ) {
       
    	       (is_numeric(WTVRcleanString($this ->XhrSeleniumLog->getXhrSeleniumLogId()))) ? $itemarray["xhr_selenium_log_id"] = WTVRcleanString($this ->XhrSeleniumLog->getXhrSeleniumLogId()) : null;
          (WTVRcleanString($this ->XhrSeleniumLog->getXhrSeleniumLogAction())) ? $itemarray["xhr_selenium_log_action"] = WTVRcleanString($this ->XhrSeleniumLog->getXhrSeleniumLogAction()) : null;
          (WTVRcleanString($this ->XhrSeleniumLog->getXhrSeleniumLogResult())) ? $itemarray["xhr_selenium_log_result"] = WTVRcleanString($this ->XhrSeleniumLog->getXhrSeleniumLogResult()) : null;
          (WTVRcleanString($this ->XhrSeleniumLog->getXhrSeleniumLogBrowser())) ? $itemarray["xhr_selenium_log_browser"] = WTVRcleanString($this ->XhrSeleniumLog->getXhrSeleniumLogBrowser()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->XhrSeleniumLog->getXhrSeleniumLogDate())) ? $itemarray["xhr_selenium_log_date"] = formatDate($this ->XhrSeleniumLog->getXhrSeleniumLogDate('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->XhrSeleniumLog->getXhrSeleniumLogGroup())) ? $itemarray["xhr_selenium_log_group"] = WTVRcleanString($this ->XhrSeleniumLog->getXhrSeleniumLogGroup()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->XhrSeleniumLog = XhrSeleniumLogPeer::retrieveByPK( $id );
    } elseif (! $this ->XhrSeleniumLog) {
      $this ->XhrSeleniumLog = new XhrSeleniumLog;
    }
        
  	 ($this -> getXhrSeleniumLogId())? $this ->XhrSeleniumLog->setXhrSeleniumLogId( WTVRcleanString( $this -> getXhrSeleniumLogId()) ) : null;
    ($this -> getXhrSeleniumLogAction())? $this ->XhrSeleniumLog->setXhrSeleniumLogAction( WTVRcleanString( $this -> getXhrSeleniumLogAction()) ) : null;
    ($this -> getXhrSeleniumLogResult())? $this ->XhrSeleniumLog->setXhrSeleniumLogResult( WTVRcleanString( $this -> getXhrSeleniumLogResult()) ) : null;
    ($this -> getXhrSeleniumLogBrowser())? $this ->XhrSeleniumLog->setXhrSeleniumLogBrowser( WTVRcleanString( $this -> getXhrSeleniumLogBrowser()) ) : null;
          if (is_valid_date( $this ->XhrSeleniumLog->getXhrSeleniumLogDate())) {
        $this ->XhrSeleniumLog->setXhrSeleniumLogDate( formatDate($this -> getXhrSeleniumLogDate(), "TS" ));
      } else {
      $XhrSeleniumLogxhr_selenium_log_date = $this -> sfDateTime( "xhr_selenium_log_date" );
      ( $XhrSeleniumLogxhr_selenium_log_date != "01/01/1900 00:00:00" )? $this ->XhrSeleniumLog->setXhrSeleniumLogDate( formatDate($XhrSeleniumLogxhr_selenium_log_date, "TS" )) : $this ->XhrSeleniumLog->setXhrSeleniumLogDate( null );
      }
    ($this -> getXhrSeleniumLogGroup())? $this ->XhrSeleniumLog->setXhrSeleniumLogGroup( WTVRcleanString( $this -> getXhrSeleniumLogGroup()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->XhrSeleniumLog ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->XhrSeleniumLog = XhrSeleniumLogPeer::retrieveByPK($id);
    }
    
    if (! $this ->XhrSeleniumLog ) {
      return;
    }
    
    $this ->XhrSeleniumLog -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('XhrSeleniumLog_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "XhrSeleniumLogPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $XhrSeleniumLog = XhrSeleniumLogPeer::doSelect($c);
    
    if (count($XhrSeleniumLog) >= 1) {
      $this ->XhrSeleniumLog = $XhrSeleniumLog[0];
      return true;
    } else {
      $this ->XhrSeleniumLog = new XhrSeleniumLog();
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
      $name = "XhrSeleniumLogPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $XhrSeleniumLog = XhrSeleniumLogPeer::doSelect($c);
    
    if (count($XhrSeleniumLog) >= 1) {
      $this ->XhrSeleniumLog = $XhrSeleniumLog[0];
      return true;
    } else {
      $this ->XhrSeleniumLog = new XhrSeleniumLog();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>