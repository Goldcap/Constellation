<?php
       
   class AliasCrudBase extends Utils_PageWidget { 
   
    var $Alias;
   
       var $alias_id;
   var $fk_film_id;
   var $alias_name;
   var $alias_status;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getAliasId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Alias = AliasPeer::retrieveByPK( $id );
    } else {
      $this ->Alias = new Alias;
    }
  }
  
  function hydrate( $id ) {
      $this ->Alias = AliasPeer::retrieveByPK( $id );
  }
  
  function getAliasId() {
    if (($this ->postVar("alias_id")) || ($this ->postVar("alias_id") === "")) {
      return $this ->postVar("alias_id");
    } elseif (($this ->getVar("alias_id")) || ($this ->getVar("alias_id") === "")) {
      return $this ->getVar("alias_id");
    } elseif (($this ->Alias) || ($this ->Alias === "")){
      return $this ->Alias -> getAliasId();
    } elseif (($this ->sessionVar("alias_id")) || ($this ->sessionVar("alias_id") == "")) {
      return $this ->sessionVar("alias_id");
    } else {
      return false;
    }
  }
  
  function setAliasId( $str ) {
    $this ->Alias -> setAliasId( $str );
  }
  
  function getFkFilmId() {
    if (($this ->postVar("fk_film_id")) || ($this ->postVar("fk_film_id") === "")) {
      return $this ->postVar("fk_film_id");
    } elseif (($this ->getVar("fk_film_id")) || ($this ->getVar("fk_film_id") === "")) {
      return $this ->getVar("fk_film_id");
    } elseif (($this ->Alias) || ($this ->Alias === "")){
      return $this ->Alias -> getFkFilmId();
    } elseif (($this ->sessionVar("fk_film_id")) || ($this ->sessionVar("fk_film_id") == "")) {
      return $this ->sessionVar("fk_film_id");
    } else {
      return false;
    }
  }
  
  function setFkFilmId( $str ) {
    $this ->Alias -> setFkFilmId( $str );
  }
  
  function getAliasName() {
    if (($this ->postVar("alias_name")) || ($this ->postVar("alias_name") === "")) {
      return $this ->postVar("alias_name");
    } elseif (($this ->getVar("alias_name")) || ($this ->getVar("alias_name") === "")) {
      return $this ->getVar("alias_name");
    } elseif (($this ->Alias) || ($this ->Alias === "")){
      return $this ->Alias -> getAliasName();
    } elseif (($this ->sessionVar("alias_name")) || ($this ->sessionVar("alias_name") == "")) {
      return $this ->sessionVar("alias_name");
    } else {
      return false;
    }
  }
  
  function setAliasName( $str ) {
    $this ->Alias -> setAliasName( $str );
  }
  
  function getAliasStatus() {
    if (($this ->postVar("alias_status")) || ($this ->postVar("alias_status") === "")) {
      return $this ->postVar("alias_status");
    } elseif (($this ->getVar("alias_status")) || ($this ->getVar("alias_status") === "")) {
      return $this ->getVar("alias_status");
    } elseif (($this ->Alias) || ($this ->Alias === "")){
      return $this ->Alias -> getAliasStatus();
    } elseif (($this ->sessionVar("alias_status")) || ($this ->sessionVar("alias_status") == "")) {
      return $this ->sessionVar("alias_status");
    } else {
      return false;
    }
  }
  
  function setAliasStatus( $str ) {
    $this ->Alias -> setAliasStatus( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Alias = AliasPeer::retrieveByPK( $id );
    }
    
    if ($this ->Alias ) {
       
    	       (is_numeric(WTVRcleanString($this ->Alias->getAliasId()))) ? $itemarray["alias_id"] = WTVRcleanString($this ->Alias->getAliasId()) : null;
          (is_numeric(WTVRcleanString($this ->Alias->getFkFilmId()))) ? $itemarray["fk_film_id"] = WTVRcleanString($this ->Alias->getFkFilmId()) : null;
          (WTVRcleanString($this ->Alias->getAliasName())) ? $itemarray["alias_name"] = WTVRcleanString($this ->Alias->getAliasName()) : null;
          (WTVRcleanString($this ->Alias->getAliasStatus())) ? $itemarray["alias_status"] = WTVRcleanString($this ->Alias->getAliasStatus()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Alias = AliasPeer::retrieveByPK( $id );
    } elseif (! $this ->Alias) {
      $this ->Alias = new Alias;
    }
        
  	 ($this -> getAliasId())? $this ->Alias->setAliasId( WTVRcleanString( $this -> getAliasId()) ) : null;
    ($this -> getFkFilmId())? $this ->Alias->setFkFilmId( WTVRcleanString( $this -> getFkFilmId()) ) : null;
    ($this -> getAliasName())? $this ->Alias->setAliasName( WTVRcleanString( $this -> getAliasName()) ) : null;
    ($this -> getAliasStatus())? $this ->Alias->setAliasStatus( WTVRcleanString( $this -> getAliasStatus()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Alias ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Alias = AliasPeer::retrieveByPK($id);
    }
    
    if (! $this ->Alias ) {
      return;
    }
    
    $this ->Alias -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Alias_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "AliasPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Alias = AliasPeer::doSelect($c);
    
    if (count($Alias) >= 1) {
      $this ->Alias = $Alias[0];
      return true;
    } else {
      $this ->Alias = new Alias();
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
      $name = "AliasPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Alias = AliasPeer::doSelect($c);
    
    if (count($Alias) >= 1) {
      $this ->Alias = $Alias[0];
      return true;
    } else {
      $this ->Alias = new Alias();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>