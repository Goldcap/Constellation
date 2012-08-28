<?php
       
   class ConversationCrudBase extends Utils_PageWidget { 
   
    var $Conversation;
   
       var $conversation_id;
   var $conversation_author;
   var $conversation_author_image;
   var $fk_author_id;
   var $fk_film_id;
   var $conversation_date_created;
   var $conversation_sequence;
   var $conversation_thread;
   var $conversation_body;
   var $conversation_rating;
   var $conversation_status;
   var $conversation_guid;
   var $fk_promoter_id;
   var $conversation_asset_type;
   var $conversation_asset_guid;
   var $fk_screening_id;
   var $fk_user_id;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getConversationId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Conversation = ConversationPeer::retrieveByPK( $id );
    } else {
      $this ->Conversation = new Conversation;
    }
  }
  
  function hydrate( $id ) {
      $this ->Conversation = ConversationPeer::retrieveByPK( $id );
  }
  
  function getConversationId() {
    if (($this ->postVar("conversation_id")) || ($this ->postVar("conversation_id") === "")) {
      return $this ->postVar("conversation_id");
    } elseif (($this ->getVar("conversation_id")) || ($this ->getVar("conversation_id") === "")) {
      return $this ->getVar("conversation_id");
    } elseif (($this ->Conversation) || ($this ->Conversation === "")){
      return $this ->Conversation -> getConversationId();
    } elseif (($this ->sessionVar("conversation_id")) || ($this ->sessionVar("conversation_id") == "")) {
      return $this ->sessionVar("conversation_id");
    } else {
      return false;
    }
  }
  
  function setConversationId( $str ) {
    $this ->Conversation -> setConversationId( $str );
  }
  
  function getConversationAuthor() {
    if (($this ->postVar("conversation_author")) || ($this ->postVar("conversation_author") === "")) {
      return $this ->postVar("conversation_author");
    } elseif (($this ->getVar("conversation_author")) || ($this ->getVar("conversation_author") === "")) {
      return $this ->getVar("conversation_author");
    } elseif (($this ->Conversation) || ($this ->Conversation === "")){
      return $this ->Conversation -> getConversationAuthor();
    } elseif (($this ->sessionVar("conversation_author")) || ($this ->sessionVar("conversation_author") == "")) {
      return $this ->sessionVar("conversation_author");
    } else {
      return false;
    }
  }
  
  function setConversationAuthor( $str ) {
    $this ->Conversation -> setConversationAuthor( $str );
  }
  
  function getConversationAuthorImage() {
    if (($this ->postVar("conversation_author_image")) || ($this ->postVar("conversation_author_image") === "")) {
      return $this ->postVar("conversation_author_image");
    } elseif (($this ->getVar("conversation_author_image")) || ($this ->getVar("conversation_author_image") === "")) {
      return $this ->getVar("conversation_author_image");
    } elseif (($this ->Conversation) || ($this ->Conversation === "")){
      return $this ->Conversation -> getConversationAuthorImage();
    } elseif (($this ->sessionVar("conversation_author_image")) || ($this ->sessionVar("conversation_author_image") == "")) {
      return $this ->sessionVar("conversation_author_image");
    } else {
      return false;
    }
  }
  
  function setConversationAuthorImage( $str ) {
    $this ->Conversation -> setConversationAuthorImage( $str );
  }
  
  function getFkAuthorId() {
    if (($this ->postVar("fk_author_id")) || ($this ->postVar("fk_author_id") === "")) {
      return $this ->postVar("fk_author_id");
    } elseif (($this ->getVar("fk_author_id")) || ($this ->getVar("fk_author_id") === "")) {
      return $this ->getVar("fk_author_id");
    } elseif (($this ->Conversation) || ($this ->Conversation === "")){
      return $this ->Conversation -> getFkAuthorId();
    } elseif (($this ->sessionVar("fk_author_id")) || ($this ->sessionVar("fk_author_id") == "")) {
      return $this ->sessionVar("fk_author_id");
    } else {
      return false;
    }
  }
  
  function setFkAuthorId( $str ) {
    $this ->Conversation -> setFkAuthorId( $str );
  }
  
  function getFkFilmId() {
    if (($this ->postVar("fk_film_id")) || ($this ->postVar("fk_film_id") === "")) {
      return $this ->postVar("fk_film_id");
    } elseif (($this ->getVar("fk_film_id")) || ($this ->getVar("fk_film_id") === "")) {
      return $this ->getVar("fk_film_id");
    } elseif (($this ->Conversation) || ($this ->Conversation === "")){
      return $this ->Conversation -> getFkFilmId();
    } elseif (($this ->sessionVar("fk_film_id")) || ($this ->sessionVar("fk_film_id") == "")) {
      return $this ->sessionVar("fk_film_id");
    } else {
      return false;
    }
  }
  
  function setFkFilmId( $str ) {
    $this ->Conversation -> setFkFilmId( $str );
  }
  
  function getConversationDateCreated() {
    if (($this ->postVar("conversation_date_created")) || ($this ->postVar("conversation_date_created") === "")) {
      return $this ->postVar("conversation_date_created");
    } elseif (($this ->getVar("conversation_date_created")) || ($this ->getVar("conversation_date_created") === "")) {
      return $this ->getVar("conversation_date_created");
    } elseif (($this ->Conversation) || ($this ->Conversation === "")){
      return $this ->Conversation -> getConversationDateCreated();
    } elseif (($this ->sessionVar("conversation_date_created")) || ($this ->sessionVar("conversation_date_created") == "")) {
      return $this ->sessionVar("conversation_date_created");
    } else {
      return false;
    }
  }
  
  function setConversationDateCreated( $str ) {
    $this ->Conversation -> setConversationDateCreated( $str );
  }
  
  function getConversationSequence() {
    if (($this ->postVar("conversation_sequence")) || ($this ->postVar("conversation_sequence") === "")) {
      return $this ->postVar("conversation_sequence");
    } elseif (($this ->getVar("conversation_sequence")) || ($this ->getVar("conversation_sequence") === "")) {
      return $this ->getVar("conversation_sequence");
    } elseif (($this ->Conversation) || ($this ->Conversation === "")){
      return $this ->Conversation -> getConversationSequence();
    } elseif (($this ->sessionVar("conversation_sequence")) || ($this ->sessionVar("conversation_sequence") == "")) {
      return $this ->sessionVar("conversation_sequence");
    } else {
      return false;
    }
  }
  
  function setConversationSequence( $str ) {
    $this ->Conversation -> setConversationSequence( $str );
  }
  
  function getConversationThread() {
    if (($this ->postVar("conversation_thread")) || ($this ->postVar("conversation_thread") === "")) {
      return $this ->postVar("conversation_thread");
    } elseif (($this ->getVar("conversation_thread")) || ($this ->getVar("conversation_thread") === "")) {
      return $this ->getVar("conversation_thread");
    } elseif (($this ->Conversation) || ($this ->Conversation === "")){
      return $this ->Conversation -> getConversationThread();
    } elseif (($this ->sessionVar("conversation_thread")) || ($this ->sessionVar("conversation_thread") == "")) {
      return $this ->sessionVar("conversation_thread");
    } else {
      return false;
    }
  }
  
  function setConversationThread( $str ) {
    $this ->Conversation -> setConversationThread( $str );
  }
  
  function getConversationBody() {
    if (($this ->postVar("conversation_body")) || ($this ->postVar("conversation_body") === "")) {
      return $this ->postVar("conversation_body");
    } elseif (($this ->getVar("conversation_body")) || ($this ->getVar("conversation_body") === "")) {
      return $this ->getVar("conversation_body");
    } elseif (($this ->Conversation) || ($this ->Conversation === "")){
      return $this ->Conversation -> getConversationBody();
    } elseif (($this ->sessionVar("conversation_body")) || ($this ->sessionVar("conversation_body") == "")) {
      return $this ->sessionVar("conversation_body");
    } else {
      return false;
    }
  }
  
  function setConversationBody( $str ) {
    $this ->Conversation -> setConversationBody( $str );
  }
  
  function getConversationRating() {
    if (($this ->postVar("conversation_rating")) || ($this ->postVar("conversation_rating") === "")) {
      return $this ->postVar("conversation_rating");
    } elseif (($this ->getVar("conversation_rating")) || ($this ->getVar("conversation_rating") === "")) {
      return $this ->getVar("conversation_rating");
    } elseif (($this ->Conversation) || ($this ->Conversation === "")){
      return $this ->Conversation -> getConversationRating();
    } elseif (($this ->sessionVar("conversation_rating")) || ($this ->sessionVar("conversation_rating") == "")) {
      return $this ->sessionVar("conversation_rating");
    } else {
      return false;
    }
  }
  
  function setConversationRating( $str ) {
    $this ->Conversation -> setConversationRating( $str );
  }
  
  function getConversationStatus() {
    if (($this ->postVar("conversation_status")) || ($this ->postVar("conversation_status") === "")) {
      return $this ->postVar("conversation_status");
    } elseif (($this ->getVar("conversation_status")) || ($this ->getVar("conversation_status") === "")) {
      return $this ->getVar("conversation_status");
    } elseif (($this ->Conversation) || ($this ->Conversation === "")){
      return $this ->Conversation -> getConversationStatus();
    } elseif (($this ->sessionVar("conversation_status")) || ($this ->sessionVar("conversation_status") == "")) {
      return $this ->sessionVar("conversation_status");
    } else {
      return false;
    }
  }
  
  function setConversationStatus( $str ) {
    $this ->Conversation -> setConversationStatus( $str );
  }
  
  function getConversationGuid() {
    if (($this ->postVar("conversation_guid")) || ($this ->postVar("conversation_guid") === "")) {
      return $this ->postVar("conversation_guid");
    } elseif (($this ->getVar("conversation_guid")) || ($this ->getVar("conversation_guid") === "")) {
      return $this ->getVar("conversation_guid");
    } elseif (($this ->Conversation) || ($this ->Conversation === "")){
      return $this ->Conversation -> getConversationGuid();
    } elseif (($this ->sessionVar("conversation_guid")) || ($this ->sessionVar("conversation_guid") == "")) {
      return $this ->sessionVar("conversation_guid");
    } else {
      return false;
    }
  }
  
  function setConversationGuid( $str ) {
    $this ->Conversation -> setConversationGuid( $str );
  }
  
  function getFkPromoterId() {
    if (($this ->postVar("fk_promoter_id")) || ($this ->postVar("fk_promoter_id") === "")) {
      return $this ->postVar("fk_promoter_id");
    } elseif (($this ->getVar("fk_promoter_id")) || ($this ->getVar("fk_promoter_id") === "")) {
      return $this ->getVar("fk_promoter_id");
    } elseif (($this ->Conversation) || ($this ->Conversation === "")){
      return $this ->Conversation -> getFkPromoterId();
    } elseif (($this ->sessionVar("fk_promoter_id")) || ($this ->sessionVar("fk_promoter_id") == "")) {
      return $this ->sessionVar("fk_promoter_id");
    } else {
      return false;
    }
  }
  
  function setFkPromoterId( $str ) {
    $this ->Conversation -> setFkPromoterId( $str );
  }
  
  function getConversationAssetType() {
    if (($this ->postVar("conversation_asset_type")) || ($this ->postVar("conversation_asset_type") === "")) {
      return $this ->postVar("conversation_asset_type");
    } elseif (($this ->getVar("conversation_asset_type")) || ($this ->getVar("conversation_asset_type") === "")) {
      return $this ->getVar("conversation_asset_type");
    } elseif (($this ->Conversation) || ($this ->Conversation === "")){
      return $this ->Conversation -> getConversationAssetType();
    } elseif (($this ->sessionVar("conversation_asset_type")) || ($this ->sessionVar("conversation_asset_type") == "")) {
      return $this ->sessionVar("conversation_asset_type");
    } else {
      return false;
    }
  }
  
  function setConversationAssetType( $str ) {
    $this ->Conversation -> setConversationAssetType( $str );
  }
  
  function getConversationAssetGuid() {
    if (($this ->postVar("conversation_asset_guid")) || ($this ->postVar("conversation_asset_guid") === "")) {
      return $this ->postVar("conversation_asset_guid");
    } elseif (($this ->getVar("conversation_asset_guid")) || ($this ->getVar("conversation_asset_guid") === "")) {
      return $this ->getVar("conversation_asset_guid");
    } elseif (($this ->Conversation) || ($this ->Conversation === "")){
      return $this ->Conversation -> getConversationAssetGuid();
    } elseif (($this ->sessionVar("conversation_asset_guid")) || ($this ->sessionVar("conversation_asset_guid") == "")) {
      return $this ->sessionVar("conversation_asset_guid");
    } else {
      return false;
    }
  }
  
  function setConversationAssetGuid( $str ) {
    $this ->Conversation -> setConversationAssetGuid( $str );
  }
  
  function getFkScreeningId() {
    if (($this ->postVar("fk_screening_id")) || ($this ->postVar("fk_screening_id") === "")) {
      return $this ->postVar("fk_screening_id");
    } elseif (($this ->getVar("fk_screening_id")) || ($this ->getVar("fk_screening_id") === "")) {
      return $this ->getVar("fk_screening_id");
    } elseif (($this ->Conversation) || ($this ->Conversation === "")){
      return $this ->Conversation -> getFkScreeningId();
    } elseif (($this ->sessionVar("fk_screening_id")) || ($this ->sessionVar("fk_screening_id") == "")) {
      return $this ->sessionVar("fk_screening_id");
    } else {
      return false;
    }
  }
  
  function setFkScreeningId( $str ) {
    $this ->Conversation -> setFkScreeningId( $str );
  }
  
  function getFkUserId() {
    if (($this ->postVar("fk_user_id")) || ($this ->postVar("fk_user_id") === "")) {
      return $this ->postVar("fk_user_id");
    } elseif (($this ->getVar("fk_user_id")) || ($this ->getVar("fk_user_id") === "")) {
      return $this ->getVar("fk_user_id");
    } elseif (($this ->Conversation) || ($this ->Conversation === "")){
      return $this ->Conversation -> getFkUserId();
    } elseif (($this ->sessionVar("fk_user_id")) || ($this ->sessionVar("fk_user_id") == "")) {
      return $this ->sessionVar("fk_user_id");
    } else {
      return false;
    }
  }
  
  function setFkUserId( $str ) {
    $this ->Conversation -> setFkUserId( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Conversation = ConversationPeer::retrieveByPK( $id );
    }
    
    if ($this ->Conversation ) {
       
    	       (is_numeric(WTVRcleanString($this ->Conversation->getConversationId()))) ? $itemarray["conversation_id"] = WTVRcleanString($this ->Conversation->getConversationId()) : null;
          (WTVRcleanString($this ->Conversation->getConversationAuthor())) ? $itemarray["conversation_author"] = WTVRcleanString($this ->Conversation->getConversationAuthor()) : null;
          (WTVRcleanString($this ->Conversation->getConversationAuthorImage())) ? $itemarray["conversation_author_image"] = WTVRcleanString($this ->Conversation->getConversationAuthorImage()) : null;
          (is_numeric(WTVRcleanString($this ->Conversation->getFkAuthorId()))) ? $itemarray["fk_author_id"] = WTVRcleanString($this ->Conversation->getFkAuthorId()) : null;
          (is_numeric(WTVRcleanString($this ->Conversation->getFkFilmId()))) ? $itemarray["fk_film_id"] = WTVRcleanString($this ->Conversation->getFkFilmId()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Conversation->getConversationDateCreated())) ? $itemarray["conversation_date_created"] = formatDate($this ->Conversation->getConversationDateCreated('%Y-%m-%d %T'),"TS") : null;
          (is_numeric(WTVRcleanString($this ->Conversation->getConversationSequence()))) ? $itemarray["conversation_sequence"] = WTVRcleanString($this ->Conversation->getConversationSequence()) : null;
          (WTVRcleanString($this ->Conversation->getConversationThread())) ? $itemarray["conversation_thread"] = WTVRcleanString($this ->Conversation->getConversationThread()) : null;
          (WTVRcleanString($this ->Conversation->getConversationBody())) ? $itemarray["conversation_body"] = WTVRcleanString($this ->Conversation->getConversationBody()) : null;
          (is_numeric(WTVRcleanString($this ->Conversation->getConversationRating()))) ? $itemarray["conversation_rating"] = WTVRcleanString($this ->Conversation->getConversationRating()) : null;
          (WTVRcleanString($this ->Conversation->getConversationStatus())) ? $itemarray["conversation_status"] = WTVRcleanString($this ->Conversation->getConversationStatus()) : null;
          (WTVRcleanString($this ->Conversation->getConversationGuid())) ? $itemarray["conversation_guid"] = WTVRcleanString($this ->Conversation->getConversationGuid()) : null;
          (is_numeric(WTVRcleanString($this ->Conversation->getFkPromoterId()))) ? $itemarray["fk_promoter_id"] = WTVRcleanString($this ->Conversation->getFkPromoterId()) : null;
          (WTVRcleanString($this ->Conversation->getConversationAssetType())) ? $itemarray["conversation_asset_type"] = WTVRcleanString($this ->Conversation->getConversationAssetType()) : null;
          (WTVRcleanString($this ->Conversation->getConversationAssetGuid())) ? $itemarray["conversation_asset_guid"] = WTVRcleanString($this ->Conversation->getConversationAssetGuid()) : null;
          (is_numeric(WTVRcleanString($this ->Conversation->getFkScreeningId()))) ? $itemarray["fk_screening_id"] = WTVRcleanString($this ->Conversation->getFkScreeningId()) : null;
          (is_numeric(WTVRcleanString($this ->Conversation->getFkUserId()))) ? $itemarray["fk_user_id"] = WTVRcleanString($this ->Conversation->getFkUserId()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Conversation = ConversationPeer::retrieveByPK( $id );
    } elseif (! $this ->Conversation) {
      $this ->Conversation = new Conversation;
    }
        
  	 ($this -> getConversationId())? $this ->Conversation->setConversationId( WTVRcleanString( $this -> getConversationId()) ) : null;
    ($this -> getConversationAuthor())? $this ->Conversation->setConversationAuthor( WTVRcleanString( $this -> getConversationAuthor()) ) : null;
    ($this -> getConversationAuthorImage())? $this ->Conversation->setConversationAuthorImage( WTVRcleanString( $this -> getConversationAuthorImage()) ) : null;
    ($this -> getFkAuthorId())? $this ->Conversation->setFkAuthorId( WTVRcleanString( $this -> getFkAuthorId()) ) : null;
    ($this -> getFkFilmId())? $this ->Conversation->setFkFilmId( WTVRcleanString( $this -> getFkFilmId()) ) : null;
          if (is_valid_date( $this ->Conversation->getConversationDateCreated())) {
        $this ->Conversation->setConversationDateCreated( formatDate($this -> getConversationDateCreated(), "TS" ));
      } else {
      $Conversationconversation_date_created = $this -> sfDateTime( "conversation_date_created" );
      ( $Conversationconversation_date_created != "01/01/1900 00:00:00" )? $this ->Conversation->setConversationDateCreated( formatDate($Conversationconversation_date_created, "TS" )) : $this ->Conversation->setConversationDateCreated( null );
      }
    ($this -> getConversationSequence())? $this ->Conversation->setConversationSequence( WTVRcleanString( $this -> getConversationSequence()) ) : null;
    ($this -> getConversationThread())? $this ->Conversation->setConversationThread( WTVRcleanString( $this -> getConversationThread()) ) : null;
    ($this -> getConversationBody())? $this ->Conversation->setConversationBody( WTVRcleanString( $this -> getConversationBody()) ) : null;
    ($this -> getConversationRating())? $this ->Conversation->setConversationRating( WTVRcleanString( $this -> getConversationRating()) ) : null;
    ($this -> getConversationStatus())? $this ->Conversation->setConversationStatus( WTVRcleanString( $this -> getConversationStatus()) ) : null;
    ($this -> getConversationGuid())? $this ->Conversation->setConversationGuid( WTVRcleanString( $this -> getConversationGuid()) ) : null;
    ($this -> getFkPromoterId())? $this ->Conversation->setFkPromoterId( WTVRcleanString( $this -> getFkPromoterId()) ) : null;
    ($this -> getConversationAssetType())? $this ->Conversation->setConversationAssetType( WTVRcleanString( $this -> getConversationAssetType()) ) : null;
    ($this -> getConversationAssetGuid())? $this ->Conversation->setConversationAssetGuid( WTVRcleanString( $this -> getConversationAssetGuid()) ) : null;
    ($this -> getFkScreeningId())? $this ->Conversation->setFkScreeningId( WTVRcleanString( $this -> getFkScreeningId()) ) : null;
    ($this -> getFkUserId())? $this ->Conversation->setFkUserId( WTVRcleanString( $this -> getFkUserId()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Conversation ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Conversation = ConversationPeer::retrieveByPK($id);
    }
    
    if (! $this ->Conversation ) {
      return;
    }
    
    $this ->Conversation -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Conversation_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "ConversationPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Conversation = ConversationPeer::doSelect($c);
    
    if (count($Conversation) >= 1) {
      $this ->Conversation = $Conversation[0];
      return true;
    } else {
      $this ->Conversation = new Conversation();
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
      $name = "ConversationPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Conversation = ConversationPeer::doSelect($c);
    
    if (count($Conversation) >= 1) {
      $this ->Conversation = $Conversation[0];
      return true;
    } else {
      $this ->Conversation = new Conversation();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>