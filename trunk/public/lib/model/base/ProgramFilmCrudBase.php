<?php
       
   class ProgramFilmCrudBase extends Utils_PageWidget { 
   
    var $ProgramFilm;
   
       var $program_film_id;
   var $fk_film_id;
   var $fk_film_child_id;
   var $program_film_level;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getProgramFilmId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->ProgramFilm = ProgramFilmPeer::retrieveByPK( $id );
    } else {
      $this ->ProgramFilm = new ProgramFilm;
    }
  }
  
  function hydrate( $id ) {
      $this ->ProgramFilm = ProgramFilmPeer::retrieveByPK( $id );
  }
  
  function getProgramFilmId() {
    if (($this ->postVar("program_film_id")) || ($this ->postVar("program_film_id") === "")) {
      return $this ->postVar("program_film_id");
    } elseif (($this ->getVar("program_film_id")) || ($this ->getVar("program_film_id") === "")) {
      return $this ->getVar("program_film_id");
    } elseif (($this ->ProgramFilm) || ($this ->ProgramFilm === "")){
      return $this ->ProgramFilm -> getProgramFilmId();
    } elseif (($this ->sessionVar("program_film_id")) || ($this ->sessionVar("program_film_id") == "")) {
      return $this ->sessionVar("program_film_id");
    } else {
      return false;
    }
  }
  
  function setProgramFilmId( $str ) {
    $this ->ProgramFilm -> setProgramFilmId( $str );
  }
  
  function getFkFilmId() {
    if (($this ->postVar("fk_film_id")) || ($this ->postVar("fk_film_id") === "")) {
      return $this ->postVar("fk_film_id");
    } elseif (($this ->getVar("fk_film_id")) || ($this ->getVar("fk_film_id") === "")) {
      return $this ->getVar("fk_film_id");
    } elseif (($this ->ProgramFilm) || ($this ->ProgramFilm === "")){
      return $this ->ProgramFilm -> getFkFilmId();
    } elseif (($this ->sessionVar("fk_film_id")) || ($this ->sessionVar("fk_film_id") == "")) {
      return $this ->sessionVar("fk_film_id");
    } else {
      return false;
    }
  }
  
  function setFkFilmId( $str ) {
    $this ->ProgramFilm -> setFkFilmId( $str );
  }
  
  function getFkFilmChildId() {
    if (($this ->postVar("fk_film_child_id")) || ($this ->postVar("fk_film_child_id") === "")) {
      return $this ->postVar("fk_film_child_id");
    } elseif (($this ->getVar("fk_film_child_id")) || ($this ->getVar("fk_film_child_id") === "")) {
      return $this ->getVar("fk_film_child_id");
    } elseif (($this ->ProgramFilm) || ($this ->ProgramFilm === "")){
      return $this ->ProgramFilm -> getFkFilmChildId();
    } elseif (($this ->sessionVar("fk_film_child_id")) || ($this ->sessionVar("fk_film_child_id") == "")) {
      return $this ->sessionVar("fk_film_child_id");
    } else {
      return false;
    }
  }
  
  function setFkFilmChildId( $str ) {
    $this ->ProgramFilm -> setFkFilmChildId( $str );
  }
  
  function getProgramFilmLevel() {
    if (($this ->postVar("program_film_level")) || ($this ->postVar("program_film_level") === "")) {
      return $this ->postVar("program_film_level");
    } elseif (($this ->getVar("program_film_level")) || ($this ->getVar("program_film_level") === "")) {
      return $this ->getVar("program_film_level");
    } elseif (($this ->ProgramFilm) || ($this ->ProgramFilm === "")){
      return $this ->ProgramFilm -> getProgramFilmLevel();
    } elseif (($this ->sessionVar("program_film_level")) || ($this ->sessionVar("program_film_level") == "")) {
      return $this ->sessionVar("program_film_level");
    } else {
      return false;
    }
  }
  
  function setProgramFilmLevel( $str ) {
    $this ->ProgramFilm -> setProgramFilmLevel( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->ProgramFilm = ProgramFilmPeer::retrieveByPK( $id );
    }
    
    if ($this ->ProgramFilm ) {
       
    	       (is_numeric(WTVRcleanString($this ->ProgramFilm->getProgramFilmId()))) ? $itemarray["program_film_id"] = WTVRcleanString($this ->ProgramFilm->getProgramFilmId()) : null;
          (is_numeric(WTVRcleanString($this ->ProgramFilm->getFkFilmId()))) ? $itemarray["fk_film_id"] = WTVRcleanString($this ->ProgramFilm->getFkFilmId()) : null;
          (is_numeric(WTVRcleanString($this ->ProgramFilm->getFkFilmChildId()))) ? $itemarray["fk_film_child_id"] = WTVRcleanString($this ->ProgramFilm->getFkFilmChildId()) : null;
          (is_numeric(WTVRcleanString($this ->ProgramFilm->getProgramFilmLevel()))) ? $itemarray["program_film_level"] = WTVRcleanString($this ->ProgramFilm->getProgramFilmLevel()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->ProgramFilm = ProgramFilmPeer::retrieveByPK( $id );
    } elseif (! $this ->ProgramFilm) {
      $this ->ProgramFilm = new ProgramFilm;
    }
        
  	 ($this -> getProgramFilmId())? $this ->ProgramFilm->setProgramFilmId( WTVRcleanString( $this -> getProgramFilmId()) ) : null;
    ($this -> getFkFilmId())? $this ->ProgramFilm->setFkFilmId( WTVRcleanString( $this -> getFkFilmId()) ) : null;
    ($this -> getFkFilmChildId())? $this ->ProgramFilm->setFkFilmChildId( WTVRcleanString( $this -> getFkFilmChildId()) ) : null;
    ($this -> getProgramFilmLevel())? $this ->ProgramFilm->setProgramFilmLevel( WTVRcleanString( $this -> getProgramFilmLevel()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->ProgramFilm ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->ProgramFilm = ProgramFilmPeer::retrieveByPK($id);
    }
    
    if (! $this ->ProgramFilm ) {
      return;
    }
    
    $this ->ProgramFilm -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('ProgramFilm_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "ProgramFilmPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $ProgramFilm = ProgramFilmPeer::doSelect($c);
    
    if (count($ProgramFilm) >= 1) {
      $this ->ProgramFilm = $ProgramFilm[0];
      return true;
    } else {
      $this ->ProgramFilm = new ProgramFilm();
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
      $name = "ProgramFilmPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $ProgramFilm = ProgramFilmPeer::doSelect($c);
    
    if (count($ProgramFilm) >= 1) {
      $this ->ProgramFilm = $ProgramFilm[0];
      return true;
    } else {
      $this ->ProgramFilm = new ProgramFilm();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>