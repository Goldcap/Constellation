<?php
       
   class FilmFilmCrudBase extends Utils_PageWidget { 
   
    var $FilmFilm;
   
       var $film_film_id;
   var $fk_film_id;
   var $fk_film_child_id;
   var $film_film_level;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getFilmFilmId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->FilmFilm = FilmFilmPeer::retrieveByPK( $id );
    } else {
      $this ->FilmFilm = new FilmFilm;
    }
  }
  
  function hydrate( $id ) {
      $this ->FilmFilm = FilmFilmPeer::retrieveByPK( $id );
  }
  
  function getFilmFilmId() {
    if (($this ->postVar("film_film_id")) || ($this ->postVar("film_film_id") === "")) {
      return $this ->postVar("film_film_id");
    } elseif (($this ->getVar("film_film_id")) || ($this ->getVar("film_film_id") === "")) {
      return $this ->getVar("film_film_id");
    } elseif (($this ->FilmFilm) || ($this ->FilmFilm === "")){
      return $this ->FilmFilm -> getFilmFilmId();
    } elseif (($this ->sessionVar("film_film_id")) || ($this ->sessionVar("film_film_id") == "")) {
      return $this ->sessionVar("film_film_id");
    } else {
      return false;
    }
  }
  
  function setFilmFilmId( $str ) {
    $this ->FilmFilm -> setFilmFilmId( $str );
  }
  
  function getFkFilmId() {
    if (($this ->postVar("fk_film_id")) || ($this ->postVar("fk_film_id") === "")) {
      return $this ->postVar("fk_film_id");
    } elseif (($this ->getVar("fk_film_id")) || ($this ->getVar("fk_film_id") === "")) {
      return $this ->getVar("fk_film_id");
    } elseif (($this ->FilmFilm) || ($this ->FilmFilm === "")){
      return $this ->FilmFilm -> getFkFilmId();
    } elseif (($this ->sessionVar("fk_film_id")) || ($this ->sessionVar("fk_film_id") == "")) {
      return $this ->sessionVar("fk_film_id");
    } else {
      return false;
    }
  }
  
  function setFkFilmId( $str ) {
    $this ->FilmFilm -> setFkFilmId( $str );
  }
  
  function getFkFilmChildId() {
    if (($this ->postVar("fk_film_child_id")) || ($this ->postVar("fk_film_child_id") === "")) {
      return $this ->postVar("fk_film_child_id");
    } elseif (($this ->getVar("fk_film_child_id")) || ($this ->getVar("fk_film_child_id") === "")) {
      return $this ->getVar("fk_film_child_id");
    } elseif (($this ->FilmFilm) || ($this ->FilmFilm === "")){
      return $this ->FilmFilm -> getFkFilmChildId();
    } elseif (($this ->sessionVar("fk_film_child_id")) || ($this ->sessionVar("fk_film_child_id") == "")) {
      return $this ->sessionVar("fk_film_child_id");
    } else {
      return false;
    }
  }
  
  function setFkFilmChildId( $str ) {
    $this ->FilmFilm -> setFkFilmChildId( $str );
  }
  
  function getFilmFilmLevel() {
    if (($this ->postVar("film_film_level")) || ($this ->postVar("film_film_level") === "")) {
      return $this ->postVar("film_film_level");
    } elseif (($this ->getVar("film_film_level")) || ($this ->getVar("film_film_level") === "")) {
      return $this ->getVar("film_film_level");
    } elseif (($this ->FilmFilm) || ($this ->FilmFilm === "")){
      return $this ->FilmFilm -> getFilmFilmLevel();
    } elseif (($this ->sessionVar("film_film_level")) || ($this ->sessionVar("film_film_level") == "")) {
      return $this ->sessionVar("film_film_level");
    } else {
      return false;
    }
  }
  
  function setFilmFilmLevel( $str ) {
    $this ->FilmFilm -> setFilmFilmLevel( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->FilmFilm = FilmFilmPeer::retrieveByPK( $id );
    }
    
    if ($this ->FilmFilm ) {
       
    	       (is_numeric(WTVRcleanString($this ->FilmFilm->getFilmFilmId()))) ? $itemarray["film_film_id"] = WTVRcleanString($this ->FilmFilm->getFilmFilmId()) : null;
          (is_numeric(WTVRcleanString($this ->FilmFilm->getFkFilmId()))) ? $itemarray["fk_film_id"] = WTVRcleanString($this ->FilmFilm->getFkFilmId()) : null;
          (is_numeric(WTVRcleanString($this ->FilmFilm->getFkFilmChildId()))) ? $itemarray["fk_film_child_id"] = WTVRcleanString($this ->FilmFilm->getFkFilmChildId()) : null;
          (is_numeric(WTVRcleanString($this ->FilmFilm->getFilmFilmLevel()))) ? $itemarray["film_film_level"] = WTVRcleanString($this ->FilmFilm->getFilmFilmLevel()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->FilmFilm = FilmFilmPeer::retrieveByPK( $id );
    } elseif (! $this ->FilmFilm) {
      $this ->FilmFilm = new FilmFilm;
    }
        
  	 ($this -> getFilmFilmId())? $this ->FilmFilm->setFilmFilmId( WTVRcleanString( $this -> getFilmFilmId()) ) : null;
    ($this -> getFkFilmId())? $this ->FilmFilm->setFkFilmId( WTVRcleanString( $this -> getFkFilmId()) ) : null;
    ($this -> getFkFilmChildId())? $this ->FilmFilm->setFkFilmChildId( WTVRcleanString( $this -> getFkFilmChildId()) ) : null;
    ($this -> getFilmFilmLevel())? $this ->FilmFilm->setFilmFilmLevel( WTVRcleanString( $this -> getFilmFilmLevel()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->FilmFilm ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->FilmFilm = FilmFilmPeer::retrieveByPK($id);
    }
    
    if (! $this ->FilmFilm ) {
      return;
    }
    
    $this ->FilmFilm -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('FilmFilm_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "FilmFilmPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $FilmFilm = FilmFilmPeer::doSelect($c);
    
    if (count($FilmFilm) >= 1) {
      $this ->FilmFilm = $FilmFilm[0];
      return true;
    } else {
      $this ->FilmFilm = new FilmFilm();
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
      $name = "FilmFilmPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $FilmFilm = FilmFilmPeer::doSelect($c);
    
    if (count($FilmFilm) >= 1) {
      $this ->FilmFilm = $FilmFilm[0];
      return true;
    } else {
      $this ->FilmFilm = new FilmFilm();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>