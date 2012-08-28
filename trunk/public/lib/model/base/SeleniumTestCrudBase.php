<?php
       
   class SeleniumTestCrudBase extends Utils_PageWidget { 
   
    var $SeleniumTest;
   
       var $selenium_test_id;
   var $selenium_test_name;
   var $selenium_test_scope;
   var $selenium_test_description;
   var $selenium_test_group;
   var $selenium_test_sleep;
   var $selenium_test_meta;
   var $selenium_test_latest_result;
   var $selenium_test_latest_result_date;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getSeleniumTestId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->SeleniumTest = SeleniumTestPeer::retrieveByPK( $id );
    } else {
      $this ->SeleniumTest = new SeleniumTest;
    }
  }
  
  function hydrate( $id ) {
      $this ->SeleniumTest = SeleniumTestPeer::retrieveByPK( $id );
  }
  
  function getSeleniumTestId() {
    if (($this ->postVar("selenium_test_id")) || ($this ->postVar("selenium_test_id") === "")) {
      return $this ->postVar("selenium_test_id");
    } elseif (($this ->getVar("selenium_test_id")) || ($this ->getVar("selenium_test_id") === "")) {
      return $this ->getVar("selenium_test_id");
    } elseif (($this ->SeleniumTest) || ($this ->SeleniumTest === "")){
      return $this ->SeleniumTest -> getSeleniumTestId();
    } elseif (($this ->sessionVar("selenium_test_id")) || ($this ->sessionVar("selenium_test_id") == "")) {
      return $this ->sessionVar("selenium_test_id");
    } else {
      return false;
    }
  }
  
  function setSeleniumTestId( $str ) {
    $this ->SeleniumTest -> setSeleniumTestId( $str );
  }
  
  function getSeleniumTestName() {
    if (($this ->postVar("selenium_test_name")) || ($this ->postVar("selenium_test_name") === "")) {
      return $this ->postVar("selenium_test_name");
    } elseif (($this ->getVar("selenium_test_name")) || ($this ->getVar("selenium_test_name") === "")) {
      return $this ->getVar("selenium_test_name");
    } elseif (($this ->SeleniumTest) || ($this ->SeleniumTest === "")){
      return $this ->SeleniumTest -> getSeleniumTestName();
    } elseif (($this ->sessionVar("selenium_test_name")) || ($this ->sessionVar("selenium_test_name") == "")) {
      return $this ->sessionVar("selenium_test_name");
    } else {
      return false;
    }
  }
  
  function setSeleniumTestName( $str ) {
    $this ->SeleniumTest -> setSeleniumTestName( $str );
  }
  
  function getSeleniumTestScope() {
    if (($this ->postVar("selenium_test_scope")) || ($this ->postVar("selenium_test_scope") === "")) {
      return $this ->postVar("selenium_test_scope");
    } elseif (($this ->getVar("selenium_test_scope")) || ($this ->getVar("selenium_test_scope") === "")) {
      return $this ->getVar("selenium_test_scope");
    } elseif (($this ->SeleniumTest) || ($this ->SeleniumTest === "")){
      return $this ->SeleniumTest -> getSeleniumTestScope();
    } elseif (($this ->sessionVar("selenium_test_scope")) || ($this ->sessionVar("selenium_test_scope") == "")) {
      return $this ->sessionVar("selenium_test_scope");
    } else {
      return false;
    }
  }
  
  function setSeleniumTestScope( $str ) {
    $this ->SeleniumTest -> setSeleniumTestScope( $str );
  }
  
  function getSeleniumTestDescription() {
    if (($this ->postVar("selenium_test_description")) || ($this ->postVar("selenium_test_description") === "")) {
      return $this ->postVar("selenium_test_description");
    } elseif (($this ->getVar("selenium_test_description")) || ($this ->getVar("selenium_test_description") === "")) {
      return $this ->getVar("selenium_test_description");
    } elseif (($this ->SeleniumTest) || ($this ->SeleniumTest === "")){
      return $this ->SeleniumTest -> getSeleniumTestDescription();
    } elseif (($this ->sessionVar("selenium_test_description")) || ($this ->sessionVar("selenium_test_description") == "")) {
      return $this ->sessionVar("selenium_test_description");
    } else {
      return false;
    }
  }
  
  function setSeleniumTestDescription( $str ) {
    $this ->SeleniumTest -> setSeleniumTestDescription( $str );
  }
  
  function getSeleniumTestGroup() {
    if (($this ->postVar("selenium_test_group")) || ($this ->postVar("selenium_test_group") === "")) {
      return $this ->postVar("selenium_test_group");
    } elseif (($this ->getVar("selenium_test_group")) || ($this ->getVar("selenium_test_group") === "")) {
      return $this ->getVar("selenium_test_group");
    } elseif (($this ->SeleniumTest) || ($this ->SeleniumTest === "")){
      return $this ->SeleniumTest -> getSeleniumTestGroup();
    } elseif (($this ->sessionVar("selenium_test_group")) || ($this ->sessionVar("selenium_test_group") == "")) {
      return $this ->sessionVar("selenium_test_group");
    } else {
      return false;
    }
  }
  
  function setSeleniumTestGroup( $str ) {
    $this ->SeleniumTest -> setSeleniumTestGroup( $str );
  }
  
  function getSeleniumTestSleep() {
    if (($this ->postVar("selenium_test_sleep")) || ($this ->postVar("selenium_test_sleep") === "")) {
      return $this ->postVar("selenium_test_sleep");
    } elseif (($this ->getVar("selenium_test_sleep")) || ($this ->getVar("selenium_test_sleep") === "")) {
      return $this ->getVar("selenium_test_sleep");
    } elseif (($this ->SeleniumTest) || ($this ->SeleniumTest === "")){
      return $this ->SeleniumTest -> getSeleniumTestSleep();
    } elseif (($this ->sessionVar("selenium_test_sleep")) || ($this ->sessionVar("selenium_test_sleep") == "")) {
      return $this ->sessionVar("selenium_test_sleep");
    } else {
      return false;
    }
  }
  
  function setSeleniumTestSleep( $str ) {
    $this ->SeleniumTest -> setSeleniumTestSleep( $str );
  }
  
  function getSeleniumTestMeta() {
    if (($this ->postVar("selenium_test_meta")) || ($this ->postVar("selenium_test_meta") === "")) {
      return $this ->postVar("selenium_test_meta");
    } elseif (($this ->getVar("selenium_test_meta")) || ($this ->getVar("selenium_test_meta") === "")) {
      return $this ->getVar("selenium_test_meta");
    } elseif (($this ->SeleniumTest) || ($this ->SeleniumTest === "")){
      return $this ->SeleniumTest -> getSeleniumTestMeta();
    } elseif (($this ->sessionVar("selenium_test_meta")) || ($this ->sessionVar("selenium_test_meta") == "")) {
      return $this ->sessionVar("selenium_test_meta");
    } else {
      return false;
    }
  }
  
  function setSeleniumTestMeta( $str ) {
    $this ->SeleniumTest -> setSeleniumTestMeta( $str );
  }
  
  function getSeleniumTestLatestResult() {
    if (($this ->postVar("selenium_test_latest_result")) || ($this ->postVar("selenium_test_latest_result") === "")) {
      return $this ->postVar("selenium_test_latest_result");
    } elseif (($this ->getVar("selenium_test_latest_result")) || ($this ->getVar("selenium_test_latest_result") === "")) {
      return $this ->getVar("selenium_test_latest_result");
    } elseif (($this ->SeleniumTest) || ($this ->SeleniumTest === "")){
      return $this ->SeleniumTest -> getSeleniumTestLatestResult();
    } elseif (($this ->sessionVar("selenium_test_latest_result")) || ($this ->sessionVar("selenium_test_latest_result") == "")) {
      return $this ->sessionVar("selenium_test_latest_result");
    } else {
      return false;
    }
  }
  
  function setSeleniumTestLatestResult( $str ) {
    $this ->SeleniumTest -> setSeleniumTestLatestResult( $str );
  }
  
  function getSeleniumTestLatestResultDate() {
    if (($this ->postVar("selenium_test_latest_result_date")) || ($this ->postVar("selenium_test_latest_result_date") === "")) {
      return $this ->postVar("selenium_test_latest_result_date");
    } elseif (($this ->getVar("selenium_test_latest_result_date")) || ($this ->getVar("selenium_test_latest_result_date") === "")) {
      return $this ->getVar("selenium_test_latest_result_date");
    } elseif (($this ->SeleniumTest) || ($this ->SeleniumTest === "")){
      return $this ->SeleniumTest -> getSeleniumTestLatestResultDate();
    } elseif (($this ->sessionVar("selenium_test_latest_result_date")) || ($this ->sessionVar("selenium_test_latest_result_date") == "")) {
      return $this ->sessionVar("selenium_test_latest_result_date");
    } else {
      return false;
    }
  }
  
  function setSeleniumTestLatestResultDate( $str ) {
    $this ->SeleniumTest -> setSeleniumTestLatestResultDate( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->SeleniumTest = SeleniumTestPeer::retrieveByPK( $id );
    }
    
    if ($this ->SeleniumTest ) {
       
    	       (is_numeric(WTVRcleanString($this ->SeleniumTest->getSeleniumTestId()))) ? $itemarray["selenium_test_id"] = WTVRcleanString($this ->SeleniumTest->getSeleniumTestId()) : null;
          (WTVRcleanString($this ->SeleniumTest->getSeleniumTestName())) ? $itemarray["selenium_test_name"] = WTVRcleanString($this ->SeleniumTest->getSeleniumTestName()) : null;
          (WTVRcleanString($this ->SeleniumTest->getSeleniumTestScope())) ? $itemarray["selenium_test_scope"] = WTVRcleanString($this ->SeleniumTest->getSeleniumTestScope()) : null;
          (WTVRcleanString($this ->SeleniumTest->getSeleniumTestDescription())) ? $itemarray["selenium_test_description"] = WTVRcleanString($this ->SeleniumTest->getSeleniumTestDescription()) : null;
          (WTVRcleanString($this ->SeleniumTest->getSeleniumTestGroup())) ? $itemarray["selenium_test_group"] = WTVRcleanString($this ->SeleniumTest->getSeleniumTestGroup()) : null;
          (is_numeric(WTVRcleanString($this ->SeleniumTest->getSeleniumTestSleep()))) ? $itemarray["selenium_test_sleep"] = WTVRcleanString($this ->SeleniumTest->getSeleniumTestSleep()) : null;
          (WTVRcleanString($this ->SeleniumTest->getSeleniumTestMeta())) ? $itemarray["selenium_test_meta"] = WTVRcleanString($this ->SeleniumTest->getSeleniumTestMeta()) : null;
          (WTVRcleanString($this ->SeleniumTest->getSeleniumTestLatestResult())) ? $itemarray["selenium_test_latest_result"] = WTVRcleanString($this ->SeleniumTest->getSeleniumTestLatestResult()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->SeleniumTest->getSeleniumTestLatestResultDate())) ? $itemarray["selenium_test_latest_result_date"] = formatDate($this ->SeleniumTest->getSeleniumTestLatestResultDate('%Y-%m-%d %T'),"TS") : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->SeleniumTest = SeleniumTestPeer::retrieveByPK( $id );
    } elseif (! $this ->SeleniumTest) {
      $this ->SeleniumTest = new SeleniumTest;
    }
        
  	 ($this -> getSeleniumTestId())? $this ->SeleniumTest->setSeleniumTestId( WTVRcleanString( $this -> getSeleniumTestId()) ) : null;
    ($this -> getSeleniumTestName())? $this ->SeleniumTest->setSeleniumTestName( WTVRcleanString( $this -> getSeleniumTestName()) ) : null;
    ($this -> getSeleniumTestScope())? $this ->SeleniumTest->setSeleniumTestScope( WTVRcleanString( $this -> getSeleniumTestScope()) ) : null;
    ($this -> getSeleniumTestDescription())? $this ->SeleniumTest->setSeleniumTestDescription( WTVRcleanString( $this -> getSeleniumTestDescription()) ) : null;
    ($this -> getSeleniumTestGroup())? $this ->SeleniumTest->setSeleniumTestGroup( WTVRcleanString( $this -> getSeleniumTestGroup()) ) : null;
    ($this -> getSeleniumTestSleep())? $this ->SeleniumTest->setSeleniumTestSleep( WTVRcleanString( $this -> getSeleniumTestSleep()) ) : null;
    ($this -> getSeleniumTestMeta())? $this ->SeleniumTest->setSeleniumTestMeta( WTVRcleanString( $this -> getSeleniumTestMeta()) ) : null;
    ($this -> getSeleniumTestLatestResult())? $this ->SeleniumTest->setSeleniumTestLatestResult( WTVRcleanString( $this -> getSeleniumTestLatestResult()) ) : null;
          if (is_valid_date( $this ->SeleniumTest->getSeleniumTestLatestResultDate())) {
        $this ->SeleniumTest->setSeleniumTestLatestResultDate( formatDate($this -> getSeleniumTestLatestResultDate(), "TS" ));
      } else {
      $SeleniumTestselenium_test_latest_result_date = $this -> sfDateTime( "selenium_test_latest_result_date" );
      ( $SeleniumTestselenium_test_latest_result_date != "01/01/1900 00:00:00" )? $this ->SeleniumTest->setSeleniumTestLatestResultDate( formatDate($SeleniumTestselenium_test_latest_result_date, "TS" )) : $this ->SeleniumTest->setSeleniumTestLatestResultDate( null );
      }
          
    $this -> save();
     
  }
  
  function save() {
    $this ->SeleniumTest ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->SeleniumTest = SeleniumTestPeer::retrieveByPK($id);
    }
    
    if (! $this ->SeleniumTest ) {
      return;
    }
    
    $this ->SeleniumTest -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('SeleniumTest_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "SeleniumTestPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $SeleniumTest = SeleniumTestPeer::doSelect($c);
    
    if (count($SeleniumTest) >= 1) {
      $this ->SeleniumTest = $SeleniumTest[0];
      return true;
    } else {
      $this ->SeleniumTest = new SeleniumTest();
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
      $name = "SeleniumTestPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $SeleniumTest = SeleniumTestPeer::doSelect($c);
    
    if (count($SeleniumTest) >= 1) {
      $this ->SeleniumTest = $SeleniumTest[0];
      return true;
    } else {
      $this ->SeleniumTest = new SeleniumTest();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>