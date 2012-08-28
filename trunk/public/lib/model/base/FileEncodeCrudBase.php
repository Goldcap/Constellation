<?php
       
   class FileEncodeCrudBase extends Utils_PageWidget { 
   
    var $FileEncode;
   
       var $file_encode_id;
   var $fk_film_id;
   var $fk_user_id;
   var $file_encode_source;
   var $file_encode_size;
   var $file_encode_date;
   var $file_encode_status;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getFileEncodeId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->FileEncode = FileEncodePeer::retrieveByPK( $id );
    } else {
      $this ->FileEncode = new FileEncode;
    }
  }
  
  function hydrate( $id ) {
      $this ->FileEncode = FileEncodePeer::retrieveByPK( $id );
  }
  
  function getFileEncodeId() {
    if (($this ->postVar("file_encode_id")) || ($this ->postVar("file_encode_id") === "")) {
      return $this ->postVar("file_encode_id");
    } elseif (($this ->getVar("file_encode_id")) || ($this ->getVar("file_encode_id") === "")) {
      return $this ->getVar("file_encode_id");
    } elseif (($this ->FileEncode) || ($this ->FileEncode === "")){
      return $this ->FileEncode -> getFileEncodeId();
    } elseif (($this ->sessionVar("file_encode_id")) || ($this ->sessionVar("file_encode_id") == "")) {
      return $this ->sessionVar("file_encode_id");
    } else {
      return false;
    }
  }
  
  function setFileEncodeId( $str ) {
    $this ->FileEncode -> setFileEncodeId( $str );
  }
  
  function getFkFilmId() {
    if (($this ->postVar("fk_film_id")) || ($this ->postVar("fk_film_id") === "")) {
      return $this ->postVar("fk_film_id");
    } elseif (($this ->getVar("fk_film_id")) || ($this ->getVar("fk_film_id") === "")) {
      return $this ->getVar("fk_film_id");
    } elseif (($this ->FileEncode) || ($this ->FileEncode === "")){
      return $this ->FileEncode -> getFkFilmId();
    } elseif (($this ->sessionVar("fk_film_id")) || ($this ->sessionVar("fk_film_id") == "")) {
      return $this ->sessionVar("fk_film_id");
    } else {
      return false;
    }
  }
  
  function setFkFilmId( $str ) {
    $this ->FileEncode -> setFkFilmId( $str );
  }
  
  function getFkUserId() {
    if (($this ->postVar("fk_user_id")) || ($this ->postVar("fk_user_id") === "")) {
      return $this ->postVar("fk_user_id");
    } elseif (($this ->getVar("fk_user_id")) || ($this ->getVar("fk_user_id") === "")) {
      return $this ->getVar("fk_user_id");
    } elseif (($this ->FileEncode) || ($this ->FileEncode === "")){
      return $this ->FileEncode -> getFkUserId();
    } elseif (($this ->sessionVar("fk_user_id")) || ($this ->sessionVar("fk_user_id") == "")) {
      return $this ->sessionVar("fk_user_id");
    } else {
      return false;
    }
  }
  
  function setFkUserId( $str ) {
    $this ->FileEncode -> setFkUserId( $str );
  }
  
  function getFileEncodeSource() {
    if (($this ->postVar("file_encode_source")) || ($this ->postVar("file_encode_source") === "")) {
      return $this ->postVar("file_encode_source");
    } elseif (($this ->getVar("file_encode_source")) || ($this ->getVar("file_encode_source") === "")) {
      return $this ->getVar("file_encode_source");
    } elseif (($this ->FileEncode) || ($this ->FileEncode === "")){
      return $this ->FileEncode -> getFileEncodeSource();
    } elseif (($this ->sessionVar("file_encode_source")) || ($this ->sessionVar("file_encode_source") == "")) {
      return $this ->sessionVar("file_encode_source");
    } else {
      return false;
    }
  }
  
  function setFileEncodeSource( $str ) {
    $this ->FileEncode -> setFileEncodeSource( $str );
  }
  
  function getFileEncodeSize() {
    if (($this ->postVar("file_encode_size")) || ($this ->postVar("file_encode_size") === "")) {
      return $this ->postVar("file_encode_size");
    } elseif (($this ->getVar("file_encode_size")) || ($this ->getVar("file_encode_size") === "")) {
      return $this ->getVar("file_encode_size");
    } elseif (($this ->FileEncode) || ($this ->FileEncode === "")){
      return $this ->FileEncode -> getFileEncodeSize();
    } elseif (($this ->sessionVar("file_encode_size")) || ($this ->sessionVar("file_encode_size") == "")) {
      return $this ->sessionVar("file_encode_size");
    } else {
      return false;
    }
  }
  
  function setFileEncodeSize( $str ) {
    $this ->FileEncode -> setFileEncodeSize( $str );
  }
  
  function getFileEncodeDate() {
    if (($this ->postVar("file_encode_date")) || ($this ->postVar("file_encode_date") === "")) {
      return $this ->postVar("file_encode_date");
    } elseif (($this ->getVar("file_encode_date")) || ($this ->getVar("file_encode_date") === "")) {
      return $this ->getVar("file_encode_date");
    } elseif (($this ->FileEncode) || ($this ->FileEncode === "")){
      return $this ->FileEncode -> getFileEncodeDate();
    } elseif (($this ->sessionVar("file_encode_date")) || ($this ->sessionVar("file_encode_date") == "")) {
      return $this ->sessionVar("file_encode_date");
    } else {
      return false;
    }
  }
  
  function setFileEncodeDate( $str ) {
    $this ->FileEncode -> setFileEncodeDate( $str );
  }
  
  function getFileEncodeStatus() {
    if (($this ->postVar("file_encode_status")) || ($this ->postVar("file_encode_status") === "")) {
      return $this ->postVar("file_encode_status");
    } elseif (($this ->getVar("file_encode_status")) || ($this ->getVar("file_encode_status") === "")) {
      return $this ->getVar("file_encode_status");
    } elseif (($this ->FileEncode) || ($this ->FileEncode === "")){
      return $this ->FileEncode -> getFileEncodeStatus();
    } elseif (($this ->sessionVar("file_encode_status")) || ($this ->sessionVar("file_encode_status") == "")) {
      return $this ->sessionVar("file_encode_status");
    } else {
      return false;
    }
  }
  
  function setFileEncodeStatus( $str ) {
    $this ->FileEncode -> setFileEncodeStatus( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->FileEncode = FileEncodePeer::retrieveByPK( $id );
    }
    
    if ($this ->FileEncode ) {
       
    	       (is_numeric(WTVRcleanString($this ->FileEncode->getFileEncodeId()))) ? $itemarray["file_encode_id"] = WTVRcleanString($this ->FileEncode->getFileEncodeId()) : null;
          (is_numeric(WTVRcleanString($this ->FileEncode->getFkFilmId()))) ? $itemarray["fk_film_id"] = WTVRcleanString($this ->FileEncode->getFkFilmId()) : null;
          (WTVRcleanString($this ->FileEncode->getFkUserId())) ? $itemarray["fk_user_id"] = WTVRcleanString($this ->FileEncode->getFkUserId()) : null;
          (WTVRcleanString($this ->FileEncode->getFileEncodeSource())) ? $itemarray["file_encode_source"] = WTVRcleanString($this ->FileEncode->getFileEncodeSource()) : null;
          (WTVRcleanString($this ->FileEncode->getFileEncodeSize())) ? $itemarray["file_encode_size"] = WTVRcleanString($this ->FileEncode->getFileEncodeSize()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->FileEncode->getFileEncodeDate())) ? $itemarray["file_encode_date"] = formatDate($this ->FileEncode->getFileEncodeDate('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->FileEncode->getFileEncodeStatus())) ? $itemarray["file_encode_status"] = WTVRcleanString($this ->FileEncode->getFileEncodeStatus()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->FileEncode = FileEncodePeer::retrieveByPK( $id );
    } elseif (! $this ->FileEncode) {
      $this ->FileEncode = new FileEncode;
    }
        
  	 ($this -> getFileEncodeId())? $this ->FileEncode->setFileEncodeId( WTVRcleanString( $this -> getFileEncodeId()) ) : null;
    ($this -> getFkFilmId())? $this ->FileEncode->setFkFilmId( WTVRcleanString( $this -> getFkFilmId()) ) : null;
    ($this -> getFkUserId())? $this ->FileEncode->setFkUserId( WTVRcleanString( $this -> getFkUserId()) ) : null;
    ($this -> getFileEncodeSource())? $this ->FileEncode->setFileEncodeSource( WTVRcleanString( $this -> getFileEncodeSource()) ) : null;
    ($this -> getFileEncodeSize())? $this ->FileEncode->setFileEncodeSize( WTVRcleanString( $this -> getFileEncodeSize()) ) : null;
          if (is_valid_date( $this ->FileEncode->getFileEncodeDate())) {
        $this ->FileEncode->setFileEncodeDate( formatDate($this -> getFileEncodeDate(), "TS" ));
      } else {
      $FileEncodefile_encode_date = $this -> sfDateTime( "file_encode_date" );
      ( $FileEncodefile_encode_date != "01/01/1900 00:00:00" )? $this ->FileEncode->setFileEncodeDate( formatDate($FileEncodefile_encode_date, "TS" )) : $this ->FileEncode->setFileEncodeDate( null );
      }
    ($this -> getFileEncodeStatus())? $this ->FileEncode->setFileEncodeStatus( WTVRcleanString( $this -> getFileEncodeStatus()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->FileEncode ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->FileEncode = FileEncodePeer::retrieveByPK($id);
    }
    
    if (! $this ->FileEncode ) {
      return;
    }
    
    $this ->FileEncode -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('FileEncode_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "FileEncodePeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $FileEncode = FileEncodePeer::doSelect($c);
    
    if (count($FileEncode) >= 1) {
      $this ->FileEncode = $FileEncode[0];
      return true;
    } else {
      $this ->FileEncode = new FileEncode();
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
      $name = "FileEncodePeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $FileEncode = FileEncodePeer::doSelect($c);
    
    if (count($FileEncode) >= 1) {
      $this ->FileEncode = $FileEncode[0];
      return true;
    } else {
      $this ->FileEncode = new FileEncode();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>