<?php
       
   class ActionCrudBase extends Utils_PageWidget { 
   
    var $Action;
   
       var $action_id;
   var $action_name;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getActionId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Action = ActionPeer::retrieveByPK( $id );
    } else {
      $this ->Action = new Action;
    }
  }
  
  function hydrate( $id ) {
      $this ->Action = ActionPeer::retrieveByPK( $id );
  }
  
  function getActionId() {
    if (($this ->postVar("action_id")) || ($this ->postVar("action_id") === "")) {
      return $this ->postVar("action_id");
    } elseif (($this ->getVar("action_id")) || ($this ->getVar("action_id") === "")) {
      return $this ->getVar("action_id");
    } elseif (($this ->Action) || ($this ->Action === "")){
      return $this ->Action -> getActionId();
    } elseif (($this ->sessionVar("action_id")) || ($this ->sessionVar("action_id") == "")) {
      return $this ->sessionVar("action_id");
    } else {
      return false;
    }
  }
  
  function setActionId( $str ) {
    $this ->Action -> setActionId( $str );
  }
  
  function getActionName() {
    if (($this ->postVar("action_name")) || ($this ->postVar("action_name") === "")) {
      return $this ->postVar("action_name");
    } elseif (($this ->getVar("action_name")) || ($this ->getVar("action_name") === "")) {
      return $this ->getVar("action_name");
    } elseif (($this ->Action) || ($this ->Action === "")){
      return $this ->Action -> getActionName();
    } elseif (($this ->sessionVar("action_name")) || ($this ->sessionVar("action_name") == "")) {
      return $this ->sessionVar("action_name");
    } else {
      return false;
    }
  }
  
  function setActionName( $str ) {
    $this ->Action -> setActionName( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Action = ActionPeer::retrieveByPK( $id );
    }
    
    if ($this ->Action ) {
       
    	       (is_numeric(WTVRcleanString($this ->Action->getActionId()))) ? $itemarray["action_id"] = WTVRcleanString($this ->Action->getActionId()) : null;
          (WTVRcleanString($this ->Action->getActionName())) ? $itemarray["action_name"] = WTVRcleanString($this ->Action->getActionName()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Action = ActionPeer::retrieveByPK( $id );
    } elseif (! $this ->Action) {
      $this ->Action = new Action;
    }
        
  	 ($this -> getActionId())? $this ->Action->setActionId( WTVRcleanString( $this -> getActionId()) ) : null;
    ($this -> getActionName())? $this ->Action->setActionName( WTVRcleanString( $this -> getActionName()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Action ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Action = ActionPeer::retrieveByPK($id);
    }
    
    if (! $this ->Action ) {
      return;
    }
    
    $this ->Action -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Action_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "ActionPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Action = ActionPeer::doSelect($c);
    
    if (count($Action) >= 1) {
      $this ->Action = $Action[0];
      return true;
    } else {
      $this ->Action = new Action();
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
      $name = "ActionPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Action = ActionPeer::doSelect($c);
    
    if (count($Action) >= 1) {
      $this ->Action = $Action[0];
      return true;
    } else {
      $this ->Action = new Action();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>