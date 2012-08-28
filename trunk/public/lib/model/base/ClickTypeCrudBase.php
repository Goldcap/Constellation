<?php
       
   class ClickTypeCrudBase extends Utils_PageWidget { 
   
    var $ClickType;
   
       var $click_type_id;
   var $click_type_name;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getClickTypeId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->ClickType = ClickTypePeer::retrieveByPK( $id );
    } else {
      $this ->ClickType = new ClickType;
    }
  }
  
  function hydrate( $id ) {
      $this ->ClickType = ClickTypePeer::retrieveByPK( $id );
  }
  
  function getClickTypeId() {
    if (($this ->postVar("click_type_id")) || ($this ->postVar("click_type_id") === "")) {
      return $this ->postVar("click_type_id");
    } elseif (($this ->getVar("click_type_id")) || ($this ->getVar("click_type_id") === "")) {
      return $this ->getVar("click_type_id");
    } elseif (($this ->ClickType) || ($this ->ClickType === "")){
      return $this ->ClickType -> getClickTypeId();
    } elseif (($this ->sessionVar("click_type_id")) || ($this ->sessionVar("click_type_id") == "")) {
      return $this ->sessionVar("click_type_id");
    } else {
      return false;
    }
  }
  
  function setClickTypeId( $str ) {
    $this ->ClickType -> setClickTypeId( $str );
  }
  
  function getClickTypeName() {
    if (($this ->postVar("click_type_name")) || ($this ->postVar("click_type_name") === "")) {
      return $this ->postVar("click_type_name");
    } elseif (($this ->getVar("click_type_name")) || ($this ->getVar("click_type_name") === "")) {
      return $this ->getVar("click_type_name");
    } elseif (($this ->ClickType) || ($this ->ClickType === "")){
      return $this ->ClickType -> getClickTypeName();
    } elseif (($this ->sessionVar("click_type_name")) || ($this ->sessionVar("click_type_name") == "")) {
      return $this ->sessionVar("click_type_name");
    } else {
      return false;
    }
  }
  
  function setClickTypeName( $str ) {
    $this ->ClickType -> setClickTypeName( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->ClickType = ClickTypePeer::retrieveByPK( $id );
    }
    
    if ($this ->ClickType ) {
       
    	       (is_numeric(WTVRcleanString($this ->ClickType->getClickTypeId()))) ? $itemarray["click_type_id"] = WTVRcleanString($this ->ClickType->getClickTypeId()) : null;
          (WTVRcleanString($this ->ClickType->getClickTypeName())) ? $itemarray["click_type_name"] = WTVRcleanString($this ->ClickType->getClickTypeName()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->ClickType = ClickTypePeer::retrieveByPK( $id );
    } elseif (! $this ->ClickType) {
      $this ->ClickType = new ClickType;
    }
        
  	 ($this -> getClickTypeId())? $this ->ClickType->setClickTypeId( WTVRcleanString( $this -> getClickTypeId()) ) : null;
    ($this -> getClickTypeName())? $this ->ClickType->setClickTypeName( WTVRcleanString( $this -> getClickTypeName()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->ClickType ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->ClickType = ClickTypePeer::retrieveByPK($id);
    }
    
    if (! $this ->ClickType ) {
      return;
    }
    
    $this ->ClickType -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('ClickType_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "ClickTypePeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $ClickType = ClickTypePeer::doSelect($c);
    
    if (count($ClickType) >= 1) {
      $this ->ClickType = $ClickType[0];
      return true;
    } else {
      $this ->ClickType = new ClickType();
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
      $name = "ClickTypePeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $ClickType = ClickTypePeer::doSelect($c);
    
    if (count($ClickType) >= 1) {
      $this ->ClickType = $ClickType[0];
      return true;
    } else {
      $this ->ClickType = new ClickType();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>