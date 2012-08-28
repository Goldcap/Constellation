<?php
       
   class SeleniumTestResultCrudBase extends Utils_PageWidget { 
   
    var $SeleniumTestResult;
   
       var $selenium_test_result_id;
   var $fk_selenium_test_id;
   var $selenium_test_result_date;
   var $selenium_test_result_result;
   var $selenium_test_result_failure;
   var $selenium_test_result_success;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getSeleniumTestResultId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->SeleniumTestResult = SeleniumTestResultPeer::retrieveByPK( $id );
    } else {
      $this ->SeleniumTestResult = new SeleniumTestResult;
    }
  }
  
  function hydrate( $id ) {
      $this ->SeleniumTestResult = SeleniumTestResultPeer::retrieveByPK( $id );
  }
  
  function getSeleniumTestResultId() {
    if (($this ->postVar("selenium_test_result_id")) || ($this ->postVar("selenium_test_result_id") === "")) {
      return $this ->postVar("selenium_test_result_id");
    } elseif (($this ->getVar("selenium_test_result_id")) || ($this ->getVar("selenium_test_result_id") === "")) {
      return $this ->getVar("selenium_test_result_id");
    } elseif (($this ->SeleniumTestResult) || ($this ->SeleniumTestResult === "")){
      return $this ->SeleniumTestResult -> getSeleniumTestResultId();
    } elseif (($this ->sessionVar("selenium_test_result_id")) || ($this ->sessionVar("selenium_test_result_id") == "")) {
      return $this ->sessionVar("selenium_test_result_id");
    } else {
      return false;
    }
  }
  
  function setSeleniumTestResultId( $str ) {
    $this ->SeleniumTestResult -> setSeleniumTestResultId( $str );
  }
  
  function getFkSeleniumTestId() {
    if (($this ->postVar("fk_selenium_test_id")) || ($this ->postVar("fk_selenium_test_id") === "")) {
      return $this ->postVar("fk_selenium_test_id");
    } elseif (($this ->getVar("fk_selenium_test_id")) || ($this ->getVar("fk_selenium_test_id") === "")) {
      return $this ->getVar("fk_selenium_test_id");
    } elseif (($this ->SeleniumTestResult) || ($this ->SeleniumTestResult === "")){
      return $this ->SeleniumTestResult -> getFkSeleniumTestId();
    } elseif (($this ->sessionVar("fk_selenium_test_id")) || ($this ->sessionVar("fk_selenium_test_id") == "")) {
      return $this ->sessionVar("fk_selenium_test_id");
    } else {
      return false;
    }
  }
  
  function setFkSeleniumTestId( $str ) {
    $this ->SeleniumTestResult -> setFkSeleniumTestId( $str );
  }
  
  function getSeleniumTestResultDate() {
    if (($this ->postVar("selenium_test_result_date")) || ($this ->postVar("selenium_test_result_date") === "")) {
      return $this ->postVar("selenium_test_result_date");
    } elseif (($this ->getVar("selenium_test_result_date")) || ($this ->getVar("selenium_test_result_date") === "")) {
      return $this ->getVar("selenium_test_result_date");
    } elseif (($this ->SeleniumTestResult) || ($this ->SeleniumTestResult === "")){
      return $this ->SeleniumTestResult -> getSeleniumTestResultDate();
    } elseif (($this ->sessionVar("selenium_test_result_date")) || ($this ->sessionVar("selenium_test_result_date") == "")) {
      return $this ->sessionVar("selenium_test_result_date");
    } else {
      return false;
    }
  }
  
  function setSeleniumTestResultDate( $str ) {
    $this ->SeleniumTestResult -> setSeleniumTestResultDate( $str );
  }
  
  function getSeleniumTestResultResult() {
    if (($this ->postVar("selenium_test_result_result")) || ($this ->postVar("selenium_test_result_result") === "")) {
      return $this ->postVar("selenium_test_result_result");
    } elseif (($this ->getVar("selenium_test_result_result")) || ($this ->getVar("selenium_test_result_result") === "")) {
      return $this ->getVar("selenium_test_result_result");
    } elseif (($this ->SeleniumTestResult) || ($this ->SeleniumTestResult === "")){
      return $this ->SeleniumTestResult -> getSeleniumTestResultResult();
    } elseif (($this ->sessionVar("selenium_test_result_result")) || ($this ->sessionVar("selenium_test_result_result") == "")) {
      return $this ->sessionVar("selenium_test_result_result");
    } else {
      return false;
    }
  }
  
  function setSeleniumTestResultResult( $str ) {
    $this ->SeleniumTestResult -> setSeleniumTestResultResult( $str );
  }
  
  function getSeleniumTestResultFailure() {
    if (($this ->postVar("selenium_test_result_failure")) || ($this ->postVar("selenium_test_result_failure") === "")) {
      return $this ->postVar("selenium_test_result_failure");
    } elseif (($this ->getVar("selenium_test_result_failure")) || ($this ->getVar("selenium_test_result_failure") === "")) {
      return $this ->getVar("selenium_test_result_failure");
    } elseif (($this ->SeleniumTestResult) || ($this ->SeleniumTestResult === "")){
      return $this ->SeleniumTestResult -> getSeleniumTestResultFailure();
    } elseif (($this ->sessionVar("selenium_test_result_failure")) || ($this ->sessionVar("selenium_test_result_failure") == "")) {
      return $this ->sessionVar("selenium_test_result_failure");
    } else {
      return false;
    }
  }
  
  function setSeleniumTestResultFailure( $str ) {
    $this ->SeleniumTestResult -> setSeleniumTestResultFailure( $str );
  }
  
  function getSeleniumTestResultSuccess() {
    if (($this ->postVar("selenium_test_result_success")) || ($this ->postVar("selenium_test_result_success") === "")) {
      return $this ->postVar("selenium_test_result_success");
    } elseif (($this ->getVar("selenium_test_result_success")) || ($this ->getVar("selenium_test_result_success") === "")) {
      return $this ->getVar("selenium_test_result_success");
    } elseif (($this ->SeleniumTestResult) || ($this ->SeleniumTestResult === "")){
      return $this ->SeleniumTestResult -> getSeleniumTestResultSuccess();
    } elseif (($this ->sessionVar("selenium_test_result_success")) || ($this ->sessionVar("selenium_test_result_success") == "")) {
      return $this ->sessionVar("selenium_test_result_success");
    } else {
      return false;
    }
  }
  
  function setSeleniumTestResultSuccess( $str ) {
    $this ->SeleniumTestResult -> setSeleniumTestResultSuccess( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->SeleniumTestResult = SeleniumTestResultPeer::retrieveByPK( $id );
    }
    
    if ($this ->SeleniumTestResult ) {
       
    	       (is_numeric(WTVRcleanString($this ->SeleniumTestResult->getSeleniumTestResultId()))) ? $itemarray["selenium_test_result_id"] = WTVRcleanString($this ->SeleniumTestResult->getSeleniumTestResultId()) : null;
          (is_numeric(WTVRcleanString($this ->SeleniumTestResult->getFkSeleniumTestId()))) ? $itemarray["fk_selenium_test_id"] = WTVRcleanString($this ->SeleniumTestResult->getFkSeleniumTestId()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->SeleniumTestResult->getSeleniumTestResultDate())) ? $itemarray["selenium_test_result_date"] = formatDate($this ->SeleniumTestResult->getSeleniumTestResultDate('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->SeleniumTestResult->getSeleniumTestResultResult())) ? $itemarray["selenium_test_result_result"] = WTVRcleanString($this ->SeleniumTestResult->getSeleniumTestResultResult()) : null;
          (is_numeric(WTVRcleanString($this ->SeleniumTestResult->getSeleniumTestResultFailure()))) ? $itemarray["selenium_test_result_failure"] = WTVRcleanString($this ->SeleniumTestResult->getSeleniumTestResultFailure()) : null;
          (is_numeric(WTVRcleanString($this ->SeleniumTestResult->getSeleniumTestResultSuccess()))) ? $itemarray["selenium_test_result_success"] = WTVRcleanString($this ->SeleniumTestResult->getSeleniumTestResultSuccess()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->SeleniumTestResult = SeleniumTestResultPeer::retrieveByPK( $id );
    } elseif (! $this ->SeleniumTestResult) {
      $this ->SeleniumTestResult = new SeleniumTestResult;
    }
        
  	 ($this -> getSeleniumTestResultId())? $this ->SeleniumTestResult->setSeleniumTestResultId( WTVRcleanString( $this -> getSeleniumTestResultId()) ) : null;
    ($this -> getFkSeleniumTestId())? $this ->SeleniumTestResult->setFkSeleniumTestId( WTVRcleanString( $this -> getFkSeleniumTestId()) ) : null;
          if (is_valid_date( $this ->SeleniumTestResult->getSeleniumTestResultDate())) {
        $this ->SeleniumTestResult->setSeleniumTestResultDate( formatDate($this -> getSeleniumTestResultDate(), "TS" ));
      } else {
      $SeleniumTestResultselenium_test_result_date = $this -> sfDateTime( "selenium_test_result_date" );
      ( $SeleniumTestResultselenium_test_result_date != "01/01/1900 00:00:00" )? $this ->SeleniumTestResult->setSeleniumTestResultDate( formatDate($SeleniumTestResultselenium_test_result_date, "TS" )) : $this ->SeleniumTestResult->setSeleniumTestResultDate( null );
      }
    ($this -> getSeleniumTestResultResult())? $this ->SeleniumTestResult->setSeleniumTestResultResult( WTVRcleanString( $this -> getSeleniumTestResultResult()) ) : null;
    ($this -> getSeleniumTestResultFailure())? $this ->SeleniumTestResult->setSeleniumTestResultFailure( WTVRcleanString( $this -> getSeleniumTestResultFailure()) ) : null;
    ($this -> getSeleniumTestResultSuccess())? $this ->SeleniumTestResult->setSeleniumTestResultSuccess( WTVRcleanString( $this -> getSeleniumTestResultSuccess()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->SeleniumTestResult ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->SeleniumTestResult = SeleniumTestResultPeer::retrieveByPK($id);
    }
    
    if (! $this ->SeleniumTestResult ) {
      return;
    }
    
    $this ->SeleniumTestResult -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('SeleniumTestResult_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "SeleniumTestResultPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $SeleniumTestResult = SeleniumTestResultPeer::doSelect($c);
    
    if (count($SeleniumTestResult) >= 1) {
      $this ->SeleniumTestResult = $SeleniumTestResult[0];
      return true;
    } else {
      $this ->SeleniumTestResult = new SeleniumTestResult();
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
      $name = "SeleniumTestResultPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $SeleniumTestResult = SeleniumTestResultPeer::doSelect($c);
    
    if (count($SeleniumTestResult) >= 1) {
      $this ->SeleniumTestResult = $SeleniumTestResult[0];
      return true;
    } else {
      $this ->SeleniumTestResult = new SeleniumTestResult();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>