<?php
       
   class CountryCrudBase extends Utils_PageWidget { 
   
    var $Country;
   
       var $iso;
   var $name;
   var $printable_name;
   var $iso3;
   var $numcode;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getIso();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Country = CountryPeer::retrieveByPK( $id );
    } else {
      $this ->Country = new Country;
    }
  }
  
  function hydrate( $id ) {
      $this ->Country = CountryPeer::retrieveByPK( $id );
  }
  
  function getIso() {
    if (($this ->postVar("iso")) || ($this ->postVar("iso") === "")) {
      return $this ->postVar("iso");
    } elseif (($this ->getVar("iso")) || ($this ->getVar("iso") === "")) {
      return $this ->getVar("iso");
    } elseif (($this ->Country) || ($this ->Country === "")){
      return $this ->Country -> getIso();
    } elseif (($this ->sessionVar("iso")) || ($this ->sessionVar("iso") == "")) {
      return $this ->sessionVar("iso");
    } else {
      return false;
    }
  }
  
  function setIso( $str ) {
    $this ->Country -> setIso( $str );
  }
  
  function getName() {
    if (($this ->postVar("name")) || ($this ->postVar("name") === "")) {
      return $this ->postVar("name");
    } elseif (($this ->getVar("name")) || ($this ->getVar("name") === "")) {
      return $this ->getVar("name");
    } elseif (($this ->Country) || ($this ->Country === "")){
      return $this ->Country -> getName();
    } elseif (($this ->sessionVar("name")) || ($this ->sessionVar("name") == "")) {
      return $this ->sessionVar("name");
    } else {
      return false;
    }
  }
  
  function setName( $str ) {
    $this ->Country -> setName( $str );
  }
  
  function getPrintableName() {
    if (($this ->postVar("printable_name")) || ($this ->postVar("printable_name") === "")) {
      return $this ->postVar("printable_name");
    } elseif (($this ->getVar("printable_name")) || ($this ->getVar("printable_name") === "")) {
      return $this ->getVar("printable_name");
    } elseif (($this ->Country) || ($this ->Country === "")){
      return $this ->Country -> getPrintableName();
    } elseif (($this ->sessionVar("printable_name")) || ($this ->sessionVar("printable_name") == "")) {
      return $this ->sessionVar("printable_name");
    } else {
      return false;
    }
  }
  
  function setPrintableName( $str ) {
    $this ->Country -> setPrintableName( $str );
  }
  
  function getIso3() {
    if (($this ->postVar("iso3")) || ($this ->postVar("iso3") === "")) {
      return $this ->postVar("iso3");
    } elseif (($this ->getVar("iso3")) || ($this ->getVar("iso3") === "")) {
      return $this ->getVar("iso3");
    } elseif (($this ->Country) || ($this ->Country === "")){
      return $this ->Country -> getIso3();
    } elseif (($this ->sessionVar("iso3")) || ($this ->sessionVar("iso3") == "")) {
      return $this ->sessionVar("iso3");
    } else {
      return false;
    }
  }
  
  function setIso3( $str ) {
    $this ->Country -> setIso3( $str );
  }
  
  function getNumcode() {
    if (($this ->postVar("numcode")) || ($this ->postVar("numcode") === "")) {
      return $this ->postVar("numcode");
    } elseif (($this ->getVar("numcode")) || ($this ->getVar("numcode") === "")) {
      return $this ->getVar("numcode");
    } elseif (($this ->Country) || ($this ->Country === "")){
      return $this ->Country -> getNumcode();
    } elseif (($this ->sessionVar("numcode")) || ($this ->sessionVar("numcode") == "")) {
      return $this ->sessionVar("numcode");
    } else {
      return false;
    }
  }
  
  function setNumcode( $str ) {
    $this ->Country -> setNumcode( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Country = CountryPeer::retrieveByPK( $id );
    }
    
    if ($this ->Country ) {
       
    	       (WTVRcleanString($this ->Country->getIso())) ? $itemarray["iso"] = WTVRcleanString($this ->Country->getIso()) : null;
          (WTVRcleanString($this ->Country->getName())) ? $itemarray["name"] = WTVRcleanString($this ->Country->getName()) : null;
          (WTVRcleanString($this ->Country->getPrintableName())) ? $itemarray["printable_name"] = WTVRcleanString($this ->Country->getPrintableName()) : null;
          (WTVRcleanString($this ->Country->getIso3())) ? $itemarray["iso3"] = WTVRcleanString($this ->Country->getIso3()) : null;
          (is_numeric(WTVRcleanString($this ->Country->getNumcode()))) ? $itemarray["numcode"] = WTVRcleanString($this ->Country->getNumcode()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Country = CountryPeer::retrieveByPK( $id );
    } elseif (! $this ->Country) {
      $this ->Country = new Country;
    }
        
  	 ($this -> getIso())? $this ->Country->setIso( WTVRcleanString( $this -> getIso()) ) : null;
    ($this -> getName())? $this ->Country->setName( WTVRcleanString( $this -> getName()) ) : null;
    ($this -> getPrintableName())? $this ->Country->setPrintableName( WTVRcleanString( $this -> getPrintableName()) ) : null;
    ($this -> getIso3())? $this ->Country->setIso3( WTVRcleanString( $this -> getIso3()) ) : null;
    ($this -> getNumcode())? $this ->Country->setNumcode( WTVRcleanString( $this -> getNumcode()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Country ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Country = CountryPeer::retrieveByPK($id);
    }
    
    if (! $this ->Country ) {
      return;
    }
    
    $this ->Country -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Country_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "CountryPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Country = CountryPeer::doSelect($c);
    
    if (count($Country) >= 1) {
      $this ->Country = $Country[0];
      return true;
    } else {
      $this ->Country = new Country();
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
      $name = "CountryPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Country = CountryPeer::doSelect($c);
    
    if (count($Country) >= 1) {
      $this ->Country = $Country[0];
      return true;
    } else {
      $this ->Country = new Country();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>