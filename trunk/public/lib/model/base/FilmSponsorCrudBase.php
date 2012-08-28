<?php
       
   class FilmSponsorCrudBase extends Utils_PageWidget { 
   
    var $FilmSponsor;
   
       var $film_sponsor_id;
   var $film_sponsor_name;
   var $film_sponsor_params;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getFilmSponsorId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->FilmSponsor = FilmSponsorPeer::retrieveByPK( $id );
    } else {
      $this ->FilmSponsor = new FilmSponsor;
    }
  }
  
  function hydrate( $id ) {
      $this ->FilmSponsor = FilmSponsorPeer::retrieveByPK( $id );
  }
  
  function getFilmSponsorId() {
    if (($this ->postVar("film_sponsor_id")) || ($this ->postVar("film_sponsor_id") === "")) {
      return $this ->postVar("film_sponsor_id");
    } elseif (($this ->getVar("film_sponsor_id")) || ($this ->getVar("film_sponsor_id") === "")) {
      return $this ->getVar("film_sponsor_id");
    } elseif (($this ->FilmSponsor) || ($this ->FilmSponsor === "")){
      return $this ->FilmSponsor -> getFilmSponsorId();
    } elseif (($this ->sessionVar("film_sponsor_id")) || ($this ->sessionVar("film_sponsor_id") == "")) {
      return $this ->sessionVar("film_sponsor_id");
    } else {
      return false;
    }
  }
  
  function setFilmSponsorId( $str ) {
    $this ->FilmSponsor -> setFilmSponsorId( $str );
  }
  
  function getFilmSponsorName() {
    if (($this ->postVar("film_sponsor_name")) || ($this ->postVar("film_sponsor_name") === "")) {
      return $this ->postVar("film_sponsor_name");
    } elseif (($this ->getVar("film_sponsor_name")) || ($this ->getVar("film_sponsor_name") === "")) {
      return $this ->getVar("film_sponsor_name");
    } elseif (($this ->FilmSponsor) || ($this ->FilmSponsor === "")){
      return $this ->FilmSponsor -> getFilmSponsorName();
    } elseif (($this ->sessionVar("film_sponsor_name")) || ($this ->sessionVar("film_sponsor_name") == "")) {
      return $this ->sessionVar("film_sponsor_name");
    } else {
      return false;
    }
  }
  
  function setFilmSponsorName( $str ) {
    $this ->FilmSponsor -> setFilmSponsorName( $str );
  }
  
  function getFilmSponsorParams() {
    if (($this ->postVar("film_sponsor_params")) || ($this ->postVar("film_sponsor_params") === "")) {
      return $this ->postVar("film_sponsor_params");
    } elseif (($this ->getVar("film_sponsor_params")) || ($this ->getVar("film_sponsor_params") === "")) {
      return $this ->getVar("film_sponsor_params");
    } elseif (($this ->FilmSponsor) || ($this ->FilmSponsor === "")){
      return $this ->FilmSponsor -> getFilmSponsorParams();
    } elseif (($this ->sessionVar("film_sponsor_params")) || ($this ->sessionVar("film_sponsor_params") == "")) {
      return $this ->sessionVar("film_sponsor_params");
    } else {
      return false;
    }
  }
  
  function setFilmSponsorParams( $str ) {
    $this ->FilmSponsor -> setFilmSponsorParams( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->FilmSponsor = FilmSponsorPeer::retrieveByPK( $id );
    }
    
    if ($this ->FilmSponsor ) {
       
    	       (is_numeric(WTVRcleanString($this ->FilmSponsor->getFilmSponsorId()))) ? $itemarray["film_sponsor_id"] = WTVRcleanString($this ->FilmSponsor->getFilmSponsorId()) : null;
          (WTVRcleanString($this ->FilmSponsor->getFilmSponsorName())) ? $itemarray["film_sponsor_name"] = WTVRcleanString($this ->FilmSponsor->getFilmSponsorName()) : null;
          (WTVRcleanString($this ->FilmSponsor->getFilmSponsorParams())) ? $itemarray["film_sponsor_params"] = WTVRcleanString($this ->FilmSponsor->getFilmSponsorParams()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->FilmSponsor = FilmSponsorPeer::retrieveByPK( $id );
    } elseif (! $this ->FilmSponsor) {
      $this ->FilmSponsor = new FilmSponsor;
    }
        
  	 ($this -> getFilmSponsorId())? $this ->FilmSponsor->setFilmSponsorId( WTVRcleanString( $this -> getFilmSponsorId()) ) : null;
    ($this -> getFilmSponsorName())? $this ->FilmSponsor->setFilmSponsorName( WTVRcleanString( $this -> getFilmSponsorName()) ) : null;
    ($this -> getFilmSponsorParams())? $this ->FilmSponsor->setFilmSponsorParams( WTVRcleanString( $this -> getFilmSponsorParams()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->FilmSponsor ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->FilmSponsor = FilmSponsorPeer::retrieveByPK($id);
    }
    
    if (! $this ->FilmSponsor ) {
      return;
    }
    
    $this ->FilmSponsor -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('FilmSponsor_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "FilmSponsorPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $FilmSponsor = FilmSponsorPeer::doSelect($c);
    
    if (count($FilmSponsor) >= 1) {
      $this ->FilmSponsor = $FilmSponsor[0];
      return true;
    } else {
      $this ->FilmSponsor = new FilmSponsor();
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
      $name = "FilmSponsorPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $FilmSponsor = FilmSponsorPeer::doSelect($c);
    
    if (count($FilmSponsor) >= 1) {
      $this ->FilmSponsor = $FilmSponsor[0];
      return true;
    } else {
      $this ->FilmSponsor = new FilmSponsor();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>