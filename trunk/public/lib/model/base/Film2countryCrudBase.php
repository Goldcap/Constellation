<?php
       
   class Film2countryCrudBase extends Utils_PageWidget { 
   
    var $Film2country;
   
       var $film2country_id;
   var $fk_film_id;
   var $film2country_country_iso;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getFilm2countryId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Film2country = Film2countryPeer::retrieveByPK( $id );
    } else {
      $this ->Film2country = new Film2country;
    }
  }
  
  function hydrate( $id ) {
      $this ->Film2country = Film2countryPeer::retrieveByPK( $id );
  }
  
  function getFilm2countryId() {
    if (($this ->postVar("film2country_id")) || ($this ->postVar("film2country_id") === "")) {
      return $this ->postVar("film2country_id");
    } elseif (($this ->getVar("film2country_id")) || ($this ->getVar("film2country_id") === "")) {
      return $this ->getVar("film2country_id");
    } elseif (($this ->Film2country) || ($this ->Film2country === "")){
      return $this ->Film2country -> getFilm2countryId();
    } elseif (($this ->sessionVar("film2country_id")) || ($this ->sessionVar("film2country_id") == "")) {
      return $this ->sessionVar("film2country_id");
    } else {
      return false;
    }
  }
  
  function setFilm2countryId( $str ) {
    $this ->Film2country -> setFilm2countryId( $str );
  }
  
  function getFkFilmId() {
    if (($this ->postVar("fk_film_id")) || ($this ->postVar("fk_film_id") === "")) {
      return $this ->postVar("fk_film_id");
    } elseif (($this ->getVar("fk_film_id")) || ($this ->getVar("fk_film_id") === "")) {
      return $this ->getVar("fk_film_id");
    } elseif (($this ->Film2country) || ($this ->Film2country === "")){
      return $this ->Film2country -> getFkFilmId();
    } elseif (($this ->sessionVar("fk_film_id")) || ($this ->sessionVar("fk_film_id") == "")) {
      return $this ->sessionVar("fk_film_id");
    } else {
      return false;
    }
  }
  
  function setFkFilmId( $str ) {
    $this ->Film2country -> setFkFilmId( $str );
  }
  
  function getFilm2countryCountryIso() {
    if (($this ->postVar("film2country_country_iso")) || ($this ->postVar("film2country_country_iso") === "")) {
      return $this ->postVar("film2country_country_iso");
    } elseif (($this ->getVar("film2country_country_iso")) || ($this ->getVar("film2country_country_iso") === "")) {
      return $this ->getVar("film2country_country_iso");
    } elseif (($this ->Film2country) || ($this ->Film2country === "")){
      return $this ->Film2country -> getFilm2countryCountryIso();
    } elseif (($this ->sessionVar("film2country_country_iso")) || ($this ->sessionVar("film2country_country_iso") == "")) {
      return $this ->sessionVar("film2country_country_iso");
    } else {
      return false;
    }
  }
  
  function setFilm2countryCountryIso( $str ) {
    $this ->Film2country -> setFilm2countryCountryIso( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Film2country = Film2countryPeer::retrieveByPK( $id );
    }
    
    if ($this ->Film2country ) {
       
    	       (is_numeric(WTVRcleanString($this ->Film2country->getFilm2countryId()))) ? $itemarray["film2country_id"] = WTVRcleanString($this ->Film2country->getFilm2countryId()) : null;
          (is_numeric(WTVRcleanString($this ->Film2country->getFkFilmId()))) ? $itemarray["fk_film_id"] = WTVRcleanString($this ->Film2country->getFkFilmId()) : null;
          (WTVRcleanString($this ->Film2country->getFilm2countryCountryIso())) ? $itemarray["film2country_country_iso"] = WTVRcleanString($this ->Film2country->getFilm2countryCountryIso()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Film2country = Film2countryPeer::retrieveByPK( $id );
    } elseif (! $this ->Film2country) {
      $this ->Film2country = new Film2country;
    }
        
  	 ($this -> getFilm2countryId())? $this ->Film2country->setFilm2countryId( WTVRcleanString( $this -> getFilm2countryId()) ) : null;
    ($this -> getFkFilmId())? $this ->Film2country->setFkFilmId( WTVRcleanString( $this -> getFkFilmId()) ) : null;
    ($this -> getFilm2countryCountryIso())? $this ->Film2country->setFilm2countryCountryIso( WTVRcleanString( $this -> getFilm2countryCountryIso()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Film2country ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Film2country = Film2countryPeer::retrieveByPK($id);
    }
    
    if (! $this ->Film2country ) {
      return;
    }
    
    $this ->Film2country -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Film2country_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "Film2countryPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Film2country = Film2countryPeer::doSelect($c);
    
    if (count($Film2country) >= 1) {
      $this ->Film2country = $Film2country[0];
      return true;
    } else {
      $this ->Film2country = new Film2country();
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
      $name = "Film2countryPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Film2country = Film2countryPeer::doSelect($c);
    
    if (count($Film2country) >= 1) {
      $this ->Film2country = $Film2country[0];
      return true;
    } else {
      $this ->Film2country = new Film2country();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>