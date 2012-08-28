<?php
       
   class ChatUsageCrudBase extends Utils_PageWidget { 
   
    var $ChatUsage;
   
       var $chat_usage_id;
   var $chat_usage_date_added;
   var $fk_user_id;
   var $fk_chat_instance_key;
   var $fk_screening_unique_key;
   var $chat_usage_browser;
   var $chat_usage_referer;
   var $chat_usage_remote_addr;
   var $chat_usage_remote_addr_computed;
   var $chat_usage_bandwidth;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getChatUsageId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->ChatUsage = ChatUsagePeer::retrieveByPK( $id );
    } else {
      $this ->ChatUsage = new ChatUsage;
    }
  }
  
  function hydrate( $id ) {
      $this ->ChatUsage = ChatUsagePeer::retrieveByPK( $id );
  }
  
  function getChatUsageId() {
    if (($this ->postVar("chat_usage_id")) || ($this ->postVar("chat_usage_id") === "")) {
      return $this ->postVar("chat_usage_id");
    } elseif (($this ->getVar("chat_usage_id")) || ($this ->getVar("chat_usage_id") === "")) {
      return $this ->getVar("chat_usage_id");
    } elseif (($this ->ChatUsage) || ($this ->ChatUsage === "")){
      return $this ->ChatUsage -> getChatUsageId();
    } elseif (($this ->sessionVar("chat_usage_id")) || ($this ->sessionVar("chat_usage_id") == "")) {
      return $this ->sessionVar("chat_usage_id");
    } else {
      return false;
    }
  }
  
  function setChatUsageId( $str ) {
    $this ->ChatUsage -> setChatUsageId( $str );
  }
  
  function getChatUsageDateAdded() {
    if (($this ->postVar("chat_usage_date_added")) || ($this ->postVar("chat_usage_date_added") === "")) {
      return $this ->postVar("chat_usage_date_added");
    } elseif (($this ->getVar("chat_usage_date_added")) || ($this ->getVar("chat_usage_date_added") === "")) {
      return $this ->getVar("chat_usage_date_added");
    } elseif (($this ->ChatUsage) || ($this ->ChatUsage === "")){
      return $this ->ChatUsage -> getChatUsageDateAdded();
    } elseif (($this ->sessionVar("chat_usage_date_added")) || ($this ->sessionVar("chat_usage_date_added") == "")) {
      return $this ->sessionVar("chat_usage_date_added");
    } else {
      return false;
    }
  }
  
  function setChatUsageDateAdded( $str ) {
    $this ->ChatUsage -> setChatUsageDateAdded( $str );
  }
  
  function getFkUserId() {
    if (($this ->postVar("fk_user_id")) || ($this ->postVar("fk_user_id") === "")) {
      return $this ->postVar("fk_user_id");
    } elseif (($this ->getVar("fk_user_id")) || ($this ->getVar("fk_user_id") === "")) {
      return $this ->getVar("fk_user_id");
    } elseif (($this ->ChatUsage) || ($this ->ChatUsage === "")){
      return $this ->ChatUsage -> getFkUserId();
    } elseif (($this ->sessionVar("fk_user_id")) || ($this ->sessionVar("fk_user_id") == "")) {
      return $this ->sessionVar("fk_user_id");
    } else {
      return false;
    }
  }
  
  function setFkUserId( $str ) {
    $this ->ChatUsage -> setFkUserId( $str );
  }
  
  function getFkChatInstanceKey() {
    if (($this ->postVar("fk_chat_instance_key")) || ($this ->postVar("fk_chat_instance_key") === "")) {
      return $this ->postVar("fk_chat_instance_key");
    } elseif (($this ->getVar("fk_chat_instance_key")) || ($this ->getVar("fk_chat_instance_key") === "")) {
      return $this ->getVar("fk_chat_instance_key");
    } elseif (($this ->ChatUsage) || ($this ->ChatUsage === "")){
      return $this ->ChatUsage -> getFkChatInstanceKey();
    } elseif (($this ->sessionVar("fk_chat_instance_key")) || ($this ->sessionVar("fk_chat_instance_key") == "")) {
      return $this ->sessionVar("fk_chat_instance_key");
    } else {
      return false;
    }
  }
  
  function setFkChatInstanceKey( $str ) {
    $this ->ChatUsage -> setFkChatInstanceKey( $str );
  }
  
  function getFkScreeningUniqueKey() {
    if (($this ->postVar("fk_screening_unique_key")) || ($this ->postVar("fk_screening_unique_key") === "")) {
      return $this ->postVar("fk_screening_unique_key");
    } elseif (($this ->getVar("fk_screening_unique_key")) || ($this ->getVar("fk_screening_unique_key") === "")) {
      return $this ->getVar("fk_screening_unique_key");
    } elseif (($this ->ChatUsage) || ($this ->ChatUsage === "")){
      return $this ->ChatUsage -> getFkScreeningUniqueKey();
    } elseif (($this ->sessionVar("fk_screening_unique_key")) || ($this ->sessionVar("fk_screening_unique_key") == "")) {
      return $this ->sessionVar("fk_screening_unique_key");
    } else {
      return false;
    }
  }
  
  function setFkScreeningUniqueKey( $str ) {
    $this ->ChatUsage -> setFkScreeningUniqueKey( $str );
  }
  
  function getChatUsageBrowser() {
    if (($this ->postVar("chat_usage_browser")) || ($this ->postVar("chat_usage_browser") === "")) {
      return $this ->postVar("chat_usage_browser");
    } elseif (($this ->getVar("chat_usage_browser")) || ($this ->getVar("chat_usage_browser") === "")) {
      return $this ->getVar("chat_usage_browser");
    } elseif (($this ->ChatUsage) || ($this ->ChatUsage === "")){
      return $this ->ChatUsage -> getChatUsageBrowser();
    } elseif (($this ->sessionVar("chat_usage_browser")) || ($this ->sessionVar("chat_usage_browser") == "")) {
      return $this ->sessionVar("chat_usage_browser");
    } else {
      return false;
    }
  }
  
  function setChatUsageBrowser( $str ) {
    $this ->ChatUsage -> setChatUsageBrowser( $str );
  }
  
  function getChatUsageReferer() {
    if (($this ->postVar("chat_usage_referer")) || ($this ->postVar("chat_usage_referer") === "")) {
      return $this ->postVar("chat_usage_referer");
    } elseif (($this ->getVar("chat_usage_referer")) || ($this ->getVar("chat_usage_referer") === "")) {
      return $this ->getVar("chat_usage_referer");
    } elseif (($this ->ChatUsage) || ($this ->ChatUsage === "")){
      return $this ->ChatUsage -> getChatUsageReferer();
    } elseif (($this ->sessionVar("chat_usage_referer")) || ($this ->sessionVar("chat_usage_referer") == "")) {
      return $this ->sessionVar("chat_usage_referer");
    } else {
      return false;
    }
  }
  
  function setChatUsageReferer( $str ) {
    $this ->ChatUsage -> setChatUsageReferer( $str );
  }
  
  function getChatUsageRemoteAddr() {
    if (($this ->postVar("chat_usage_remote_addr")) || ($this ->postVar("chat_usage_remote_addr") === "")) {
      return $this ->postVar("chat_usage_remote_addr");
    } elseif (($this ->getVar("chat_usage_remote_addr")) || ($this ->getVar("chat_usage_remote_addr") === "")) {
      return $this ->getVar("chat_usage_remote_addr");
    } elseif (($this ->ChatUsage) || ($this ->ChatUsage === "")){
      return $this ->ChatUsage -> getChatUsageRemoteAddr();
    } elseif (($this ->sessionVar("chat_usage_remote_addr")) || ($this ->sessionVar("chat_usage_remote_addr") == "")) {
      return $this ->sessionVar("chat_usage_remote_addr");
    } else {
      return false;
    }
  }
  
  function setChatUsageRemoteAddr( $str ) {
    $this ->ChatUsage -> setChatUsageRemoteAddr( $str );
  }
  
  function getChatUsageRemoteAddrComputed() {
    if (($this ->postVar("chat_usage_remote_addr_computed")) || ($this ->postVar("chat_usage_remote_addr_computed") === "")) {
      return $this ->postVar("chat_usage_remote_addr_computed");
    } elseif (($this ->getVar("chat_usage_remote_addr_computed")) || ($this ->getVar("chat_usage_remote_addr_computed") === "")) {
      return $this ->getVar("chat_usage_remote_addr_computed");
    } elseif (($this ->ChatUsage) || ($this ->ChatUsage === "")){
      return $this ->ChatUsage -> getChatUsageRemoteAddrComputed();
    } elseif (($this ->sessionVar("chat_usage_remote_addr_computed")) || ($this ->sessionVar("chat_usage_remote_addr_computed") == "")) {
      return $this ->sessionVar("chat_usage_remote_addr_computed");
    } else {
      return false;
    }
  }
  
  function setChatUsageRemoteAddrComputed( $str ) {
    $this ->ChatUsage -> setChatUsageRemoteAddrComputed( $str );
  }
  
  function getChatUsageBandwidth() {
    if (($this ->postVar("chat_usage_bandwidth")) || ($this ->postVar("chat_usage_bandwidth") === "")) {
      return $this ->postVar("chat_usage_bandwidth");
    } elseif (($this ->getVar("chat_usage_bandwidth")) || ($this ->getVar("chat_usage_bandwidth") === "")) {
      return $this ->getVar("chat_usage_bandwidth");
    } elseif (($this ->ChatUsage) || ($this ->ChatUsage === "")){
      return $this ->ChatUsage -> getChatUsageBandwidth();
    } elseif (($this ->sessionVar("chat_usage_bandwidth")) || ($this ->sessionVar("chat_usage_bandwidth") == "")) {
      return $this ->sessionVar("chat_usage_bandwidth");
    } else {
      return false;
    }
  }
  
  function setChatUsageBandwidth( $str ) {
    $this ->ChatUsage -> setChatUsageBandwidth( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->ChatUsage = ChatUsagePeer::retrieveByPK( $id );
    }
    
    if ($this ->ChatUsage ) {
       
    	       (is_numeric(WTVRcleanString($this ->ChatUsage->getChatUsageId()))) ? $itemarray["chat_usage_id"] = WTVRcleanString($this ->ChatUsage->getChatUsageId()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->ChatUsage->getChatUsageDateAdded())) ? $itemarray["chat_usage_date_added"] = formatDate($this ->ChatUsage->getChatUsageDateAdded('%Y-%m-%d %T'),"TS") : null;
          (is_numeric(WTVRcleanString($this ->ChatUsage->getFkUserId()))) ? $itemarray["fk_user_id"] = WTVRcleanString($this ->ChatUsage->getFkUserId()) : null;
          (WTVRcleanString($this ->ChatUsage->getFkChatInstanceKey())) ? $itemarray["fk_chat_instance_key"] = WTVRcleanString($this ->ChatUsage->getFkChatInstanceKey()) : null;
          (WTVRcleanString($this ->ChatUsage->getFkScreeningUniqueKey())) ? $itemarray["fk_screening_unique_key"] = WTVRcleanString($this ->ChatUsage->getFkScreeningUniqueKey()) : null;
          (WTVRcleanString($this ->ChatUsage->getChatUsageBrowser())) ? $itemarray["chat_usage_browser"] = WTVRcleanString($this ->ChatUsage->getChatUsageBrowser()) : null;
          (WTVRcleanString($this ->ChatUsage->getChatUsageReferer())) ? $itemarray["chat_usage_referer"] = WTVRcleanString($this ->ChatUsage->getChatUsageReferer()) : null;
          (WTVRcleanString($this ->ChatUsage->getChatUsageRemoteAddr())) ? $itemarray["chat_usage_remote_addr"] = WTVRcleanString($this ->ChatUsage->getChatUsageRemoteAddr()) : null;
          (WTVRcleanString($this ->ChatUsage->getChatUsageRemoteAddrComputed())) ? $itemarray["chat_usage_remote_addr_computed"] = WTVRcleanString($this ->ChatUsage->getChatUsageRemoteAddrComputed()) : null;
          (is_numeric(WTVRcleanString($this ->ChatUsage->getChatUsageBandwidth()))) ? $itemarray["chat_usage_bandwidth"] = WTVRcleanString($this ->ChatUsage->getChatUsageBandwidth()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->ChatUsage = ChatUsagePeer::retrieveByPK( $id );
    } elseif (! $this ->ChatUsage) {
      $this ->ChatUsage = new ChatUsage;
    }
        
  	 ($this -> getChatUsageId())? $this ->ChatUsage->setChatUsageId( WTVRcleanString( $this -> getChatUsageId()) ) : null;
          if (is_valid_date( $this ->ChatUsage->getChatUsageDateAdded())) {
        $this ->ChatUsage->setChatUsageDateAdded( formatDate($this -> getChatUsageDateAdded(), "TS" ));
      } else {
      $ChatUsagechat_usage_date_added = $this -> sfDateTime( "chat_usage_date_added" );
      ( $ChatUsagechat_usage_date_added != "01/01/1900 00:00:00" )? $this ->ChatUsage->setChatUsageDateAdded( formatDate($ChatUsagechat_usage_date_added, "TS" )) : $this ->ChatUsage->setChatUsageDateAdded( null );
      }
    ($this -> getFkUserId())? $this ->ChatUsage->setFkUserId( WTVRcleanString( $this -> getFkUserId()) ) : null;
    ($this -> getFkChatInstanceKey())? $this ->ChatUsage->setFkChatInstanceKey( WTVRcleanString( $this -> getFkChatInstanceKey()) ) : null;
    ($this -> getFkScreeningUniqueKey())? $this ->ChatUsage->setFkScreeningUniqueKey( WTVRcleanString( $this -> getFkScreeningUniqueKey()) ) : null;
    ($this -> getChatUsageBrowser())? $this ->ChatUsage->setChatUsageBrowser( WTVRcleanString( $this -> getChatUsageBrowser()) ) : null;
    ($this -> getChatUsageReferer())? $this ->ChatUsage->setChatUsageReferer( WTVRcleanString( $this -> getChatUsageReferer()) ) : null;
    ($this -> getChatUsageRemoteAddr())? $this ->ChatUsage->setChatUsageRemoteAddr( WTVRcleanString( $this -> getChatUsageRemoteAddr()) ) : null;
    ($this -> getChatUsageRemoteAddrComputed())? $this ->ChatUsage->setChatUsageRemoteAddrComputed( WTVRcleanString( $this -> getChatUsageRemoteAddrComputed()) ) : null;
    ($this -> getChatUsageBandwidth())? $this ->ChatUsage->setChatUsageBandwidth( WTVRcleanString( $this -> getChatUsageBandwidth()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->ChatUsage ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->ChatUsage = ChatUsagePeer::retrieveByPK($id);
    }
    
    if (! $this ->ChatUsage ) {
      return;
    }
    
    $this ->ChatUsage -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('ChatUsage_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "ChatUsagePeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $ChatUsage = ChatUsagePeer::doSelect($c);
    
    if (count($ChatUsage) >= 1) {
      $this ->ChatUsage = $ChatUsage[0];
      return true;
    } else {
      $this ->ChatUsage = new ChatUsage();
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
      $name = "ChatUsagePeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $ChatUsage = ChatUsagePeer::doSelect($c);
    
    if (count($ChatUsage) >= 1) {
      $this ->ChatUsage = $ChatUsage[0];
      return true;
    } else {
      $this ->ChatUsage = new ChatUsage();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>