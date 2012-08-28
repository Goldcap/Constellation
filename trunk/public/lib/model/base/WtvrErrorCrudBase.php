<?php
       
   class WtvrErrorCrudBase extends Utils_PageWidget { 
   
    var $WtvrError;
   
       var $error_id;
   var $error_no;
   var $error_code;
   var $error_line;
   var $error_file;
   var $error_context;
   var $ip_address;
   var $user_agent;
   var $referring_url;
   var $error_date;
   var $is_php;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getErrorId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->WtvrError = WtvrErrorPeer::retrieveByPK( $id );
    } else {
      $this ->WtvrError = new WtvrError;
    }
  }
  
  function hydrate( $id ) {
      $this ->WtvrError = WtvrErrorPeer::retrieveByPK( $id );
  }
  
  function getErrorId() {
    if (($this ->postVar("error_id")) || ($this ->postVar("error_id") === "")) {
      return $this ->postVar("error_id");
    } elseif (($this ->getVar("error_id")) || ($this ->getVar("error_id") === "")) {
      return $this ->getVar("error_id");
    } elseif (($this ->WtvrError) || ($this ->WtvrError === "")){
      return $this ->WtvrError -> getErrorId();
    } elseif (($this ->sessionVar("error_id")) || ($this ->sessionVar("error_id") == "")) {
      return $this ->sessionVar("error_id");
    } else {
      return false;
    }
  }
  
  function setErrorId( $str ) {
    $this ->WtvrError -> setErrorId( $str );
  }
  
  function getErrorNo() {
    if (($this ->postVar("error_no")) || ($this ->postVar("error_no") === "")) {
      return $this ->postVar("error_no");
    } elseif (($this ->getVar("error_no")) || ($this ->getVar("error_no") === "")) {
      return $this ->getVar("error_no");
    } elseif (($this ->WtvrError) || ($this ->WtvrError === "")){
      return $this ->WtvrError -> getErrorNo();
    } elseif (($this ->sessionVar("error_no")) || ($this ->sessionVar("error_no") == "")) {
      return $this ->sessionVar("error_no");
    } else {
      return false;
    }
  }
  
  function setErrorNo( $str ) {
    $this ->WtvrError -> setErrorNo( $str );
  }
  
  function getErrorCode() {
    if (($this ->postVar("error_code")) || ($this ->postVar("error_code") === "")) {
      return $this ->postVar("error_code");
    } elseif (($this ->getVar("error_code")) || ($this ->getVar("error_code") === "")) {
      return $this ->getVar("error_code");
    } elseif (($this ->WtvrError) || ($this ->WtvrError === "")){
      return $this ->WtvrError -> getErrorCode();
    } elseif (($this ->sessionVar("error_code")) || ($this ->sessionVar("error_code") == "")) {
      return $this ->sessionVar("error_code");
    } else {
      return false;
    }
  }
  
  function setErrorCode( $str ) {
    $this ->WtvrError -> setErrorCode( $str );
  }
  
  function getErrorLine() {
    if (($this ->postVar("error_line")) || ($this ->postVar("error_line") === "")) {
      return $this ->postVar("error_line");
    } elseif (($this ->getVar("error_line")) || ($this ->getVar("error_line") === "")) {
      return $this ->getVar("error_line");
    } elseif (($this ->WtvrError) || ($this ->WtvrError === "")){
      return $this ->WtvrError -> getErrorLine();
    } elseif (($this ->sessionVar("error_line")) || ($this ->sessionVar("error_line") == "")) {
      return $this ->sessionVar("error_line");
    } else {
      return false;
    }
  }
  
  function setErrorLine( $str ) {
    $this ->WtvrError -> setErrorLine( $str );
  }
  
  function getErrorFile() {
    if (($this ->postVar("error_file")) || ($this ->postVar("error_file") === "")) {
      return $this ->postVar("error_file");
    } elseif (($this ->getVar("error_file")) || ($this ->getVar("error_file") === "")) {
      return $this ->getVar("error_file");
    } elseif (($this ->WtvrError) || ($this ->WtvrError === "")){
      return $this ->WtvrError -> getErrorFile();
    } elseif (($this ->sessionVar("error_file")) || ($this ->sessionVar("error_file") == "")) {
      return $this ->sessionVar("error_file");
    } else {
      return false;
    }
  }
  
  function setErrorFile( $str ) {
    $this ->WtvrError -> setErrorFile( $str );
  }
  
  function getErrorContext() {
    if (($this ->postVar("error_context")) || ($this ->postVar("error_context") === "")) {
      return $this ->postVar("error_context");
    } elseif (($this ->getVar("error_context")) || ($this ->getVar("error_context") === "")) {
      return $this ->getVar("error_context");
    } elseif (($this ->WtvrError) || ($this ->WtvrError === "")){
      return $this ->WtvrError -> getErrorContext();
    } elseif (($this ->sessionVar("error_context")) || ($this ->sessionVar("error_context") == "")) {
      return $this ->sessionVar("error_context");
    } else {
      return false;
    }
  }
  
  function setErrorContext( $str ) {
    $this ->WtvrError -> setErrorContext( $str );
  }
  
  function getIpAddress() {
    if (($this ->postVar("ip_address")) || ($this ->postVar("ip_address") === "")) {
      return $this ->postVar("ip_address");
    } elseif (($this ->getVar("ip_address")) || ($this ->getVar("ip_address") === "")) {
      return $this ->getVar("ip_address");
    } elseif (($this ->WtvrError) || ($this ->WtvrError === "")){
      return $this ->WtvrError -> getIpAddress();
    } elseif (($this ->sessionVar("ip_address")) || ($this ->sessionVar("ip_address") == "")) {
      return $this ->sessionVar("ip_address");
    } else {
      return false;
    }
  }
  
  function setIpAddress( $str ) {
    $this ->WtvrError -> setIpAddress( $str );
  }
  
  function getUserAgent() {
    if (($this ->postVar("user_agent")) || ($this ->postVar("user_agent") === "")) {
      return $this ->postVar("user_agent");
    } elseif (($this ->getVar("user_agent")) || ($this ->getVar("user_agent") === "")) {
      return $this ->getVar("user_agent");
    } elseif (($this ->WtvrError) || ($this ->WtvrError === "")){
      return $this ->WtvrError -> getUserAgent();
    } elseif (($this ->sessionVar("user_agent")) || ($this ->sessionVar("user_agent") == "")) {
      return $this ->sessionVar("user_agent");
    } else {
      return false;
    }
  }
  
  function setUserAgent( $str ) {
    $this ->WtvrError -> setUserAgent( $str );
  }
  
  function getReferringUrl() {
    if (($this ->postVar("referring_url")) || ($this ->postVar("referring_url") === "")) {
      return $this ->postVar("referring_url");
    } elseif (($this ->getVar("referring_url")) || ($this ->getVar("referring_url") === "")) {
      return $this ->getVar("referring_url");
    } elseif (($this ->WtvrError) || ($this ->WtvrError === "")){
      return $this ->WtvrError -> getReferringUrl();
    } elseif (($this ->sessionVar("referring_url")) || ($this ->sessionVar("referring_url") == "")) {
      return $this ->sessionVar("referring_url");
    } else {
      return false;
    }
  }
  
  function setReferringUrl( $str ) {
    $this ->WtvrError -> setReferringUrl( $str );
  }
  
  function getErrorDate() {
    if (($this ->postVar("error_date")) || ($this ->postVar("error_date") === "")) {
      return $this ->postVar("error_date");
    } elseif (($this ->getVar("error_date")) || ($this ->getVar("error_date") === "")) {
      return $this ->getVar("error_date");
    } elseif (($this ->WtvrError) || ($this ->WtvrError === "")){
      return $this ->WtvrError -> getErrorDate();
    } elseif (($this ->sessionVar("error_date")) || ($this ->sessionVar("error_date") == "")) {
      return $this ->sessionVar("error_date");
    } else {
      return false;
    }
  }
  
  function setErrorDate( $str ) {
    $this ->WtvrError -> setErrorDate( $str );
  }
  
  function getIsPhp() {
    if (($this ->postVar("is_php")) || ($this ->postVar("is_php") === "")) {
      return $this ->postVar("is_php");
    } elseif (($this ->getVar("is_php")) || ($this ->getVar("is_php") === "")) {
      return $this ->getVar("is_php");
    } elseif (($this ->WtvrError) || ($this ->WtvrError === "")){
      return $this ->WtvrError -> getIsPhp();
    } elseif (($this ->sessionVar("is_php")) || ($this ->sessionVar("is_php") == "")) {
      return $this ->sessionVar("is_php");
    } else {
      return false;
    }
  }
  
  function setIsPhp( $str ) {
    $this ->WtvrError -> setIsPhp( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->WtvrError = WtvrErrorPeer::retrieveByPK( $id );
    }
    
    if ($this ->WtvrError ) {
       
    	       (is_numeric(WTVRcleanString($this ->WtvrError->getErrorId()))) ? $itemarray["error_id"] = WTVRcleanString($this ->WtvrError->getErrorId()) : null;
          (WTVRcleanString($this ->WtvrError->getErrorNo())) ? $itemarray["error_no"] = WTVRcleanString($this ->WtvrError->getErrorNo()) : null;
          (WTVRcleanString($this ->WtvrError->getErrorCode())) ? $itemarray["error_code"] = WTVRcleanString($this ->WtvrError->getErrorCode()) : null;
          (WTVRcleanString($this ->WtvrError->getErrorLine())) ? $itemarray["error_line"] = WTVRcleanString($this ->WtvrError->getErrorLine()) : null;
          (WTVRcleanString($this ->WtvrError->getErrorFile())) ? $itemarray["error_file"] = WTVRcleanString($this ->WtvrError->getErrorFile()) : null;
          (WTVRcleanString($this ->WtvrError->getErrorContext())) ? $itemarray["error_context"] = WTVRcleanString($this ->WtvrError->getErrorContext()) : null;
          (WTVRcleanString($this ->WtvrError->getIpAddress())) ? $itemarray["ip_address"] = WTVRcleanString($this ->WtvrError->getIpAddress()) : null;
          (WTVRcleanString($this ->WtvrError->getUserAgent())) ? $itemarray["user_agent"] = WTVRcleanString($this ->WtvrError->getUserAgent()) : null;
          (WTVRcleanString($this ->WtvrError->getReferringUrl())) ? $itemarray["referring_url"] = WTVRcleanString($this ->WtvrError->getReferringUrl()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->WtvrError->getErrorDate())) ? $itemarray["error_date"] = formatDate($this ->WtvrError->getErrorDate('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->WtvrError->getIsPhp())) ? $itemarray["is_php"] = WTVRcleanString($this ->WtvrError->getIsPhp()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->WtvrError = WtvrErrorPeer::retrieveByPK( $id );
    } elseif (! $this ->WtvrError) {
      $this ->WtvrError = new WtvrError;
    }
        
  	 ($this -> getErrorId())? $this ->WtvrError->setErrorId( WTVRcleanString( $this -> getErrorId()) ) : null;
    ($this -> getErrorNo())? $this ->WtvrError->setErrorNo( WTVRcleanString( $this -> getErrorNo()) ) : null;
    ($this -> getErrorCode())? $this ->WtvrError->setErrorCode( WTVRcleanString( $this -> getErrorCode()) ) : null;
    ($this -> getErrorLine())? $this ->WtvrError->setErrorLine( WTVRcleanString( $this -> getErrorLine()) ) : null;
    ($this -> getErrorFile())? $this ->WtvrError->setErrorFile( WTVRcleanString( $this -> getErrorFile()) ) : null;
    ($this -> getErrorContext())? $this ->WtvrError->setErrorContext( WTVRcleanString( $this -> getErrorContext()) ) : null;
    ($this -> getIpAddress())? $this ->WtvrError->setIpAddress( WTVRcleanString( $this -> getIpAddress()) ) : null;
    ($this -> getUserAgent())? $this ->WtvrError->setUserAgent( WTVRcleanString( $this -> getUserAgent()) ) : null;
    ($this -> getReferringUrl())? $this ->WtvrError->setReferringUrl( WTVRcleanString( $this -> getReferringUrl()) ) : null;
          if (is_valid_date( $this ->WtvrError->getErrorDate())) {
        $this ->WtvrError->setErrorDate( formatDate($this -> getErrorDate(), "TS" ));
      } else {
      $WtvrErrorerror_date = $this -> sfDateTime( "error_date" );
      ( $WtvrErrorerror_date != "01/01/1900 00:00:00" )? $this ->WtvrError->setErrorDate( formatDate($WtvrErrorerror_date, "TS" )) : $this ->WtvrError->setErrorDate( null );
      }
    ($this -> getIsPhp())? $this ->WtvrError->setIsPhp( WTVRcleanString( $this -> getIsPhp()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->WtvrError ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->WtvrError = WtvrErrorPeer::retrieveByPK($id);
    }
    
    if (! $this ->WtvrError ) {
      return;
    }
    
    $this ->WtvrError -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('WtvrError_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "WtvrErrorPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $WtvrError = WtvrErrorPeer::doSelect($c);
    
    if (count($WtvrError) >= 1) {
      $this ->WtvrError = $WtvrError[0];
      return true;
    } else {
      $this ->WtvrError = new WtvrError();
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
      $name = "WtvrErrorPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $WtvrError = WtvrErrorPeer::doSelect($c);
    
    if (count($WtvrError) >= 1) {
      $this ->WtvrError = $WtvrError[0];
      return true;
    } else {
      $this ->WtvrError = new WtvrError();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>