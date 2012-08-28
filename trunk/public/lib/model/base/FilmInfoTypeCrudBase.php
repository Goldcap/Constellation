<?php
       
   class FilmInfoTypeCrudBase extends Utils_PageWidget { 
   
    var $FilmInfoType;
   
       var $film_info_type_id;
   var $film_info_type_name;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getFilmInfoTypeId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->FilmInfoType = FilmInfoTypePeer::retrieveByPK( $id );
    } else {
      $this ->FilmInfoType = new FilmInfoType;
    }
  }
  
  function hydrate( $id ) {
      $this ->FilmInfoType = FilmInfoTypePeer::retrieveByPK( $id );
  }
  
  function getFilmInfoTypeId() {
    if (($this ->postVar("film_info_type_id")) || ($this ->postVar("film_info_type_id") === "")) {
      return $this ->postVar("film_info_type_id");
    } elseif (($this ->getVar("film_info_type_id")) || ($this ->getVar("film_info_type_id") === "")) {
      return $this ->getVar("film_info_type_id");
    } elseif (($this ->FilmInfoType) || ($this ->FilmInfoType === "")){
      return $this ->FilmInfoType -> getFilmInfoTypeId();
    } elseif (($this ->sessionVar("film_info_type_id")) || ($this ->sessionVar("film_info_type_id") == "")) {
      return $this ->sessionVar("film_info_type_id");
    } else {
      return false;
    }
  }
  
  function setFilmInfoTypeId( $str ) {
    $this ->FilmInfoType -> setFilmInfoTypeId( $str );
  }
  
  function getFilmInfoTypeName() {
    if (($this ->postVar("film_info_type_name")) || ($this ->postVar("film_info_type_name") === "")) {
      return $this ->postVar("film_info_type_name");
    } elseif (($this ->getVar("film_info_type_name")) || ($this ->getVar("film_info_type_name") === "")) {
      return $this ->getVar("film_info_type_name");
    } elseif (($this ->FilmInfoType) || ($this ->FilmInfoType === "")){
      return $this ->FilmInfoType -> getFilmInfoTypeName();
    } elseif (($this ->sessionVar("film_info_type_name")) || ($this ->sessionVar("film_info_type_name") == "")) {
      return $this ->sessionVar("film_info_type_name");
    } else {
      return false;
    }
  }
  
  function setFilmInfoTypeName( $str ) {
    $this ->FilmInfoType -> setFilmInfoTypeName( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->FilmInfoType = FilmInfoTypePeer::retrieveByPK( $id );
    }
    
    if ($this ->FilmInfoType ) {
       
    	       (is_numeric(WTVRcleanString($this ->FilmInfoType->getFilmInfoTypeId()))) ? $itemarray["film_info_type_id"] = WTVRcleanString($this ->FilmInfoType->getFilmInfoTypeId()) : null;
          (WTVRcleanString($this ->FilmInfoType->getFilmInfoTypeName())) ? $itemarray["film_info_type_name"] = WTVRcleanString($this ->FilmInfoType->getFilmInfoTypeName()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->FilmInfoType = FilmInfoTypePeer::retrieveByPK( $id );
    } elseif (! $this ->FilmInfoType) {
      $this ->FilmInfoType = new FilmInfoType;
    }
        
  	 ($this -> getFilmInfoTypeId())? $this ->FilmInfoType->setFilmInfoTypeId( WTVRcleanString( $this -> getFilmInfoTypeId()) ) : null;
    ($this -> getFilmInfoTypeName())? $this ->FilmInfoType->setFilmInfoTypeName( WTVRcleanString( $this -> getFilmInfoTypeName()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->FilmInfoType ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->FilmInfoType = FilmInfoTypePeer::retrieveByPK($id);
    }
    
    if (! $this ->FilmInfoType ) {
      return;
    }
    
    $this ->FilmInfoType -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('FilmInfoType_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "FilmInfoTypePeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $FilmInfoType = FilmInfoTypePeer::doSelect($c);
    
    if (count($FilmInfoType) >= 1) {
      $this ->FilmInfoType = $FilmInfoType[0];
      return true;
    } else {
      $this ->FilmInfoType = new FilmInfoType();
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
      $name = "FilmInfoTypePeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $FilmInfoType = FilmInfoTypePeer::doSelect($c);
    
    if (count($FilmInfoType) >= 1) {
      $this ->FilmInfoType = $FilmInfoType[0];
      return true;
    } else {
      $this ->FilmInfoType = new FilmInfoType();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>