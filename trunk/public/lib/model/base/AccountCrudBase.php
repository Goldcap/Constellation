<?php
       
   class AccountCrudBase extends Utils_PageWidget { 
   
    var $Account;
   
       var $account_id;
   var $username;
   var $pass;
   var $fk_user_id;
   var $account_active;
   var $account_pw_raw;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getAccountId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Account = AccountPeer::retrieveByPK( $id );
    } else {
      $this ->Account = new Account;
    }
  }
  
  function hydrate( $id ) {
      $this ->Account = AccountPeer::retrieveByPK( $id );
  }
  
  function getAccountId() {
    if (($this ->postVar("account_id")) || ($this ->postVar("account_id") === "")) {
      return $this ->postVar("account_id");
    } elseif (($this ->getVar("account_id")) || ($this ->getVar("account_id") === "")) {
      return $this ->getVar("account_id");
    } elseif (($this ->Account) || ($this ->Account === "")){
      return $this ->Account -> getAccountId();
    } elseif (($this ->sessionVar("account_id")) || ($this ->sessionVar("account_id") == "")) {
      return $this ->sessionVar("account_id");
    } else {
      return false;
    }
  }
  
  function setAccountId( $str ) {
    $this ->Account -> setAccountId( $str );
  }
  
  function getUsername() {
    if (($this ->postVar("username")) || ($this ->postVar("username") === "")) {
      return $this ->postVar("username");
    } elseif (($this ->getVar("username")) || ($this ->getVar("username") === "")) {
      return $this ->getVar("username");
    } elseif (($this ->Account) || ($this ->Account === "")){
      return $this ->Account -> getUsername();
    } elseif (($this ->sessionVar("username")) || ($this ->sessionVar("username") == "")) {
      return $this ->sessionVar("username");
    } else {
      return false;
    }
  }
  
  function setUsername( $str ) {
    $this ->Account -> setUsername( $str );
  }
  
  function getPass() {
    if (($this ->postVar("pass")) || ($this ->postVar("pass") === "")) {
      return $this ->postVar("pass");
    } elseif (($this ->getVar("pass")) || ($this ->getVar("pass") === "")) {
      return $this ->getVar("pass");
    } elseif (($this ->Account) || ($this ->Account === "")){
      return $this ->Account -> getPass();
    } elseif (($this ->sessionVar("pass")) || ($this ->sessionVar("pass") == "")) {
      return $this ->sessionVar("pass");
    } else {
      return false;
    }
  }
  
  function setPass( $str ) {
    $this ->Account -> setPass( $str );
  }
  
  function getFkUserId() {
    if (($this ->postVar("fk_user_id")) || ($this ->postVar("fk_user_id") === "")) {
      return $this ->postVar("fk_user_id");
    } elseif (($this ->getVar("fk_user_id")) || ($this ->getVar("fk_user_id") === "")) {
      return $this ->getVar("fk_user_id");
    } elseif (($this ->Account) || ($this ->Account === "")){
      return $this ->Account -> getFkUserId();
    } elseif (($this ->sessionVar("fk_user_id")) || ($this ->sessionVar("fk_user_id") == "")) {
      return $this ->sessionVar("fk_user_id");
    } else {
      return false;
    }
  }
  
  function setFkUserId( $str ) {
    $this ->Account -> setFkUserId( $str );
  }
  
  function getAccountActive() {
    if (($this ->postVar("account_active")) || ($this ->postVar("account_active") === "")) {
      return $this ->postVar("account_active");
    } elseif (($this ->getVar("account_active")) || ($this ->getVar("account_active") === "")) {
      return $this ->getVar("account_active");
    } elseif (($this ->Account) || ($this ->Account === "")){
      return $this ->Account -> getAccountActive();
    } elseif (($this ->sessionVar("account_active")) || ($this ->sessionVar("account_active") == "")) {
      return $this ->sessionVar("account_active");
    } else {
      return false;
    }
  }
  
  function setAccountActive( $str ) {
    $this ->Account -> setAccountActive( $str );
  }
  
  function getAccountPwRaw() {
    if (($this ->postVar("account_pw_raw")) || ($this ->postVar("account_pw_raw") === "")) {
      return $this ->postVar("account_pw_raw");
    } elseif (($this ->getVar("account_pw_raw")) || ($this ->getVar("account_pw_raw") === "")) {
      return $this ->getVar("account_pw_raw");
    } elseif (($this ->Account) || ($this ->Account === "")){
      return $this ->Account -> getAccountPwRaw();
    } elseif (($this ->sessionVar("account_pw_raw")) || ($this ->sessionVar("account_pw_raw") == "")) {
      return $this ->sessionVar("account_pw_raw");
    } else {
      return false;
    }
  }
  
  function setAccountPwRaw( $str ) {
    $this ->Account -> setAccountPwRaw( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Account = AccountPeer::retrieveByPK( $id );
    }
    
    if ($this ->Account ) {
       
    	       (is_numeric(WTVRcleanString($this ->Account->getAccountId()))) ? $itemarray["account_id"] = WTVRcleanString($this ->Account->getAccountId()) : null;
          (WTVRcleanString($this ->Account->getUsername())) ? $itemarray["username"] = WTVRcleanString($this ->Account->getUsername()) : null;
          (WTVRcleanString($this ->Account->getPass())) ? $itemarray["pass"] = WTVRcleanString($this ->Account->getPass()) : null;
          (is_numeric(WTVRcleanString($this ->Account->getFkUserId()))) ? $itemarray["fk_user_id"] = WTVRcleanString($this ->Account->getFkUserId()) : null;
          (WTVRcleanString($this ->Account->getAccountActive())) ? $itemarray["account_active"] = WTVRcleanString($this ->Account->getAccountActive()) : null;
          (WTVRcleanString($this ->Account->getAccountPwRaw())) ? $itemarray["account_pw_raw"] = WTVRcleanString($this ->Account->getAccountPwRaw()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Account = AccountPeer::retrieveByPK( $id );
    } elseif (! $this ->Account) {
      $this ->Account = new Account;
    }
        
  	 ($this -> getAccountId())? $this ->Account->setAccountId( WTVRcleanString( $this -> getAccountId()) ) : null;
    ($this -> getUsername())? $this ->Account->setUsername( WTVRcleanString( $this -> getUsername()) ) : null;
    ($this -> getPass())? $this ->Account->setPass( WTVRcleanString( $this -> getPass()) ) : null;
    ($this -> getFkUserId())? $this ->Account->setFkUserId( WTVRcleanString( $this -> getFkUserId()) ) : null;
    ($this -> getAccountActive())? $this ->Account->setAccountActive( WTVRcleanString( $this -> getAccountActive()) ) : null;
    ($this -> getAccountPwRaw())? $this ->Account->setAccountPwRaw( WTVRcleanString( $this -> getAccountPwRaw()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Account ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Account = AccountPeer::retrieveByPK($id);
    }
    
    if (! $this ->Account ) {
      return;
    }
    
    $this ->Account -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Account_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "AccountPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Account = AccountPeer::doSelect($c);
    
    if (count($Account) >= 1) {
      $this ->Account = $Account[0];
      return true;
    } else {
      $this ->Account = new Account();
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
      $name = "AccountPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Account = AccountPeer::doSelect($c);
    
    if (count($Account) >= 1) {
      $this ->Account = $Account[0];
      return true;
    } else {
      $this ->Account = new Account();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>