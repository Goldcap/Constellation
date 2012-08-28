<?php
       
   class FilmPromotionCrudBase extends Utils_PageWidget { 
   
    var $FilmPromotion;
   
       var $film_promotion_id;
   var $fk_film_id;
   var $fk_promotion_id;
   var $film_promotion_priority;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getFilmPromotionId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->FilmPromotion = FilmPromotionPeer::retrieveByPK( $id );
    } else {
      $this ->FilmPromotion = new FilmPromotion;
    }
  }
  
  function hydrate( $id ) {
      $this ->FilmPromotion = FilmPromotionPeer::retrieveByPK( $id );
  }
  
  function getFilmPromotionId() {
    if (($this ->postVar("film_promotion_id")) || ($this ->postVar("film_promotion_id") === "")) {
      return $this ->postVar("film_promotion_id");
    } elseif (($this ->getVar("film_promotion_id")) || ($this ->getVar("film_promotion_id") === "")) {
      return $this ->getVar("film_promotion_id");
    } elseif (($this ->FilmPromotion) || ($this ->FilmPromotion === "")){
      return $this ->FilmPromotion -> getFilmPromotionId();
    } elseif (($this ->sessionVar("film_promotion_id")) || ($this ->sessionVar("film_promotion_id") == "")) {
      return $this ->sessionVar("film_promotion_id");
    } else {
      return false;
    }
  }
  
  function setFilmPromotionId( $str ) {
    $this ->FilmPromotion -> setFilmPromotionId( $str );
  }
  
  function getFkFilmId() {
    if (($this ->postVar("fk_film_id")) || ($this ->postVar("fk_film_id") === "")) {
      return $this ->postVar("fk_film_id");
    } elseif (($this ->getVar("fk_film_id")) || ($this ->getVar("fk_film_id") === "")) {
      return $this ->getVar("fk_film_id");
    } elseif (($this ->FilmPromotion) || ($this ->FilmPromotion === "")){
      return $this ->FilmPromotion -> getFkFilmId();
    } elseif (($this ->sessionVar("fk_film_id")) || ($this ->sessionVar("fk_film_id") == "")) {
      return $this ->sessionVar("fk_film_id");
    } else {
      return false;
    }
  }
  
  function setFkFilmId( $str ) {
    $this ->FilmPromotion -> setFkFilmId( $str );
  }
  
  function getFkPromotionId() {
    if (($this ->postVar("fk_promotion_id")) || ($this ->postVar("fk_promotion_id") === "")) {
      return $this ->postVar("fk_promotion_id");
    } elseif (($this ->getVar("fk_promotion_id")) || ($this ->getVar("fk_promotion_id") === "")) {
      return $this ->getVar("fk_promotion_id");
    } elseif (($this ->FilmPromotion) || ($this ->FilmPromotion === "")){
      return $this ->FilmPromotion -> getFkPromotionId();
    } elseif (($this ->sessionVar("fk_promotion_id")) || ($this ->sessionVar("fk_promotion_id") == "")) {
      return $this ->sessionVar("fk_promotion_id");
    } else {
      return false;
    }
  }
  
  function setFkPromotionId( $str ) {
    $this ->FilmPromotion -> setFkPromotionId( $str );
  }
  
  function getFilmPromotionPriority() {
    if (($this ->postVar("film_promotion_priority")) || ($this ->postVar("film_promotion_priority") === "")) {
      return $this ->postVar("film_promotion_priority");
    } elseif (($this ->getVar("film_promotion_priority")) || ($this ->getVar("film_promotion_priority") === "")) {
      return $this ->getVar("film_promotion_priority");
    } elseif (($this ->FilmPromotion) || ($this ->FilmPromotion === "")){
      return $this ->FilmPromotion -> getFilmPromotionPriority();
    } elseif (($this ->sessionVar("film_promotion_priority")) || ($this ->sessionVar("film_promotion_priority") == "")) {
      return $this ->sessionVar("film_promotion_priority");
    } else {
      return false;
    }
  }
  
  function setFilmPromotionPriority( $str ) {
    $this ->FilmPromotion -> setFilmPromotionPriority( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->FilmPromotion = FilmPromotionPeer::retrieveByPK( $id );
    }
    
    if ($this ->FilmPromotion ) {
       
    	       (is_numeric(WTVRcleanString($this ->FilmPromotion->getFilmPromotionId()))) ? $itemarray["film_promotion_id"] = WTVRcleanString($this ->FilmPromotion->getFilmPromotionId()) : null;
          (is_numeric(WTVRcleanString($this ->FilmPromotion->getFkFilmId()))) ? $itemarray["fk_film_id"] = WTVRcleanString($this ->FilmPromotion->getFkFilmId()) : null;
          (is_numeric(WTVRcleanString($this ->FilmPromotion->getFkPromotionId()))) ? $itemarray["fk_promotion_id"] = WTVRcleanString($this ->FilmPromotion->getFkPromotionId()) : null;
          (is_numeric(WTVRcleanString($this ->FilmPromotion->getFilmPromotionPriority()))) ? $itemarray["film_promotion_priority"] = WTVRcleanString($this ->FilmPromotion->getFilmPromotionPriority()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->FilmPromotion = FilmPromotionPeer::retrieveByPK( $id );
    } elseif (! $this ->FilmPromotion) {
      $this ->FilmPromotion = new FilmPromotion;
    }
        
  	 ($this -> getFilmPromotionId())? $this ->FilmPromotion->setFilmPromotionId( WTVRcleanString( $this -> getFilmPromotionId()) ) : null;
    ($this -> getFkFilmId())? $this ->FilmPromotion->setFkFilmId( WTVRcleanString( $this -> getFkFilmId()) ) : null;
    ($this -> getFkPromotionId())? $this ->FilmPromotion->setFkPromotionId( WTVRcleanString( $this -> getFkPromotionId()) ) : null;
    ($this -> getFilmPromotionPriority())? $this ->FilmPromotion->setFilmPromotionPriority( WTVRcleanString( $this -> getFilmPromotionPriority()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->FilmPromotion ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->FilmPromotion = FilmPromotionPeer::retrieveByPK($id);
    }
    
    if (! $this ->FilmPromotion ) {
      return;
    }
    
    $this ->FilmPromotion -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('FilmPromotion_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "FilmPromotionPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $FilmPromotion = FilmPromotionPeer::doSelect($c);
    
    if (count($FilmPromotion) >= 1) {
      $this ->FilmPromotion = $FilmPromotion[0];
      return true;
    } else {
      $this ->FilmPromotion = new FilmPromotion();
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
      $name = "FilmPromotionPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $FilmPromotion = FilmPromotionPeer::doSelect($c);
    
    if (count($FilmPromotion) >= 1) {
      $this ->FilmPromotion = $FilmPromotion[0];
      return true;
    } else {
      $this ->FilmPromotion = new FilmPromotion();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>