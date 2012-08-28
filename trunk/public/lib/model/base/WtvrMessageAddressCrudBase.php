<?php
       
   class WtvrMessageAddressCrudBase extends Utils_PageWidget { 
   
    var $WtvrMessageAddress;
   
       var $wtvr_message_address_id;
   var $wtvr_message_emailaddress;
   var $wtvr_message_address_valid;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getWtvrMessageAddressId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->WtvrMessageAddress = WtvrMessageAddressPeer::retrieveByPK( $id );
    } else {
      $this ->WtvrMessageAddress = new WtvrMessageAddress;
    }
  }
  
  function hydrate( $id ) {
      $this ->WtvrMessageAddress = WtvrMessageAddressPeer::retrieveByPK( $id );
  }
  
  function getWtvrMessageAddressId() {
    if (($this ->postVar("wtvr_message_address_id")) || ($this ->postVar("wtvr_message_address_id") === "")) {
      return $this ->postVar("wtvr_message_address_id");
    } elseif (($this ->getVar("wtvr_message_address_id")) || ($this ->getVar("wtvr_message_address_id") === "")) {
      return $this ->getVar("wtvr_message_address_id");
    } elseif (($this ->WtvrMessageAddress) || ($this ->WtvrMessageAddress === "")){
      return $this ->WtvrMessageAddress -> getWtvrMessageAddressId();
    } elseif (($this ->sessionVar("wtvr_message_address_id")) || ($this ->sessionVar("wtvr_message_address_id") == "")) {
      return $this ->sessionVar("wtvr_message_address_id");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageAddressId( $str ) {
    $this ->WtvrMessageAddress -> setWtvrMessageAddressId( $str );
  }
  
  function getWtvrMessageEmailaddress() {
    if (($this ->postVar("wtvr_message_emailaddress")) || ($this ->postVar("wtvr_message_emailaddress") === "")) {
      return $this ->postVar("wtvr_message_emailaddress");
    } elseif (($this ->getVar("wtvr_message_emailaddress")) || ($this ->getVar("wtvr_message_emailaddress") === "")) {
      return $this ->getVar("wtvr_message_emailaddress");
    } elseif (($this ->WtvrMessageAddress) || ($this ->WtvrMessageAddress === "")){
      return $this ->WtvrMessageAddress -> getWtvrMessageEmailaddress();
    } elseif (($this ->sessionVar("wtvr_message_emailaddress")) || ($this ->sessionVar("wtvr_message_emailaddress") == "")) {
      return $this ->sessionVar("wtvr_message_emailaddress");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageEmailaddress( $str ) {
    $this ->WtvrMessageAddress -> setWtvrMessageEmailaddress( $str );
  }
  
  function getWtvrMessageAddressValid() {
    if (($this ->postVar("wtvr_message_address_valid")) || ($this ->postVar("wtvr_message_address_valid") === "")) {
      return $this ->postVar("wtvr_message_address_valid");
    } elseif (($this ->getVar("wtvr_message_address_valid")) || ($this ->getVar("wtvr_message_address_valid") === "")) {
      return $this ->getVar("wtvr_message_address_valid");
    } elseif (($this ->WtvrMessageAddress) || ($this ->WtvrMessageAddress === "")){
      return $this ->WtvrMessageAddress -> getWtvrMessageAddressValid();
    } elseif (($this ->sessionVar("wtvr_message_address_valid")) || ($this ->sessionVar("wtvr_message_address_valid") == "")) {
      return $this ->sessionVar("wtvr_message_address_valid");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageAddressValid( $str ) {
    $this ->WtvrMessageAddress -> setWtvrMessageAddressValid( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->WtvrMessageAddress = WtvrMessageAddressPeer::retrieveByPK( $id );
    }
    
    if ($this ->WtvrMessageAddress ) {
       
    	       (is_numeric(WTVRcleanString($this ->WtvrMessageAddress->getWtvrMessageAddressId()))) ? $itemarray["wtvr_message_address_id"] = WTVRcleanString($this ->WtvrMessageAddress->getWtvrMessageAddressId()) : null;
          (WTVRcleanString($this ->WtvrMessageAddress->getWtvrMessageEmailaddress())) ? $itemarray["wtvr_message_emailaddress"] = WTVRcleanString($this ->WtvrMessageAddress->getWtvrMessageEmailaddress()) : null;
          (is_numeric(WTVRcleanString($this ->WtvrMessageAddress->getWtvrMessageAddressValid()))) ? $itemarray["wtvr_message_address_valid"] = WTVRcleanString($this ->WtvrMessageAddress->getWtvrMessageAddressValid()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->WtvrMessageAddress = WtvrMessageAddressPeer::retrieveByPK( $id );
    } elseif (! $this ->WtvrMessageAddress) {
      $this ->WtvrMessageAddress = new WtvrMessageAddress;
    }
        
  	 ($this -> getWtvrMessageAddressId())? $this ->WtvrMessageAddress->setWtvrMessageAddressId( WTVRcleanString( $this -> getWtvrMessageAddressId()) ) : null;
    ($this -> getWtvrMessageEmailaddress())? $this ->WtvrMessageAddress->setWtvrMessageEmailaddress( WTVRcleanString( $this -> getWtvrMessageEmailaddress()) ) : null;
    ($this -> getWtvrMessageAddressValid())? $this ->WtvrMessageAddress->setWtvrMessageAddressValid( WTVRcleanString( $this -> getWtvrMessageAddressValid()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->WtvrMessageAddress ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->WtvrMessageAddress = WtvrMessageAddressPeer::retrieveByPK($id);
    }
    
    if (! $this ->WtvrMessageAddress ) {
      return;
    }
    
    $this ->WtvrMessageAddress -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('WtvrMessageAddress_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "WtvrMessageAddressPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $WtvrMessageAddress = WtvrMessageAddressPeer::doSelect($c);
    
    if (count($WtvrMessageAddress) >= 1) {
      $this ->WtvrMessageAddress = $WtvrMessageAddress[0];
      return true;
    } else {
      $this ->WtvrMessageAddress = new WtvrMessageAddress();
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
      $name = "WtvrMessageAddressPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $WtvrMessageAddress = WtvrMessageAddressPeer::doSelect($c);
    
    if (count($WtvrMessageAddress) >= 1) {
      $this ->WtvrMessageAddress = $WtvrMessageAddress[0];
      return true;
    } else {
      $this ->WtvrMessageAddress = new WtvrMessageAddress();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>