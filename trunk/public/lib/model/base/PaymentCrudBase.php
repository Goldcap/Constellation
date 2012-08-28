<?php
       
   class PaymentCrudBase extends Utils_PageWidget { 
   
    var $Payment;
   
       var $payment_id;
   var $fk_film_id;
   var $fk_screening_id;
   var $fk_screening_name;
   var $fk_audience_id;
   var $fk_subscription_id;
   var $fk_user_id;
   var $payment_unique_code;
   var $payment_type;
   var $payment_first_name;
   var $payment_last_name;
   var $payment_email;
   var $payment_b_addr_1;
   var $payment_b_addr_2;
   var $payment_b_city;
   var $payment_b_state;
   var $payment_b_zipcode;
   var $payment_b_country;
   var $payment_status;
   var $payment_amount;
   var $payment_description;
   var $payment_card_type;
   var $payment_last_four_CC_digits;
   var $payment_cvv2;
   var $payment_cc_exp;
   var $payment_created_at;
   var $payment_updated_at;
   var $payment_ip;
   var $payment_site_id;
   var $payment_transaction_id;
   var $payment_fraud_score;
   var $payment_maxmind_object;
   var $payment_order_processor;
   var $payment_invites;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getPaymentId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Payment = PaymentPeer::retrieveByPK( $id );
    } else {
      $this ->Payment = new Payment;
    }
  }
  
  function hydrate( $id ) {
      $this ->Payment = PaymentPeer::retrieveByPK( $id );
  }
  
  function getPaymentId() {
    if (($this ->postVar("payment_id")) || ($this ->postVar("payment_id") === "")) {
      return $this ->postVar("payment_id");
    } elseif (($this ->getVar("payment_id")) || ($this ->getVar("payment_id") === "")) {
      return $this ->getVar("payment_id");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentId();
    } elseif (($this ->sessionVar("payment_id")) || ($this ->sessionVar("payment_id") == "")) {
      return $this ->sessionVar("payment_id");
    } else {
      return false;
    }
  }
  
  function setPaymentId( $str ) {
    $this ->Payment -> setPaymentId( $str );
  }
  
  function getFkFilmId() {
    if (($this ->postVar("fk_film_id")) || ($this ->postVar("fk_film_id") === "")) {
      return $this ->postVar("fk_film_id");
    } elseif (($this ->getVar("fk_film_id")) || ($this ->getVar("fk_film_id") === "")) {
      return $this ->getVar("fk_film_id");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getFkFilmId();
    } elseif (($this ->sessionVar("fk_film_id")) || ($this ->sessionVar("fk_film_id") == "")) {
      return $this ->sessionVar("fk_film_id");
    } else {
      return false;
    }
  }
  
  function setFkFilmId( $str ) {
    $this ->Payment -> setFkFilmId( $str );
  }
  
  function getFkScreeningId() {
    if (($this ->postVar("fk_screening_id")) || ($this ->postVar("fk_screening_id") === "")) {
      return $this ->postVar("fk_screening_id");
    } elseif (($this ->getVar("fk_screening_id")) || ($this ->getVar("fk_screening_id") === "")) {
      return $this ->getVar("fk_screening_id");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getFkScreeningId();
    } elseif (($this ->sessionVar("fk_screening_id")) || ($this ->sessionVar("fk_screening_id") == "")) {
      return $this ->sessionVar("fk_screening_id");
    } else {
      return false;
    }
  }
  
  function setFkScreeningId( $str ) {
    $this ->Payment -> setFkScreeningId( $str );
  }
  
  function getFkScreeningName() {
    if (($this ->postVar("fk_screening_name")) || ($this ->postVar("fk_screening_name") === "")) {
      return $this ->postVar("fk_screening_name");
    } elseif (($this ->getVar("fk_screening_name")) || ($this ->getVar("fk_screening_name") === "")) {
      return $this ->getVar("fk_screening_name");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getFkScreeningName();
    } elseif (($this ->sessionVar("fk_screening_name")) || ($this ->sessionVar("fk_screening_name") == "")) {
      return $this ->sessionVar("fk_screening_name");
    } else {
      return false;
    }
  }
  
  function setFkScreeningName( $str ) {
    $this ->Payment -> setFkScreeningName( $str );
  }
  
  function getFkAudienceId() {
    if (($this ->postVar("fk_audience_id")) || ($this ->postVar("fk_audience_id") === "")) {
      return $this ->postVar("fk_audience_id");
    } elseif (($this ->getVar("fk_audience_id")) || ($this ->getVar("fk_audience_id") === "")) {
      return $this ->getVar("fk_audience_id");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getFkAudienceId();
    } elseif (($this ->sessionVar("fk_audience_id")) || ($this ->sessionVar("fk_audience_id") == "")) {
      return $this ->sessionVar("fk_audience_id");
    } else {
      return false;
    }
  }
  
  function setFkAudienceId( $str ) {
    $this ->Payment -> setFkAudienceId( $str );
  }
  
  function getFkSubscriptionId() {
    if (($this ->postVar("fk_subscription_id")) || ($this ->postVar("fk_subscription_id") === "")) {
      return $this ->postVar("fk_subscription_id");
    } elseif (($this ->getVar("fk_subscription_id")) || ($this ->getVar("fk_subscription_id") === "")) {
      return $this ->getVar("fk_subscription_id");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getFkSubscriptionId();
    } elseif (($this ->sessionVar("fk_subscription_id")) || ($this ->sessionVar("fk_subscription_id") == "")) {
      return $this ->sessionVar("fk_subscription_id");
    } else {
      return false;
    }
  }
  
  function setFkSubscriptionId( $str ) {
    $this ->Payment -> setFkSubscriptionId( $str );
  }
  
  function getFkUserId() {
    if (($this ->postVar("fk_user_id")) || ($this ->postVar("fk_user_id") === "")) {
      return $this ->postVar("fk_user_id");
    } elseif (($this ->getVar("fk_user_id")) || ($this ->getVar("fk_user_id") === "")) {
      return $this ->getVar("fk_user_id");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getFkUserId();
    } elseif (($this ->sessionVar("fk_user_id")) || ($this ->sessionVar("fk_user_id") == "")) {
      return $this ->sessionVar("fk_user_id");
    } else {
      return false;
    }
  }
  
  function setFkUserId( $str ) {
    $this ->Payment -> setFkUserId( $str );
  }
  
  function getPaymentUniqueCode() {
    if (($this ->postVar("payment_unique_code")) || ($this ->postVar("payment_unique_code") === "")) {
      return $this ->postVar("payment_unique_code");
    } elseif (($this ->getVar("payment_unique_code")) || ($this ->getVar("payment_unique_code") === "")) {
      return $this ->getVar("payment_unique_code");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentUniqueCode();
    } elseif (($this ->sessionVar("payment_unique_code")) || ($this ->sessionVar("payment_unique_code") == "")) {
      return $this ->sessionVar("payment_unique_code");
    } else {
      return false;
    }
  }
  
  function setPaymentUniqueCode( $str ) {
    $this ->Payment -> setPaymentUniqueCode( $str );
  }
  
  function getPaymentType() {
    if (($this ->postVar("payment_type")) || ($this ->postVar("payment_type") === "")) {
      return $this ->postVar("payment_type");
    } elseif (($this ->getVar("payment_type")) || ($this ->getVar("payment_type") === "")) {
      return $this ->getVar("payment_type");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentType();
    } elseif (($this ->sessionVar("payment_type")) || ($this ->sessionVar("payment_type") == "")) {
      return $this ->sessionVar("payment_type");
    } else {
      return false;
    }
  }
  
  function setPaymentType( $str ) {
    $this ->Payment -> setPaymentType( $str );
  }
  
  function getPaymentFirstName() {
    if (($this ->postVar("payment_first_name")) || ($this ->postVar("payment_first_name") === "")) {
      return $this ->postVar("payment_first_name");
    } elseif (($this ->getVar("payment_first_name")) || ($this ->getVar("payment_first_name") === "")) {
      return $this ->getVar("payment_first_name");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentFirstName();
    } elseif (($this ->sessionVar("payment_first_name")) || ($this ->sessionVar("payment_first_name") == "")) {
      return $this ->sessionVar("payment_first_name");
    } else {
      return false;
    }
  }
  
  function setPaymentFirstName( $str ) {
    $this ->Payment -> setPaymentFirstName( $str );
  }
  
  function getPaymentLastName() {
    if (($this ->postVar("payment_last_name")) || ($this ->postVar("payment_last_name") === "")) {
      return $this ->postVar("payment_last_name");
    } elseif (($this ->getVar("payment_last_name")) || ($this ->getVar("payment_last_name") === "")) {
      return $this ->getVar("payment_last_name");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentLastName();
    } elseif (($this ->sessionVar("payment_last_name")) || ($this ->sessionVar("payment_last_name") == "")) {
      return $this ->sessionVar("payment_last_name");
    } else {
      return false;
    }
  }
  
  function setPaymentLastName( $str ) {
    $this ->Payment -> setPaymentLastName( $str );
  }
  
  function getPaymentEmail() {
    if (($this ->postVar("payment_email")) || ($this ->postVar("payment_email") === "")) {
      return $this ->postVar("payment_email");
    } elseif (($this ->getVar("payment_email")) || ($this ->getVar("payment_email") === "")) {
      return $this ->getVar("payment_email");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentEmail();
    } elseif (($this ->sessionVar("payment_email")) || ($this ->sessionVar("payment_email") == "")) {
      return $this ->sessionVar("payment_email");
    } else {
      return false;
    }
  }
  
  function setPaymentEmail( $str ) {
    $this ->Payment -> setPaymentEmail( $str );
  }
  
  function getPaymentBAddr1() {
    if (($this ->postVar("payment_b_addr_1")) || ($this ->postVar("payment_b_addr_1") === "")) {
      return $this ->postVar("payment_b_addr_1");
    } elseif (($this ->getVar("payment_b_addr_1")) || ($this ->getVar("payment_b_addr_1") === "")) {
      return $this ->getVar("payment_b_addr_1");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentBAddr1();
    } elseif (($this ->sessionVar("payment_b_addr_1")) || ($this ->sessionVar("payment_b_addr_1") == "")) {
      return $this ->sessionVar("payment_b_addr_1");
    } else {
      return false;
    }
  }
  
  function setPaymentBAddr1( $str ) {
    $this ->Payment -> setPaymentBAddr1( $str );
  }
  
  function getPaymentBAddr2() {
    if (($this ->postVar("payment_b_addr_2")) || ($this ->postVar("payment_b_addr_2") === "")) {
      return $this ->postVar("payment_b_addr_2");
    } elseif (($this ->getVar("payment_b_addr_2")) || ($this ->getVar("payment_b_addr_2") === "")) {
      return $this ->getVar("payment_b_addr_2");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentBAddr2();
    } elseif (($this ->sessionVar("payment_b_addr_2")) || ($this ->sessionVar("payment_b_addr_2") == "")) {
      return $this ->sessionVar("payment_b_addr_2");
    } else {
      return false;
    }
  }
  
  function setPaymentBAddr2( $str ) {
    $this ->Payment -> setPaymentBAddr2( $str );
  }
  
  function getPaymentBCity() {
    if (($this ->postVar("payment_b_city")) || ($this ->postVar("payment_b_city") === "")) {
      return $this ->postVar("payment_b_city");
    } elseif (($this ->getVar("payment_b_city")) || ($this ->getVar("payment_b_city") === "")) {
      return $this ->getVar("payment_b_city");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentBCity();
    } elseif (($this ->sessionVar("payment_b_city")) || ($this ->sessionVar("payment_b_city") == "")) {
      return $this ->sessionVar("payment_b_city");
    } else {
      return false;
    }
  }
  
  function setPaymentBCity( $str ) {
    $this ->Payment -> setPaymentBCity( $str );
  }
  
  function getPaymentBState() {
    if (($this ->postVar("payment_b_state")) || ($this ->postVar("payment_b_state") === "")) {
      return $this ->postVar("payment_b_state");
    } elseif (($this ->getVar("payment_b_state")) || ($this ->getVar("payment_b_state") === "")) {
      return $this ->getVar("payment_b_state");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentBState();
    } elseif (($this ->sessionVar("payment_b_state")) || ($this ->sessionVar("payment_b_state") == "")) {
      return $this ->sessionVar("payment_b_state");
    } else {
      return false;
    }
  }
  
  function setPaymentBState( $str ) {
    $this ->Payment -> setPaymentBState( $str );
  }
  
  function getPaymentBZipcode() {
    if (($this ->postVar("payment_b_zipcode")) || ($this ->postVar("payment_b_zipcode") === "")) {
      return $this ->postVar("payment_b_zipcode");
    } elseif (($this ->getVar("payment_b_zipcode")) || ($this ->getVar("payment_b_zipcode") === "")) {
      return $this ->getVar("payment_b_zipcode");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentBZipcode();
    } elseif (($this ->sessionVar("payment_b_zipcode")) || ($this ->sessionVar("payment_b_zipcode") == "")) {
      return $this ->sessionVar("payment_b_zipcode");
    } else {
      return false;
    }
  }
  
  function setPaymentBZipcode( $str ) {
    $this ->Payment -> setPaymentBZipcode( $str );
  }
  
  function getPaymentBCountry() {
    if (($this ->postVar("payment_b_country")) || ($this ->postVar("payment_b_country") === "")) {
      return $this ->postVar("payment_b_country");
    } elseif (($this ->getVar("payment_b_country")) || ($this ->getVar("payment_b_country") === "")) {
      return $this ->getVar("payment_b_country");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentBCountry();
    } elseif (($this ->sessionVar("payment_b_country")) || ($this ->sessionVar("payment_b_country") == "")) {
      return $this ->sessionVar("payment_b_country");
    } else {
      return false;
    }
  }
  
  function setPaymentBCountry( $str ) {
    $this ->Payment -> setPaymentBCountry( $str );
  }
  
  function getPaymentStatus() {
    if (($this ->postVar("payment_status")) || ($this ->postVar("payment_status") === "")) {
      return $this ->postVar("payment_status");
    } elseif (($this ->getVar("payment_status")) || ($this ->getVar("payment_status") === "")) {
      return $this ->getVar("payment_status");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentStatus();
    } elseif (($this ->sessionVar("payment_status")) || ($this ->sessionVar("payment_status") == "")) {
      return $this ->sessionVar("payment_status");
    } else {
      return false;
    }
  }
  
  function setPaymentStatus( $str ) {
    $this ->Payment -> setPaymentStatus( $str );
  }
  
  function getPaymentAmount() {
    if (($this ->postVar("payment_amount")) || ($this ->postVar("payment_amount") === "")) {
      return $this ->postVar("payment_amount");
    } elseif (($this ->getVar("payment_amount")) || ($this ->getVar("payment_amount") === "")) {
      return $this ->getVar("payment_amount");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentAmount();
    } elseif (($this ->sessionVar("payment_amount")) || ($this ->sessionVar("payment_amount") == "")) {
      return $this ->sessionVar("payment_amount");
    } else {
      return false;
    }
  }
  
  function setPaymentAmount( $str ) {
    $this ->Payment -> setPaymentAmount( $str );
  }
  
  function getPaymentDescription() {
    if (($this ->postVar("payment_description")) || ($this ->postVar("payment_description") === "")) {
      return $this ->postVar("payment_description");
    } elseif (($this ->getVar("payment_description")) || ($this ->getVar("payment_description") === "")) {
      return $this ->getVar("payment_description");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentDescription();
    } elseif (($this ->sessionVar("payment_description")) || ($this ->sessionVar("payment_description") == "")) {
      return $this ->sessionVar("payment_description");
    } else {
      return false;
    }
  }
  
  function setPaymentDescription( $str ) {
    $this ->Payment -> setPaymentDescription( $str );
  }
  
  function getPaymentCardType() {
    if (($this ->postVar("payment_card_type")) || ($this ->postVar("payment_card_type") === "")) {
      return $this ->postVar("payment_card_type");
    } elseif (($this ->getVar("payment_card_type")) || ($this ->getVar("payment_card_type") === "")) {
      return $this ->getVar("payment_card_type");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentCardType();
    } elseif (($this ->sessionVar("payment_card_type")) || ($this ->sessionVar("payment_card_type") == "")) {
      return $this ->sessionVar("payment_card_type");
    } else {
      return false;
    }
  }
  
  function setPaymentCardType( $str ) {
    $this ->Payment -> setPaymentCardType( $str );
  }
  
  function getPaymentLastFourCCDigits() {
    if (($this ->postVar("payment_last_four_CC_digits")) || ($this ->postVar("payment_last_four_CC_digits") === "")) {
      return $this ->postVar("payment_last_four_CC_digits");
    } elseif (($this ->getVar("payment_last_four_CC_digits")) || ($this ->getVar("payment_last_four_CC_digits") === "")) {
      return $this ->getVar("payment_last_four_CC_digits");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentLastFourCCDigits();
    } elseif (($this ->sessionVar("payment_last_four_CC_digits")) || ($this ->sessionVar("payment_last_four_CC_digits") == "")) {
      return $this ->sessionVar("payment_last_four_CC_digits");
    } else {
      return false;
    }
  }
  
  function setPaymentLastFourCCDigits( $str ) {
    $this ->Payment -> setPaymentLastFourCCDigits( $str );
  }
  
  function getPaymentCvv2() {
    if (($this ->postVar("payment_cvv2")) || ($this ->postVar("payment_cvv2") === "")) {
      return $this ->postVar("payment_cvv2");
    } elseif (($this ->getVar("payment_cvv2")) || ($this ->getVar("payment_cvv2") === "")) {
      return $this ->getVar("payment_cvv2");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentCvv2();
    } elseif (($this ->sessionVar("payment_cvv2")) || ($this ->sessionVar("payment_cvv2") == "")) {
      return $this ->sessionVar("payment_cvv2");
    } else {
      return false;
    }
  }
  
  function setPaymentCvv2( $str ) {
    $this ->Payment -> setPaymentCvv2( $str );
  }
  
  function getPaymentCcExp() {
    if (($this ->postVar("payment_cc_exp")) || ($this ->postVar("payment_cc_exp") === "")) {
      return $this ->postVar("payment_cc_exp");
    } elseif (($this ->getVar("payment_cc_exp")) || ($this ->getVar("payment_cc_exp") === "")) {
      return $this ->getVar("payment_cc_exp");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentCcExp();
    } elseif (($this ->sessionVar("payment_cc_exp")) || ($this ->sessionVar("payment_cc_exp") == "")) {
      return $this ->sessionVar("payment_cc_exp");
    } else {
      return false;
    }
  }
  
  function setPaymentCcExp( $str ) {
    $this ->Payment -> setPaymentCcExp( $str );
  }
  
  function getPaymentCreatedAt() {
    if (($this ->postVar("payment_created_at")) || ($this ->postVar("payment_created_at") === "")) {
      return $this ->postVar("payment_created_at");
    } elseif (($this ->getVar("payment_created_at")) || ($this ->getVar("payment_created_at") === "")) {
      return $this ->getVar("payment_created_at");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentCreatedAt();
    } elseif (($this ->sessionVar("payment_created_at")) || ($this ->sessionVar("payment_created_at") == "")) {
      return $this ->sessionVar("payment_created_at");
    } else {
      return false;
    }
  }
  
  function setPaymentCreatedAt( $str ) {
    $this ->Payment -> setPaymentCreatedAt( $str );
  }
  
  function getPaymentUpdatedAt() {
    if (($this ->postVar("payment_updated_at")) || ($this ->postVar("payment_updated_at") === "")) {
      return $this ->postVar("payment_updated_at");
    } elseif (($this ->getVar("payment_updated_at")) || ($this ->getVar("payment_updated_at") === "")) {
      return $this ->getVar("payment_updated_at");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentUpdatedAt();
    } elseif (($this ->sessionVar("payment_updated_at")) || ($this ->sessionVar("payment_updated_at") == "")) {
      return $this ->sessionVar("payment_updated_at");
    } else {
      return false;
    }
  }
  
  function setPaymentUpdatedAt( $str ) {
    $this ->Payment -> setPaymentUpdatedAt( $str );
  }
  
  function getPaymentIp() {
    if (($this ->postVar("payment_ip")) || ($this ->postVar("payment_ip") === "")) {
      return $this ->postVar("payment_ip");
    } elseif (($this ->getVar("payment_ip")) || ($this ->getVar("payment_ip") === "")) {
      return $this ->getVar("payment_ip");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentIp();
    } elseif (($this ->sessionVar("payment_ip")) || ($this ->sessionVar("payment_ip") == "")) {
      return $this ->sessionVar("payment_ip");
    } else {
      return false;
    }
  }
  
  function setPaymentIp( $str ) {
    $this ->Payment -> setPaymentIp( $str );
  }
  
  function getPaymentSiteId() {
    if (($this ->postVar("payment_site_id")) || ($this ->postVar("payment_site_id") === "")) {
      return $this ->postVar("payment_site_id");
    } elseif (($this ->getVar("payment_site_id")) || ($this ->getVar("payment_site_id") === "")) {
      return $this ->getVar("payment_site_id");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentSiteId();
    } elseif (($this ->sessionVar("payment_site_id")) || ($this ->sessionVar("payment_site_id") == "")) {
      return $this ->sessionVar("payment_site_id");
    } else {
      return false;
    }
  }
  
  function setPaymentSiteId( $str ) {
    $this ->Payment -> setPaymentSiteId( $str );
  }
  
  function getPaymentTransactionId() {
    if (($this ->postVar("payment_transaction_id")) || ($this ->postVar("payment_transaction_id") === "")) {
      return $this ->postVar("payment_transaction_id");
    } elseif (($this ->getVar("payment_transaction_id")) || ($this ->getVar("payment_transaction_id") === "")) {
      return $this ->getVar("payment_transaction_id");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentTransactionId();
    } elseif (($this ->sessionVar("payment_transaction_id")) || ($this ->sessionVar("payment_transaction_id") == "")) {
      return $this ->sessionVar("payment_transaction_id");
    } else {
      return false;
    }
  }
  
  function setPaymentTransactionId( $str ) {
    $this ->Payment -> setPaymentTransactionId( $str );
  }
  
  function getPaymentFraudScore() {
    if (($this ->postVar("payment_fraud_score")) || ($this ->postVar("payment_fraud_score") === "")) {
      return $this ->postVar("payment_fraud_score");
    } elseif (($this ->getVar("payment_fraud_score")) || ($this ->getVar("payment_fraud_score") === "")) {
      return $this ->getVar("payment_fraud_score");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentFraudScore();
    } elseif (($this ->sessionVar("payment_fraud_score")) || ($this ->sessionVar("payment_fraud_score") == "")) {
      return $this ->sessionVar("payment_fraud_score");
    } else {
      return false;
    }
  }
  
  function setPaymentFraudScore( $str ) {
    $this ->Payment -> setPaymentFraudScore( $str );
  }
  
  function getPaymentMaxmindObject() {
    if (($this ->postVar("payment_maxmind_object")) || ($this ->postVar("payment_maxmind_object") === "")) {
      return $this ->postVar("payment_maxmind_object");
    } elseif (($this ->getVar("payment_maxmind_object")) || ($this ->getVar("payment_maxmind_object") === "")) {
      return $this ->getVar("payment_maxmind_object");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentMaxmindObject();
    } elseif (($this ->sessionVar("payment_maxmind_object")) || ($this ->sessionVar("payment_maxmind_object") == "")) {
      return $this ->sessionVar("payment_maxmind_object");
    } else {
      return false;
    }
  }
  
  function setPaymentMaxmindObject( $str ) {
    $this ->Payment -> setPaymentMaxmindObject( $str );
  }
  
  function getPaymentOrderProcessor() {
    if (($this ->postVar("payment_order_processor")) || ($this ->postVar("payment_order_processor") === "")) {
      return $this ->postVar("payment_order_processor");
    } elseif (($this ->getVar("payment_order_processor")) || ($this ->getVar("payment_order_processor") === "")) {
      return $this ->getVar("payment_order_processor");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentOrderProcessor();
    } elseif (($this ->sessionVar("payment_order_processor")) || ($this ->sessionVar("payment_order_processor") == "")) {
      return $this ->sessionVar("payment_order_processor");
    } else {
      return false;
    }
  }
  
  function setPaymentOrderProcessor( $str ) {
    $this ->Payment -> setPaymentOrderProcessor( $str );
  }
  
  function getPaymentInvites() {
    if (($this ->postVar("payment_invites")) || ($this ->postVar("payment_invites") === "")) {
      return $this ->postVar("payment_invites");
    } elseif (($this ->getVar("payment_invites")) || ($this ->getVar("payment_invites") === "")) {
      return $this ->getVar("payment_invites");
    } elseif (($this ->Payment) || ($this ->Payment === "")){
      return $this ->Payment -> getPaymentInvites();
    } elseif (($this ->sessionVar("payment_invites")) || ($this ->sessionVar("payment_invites") == "")) {
      return $this ->sessionVar("payment_invites");
    } else {
      return false;
    }
  }
  
  function setPaymentInvites( $str ) {
    $this ->Payment -> setPaymentInvites( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Payment = PaymentPeer::retrieveByPK( $id );
    }
    
    if ($this ->Payment ) {
       
    	       (is_numeric(WTVRcleanString($this ->Payment->getPaymentId()))) ? $itemarray["payment_id"] = WTVRcleanString($this ->Payment->getPaymentId()) : null;
          (is_numeric(WTVRcleanString($this ->Payment->getFkFilmId()))) ? $itemarray["fk_film_id"] = WTVRcleanString($this ->Payment->getFkFilmId()) : null;
          (is_numeric(WTVRcleanString($this ->Payment->getFkScreeningId()))) ? $itemarray["fk_screening_id"] = WTVRcleanString($this ->Payment->getFkScreeningId()) : null;
          (WTVRcleanString($this ->Payment->getFkScreeningName())) ? $itemarray["fk_screening_name"] = WTVRcleanString($this ->Payment->getFkScreeningName()) : null;
          (is_numeric(WTVRcleanString($this ->Payment->getFkAudienceId()))) ? $itemarray["fk_audience_id"] = WTVRcleanString($this ->Payment->getFkAudienceId()) : null;
          (is_numeric(WTVRcleanString($this ->Payment->getFkSubscriptionId()))) ? $itemarray["fk_subscription_id"] = WTVRcleanString($this ->Payment->getFkSubscriptionId()) : null;
          (is_numeric(WTVRcleanString($this ->Payment->getFkUserId()))) ? $itemarray["fk_user_id"] = WTVRcleanString($this ->Payment->getFkUserId()) : null;
          (WTVRcleanString($this ->Payment->getPaymentUniqueCode())) ? $itemarray["payment_unique_code"] = WTVRcleanString($this ->Payment->getPaymentUniqueCode()) : null;
          (WTVRcleanString($this ->Payment->getPaymentType())) ? $itemarray["payment_type"] = WTVRcleanString($this ->Payment->getPaymentType()) : null;
          (WTVRcleanString($this ->Payment->getPaymentFirstName())) ? $itemarray["payment_first_name"] = WTVRcleanString($this ->Payment->getPaymentFirstName()) : null;
          (WTVRcleanString($this ->Payment->getPaymentLastName())) ? $itemarray["payment_last_name"] = WTVRcleanString($this ->Payment->getPaymentLastName()) : null;
          (WTVRcleanString($this ->Payment->getPaymentEmail())) ? $itemarray["payment_email"] = WTVRcleanString($this ->Payment->getPaymentEmail()) : null;
          (WTVRcleanString($this ->Payment->getPaymentBAddr1())) ? $itemarray["payment_b_addr_1"] = WTVRcleanString($this ->Payment->getPaymentBAddr1()) : null;
          (WTVRcleanString($this ->Payment->getPaymentBAddr2())) ? $itemarray["payment_b_addr_2"] = WTVRcleanString($this ->Payment->getPaymentBAddr2()) : null;
          (WTVRcleanString($this ->Payment->getPaymentBCity())) ? $itemarray["payment_b_city"] = WTVRcleanString($this ->Payment->getPaymentBCity()) : null;
          (WTVRcleanString($this ->Payment->getPaymentBState())) ? $itemarray["payment_b_state"] = WTVRcleanString($this ->Payment->getPaymentBState()) : null;
          (WTVRcleanString($this ->Payment->getPaymentBZipcode())) ? $itemarray["payment_b_zipcode"] = WTVRcleanString($this ->Payment->getPaymentBZipcode()) : null;
          (WTVRcleanString($this ->Payment->getPaymentBCountry())) ? $itemarray["payment_b_country"] = WTVRcleanString($this ->Payment->getPaymentBCountry()) : null;
          (WTVRcleanString($this ->Payment->getPaymentStatus())) ? $itemarray["payment_status"] = WTVRcleanString($this ->Payment->getPaymentStatus()) : null;
          (WTVRcleanString($this ->Payment->getPaymentAmount())) ? $itemarray["payment_amount"] = WTVRcleanString($this ->Payment->getPaymentAmount()) : null;
          (WTVRcleanString($this ->Payment->getPaymentDescription())) ? $itemarray["payment_description"] = WTVRcleanString($this ->Payment->getPaymentDescription()) : null;
          (WTVRcleanString($this ->Payment->getPaymentCardType())) ? $itemarray["payment_card_type"] = WTVRcleanString($this ->Payment->getPaymentCardType()) : null;
          (WTVRcleanString($this ->Payment->getPaymentLastFourCCDigits())) ? $itemarray["payment_last_four_CC_digits"] = WTVRcleanString($this ->Payment->getPaymentLastFourCCDigits()) : null;
          (WTVRcleanString($this ->Payment->getPaymentCvv2())) ? $itemarray["payment_cvv2"] = WTVRcleanString($this ->Payment->getPaymentCvv2()) : null;
          (WTVRcleanString($this ->Payment->getPaymentCcExp())) ? $itemarray["payment_cc_exp"] = WTVRcleanString($this ->Payment->getPaymentCcExp()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Payment->getPaymentCreatedAt())) ? $itemarray["payment_created_at"] = formatDate($this ->Payment->getPaymentCreatedAt('%Y-%m-%d %T'),"TS") : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Payment->getPaymentUpdatedAt())) ? $itemarray["payment_updated_at"] = formatDate($this ->Payment->getPaymentUpdatedAt('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->Payment->getPaymentIp())) ? $itemarray["payment_ip"] = WTVRcleanString($this ->Payment->getPaymentIp()) : null;
          (WTVRcleanString($this ->Payment->getPaymentSiteId())) ? $itemarray["payment_site_id"] = WTVRcleanString($this ->Payment->getPaymentSiteId()) : null;
          (WTVRcleanString($this ->Payment->getPaymentTransactionId())) ? $itemarray["payment_transaction_id"] = WTVRcleanString($this ->Payment->getPaymentTransactionId()) : null;
          (is_numeric(WTVRcleanString($this ->Payment->getPaymentFraudScore()))) ? $itemarray["payment_fraud_score"] = WTVRcleanString($this ->Payment->getPaymentFraudScore()) : null;
          (WTVRcleanString($this ->Payment->getPaymentMaxmindObject())) ? $itemarray["payment_maxmind_object"] = WTVRcleanString($this ->Payment->getPaymentMaxmindObject()) : null;
          (WTVRcleanString($this ->Payment->getPaymentOrderProcessor())) ? $itemarray["payment_order_processor"] = WTVRcleanString($this ->Payment->getPaymentOrderProcessor()) : null;
          (is_numeric(WTVRcleanString($this ->Payment->getPaymentInvites()))) ? $itemarray["payment_invites"] = WTVRcleanString($this ->Payment->getPaymentInvites()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Payment = PaymentPeer::retrieveByPK( $id );
    } elseif (! $this ->Payment) {
      $this ->Payment = new Payment;
    }
        
  	 ($this -> getPaymentId())? $this ->Payment->setPaymentId( WTVRcleanString( $this -> getPaymentId()) ) : null;
    ($this -> getFkFilmId())? $this ->Payment->setFkFilmId( WTVRcleanString( $this -> getFkFilmId()) ) : null;
    ($this -> getFkScreeningId())? $this ->Payment->setFkScreeningId( WTVRcleanString( $this -> getFkScreeningId()) ) : null;
    ($this -> getFkScreeningName())? $this ->Payment->setFkScreeningName( WTVRcleanString( $this -> getFkScreeningName()) ) : null;
    ($this -> getFkAudienceId())? $this ->Payment->setFkAudienceId( WTVRcleanString( $this -> getFkAudienceId()) ) : null;
    ($this -> getFkSubscriptionId())? $this ->Payment->setFkSubscriptionId( WTVRcleanString( $this -> getFkSubscriptionId()) ) : null;
    ($this -> getFkUserId())? $this ->Payment->setFkUserId( WTVRcleanString( $this -> getFkUserId()) ) : null;
    ($this -> getPaymentUniqueCode())? $this ->Payment->setPaymentUniqueCode( WTVRcleanString( $this -> getPaymentUniqueCode()) ) : null;
    ($this -> getPaymentType())? $this ->Payment->setPaymentType( WTVRcleanString( $this -> getPaymentType()) ) : null;
    ($this -> getPaymentFirstName())? $this ->Payment->setPaymentFirstName( WTVRcleanString( $this -> getPaymentFirstName()) ) : null;
    ($this -> getPaymentLastName())? $this ->Payment->setPaymentLastName( WTVRcleanString( $this -> getPaymentLastName()) ) : null;
    ($this -> getPaymentEmail())? $this ->Payment->setPaymentEmail( WTVRcleanString( $this -> getPaymentEmail()) ) : null;
    ($this -> getPaymentBAddr1())? $this ->Payment->setPaymentBAddr1( WTVRcleanString( $this -> getPaymentBAddr1()) ) : null;
    ($this -> getPaymentBAddr2())? $this ->Payment->setPaymentBAddr2( WTVRcleanString( $this -> getPaymentBAddr2()) ) : null;
    ($this -> getPaymentBCity())? $this ->Payment->setPaymentBCity( WTVRcleanString( $this -> getPaymentBCity()) ) : null;
    ($this -> getPaymentBState())? $this ->Payment->setPaymentBState( WTVRcleanString( $this -> getPaymentBState()) ) : null;
    ($this -> getPaymentBZipcode())? $this ->Payment->setPaymentBZipcode( WTVRcleanString( $this -> getPaymentBZipcode()) ) : null;
    ($this -> getPaymentBCountry())? $this ->Payment->setPaymentBCountry( WTVRcleanString( $this -> getPaymentBCountry()) ) : null;
    ($this -> getPaymentStatus())? $this ->Payment->setPaymentStatus( WTVRcleanString( $this -> getPaymentStatus()) ) : null;
    ($this -> getPaymentAmount())? $this ->Payment->setPaymentAmount( WTVRcleanString( $this -> getPaymentAmount()) ) : null;
    ($this -> getPaymentDescription())? $this ->Payment->setPaymentDescription( WTVRcleanString( $this -> getPaymentDescription()) ) : null;
    ($this -> getPaymentCardType())? $this ->Payment->setPaymentCardType( WTVRcleanString( $this -> getPaymentCardType()) ) : null;
    ($this -> getPaymentLastFourCCDigits())? $this ->Payment->setPaymentLastFourCCDigits( WTVRcleanString( $this -> getPaymentLastFourCCDigits()) ) : null;
    ($this -> getPaymentCvv2())? $this ->Payment->setPaymentCvv2( WTVRcleanString( $this -> getPaymentCvv2()) ) : null;
    ($this -> getPaymentCcExp())? $this ->Payment->setPaymentCcExp( WTVRcleanString( $this -> getPaymentCcExp()) ) : null;
          if (is_valid_date( $this ->Payment->getPaymentCreatedAt())) {
        $this ->Payment->setPaymentCreatedAt( formatDate($this -> getPaymentCreatedAt(), "TS" ));
      } else {
      $Paymentpayment_created_at = $this -> sfDateTime( "payment_created_at" );
      ( $Paymentpayment_created_at != "01/01/1900 00:00:00" )? $this ->Payment->setPaymentCreatedAt( formatDate($Paymentpayment_created_at, "TS" )) : $this ->Payment->setPaymentCreatedAt( null );
      }
          if (is_valid_date( $this ->Payment->getPaymentUpdatedAt())) {
        $this ->Payment->setPaymentUpdatedAt( formatDate($this -> getPaymentUpdatedAt(), "TS" ));
      } else {
      $Paymentpayment_updated_at = $this -> sfDateTime( "payment_updated_at" );
      ( $Paymentpayment_updated_at != "01/01/1900 00:00:00" )? $this ->Payment->setPaymentUpdatedAt( formatDate($Paymentpayment_updated_at, "TS" )) : $this ->Payment->setPaymentUpdatedAt( null );
      }
    ($this -> getPaymentIp())? $this ->Payment->setPaymentIp( WTVRcleanString( $this -> getPaymentIp()) ) : null;
    ($this -> getPaymentSiteId())? $this ->Payment->setPaymentSiteId( WTVRcleanString( $this -> getPaymentSiteId()) ) : null;
    ($this -> getPaymentTransactionId())? $this ->Payment->setPaymentTransactionId( WTVRcleanString( $this -> getPaymentTransactionId()) ) : null;
    ($this -> getPaymentFraudScore())? $this ->Payment->setPaymentFraudScore( WTVRcleanString( $this -> getPaymentFraudScore()) ) : null;
    ($this -> getPaymentMaxmindObject())? $this ->Payment->setPaymentMaxmindObject( WTVRcleanString( $this -> getPaymentMaxmindObject()) ) : null;
    ($this -> getPaymentOrderProcessor())? $this ->Payment->setPaymentOrderProcessor( WTVRcleanString( $this -> getPaymentOrderProcessor()) ) : null;
    ($this -> getPaymentInvites())? $this ->Payment->setPaymentInvites( WTVRcleanString( $this -> getPaymentInvites()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Payment ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Payment = PaymentPeer::retrieveByPK($id);
    }
    
    if (! $this ->Payment ) {
      return;
    }
    
    $this ->Payment -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Payment_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "PaymentPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Payment = PaymentPeer::doSelect($c);
    
    if (count($Payment) >= 1) {
      $this ->Payment = $Payment[0];
      return true;
    } else {
      $this ->Payment = new Payment();
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
      $name = "PaymentPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Payment = PaymentPeer::doSelect($c);
    
    if (count($Payment) >= 1) {
      $this ->Payment = $Payment[0];
      return true;
    } else {
      $this ->Payment = new Payment();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>