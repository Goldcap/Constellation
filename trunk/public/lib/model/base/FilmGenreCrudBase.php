<?php
       
   class FilmGenreCrudBase extends Utils_PageWidget { 
   
    var $FilmGenre;
   
       var $film_genre_id;
   var $fk_film_id;
   var $fk_genre_id;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getFilmGenreId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->FilmGenre = FilmGenrePeer::retrieveByPK( $id );
    } else {
      $this ->FilmGenre = new FilmGenre;
    }
  }
  
  function hydrate( $id ) {
      $this ->FilmGenre = FilmGenrePeer::retrieveByPK( $id );
  }
  
  function getFilmGenreId() {
    if (($this ->postVar("film_genre_id")) || ($this ->postVar("film_genre_id") === "")) {
      return $this ->postVar("film_genre_id");
    } elseif (($this ->getVar("film_genre_id")) || ($this ->getVar("film_genre_id") === "")) {
      return $this ->getVar("film_genre_id");
    } elseif (($this ->FilmGenre) || ($this ->FilmGenre === "")){
      return $this ->FilmGenre -> getFilmGenreId();
    } elseif (($this ->sessionVar("film_genre_id")) || ($this ->sessionVar("film_genre_id") == "")) {
      return $this ->sessionVar("film_genre_id");
    } else {
      return false;
    }
  }
  
  function setFilmGenreId( $str ) {
    $this ->FilmGenre -> setFilmGenreId( $str );
  }
  
  function getFkFilmId() {
    if (($this ->postVar("fk_film_id")) || ($this ->postVar("fk_film_id") === "")) {
      return $this ->postVar("fk_film_id");
    } elseif (($this ->getVar("fk_film_id")) || ($this ->getVar("fk_film_id") === "")) {
      return $this ->getVar("fk_film_id");
    } elseif (($this ->FilmGenre) || ($this ->FilmGenre === "")){
      return $this ->FilmGenre -> getFkFilmId();
    } elseif (($this ->sessionVar("fk_film_id")) || ($this ->sessionVar("fk_film_id") == "")) {
      return $this ->sessionVar("fk_film_id");
    } else {
      return false;
    }
  }
  
  function setFkFilmId( $str ) {
    $this ->FilmGenre -> setFkFilmId( $str );
  }
  
  function getFkGenreId() {
    if (($this ->postVar("fk_genre_id")) || ($this ->postVar("fk_genre_id") === "")) {
      return $this ->postVar("fk_genre_id");
    } elseif (($this ->getVar("fk_genre_id")) || ($this ->getVar("fk_genre_id") === "")) {
      return $this ->getVar("fk_genre_id");
    } elseif (($this ->FilmGenre) || ($this ->FilmGenre === "")){
      return $this ->FilmGenre -> getFkGenreId();
    } elseif (($this ->sessionVar("fk_genre_id")) || ($this ->sessionVar("fk_genre_id") == "")) {
      return $this ->sessionVar("fk_genre_id");
    } else {
      return false;
    }
  }
  
  function setFkGenreId( $str ) {
    $this ->FilmGenre -> setFkGenreId( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->FilmGenre = FilmGenrePeer::retrieveByPK( $id );
    }
    
    if ($this ->FilmGenre ) {
       
    	       (is_numeric(WTVRcleanString($this ->FilmGenre->getFilmGenreId()))) ? $itemarray["film_genre_id"] = WTVRcleanString($this ->FilmGenre->getFilmGenreId()) : null;
          (is_numeric(WTVRcleanString($this ->FilmGenre->getFkFilmId()))) ? $itemarray["fk_film_id"] = WTVRcleanString($this ->FilmGenre->getFkFilmId()) : null;
          (is_numeric(WTVRcleanString($this ->FilmGenre->getFkGenreId()))) ? $itemarray["fk_genre_id"] = WTVRcleanString($this ->FilmGenre->getFkGenreId()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->FilmGenre = FilmGenrePeer::retrieveByPK( $id );
    } elseif (! $this ->FilmGenre) {
      $this ->FilmGenre = new FilmGenre;
    }
        
  	 ($this -> getFilmGenreId())? $this ->FilmGenre->setFilmGenreId( WTVRcleanString( $this -> getFilmGenreId()) ) : null;
    ($this -> getFkFilmId())? $this ->FilmGenre->setFkFilmId( WTVRcleanString( $this -> getFkFilmId()) ) : null;
    ($this -> getFkGenreId())? $this ->FilmGenre->setFkGenreId( WTVRcleanString( $this -> getFkGenreId()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->FilmGenre ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->FilmGenre = FilmGenrePeer::retrieveByPK($id);
    }
    
    if (! $this ->FilmGenre ) {
      return;
    }
    
    $this ->FilmGenre -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('FilmGenre_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "FilmGenrePeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $FilmGenre = FilmGenrePeer::doSelect($c);
    
    if (count($FilmGenre) >= 1) {
      $this ->FilmGenre = $FilmGenre[0];
      return true;
    } else {
      $this ->FilmGenre = new FilmGenre();
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
      $name = "FilmGenrePeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $FilmGenre = FilmGenrePeer::doSelect($c);
    
    if (count($FilmGenre) >= 1) {
      $this ->FilmGenre = $FilmGenre[0];
      return true;
    } else {
      $this ->FilmGenre = new FilmGenre();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>