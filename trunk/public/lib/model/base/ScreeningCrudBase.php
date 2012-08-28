<?php
       
   class ScreeningCrudBase extends Utils_PageWidget { 
   
    var $Screening;
   
       var $screening_id;
   var $fk_host_id;
   var $fk_film_id;
   var $fk_payment_id;
   var $fk_program_id;
   var $screening_name;
   var $screening_date;
   var $screening_time;
   var $screening_end_time;
   var $screening_prescreening_time;
   var $screening_post_message;
   var $screening_paid_status;
   var $screening_seats_occupied;
   var $screening_created_at;
   var $screening_updated_at;
   var $screening_unique_key;
   var $screening_status;
   var $screening_type;
   var $screening_total_seats;
   var $screening_constellation_image;
   var $screening_guest_name;
   var $screening_guest_image;
   var $screening_description;
   var $screening_live_webcam;
   var $screening_is_admin;
   var $screening_featured;
   var $screening_highlighted;
   var $screening_credit_status;
   var $screening_default_timezone;
   var $screening_receipt_status;
   var $screening_default_timezone_id;
   var $screening_video_server_hostname;
   var $screening_video_server_instance_id;
   var $screening_video_is_queued;
   var $screening_is_private;
   var $screening_has_qanda;
   var $screening_still_image;
   var $screening_chat_moderated;
   var $screening_chat_qanda_started;
   var $screening_allow_latecomers;
   var $screening_facebook_text;
   var $screening_twitter_text;
   var $screening_qa;
   var $screening_is_dohbr;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getScreeningId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Screening = ScreeningPeer::retrieveByPK( $id );
    } else {
      $this ->Screening = new Screening;
    }
  }
  
  function hydrate( $id ) {
      $this ->Screening = ScreeningPeer::retrieveByPK( $id );
  }
  
  function getScreeningId() {
    if (($this ->postVar("screening_id")) || ($this ->postVar("screening_id") === "")) {
      return $this ->postVar("screening_id");
    } elseif (($this ->getVar("screening_id")) || ($this ->getVar("screening_id") === "")) {
      return $this ->getVar("screening_id");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningId();
    } elseif (($this ->sessionVar("screening_id")) || ($this ->sessionVar("screening_id") == "")) {
      return $this ->sessionVar("screening_id");
    } else {
      return false;
    }
  }
  
  function setScreeningId( $str ) {
    $this ->Screening -> setScreeningId( $str );
  }
  
  function getFkHostId() {
    if (($this ->postVar("fk_host_id")) || ($this ->postVar("fk_host_id") === "")) {
      return $this ->postVar("fk_host_id");
    } elseif (($this ->getVar("fk_host_id")) || ($this ->getVar("fk_host_id") === "")) {
      return $this ->getVar("fk_host_id");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getFkHostId();
    } elseif (($this ->sessionVar("fk_host_id")) || ($this ->sessionVar("fk_host_id") == "")) {
      return $this ->sessionVar("fk_host_id");
    } else {
      return false;
    }
  }
  
  function setFkHostId( $str ) {
    $this ->Screening -> setFkHostId( $str );
  }
  
  function getFkFilmId() {
    if (($this ->postVar("fk_film_id")) || ($this ->postVar("fk_film_id") === "")) {
      return $this ->postVar("fk_film_id");
    } elseif (($this ->getVar("fk_film_id")) || ($this ->getVar("fk_film_id") === "")) {
      return $this ->getVar("fk_film_id");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getFkFilmId();
    } elseif (($this ->sessionVar("fk_film_id")) || ($this ->sessionVar("fk_film_id") == "")) {
      return $this ->sessionVar("fk_film_id");
    } else {
      return false;
    }
  }
  
  function setFkFilmId( $str ) {
    $this ->Screening -> setFkFilmId( $str );
  }
  
  function getFkPaymentId() {
    if (($this ->postVar("fk_payment_id")) || ($this ->postVar("fk_payment_id") === "")) {
      return $this ->postVar("fk_payment_id");
    } elseif (($this ->getVar("fk_payment_id")) || ($this ->getVar("fk_payment_id") === "")) {
      return $this ->getVar("fk_payment_id");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getFkPaymentId();
    } elseif (($this ->sessionVar("fk_payment_id")) || ($this ->sessionVar("fk_payment_id") == "")) {
      return $this ->sessionVar("fk_payment_id");
    } else {
      return false;
    }
  }
  
  function setFkPaymentId( $str ) {
    $this ->Screening -> setFkPaymentId( $str );
  }
  
  function getFkProgramId() {
    if (($this ->postVar("fk_program_id")) || ($this ->postVar("fk_program_id") === "")) {
      return $this ->postVar("fk_program_id");
    } elseif (($this ->getVar("fk_program_id")) || ($this ->getVar("fk_program_id") === "")) {
      return $this ->getVar("fk_program_id");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getFkProgramId();
    } elseif (($this ->sessionVar("fk_program_id")) || ($this ->sessionVar("fk_program_id") == "")) {
      return $this ->sessionVar("fk_program_id");
    } else {
      return false;
    }
  }
  
  function setFkProgramId( $str ) {
    $this ->Screening -> setFkProgramId( $str );
  }
  
  function getScreeningName() {
    if (($this ->postVar("screening_name")) || ($this ->postVar("screening_name") === "")) {
      return $this ->postVar("screening_name");
    } elseif (($this ->getVar("screening_name")) || ($this ->getVar("screening_name") === "")) {
      return $this ->getVar("screening_name");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningName();
    } elseif (($this ->sessionVar("screening_name")) || ($this ->sessionVar("screening_name") == "")) {
      return $this ->sessionVar("screening_name");
    } else {
      return false;
    }
  }
  
  function setScreeningName( $str ) {
    $this ->Screening -> setScreeningName( $str );
  }
  
  function getScreeningDate() {
    if (($this ->postVar("screening_date")) || ($this ->postVar("screening_date") === "")) {
      return $this ->postVar("screening_date");
    } elseif (($this ->getVar("screening_date")) || ($this ->getVar("screening_date") === "")) {
      return $this ->getVar("screening_date");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningDate();
    } elseif (($this ->sessionVar("screening_date")) || ($this ->sessionVar("screening_date") == "")) {
      return $this ->sessionVar("screening_date");
    } else {
      return false;
    }
  }
  
  function setScreeningDate( $str ) {
    $this ->Screening -> setScreeningDate( $str );
  }
  
  function getScreeningTime() {
    if (($this ->postVar("screening_time")) || ($this ->postVar("screening_time") === "")) {
      return $this ->postVar("screening_time");
    } elseif (($this ->getVar("screening_time")) || ($this ->getVar("screening_time") === "")) {
      return $this ->getVar("screening_time");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningTime();
    } elseif (($this ->sessionVar("screening_time")) || ($this ->sessionVar("screening_time") == "")) {
      return $this ->sessionVar("screening_time");
    } else {
      return false;
    }
  }
  
  function setScreeningTime( $str ) {
    $this ->Screening -> setScreeningTime( $str );
  }
  
  function getScreeningEndTime() {
    if (($this ->postVar("screening_end_time")) || ($this ->postVar("screening_end_time") === "")) {
      return $this ->postVar("screening_end_time");
    } elseif (($this ->getVar("screening_end_time")) || ($this ->getVar("screening_end_time") === "")) {
      return $this ->getVar("screening_end_time");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningEndTime();
    } elseif (($this ->sessionVar("screening_end_time")) || ($this ->sessionVar("screening_end_time") == "")) {
      return $this ->sessionVar("screening_end_time");
    } else {
      return false;
    }
  }
  
  function setScreeningEndTime( $str ) {
    $this ->Screening -> setScreeningEndTime( $str );
  }
  
  function getScreeningPrescreeningTime() {
    if (($this ->postVar("screening_prescreening_time")) || ($this ->postVar("screening_prescreening_time") === "")) {
      return $this ->postVar("screening_prescreening_time");
    } elseif (($this ->getVar("screening_prescreening_time")) || ($this ->getVar("screening_prescreening_time") === "")) {
      return $this ->getVar("screening_prescreening_time");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningPrescreeningTime();
    } elseif (($this ->sessionVar("screening_prescreening_time")) || ($this ->sessionVar("screening_prescreening_time") == "")) {
      return $this ->sessionVar("screening_prescreening_time");
    } else {
      return false;
    }
  }
  
  function setScreeningPrescreeningTime( $str ) {
    $this ->Screening -> setScreeningPrescreeningTime( $str );
  }
  
  function getScreeningPostMessage() {
    if (($this ->postVar("screening_post_message")) || ($this ->postVar("screening_post_message") === "")) {
      return $this ->postVar("screening_post_message");
    } elseif (($this ->getVar("screening_post_message")) || ($this ->getVar("screening_post_message") === "")) {
      return $this ->getVar("screening_post_message");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningPostMessage();
    } elseif (($this ->sessionVar("screening_post_message")) || ($this ->sessionVar("screening_post_message") == "")) {
      return $this ->sessionVar("screening_post_message");
    } else {
      return false;
    }
  }
  
  function setScreeningPostMessage( $str ) {
    $this ->Screening -> setScreeningPostMessage( $str );
  }
  
  function getScreeningPaidStatus() {
    if (($this ->postVar("screening_paid_status")) || ($this ->postVar("screening_paid_status") === "")) {
      return $this ->postVar("screening_paid_status");
    } elseif (($this ->getVar("screening_paid_status")) || ($this ->getVar("screening_paid_status") === "")) {
      return $this ->getVar("screening_paid_status");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningPaidStatus();
    } elseif (($this ->sessionVar("screening_paid_status")) || ($this ->sessionVar("screening_paid_status") == "")) {
      return $this ->sessionVar("screening_paid_status");
    } else {
      return false;
    }
  }
  
  function setScreeningPaidStatus( $str ) {
    $this ->Screening -> setScreeningPaidStatus( $str );
  }
  
  function getScreeningSeatsOccupied() {
    if (($this ->postVar("screening_seats_occupied")) || ($this ->postVar("screening_seats_occupied") === "")) {
      return $this ->postVar("screening_seats_occupied");
    } elseif (($this ->getVar("screening_seats_occupied")) || ($this ->getVar("screening_seats_occupied") === "")) {
      return $this ->getVar("screening_seats_occupied");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningSeatsOccupied();
    } elseif (($this ->sessionVar("screening_seats_occupied")) || ($this ->sessionVar("screening_seats_occupied") == "")) {
      return $this ->sessionVar("screening_seats_occupied");
    } else {
      return false;
    }
  }
  
  function setScreeningSeatsOccupied( $str ) {
    $this ->Screening -> setScreeningSeatsOccupied( $str );
  }
  
  function getScreeningCreatedAt() {
    if (($this ->postVar("screening_created_at")) || ($this ->postVar("screening_created_at") === "")) {
      return $this ->postVar("screening_created_at");
    } elseif (($this ->getVar("screening_created_at")) || ($this ->getVar("screening_created_at") === "")) {
      return $this ->getVar("screening_created_at");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningCreatedAt();
    } elseif (($this ->sessionVar("screening_created_at")) || ($this ->sessionVar("screening_created_at") == "")) {
      return $this ->sessionVar("screening_created_at");
    } else {
      return false;
    }
  }
  
  function setScreeningCreatedAt( $str ) {
    $this ->Screening -> setScreeningCreatedAt( $str );
  }
  
  function getScreeningUpdatedAt() {
    if (($this ->postVar("screening_updated_at")) || ($this ->postVar("screening_updated_at") === "")) {
      return $this ->postVar("screening_updated_at");
    } elseif (($this ->getVar("screening_updated_at")) || ($this ->getVar("screening_updated_at") === "")) {
      return $this ->getVar("screening_updated_at");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningUpdatedAt();
    } elseif (($this ->sessionVar("screening_updated_at")) || ($this ->sessionVar("screening_updated_at") == "")) {
      return $this ->sessionVar("screening_updated_at");
    } else {
      return false;
    }
  }
  
  function setScreeningUpdatedAt( $str ) {
    $this ->Screening -> setScreeningUpdatedAt( $str );
  }
  
  function getScreeningUniqueKey() {
    if (($this ->postVar("screening_unique_key")) || ($this ->postVar("screening_unique_key") === "")) {
      return $this ->postVar("screening_unique_key");
    } elseif (($this ->getVar("screening_unique_key")) || ($this ->getVar("screening_unique_key") === "")) {
      return $this ->getVar("screening_unique_key");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningUniqueKey();
    } elseif (($this ->sessionVar("screening_unique_key")) || ($this ->sessionVar("screening_unique_key") == "")) {
      return $this ->sessionVar("screening_unique_key");
    } else {
      return false;
    }
  }
  
  function setScreeningUniqueKey( $str ) {
    $this ->Screening -> setScreeningUniqueKey( $str );
  }
  
  function getScreeningStatus() {
    if (($this ->postVar("screening_status")) || ($this ->postVar("screening_status") === "")) {
      return $this ->postVar("screening_status");
    } elseif (($this ->getVar("screening_status")) || ($this ->getVar("screening_status") === "")) {
      return $this ->getVar("screening_status");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningStatus();
    } elseif (($this ->sessionVar("screening_status")) || ($this ->sessionVar("screening_status") == "")) {
      return $this ->sessionVar("screening_status");
    } else {
      return false;
    }
  }
  
  function setScreeningStatus( $str ) {
    $this ->Screening -> setScreeningStatus( $str );
  }
  
  function getScreeningType() {
    if (($this ->postVar("screening_type")) || ($this ->postVar("screening_type") === "")) {
      return $this ->postVar("screening_type");
    } elseif (($this ->getVar("screening_type")) || ($this ->getVar("screening_type") === "")) {
      return $this ->getVar("screening_type");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningType();
    } elseif (($this ->sessionVar("screening_type")) || ($this ->sessionVar("screening_type") == "")) {
      return $this ->sessionVar("screening_type");
    } else {
      return false;
    }
  }
  
  function setScreeningType( $str ) {
    $this ->Screening -> setScreeningType( $str );
  }
  
  function getScreeningTotalSeats() {
    if (($this ->postVar("screening_total_seats")) || ($this ->postVar("screening_total_seats") === "")) {
      return $this ->postVar("screening_total_seats");
    } elseif (($this ->getVar("screening_total_seats")) || ($this ->getVar("screening_total_seats") === "")) {
      return $this ->getVar("screening_total_seats");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningTotalSeats();
    } elseif (($this ->sessionVar("screening_total_seats")) || ($this ->sessionVar("screening_total_seats") == "")) {
      return $this ->sessionVar("screening_total_seats");
    } else {
      return false;
    }
  }
  
  function setScreeningTotalSeats( $str ) {
    $this ->Screening -> setScreeningTotalSeats( $str );
  }
  
  function getScreeningConstellationImage() {
    if (($this ->postVar("screening_constellation_image")) || ($this ->postVar("screening_constellation_image") === "")) {
      return $this ->postVar("screening_constellation_image");
    } elseif (($this ->getVar("screening_constellation_image")) || ($this ->getVar("screening_constellation_image") === "")) {
      return $this ->getVar("screening_constellation_image");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningConstellationImage();
    } elseif (($this ->sessionVar("screening_constellation_image")) || ($this ->sessionVar("screening_constellation_image") == "")) {
      return $this ->sessionVar("screening_constellation_image");
    } else {
      return false;
    }
  }
  
  function setScreeningConstellationImage( $str ) {
    $this ->Screening -> setScreeningConstellationImage( $str );
  }
  
  function getScreeningGuestName() {
    if (($this ->postVar("screening_guest_name")) || ($this ->postVar("screening_guest_name") === "")) {
      return $this ->postVar("screening_guest_name");
    } elseif (($this ->getVar("screening_guest_name")) || ($this ->getVar("screening_guest_name") === "")) {
      return $this ->getVar("screening_guest_name");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningGuestName();
    } elseif (($this ->sessionVar("screening_guest_name")) || ($this ->sessionVar("screening_guest_name") == "")) {
      return $this ->sessionVar("screening_guest_name");
    } else {
      return false;
    }
  }
  
  function setScreeningGuestName( $str ) {
    $this ->Screening -> setScreeningGuestName( $str );
  }
  
  function getScreeningGuestImage() {
    if (($this ->postVar("screening_guest_image")) || ($this ->postVar("screening_guest_image") === "")) {
      return $this ->postVar("screening_guest_image");
    } elseif (($this ->getVar("screening_guest_image")) || ($this ->getVar("screening_guest_image") === "")) {
      return $this ->getVar("screening_guest_image");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningGuestImage();
    } elseif (($this ->sessionVar("screening_guest_image")) || ($this ->sessionVar("screening_guest_image") == "")) {
      return $this ->sessionVar("screening_guest_image");
    } else {
      return false;
    }
  }
  
  function setScreeningGuestImage( $str ) {
    $this ->Screening -> setScreeningGuestImage( $str );
  }
  
  function getScreeningDescription() {
    if (($this ->postVar("screening_description")) || ($this ->postVar("screening_description") === "")) {
      return $this ->postVar("screening_description");
    } elseif (($this ->getVar("screening_description")) || ($this ->getVar("screening_description") === "")) {
      return $this ->getVar("screening_description");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningDescription();
    } elseif (($this ->sessionVar("screening_description")) || ($this ->sessionVar("screening_description") == "")) {
      return $this ->sessionVar("screening_description");
    } else {
      return false;
    }
  }
  
  function setScreeningDescription( $str ) {
    $this ->Screening -> setScreeningDescription( $str );
  }
  
  function getScreeningLiveWebcam() {
    if (($this ->postVar("screening_live_webcam")) || ($this ->postVar("screening_live_webcam") === "")) {
      return $this ->postVar("screening_live_webcam");
    } elseif (($this ->getVar("screening_live_webcam")) || ($this ->getVar("screening_live_webcam") === "")) {
      return $this ->getVar("screening_live_webcam");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningLiveWebcam();
    } elseif (($this ->sessionVar("screening_live_webcam")) || ($this ->sessionVar("screening_live_webcam") == "")) {
      return $this ->sessionVar("screening_live_webcam");
    } else {
      return false;
    }
  }
  
  function setScreeningLiveWebcam( $str ) {
    $this ->Screening -> setScreeningLiveWebcam( $str );
  }
  
  function getScreeningIsAdmin() {
    if (($this ->postVar("screening_is_admin")) || ($this ->postVar("screening_is_admin") === "")) {
      return $this ->postVar("screening_is_admin");
    } elseif (($this ->getVar("screening_is_admin")) || ($this ->getVar("screening_is_admin") === "")) {
      return $this ->getVar("screening_is_admin");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningIsAdmin();
    } elseif (($this ->sessionVar("screening_is_admin")) || ($this ->sessionVar("screening_is_admin") == "")) {
      return $this ->sessionVar("screening_is_admin");
    } else {
      return false;
    }
  }
  
  function setScreeningIsAdmin( $str ) {
    $this ->Screening -> setScreeningIsAdmin( $str );
  }
  
  function getScreeningFeatured() {
    if (($this ->postVar("screening_featured")) || ($this ->postVar("screening_featured") === "")) {
      return $this ->postVar("screening_featured");
    } elseif (($this ->getVar("screening_featured")) || ($this ->getVar("screening_featured") === "")) {
      return $this ->getVar("screening_featured");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningFeatured();
    } elseif (($this ->sessionVar("screening_featured")) || ($this ->sessionVar("screening_featured") == "")) {
      return $this ->sessionVar("screening_featured");
    } else {
      return false;
    }
  }
  
  function setScreeningFeatured( $str ) {
    $this ->Screening -> setScreeningFeatured( $str );
  }
  
  function getScreeningHighlighted() {
    if (($this ->postVar("screening_highlighted")) || ($this ->postVar("screening_highlighted") === "")) {
      return $this ->postVar("screening_highlighted");
    } elseif (($this ->getVar("screening_highlighted")) || ($this ->getVar("screening_highlighted") === "")) {
      return $this ->getVar("screening_highlighted");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningHighlighted();
    } elseif (($this ->sessionVar("screening_highlighted")) || ($this ->sessionVar("screening_highlighted") == "")) {
      return $this ->sessionVar("screening_highlighted");
    } else {
      return false;
    }
  }
  
  function setScreeningHighlighted( $str ) {
    $this ->Screening -> setScreeningHighlighted( $str );
  }
  
  function getScreeningCreditStatus() {
    if (($this ->postVar("screening_credit_status")) || ($this ->postVar("screening_credit_status") === "")) {
      return $this ->postVar("screening_credit_status");
    } elseif (($this ->getVar("screening_credit_status")) || ($this ->getVar("screening_credit_status") === "")) {
      return $this ->getVar("screening_credit_status");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningCreditStatus();
    } elseif (($this ->sessionVar("screening_credit_status")) || ($this ->sessionVar("screening_credit_status") == "")) {
      return $this ->sessionVar("screening_credit_status");
    } else {
      return false;
    }
  }
  
  function setScreeningCreditStatus( $str ) {
    $this ->Screening -> setScreeningCreditStatus( $str );
  }
  
  function getScreeningDefaultTimezone() {
    if (($this ->postVar("screening_default_timezone")) || ($this ->postVar("screening_default_timezone") === "")) {
      return $this ->postVar("screening_default_timezone");
    } elseif (($this ->getVar("screening_default_timezone")) || ($this ->getVar("screening_default_timezone") === "")) {
      return $this ->getVar("screening_default_timezone");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningDefaultTimezone();
    } elseif (($this ->sessionVar("screening_default_timezone")) || ($this ->sessionVar("screening_default_timezone") == "")) {
      return $this ->sessionVar("screening_default_timezone");
    } else {
      return false;
    }
  }
  
  function setScreeningDefaultTimezone( $str ) {
    $this ->Screening -> setScreeningDefaultTimezone( $str );
  }
  
  function getScreeningReceiptStatus() {
    if (($this ->postVar("screening_receipt_status")) || ($this ->postVar("screening_receipt_status") === "")) {
      return $this ->postVar("screening_receipt_status");
    } elseif (($this ->getVar("screening_receipt_status")) || ($this ->getVar("screening_receipt_status") === "")) {
      return $this ->getVar("screening_receipt_status");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningReceiptStatus();
    } elseif (($this ->sessionVar("screening_receipt_status")) || ($this ->sessionVar("screening_receipt_status") == "")) {
      return $this ->sessionVar("screening_receipt_status");
    } else {
      return false;
    }
  }
  
  function setScreeningReceiptStatus( $str ) {
    $this ->Screening -> setScreeningReceiptStatus( $str );
  }
  
  function getScreeningDefaultTimezoneId() {
    if (($this ->postVar("screening_default_timezone_id")) || ($this ->postVar("screening_default_timezone_id") === "")) {
      return $this ->postVar("screening_default_timezone_id");
    } elseif (($this ->getVar("screening_default_timezone_id")) || ($this ->getVar("screening_default_timezone_id") === "")) {
      return $this ->getVar("screening_default_timezone_id");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningDefaultTimezoneId();
    } elseif (($this ->sessionVar("screening_default_timezone_id")) || ($this ->sessionVar("screening_default_timezone_id") == "")) {
      return $this ->sessionVar("screening_default_timezone_id");
    } else {
      return false;
    }
  }
  
  function setScreeningDefaultTimezoneId( $str ) {
    $this ->Screening -> setScreeningDefaultTimezoneId( $str );
  }
  
  function getScreeningVideoServerHostname() {
    if (($this ->postVar("screening_video_server_hostname")) || ($this ->postVar("screening_video_server_hostname") === "")) {
      return $this ->postVar("screening_video_server_hostname");
    } elseif (($this ->getVar("screening_video_server_hostname")) || ($this ->getVar("screening_video_server_hostname") === "")) {
      return $this ->getVar("screening_video_server_hostname");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningVideoServerHostname();
    } elseif (($this ->sessionVar("screening_video_server_hostname")) || ($this ->sessionVar("screening_video_server_hostname") == "")) {
      return $this ->sessionVar("screening_video_server_hostname");
    } else {
      return false;
    }
  }
  
  function setScreeningVideoServerHostname( $str ) {
    $this ->Screening -> setScreeningVideoServerHostname( $str );
  }
  
  function getScreeningVideoServerInstanceId() {
    if (($this ->postVar("screening_video_server_instance_id")) || ($this ->postVar("screening_video_server_instance_id") === "")) {
      return $this ->postVar("screening_video_server_instance_id");
    } elseif (($this ->getVar("screening_video_server_instance_id")) || ($this ->getVar("screening_video_server_instance_id") === "")) {
      return $this ->getVar("screening_video_server_instance_id");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningVideoServerInstanceId();
    } elseif (($this ->sessionVar("screening_video_server_instance_id")) || ($this ->sessionVar("screening_video_server_instance_id") == "")) {
      return $this ->sessionVar("screening_video_server_instance_id");
    } else {
      return false;
    }
  }
  
  function setScreeningVideoServerInstanceId( $str ) {
    $this ->Screening -> setScreeningVideoServerInstanceId( $str );
  }
  
  function getScreeningVideoIsQueued() {
    if (($this ->postVar("screening_video_is_queued")) || ($this ->postVar("screening_video_is_queued") === "")) {
      return $this ->postVar("screening_video_is_queued");
    } elseif (($this ->getVar("screening_video_is_queued")) || ($this ->getVar("screening_video_is_queued") === "")) {
      return $this ->getVar("screening_video_is_queued");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningVideoIsQueued();
    } elseif (($this ->sessionVar("screening_video_is_queued")) || ($this ->sessionVar("screening_video_is_queued") == "")) {
      return $this ->sessionVar("screening_video_is_queued");
    } else {
      return false;
    }
  }
  
  function setScreeningVideoIsQueued( $str ) {
    $this ->Screening -> setScreeningVideoIsQueued( $str );
  }
  
  function getScreeningIsPrivate() {
    if (($this ->postVar("screening_is_private")) || ($this ->postVar("screening_is_private") === "")) {
      return $this ->postVar("screening_is_private");
    } elseif (($this ->getVar("screening_is_private")) || ($this ->getVar("screening_is_private") === "")) {
      return $this ->getVar("screening_is_private");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningIsPrivate();
    } elseif (($this ->sessionVar("screening_is_private")) || ($this ->sessionVar("screening_is_private") == "")) {
      return $this ->sessionVar("screening_is_private");
    } else {
      return false;
    }
  }
  
  function setScreeningIsPrivate( $str ) {
    $this ->Screening -> setScreeningIsPrivate( $str );
  }
  
  function getScreeningHasQanda() {
    if (($this ->postVar("screening_has_qanda")) || ($this ->postVar("screening_has_qanda") === "")) {
      return $this ->postVar("screening_has_qanda");
    } elseif (($this ->getVar("screening_has_qanda")) || ($this ->getVar("screening_has_qanda") === "")) {
      return $this ->getVar("screening_has_qanda");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningHasQanda();
    } elseif (($this ->sessionVar("screening_has_qanda")) || ($this ->sessionVar("screening_has_qanda") == "")) {
      return $this ->sessionVar("screening_has_qanda");
    } else {
      return false;
    }
  }
  
  function setScreeningHasQanda( $str ) {
    $this ->Screening -> setScreeningHasQanda( $str );
  }
  
  function getScreeningStillImage() {
    if (($this ->postVar("screening_still_image")) || ($this ->postVar("screening_still_image") === "")) {
      return $this ->postVar("screening_still_image");
    } elseif (($this ->getVar("screening_still_image")) || ($this ->getVar("screening_still_image") === "")) {
      return $this ->getVar("screening_still_image");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningStillImage();
    } elseif (($this ->sessionVar("screening_still_image")) || ($this ->sessionVar("screening_still_image") == "")) {
      return $this ->sessionVar("screening_still_image");
    } else {
      return false;
    }
  }
  
  function setScreeningStillImage( $str ) {
    $this ->Screening -> setScreeningStillImage( $str );
  }
  
  function getScreeningChatModerated() {
    if (($this ->postVar("screening_chat_moderated")) || ($this ->postVar("screening_chat_moderated") === "")) {
      return $this ->postVar("screening_chat_moderated");
    } elseif (($this ->getVar("screening_chat_moderated")) || ($this ->getVar("screening_chat_moderated") === "")) {
      return $this ->getVar("screening_chat_moderated");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningChatModerated();
    } elseif (($this ->sessionVar("screening_chat_moderated")) || ($this ->sessionVar("screening_chat_moderated") == "")) {
      return $this ->sessionVar("screening_chat_moderated");
    } else {
      return false;
    }
  }
  
  function setScreeningChatModerated( $str ) {
    $this ->Screening -> setScreeningChatModerated( $str );
  }
  
  function getScreeningChatQandaStarted() {
    if (($this ->postVar("screening_chat_qanda_started")) || ($this ->postVar("screening_chat_qanda_started") === "")) {
      return $this ->postVar("screening_chat_qanda_started");
    } elseif (($this ->getVar("screening_chat_qanda_started")) || ($this ->getVar("screening_chat_qanda_started") === "")) {
      return $this ->getVar("screening_chat_qanda_started");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningChatQandaStarted();
    } elseif (($this ->sessionVar("screening_chat_qanda_started")) || ($this ->sessionVar("screening_chat_qanda_started") == "")) {
      return $this ->sessionVar("screening_chat_qanda_started");
    } else {
      return false;
    }
  }
  
  function setScreeningChatQandaStarted( $str ) {
    $this ->Screening -> setScreeningChatQandaStarted( $str );
  }
  
  function getScreeningAllowLatecomers() {
    if (($this ->postVar("screening_allow_latecomers")) || ($this ->postVar("screening_allow_latecomers") === "")) {
      return $this ->postVar("screening_allow_latecomers");
    } elseif (($this ->getVar("screening_allow_latecomers")) || ($this ->getVar("screening_allow_latecomers") === "")) {
      return $this ->getVar("screening_allow_latecomers");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningAllowLatecomers();
    } elseif (($this ->sessionVar("screening_allow_latecomers")) || ($this ->sessionVar("screening_allow_latecomers") == "")) {
      return $this ->sessionVar("screening_allow_latecomers");
    } else {
      return false;
    }
  }
  
  function setScreeningAllowLatecomers( $str ) {
    $this ->Screening -> setScreeningAllowLatecomers( $str );
  }
  
  function getScreeningFacebookText() {
    if (($this ->postVar("screening_facebook_text")) || ($this ->postVar("screening_facebook_text") === "")) {
      return $this ->postVar("screening_facebook_text");
    } elseif (($this ->getVar("screening_facebook_text")) || ($this ->getVar("screening_facebook_text") === "")) {
      return $this ->getVar("screening_facebook_text");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningFacebookText();
    } elseif (($this ->sessionVar("screening_facebook_text")) || ($this ->sessionVar("screening_facebook_text") == "")) {
      return $this ->sessionVar("screening_facebook_text");
    } else {
      return false;
    }
  }
  
  function setScreeningFacebookText( $str ) {
    $this ->Screening -> setScreeningFacebookText( $str );
  }
  
  function getScreeningTwitterText() {
    if (($this ->postVar("screening_twitter_text")) || ($this ->postVar("screening_twitter_text") === "")) {
      return $this ->postVar("screening_twitter_text");
    } elseif (($this ->getVar("screening_twitter_text")) || ($this ->getVar("screening_twitter_text") === "")) {
      return $this ->getVar("screening_twitter_text");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningTwitterText();
    } elseif (($this ->sessionVar("screening_twitter_text")) || ($this ->sessionVar("screening_twitter_text") == "")) {
      return $this ->sessionVar("screening_twitter_text");
    } else {
      return false;
    }
  }
  
  function setScreeningTwitterText( $str ) {
    $this ->Screening -> setScreeningTwitterText( $str );
  }
  
  function getScreeningQa() {
    if (($this ->postVar("screening_qa")) || ($this ->postVar("screening_qa") === "")) {
      return $this ->postVar("screening_qa");
    } elseif (($this ->getVar("screening_qa")) || ($this ->getVar("screening_qa") === "")) {
      return $this ->getVar("screening_qa");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningQa();
    } elseif (($this ->sessionVar("screening_qa")) || ($this ->sessionVar("screening_qa") == "")) {
      return $this ->sessionVar("screening_qa");
    } else {
      return false;
    }
  }
  
  function setScreeningQa( $str ) {
    $this ->Screening -> setScreeningQa( $str );
  }
  
  function getScreeningIsDohbr() {
    if (($this ->postVar("screening_is_dohbr")) || ($this ->postVar("screening_is_dohbr") === "")) {
      return $this ->postVar("screening_is_dohbr");
    } elseif (($this ->getVar("screening_is_dohbr")) || ($this ->getVar("screening_is_dohbr") === "")) {
      return $this ->getVar("screening_is_dohbr");
    } elseif (($this ->Screening) || ($this ->Screening === "")){
      return $this ->Screening -> getScreeningIsDohbr();
    } elseif (($this ->sessionVar("screening_is_dohbr")) || ($this ->sessionVar("screening_is_dohbr") == "")) {
      return $this ->sessionVar("screening_is_dohbr");
    } else {
      return false;
    }
  }
  
  function setScreeningIsDohbr( $str ) {
    $this ->Screening -> setScreeningIsDohbr( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Screening = ScreeningPeer::retrieveByPK( $id );
    }
    
    if ($this ->Screening ) {
       
    	       (is_numeric(WTVRcleanString($this ->Screening->getScreeningId()))) ? $itemarray["screening_id"] = WTVRcleanString($this ->Screening->getScreeningId()) : null;
          (is_numeric(WTVRcleanString($this ->Screening->getFkHostId()))) ? $itemarray["fk_host_id"] = WTVRcleanString($this ->Screening->getFkHostId()) : null;
          (is_numeric(WTVRcleanString($this ->Screening->getFkFilmId()))) ? $itemarray["fk_film_id"] = WTVRcleanString($this ->Screening->getFkFilmId()) : null;
          (is_numeric(WTVRcleanString($this ->Screening->getFkPaymentId()))) ? $itemarray["fk_payment_id"] = WTVRcleanString($this ->Screening->getFkPaymentId()) : null;
          (is_numeric(WTVRcleanString($this ->Screening->getFkProgramId()))) ? $itemarray["fk_program_id"] = WTVRcleanString($this ->Screening->getFkProgramId()) : null;
          (WTVRcleanString($this ->Screening->getScreeningName())) ? $itemarray["screening_name"] = WTVRcleanString($this ->Screening->getScreeningName()) : null;
          (WTVRcleanString($this ->Screening->getScreeningDate())) ? $itemarray["screening_date"] = WTVRcleanString($this ->Screening->getScreeningDate()) : null;
          (WTVRcleanString($this ->Screening->getScreeningTime())) ? $itemarray["screening_time"] = WTVRcleanString($this ->Screening->getScreeningTime()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Screening->getScreeningEndTime())) ? $itemarray["screening_end_time"] = formatDate($this ->Screening->getScreeningEndTime('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->Screening->getScreeningPrescreeningTime())) ? $itemarray["screening_prescreening_time"] = WTVRcleanString($this ->Screening->getScreeningPrescreeningTime()) : null;
          (WTVRcleanString($this ->Screening->getScreeningPostMessage())) ? $itemarray["screening_post_message"] = WTVRcleanString($this ->Screening->getScreeningPostMessage()) : null;
          (WTVRcleanString($this ->Screening->getScreeningPaidStatus())) ? $itemarray["screening_paid_status"] = WTVRcleanString($this ->Screening->getScreeningPaidStatus()) : null;
          (is_numeric(WTVRcleanString($this ->Screening->getScreeningSeatsOccupied()))) ? $itemarray["screening_seats_occupied"] = WTVRcleanString($this ->Screening->getScreeningSeatsOccupied()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Screening->getScreeningCreatedAt())) ? $itemarray["screening_created_at"] = formatDate($this ->Screening->getScreeningCreatedAt('%Y-%m-%d %T'),"TS") : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Screening->getScreeningUpdatedAt())) ? $itemarray["screening_updated_at"] = formatDate($this ->Screening->getScreeningUpdatedAt('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->Screening->getScreeningUniqueKey())) ? $itemarray["screening_unique_key"] = WTVRcleanString($this ->Screening->getScreeningUniqueKey()) : null;
          (WTVRcleanString($this ->Screening->getScreeningStatus())) ? $itemarray["screening_status"] = WTVRcleanString($this ->Screening->getScreeningStatus()) : null;
          (WTVRcleanString($this ->Screening->getScreeningType())) ? $itemarray["screening_type"] = WTVRcleanString($this ->Screening->getScreeningType()) : null;
          (is_numeric(WTVRcleanString($this ->Screening->getScreeningTotalSeats()))) ? $itemarray["screening_total_seats"] = WTVRcleanString($this ->Screening->getScreeningTotalSeats()) : null;
          (WTVRcleanString($this ->Screening->getScreeningConstellationImage())) ? $itemarray["screening_constellation_image"] = WTVRcleanString($this ->Screening->getScreeningConstellationImage()) : null;
          (WTVRcleanString($this ->Screening->getScreeningGuestName())) ? $itemarray["screening_guest_name"] = WTVRcleanString($this ->Screening->getScreeningGuestName()) : null;
          (WTVRcleanString($this ->Screening->getScreeningGuestImage())) ? $itemarray["screening_guest_image"] = WTVRcleanString($this ->Screening->getScreeningGuestImage()) : null;
          (WTVRcleanString($this ->Screening->getScreeningDescription())) ? $itemarray["screening_description"] = WTVRcleanString($this ->Screening->getScreeningDescription()) : null;
          (WTVRcleanString($this ->Screening->getScreeningLiveWebcam())) ? $itemarray["screening_live_webcam"] = WTVRcleanString($this ->Screening->getScreeningLiveWebcam()) : null;
          (WTVRcleanString($this ->Screening->getScreeningIsAdmin())) ? $itemarray["screening_is_admin"] = WTVRcleanString($this ->Screening->getScreeningIsAdmin()) : null;
          (WTVRcleanString($this ->Screening->getScreeningFeatured())) ? $itemarray["screening_featured"] = WTVRcleanString($this ->Screening->getScreeningFeatured()) : null;
          (WTVRcleanString($this ->Screening->getScreeningHighlighted())) ? $itemarray["screening_highlighted"] = WTVRcleanString($this ->Screening->getScreeningHighlighted()) : null;
          (WTVRcleanString($this ->Screening->getScreeningCreditStatus())) ? $itemarray["screening_credit_status"] = WTVRcleanString($this ->Screening->getScreeningCreditStatus()) : null;
          (WTVRcleanString($this ->Screening->getScreeningDefaultTimezone())) ? $itemarray["screening_default_timezone"] = WTVRcleanString($this ->Screening->getScreeningDefaultTimezone()) : null;
          (WTVRcleanString($this ->Screening->getScreeningReceiptStatus())) ? $itemarray["screening_receipt_status"] = WTVRcleanString($this ->Screening->getScreeningReceiptStatus()) : null;
          (WTVRcleanString($this ->Screening->getScreeningDefaultTimezoneId())) ? $itemarray["screening_default_timezone_id"] = WTVRcleanString($this ->Screening->getScreeningDefaultTimezoneId()) : null;
          (WTVRcleanString($this ->Screening->getScreeningVideoServerHostname())) ? $itemarray["screening_video_server_hostname"] = WTVRcleanString($this ->Screening->getScreeningVideoServerHostname()) : null;
          (WTVRcleanString($this ->Screening->getScreeningVideoServerInstanceId())) ? $itemarray["screening_video_server_instance_id"] = WTVRcleanString($this ->Screening->getScreeningVideoServerInstanceId()) : null;
          (is_numeric(WTVRcleanString($this ->Screening->getScreeningVideoIsQueued()))) ? $itemarray["screening_video_is_queued"] = WTVRcleanString($this ->Screening->getScreeningVideoIsQueued()) : null;
          (WTVRcleanString($this ->Screening->getScreeningIsPrivate())) ? $itemarray["screening_is_private"] = WTVRcleanString($this ->Screening->getScreeningIsPrivate()) : null;
          (WTVRcleanString($this ->Screening->getScreeningHasQanda())) ? $itemarray["screening_has_qanda"] = WTVRcleanString($this ->Screening->getScreeningHasQanda()) : null;
          (WTVRcleanString($this ->Screening->getScreeningStillImage())) ? $itemarray["screening_still_image"] = WTVRcleanString($this ->Screening->getScreeningStillImage()) : null;
          (WTVRcleanString($this ->Screening->getScreeningChatModerated())) ? $itemarray["screening_chat_moderated"] = WTVRcleanString($this ->Screening->getScreeningChatModerated()) : null;
          (WTVRcleanString($this ->Screening->getScreeningChatQandaStarted())) ? $itemarray["screening_chat_qanda_started"] = WTVRcleanString($this ->Screening->getScreeningChatQandaStarted()) : null;
          (WTVRcleanString($this ->Screening->getScreeningAllowLatecomers())) ? $itemarray["screening_allow_latecomers"] = WTVRcleanString($this ->Screening->getScreeningAllowLatecomers()) : null;
          (WTVRcleanString($this ->Screening->getScreeningFacebookText())) ? $itemarray["screening_facebook_text"] = WTVRcleanString($this ->Screening->getScreeningFacebookText()) : null;
          (WTVRcleanString($this ->Screening->getScreeningTwitterText())) ? $itemarray["screening_twitter_text"] = WTVRcleanString($this ->Screening->getScreeningTwitterText()) : null;
          (WTVRcleanString($this ->Screening->getScreeningQa())) ? $itemarray["screening_qa"] = WTVRcleanString($this ->Screening->getScreeningQa()) : null;
          (WTVRcleanString($this ->Screening->getScreeningIsDohbr())) ? $itemarray["screening_is_dohbr"] = WTVRcleanString($this ->Screening->getScreeningIsDohbr()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Screening = ScreeningPeer::retrieveByPK( $id );
    } elseif (! $this ->Screening) {
      $this ->Screening = new Screening;
    }
        
  	 ($this -> getScreeningId())? $this ->Screening->setScreeningId( WTVRcleanString( $this -> getScreeningId()) ) : null;
    ($this -> getFkHostId())? $this ->Screening->setFkHostId( WTVRcleanString( $this -> getFkHostId()) ) : null;
    ($this -> getFkFilmId())? $this ->Screening->setFkFilmId( WTVRcleanString( $this -> getFkFilmId()) ) : null;
    ($this -> getFkPaymentId())? $this ->Screening->setFkPaymentId( WTVRcleanString( $this -> getFkPaymentId()) ) : null;
    ($this -> getFkProgramId())? $this ->Screening->setFkProgramId( WTVRcleanString( $this -> getFkProgramId()) ) : null;
    ($this -> getScreeningName())? $this ->Screening->setScreeningName( WTVRcleanString( $this -> getScreeningName()) ) : null;
    ($this -> getScreeningDate())? $this ->Screening->setScreeningDate( WTVRcleanString( $this -> getScreeningDate()) ) : null;
    ($this -> getScreeningTime())? $this ->Screening->setScreeningTime( WTVRcleanString( $this -> getScreeningTime()) ) : null;
          if (is_valid_date( $this ->Screening->getScreeningEndTime())) {
        $this ->Screening->setScreeningEndTime( formatDate($this -> getScreeningEndTime(), "TS" ));
      } else {
      $Screeningscreening_end_time = $this -> sfDateTime( "screening_end_time" );
      ( $Screeningscreening_end_time != "01/01/1900 00:00:00" )? $this ->Screening->setScreeningEndTime( formatDate($Screeningscreening_end_time, "TS" )) : $this ->Screening->setScreeningEndTime( null );
      }
    ($this -> getScreeningPrescreeningTime())? $this ->Screening->setScreeningPrescreeningTime( WTVRcleanString( $this -> getScreeningPrescreeningTime()) ) : null;
    ($this -> getScreeningPostMessage())? $this ->Screening->setScreeningPostMessage( WTVRcleanString( $this -> getScreeningPostMessage()) ) : null;
    ($this -> getScreeningPaidStatus())? $this ->Screening->setScreeningPaidStatus( WTVRcleanString( $this -> getScreeningPaidStatus()) ) : null;
    ($this -> getScreeningSeatsOccupied())? $this ->Screening->setScreeningSeatsOccupied( WTVRcleanString( $this -> getScreeningSeatsOccupied()) ) : null;
          if (is_valid_date( $this ->Screening->getScreeningCreatedAt())) {
        $this ->Screening->setScreeningCreatedAt( formatDate($this -> getScreeningCreatedAt(), "TS" ));
      } else {
      $Screeningscreening_created_at = $this -> sfDateTime( "screening_created_at" );
      ( $Screeningscreening_created_at != "01/01/1900 00:00:00" )? $this ->Screening->setScreeningCreatedAt( formatDate($Screeningscreening_created_at, "TS" )) : $this ->Screening->setScreeningCreatedAt( null );
      }
          if (is_valid_date( $this ->Screening->getScreeningUpdatedAt())) {
        $this ->Screening->setScreeningUpdatedAt( formatDate($this -> getScreeningUpdatedAt(), "TS" ));
      } else {
      $Screeningscreening_updated_at = $this -> sfDateTime( "screening_updated_at" );
      ( $Screeningscreening_updated_at != "01/01/1900 00:00:00" )? $this ->Screening->setScreeningUpdatedAt( formatDate($Screeningscreening_updated_at, "TS" )) : $this ->Screening->setScreeningUpdatedAt( null );
      }
    ($this -> getScreeningUniqueKey())? $this ->Screening->setScreeningUniqueKey( WTVRcleanString( $this -> getScreeningUniqueKey()) ) : null;
    ($this -> getScreeningStatus())? $this ->Screening->setScreeningStatus( WTVRcleanString( $this -> getScreeningStatus()) ) : null;
    ($this -> getScreeningType())? $this ->Screening->setScreeningType( WTVRcleanString( $this -> getScreeningType()) ) : null;
    ($this -> getScreeningTotalSeats())? $this ->Screening->setScreeningTotalSeats( WTVRcleanString( $this -> getScreeningTotalSeats()) ) : null;
    ($this -> getScreeningConstellationImage())? $this ->Screening->setScreeningConstellationImage( WTVRcleanString( $this -> getScreeningConstellationImage()) ) : null;
    ($this -> getScreeningGuestName())? $this ->Screening->setScreeningGuestName( WTVRcleanString( $this -> getScreeningGuestName()) ) : null;
    ($this -> getScreeningGuestImage())? $this ->Screening->setScreeningGuestImage( WTVRcleanString( $this -> getScreeningGuestImage()) ) : null;
    ($this -> getScreeningDescription())? $this ->Screening->setScreeningDescription( WTVRcleanString( $this -> getScreeningDescription()) ) : null;
    ($this -> getScreeningLiveWebcam())? $this ->Screening->setScreeningLiveWebcam( WTVRcleanString( $this -> getScreeningLiveWebcam()) ) : null;
    ($this -> getScreeningIsAdmin())? $this ->Screening->setScreeningIsAdmin( WTVRcleanString( $this -> getScreeningIsAdmin()) ) : null;
    ($this -> getScreeningFeatured())? $this ->Screening->setScreeningFeatured( WTVRcleanString( $this -> getScreeningFeatured()) ) : null;
    ($this -> getScreeningHighlighted())? $this ->Screening->setScreeningHighlighted( WTVRcleanString( $this -> getScreeningHighlighted()) ) : null;
    ($this -> getScreeningCreditStatus())? $this ->Screening->setScreeningCreditStatus( WTVRcleanString( $this -> getScreeningCreditStatus()) ) : null;
    ($this -> getScreeningDefaultTimezone())? $this ->Screening->setScreeningDefaultTimezone( WTVRcleanString( $this -> getScreeningDefaultTimezone()) ) : null;
    ($this -> getScreeningReceiptStatus())? $this ->Screening->setScreeningReceiptStatus( WTVRcleanString( $this -> getScreeningReceiptStatus()) ) : null;
    ($this -> getScreeningDefaultTimezoneId())? $this ->Screening->setScreeningDefaultTimezoneId( WTVRcleanString( $this -> getScreeningDefaultTimezoneId()) ) : null;
    ($this -> getScreeningVideoServerHostname())? $this ->Screening->setScreeningVideoServerHostname( WTVRcleanString( $this -> getScreeningVideoServerHostname()) ) : null;
    ($this -> getScreeningVideoServerInstanceId())? $this ->Screening->setScreeningVideoServerInstanceId( WTVRcleanString( $this -> getScreeningVideoServerInstanceId()) ) : null;
    ($this -> getScreeningVideoIsQueued())? $this ->Screening->setScreeningVideoIsQueued( WTVRcleanString( $this -> getScreeningVideoIsQueued()) ) : null;
    ($this -> getScreeningIsPrivate())? $this ->Screening->setScreeningIsPrivate( WTVRcleanString( $this -> getScreeningIsPrivate()) ) : null;
    ($this -> getScreeningHasQanda())? $this ->Screening->setScreeningHasQanda( WTVRcleanString( $this -> getScreeningHasQanda()) ) : null;
    ($this -> getScreeningStillImage())? $this ->Screening->setScreeningStillImage( WTVRcleanString( $this -> getScreeningStillImage()) ) : null;
    ($this -> getScreeningChatModerated())? $this ->Screening->setScreeningChatModerated( WTVRcleanString( $this -> getScreeningChatModerated()) ) : null;
    ($this -> getScreeningChatQandaStarted())? $this ->Screening->setScreeningChatQandaStarted( WTVRcleanString( $this -> getScreeningChatQandaStarted()) ) : null;
    ($this -> getScreeningAllowLatecomers())? $this ->Screening->setScreeningAllowLatecomers( WTVRcleanString( $this -> getScreeningAllowLatecomers()) ) : null;
    ($this -> getScreeningFacebookText())? $this ->Screening->setScreeningFacebookText( WTVRcleanString( $this -> getScreeningFacebookText()) ) : null;
    ($this -> getScreeningTwitterText())? $this ->Screening->setScreeningTwitterText( WTVRcleanString( $this -> getScreeningTwitterText()) ) : null;
    ($this -> getScreeningQa())? $this ->Screening->setScreeningQa( WTVRcleanString( $this -> getScreeningQa()) ) : null;
    ($this -> getScreeningIsDohbr())? $this ->Screening->setScreeningIsDohbr( WTVRcleanString( $this -> getScreeningIsDohbr()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Screening ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Screening = ScreeningPeer::retrieveByPK($id);
    }
    
    if (! $this ->Screening ) {
      return;
    }
    
    $this ->Screening -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Screening_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "ScreeningPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Screening = ScreeningPeer::doSelect($c);
    
    if (count($Screening) >= 1) {
      $this ->Screening = $Screening[0];
      return true;
    } else {
      $this ->Screening = new Screening();
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
      $name = "ScreeningPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Screening = ScreeningPeer::doSelect($c);
    
    if (count($Screening) >= 1) {
      $this ->Screening = $Screening[0];
      return true;
    } else {
      $this ->Screening = new Screening();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>