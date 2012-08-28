<?php
       
   class JumpCrudBase extends Utils_PageWidget { 
   
    var $Jump;
   
       var $jump_id;
   var $jump_email;
   var $jump_referer;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getJumpId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Jump = JumpPeer::retrieveByPK( $id );
    } else {
      $this ->Jump = new Jump;
    }
  }
  
  function hydrate( $id ) {
      $this ->Jump = JumpPeer::retrieveByPK( $id );
  }
  
  function getJumpId() {
    if (($this ->postVar("jump_id")) || ($this ->postVar("jump_id") === "")) {
      return $this ->postVar("jump_id");
    } elseif (($this ->getVar("jump_id")) || ($this ->getVar("jump_id") === "")) {
      return $this ->getVar("jump_id");
    } elseif (($this ->Jump) || ($this ->Jump === "")){
      return $this ->Jump -> getJumpId();
    } elseif (($this ->sessionVar("jump_id")) || ($this ->sessionVar("jump_id") == "")) {
      return $this ->sessionVar("jump_id");
    } else {
      return false;
    }
  }
  
  function setJumpId( $str ) {
    $this ->Jump -> setJumpId( $str );
  }
  
  function getJumpEmail() {
    if (($this ->postVar("jump_email")) || ($this ->postVar("jump_email") === "")) {
      return $this ->postVar("jump_email");
    } elseif (($this ->getVar("jump_email")) || ($this ->getVar("jump_email") === "")) {
      return $this ->getVar("jump_email");
    } elseif (($this ->Jump) || ($this ->Jump === "")){
      return $this ->Jump -> getJumpEmail();
    } elseif (($this ->sessionVar("jump_email")) || ($this ->sessionVar("jump_email") == "")) {
      return $this ->sessionVar("jump_email");
    } else {
      return false;
    }
  }
  
  function setJumpEmail( $str ) {
    $this ->Jump -> setJumpEmail( $str );
  }
  
  function getJumpReferer() {
    if (($this ->postVar("jump_referer")) || ($this ->postVar("jump_referer") === "")) {
      return $this ->postVar("jump_referer");
    } elseif (($this ->getVar("jump_referer")) || ($this ->getVar("jump_referer") === "")) {
      return $this ->getVar("jump_referer");
    } elseif (($this ->Jump) || ($this ->Jump === "")){
      return $this ->Jump -> getJumpReferer();
    } elseif (($this ->sessionVar("jump_referer")) || ($this ->sessionVar("jump_referer") == "")) {
      return $this ->sessionVar("jump_referer");
    } else {
      return false;
    }
  }
  
  function setJumpReferer( $str ) {
    $this ->Jump -> setJumpReferer( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Jump = JumpPeer::retrieveByPK( $id );
    }
    
    if ($this ->Jump ) {
       
    	       (is_numeric(WTVRcleanString($this ->Jump->getJumpId()))) ? $itemarray["jump_id"] = WTVRcleanString($this ->Jump->getJumpId()) : null;
          (WTVRcleanString($this ->Jump->getJumpEmail())) ? $itemarray["jump_email"] = WTVRcleanString($this ->Jump->getJumpEmail()) : null;
          (WTVRcleanString($this ->Jump->getJumpReferer())) ? $itemarray["jump_referer"] = WTVRcleanString($this ->Jump->getJumpReferer()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Jump = JumpPeer::retrieveByPK( $id );
    } elseif (! $this ->Jump) {
      $this ->Jump = new Jump;
    }
        
  	 ($this -> getJumpId())? $this ->Jump->setJumpId( WTVRcleanString( $this -> getJumpId()) ) : null;
    ($this -> getJumpEmail())? $this ->Jump->setJumpEmail( WTVRcleanString( $this -> getJumpEmail()) ) : null;
    ($this -> getJumpReferer())? $this ->Jump->setJumpReferer( WTVRcleanString( $this -> getJumpReferer()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Jump ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Jump = JumpPeer::retrieveByPK($id);
    }
    
    if (! $this ->Jump ) {
      return;
    }
    
    $this ->Jump -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Jump_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "JumpPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Jump = JumpPeer::doSelect($c);
    
    if (count($Jump) >= 1) {
      $this ->Jump = $Jump[0];
      return true;
    } else {
      $this ->Jump = new Jump();
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
      $name = "JumpPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Jump = JumpPeer::doSelect($c);
    
    if (count($Jump) >= 1) {
      $this ->Jump = $Jump[0];
      return true;
    } else {
      $this ->Jump = new Jump();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>