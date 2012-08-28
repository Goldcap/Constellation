<?php
       
   class ChatInstanceCrudBase extends Utils_PageWidget { 
   
    var $ChatInstance;
   
       var $chat_instance_id;
   var $fk_screening_key;
   var $chat_instance_key;
   var $chat_instance_host;
   var $chat_instance_port;
   var $chat_instance_count;
   var $chat_instance_name;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getChatInstanceId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->ChatInstance = ChatInstancePeer::retrieveByPK( $id );
    } else {
      $this ->ChatInstance = new ChatInstance;
    }
  }
  
  function hydrate( $id ) {
      $this ->ChatInstance = ChatInstancePeer::retrieveByPK( $id );
  }
  
  function getChatInstanceId() {
    if (($this ->postVar("chat_instance_id")) || ($this ->postVar("chat_instance_id") === "")) {
      return $this ->postVar("chat_instance_id");
    } elseif (($this ->getVar("chat_instance_id")) || ($this ->getVar("chat_instance_id") === "")) {
      return $this ->getVar("chat_instance_id");
    } elseif (($this ->ChatInstance) || ($this ->ChatInstance === "")){
      return $this ->ChatInstance -> getChatInstanceId();
    } elseif (($this ->sessionVar("chat_instance_id")) || ($this ->sessionVar("chat_instance_id") == "")) {
      return $this ->sessionVar("chat_instance_id");
    } else {
      return false;
    }
  }
  
  function setChatInstanceId( $str ) {
    $this ->ChatInstance -> setChatInstanceId( $str );
  }
  
  function getFkScreeningKey() {
    if (($this ->postVar("fk_screening_key")) || ($this ->postVar("fk_screening_key") === "")) {
      return $this ->postVar("fk_screening_key");
    } elseif (($this ->getVar("fk_screening_key")) || ($this ->getVar("fk_screening_key") === "")) {
      return $this ->getVar("fk_screening_key");
    } elseif (($this ->ChatInstance) || ($this ->ChatInstance === "")){
      return $this ->ChatInstance -> getFkScreeningKey();
    } elseif (($this ->sessionVar("fk_screening_key")) || ($this ->sessionVar("fk_screening_key") == "")) {
      return $this ->sessionVar("fk_screening_key");
    } else {
      return false;
    }
  }
  
  function setFkScreeningKey( $str ) {
    $this ->ChatInstance -> setFkScreeningKey( $str );
  }
  
  function getChatInstanceKey() {
    if (($this ->postVar("chat_instance_key")) || ($this ->postVar("chat_instance_key") === "")) {
      return $this ->postVar("chat_instance_key");
    } elseif (($this ->getVar("chat_instance_key")) || ($this ->getVar("chat_instance_key") === "")) {
      return $this ->getVar("chat_instance_key");
    } elseif (($this ->ChatInstance) || ($this ->ChatInstance === "")){
      return $this ->ChatInstance -> getChatInstanceKey();
    } elseif (($this ->sessionVar("chat_instance_key")) || ($this ->sessionVar("chat_instance_key") == "")) {
      return $this ->sessionVar("chat_instance_key");
    } else {
      return false;
    }
  }
  
  function setChatInstanceKey( $str ) {
    $this ->ChatInstance -> setChatInstanceKey( $str );
  }
  
  function getChatInstanceHost() {
    if (($this ->postVar("chat_instance_host")) || ($this ->postVar("chat_instance_host") === "")) {
      return $this ->postVar("chat_instance_host");
    } elseif (($this ->getVar("chat_instance_host")) || ($this ->getVar("chat_instance_host") === "")) {
      return $this ->getVar("chat_instance_host");
    } elseif (($this ->ChatInstance) || ($this ->ChatInstance === "")){
      return $this ->ChatInstance -> getChatInstanceHost();
    } elseif (($this ->sessionVar("chat_instance_host")) || ($this ->sessionVar("chat_instance_host") == "")) {
      return $this ->sessionVar("chat_instance_host");
    } else {
      return false;
    }
  }
  
  function setChatInstanceHost( $str ) {
    $this ->ChatInstance -> setChatInstanceHost( $str );
  }
  
  function getChatInstancePort() {
    if (($this ->postVar("chat_instance_port")) || ($this ->postVar("chat_instance_port") === "")) {
      return $this ->postVar("chat_instance_port");
    } elseif (($this ->getVar("chat_instance_port")) || ($this ->getVar("chat_instance_port") === "")) {
      return $this ->getVar("chat_instance_port");
    } elseif (($this ->ChatInstance) || ($this ->ChatInstance === "")){
      return $this ->ChatInstance -> getChatInstancePort();
    } elseif (($this ->sessionVar("chat_instance_port")) || ($this ->sessionVar("chat_instance_port") == "")) {
      return $this ->sessionVar("chat_instance_port");
    } else {
      return false;
    }
  }
  
  function setChatInstancePort( $str ) {
    $this ->ChatInstance -> setChatInstancePort( $str );
  }
  
  function getChatInstanceCount() {
    if (($this ->postVar("chat_instance_count")) || ($this ->postVar("chat_instance_count") === "")) {
      return $this ->postVar("chat_instance_count");
    } elseif (($this ->getVar("chat_instance_count")) || ($this ->getVar("chat_instance_count") === "")) {
      return $this ->getVar("chat_instance_count");
    } elseif (($this ->ChatInstance) || ($this ->ChatInstance === "")){
      return $this ->ChatInstance -> getChatInstanceCount();
    } elseif (($this ->sessionVar("chat_instance_count")) || ($this ->sessionVar("chat_instance_count") == "")) {
      return $this ->sessionVar("chat_instance_count");
    } else {
      return false;
    }
  }
  
  function setChatInstanceCount( $str ) {
    $this ->ChatInstance -> setChatInstanceCount( $str );
  }
  
  function getChatInstanceName() {
    if (($this ->postVar("chat_instance_name")) || ($this ->postVar("chat_instance_name") === "")) {
      return $this ->postVar("chat_instance_name");
    } elseif (($this ->getVar("chat_instance_name")) || ($this ->getVar("chat_instance_name") === "")) {
      return $this ->getVar("chat_instance_name");
    } elseif (($this ->ChatInstance) || ($this ->ChatInstance === "")){
      return $this ->ChatInstance -> getChatInstanceName();
    } elseif (($this ->sessionVar("chat_instance_name")) || ($this ->sessionVar("chat_instance_name") == "")) {
      return $this ->sessionVar("chat_instance_name");
    } else {
      return false;
    }
  }
  
  function setChatInstanceName( $str ) {
    $this ->ChatInstance -> setChatInstanceName( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->ChatInstance = ChatInstancePeer::retrieveByPK( $id );
    }
    
    if ($this ->ChatInstance ) {
       
    	       (WTVRcleanString($this ->ChatInstance->getChatInstanceId())) ? $itemarray["chat_instance_id"] = WTVRcleanString($this ->ChatInstance->getChatInstanceId()) : null;
          (WTVRcleanString($this ->ChatInstance->getFkScreeningKey())) ? $itemarray["fk_screening_key"] = WTVRcleanString($this ->ChatInstance->getFkScreeningKey()) : null;
          (WTVRcleanString($this ->ChatInstance->getChatInstanceKey())) ? $itemarray["chat_instance_key"] = WTVRcleanString($this ->ChatInstance->getChatInstanceKey()) : null;
          (WTVRcleanString($this ->ChatInstance->getChatInstanceHost())) ? $itemarray["chat_instance_host"] = WTVRcleanString($this ->ChatInstance->getChatInstanceHost()) : null;
          (WTVRcleanString($this ->ChatInstance->getChatInstancePort())) ? $itemarray["chat_instance_port"] = WTVRcleanString($this ->ChatInstance->getChatInstancePort()) : null;
          (is_numeric(WTVRcleanString($this ->ChatInstance->getChatInstanceCount()))) ? $itemarray["chat_instance_count"] = WTVRcleanString($this ->ChatInstance->getChatInstanceCount()) : null;
          (WTVRcleanString($this ->ChatInstance->getChatInstanceName())) ? $itemarray["chat_instance_name"] = WTVRcleanString($this ->ChatInstance->getChatInstanceName()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->ChatInstance = ChatInstancePeer::retrieveByPK( $id );
    } elseif (! $this ->ChatInstance) {
      $this ->ChatInstance = new ChatInstance;
    }
        
  	 ($this -> getChatInstanceId())? $this ->ChatInstance->setChatInstanceId( WTVRcleanString( $this -> getChatInstanceId()) ) : null;
    ($this -> getFkScreeningKey())? $this ->ChatInstance->setFkScreeningKey( WTVRcleanString( $this -> getFkScreeningKey()) ) : null;
    ($this -> getChatInstanceKey())? $this ->ChatInstance->setChatInstanceKey( WTVRcleanString( $this -> getChatInstanceKey()) ) : null;
    ($this -> getChatInstanceHost())? $this ->ChatInstance->setChatInstanceHost( WTVRcleanString( $this -> getChatInstanceHost()) ) : null;
    ($this -> getChatInstancePort())? $this ->ChatInstance->setChatInstancePort( WTVRcleanString( $this -> getChatInstancePort()) ) : null;
    ($this -> getChatInstanceCount())? $this ->ChatInstance->setChatInstanceCount( WTVRcleanString( $this -> getChatInstanceCount()) ) : null;
    ($this -> getChatInstanceName())? $this ->ChatInstance->setChatInstanceName( WTVRcleanString( $this -> getChatInstanceName()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->ChatInstance ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->ChatInstance = ChatInstancePeer::retrieveByPK($id);
    }
    
    if (! $this ->ChatInstance ) {
      return;
    }
    
    $this ->ChatInstance -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('ChatInstance_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "ChatInstancePeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $ChatInstance = ChatInstancePeer::doSelect($c);
    
    if (count($ChatInstance) >= 1) {
      $this ->ChatInstance = $ChatInstance[0];
      return true;
    } else {
      $this ->ChatInstance = new ChatInstance();
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
      $name = "ChatInstancePeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $ChatInstance = ChatInstancePeer::doSelect($c);
    
    if (count($ChatInstance) >= 1) {
      $this ->ChatInstance = $ChatInstance[0];
      return true;
    } else {
      $this ->ChatInstance = new ChatInstance();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>