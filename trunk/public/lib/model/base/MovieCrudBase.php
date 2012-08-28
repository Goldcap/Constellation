<?php
       
   class MovieCrudBase extends Utils_PageWidget { 
   
    var $Movie;
   
       var $movie_id;
   var $movie_name;
   var $movie_rate_no;
   var $movie_rate_count;
   var $movie_created_at;
   var $movie_updated_at;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getMovieId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Movie = MoviePeer::retrieveByPK( $id );
    } else {
      $this ->Movie = new Movie;
    }
  }
  
  function hydrate( $id ) {
      $this ->Movie = MoviePeer::retrieveByPK( $id );
  }
  
  function getMovieId() {
    if (($this ->postVar("movie_id")) || ($this ->postVar("movie_id") === "")) {
      return $this ->postVar("movie_id");
    } elseif (($this ->getVar("movie_id")) || ($this ->getVar("movie_id") === "")) {
      return $this ->getVar("movie_id");
    } elseif (($this ->Movie) || ($this ->Movie === "")){
      return $this ->Movie -> getMovieId();
    } elseif (($this ->sessionVar("movie_id")) || ($this ->sessionVar("movie_id") == "")) {
      return $this ->sessionVar("movie_id");
    } else {
      return false;
    }
  }
  
  function setMovieId( $str ) {
    $this ->Movie -> setMovieId( $str );
  }
  
  function getMovieName() {
    if (($this ->postVar("movie_name")) || ($this ->postVar("movie_name") === "")) {
      return $this ->postVar("movie_name");
    } elseif (($this ->getVar("movie_name")) || ($this ->getVar("movie_name") === "")) {
      return $this ->getVar("movie_name");
    } elseif (($this ->Movie) || ($this ->Movie === "")){
      return $this ->Movie -> getMovieName();
    } elseif (($this ->sessionVar("movie_name")) || ($this ->sessionVar("movie_name") == "")) {
      return $this ->sessionVar("movie_name");
    } else {
      return false;
    }
  }
  
  function setMovieName( $str ) {
    $this ->Movie -> setMovieName( $str );
  }
  
  function getMovieRateNo() {
    if (($this ->postVar("movie_rate_no")) || ($this ->postVar("movie_rate_no") === "")) {
      return $this ->postVar("movie_rate_no");
    } elseif (($this ->getVar("movie_rate_no")) || ($this ->getVar("movie_rate_no") === "")) {
      return $this ->getVar("movie_rate_no");
    } elseif (($this ->Movie) || ($this ->Movie === "")){
      return $this ->Movie -> getMovieRateNo();
    } elseif (($this ->sessionVar("movie_rate_no")) || ($this ->sessionVar("movie_rate_no") == "")) {
      return $this ->sessionVar("movie_rate_no");
    } else {
      return false;
    }
  }
  
  function setMovieRateNo( $str ) {
    $this ->Movie -> setMovieRateNo( $str );
  }
  
  function getMovieRateCount() {
    if (($this ->postVar("movie_rate_count")) || ($this ->postVar("movie_rate_count") === "")) {
      return $this ->postVar("movie_rate_count");
    } elseif (($this ->getVar("movie_rate_count")) || ($this ->getVar("movie_rate_count") === "")) {
      return $this ->getVar("movie_rate_count");
    } elseif (($this ->Movie) || ($this ->Movie === "")){
      return $this ->Movie -> getMovieRateCount();
    } elseif (($this ->sessionVar("movie_rate_count")) || ($this ->sessionVar("movie_rate_count") == "")) {
      return $this ->sessionVar("movie_rate_count");
    } else {
      return false;
    }
  }
  
  function setMovieRateCount( $str ) {
    $this ->Movie -> setMovieRateCount( $str );
  }
  
  function getMovieCreatedAt() {
    if (($this ->postVar("movie_created_at")) || ($this ->postVar("movie_created_at") === "")) {
      return $this ->postVar("movie_created_at");
    } elseif (($this ->getVar("movie_created_at")) || ($this ->getVar("movie_created_at") === "")) {
      return $this ->getVar("movie_created_at");
    } elseif (($this ->Movie) || ($this ->Movie === "")){
      return $this ->Movie -> getMovieCreatedAt();
    } elseif (($this ->sessionVar("movie_created_at")) || ($this ->sessionVar("movie_created_at") == "")) {
      return $this ->sessionVar("movie_created_at");
    } else {
      return false;
    }
  }
  
  function setMovieCreatedAt( $str ) {
    $this ->Movie -> setMovieCreatedAt( $str );
  }
  
  function getMovieUpdatedAt() {
    if (($this ->postVar("movie_updated_at")) || ($this ->postVar("movie_updated_at") === "")) {
      return $this ->postVar("movie_updated_at");
    } elseif (($this ->getVar("movie_updated_at")) || ($this ->getVar("movie_updated_at") === "")) {
      return $this ->getVar("movie_updated_at");
    } elseif (($this ->Movie) || ($this ->Movie === "")){
      return $this ->Movie -> getMovieUpdatedAt();
    } elseif (($this ->sessionVar("movie_updated_at")) || ($this ->sessionVar("movie_updated_at") == "")) {
      return $this ->sessionVar("movie_updated_at");
    } else {
      return false;
    }
  }
  
  function setMovieUpdatedAt( $str ) {
    $this ->Movie -> setMovieUpdatedAt( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Movie = MoviePeer::retrieveByPK( $id );
    }
    
    if ($this ->Movie ) {
       
    	       (is_numeric(WTVRcleanString($this ->Movie->getMovieId()))) ? $itemarray["movie_id"] = WTVRcleanString($this ->Movie->getMovieId()) : null;
          (WTVRcleanString($this ->Movie->getMovieName())) ? $itemarray["movie_name"] = WTVRcleanString($this ->Movie->getMovieName()) : null;
          (is_numeric(WTVRcleanString($this ->Movie->getMovieRateNo()))) ? $itemarray["movie_rate_no"] = WTVRcleanString($this ->Movie->getMovieRateNo()) : null;
          (is_numeric(WTVRcleanString($this ->Movie->getMovieRateCount()))) ? $itemarray["movie_rate_count"] = WTVRcleanString($this ->Movie->getMovieRateCount()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Movie->getMovieCreatedAt())) ? $itemarray["movie_created_at"] = formatDate($this ->Movie->getMovieCreatedAt('%Y-%m-%d %T'),"TS") : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Movie->getMovieUpdatedAt())) ? $itemarray["movie_updated_at"] = formatDate($this ->Movie->getMovieUpdatedAt('%Y-%m-%d %T'),"TS") : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Movie = MoviePeer::retrieveByPK( $id );
    } elseif (! $this ->Movie) {
      $this ->Movie = new Movie;
    }
        
  	 ($this -> getMovieId())? $this ->Movie->setMovieId( WTVRcleanString( $this -> getMovieId()) ) : null;
    ($this -> getMovieName())? $this ->Movie->setMovieName( WTVRcleanString( $this -> getMovieName()) ) : null;
    ($this -> getMovieRateNo())? $this ->Movie->setMovieRateNo( WTVRcleanString( $this -> getMovieRateNo()) ) : null;
    ($this -> getMovieRateCount())? $this ->Movie->setMovieRateCount( WTVRcleanString( $this -> getMovieRateCount()) ) : null;
          if (is_valid_date( $this ->Movie->getMovieCreatedAt())) {
        $this ->Movie->setMovieCreatedAt( formatDate($this -> getMovieCreatedAt(), "TS" ));
      } else {
      $Moviemovie_created_at = $this -> sfDateTime( "movie_created_at" );
      ( $Moviemovie_created_at != "01/01/1900 00:00:00" )? $this ->Movie->setMovieCreatedAt( formatDate($Moviemovie_created_at, "TS" )) : $this ->Movie->setMovieCreatedAt( null );
      }
          if (is_valid_date( $this ->Movie->getMovieUpdatedAt())) {
        $this ->Movie->setMovieUpdatedAt( formatDate($this -> getMovieUpdatedAt(), "TS" ));
      } else {
      $Moviemovie_updated_at = $this -> sfDateTime( "movie_updated_at" );
      ( $Moviemovie_updated_at != "01/01/1900 00:00:00" )? $this ->Movie->setMovieUpdatedAt( formatDate($Moviemovie_updated_at, "TS" )) : $this ->Movie->setMovieUpdatedAt( null );
      }
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Movie ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Movie = MoviePeer::retrieveByPK($id);
    }
    
    if (! $this ->Movie ) {
      return;
    }
    
    $this ->Movie -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Movie_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "MoviePeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Movie = MoviePeer::doSelect($c);
    
    if (count($Movie) >= 1) {
      $this ->Movie = $Movie[0];
      return true;
    } else {
      $this ->Movie = new Movie();
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
      $name = "MoviePeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Movie = MoviePeer::doSelect($c);
    
    if (count($Movie) >= 1) {
      $this ->Movie = $Movie[0];
      return true;
    } else {
      $this ->Movie = new Movie();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>