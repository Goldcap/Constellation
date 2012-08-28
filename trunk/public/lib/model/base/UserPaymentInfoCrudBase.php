<?php
       
   class UserPaymentInfoCrudBase extends Utils_PageWidget { 
   
    var $UserPaymentInfo;
   
       var $user_payment_info_id;
   var $fk_user_id;
   var $user_payment_info_first_name;
   var $user_payment_info_last_name;
   var $user_payment_info_cc_type;
   var $user_payment_info_cc_number;
   var $user_payment_info_security_code;
   var $user_payment_info_expiration_month;
   var $user_payment_info_expiration_year;
   var $user_payment_info_b_address_1;
   var $user_payment_info_b_address_2;
   var $user_payment_info_b_city;
   var $user_payment_info_b_state;
   var $user_payment_info_b_zip;
   var $user_payment_info_paypal_email_address;
   var $user_payment_info_created_at;
   var $user_payment_info_updated_at;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getUserPaymentInfoId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->UserPaymentInfo = UserPaymentInfoPeer::retrieveByPK( $id );
    } else {
      $this ->UserPaymentInfo = new UserPaymentInfo;
    }
  }
  
  function hydrate( $id ) {
      $this ->UserPaymentInfo = UserPaymentInfoPeer::retrieveByPK( $id );
  }
  
  function getUserPaymentInfoId() {
    if (($this ->postVar("user_payment_info_id")) || ($this ->postVar("user_payment_info_id") === "")) {
      return $this ->postVar("user_payment_info_id");
    } elseif (($this ->getVar("user_payment_info_id")) || ($this ->getVar("user_payment_info_id") === "")) {
      return $this ->getVar("user_payment_info_id");
    } elseif (($this ->UserPaymentInfo) || ($this ->UserPaymentInfo === "")){
      return $this ->UserPaymentInfo -> getUserPaymentInfoId();
    } elseif (($this ->sessionVar("user_payment_info_id")) || ($this ->sessionVar("user_payment_info_id") == "")) {
      return $this ->sessionVar("user_payment_info_id");
    } else {
      return false;
    }
  }
  
  function setUserPaymentInfoId( $str ) {
    $this ->UserPaymentInfo -> setUserPaymentInfoId( $str );
  }
  
  function getFkUserId() {
    if (($this ->postVar("fk_user_id")) || ($this ->postVar("fk_user_id") === "")) {
      return $this ->postVar("fk_user_id");
    } elseif (($this ->getVar("fk_user_id")) || ($this ->getVar("fk_user_id") === "")) {
      return $this ->getVar("fk_user_id");
    } elseif (($this ->UserPaymentInfo) || ($this ->UserPaymentInfo === "")){
      return $this ->UserPaymentInfo -> getFkUserId();
    } elseif (($this ->sessionVar("fk_user_id")) || ($this ->sessionVar("fk_user_id") == "")) {
      return $this ->sessionVar("fk_user_id");
    } else {
      return false;
    }
  }
  
  function setFkUserId( $str ) {
    $this ->UserPaymentInfo -> setFkUserId( $str );
  }
  
  function getUserPaymentInfoFirstName() {
    if (($this ->postVar("user_payment_info_first_name")) || ($this ->postVar("user_payment_info_first_name") === "")) {
      return $this ->postVar("user_payment_info_first_name");
    } elseif (($this ->getVar("user_payment_info_first_name")) || ($this ->getVar("user_payment_info_first_name") === "")) {
      return $this ->getVar("user_payment_info_first_name");
    } elseif (($this ->UserPaymentInfo) || ($this ->UserPaymentInfo === "")){
      return $this ->UserPaymentInfo -> getUserPaymentInfoFirstName();
    } elseif (($this ->sessionVar("user_payment_info_first_name")) || ($this ->sessionVar("user_payment_info_first_name") == "")) {
      return $this ->sessionVar("user_payment_info_first_name");
    } else {
      return false;
    }
  }
  
  function setUserPaymentInfoFirstName( $str ) {
    $this ->UserPaymentInfo -> setUserPaymentInfoFirstName( $str );
  }
  
  function getUserPaymentInfoLastName() {
    if (($this ->postVar("user_payment_info_last_name")) || ($this ->postVar("user_payment_info_last_name") === "")) {
      return $this ->postVar("user_payment_info_last_name");
    } elseif (($this ->getVar("user_payment_info_last_name")) || ($this ->getVar("user_payment_info_last_name") === "")) {
      return $this ->getVar("user_payment_info_last_name");
    } elseif (($this ->UserPaymentInfo) || ($this ->UserPaymentInfo === "")){
      return $this ->UserPaymentInfo -> getUserPaymentInfoLastName();
    } elseif (($this ->sessionVar("user_payment_info_last_name")) || ($this ->sessionVar("user_payment_info_last_name") == "")) {
      return $this ->sessionVar("user_payment_info_last_name");
    } else {
      return false;
    }
  }
  
  function setUserPaymentInfoLastName( $str ) {
    $this ->UserPaymentInfo -> setUserPaymentInfoLastName( $str );
  }
  
  function getUserPaymentInfoCcType() {
    if (($this ->postVar("user_payment_info_cc_type")) || ($this ->postVar("user_payment_info_cc_type") === "")) {
      return $this ->postVar("user_payment_info_cc_type");
    } elseif (($this ->getVar("user_payment_info_cc_type")) || ($this ->getVar("user_payment_info_cc_type") === "")) {
      return $this ->getVar("user_payment_info_cc_type");
    } elseif (($this ->UserPaymentInfo) || ($this ->UserPaymentInfo === "")){
      return $this ->UserPaymentInfo -> getUserPaymentInfoCcType();
    } elseif (($this ->sessionVar("user_payment_info_cc_type")) || ($this ->sessionVar("user_payment_info_cc_type") == "")) {
      return $this ->sessionVar("user_payment_info_cc_type");
    } else {
      return false;
    }
  }
  
  function setUserPaymentInfoCcType( $str ) {
    $this ->UserPaymentInfo -> setUserPaymentInfoCcType( $str );
  }
  
  function getUserPaymentInfoCcNumber() {
    if (($this ->postVar("user_payment_info_cc_number")) || ($this ->postVar("user_payment_info_cc_number") === "")) {
      return $this ->postVar("user_payment_info_cc_number");
    } elseif (($this ->getVar("user_payment_info_cc_number")) || ($this ->getVar("user_payment_info_cc_number") === "")) {
      return $this ->getVar("user_payment_info_cc_number");
    } elseif (($this ->UserPaymentInfo) || ($this ->UserPaymentInfo === "")){
      return $this ->UserPaymentInfo -> getUserPaymentInfoCcNumber();
    } elseif (($this ->sessionVar("user_payment_info_cc_number")) || ($this ->sessionVar("user_payment_info_cc_number") == "")) {
      return $this ->sessionVar("user_payment_info_cc_number");
    } else {
      return false;
    }
  }
  
  function setUserPaymentInfoCcNumber( $str ) {
    $this ->UserPaymentInfo -> setUserPaymentInfoCcNumber( $str );
  }
  
  function getUserPaymentInfoSecurityCode() {
    if (($this ->postVar("user_payment_info_security_code")) || ($this ->postVar("user_payment_info_security_code") === "")) {
      return $this ->postVar("user_payment_info_security_code");
    } elseif (($this ->getVar("user_payment_info_security_code")) || ($this ->getVar("user_payment_info_security_code") === "")) {
      return $this ->getVar("user_payment_info_security_code");
    } elseif (($this ->UserPaymentInfo) || ($this ->UserPaymentInfo === "")){
      return $this ->UserPaymentInfo -> getUserPaymentInfoSecurityCode();
    } elseif (($this ->sessionVar("user_payment_info_security_code")) || ($this ->sessionVar("user_payment_info_security_code") == "")) {
      return $this ->sessionVar("user_payment_info_security_code");
    } else {
      return false;
    }
  }
  
  function setUserPaymentInfoSecurityCode( $str ) {
    $this ->UserPaymentInfo -> setUserPaymentInfoSecurityCode( $str );
  }
  
  function getUserPaymentInfoExpirationMonth() {
    if (($this ->postVar("user_payment_info_expiration_month")) || ($this ->postVar("user_payment_info_expiration_month") === "")) {
      return $this ->postVar("user_payment_info_expiration_month");
    } elseif (($this ->getVar("user_payment_info_expiration_month")) || ($this ->getVar("user_payment_info_expiration_month") === "")) {
      return $this ->getVar("user_payment_info_expiration_month");
    } elseif (($this ->UserPaymentInfo) || ($this ->UserPaymentInfo === "")){
      return $this ->UserPaymentInfo -> getUserPaymentInfoExpirationMonth();
    } elseif (($this ->sessionVar("user_payment_info_expiration_month")) || ($this ->sessionVar("user_payment_info_expiration_month") == "")) {
      return $this ->sessionVar("user_payment_info_expiration_month");
    } else {
      return false;
    }
  }
  
  function setUserPaymentInfoExpirationMonth( $str ) {
    $this ->UserPaymentInfo -> setUserPaymentInfoExpirationMonth( $str );
  }
  
  function getUserPaymentInfoExpirationYear() {
    if (($this ->postVar("user_payment_info_expiration_year")) || ($this ->postVar("user_payment_info_expiration_year") === "")) {
      return $this ->postVar("user_payment_info_expiration_year");
    } elseif (($this ->getVar("user_payment_info_expiration_year")) || ($this ->getVar("user_payment_info_expiration_year") === "")) {
      return $this ->getVar("user_payment_info_expiration_year");
    } elseif (($this ->UserPaymentInfo) || ($this ->UserPaymentInfo === "")){
      return $this ->UserPaymentInfo -> getUserPaymentInfoExpirationYear();
    } elseif (($this ->sessionVar("user_payment_info_expiration_year")) || ($this ->sessionVar("user_payment_info_expiration_year") == "")) {
      return $this ->sessionVar("user_payment_info_expiration_year");
    } else {
      return false;
    }
  }
  
  function setUserPaymentInfoExpirationYear( $str ) {
    $this ->UserPaymentInfo -> setUserPaymentInfoExpirationYear( $str );
  }
  
  function getUserPaymentInfoBAddress1() {
    if (($this ->postVar("user_payment_info_b_address_1")) || ($this ->postVar("user_payment_info_b_address_1") === "")) {
      return $this ->postVar("user_payment_info_b_address_1");
    } elseif (($this ->getVar("user_payment_info_b_address_1")) || ($this ->getVar("user_payment_info_b_address_1") === "")) {
      return $this ->getVar("user_payment_info_b_address_1");
    } elseif (($this ->UserPaymentInfo) || ($this ->UserPaymentInfo === "")){
      return $this ->UserPaymentInfo -> getUserPaymentInfoBAddress1();
    } elseif (($this ->sessionVar("user_payment_info_b_address_1")) || ($this ->sessionVar("user_payment_info_b_address_1") == "")) {
      return $this ->sessionVar("user_payment_info_b_address_1");
    } else {
      return false;
    }
  }
  
  function setUserPaymentInfoBAddress1( $str ) {
    $this ->UserPaymentInfo -> setUserPaymentInfoBAddress1( $str );
  }
  
  function getUserPaymentInfoBAddress2() {
    if (($this ->postVar("user_payment_info_b_address_2")) || ($this ->postVar("user_payment_info_b_address_2") === "")) {
      return $this ->postVar("user_payment_info_b_address_2");
    } elseif (($this ->getVar("user_payment_info_b_address_2")) || ($this ->getVar("user_payment_info_b_address_2") === "")) {
      return $this ->getVar("user_payment_info_b_address_2");
    } elseif (($this ->UserPaymentInfo) || ($this ->UserPaymentInfo === "")){
      return $this ->UserPaymentInfo -> getUserPaymentInfoBAddress2();
    } elseif (($this ->sessionVar("user_payment_info_b_address_2")) || ($this ->sessionVar("user_payment_info_b_address_2") == "")) {
      return $this ->sessionVar("user_payment_info_b_address_2");
    } else {
      return false;
    }
  }
  
  function setUserPaymentInfoBAddress2( $str ) {
    $this ->UserPaymentInfo -> setUserPaymentInfoBAddress2( $str );
  }
  
  function getUserPaymentInfoBCity() {
    if (($this ->postVar("user_payment_info_b_city")) || ($this ->postVar("user_payment_info_b_city") === "")) {
      return $this ->postVar("user_payment_info_b_city");
    } elseif (($this ->getVar("user_payment_info_b_city")) || ($this ->getVar("user_payment_info_b_city") === "")) {
      return $this ->getVar("user_payment_info_b_city");
    } elseif (($this ->UserPaymentInfo) || ($this ->UserPaymentInfo === "")){
      return $this ->UserPaymentInfo -> getUserPaymentInfoBCity();
    } elseif (($this ->sessionVar("user_payment_info_b_city")) || ($this ->sessionVar("user_payment_info_b_city") == "")) {
      return $this ->sessionVar("user_payment_info_b_city");
    } else {
      return false;
    }
  }
  
  function setUserPaymentInfoBCity( $str ) {
    $this ->UserPaymentInfo -> setUserPaymentInfoBCity( $str );
  }
  
  function getUserPaymentInfoBState() {
    if (($this ->postVar("user_payment_info_b_state")) || ($this ->postVar("user_payment_info_b_state") === "")) {
      return $this ->postVar("user_payment_info_b_state");
    } elseif (($this ->getVar("user_payment_info_b_state")) || ($this ->getVar("user_payment_info_b_state") === "")) {
      return $this ->getVar("user_payment_info_b_state");
    } elseif (($this ->UserPaymentInfo) || ($this ->UserPaymentInfo === "")){
      return $this ->UserPaymentInfo -> getUserPaymentInfoBState();
    } elseif (($this ->sessionVar("user_payment_info_b_state")) || ($this ->sessionVar("user_payment_info_b_state") == "")) {
      return $this ->sessionVar("user_payment_info_b_state");
    } else {
      return false;
    }
  }
  
  function setUserPaymentInfoBState( $str ) {
    $this ->UserPaymentInfo -> setUserPaymentInfoBState( $str );
  }
  
  function getUserPaymentInfoBZip() {
    if (($this ->postVar("user_payment_info_b_zip")) || ($this ->postVar("user_payment_info_b_zip") === "")) {
      return $this ->postVar("user_payment_info_b_zip");
    } elseif (($this ->getVar("user_payment_info_b_zip")) || ($this ->getVar("user_payment_info_b_zip") === "")) {
      return $this ->getVar("user_payment_info_b_zip");
    } elseif (($this ->UserPaymentInfo) || ($this ->UserPaymentInfo === "")){
      return $this ->UserPaymentInfo -> getUserPaymentInfoBZip();
    } elseif (($this ->sessionVar("user_payment_info_b_zip")) || ($this ->sessionVar("user_payment_info_b_zip") == "")) {
      return $this ->sessionVar("user_payment_info_b_zip");
    } else {
      return false;
    }
  }
  
  function setUserPaymentInfoBZip( $str ) {
    $this ->UserPaymentInfo -> setUserPaymentInfoBZip( $str );
  }
  
  function getUserPaymentInfoPaypalEmailAddress() {
    if (($this ->postVar("user_payment_info_paypal_email_address")) || ($this ->postVar("user_payment_info_paypal_email_address") === "")) {
      return $this ->postVar("user_payment_info_paypal_email_address");
    } elseif (($this ->getVar("user_payment_info_paypal_email_address")) || ($this ->getVar("user_payment_info_paypal_email_address") === "")) {
      return $this ->getVar("user_payment_info_paypal_email_address");
    } elseif (($this ->UserPaymentInfo) || ($this ->UserPaymentInfo === "")){
      return $this ->UserPaymentInfo -> getUserPaymentInfoPaypalEmailAddress();
    } elseif (($this ->sessionVar("user_payment_info_paypal_email_address")) || ($this ->sessionVar("user_payment_info_paypal_email_address") == "")) {
      return $this ->sessionVar("user_payment_info_paypal_email_address");
    } else {
      return false;
    }
  }
  
  function setUserPaymentInfoPaypalEmailAddress( $str ) {
    $this ->UserPaymentInfo -> setUserPaymentInfoPaypalEmailAddress( $str );
  }
  
  function getUserPaymentInfoCreatedAt() {
    if (($this ->postVar("user_payment_info_created_at")) || ($this ->postVar("user_payment_info_created_at") === "")) {
      return $this ->postVar("user_payment_info_created_at");
    } elseif (($this ->getVar("user_payment_info_created_at")) || ($this ->getVar("user_payment_info_created_at") === "")) {
      return $this ->getVar("user_payment_info_created_at");
    } elseif (($this ->UserPaymentInfo) || ($this ->UserPaymentInfo === "")){
      return $this ->UserPaymentInfo -> getUserPaymentInfoCreatedAt();
    } elseif (($this ->sessionVar("user_payment_info_created_at")) || ($this ->sessionVar("user_payment_info_created_at") == "")) {
      return $this ->sessionVar("user_payment_info_created_at");
    } else {
      return false;
    }
  }
  
  function setUserPaymentInfoCreatedAt( $str ) {
    $this ->UserPaymentInfo -> setUserPaymentInfoCreatedAt( $str );
  }
  
  function getUserPaymentInfoUpdatedAt() {
    if (($this ->postVar("user_payment_info_updated_at")) || ($this ->postVar("user_payment_info_updated_at") === "")) {
      return $this ->postVar("user_payment_info_updated_at");
    } elseif (($this ->getVar("user_payment_info_updated_at")) || ($this ->getVar("user_payment_info_updated_at") === "")) {
      return $this ->getVar("user_payment_info_updated_at");
    } elseif (($this ->UserPaymentInfo) || ($this ->UserPaymentInfo === "")){
      return $this ->UserPaymentInfo -> getUserPaymentInfoUpdatedAt();
    } elseif (($this ->sessionVar("user_payment_info_updated_at")) || ($this ->sessionVar("user_payment_info_updated_at") == "")) {
      return $this ->sessionVar("user_payment_info_updated_at");
    } else {
      return false;
    }
  }
  
  function setUserPaymentInfoUpdatedAt( $str ) {
    $this ->UserPaymentInfo -> setUserPaymentInfoUpdatedAt( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->UserPaymentInfo = UserPaymentInfoPeer::retrieveByPK( $id );
    }
    
    if ($this ->UserPaymentInfo ) {
       
    	       (is_numeric(WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoId()))) ? $itemarray["user_payment_info_id"] = WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoId()) : null;
          (is_numeric(WTVRcleanString($this ->UserPaymentInfo->getFkUserId()))) ? $itemarray["fk_user_id"] = WTVRcleanString($this ->UserPaymentInfo->getFkUserId()) : null;
          (WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoFirstName())) ? $itemarray["user_payment_info_first_name"] = WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoFirstName()) : null;
          (WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoLastName())) ? $itemarray["user_payment_info_last_name"] = WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoLastName()) : null;
          (WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoCcType())) ? $itemarray["user_payment_info_cc_type"] = WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoCcType()) : null;
          (WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoCcNumber())) ? $itemarray["user_payment_info_cc_number"] = WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoCcNumber()) : null;
          (WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoSecurityCode())) ? $itemarray["user_payment_info_security_code"] = WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoSecurityCode()) : null;
          (WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoExpirationMonth())) ? $itemarray["user_payment_info_expiration_month"] = WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoExpirationMonth()) : null;
          (WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoExpirationYear())) ? $itemarray["user_payment_info_expiration_year"] = WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoExpirationYear()) : null;
          (WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoBAddress1())) ? $itemarray["user_payment_info_b_address_1"] = WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoBAddress1()) : null;
          (WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoBAddress2())) ? $itemarray["user_payment_info_b_address_2"] = WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoBAddress2()) : null;
          (WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoBCity())) ? $itemarray["user_payment_info_b_city"] = WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoBCity()) : null;
          (WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoBState())) ? $itemarray["user_payment_info_b_state"] = WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoBState()) : null;
          (WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoBZip())) ? $itemarray["user_payment_info_b_zip"] = WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoBZip()) : null;
          (WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoPaypalEmailAddress())) ? $itemarray["user_payment_info_paypal_email_address"] = WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoPaypalEmailAddress()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoCreatedAt())) ? $itemarray["user_payment_info_created_at"] = formatDate($this ->UserPaymentInfo->getUserPaymentInfoCreatedAt('%Y-%m-%d %T'),"TS") : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->UserPaymentInfo->getUserPaymentInfoUpdatedAt())) ? $itemarray["user_payment_info_updated_at"] = formatDate($this ->UserPaymentInfo->getUserPaymentInfoUpdatedAt('%Y-%m-%d %T'),"TS") : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->UserPaymentInfo = UserPaymentInfoPeer::retrieveByPK( $id );
    } elseif (! $this ->UserPaymentInfo) {
      $this ->UserPaymentInfo = new UserPaymentInfo;
    }
        
  	 ($this -> getUserPaymentInfoId())? $this ->UserPaymentInfo->setUserPaymentInfoId( WTVRcleanString( $this -> getUserPaymentInfoId()) ) : null;
    ($this -> getFkUserId())? $this ->UserPaymentInfo->setFkUserId( WTVRcleanString( $this -> getFkUserId()) ) : null;
    ($this -> getUserPaymentInfoFirstName())? $this ->UserPaymentInfo->setUserPaymentInfoFirstName( WTVRcleanString( $this -> getUserPaymentInfoFirstName()) ) : null;
    ($this -> getUserPaymentInfoLastName())? $this ->UserPaymentInfo->setUserPaymentInfoLastName( WTVRcleanString( $this -> getUserPaymentInfoLastName()) ) : null;
    ($this -> getUserPaymentInfoCcType())? $this ->UserPaymentInfo->setUserPaymentInfoCcType( WTVRcleanString( $this -> getUserPaymentInfoCcType()) ) : null;
    ($this -> getUserPaymentInfoCcNumber())? $this ->UserPaymentInfo->setUserPaymentInfoCcNumber( WTVRcleanString( $this -> getUserPaymentInfoCcNumber()) ) : null;
    ($this -> getUserPaymentInfoSecurityCode())? $this ->UserPaymentInfo->setUserPaymentInfoSecurityCode( WTVRcleanString( $this -> getUserPaymentInfoSecurityCode()) ) : null;
    ($this -> getUserPaymentInfoExpirationMonth())? $this ->UserPaymentInfo->setUserPaymentInfoExpirationMonth( WTVRcleanString( $this -> getUserPaymentInfoExpirationMonth()) ) : null;
    ($this -> getUserPaymentInfoExpirationYear())? $this ->UserPaymentInfo->setUserPaymentInfoExpirationYear( WTVRcleanString( $this -> getUserPaymentInfoExpirationYear()) ) : null;
    ($this -> getUserPaymentInfoBAddress1())? $this ->UserPaymentInfo->setUserPaymentInfoBAddress1( WTVRcleanString( $this -> getUserPaymentInfoBAddress1()) ) : null;
    ($this -> getUserPaymentInfoBAddress2())? $this ->UserPaymentInfo->setUserPaymentInfoBAddress2( WTVRcleanString( $this -> getUserPaymentInfoBAddress2()) ) : null;
    ($this -> getUserPaymentInfoBCity())? $this ->UserPaymentInfo->setUserPaymentInfoBCity( WTVRcleanString( $this -> getUserPaymentInfoBCity()) ) : null;
    ($this -> getUserPaymentInfoBState())? $this ->UserPaymentInfo->setUserPaymentInfoBState( WTVRcleanString( $this -> getUserPaymentInfoBState()) ) : null;
    ($this -> getUserPaymentInfoBZip())? $this ->UserPaymentInfo->setUserPaymentInfoBZip( WTVRcleanString( $this -> getUserPaymentInfoBZip()) ) : null;
    ($this -> getUserPaymentInfoPaypalEmailAddress())? $this ->UserPaymentInfo->setUserPaymentInfoPaypalEmailAddress( WTVRcleanString( $this -> getUserPaymentInfoPaypalEmailAddress()) ) : null;
          if (is_valid_date( $this ->UserPaymentInfo->getUserPaymentInfoCreatedAt())) {
        $this ->UserPaymentInfo->setUserPaymentInfoCreatedAt( formatDate($this -> getUserPaymentInfoCreatedAt(), "TS" ));
      } else {
      $UserPaymentInfouser_payment_info_created_at = $this -> sfDateTime( "user_payment_info_created_at" );
      ( $UserPaymentInfouser_payment_info_created_at != "01/01/1900 00:00:00" )? $this ->UserPaymentInfo->setUserPaymentInfoCreatedAt( formatDate($UserPaymentInfouser_payment_info_created_at, "TS" )) : $this ->UserPaymentInfo->setUserPaymentInfoCreatedAt( null );
      }
          if (is_valid_date( $this ->UserPaymentInfo->getUserPaymentInfoUpdatedAt())) {
        $this ->UserPaymentInfo->setUserPaymentInfoUpdatedAt( formatDate($this -> getUserPaymentInfoUpdatedAt(), "TS" ));
      } else {
      $UserPaymentInfouser_payment_info_updated_at = $this -> sfDateTime( "user_payment_info_updated_at" );
      ( $UserPaymentInfouser_payment_info_updated_at != "01/01/1900 00:00:00" )? $this ->UserPaymentInfo->setUserPaymentInfoUpdatedAt( formatDate($UserPaymentInfouser_payment_info_updated_at, "TS" )) : $this ->UserPaymentInfo->setUserPaymentInfoUpdatedAt( null );
      }
          
    $this -> save();
     
  }
  
  function save() {
    $this ->UserPaymentInfo ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->UserPaymentInfo = UserPaymentInfoPeer::retrieveByPK($id);
    }
    
    if (! $this ->UserPaymentInfo ) {
      return;
    }
    
    $this ->UserPaymentInfo -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('UserPaymentInfo_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "UserPaymentInfoPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $UserPaymentInfo = UserPaymentInfoPeer::doSelect($c);
    
    if (count($UserPaymentInfo) >= 1) {
      $this ->UserPaymentInfo = $UserPaymentInfo[0];
      return true;
    } else {
      $this ->UserPaymentInfo = new UserPaymentInfo();
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
      $name = "UserPaymentInfoPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $UserPaymentInfo = UserPaymentInfoPeer::doSelect($c);
    
    if (count($UserPaymentInfo) >= 1) {
      $this ->UserPaymentInfo = $UserPaymentInfo[0];
      return true;
    } else {
      $this ->UserPaymentInfo = new UserPaymentInfo();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>