<?php
       
   class GenreCrudBase extends Utils_PageWidget { 
   
    var $Genre;
   
       var $genre_id;
   var $genre_name;
   var $genre_description;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getGenreId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Genre = GenrePeer::retrieveByPK( $id );
    } else {
      $this ->Genre = new Genre;
    }
  }
  
  function hydrate( $id ) {
      $this ->Genre = GenrePeer::retrieveByPK( $id );
  }
  
  function getGenreId() {
    if (($this ->postVar("genre_id")) || ($this ->postVar("genre_id") === "")) {
      return $this ->postVar("genre_id");
    } elseif (($this ->getVar("genre_id")) || ($this ->getVar("genre_id") === "")) {
      return $this ->getVar("genre_id");
    } elseif (($this ->Genre) || ($this ->Genre === "")){
      return $this ->Genre -> getGenreId();
    } elseif (($this ->sessionVar("genre_id")) || ($this ->sessionVar("genre_id") == "")) {
      return $this ->sessionVar("genre_id");
    } else {
      return false;
    }
  }
  
  function setGenreId( $str ) {
    $this ->Genre -> setGenreId( $str );
  }
  
  function getGenreName() {
    if (($this ->postVar("genre_name")) || ($this ->postVar("genre_name") === "")) {
      return $this ->postVar("genre_name");
    } elseif (($this ->getVar("genre_name")) || ($this ->getVar("genre_name") === "")) {
      return $this ->getVar("genre_name");
    } elseif (($this ->Genre) || ($this ->Genre === "")){
      return $this ->Genre -> getGenreName();
    } elseif (($this ->sessionVar("genre_name")) || ($this ->sessionVar("genre_name") == "")) {
      return $this ->sessionVar("genre_name");
    } else {
      return false;
    }
  }
  
  function setGenreName( $str ) {
    $this ->Genre -> setGenreName( $str );
  }
  
  function getGenreDescription() {
    if (($this ->postVar("genre_description")) || ($this ->postVar("genre_description") === "")) {
      return $this ->postVar("genre_description");
    } elseif (($this ->getVar("genre_description")) || ($this ->getVar("genre_description") === "")) {
      return $this ->getVar("genre_description");
    } elseif (($this ->Genre) || ($this ->Genre === "")){
      return $this ->Genre -> getGenreDescription();
    } elseif (($this ->sessionVar("genre_description")) || ($this ->sessionVar("genre_description") == "")) {
      return $this ->sessionVar("genre_description");
    } else {
      return false;
    }
  }
  
  function setGenreDescription( $str ) {
    $this ->Genre -> setGenreDescription( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Genre = GenrePeer::retrieveByPK( $id );
    }
    
    if ($this ->Genre ) {
       
    	       (is_numeric(WTVRcleanString($this ->Genre->getGenreId()))) ? $itemarray["genre_id"] = WTVRcleanString($this ->Genre->getGenreId()) : null;
          (WTVRcleanString($this ->Genre->getGenreName())) ? $itemarray["genre_name"] = WTVRcleanString($this ->Genre->getGenreName()) : null;
          (WTVRcleanString($this ->Genre->getGenreDescription())) ? $itemarray["genre_description"] = WTVRcleanString($this ->Genre->getGenreDescription()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Genre = GenrePeer::retrieveByPK( $id );
    } elseif (! $this ->Genre) {
      $this ->Genre = new Genre;
    }
        
  	 ($this -> getGenreId())? $this ->Genre->setGenreId( WTVRcleanString( $this -> getGenreId()) ) : null;
    ($this -> getGenreName())? $this ->Genre->setGenreName( WTVRcleanString( $this -> getGenreName()) ) : null;
    ($this -> getGenreDescription())? $this ->Genre->setGenreDescription( WTVRcleanString( $this -> getGenreDescription()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Genre ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Genre = GenrePeer::retrieveByPK($id);
    }
    
    if (! $this ->Genre ) {
      return;
    }
    
    $this ->Genre -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Genre_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "GenrePeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Genre = GenrePeer::doSelect($c);
    
    if (count($Genre) >= 1) {
      $this ->Genre = $Genre[0];
      return true;
    } else {
      $this ->Genre = new Genre();
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
      $name = "GenrePeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Genre = GenrePeer::doSelect($c);
    
    if (count($Genre) >= 1) {
      $this ->Genre = $Genre[0];
      return true;
    } else {
      $this ->Genre = new Genre();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>