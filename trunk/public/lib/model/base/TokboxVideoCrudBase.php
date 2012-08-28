<?php
       
   class TokboxVideoCrudBase extends Utils_PageWidget { 
   
    var $TokboxVideo;
   
       var $tokbox_video_id;
   var $tokbox_video_archive_id;
   var $tokbox_video_date_created;
   var $fk_screening_unique_key;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getTokboxVideoId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->TokboxVideo = TokboxVideoPeer::retrieveByPK( $id );
    } else {
      $this ->TokboxVideo = new TokboxVideo;
    }
  }
  
  function hydrate( $id ) {
      $this ->TokboxVideo = TokboxVideoPeer::retrieveByPK( $id );
  }
  
  function getTokboxVideoId() {
    if (($this ->postVar("tokbox_video_id")) || ($this ->postVar("tokbox_video_id") === "")) {
      return $this ->postVar("tokbox_video_id");
    } elseif (($this ->getVar("tokbox_video_id")) || ($this ->getVar("tokbox_video_id") === "")) {
      return $this ->getVar("tokbox_video_id");
    } elseif (($this ->TokboxVideo) || ($this ->TokboxVideo === "")){
      return $this ->TokboxVideo -> getTokboxVideoId();
    } elseif (($this ->sessionVar("tokbox_video_id")) || ($this ->sessionVar("tokbox_video_id") == "")) {
      return $this ->sessionVar("tokbox_video_id");
    } else {
      return false;
    }
  }
  
  function setTokboxVideoId( $str ) {
    $this ->TokboxVideo -> setTokboxVideoId( $str );
  }
  
  function getTokboxVideoArchiveId() {
    if (($this ->postVar("tokbox_video_archive_id")) || ($this ->postVar("tokbox_video_archive_id") === "")) {
      return $this ->postVar("tokbox_video_archive_id");
    } elseif (($this ->getVar("tokbox_video_archive_id")) || ($this ->getVar("tokbox_video_archive_id") === "")) {
      return $this ->getVar("tokbox_video_archive_id");
    } elseif (($this ->TokboxVideo) || ($this ->TokboxVideo === "")){
      return $this ->TokboxVideo -> getTokboxVideoArchiveId();
    } elseif (($this ->sessionVar("tokbox_video_archive_id")) || ($this ->sessionVar("tokbox_video_archive_id") == "")) {
      return $this ->sessionVar("tokbox_video_archive_id");
    } else {
      return false;
    }
  }
  
  function setTokboxVideoArchiveId( $str ) {
    $this ->TokboxVideo -> setTokboxVideoArchiveId( $str );
  }
  
  function getTokboxVideoDateCreated() {
    if (($this ->postVar("tokbox_video_date_created")) || ($this ->postVar("tokbox_video_date_created") === "")) {
      return $this ->postVar("tokbox_video_date_created");
    } elseif (($this ->getVar("tokbox_video_date_created")) || ($this ->getVar("tokbox_video_date_created") === "")) {
      return $this ->getVar("tokbox_video_date_created");
    } elseif (($this ->TokboxVideo) || ($this ->TokboxVideo === "")){
      return $this ->TokboxVideo -> getTokboxVideoDateCreated();
    } elseif (($this ->sessionVar("tokbox_video_date_created")) || ($this ->sessionVar("tokbox_video_date_created") == "")) {
      return $this ->sessionVar("tokbox_video_date_created");
    } else {
      return false;
    }
  }
  
  function setTokboxVideoDateCreated( $str ) {
    $this ->TokboxVideo -> setTokboxVideoDateCreated( $str );
  }
  
  function getFkScreeningUniqueKey() {
    if (($this ->postVar("fk_screening_unique_key")) || ($this ->postVar("fk_screening_unique_key") === "")) {
      return $this ->postVar("fk_screening_unique_key");
    } elseif (($this ->getVar("fk_screening_unique_key")) || ($this ->getVar("fk_screening_unique_key") === "")) {
      return $this ->getVar("fk_screening_unique_key");
    } elseif (($this ->TokboxVideo) || ($this ->TokboxVideo === "")){
      return $this ->TokboxVideo -> getFkScreeningUniqueKey();
    } elseif (($this ->sessionVar("fk_screening_unique_key")) || ($this ->sessionVar("fk_screening_unique_key") == "")) {
      return $this ->sessionVar("fk_screening_unique_key");
    } else {
      return false;
    }
  }
  
  function setFkScreeningUniqueKey( $str ) {
    $this ->TokboxVideo -> setFkScreeningUniqueKey( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->TokboxVideo = TokboxVideoPeer::retrieveByPK( $id );
    }
    
    if ($this ->TokboxVideo ) {
       
    	       (is_numeric(WTVRcleanString($this ->TokboxVideo->getTokboxVideoId()))) ? $itemarray["tokbox_video_id"] = WTVRcleanString($this ->TokboxVideo->getTokboxVideoId()) : null;
          (WTVRcleanString($this ->TokboxVideo->getTokboxVideoArchiveId())) ? $itemarray["tokbox_video_archive_id"] = WTVRcleanString($this ->TokboxVideo->getTokboxVideoArchiveId()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->TokboxVideo->getTokboxVideoDateCreated())) ? $itemarray["tokbox_video_date_created"] = formatDate($this ->TokboxVideo->getTokboxVideoDateCreated('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->TokboxVideo->getFkScreeningUniqueKey())) ? $itemarray["fk_screening_unique_key"] = WTVRcleanString($this ->TokboxVideo->getFkScreeningUniqueKey()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->TokboxVideo = TokboxVideoPeer::retrieveByPK( $id );
    } elseif (! $this ->TokboxVideo) {
      $this ->TokboxVideo = new TokboxVideo;
    }
        
  	 ($this -> getTokboxVideoId())? $this ->TokboxVideo->setTokboxVideoId( WTVRcleanString( $this -> getTokboxVideoId()) ) : null;
    ($this -> getTokboxVideoArchiveId())? $this ->TokboxVideo->setTokboxVideoArchiveId( WTVRcleanString( $this -> getTokboxVideoArchiveId()) ) : null;
          if (is_valid_date( $this ->TokboxVideo->getTokboxVideoDateCreated())) {
        $this ->TokboxVideo->setTokboxVideoDateCreated( formatDate($this -> getTokboxVideoDateCreated(), "TS" ));
      } else {
      $TokboxVideotokbox_video_date_created = $this -> sfDateTime( "tokbox_video_date_created" );
      ( $TokboxVideotokbox_video_date_created != "01/01/1900 00:00:00" )? $this ->TokboxVideo->setTokboxVideoDateCreated( formatDate($TokboxVideotokbox_video_date_created, "TS" )) : $this ->TokboxVideo->setTokboxVideoDateCreated( null );
      }
    ($this -> getFkScreeningUniqueKey())? $this ->TokboxVideo->setFkScreeningUniqueKey( WTVRcleanString( $this -> getFkScreeningUniqueKey()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->TokboxVideo ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->TokboxVideo = TokboxVideoPeer::retrieveByPK($id);
    }
    
    if (! $this ->TokboxVideo ) {
      return;
    }
    
    $this ->TokboxVideo -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('TokboxVideo_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "TokboxVideoPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $TokboxVideo = TokboxVideoPeer::doSelect($c);
    
    if (count($TokboxVideo) >= 1) {
      $this ->TokboxVideo = $TokboxVideo[0];
      return true;
    } else {
      $this ->TokboxVideo = new TokboxVideo();
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
      $name = "TokboxVideoPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $TokboxVideo = TokboxVideoPeer::doSelect($c);
    
    if (count($TokboxVideo) >= 1) {
      $this ->TokboxVideo = $TokboxVideo[0];
      return true;
    } else {
      $this ->TokboxVideo = new TokboxVideo();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>