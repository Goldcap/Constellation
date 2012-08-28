<?php
       
   class FilmGeoBlockingCrudBase extends Utils_PageWidget { 
   
    var $FilmGeoBlocking;
   
       var $film_geoblocking_id;
   var $fk_film_id;
   var $film_geoblocking_region;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getFilmGeoblockingId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->FilmGeoBlocking = FilmGeoBlockingPeer::retrieveByPK( $id );
    } else {
      $this ->FilmGeoBlocking = new FilmGeoBlocking;
    }
  }
  
  function hydrate( $id ) {
      $this ->FilmGeoBlocking = FilmGeoBlockingPeer::retrieveByPK( $id );
  }
  
  function getFilmGeoblockingId() {
    if (($this ->postVar("film_geoblocking_id")) || ($this ->postVar("film_geoblocking_id") === "")) {
      return $this ->postVar("film_geoblocking_id");
    } elseif (($this ->getVar("film_geoblocking_id")) || ($this ->getVar("film_geoblocking_id") === "")) {
      return $this ->getVar("film_geoblocking_id");
    } elseif (($this ->FilmGeoBlocking) || ($this ->FilmGeoBlocking === "")){
      return $this ->FilmGeoBlocking -> getFilmGeoblockingId();
    } elseif (($this ->sessionVar("film_geoblocking_id")) || ($this ->sessionVar("film_geoblocking_id") == "")) {
      return $this ->sessionVar("film_geoblocking_id");
    } else {
      return false;
    }
  }
  
  function setFilmGeoblockingId( $str ) {
    $this ->FilmGeoBlocking -> setFilmGeoblockingId( $str );
  }
  
  function getFkFilmId() {
    if (($this ->postVar("fk_film_id")) || ($this ->postVar("fk_film_id") === "")) {
      return $this ->postVar("fk_film_id");
    } elseif (($this ->getVar("fk_film_id")) || ($this ->getVar("fk_film_id") === "")) {
      return $this ->getVar("fk_film_id");
    } elseif (($this ->FilmGeoBlocking) || ($this ->FilmGeoBlocking === "")){
      return $this ->FilmGeoBlocking -> getFkFilmId();
    } elseif (($this ->sessionVar("fk_film_id")) || ($this ->sessionVar("fk_film_id") == "")) {
      return $this ->sessionVar("fk_film_id");
    } else {
      return false;
    }
  }
  
  function setFkFilmId( $str ) {
    $this ->FilmGeoBlocking -> setFkFilmId( $str );
  }
  
  function getFilmGeoblockingRegion() {
    if (($this ->postVar("film_geoblocking_region")) || ($this ->postVar("film_geoblocking_region") === "")) {
      return $this ->postVar("film_geoblocking_region");
    } elseif (($this ->getVar("film_geoblocking_region")) || ($this ->getVar("film_geoblocking_region") === "")) {
      return $this ->getVar("film_geoblocking_region");
    } elseif (($this ->FilmGeoBlocking) || ($this ->FilmGeoBlocking === "")){
      return $this ->FilmGeoBlocking -> getFilmGeoblockingRegion();
    } elseif (($this ->sessionVar("film_geoblocking_region")) || ($this ->sessionVar("film_geoblocking_region") == "")) {
      return $this ->sessionVar("film_geoblocking_region");
    } else {
      return false;
    }
  }
  
  function setFilmGeoblockingRegion( $str ) {
    $this ->FilmGeoBlocking -> setFilmGeoblockingRegion( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->FilmGeoBlocking = FilmGeoBlockingPeer::retrieveByPK( $id );
    }
    
    if ($this ->FilmGeoBlocking ) {
       
    	       (is_numeric(WTVRcleanString($this ->FilmGeoBlocking->getFilmGeoblockingId()))) ? $itemarray["film_geoblocking_id"] = WTVRcleanString($this ->FilmGeoBlocking->getFilmGeoblockingId()) : null;
          (is_numeric(WTVRcleanString($this ->FilmGeoBlocking->getFkFilmId()))) ? $itemarray["fk_film_id"] = WTVRcleanString($this ->FilmGeoBlocking->getFkFilmId()) : null;
          (WTVRcleanString($this ->FilmGeoBlocking->getFilmGeoblockingRegion())) ? $itemarray["film_geoblocking_region"] = WTVRcleanString($this ->FilmGeoBlocking->getFilmGeoblockingRegion()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->FilmGeoBlocking = FilmGeoBlockingPeer::retrieveByPK( $id );
    } elseif (! $this ->FilmGeoBlocking) {
      $this ->FilmGeoBlocking = new FilmGeoBlocking;
    }
        
  	 ($this -> getFilmGeoblockingId())? $this ->FilmGeoBlocking->setFilmGeoblockingId( WTVRcleanString( $this -> getFilmGeoblockingId()) ) : null;
    ($this -> getFkFilmId())? $this ->FilmGeoBlocking->setFkFilmId( WTVRcleanString( $this -> getFkFilmId()) ) : null;
    ($this -> getFilmGeoblockingRegion())? $this ->FilmGeoBlocking->setFilmGeoblockingRegion( WTVRcleanString( $this -> getFilmGeoblockingRegion()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->FilmGeoBlocking ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->FilmGeoBlocking = FilmGeoBlockingPeer::retrieveByPK($id);
    }
    
    if (! $this ->FilmGeoBlocking ) {
      return;
    }
    
    $this ->FilmGeoBlocking -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('FilmGeoBlocking_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "FilmGeoBlockingPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $FilmGeoBlocking = FilmGeoBlockingPeer::doSelect($c);
    
    if (count($FilmGeoBlocking) >= 1) {
      $this ->FilmGeoBlocking = $FilmGeoBlocking[0];
      return true;
    } else {
      $this ->FilmGeoBlocking = new FilmGeoBlocking();
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
      $name = "FilmGeoBlockingPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $FilmGeoBlocking = FilmGeoBlockingPeer::doSelect($c);
    
    if (count($FilmGeoBlocking) >= 1) {
      $this ->FilmGeoBlocking = $FilmGeoBlocking[0];
      return true;
    } else {
      $this ->FilmGeoBlocking = new FilmGeoBlocking();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>