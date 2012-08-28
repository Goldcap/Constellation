<?php
       
   class TwitterFeedCrudBase extends Utils_PageWidget { 
   
    var $TwitterFeed;
   
       var $twitter_feed_id;
   var $twitter_feed_guid;
   var $twitter_feed_author;
   var $twitter_feed_author_id;
   var $twitter_feed_date_created;
   var $twitter_feed_text;
   var $twitter_feed_responded;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getTwitterFeedId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->TwitterFeed = TwitterFeedPeer::retrieveByPK( $id );
    } else {
      $this ->TwitterFeed = new TwitterFeed;
    }
  }
  
  function hydrate( $id ) {
      $this ->TwitterFeed = TwitterFeedPeer::retrieveByPK( $id );
  }
  
  function getTwitterFeedId() {
    if (($this ->postVar("twitter_feed_id")) || ($this ->postVar("twitter_feed_id") === "")) {
      return $this ->postVar("twitter_feed_id");
    } elseif (($this ->getVar("twitter_feed_id")) || ($this ->getVar("twitter_feed_id") === "")) {
      return $this ->getVar("twitter_feed_id");
    } elseif (($this ->TwitterFeed) || ($this ->TwitterFeed === "")){
      return $this ->TwitterFeed -> getTwitterFeedId();
    } elseif (($this ->sessionVar("twitter_feed_id")) || ($this ->sessionVar("twitter_feed_id") == "")) {
      return $this ->sessionVar("twitter_feed_id");
    } else {
      return false;
    }
  }
  
  function setTwitterFeedId( $str ) {
    $this ->TwitterFeed -> setTwitterFeedId( $str );
  }
  
  function getTwitterFeedGuid() {
    if (($this ->postVar("twitter_feed_guid")) || ($this ->postVar("twitter_feed_guid") === "")) {
      return $this ->postVar("twitter_feed_guid");
    } elseif (($this ->getVar("twitter_feed_guid")) || ($this ->getVar("twitter_feed_guid") === "")) {
      return $this ->getVar("twitter_feed_guid");
    } elseif (($this ->TwitterFeed) || ($this ->TwitterFeed === "")){
      return $this ->TwitterFeed -> getTwitterFeedGuid();
    } elseif (($this ->sessionVar("twitter_feed_guid")) || ($this ->sessionVar("twitter_feed_guid") == "")) {
      return $this ->sessionVar("twitter_feed_guid");
    } else {
      return false;
    }
  }
  
  function setTwitterFeedGuid( $str ) {
    $this ->TwitterFeed -> setTwitterFeedGuid( $str );
  }
  
  function getTwitterFeedAuthor() {
    if (($this ->postVar("twitter_feed_author")) || ($this ->postVar("twitter_feed_author") === "")) {
      return $this ->postVar("twitter_feed_author");
    } elseif (($this ->getVar("twitter_feed_author")) || ($this ->getVar("twitter_feed_author") === "")) {
      return $this ->getVar("twitter_feed_author");
    } elseif (($this ->TwitterFeed) || ($this ->TwitterFeed === "")){
      return $this ->TwitterFeed -> getTwitterFeedAuthor();
    } elseif (($this ->sessionVar("twitter_feed_author")) || ($this ->sessionVar("twitter_feed_author") == "")) {
      return $this ->sessionVar("twitter_feed_author");
    } else {
      return false;
    }
  }
  
  function setTwitterFeedAuthor( $str ) {
    $this ->TwitterFeed -> setTwitterFeedAuthor( $str );
  }
  
  function getTwitterFeedAuthorId() {
    if (($this ->postVar("twitter_feed_author_id")) || ($this ->postVar("twitter_feed_author_id") === "")) {
      return $this ->postVar("twitter_feed_author_id");
    } elseif (($this ->getVar("twitter_feed_author_id")) || ($this ->getVar("twitter_feed_author_id") === "")) {
      return $this ->getVar("twitter_feed_author_id");
    } elseif (($this ->TwitterFeed) || ($this ->TwitterFeed === "")){
      return $this ->TwitterFeed -> getTwitterFeedAuthorId();
    } elseif (($this ->sessionVar("twitter_feed_author_id")) || ($this ->sessionVar("twitter_feed_author_id") == "")) {
      return $this ->sessionVar("twitter_feed_author_id");
    } else {
      return false;
    }
  }
  
  function setTwitterFeedAuthorId( $str ) {
    $this ->TwitterFeed -> setTwitterFeedAuthorId( $str );
  }
  
  function getTwitterFeedDateCreated() {
    if (($this ->postVar("twitter_feed_date_created")) || ($this ->postVar("twitter_feed_date_created") === "")) {
      return $this ->postVar("twitter_feed_date_created");
    } elseif (($this ->getVar("twitter_feed_date_created")) || ($this ->getVar("twitter_feed_date_created") === "")) {
      return $this ->getVar("twitter_feed_date_created");
    } elseif (($this ->TwitterFeed) || ($this ->TwitterFeed === "")){
      return $this ->TwitterFeed -> getTwitterFeedDateCreated();
    } elseif (($this ->sessionVar("twitter_feed_date_created")) || ($this ->sessionVar("twitter_feed_date_created") == "")) {
      return $this ->sessionVar("twitter_feed_date_created");
    } else {
      return false;
    }
  }
  
  function setTwitterFeedDateCreated( $str ) {
    $this ->TwitterFeed -> setTwitterFeedDateCreated( $str );
  }
  
  function getTwitterFeedText() {
    if (($this ->postVar("twitter_feed_text")) || ($this ->postVar("twitter_feed_text") === "")) {
      return $this ->postVar("twitter_feed_text");
    } elseif (($this ->getVar("twitter_feed_text")) || ($this ->getVar("twitter_feed_text") === "")) {
      return $this ->getVar("twitter_feed_text");
    } elseif (($this ->TwitterFeed) || ($this ->TwitterFeed === "")){
      return $this ->TwitterFeed -> getTwitterFeedText();
    } elseif (($this ->sessionVar("twitter_feed_text")) || ($this ->sessionVar("twitter_feed_text") == "")) {
      return $this ->sessionVar("twitter_feed_text");
    } else {
      return false;
    }
  }
  
  function setTwitterFeedText( $str ) {
    $this ->TwitterFeed -> setTwitterFeedText( $str );
  }
  
  function getTwitterFeedResponded() {
    if (($this ->postVar("twitter_feed_responded")) || ($this ->postVar("twitter_feed_responded") === "")) {
      return $this ->postVar("twitter_feed_responded");
    } elseif (($this ->getVar("twitter_feed_responded")) || ($this ->getVar("twitter_feed_responded") === "")) {
      return $this ->getVar("twitter_feed_responded");
    } elseif (($this ->TwitterFeed) || ($this ->TwitterFeed === "")){
      return $this ->TwitterFeed -> getTwitterFeedResponded();
    } elseif (($this ->sessionVar("twitter_feed_responded")) || ($this ->sessionVar("twitter_feed_responded") == "")) {
      return $this ->sessionVar("twitter_feed_responded");
    } else {
      return false;
    }
  }
  
  function setTwitterFeedResponded( $str ) {
    $this ->TwitterFeed -> setTwitterFeedResponded( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->TwitterFeed = TwitterFeedPeer::retrieveByPK( $id );
    }
    
    if ($this ->TwitterFeed ) {
       
    	       (is_numeric(WTVRcleanString($this ->TwitterFeed->getTwitterFeedId()))) ? $itemarray["twitter_feed_id"] = WTVRcleanString($this ->TwitterFeed->getTwitterFeedId()) : null;
          (WTVRcleanString($this ->TwitterFeed->getTwitterFeedGuid())) ? $itemarray["twitter_feed_guid"] = WTVRcleanString($this ->TwitterFeed->getTwitterFeedGuid()) : null;
          (WTVRcleanString($this ->TwitterFeed->getTwitterFeedAuthor())) ? $itemarray["twitter_feed_author"] = WTVRcleanString($this ->TwitterFeed->getTwitterFeedAuthor()) : null;
          (is_numeric(WTVRcleanString($this ->TwitterFeed->getTwitterFeedAuthorId()))) ? $itemarray["twitter_feed_author_id"] = WTVRcleanString($this ->TwitterFeed->getTwitterFeedAuthorId()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->TwitterFeed->getTwitterFeedDateCreated())) ? $itemarray["twitter_feed_date_created"] = formatDate($this ->TwitterFeed->getTwitterFeedDateCreated('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->TwitterFeed->getTwitterFeedText())) ? $itemarray["twitter_feed_text"] = WTVRcleanString($this ->TwitterFeed->getTwitterFeedText()) : null;
          (WTVRcleanString($this ->TwitterFeed->getTwitterFeedResponded())) ? $itemarray["twitter_feed_responded"] = WTVRcleanString($this ->TwitterFeed->getTwitterFeedResponded()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->TwitterFeed = TwitterFeedPeer::retrieveByPK( $id );
    } elseif (! $this ->TwitterFeed) {
      $this ->TwitterFeed = new TwitterFeed;
    }
        
  	 ($this -> getTwitterFeedId())? $this ->TwitterFeed->setTwitterFeedId( WTVRcleanString( $this -> getTwitterFeedId()) ) : null;
    ($this -> getTwitterFeedGuid())? $this ->TwitterFeed->setTwitterFeedGuid( WTVRcleanString( $this -> getTwitterFeedGuid()) ) : null;
    ($this -> getTwitterFeedAuthor())? $this ->TwitterFeed->setTwitterFeedAuthor( WTVRcleanString( $this -> getTwitterFeedAuthor()) ) : null;
    ($this -> getTwitterFeedAuthorId())? $this ->TwitterFeed->setTwitterFeedAuthorId( WTVRcleanString( $this -> getTwitterFeedAuthorId()) ) : null;
          if (is_valid_date( $this ->TwitterFeed->getTwitterFeedDateCreated())) {
        $this ->TwitterFeed->setTwitterFeedDateCreated( formatDate($this -> getTwitterFeedDateCreated(), "TS" ));
      } else {
      $TwitterFeedtwitter_feed_date_created = $this -> sfDateTime( "twitter_feed_date_created" );
      ( $TwitterFeedtwitter_feed_date_created != "01/01/1900 00:00:00" )? $this ->TwitterFeed->setTwitterFeedDateCreated( formatDate($TwitterFeedtwitter_feed_date_created, "TS" )) : $this ->TwitterFeed->setTwitterFeedDateCreated( null );
      }
    ($this -> getTwitterFeedText())? $this ->TwitterFeed->setTwitterFeedText( WTVRcleanString( $this -> getTwitterFeedText()) ) : null;
    ($this -> getTwitterFeedResponded())? $this ->TwitterFeed->setTwitterFeedResponded( WTVRcleanString( $this -> getTwitterFeedResponded()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->TwitterFeed ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->TwitterFeed = TwitterFeedPeer::retrieveByPK($id);
    }
    
    if (! $this ->TwitterFeed ) {
      return;
    }
    
    $this ->TwitterFeed -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('TwitterFeed_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "TwitterFeedPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $TwitterFeed = TwitterFeedPeer::doSelect($c);
    
    if (count($TwitterFeed) >= 1) {
      $this ->TwitterFeed = $TwitterFeed[0];
      return true;
    } else {
      $this ->TwitterFeed = new TwitterFeed();
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
      $name = "TwitterFeedPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $TwitterFeed = TwitterFeedPeer::doSelect($c);
    
    if (count($TwitterFeed) >= 1) {
      $this ->TwitterFeed = $TwitterFeed[0];
      return true;
    } else {
      $this ->TwitterFeed = new TwitterFeed();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>