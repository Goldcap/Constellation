<?php
       
   class ClickTrackCrudBase extends Utils_PageWidget { 
   
    var $ClickTrack;
   
       var $click_track_id;
   var $click_track_guid;
   var $fk_click_type;
   var $fk_click_owner_id;
   var $click_track_count;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getClickTrackId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->ClickTrack = ClickTrackPeer::retrieveByPK( $id );
    } else {
      $this ->ClickTrack = new ClickTrack;
    }
  }
  
  function hydrate( $id ) {
      $this ->ClickTrack = ClickTrackPeer::retrieveByPK( $id );
  }
  
  function getClickTrackId() {
    if (($this ->postVar("click_track_id")) || ($this ->postVar("click_track_id") === "")) {
      return $this ->postVar("click_track_id");
    } elseif (($this ->getVar("click_track_id")) || ($this ->getVar("click_track_id") === "")) {
      return $this ->getVar("click_track_id");
    } elseif (($this ->ClickTrack) || ($this ->ClickTrack === "")){
      return $this ->ClickTrack -> getClickTrackId();
    } elseif (($this ->sessionVar("click_track_id")) || ($this ->sessionVar("click_track_id") == "")) {
      return $this ->sessionVar("click_track_id");
    } else {
      return false;
    }
  }
  
  function setClickTrackId( $str ) {
    $this ->ClickTrack -> setClickTrackId( $str );
  }
  
  function getClickTrackGuid() {
    if (($this ->postVar("click_track_guid")) || ($this ->postVar("click_track_guid") === "")) {
      return $this ->postVar("click_track_guid");
    } elseif (($this ->getVar("click_track_guid")) || ($this ->getVar("click_track_guid") === "")) {
      return $this ->getVar("click_track_guid");
    } elseif (($this ->ClickTrack) || ($this ->ClickTrack === "")){
      return $this ->ClickTrack -> getClickTrackGuid();
    } elseif (($this ->sessionVar("click_track_guid")) || ($this ->sessionVar("click_track_guid") == "")) {
      return $this ->sessionVar("click_track_guid");
    } else {
      return false;
    }
  }
  
  function setClickTrackGuid( $str ) {
    $this ->ClickTrack -> setClickTrackGuid( $str );
  }
  
  function getFkClickType() {
    if (($this ->postVar("fk_click_type")) || ($this ->postVar("fk_click_type") === "")) {
      return $this ->postVar("fk_click_type");
    } elseif (($this ->getVar("fk_click_type")) || ($this ->getVar("fk_click_type") === "")) {
      return $this ->getVar("fk_click_type");
    } elseif (($this ->ClickTrack) || ($this ->ClickTrack === "")){
      return $this ->ClickTrack -> getFkClickType();
    } elseif (($this ->sessionVar("fk_click_type")) || ($this ->sessionVar("fk_click_type") == "")) {
      return $this ->sessionVar("fk_click_type");
    } else {
      return false;
    }
  }
  
  function setFkClickType( $str ) {
    $this ->ClickTrack -> setFkClickType( $str );
  }
  
  function getFkClickOwnerId() {
    if (($this ->postVar("fk_click_owner_id")) || ($this ->postVar("fk_click_owner_id") === "")) {
      return $this ->postVar("fk_click_owner_id");
    } elseif (($this ->getVar("fk_click_owner_id")) || ($this ->getVar("fk_click_owner_id") === "")) {
      return $this ->getVar("fk_click_owner_id");
    } elseif (($this ->ClickTrack) || ($this ->ClickTrack === "")){
      return $this ->ClickTrack -> getFkClickOwnerId();
    } elseif (($this ->sessionVar("fk_click_owner_id")) || ($this ->sessionVar("fk_click_owner_id") == "")) {
      return $this ->sessionVar("fk_click_owner_id");
    } else {
      return false;
    }
  }
  
  function setFkClickOwnerId( $str ) {
    $this ->ClickTrack -> setFkClickOwnerId( $str );
  }
  
  function getClickTrackCount() {
    if (($this ->postVar("click_track_count")) || ($this ->postVar("click_track_count") === "")) {
      return $this ->postVar("click_track_count");
    } elseif (($this ->getVar("click_track_count")) || ($this ->getVar("click_track_count") === "")) {
      return $this ->getVar("click_track_count");
    } elseif (($this ->ClickTrack) || ($this ->ClickTrack === "")){
      return $this ->ClickTrack -> getClickTrackCount();
    } elseif (($this ->sessionVar("click_track_count")) || ($this ->sessionVar("click_track_count") == "")) {
      return $this ->sessionVar("click_track_count");
    } else {
      return false;
    }
  }
  
  function setClickTrackCount( $str ) {
    $this ->ClickTrack -> setClickTrackCount( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->ClickTrack = ClickTrackPeer::retrieveByPK( $id );
    }
    
    if ($this ->ClickTrack ) {
       
    	       (is_numeric(WTVRcleanString($this ->ClickTrack->getClickTrackId()))) ? $itemarray["click_track_id"] = WTVRcleanString($this ->ClickTrack->getClickTrackId()) : null;
          (WTVRcleanString($this ->ClickTrack->getClickTrackGuid())) ? $itemarray["click_track_guid"] = WTVRcleanString($this ->ClickTrack->getClickTrackGuid()) : null;
          (is_numeric(WTVRcleanString($this ->ClickTrack->getFkClickType()))) ? $itemarray["fk_click_type"] = WTVRcleanString($this ->ClickTrack->getFkClickType()) : null;
          (is_numeric(WTVRcleanString($this ->ClickTrack->getFkClickOwnerId()))) ? $itemarray["fk_click_owner_id"] = WTVRcleanString($this ->ClickTrack->getFkClickOwnerId()) : null;
          (is_numeric(WTVRcleanString($this ->ClickTrack->getClickTrackCount()))) ? $itemarray["click_track_count"] = WTVRcleanString($this ->ClickTrack->getClickTrackCount()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->ClickTrack = ClickTrackPeer::retrieveByPK( $id );
    } elseif (! $this ->ClickTrack) {
      $this ->ClickTrack = new ClickTrack;
    }
        
  	 ($this -> getClickTrackId())? $this ->ClickTrack->setClickTrackId( WTVRcleanString( $this -> getClickTrackId()) ) : null;
    ($this -> getClickTrackGuid())? $this ->ClickTrack->setClickTrackGuid( WTVRcleanString( $this -> getClickTrackGuid()) ) : null;
    ($this -> getFkClickType())? $this ->ClickTrack->setFkClickType( WTVRcleanString( $this -> getFkClickType()) ) : null;
    ($this -> getFkClickOwnerId())? $this ->ClickTrack->setFkClickOwnerId( WTVRcleanString( $this -> getFkClickOwnerId()) ) : null;
    ($this -> getClickTrackCount())? $this ->ClickTrack->setClickTrackCount( WTVRcleanString( $this -> getClickTrackCount()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->ClickTrack ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->ClickTrack = ClickTrackPeer::retrieveByPK($id);
    }
    
    if (! $this ->ClickTrack ) {
      return;
    }
    
    $this ->ClickTrack -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('ClickTrack_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "ClickTrackPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $ClickTrack = ClickTrackPeer::doSelect($c);
    
    if (count($ClickTrack) >= 1) {
      $this ->ClickTrack = $ClickTrack[0];
      return true;
    } else {
      $this ->ClickTrack = new ClickTrack();
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
      $name = "ClickTrackPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $ClickTrack = ClickTrackPeer::doSelect($c);
    
    if (count($ClickTrack) >= 1) {
      $this ->ClickTrack = $ClickTrack[0];
      return true;
    } else {
      $this ->ClickTrack = new ClickTrack();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>