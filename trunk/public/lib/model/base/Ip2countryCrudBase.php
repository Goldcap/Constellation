<?php
       
   class Ip2countryCrudBase extends Utils_PageWidget { 
   
    var $Ip2country;
   
       var $id;
   var $ip_from;
   var $ip_to;
   var $country_code2;
   var $country_code3;
   var $country_name;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Ip2country = Ip2countryPeer::retrieveByPK( $id );
    } else {
      $this ->Ip2country = new Ip2country;
    }
  }
  
  function hydrate( $id ) {
      $this ->Ip2country = Ip2countryPeer::retrieveByPK( $id );
  }
  
  function getId() {
    if (($this ->postVar("id")) || ($this ->postVar("id") === "")) {
      return $this ->postVar("id");
    } elseif (($this ->getVar("id")) || ($this ->getVar("id") === "")) {
      return $this ->getVar("id");
    } elseif (($this ->Ip2country) || ($this ->Ip2country === "")){
      return $this ->Ip2country -> getId();
    } elseif (($this ->sessionVar("id")) || ($this ->sessionVar("id") == "")) {
      return $this ->sessionVar("id");
    } else {
      return false;
    }
  }
  
  function setId( $str ) {
    $this ->Ip2country -> setId( $str );
  }
  
  function getIpFrom() {
    if (($this ->postVar("ip_from")) || ($this ->postVar("ip_from") === "")) {
      return $this ->postVar("ip_from");
    } elseif (($this ->getVar("ip_from")) || ($this ->getVar("ip_from") === "")) {
      return $this ->getVar("ip_from");
    } elseif (($this ->Ip2country) || ($this ->Ip2country === "")){
      return $this ->Ip2country -> getIpFrom();
    } elseif (($this ->sessionVar("ip_from")) || ($this ->sessionVar("ip_from") == "")) {
      return $this ->sessionVar("ip_from");
    } else {
      return false;
    }
  }
  
  function setIpFrom( $str ) {
    $this ->Ip2country -> setIpFrom( $str );
  }
  
  function getIpTo() {
    if (($this ->postVar("ip_to")) || ($this ->postVar("ip_to") === "")) {
      return $this ->postVar("ip_to");
    } elseif (($this ->getVar("ip_to")) || ($this ->getVar("ip_to") === "")) {
      return $this ->getVar("ip_to");
    } elseif (($this ->Ip2country) || ($this ->Ip2country === "")){
      return $this ->Ip2country -> getIpTo();
    } elseif (($this ->sessionVar("ip_to")) || ($this ->sessionVar("ip_to") == "")) {
      return $this ->sessionVar("ip_to");
    } else {
      return false;
    }
  }
  
  function setIpTo( $str ) {
    $this ->Ip2country -> setIpTo( $str );
  }
  
  function getCountryCode2() {
    if (($this ->postVar("country_code2")) || ($this ->postVar("country_code2") === "")) {
      return $this ->postVar("country_code2");
    } elseif (($this ->getVar("country_code2")) || ($this ->getVar("country_code2") === "")) {
      return $this ->getVar("country_code2");
    } elseif (($this ->Ip2country) || ($this ->Ip2country === "")){
      return $this ->Ip2country -> getCountryCode2();
    } elseif (($this ->sessionVar("country_code2")) || ($this ->sessionVar("country_code2") == "")) {
      return $this ->sessionVar("country_code2");
    } else {
      return false;
    }
  }
  
  function setCountryCode2( $str ) {
    $this ->Ip2country -> setCountryCode2( $str );
  }
  
  function getCountryCode3() {
    if (($this ->postVar("country_code3")) || ($this ->postVar("country_code3") === "")) {
      return $this ->postVar("country_code3");
    } elseif (($this ->getVar("country_code3")) || ($this ->getVar("country_code3") === "")) {
      return $this ->getVar("country_code3");
    } elseif (($this ->Ip2country) || ($this ->Ip2country === "")){
      return $this ->Ip2country -> getCountryCode3();
    } elseif (($this ->sessionVar("country_code3")) || ($this ->sessionVar("country_code3") == "")) {
      return $this ->sessionVar("country_code3");
    } else {
      return false;
    }
  }
  
  function setCountryCode3( $str ) {
    $this ->Ip2country -> setCountryCode3( $str );
  }
  
  function getCountryName() {
    if (($this ->postVar("country_name")) || ($this ->postVar("country_name") === "")) {
      return $this ->postVar("country_name");
    } elseif (($this ->getVar("country_name")) || ($this ->getVar("country_name") === "")) {
      return $this ->getVar("country_name");
    } elseif (($this ->Ip2country) || ($this ->Ip2country === "")){
      return $this ->Ip2country -> getCountryName();
    } elseif (($this ->sessionVar("country_name")) || ($this ->sessionVar("country_name") == "")) {
      return $this ->sessionVar("country_name");
    } else {
      return false;
    }
  }
  
  function setCountryName( $str ) {
    $this ->Ip2country -> setCountryName( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Ip2country = Ip2countryPeer::retrieveByPK( $id );
    }
    
    if ($this ->Ip2country ) {
       
    	       (is_numeric(WTVRcleanString($this ->Ip2country->getId()))) ? $itemarray["id"] = WTVRcleanString($this ->Ip2country->getId()) : null;
          (WTVRcleanString($this ->Ip2country->getIpFrom())) ? $itemarray["ip_from"] = WTVRcleanString($this ->Ip2country->getIpFrom()) : null;
          (WTVRcleanString($this ->Ip2country->getIpTo())) ? $itemarray["ip_to"] = WTVRcleanString($this ->Ip2country->getIpTo()) : null;
          (WTVRcleanString($this ->Ip2country->getCountryCode2())) ? $itemarray["country_code2"] = WTVRcleanString($this ->Ip2country->getCountryCode2()) : null;
          (WTVRcleanString($this ->Ip2country->getCountryCode3())) ? $itemarray["country_code3"] = WTVRcleanString($this ->Ip2country->getCountryCode3()) : null;
          (WTVRcleanString($this ->Ip2country->getCountryName())) ? $itemarray["country_name"] = WTVRcleanString($this ->Ip2country->getCountryName()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Ip2country = Ip2countryPeer::retrieveByPK( $id );
    } elseif (! $this ->Ip2country) {
      $this ->Ip2country = new Ip2country;
    }
        
  	 ($this -> getId())? $this ->Ip2country->setId( WTVRcleanString( $this -> getId()) ) : null;
    ($this -> getIpFrom())? $this ->Ip2country->setIpFrom( WTVRcleanString( $this -> getIpFrom()) ) : null;
    ($this -> getIpTo())? $this ->Ip2country->setIpTo( WTVRcleanString( $this -> getIpTo()) ) : null;
    ($this -> getCountryCode2())? $this ->Ip2country->setCountryCode2( WTVRcleanString( $this -> getCountryCode2()) ) : null;
    ($this -> getCountryCode3())? $this ->Ip2country->setCountryCode3( WTVRcleanString( $this -> getCountryCode3()) ) : null;
    ($this -> getCountryName())? $this ->Ip2country->setCountryName( WTVRcleanString( $this -> getCountryName()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Ip2country ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Ip2country = Ip2countryPeer::retrieveByPK($id);
    }
    
    if (! $this ->Ip2country ) {
      return;
    }
    
    $this ->Ip2country -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Ip2country_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "Ip2countryPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Ip2country = Ip2countryPeer::doSelect($c);
    
    if (count($Ip2country) >= 1) {
      $this ->Ip2country = $Ip2country[0];
      return true;
    } else {
      $this ->Ip2country = new Ip2country();
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
      $name = "Ip2countryPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Ip2country = Ip2countryPeer::doSelect($c);
    
    if (count($Ip2country) >= 1) {
      $this ->Ip2country = $Ip2country[0];
      return true;
    } else {
      $this ->Ip2country = new Ip2country();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>