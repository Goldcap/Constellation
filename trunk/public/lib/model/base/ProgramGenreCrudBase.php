<?php
       
   class ProgramGenreCrudBase extends Utils_PageWidget { 
   
    var $ProgramGenre;
   
       var $program_genre_id;
   var $fk_program_id;
   var $fk_genre_id;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getProgramGenreId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->ProgramGenre = ProgramGenrePeer::retrieveByPK( $id );
    } else {
      $this ->ProgramGenre = new ProgramGenre;
    }
  }
  
  function hydrate( $id ) {
      $this ->ProgramGenre = ProgramGenrePeer::retrieveByPK( $id );
  }
  
  function getProgramGenreId() {
    if (($this ->postVar("program_genre_id")) || ($this ->postVar("program_genre_id") === "")) {
      return $this ->postVar("program_genre_id");
    } elseif (($this ->getVar("program_genre_id")) || ($this ->getVar("program_genre_id") === "")) {
      return $this ->getVar("program_genre_id");
    } elseif (($this ->ProgramGenre) || ($this ->ProgramGenre === "")){
      return $this ->ProgramGenre -> getProgramGenreId();
    } elseif (($this ->sessionVar("program_genre_id")) || ($this ->sessionVar("program_genre_id") == "")) {
      return $this ->sessionVar("program_genre_id");
    } else {
      return false;
    }
  }
  
  function setProgramGenreId( $str ) {
    $this ->ProgramGenre -> setProgramGenreId( $str );
  }
  
  function getFkProgramId() {
    if (($this ->postVar("fk_program_id")) || ($this ->postVar("fk_program_id") === "")) {
      return $this ->postVar("fk_program_id");
    } elseif (($this ->getVar("fk_program_id")) || ($this ->getVar("fk_program_id") === "")) {
      return $this ->getVar("fk_program_id");
    } elseif (($this ->ProgramGenre) || ($this ->ProgramGenre === "")){
      return $this ->ProgramGenre -> getFkProgramId();
    } elseif (($this ->sessionVar("fk_program_id")) || ($this ->sessionVar("fk_program_id") == "")) {
      return $this ->sessionVar("fk_program_id");
    } else {
      return false;
    }
  }
  
  function setFkProgramId( $str ) {
    $this ->ProgramGenre -> setFkProgramId( $str );
  }
  
  function getFkGenreId() {
    if (($this ->postVar("fk_genre_id")) || ($this ->postVar("fk_genre_id") === "")) {
      return $this ->postVar("fk_genre_id");
    } elseif (($this ->getVar("fk_genre_id")) || ($this ->getVar("fk_genre_id") === "")) {
      return $this ->getVar("fk_genre_id");
    } elseif (($this ->ProgramGenre) || ($this ->ProgramGenre === "")){
      return $this ->ProgramGenre -> getFkGenreId();
    } elseif (($this ->sessionVar("fk_genre_id")) || ($this ->sessionVar("fk_genre_id") == "")) {
      return $this ->sessionVar("fk_genre_id");
    } else {
      return false;
    }
  }
  
  function setFkGenreId( $str ) {
    $this ->ProgramGenre -> setFkGenreId( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->ProgramGenre = ProgramGenrePeer::retrieveByPK( $id );
    }
    
    if ($this ->ProgramGenre ) {
       
    	       (is_numeric(WTVRcleanString($this ->ProgramGenre->getProgramGenreId()))) ? $itemarray["program_genre_id"] = WTVRcleanString($this ->ProgramGenre->getProgramGenreId()) : null;
          (is_numeric(WTVRcleanString($this ->ProgramGenre->getFkProgramId()))) ? $itemarray["fk_program_id"] = WTVRcleanString($this ->ProgramGenre->getFkProgramId()) : null;
          (is_numeric(WTVRcleanString($this ->ProgramGenre->getFkGenreId()))) ? $itemarray["fk_genre_id"] = WTVRcleanString($this ->ProgramGenre->getFkGenreId()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->ProgramGenre = ProgramGenrePeer::retrieveByPK( $id );
    } elseif (! $this ->ProgramGenre) {
      $this ->ProgramGenre = new ProgramGenre;
    }
        
  	 ($this -> getProgramGenreId())? $this ->ProgramGenre->setProgramGenreId( WTVRcleanString( $this -> getProgramGenreId()) ) : null;
    ($this -> getFkProgramId())? $this ->ProgramGenre->setFkProgramId( WTVRcleanString( $this -> getFkProgramId()) ) : null;
    ($this -> getFkGenreId())? $this ->ProgramGenre->setFkGenreId( WTVRcleanString( $this -> getFkGenreId()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->ProgramGenre ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->ProgramGenre = ProgramGenrePeer::retrieveByPK($id);
    }
    
    if (! $this ->ProgramGenre ) {
      return;
    }
    
    $this ->ProgramGenre -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('ProgramGenre_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "ProgramGenrePeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $ProgramGenre = ProgramGenrePeer::doSelect($c);
    
    if (count($ProgramGenre) >= 1) {
      $this ->ProgramGenre = $ProgramGenre[0];
      return true;
    } else {
      $this ->ProgramGenre = new ProgramGenre();
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
      $name = "ProgramGenrePeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $ProgramGenre = ProgramGenrePeer::doSelect($c);
    
    if (count($ProgramGenre) >= 1) {
      $this ->ProgramGenre = $ProgramGenre[0];
      return true;
    } else {
      $this ->ProgramGenre = new ProgramGenre();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>