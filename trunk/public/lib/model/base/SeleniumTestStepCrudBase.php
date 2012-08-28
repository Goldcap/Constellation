<?php
       
   class SeleniumTestStepCrudBase extends Utils_PageWidget { 
   
    var $SeleniumTestStep;
   
       var $selenium_test_step_id;
   var $fk_selenium_test_id;
   var $selenium_test_step_order;
   var $selenium_test_step_description;
   var $selenium_test_step_type;
   var $selenium_test_step_locator;
   var $selenium_test_step_value;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getSeleniumTestStepId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->SeleniumTestStep = SeleniumTestStepPeer::retrieveByPK( $id );
    } else {
      $this ->SeleniumTestStep = new SeleniumTestStep;
    }
  }
  
  function hydrate( $id ) {
      $this ->SeleniumTestStep = SeleniumTestStepPeer::retrieveByPK( $id );
  }
  
  function getSeleniumTestStepId() {
    if (($this ->postVar("selenium_test_step_id")) || ($this ->postVar("selenium_test_step_id") === "")) {
      return $this ->postVar("selenium_test_step_id");
    } elseif (($this ->getVar("selenium_test_step_id")) || ($this ->getVar("selenium_test_step_id") === "")) {
      return $this ->getVar("selenium_test_step_id");
    } elseif (($this ->SeleniumTestStep) || ($this ->SeleniumTestStep === "")){
      return $this ->SeleniumTestStep -> getSeleniumTestStepId();
    } elseif (($this ->sessionVar("selenium_test_step_id")) || ($this ->sessionVar("selenium_test_step_id") == "")) {
      return $this ->sessionVar("selenium_test_step_id");
    } else {
      return false;
    }
  }
  
  function setSeleniumTestStepId( $str ) {
    $this ->SeleniumTestStep -> setSeleniumTestStepId( $str );
  }
  
  function getFkSeleniumTestId() {
    if (($this ->postVar("fk_selenium_test_id")) || ($this ->postVar("fk_selenium_test_id") === "")) {
      return $this ->postVar("fk_selenium_test_id");
    } elseif (($this ->getVar("fk_selenium_test_id")) || ($this ->getVar("fk_selenium_test_id") === "")) {
      return $this ->getVar("fk_selenium_test_id");
    } elseif (($this ->SeleniumTestStep) || ($this ->SeleniumTestStep === "")){
      return $this ->SeleniumTestStep -> getFkSeleniumTestId();
    } elseif (($this ->sessionVar("fk_selenium_test_id")) || ($this ->sessionVar("fk_selenium_test_id") == "")) {
      return $this ->sessionVar("fk_selenium_test_id");
    } else {
      return false;
    }
  }
  
  function setFkSeleniumTestId( $str ) {
    $this ->SeleniumTestStep -> setFkSeleniumTestId( $str );
  }
  
  function getSeleniumTestStepOrder() {
    if (($this ->postVar("selenium_test_step_order")) || ($this ->postVar("selenium_test_step_order") === "")) {
      return $this ->postVar("selenium_test_step_order");
    } elseif (($this ->getVar("selenium_test_step_order")) || ($this ->getVar("selenium_test_step_order") === "")) {
      return $this ->getVar("selenium_test_step_order");
    } elseif (($this ->SeleniumTestStep) || ($this ->SeleniumTestStep === "")){
      return $this ->SeleniumTestStep -> getSeleniumTestStepOrder();
    } elseif (($this ->sessionVar("selenium_test_step_order")) || ($this ->sessionVar("selenium_test_step_order") == "")) {
      return $this ->sessionVar("selenium_test_step_order");
    } else {
      return false;
    }
  }
  
  function setSeleniumTestStepOrder( $str ) {
    $this ->SeleniumTestStep -> setSeleniumTestStepOrder( $str );
  }
  
  function getSeleniumTestStepDescription() {
    if (($this ->postVar("selenium_test_step_description")) || ($this ->postVar("selenium_test_step_description") === "")) {
      return $this ->postVar("selenium_test_step_description");
    } elseif (($this ->getVar("selenium_test_step_description")) || ($this ->getVar("selenium_test_step_description") === "")) {
      return $this ->getVar("selenium_test_step_description");
    } elseif (($this ->SeleniumTestStep) || ($this ->SeleniumTestStep === "")){
      return $this ->SeleniumTestStep -> getSeleniumTestStepDescription();
    } elseif (($this ->sessionVar("selenium_test_step_description")) || ($this ->sessionVar("selenium_test_step_description") == "")) {
      return $this ->sessionVar("selenium_test_step_description");
    } else {
      return false;
    }
  }
  
  function setSeleniumTestStepDescription( $str ) {
    $this ->SeleniumTestStep -> setSeleniumTestStepDescription( $str );
  }
  
  function getSeleniumTestStepType() {
    if (($this ->postVar("selenium_test_step_type")) || ($this ->postVar("selenium_test_step_type") === "")) {
      return $this ->postVar("selenium_test_step_type");
    } elseif (($this ->getVar("selenium_test_step_type")) || ($this ->getVar("selenium_test_step_type") === "")) {
      return $this ->getVar("selenium_test_step_type");
    } elseif (($this ->SeleniumTestStep) || ($this ->SeleniumTestStep === "")){
      return $this ->SeleniumTestStep -> getSeleniumTestStepType();
    } elseif (($this ->sessionVar("selenium_test_step_type")) || ($this ->sessionVar("selenium_test_step_type") == "")) {
      return $this ->sessionVar("selenium_test_step_type");
    } else {
      return false;
    }
  }
  
  function setSeleniumTestStepType( $str ) {
    $this ->SeleniumTestStep -> setSeleniumTestStepType( $str );
  }
  
  function getSeleniumTestStepLocator() {
    if (($this ->postVar("selenium_test_step_locator")) || ($this ->postVar("selenium_test_step_locator") === "")) {
      return $this ->postVar("selenium_test_step_locator");
    } elseif (($this ->getVar("selenium_test_step_locator")) || ($this ->getVar("selenium_test_step_locator") === "")) {
      return $this ->getVar("selenium_test_step_locator");
    } elseif (($this ->SeleniumTestStep) || ($this ->SeleniumTestStep === "")){
      return $this ->SeleniumTestStep -> getSeleniumTestStepLocator();
    } elseif (($this ->sessionVar("selenium_test_step_locator")) || ($this ->sessionVar("selenium_test_step_locator") == "")) {
      return $this ->sessionVar("selenium_test_step_locator");
    } else {
      return false;
    }
  }
  
  function setSeleniumTestStepLocator( $str ) {
    $this ->SeleniumTestStep -> setSeleniumTestStepLocator( $str );
  }
  
  function getSeleniumTestStepValue() {
    if (($this ->postVar("selenium_test_step_value")) || ($this ->postVar("selenium_test_step_value") === "")) {
      return $this ->postVar("selenium_test_step_value");
    } elseif (($this ->getVar("selenium_test_step_value")) || ($this ->getVar("selenium_test_step_value") === "")) {
      return $this ->getVar("selenium_test_step_value");
    } elseif (($this ->SeleniumTestStep) || ($this ->SeleniumTestStep === "")){
      return $this ->SeleniumTestStep -> getSeleniumTestStepValue();
    } elseif (($this ->sessionVar("selenium_test_step_value")) || ($this ->sessionVar("selenium_test_step_value") == "")) {
      return $this ->sessionVar("selenium_test_step_value");
    } else {
      return false;
    }
  }
  
  function setSeleniumTestStepValue( $str ) {
    $this ->SeleniumTestStep -> setSeleniumTestStepValue( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->SeleniumTestStep = SeleniumTestStepPeer::retrieveByPK( $id );
    }
    
    if ($this ->SeleniumTestStep ) {
       
    	       (is_numeric(WTVRcleanString($this ->SeleniumTestStep->getSeleniumTestStepId()))) ? $itemarray["selenium_test_step_id"] = WTVRcleanString($this ->SeleniumTestStep->getSeleniumTestStepId()) : null;
          (is_numeric(WTVRcleanString($this ->SeleniumTestStep->getFkSeleniumTestId()))) ? $itemarray["fk_selenium_test_id"] = WTVRcleanString($this ->SeleniumTestStep->getFkSeleniumTestId()) : null;
          (is_numeric(WTVRcleanString($this ->SeleniumTestStep->getSeleniumTestStepOrder()))) ? $itemarray["selenium_test_step_order"] = WTVRcleanString($this ->SeleniumTestStep->getSeleniumTestStepOrder()) : null;
          (WTVRcleanString($this ->SeleniumTestStep->getSeleniumTestStepDescription())) ? $itemarray["selenium_test_step_description"] = WTVRcleanString($this ->SeleniumTestStep->getSeleniumTestStepDescription()) : null;
          (WTVRcleanString($this ->SeleniumTestStep->getSeleniumTestStepType())) ? $itemarray["selenium_test_step_type"] = WTVRcleanString($this ->SeleniumTestStep->getSeleniumTestStepType()) : null;
          (WTVRcleanString($this ->SeleniumTestStep->getSeleniumTestStepLocator())) ? $itemarray["selenium_test_step_locator"] = WTVRcleanString($this ->SeleniumTestStep->getSeleniumTestStepLocator()) : null;
          (WTVRcleanString($this ->SeleniumTestStep->getSeleniumTestStepValue())) ? $itemarray["selenium_test_step_value"] = WTVRcleanString($this ->SeleniumTestStep->getSeleniumTestStepValue()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->SeleniumTestStep = SeleniumTestStepPeer::retrieveByPK( $id );
    } elseif (! $this ->SeleniumTestStep) {
      $this ->SeleniumTestStep = new SeleniumTestStep;
    }
        
  	 ($this -> getSeleniumTestStepId())? $this ->SeleniumTestStep->setSeleniumTestStepId( WTVRcleanString( $this -> getSeleniumTestStepId()) ) : null;
    ($this -> getFkSeleniumTestId())? $this ->SeleniumTestStep->setFkSeleniumTestId( WTVRcleanString( $this -> getFkSeleniumTestId()) ) : null;
    ($this -> getSeleniumTestStepOrder())? $this ->SeleniumTestStep->setSeleniumTestStepOrder( WTVRcleanString( $this -> getSeleniumTestStepOrder()) ) : null;
    ($this -> getSeleniumTestStepDescription())? $this ->SeleniumTestStep->setSeleniumTestStepDescription( WTVRcleanString( $this -> getSeleniumTestStepDescription()) ) : null;
    ($this -> getSeleniumTestStepType())? $this ->SeleniumTestStep->setSeleniumTestStepType( WTVRcleanString( $this -> getSeleniumTestStepType()) ) : null;
    ($this -> getSeleniumTestStepLocator())? $this ->SeleniumTestStep->setSeleniumTestStepLocator( WTVRcleanString( $this -> getSeleniumTestStepLocator()) ) : null;
    ($this -> getSeleniumTestStepValue())? $this ->SeleniumTestStep->setSeleniumTestStepValue( WTVRcleanString( $this -> getSeleniumTestStepValue()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->SeleniumTestStep ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->SeleniumTestStep = SeleniumTestStepPeer::retrieveByPK($id);
    }
    
    if (! $this ->SeleniumTestStep ) {
      return;
    }
    
    $this ->SeleniumTestStep -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('SeleniumTestStep_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "SeleniumTestStepPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $SeleniumTestStep = SeleniumTestStepPeer::doSelect($c);
    
    if (count($SeleniumTestStep) >= 1) {
      $this ->SeleniumTestStep = $SeleniumTestStep[0];
      return true;
    } else {
      $this ->SeleniumTestStep = new SeleniumTestStep();
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
      $name = "SeleniumTestStepPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $SeleniumTestStep = SeleniumTestStepPeer::doSelect($c);
    
    if (count($SeleniumTestStep) >= 1) {
      $this ->SeleniumTestStep = $SeleniumTestStep[0];
      return true;
    } else {
      $this ->SeleniumTestStep = new SeleniumTestStep();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>