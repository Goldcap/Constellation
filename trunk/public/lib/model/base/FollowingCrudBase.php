<?php
       
   class FollowingCrudBase extends Utils_PageWidget { 
   
    var $Following;
   
       var $following_id;
   var $fk_follower_id;
   var $fk_followed_id;
   var $following_date_created;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getFollowingId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Following = FollowingPeer::retrieveByPK( $id );
    } else {
      $this ->Following = new Following;
    }
  }
  
  function hydrate( $id ) {
      $this ->Following = FollowingPeer::retrieveByPK( $id );
  }
  
  function getFollowingId() {
    if (($this ->postVar("following_id")) || ($this ->postVar("following_id") === "")) {
      return $this ->postVar("following_id");
    } elseif (($this ->getVar("following_id")) || ($this ->getVar("following_id") === "")) {
      return $this ->getVar("following_id");
    } elseif (($this ->Following) || ($this ->Following === "")){
      return $this ->Following -> getFollowingId();
    } elseif (($this ->sessionVar("following_id")) || ($this ->sessionVar("following_id") == "")) {
      return $this ->sessionVar("following_id");
    } else {
      return false;
    }
  }
  
  function setFollowingId( $str ) {
    $this ->Following -> setFollowingId( $str );
  }
  
  function getFkFollowerId() {
    if (($this ->postVar("fk_follower_id")) || ($this ->postVar("fk_follower_id") === "")) {
      return $this ->postVar("fk_follower_id");
    } elseif (($this ->getVar("fk_follower_id")) || ($this ->getVar("fk_follower_id") === "")) {
      return $this ->getVar("fk_follower_id");
    } elseif (($this ->Following) || ($this ->Following === "")){
      return $this ->Following -> getFkFollowerId();
    } elseif (($this ->sessionVar("fk_follower_id")) || ($this ->sessionVar("fk_follower_id") == "")) {
      return $this ->sessionVar("fk_follower_id");
    } else {
      return false;
    }
  }
  
  function setFkFollowerId( $str ) {
    $this ->Following -> setFkFollowerId( $str );
  }
  
  function getFkFollowedId() {
    if (($this ->postVar("fk_followed_id")) || ($this ->postVar("fk_followed_id") === "")) {
      return $this ->postVar("fk_followed_id");
    } elseif (($this ->getVar("fk_followed_id")) || ($this ->getVar("fk_followed_id") === "")) {
      return $this ->getVar("fk_followed_id");
    } elseif (($this ->Following) || ($this ->Following === "")){
      return $this ->Following -> getFkFollowedId();
    } elseif (($this ->sessionVar("fk_followed_id")) || ($this ->sessionVar("fk_followed_id") == "")) {
      return $this ->sessionVar("fk_followed_id");
    } else {
      return false;
    }
  }
  
  function setFkFollowedId( $str ) {
    $this ->Following -> setFkFollowedId( $str );
  }
  
  function getFollowingDateCreated() {
    if (($this ->postVar("following_date_created")) || ($this ->postVar("following_date_created") === "")) {
      return $this ->postVar("following_date_created");
    } elseif (($this ->getVar("following_date_created")) || ($this ->getVar("following_date_created") === "")) {
      return $this ->getVar("following_date_created");
    } elseif (($this ->Following) || ($this ->Following === "")){
      return $this ->Following -> getFollowingDateCreated();
    } elseif (($this ->sessionVar("following_date_created")) || ($this ->sessionVar("following_date_created") == "")) {
      return $this ->sessionVar("following_date_created");
    } else {
      return false;
    }
  }
  
  function setFollowingDateCreated( $str ) {
    $this ->Following -> setFollowingDateCreated( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Following = FollowingPeer::retrieveByPK( $id );
    }
    
    if ($this ->Following ) {
       
    	       (is_numeric(WTVRcleanString($this ->Following->getFollowingId()))) ? $itemarray["following_id"] = WTVRcleanString($this ->Following->getFollowingId()) : null;
          (is_numeric(WTVRcleanString($this ->Following->getFkFollowerId()))) ? $itemarray["fk_follower_id"] = WTVRcleanString($this ->Following->getFkFollowerId()) : null;
          (is_numeric(WTVRcleanString($this ->Following->getFkFollowedId()))) ? $itemarray["fk_followed_id"] = WTVRcleanString($this ->Following->getFkFollowedId()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Following->getFollowingDateCreated())) ? $itemarray["following_date_created"] = formatDate($this ->Following->getFollowingDateCreated('%Y-%m-%d %T'),"TS") : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Following = FollowingPeer::retrieveByPK( $id );
    } elseif (! $this ->Following) {
      $this ->Following = new Following;
    }
        
  	 ($this -> getFollowingId())? $this ->Following->setFollowingId( WTVRcleanString( $this -> getFollowingId()) ) : null;
    ($this -> getFkFollowerId())? $this ->Following->setFkFollowerId( WTVRcleanString( $this -> getFkFollowerId()) ) : null;
    ($this -> getFkFollowedId())? $this ->Following->setFkFollowedId( WTVRcleanString( $this -> getFkFollowedId()) ) : null;
          if (is_valid_date( $this ->Following->getFollowingDateCreated())) {
        $this ->Following->setFollowingDateCreated( formatDate($this -> getFollowingDateCreated(), "TS" ));
      } else {
      $Followingfollowing_date_created = $this -> sfDateTime( "following_date_created" );
      ( $Followingfollowing_date_created != "01/01/1900 00:00:00" )? $this ->Following->setFollowingDateCreated( formatDate($Followingfollowing_date_created, "TS" )) : $this ->Following->setFollowingDateCreated( null );
      }
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Following ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Following = FollowingPeer::retrieveByPK($id);
    }
    
    if (! $this ->Following ) {
      return;
    }
    
    $this ->Following -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Following_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "FollowingPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Following = FollowingPeer::doSelect($c);
    
    if (count($Following) >= 1) {
      $this ->Following = $Following[0];
      return true;
    } else {
      $this ->Following = new Following();
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
      $name = "FollowingPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Following = FollowingPeer::doSelect($c);
    
    if (count($Following) >= 1) {
      $this ->Following = $Following[0];
      return true;
    } else {
      $this ->Following = new Following();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>