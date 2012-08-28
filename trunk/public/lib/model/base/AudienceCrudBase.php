<?php
       
   class AudienceCrudBase extends Utils_PageWidget { 
   
    var $Audience;
   
       var $audience_id;
   var $audience_paid_status;
   var $fk_user_id;
   var $fk_screening_id;
   var $fk_screening_unique_key;
   var $fk_payment_id;
   var $audience_invite_code;
   var $audience_ip_addr;
   var $audience_created_at;
   var $audience_updated_at;
   var $audience_status;
   var $audience_username;
   var $audience_review;
   var $audience_review_status;
   var $audience_short_url;
   var $audience_ticket_price;
   var $audience_hmac_key;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getAudienceId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Audience = AudiencePeer::retrieveByPK( $id );
    } else {
      $this ->Audience = new Audience;
    }
  }
  
  function hydrate( $id ) {
      $this ->Audience = AudiencePeer::retrieveByPK( $id );
  }
  
  function getAudienceId() {
    if (($this ->postVar("audience_id")) || ($this ->postVar("audience_id") === "")) {
      return $this ->postVar("audience_id");
    } elseif (($this ->getVar("audience_id")) || ($this ->getVar("audience_id") === "")) {
      return $this ->getVar("audience_id");
    } elseif (($this ->Audience) || ($this ->Audience === "")){
      return $this ->Audience -> getAudienceId();
    } elseif (($this ->sessionVar("audience_id")) || ($this ->sessionVar("audience_id") == "")) {
      return $this ->sessionVar("audience_id");
    } else {
      return false;
    }
  }
  
  function setAudienceId( $str ) {
    $this ->Audience -> setAudienceId( $str );
  }
  
  function getAudiencePaidStatus() {
    if (($this ->postVar("audience_paid_status")) || ($this ->postVar("audience_paid_status") === "")) {
      return $this ->postVar("audience_paid_status");
    } elseif (($this ->getVar("audience_paid_status")) || ($this ->getVar("audience_paid_status") === "")) {
      return $this ->getVar("audience_paid_status");
    } elseif (($this ->Audience) || ($this ->Audience === "")){
      return $this ->Audience -> getAudiencePaidStatus();
    } elseif (($this ->sessionVar("audience_paid_status")) || ($this ->sessionVar("audience_paid_status") == "")) {
      return $this ->sessionVar("audience_paid_status");
    } else {
      return false;
    }
  }
  
  function setAudiencePaidStatus( $str ) {
    $this ->Audience -> setAudiencePaidStatus( $str );
  }
  
  function getFkUserId() {
    if (($this ->postVar("fk_user_id")) || ($this ->postVar("fk_user_id") === "")) {
      return $this ->postVar("fk_user_id");
    } elseif (($this ->getVar("fk_user_id")) || ($this ->getVar("fk_user_id") === "")) {
      return $this ->getVar("fk_user_id");
    } elseif (($this ->Audience) || ($this ->Audience === "")){
      return $this ->Audience -> getFkUserId();
    } elseif (($this ->sessionVar("fk_user_id")) || ($this ->sessionVar("fk_user_id") == "")) {
      return $this ->sessionVar("fk_user_id");
    } else {
      return false;
    }
  }
  
  function setFkUserId( $str ) {
    $this ->Audience -> setFkUserId( $str );
  }
  
  function getFkScreeningId() {
    if (($this ->postVar("fk_screening_id")) || ($this ->postVar("fk_screening_id") === "")) {
      return $this ->postVar("fk_screening_id");
    } elseif (($this ->getVar("fk_screening_id")) || ($this ->getVar("fk_screening_id") === "")) {
      return $this ->getVar("fk_screening_id");
    } elseif (($this ->Audience) || ($this ->Audience === "")){
      return $this ->Audience -> getFkScreeningId();
    } elseif (($this ->sessionVar("fk_screening_id")) || ($this ->sessionVar("fk_screening_id") == "")) {
      return $this ->sessionVar("fk_screening_id");
    } else {
      return false;
    }
  }
  
  function setFkScreeningId( $str ) {
    $this ->Audience -> setFkScreeningId( $str );
  }
  
  function getFkScreeningUniqueKey() {
    if (($this ->postVar("fk_screening_unique_key")) || ($this ->postVar("fk_screening_unique_key") === "")) {
      return $this ->postVar("fk_screening_unique_key");
    } elseif (($this ->getVar("fk_screening_unique_key")) || ($this ->getVar("fk_screening_unique_key") === "")) {
      return $this ->getVar("fk_screening_unique_key");
    } elseif (($this ->Audience) || ($this ->Audience === "")){
      return $this ->Audience -> getFkScreeningUniqueKey();
    } elseif (($this ->sessionVar("fk_screening_unique_key")) || ($this ->sessionVar("fk_screening_unique_key") == "")) {
      return $this ->sessionVar("fk_screening_unique_key");
    } else {
      return false;
    }
  }
  
  function setFkScreeningUniqueKey( $str ) {
    $this ->Audience -> setFkScreeningUniqueKey( $str );
  }
  
  function getFkPaymentId() {
    if (($this ->postVar("fk_payment_id")) || ($this ->postVar("fk_payment_id") === "")) {
      return $this ->postVar("fk_payment_id");
    } elseif (($this ->getVar("fk_payment_id")) || ($this ->getVar("fk_payment_id") === "")) {
      return $this ->getVar("fk_payment_id");
    } elseif (($this ->Audience) || ($this ->Audience === "")){
      return $this ->Audience -> getFkPaymentId();
    } elseif (($this ->sessionVar("fk_payment_id")) || ($this ->sessionVar("fk_payment_id") == "")) {
      return $this ->sessionVar("fk_payment_id");
    } else {
      return false;
    }
  }
  
  function setFkPaymentId( $str ) {
    $this ->Audience -> setFkPaymentId( $str );
  }
  
  function getAudienceInviteCode() {
    if (($this ->postVar("audience_invite_code")) || ($this ->postVar("audience_invite_code") === "")) {
      return $this ->postVar("audience_invite_code");
    } elseif (($this ->getVar("audience_invite_code")) || ($this ->getVar("audience_invite_code") === "")) {
      return $this ->getVar("audience_invite_code");
    } elseif (($this ->Audience) || ($this ->Audience === "")){
      return $this ->Audience -> getAudienceInviteCode();
    } elseif (($this ->sessionVar("audience_invite_code")) || ($this ->sessionVar("audience_invite_code") == "")) {
      return $this ->sessionVar("audience_invite_code");
    } else {
      return false;
    }
  }
  
  function setAudienceInviteCode( $str ) {
    $this ->Audience -> setAudienceInviteCode( $str );
  }
  
  function getAudienceIpAddr() {
    if (($this ->postVar("audience_ip_addr")) || ($this ->postVar("audience_ip_addr") === "")) {
      return $this ->postVar("audience_ip_addr");
    } elseif (($this ->getVar("audience_ip_addr")) || ($this ->getVar("audience_ip_addr") === "")) {
      return $this ->getVar("audience_ip_addr");
    } elseif (($this ->Audience) || ($this ->Audience === "")){
      return $this ->Audience -> getAudienceIpAddr();
    } elseif (($this ->sessionVar("audience_ip_addr")) || ($this ->sessionVar("audience_ip_addr") == "")) {
      return $this ->sessionVar("audience_ip_addr");
    } else {
      return false;
    }
  }
  
  function setAudienceIpAddr( $str ) {
    $this ->Audience -> setAudienceIpAddr( $str );
  }
  
  function getAudienceCreatedAt() {
    if (($this ->postVar("audience_created_at")) || ($this ->postVar("audience_created_at") === "")) {
      return $this ->postVar("audience_created_at");
    } elseif (($this ->getVar("audience_created_at")) || ($this ->getVar("audience_created_at") === "")) {
      return $this ->getVar("audience_created_at");
    } elseif (($this ->Audience) || ($this ->Audience === "")){
      return $this ->Audience -> getAudienceCreatedAt();
    } elseif (($this ->sessionVar("audience_created_at")) || ($this ->sessionVar("audience_created_at") == "")) {
      return $this ->sessionVar("audience_created_at");
    } else {
      return false;
    }
  }
  
  function setAudienceCreatedAt( $str ) {
    $this ->Audience -> setAudienceCreatedAt( $str );
  }
  
  function getAudienceUpdatedAt() {
    if (($this ->postVar("audience_updated_at")) || ($this ->postVar("audience_updated_at") === "")) {
      return $this ->postVar("audience_updated_at");
    } elseif (($this ->getVar("audience_updated_at")) || ($this ->getVar("audience_updated_at") === "")) {
      return $this ->getVar("audience_updated_at");
    } elseif (($this ->Audience) || ($this ->Audience === "")){
      return $this ->Audience -> getAudienceUpdatedAt();
    } elseif (($this ->sessionVar("audience_updated_at")) || ($this ->sessionVar("audience_updated_at") == "")) {
      return $this ->sessionVar("audience_updated_at");
    } else {
      return false;
    }
  }
  
  function setAudienceUpdatedAt( $str ) {
    $this ->Audience -> setAudienceUpdatedAt( $str );
  }
  
  function getAudienceStatus() {
    if (($this ->postVar("audience_status")) || ($this ->postVar("audience_status") === "")) {
      return $this ->postVar("audience_status");
    } elseif (($this ->getVar("audience_status")) || ($this ->getVar("audience_status") === "")) {
      return $this ->getVar("audience_status");
    } elseif (($this ->Audience) || ($this ->Audience === "")){
      return $this ->Audience -> getAudienceStatus();
    } elseif (($this ->sessionVar("audience_status")) || ($this ->sessionVar("audience_status") == "")) {
      return $this ->sessionVar("audience_status");
    } else {
      return false;
    }
  }
  
  function setAudienceStatus( $str ) {
    $this ->Audience -> setAudienceStatus( $str );
  }
  
  function getAudienceUsername() {
    if (($this ->postVar("audience_username")) || ($this ->postVar("audience_username") === "")) {
      return $this ->postVar("audience_username");
    } elseif (($this ->getVar("audience_username")) || ($this ->getVar("audience_username") === "")) {
      return $this ->getVar("audience_username");
    } elseif (($this ->Audience) || ($this ->Audience === "")){
      return $this ->Audience -> getAudienceUsername();
    } elseif (($this ->sessionVar("audience_username")) || ($this ->sessionVar("audience_username") == "")) {
      return $this ->sessionVar("audience_username");
    } else {
      return false;
    }
  }
  
  function setAudienceUsername( $str ) {
    $this ->Audience -> setAudienceUsername( $str );
  }
  
  function getAudienceReview() {
    if (($this ->postVar("audience_review")) || ($this ->postVar("audience_review") === "")) {
      return $this ->postVar("audience_review");
    } elseif (($this ->getVar("audience_review")) || ($this ->getVar("audience_review") === "")) {
      return $this ->getVar("audience_review");
    } elseif (($this ->Audience) || ($this ->Audience === "")){
      return $this ->Audience -> getAudienceReview();
    } elseif (($this ->sessionVar("audience_review")) || ($this ->sessionVar("audience_review") == "")) {
      return $this ->sessionVar("audience_review");
    } else {
      return false;
    }
  }
  
  function setAudienceReview( $str ) {
    $this ->Audience -> setAudienceReview( $str );
  }
  
  function getAudienceReviewStatus() {
    if (($this ->postVar("audience_review_status")) || ($this ->postVar("audience_review_status") === "")) {
      return $this ->postVar("audience_review_status");
    } elseif (($this ->getVar("audience_review_status")) || ($this ->getVar("audience_review_status") === "")) {
      return $this ->getVar("audience_review_status");
    } elseif (($this ->Audience) || ($this ->Audience === "")){
      return $this ->Audience -> getAudienceReviewStatus();
    } elseif (($this ->sessionVar("audience_review_status")) || ($this ->sessionVar("audience_review_status") == "")) {
      return $this ->sessionVar("audience_review_status");
    } else {
      return false;
    }
  }
  
  function setAudienceReviewStatus( $str ) {
    $this ->Audience -> setAudienceReviewStatus( $str );
  }
  
  function getAudienceShortUrl() {
    if (($this ->postVar("audience_short_url")) || ($this ->postVar("audience_short_url") === "")) {
      return $this ->postVar("audience_short_url");
    } elseif (($this ->getVar("audience_short_url")) || ($this ->getVar("audience_short_url") === "")) {
      return $this ->getVar("audience_short_url");
    } elseif (($this ->Audience) || ($this ->Audience === "")){
      return $this ->Audience -> getAudienceShortUrl();
    } elseif (($this ->sessionVar("audience_short_url")) || ($this ->sessionVar("audience_short_url") == "")) {
      return $this ->sessionVar("audience_short_url");
    } else {
      return false;
    }
  }
  
  function setAudienceShortUrl( $str ) {
    $this ->Audience -> setAudienceShortUrl( $str );
  }
  
  function getAudienceTicketPrice() {
    if (($this ->postVar("audience_ticket_price")) || ($this ->postVar("audience_ticket_price") === "")) {
      return $this ->postVar("audience_ticket_price");
    } elseif (($this ->getVar("audience_ticket_price")) || ($this ->getVar("audience_ticket_price") === "")) {
      return $this ->getVar("audience_ticket_price");
    } elseif (($this ->Audience) || ($this ->Audience === "")){
      return $this ->Audience -> getAudienceTicketPrice();
    } elseif (($this ->sessionVar("audience_ticket_price")) || ($this ->sessionVar("audience_ticket_price") == "")) {
      return $this ->sessionVar("audience_ticket_price");
    } else {
      return false;
    }
  }
  
  function setAudienceTicketPrice( $str ) {
    $this ->Audience -> setAudienceTicketPrice( $str );
  }
  
  function getAudienceHmacKey() {
    if (($this ->postVar("audience_hmac_key")) || ($this ->postVar("audience_hmac_key") === "")) {
      return $this ->postVar("audience_hmac_key");
    } elseif (($this ->getVar("audience_hmac_key")) || ($this ->getVar("audience_hmac_key") === "")) {
      return $this ->getVar("audience_hmac_key");
    } elseif (($this ->Audience) || ($this ->Audience === "")){
      return $this ->Audience -> getAudienceHmacKey();
    } elseif (($this ->sessionVar("audience_hmac_key")) || ($this ->sessionVar("audience_hmac_key") == "")) {
      return $this ->sessionVar("audience_hmac_key");
    } else {
      return false;
    }
  }
  
  function setAudienceHmacKey( $str ) {
    $this ->Audience -> setAudienceHmacKey( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Audience = AudiencePeer::retrieveByPK( $id );
    }
    
    if ($this ->Audience ) {
       
    	       (is_numeric(WTVRcleanString($this ->Audience->getAudienceId()))) ? $itemarray["audience_id"] = WTVRcleanString($this ->Audience->getAudienceId()) : null;
          (WTVRcleanString($this ->Audience->getAudiencePaidStatus())) ? $itemarray["audience_paid_status"] = WTVRcleanString($this ->Audience->getAudiencePaidStatus()) : null;
          (is_numeric(WTVRcleanString($this ->Audience->getFkUserId()))) ? $itemarray["fk_user_id"] = WTVRcleanString($this ->Audience->getFkUserId()) : null;
          (is_numeric(WTVRcleanString($this ->Audience->getFkScreeningId()))) ? $itemarray["fk_screening_id"] = WTVRcleanString($this ->Audience->getFkScreeningId()) : null;
          (WTVRcleanString($this ->Audience->getFkScreeningUniqueKey())) ? $itemarray["fk_screening_unique_key"] = WTVRcleanString($this ->Audience->getFkScreeningUniqueKey()) : null;
          (is_numeric(WTVRcleanString($this ->Audience->getFkPaymentId()))) ? $itemarray["fk_payment_id"] = WTVRcleanString($this ->Audience->getFkPaymentId()) : null;
          (WTVRcleanString($this ->Audience->getAudienceInviteCode())) ? $itemarray["audience_invite_code"] = WTVRcleanString($this ->Audience->getAudienceInviteCode()) : null;
          (WTVRcleanString($this ->Audience->getAudienceIpAddr())) ? $itemarray["audience_ip_addr"] = WTVRcleanString($this ->Audience->getAudienceIpAddr()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Audience->getAudienceCreatedAt())) ? $itemarray["audience_created_at"] = formatDate($this ->Audience->getAudienceCreatedAt('%Y-%m-%d %T'),"TS") : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Audience->getAudienceUpdatedAt())) ? $itemarray["audience_updated_at"] = formatDate($this ->Audience->getAudienceUpdatedAt('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->Audience->getAudienceStatus())) ? $itemarray["audience_status"] = WTVRcleanString($this ->Audience->getAudienceStatus()) : null;
          (WTVRcleanString($this ->Audience->getAudienceUsername())) ? $itemarray["audience_username"] = WTVRcleanString($this ->Audience->getAudienceUsername()) : null;
          (WTVRcleanString($this ->Audience->getAudienceReview())) ? $itemarray["audience_review"] = WTVRcleanString($this ->Audience->getAudienceReview()) : null;
          (WTVRcleanString($this ->Audience->getAudienceReviewStatus())) ? $itemarray["audience_review_status"] = WTVRcleanString($this ->Audience->getAudienceReviewStatus()) : null;
          (WTVRcleanString($this ->Audience->getAudienceShortUrl())) ? $itemarray["audience_short_url"] = WTVRcleanString($this ->Audience->getAudienceShortUrl()) : null;
          (is_numeric(WTVRcleanString($this ->Audience->getAudienceTicketPrice()))) ? $itemarray["audience_ticket_price"] = sprintf("%01.2f",WTVRcleanString($this ->Audience->getAudienceTicketPrice())) : null;
          (WTVRcleanString($this ->Audience->getAudienceHmacKey())) ? $itemarray["audience_hmac_key"] = WTVRcleanString($this ->Audience->getAudienceHmacKey()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Audience = AudiencePeer::retrieveByPK( $id );
    } elseif (! $this ->Audience) {
      $this ->Audience = new Audience;
    }
        
  	 ($this -> getAudienceId())? $this ->Audience->setAudienceId( WTVRcleanString( $this -> getAudienceId()) ) : null;
    ($this -> getAudiencePaidStatus())? $this ->Audience->setAudiencePaidStatus( WTVRcleanString( $this -> getAudiencePaidStatus()) ) : null;
    ($this -> getFkUserId())? $this ->Audience->setFkUserId( WTVRcleanString( $this -> getFkUserId()) ) : null;
    ($this -> getFkScreeningId())? $this ->Audience->setFkScreeningId( WTVRcleanString( $this -> getFkScreeningId()) ) : null;
    ($this -> getFkScreeningUniqueKey())? $this ->Audience->setFkScreeningUniqueKey( WTVRcleanString( $this -> getFkScreeningUniqueKey()) ) : null;
    ($this -> getFkPaymentId())? $this ->Audience->setFkPaymentId( WTVRcleanString( $this -> getFkPaymentId()) ) : null;
    ($this -> getAudienceInviteCode())? $this ->Audience->setAudienceInviteCode( WTVRcleanString( $this -> getAudienceInviteCode()) ) : null;
    ($this -> getAudienceIpAddr())? $this ->Audience->setAudienceIpAddr( WTVRcleanString( $this -> getAudienceIpAddr()) ) : null;
          if (is_valid_date( $this ->Audience->getAudienceCreatedAt())) {
        $this ->Audience->setAudienceCreatedAt( formatDate($this -> getAudienceCreatedAt(), "TS" ));
      } else {
      $Audienceaudience_created_at = $this -> sfDateTime( "audience_created_at" );
      ( $Audienceaudience_created_at != "01/01/1900 00:00:00" )? $this ->Audience->setAudienceCreatedAt( formatDate($Audienceaudience_created_at, "TS" )) : $this ->Audience->setAudienceCreatedAt( null );
      }
          if (is_valid_date( $this ->Audience->getAudienceUpdatedAt())) {
        $this ->Audience->setAudienceUpdatedAt( formatDate($this -> getAudienceUpdatedAt(), "TS" ));
      } else {
      $Audienceaudience_updated_at = $this -> sfDateTime( "audience_updated_at" );
      ( $Audienceaudience_updated_at != "01/01/1900 00:00:00" )? $this ->Audience->setAudienceUpdatedAt( formatDate($Audienceaudience_updated_at, "TS" )) : $this ->Audience->setAudienceUpdatedAt( null );
      }
    ($this -> getAudienceStatus())? $this ->Audience->setAudienceStatus( WTVRcleanString( $this -> getAudienceStatus()) ) : null;
    ($this -> getAudienceUsername())? $this ->Audience->setAudienceUsername( WTVRcleanString( $this -> getAudienceUsername()) ) : null;
    ($this -> getAudienceReview())? $this ->Audience->setAudienceReview( WTVRcleanString( $this -> getAudienceReview()) ) : null;
    ($this -> getAudienceReviewStatus())? $this ->Audience->setAudienceReviewStatus( WTVRcleanString( $this -> getAudienceReviewStatus()) ) : null;
    ($this -> getAudienceShortUrl())? $this ->Audience->setAudienceShortUrl( WTVRcleanString( $this -> getAudienceShortUrl()) ) : null;
          (is_numeric($this ->getAudienceTicketPrice())) ? $this ->Audience->setAudienceTicketPrice( (float) $this -> getAudienceTicketPrice() ) : null;
    ($this -> getAudienceHmacKey())? $this ->Audience->setAudienceHmacKey( WTVRcleanString( $this -> getAudienceHmacKey()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Audience ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Audience = AudiencePeer::retrieveByPK($id);
    }
    
    if (! $this ->Audience ) {
      return;
    }
    
    $this ->Audience -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Audience_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "AudiencePeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Audience = AudiencePeer::doSelect($c);
    
    if (count($Audience) >= 1) {
      $this ->Audience = $Audience[0];
      return true;
    } else {
      $this ->Audience = new Audience();
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
      $name = "AudiencePeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Audience = AudiencePeer::doSelect($c);
    
    if (count($Audience) >= 1) {
      $this ->Audience = $Audience[0];
      return true;
    } else {
      $this ->Audience = new Audience();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>