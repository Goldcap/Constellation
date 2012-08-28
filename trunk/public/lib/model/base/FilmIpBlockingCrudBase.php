<?php
       
   class FilmIpBlockingCrudBase extends Utils_PageWidget { 
   
    var $FilmIpBlocking;
   
       var $id;
   var $film_id;
   var $ip_match;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->FilmIpBlocking = FilmIpBlockingPeer::retrieveByPK( $id );
    } else {
      $this ->FilmIpBlocking = new FilmIpBlocking;
    }
  }
  
  function hydrate( $id ) {
      $this ->FilmIpBlocking = FilmIpBlockingPeer::retrieveByPK( $id );
  }
  
  function getId() {
    if (($this ->postVar("id")) || ($this ->postVar("id") === "")) {
      return $this ->postVar("id");
    } elseif (($this ->getVar("id")) || ($this ->getVar("id") === "")) {
      return $this ->getVar("id");
    } elseif (($this ->FilmIpBlocking) || ($this ->FilmIpBlocking === "")){
      return $this ->FilmIpBlocking -> getId();
    } elseif (($this ->sessionVar("id")) || ($this ->sessionVar("id") == "")) {
      return $this ->sessionVar("id");
    } else {
      return false;
    }
  }
  
  function setId( $str ) {
    $this ->FilmIpBlocking -> setId( $str );
  }
  
  function getFilmId() {
    if (($this ->postVar("film_id")) || ($this ->postVar("film_id") === "")) {
      return $this ->postVar("film_id");
    } elseif (($this ->getVar("film_id")) || ($this ->getVar("film_id") === "")) {
      return $this ->getVar("film_id");
    } elseif (($this ->FilmIpBlocking) || ($this ->FilmIpBlocking === "")){
      return $this ->FilmIpBlocking -> getFilmId();
    } elseif (($this ->sessionVar("film_id")) || ($this ->sessionVar("film_id") == "")) {
      return $this ->sessionVar("film_id");
    } else {
      return false;
    }
  }
  
  function setFilmId( $str ) {
    $this ->FilmIpBlocking -> setFilmId( $str );
  }
  
  function getIpMatch() {
    if (($this ->postVar("ip_match")) || ($this ->postVar("ip_match") === "")) {
      return $this ->postVar("ip_match");
    } elseif (($this ->getVar("ip_match")) || ($this ->getVar("ip_match") === "")) {
      return $this ->getVar("ip_match");
    } elseif (($this ->FilmIpBlocking) || ($this ->FilmIpBlocking === "")){
      return $this ->FilmIpBlocking -> getIpMatch();
    } elseif (($this ->sessionVar("ip_match")) || ($this ->sessionVar("ip_match") == "")) {
      return $this ->sessionVar("ip_match");
    } else {
      return false;
    }
  }
  
  function setIpMatch( $str ) {
    $this ->FilmIpBlocking -> setIpMatch( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->FilmIpBlocking = FilmIpBlockingPeer::retrieveByPK( $id );
    }
    
    if ($this ->FilmIpBlocking ) {
       
    	       (is_numeric(WTVRcleanString($this ->FilmIpBlocking->getId()))) ? $itemarray["id"] = WTVRcleanString($this ->FilmIpBlocking->getId()) : null;
          (is_numeric(WTVRcleanString($this ->FilmIpBlocking->getFilmId()))) ? $itemarray["film_id"] = WTVRcleanString($this ->FilmIpBlocking->getFilmId()) : null;
          (WTVRcleanString($this ->FilmIpBlocking->getIpMatch())) ? $itemarray["ip_match"] = WTVRcleanString($this ->FilmIpBlocking->getIpMatch()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->FilmIpBlocking = FilmIpBlockingPeer::retrieveByPK( $id );
    } elseif (! $this ->FilmIpBlocking) {
      $this ->FilmIpBlocking = new FilmIpBlocking;
    }
        
  	 ($this -> getId())? $this ->FilmIpBlocking->setId( WTVRcleanString( $this -> getId()) ) : null;
    ($this -> getFilmId())? $this ->FilmIpBlocking->setFilmId( WTVRcleanString( $this -> getFilmId()) ) : null;
    ($this -> getIpMatch())? $this ->FilmIpBlocking->setIpMatch( WTVRcleanString( $this -> getIpMatch()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->FilmIpBlocking ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->FilmIpBlocking = FilmIpBlockingPeer::retrieveByPK($id);
    }
    
    if (! $this ->FilmIpBlocking ) {
      return;
    }
    
    $this ->FilmIpBlocking -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('FilmIpBlocking_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "FilmIpBlockingPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $FilmIpBlocking = FilmIpBlockingPeer::doSelect($c);
    
    if (count($FilmIpBlocking) >= 1) {
      $this ->FilmIpBlocking = $FilmIpBlocking[0];
      return true;
    } else {
      $this ->FilmIpBlocking = new FilmIpBlocking();
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
      $name = "FilmIpBlockingPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $FilmIpBlocking = FilmIpBlockingPeer::doSelect($c);
    
    if (count($FilmIpBlocking) >= 1) {
      $this ->FilmIpBlocking = $FilmIpBlocking[0];
      return true;
    } else {
      $this ->FilmIpBlocking = new FilmIpBlocking();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>