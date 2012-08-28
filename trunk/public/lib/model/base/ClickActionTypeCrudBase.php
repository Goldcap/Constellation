<?php
       
   class ClickActionTypeCrudBase extends Utils_PageWidget { 
   
    var $ClickActionType;
   
       var $click_action_type_id;
   var $click_action_type_name;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getClickActionTypeId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->ClickActionType = ClickActionTypePeer::retrieveByPK( $id );
    } else {
      $this ->ClickActionType = new ClickActionType;
    }
  }
  
  function hydrate( $id ) {
      $this ->ClickActionType = ClickActionTypePeer::retrieveByPK( $id );
  }
  
  function getClickActionTypeId() {
    if (($this ->postVar("click_action_type_id")) || ($this ->postVar("click_action_type_id") === "")) {
      return $this ->postVar("click_action_type_id");
    } elseif (($this ->getVar("click_action_type_id")) || ($this ->getVar("click_action_type_id") === "")) {
      return $this ->getVar("click_action_type_id");
    } elseif (($this ->ClickActionType) || ($this ->ClickActionType === "")){
      return $this ->ClickActionType -> getClickActionTypeId();
    } elseif (($this ->sessionVar("click_action_type_id")) || ($this ->sessionVar("click_action_type_id") == "")) {
      return $this ->sessionVar("click_action_type_id");
    } else {
      return false;
    }
  }
  
  function setClickActionTypeId( $str ) {
    $this ->ClickActionType -> setClickActionTypeId( $str );
  }
  
  function getClickActionTypeName() {
    if (($this ->postVar("click_action_type_name")) || ($this ->postVar("click_action_type_name") === "")) {
      return $this ->postVar("click_action_type_name");
    } elseif (($this ->getVar("click_action_type_name")) || ($this ->getVar("click_action_type_name") === "")) {
      return $this ->getVar("click_action_type_name");
    } elseif (($this ->ClickActionType) || ($this ->ClickActionType === "")){
      return $this ->ClickActionType -> getClickActionTypeName();
    } elseif (($this ->sessionVar("click_action_type_name")) || ($this ->sessionVar("click_action_type_name") == "")) {
      return $this ->sessionVar("click_action_type_name");
    } else {
      return false;
    }
  }
  
  function setClickActionTypeName( $str ) {
    $this ->ClickActionType -> setClickActionTypeName( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->ClickActionType = ClickActionTypePeer::retrieveByPK( $id );
    }
    
    if ($this ->ClickActionType ) {
       
    	       (is_numeric(WTVRcleanString($this ->ClickActionType->getClickActionTypeId()))) ? $itemarray["click_action_type_id"] = WTVRcleanString($this ->ClickActionType->getClickActionTypeId()) : null;
          (WTVRcleanString($this ->ClickActionType->getClickActionTypeName())) ? $itemarray["click_action_type_name"] = WTVRcleanString($this ->ClickActionType->getClickActionTypeName()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->ClickActionType = ClickActionTypePeer::retrieveByPK( $id );
    } elseif (! $this ->ClickActionType) {
      $this ->ClickActionType = new ClickActionType;
    }
        
  	 ($this -> getClickActionTypeId())? $this ->ClickActionType->setClickActionTypeId( WTVRcleanString( $this -> getClickActionTypeId()) ) : null;
    ($this -> getClickActionTypeName())? $this ->ClickActionType->setClickActionTypeName( WTVRcleanString( $this -> getClickActionTypeName()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->ClickActionType ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->ClickActionType = ClickActionTypePeer::retrieveByPK($id);
    }
    
    if (! $this ->ClickActionType ) {
      return;
    }
    
    $this ->ClickActionType -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('ClickActionType_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "ClickActionTypePeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $ClickActionType = ClickActionTypePeer::doSelect($c);
    
    if (count($ClickActionType) >= 1) {
      $this ->ClickActionType = $ClickActionType[0];
      return true;
    } else {
      $this ->ClickActionType = new ClickActionType();
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
      $name = "ClickActionTypePeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $ClickActionType = ClickActionTypePeer::doSelect($c);
    
    if (count($ClickActionType) >= 1) {
      $this ->ClickActionType = $ClickActionType[0];
      return true;
    } else {
      $this ->ClickActionType = new ClickActionType();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>