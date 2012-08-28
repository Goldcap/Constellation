<?php
       
   class FilmInfoCrudBase extends Utils_PageWidget { 
   
    var $FilmInfo;
   
       var $film_info_id;
   var $fk_film_id;
   var $film_info_type;
   var $film_info;
   var $film_info_url;
   var $film_info_updated_at;
   var $film_info_created_at;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getFilmInfoId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->FilmInfo = FilmInfoPeer::retrieveByPK( $id );
    } else {
      $this ->FilmInfo = new FilmInfo;
    }
  }
  
  function hydrate( $id ) {
      $this ->FilmInfo = FilmInfoPeer::retrieveByPK( $id );
  }
  
  function getFilmInfoId() {
    if (($this ->postVar("film_info_id")) || ($this ->postVar("film_info_id") === "")) {
      return $this ->postVar("film_info_id");
    } elseif (($this ->getVar("film_info_id")) || ($this ->getVar("film_info_id") === "")) {
      return $this ->getVar("film_info_id");
    } elseif (($this ->FilmInfo) || ($this ->FilmInfo === "")){
      return $this ->FilmInfo -> getFilmInfoId();
    } elseif (($this ->sessionVar("film_info_id")) || ($this ->sessionVar("film_info_id") == "")) {
      return $this ->sessionVar("film_info_id");
    } else {
      return false;
    }
  }
  
  function setFilmInfoId( $str ) {
    $this ->FilmInfo -> setFilmInfoId( $str );
  }
  
  function getFkFilmId() {
    if (($this ->postVar("fk_film_id")) || ($this ->postVar("fk_film_id") === "")) {
      return $this ->postVar("fk_film_id");
    } elseif (($this ->getVar("fk_film_id")) || ($this ->getVar("fk_film_id") === "")) {
      return $this ->getVar("fk_film_id");
    } elseif (($this ->FilmInfo) || ($this ->FilmInfo === "")){
      return $this ->FilmInfo -> getFkFilmId();
    } elseif (($this ->sessionVar("fk_film_id")) || ($this ->sessionVar("fk_film_id") == "")) {
      return $this ->sessionVar("fk_film_id");
    } else {
      return false;
    }
  }
  
  function setFkFilmId( $str ) {
    $this ->FilmInfo -> setFkFilmId( $str );
  }
  
  function getFilmInfoType() {
    if (($this ->postVar("film_info_type")) || ($this ->postVar("film_info_type") === "")) {
      return $this ->postVar("film_info_type");
    } elseif (($this ->getVar("film_info_type")) || ($this ->getVar("film_info_type") === "")) {
      return $this ->getVar("film_info_type");
    } elseif (($this ->FilmInfo) || ($this ->FilmInfo === "")){
      return $this ->FilmInfo -> getFilmInfoType();
    } elseif (($this ->sessionVar("film_info_type")) || ($this ->sessionVar("film_info_type") == "")) {
      return $this ->sessionVar("film_info_type");
    } else {
      return false;
    }
  }
  
  function setFilmInfoType( $str ) {
    $this ->FilmInfo -> setFilmInfoType( $str );
  }
  
  function getFilmInfo() {
    if (($this ->postVar("film_info")) || ($this ->postVar("film_info") === "")) {
      return $this ->postVar("film_info");
    } elseif (($this ->getVar("film_info")) || ($this ->getVar("film_info") === "")) {
      return $this ->getVar("film_info");
    } elseif (($this ->FilmInfo) || ($this ->FilmInfo === "")){
      return $this ->FilmInfo -> getFilmInfo();
    } elseif (($this ->sessionVar("film_info")) || ($this ->sessionVar("film_info") == "")) {
      return $this ->sessionVar("film_info");
    } else {
      return false;
    }
  }
  
  function setFilmInfo( $str ) {
    $this ->FilmInfo -> setFilmInfo( $str );
  }
  
  function getFilmInfoUrl() {
    if (($this ->postVar("film_info_url")) || ($this ->postVar("film_info_url") === "")) {
      return $this ->postVar("film_info_url");
    } elseif (($this ->getVar("film_info_url")) || ($this ->getVar("film_info_url") === "")) {
      return $this ->getVar("film_info_url");
    } elseif (($this ->FilmInfo) || ($this ->FilmInfo === "")){
      return $this ->FilmInfo -> getFilmInfoUrl();
    } elseif (($this ->sessionVar("film_info_url")) || ($this ->sessionVar("film_info_url") == "")) {
      return $this ->sessionVar("film_info_url");
    } else {
      return false;
    }
  }
  
  function setFilmInfoUrl( $str ) {
    $this ->FilmInfo -> setFilmInfoUrl( $str );
  }
  
  function getFilmInfoUpdatedAt() {
    if (($this ->postVar("film_info_updated_at")) || ($this ->postVar("film_info_updated_at") === "")) {
      return $this ->postVar("film_info_updated_at");
    } elseif (($this ->getVar("film_info_updated_at")) || ($this ->getVar("film_info_updated_at") === "")) {
      return $this ->getVar("film_info_updated_at");
    } elseif (($this ->FilmInfo) || ($this ->FilmInfo === "")){
      return $this ->FilmInfo -> getFilmInfoUpdatedAt();
    } elseif (($this ->sessionVar("film_info_updated_at")) || ($this ->sessionVar("film_info_updated_at") == "")) {
      return $this ->sessionVar("film_info_updated_at");
    } else {
      return false;
    }
  }
  
  function setFilmInfoUpdatedAt( $str ) {
    $this ->FilmInfo -> setFilmInfoUpdatedAt( $str );
  }
  
  function getFilmInfoCreatedAt() {
    if (($this ->postVar("film_info_created_at")) || ($this ->postVar("film_info_created_at") === "")) {
      return $this ->postVar("film_info_created_at");
    } elseif (($this ->getVar("film_info_created_at")) || ($this ->getVar("film_info_created_at") === "")) {
      return $this ->getVar("film_info_created_at");
    } elseif (($this ->FilmInfo) || ($this ->FilmInfo === "")){
      return $this ->FilmInfo -> getFilmInfoCreatedAt();
    } elseif (($this ->sessionVar("film_info_created_at")) || ($this ->sessionVar("film_info_created_at") == "")) {
      return $this ->sessionVar("film_info_created_at");
    } else {
      return false;
    }
  }
  
  function setFilmInfoCreatedAt( $str ) {
    $this ->FilmInfo -> setFilmInfoCreatedAt( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->FilmInfo = FilmInfoPeer::retrieveByPK( $id );
    }
    
    if ($this ->FilmInfo ) {
       
    	       (is_numeric(WTVRcleanString($this ->FilmInfo->getFilmInfoId()))) ? $itemarray["film_info_id"] = WTVRcleanString($this ->FilmInfo->getFilmInfoId()) : null;
          (is_numeric(WTVRcleanString($this ->FilmInfo->getFkFilmId()))) ? $itemarray["fk_film_id"] = WTVRcleanString($this ->FilmInfo->getFkFilmId()) : null;
          (WTVRcleanString($this ->FilmInfo->getFilmInfoType())) ? $itemarray["film_info_type"] = WTVRcleanString($this ->FilmInfo->getFilmInfoType()) : null;
          (WTVRcleanString($this ->FilmInfo->getFilmInfo())) ? $itemarray["film_info"] = WTVRcleanString($this ->FilmInfo->getFilmInfo()) : null;
          (WTVRcleanString($this ->FilmInfo->getFilmInfoUrl())) ? $itemarray["film_info_url"] = WTVRcleanString($this ->FilmInfo->getFilmInfoUrl()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->FilmInfo->getFilmInfoUpdatedAt())) ? $itemarray["film_info_updated_at"] = formatDate($this ->FilmInfo->getFilmInfoUpdatedAt('%Y-%m-%d %T'),"TS") : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->FilmInfo->getFilmInfoCreatedAt())) ? $itemarray["film_info_created_at"] = formatDate($this ->FilmInfo->getFilmInfoCreatedAt('%Y-%m-%d %T'),"TS") : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->FilmInfo = FilmInfoPeer::retrieveByPK( $id );
    } elseif (! $this ->FilmInfo) {
      $this ->FilmInfo = new FilmInfo;
    }
        
  	 ($this -> getFilmInfoId())? $this ->FilmInfo->setFilmInfoId( WTVRcleanString( $this -> getFilmInfoId()) ) : null;
    ($this -> getFkFilmId())? $this ->FilmInfo->setFkFilmId( WTVRcleanString( $this -> getFkFilmId()) ) : null;
    ($this -> getFilmInfoType())? $this ->FilmInfo->setFilmInfoType( WTVRcleanString( $this -> getFilmInfoType()) ) : null;
    ($this -> getFilmInfo())? $this ->FilmInfo->setFilmInfo( WTVRcleanString( $this -> getFilmInfo()) ) : null;
    ($this -> getFilmInfoUrl())? $this ->FilmInfo->setFilmInfoUrl( WTVRcleanString( $this -> getFilmInfoUrl()) ) : null;
          if (is_valid_date( $this ->FilmInfo->getFilmInfoUpdatedAt())) {
        $this ->FilmInfo->setFilmInfoUpdatedAt( formatDate($this -> getFilmInfoUpdatedAt(), "TS" ));
      } else {
      $FilmInfofilm_info_updated_at = $this -> sfDateTime( "film_info_updated_at" );
      ( $FilmInfofilm_info_updated_at != "01/01/1900 00:00:00" )? $this ->FilmInfo->setFilmInfoUpdatedAt( formatDate($FilmInfofilm_info_updated_at, "TS" )) : $this ->FilmInfo->setFilmInfoUpdatedAt( null );
      }
          if (is_valid_date( $this ->FilmInfo->getFilmInfoCreatedAt())) {
        $this ->FilmInfo->setFilmInfoCreatedAt( formatDate($this -> getFilmInfoCreatedAt(), "TS" ));
      } else {
      $FilmInfofilm_info_created_at = $this -> sfDateTime( "film_info_created_at" );
      ( $FilmInfofilm_info_created_at != "01/01/1900 00:00:00" )? $this ->FilmInfo->setFilmInfoCreatedAt( formatDate($FilmInfofilm_info_created_at, "TS" )) : $this ->FilmInfo->setFilmInfoCreatedAt( null );
      }
          
    $this -> save();
     
  }
  
  function save() {
    $this ->FilmInfo ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->FilmInfo = FilmInfoPeer::retrieveByPK($id);
    }
    
    if (! $this ->FilmInfo ) {
      return;
    }
    
    $this ->FilmInfo -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('FilmInfo_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "FilmInfoPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $FilmInfo = FilmInfoPeer::doSelect($c);
    
    if (count($FilmInfo) >= 1) {
      $this ->FilmInfo = $FilmInfo[0];
      return true;
    } else {
      $this ->FilmInfo = new FilmInfo();
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
      $name = "FilmInfoPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $FilmInfo = FilmInfoPeer::doSelect($c);
    
    if (count($FilmInfo) >= 1) {
      $this ->FilmInfo = $FilmInfo[0];
      return true;
    } else {
      $this ->FilmInfo = new FilmInfo();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>