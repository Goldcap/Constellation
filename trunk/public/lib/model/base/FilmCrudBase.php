<?php
       
   class FilmCrudBase extends Utils_PageWidget { 
   
    var $Film;
   
       var $film_id;
   var $film_name;
   var $film_alt_name;
   var $film_makers;
   var $film_production_company;
   var $film_logo;
   var $film_homelogo;
   var $film_trailer_file;
   var $film_movie_file;
   var $film_maker_message;
   var $film_ticket_price;
   var $film_hostbyrequest_price;
   var $film_status;
   var $film_featured;
   var $film_created_at;
   var $film_updated_at;
   var $film_setup_price;
   var $film_info;
   var $film_cast;
   var $film_running_time;
   var $film_total_seats;
   var $film_short_name;
   var $film_synopsis;
   var $film_still_image;
   var $film_background_image;
   var $film_splash_image;
   var $film_geoblocking_enabled;
   var $film_geoblocking_type;
   var $film_short_url;
   var $film_review;
   var $film_startdate;
   var $film_enddate;
   var $fk_film_sponsor_id;
   var $film_bitrate_minimum;
   var $film_bitrate_low;
   var $film_bitrate_small;
   var $film_bitrate_medium;
   var $film_bitrate_large;
   var $film_bitrate_largest;
   var $film_use_sponsor_codes;
   var $film_allow_hostbyrequest;
   var $film_allow_user_hosting;
   var $film_alternate_template;
   var $film_alternate_opts;
   var $film_cdn;
   var $film_share;
   var $film_preuser;
   var $film_freewithinvite;
   var $film_free_screening;
   var $film_twitter_tags;
   var $fk_user_id;
   var $film_website;
   var $film_facebook;
   var $film_twitter;
   var $film_youtube_trailer;
   var $film_ooyala_embed;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getFilmId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Film = FilmPeer::retrieveByPK( $id );
    } else {
      $this ->Film = new Film;
    }
  }
  
  function hydrate( $id ) {
      $this ->Film = FilmPeer::retrieveByPK( $id );
  }
  
  function getFilmId() {
    if (($this ->postVar("film_id")) || ($this ->postVar("film_id") === "")) {
      return $this ->postVar("film_id");
    } elseif (($this ->getVar("film_id")) || ($this ->getVar("film_id") === "")) {
      return $this ->getVar("film_id");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmId();
    } elseif (($this ->sessionVar("film_id")) || ($this ->sessionVar("film_id") == "")) {
      return $this ->sessionVar("film_id");
    } else {
      return false;
    }
  }
  
  function setFilmId( $str ) {
    $this ->Film -> setFilmId( $str );
  }
  
  function getFilmName() {
    if (($this ->postVar("film_name")) || ($this ->postVar("film_name") === "")) {
      return $this ->postVar("film_name");
    } elseif (($this ->getVar("film_name")) || ($this ->getVar("film_name") === "")) {
      return $this ->getVar("film_name");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmName();
    } elseif (($this ->sessionVar("film_name")) || ($this ->sessionVar("film_name") == "")) {
      return $this ->sessionVar("film_name");
    } else {
      return false;
    }
  }
  
  function setFilmName( $str ) {
    $this ->Film -> setFilmName( $str );
  }
  
  function getFilmAltName() {
    if (($this ->postVar("film_alt_name")) || ($this ->postVar("film_alt_name") === "")) {
      return $this ->postVar("film_alt_name");
    } elseif (($this ->getVar("film_alt_name")) || ($this ->getVar("film_alt_name") === "")) {
      return $this ->getVar("film_alt_name");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmAltName();
    } elseif (($this ->sessionVar("film_alt_name")) || ($this ->sessionVar("film_alt_name") == "")) {
      return $this ->sessionVar("film_alt_name");
    } else {
      return false;
    }
  }
  
  function setFilmAltName( $str ) {
    $this ->Film -> setFilmAltName( $str );
  }
  
  function getFilmMakers() {
    if (($this ->postVar("film_makers")) || ($this ->postVar("film_makers") === "")) {
      return $this ->postVar("film_makers");
    } elseif (($this ->getVar("film_makers")) || ($this ->getVar("film_makers") === "")) {
      return $this ->getVar("film_makers");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmMakers();
    } elseif (($this ->sessionVar("film_makers")) || ($this ->sessionVar("film_makers") == "")) {
      return $this ->sessionVar("film_makers");
    } else {
      return false;
    }
  }
  
  function setFilmMakers( $str ) {
    $this ->Film -> setFilmMakers( $str );
  }
  
  function getFilmProductionCompany() {
    if (($this ->postVar("film_production_company")) || ($this ->postVar("film_production_company") === "")) {
      return $this ->postVar("film_production_company");
    } elseif (($this ->getVar("film_production_company")) || ($this ->getVar("film_production_company") === "")) {
      return $this ->getVar("film_production_company");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmProductionCompany();
    } elseif (($this ->sessionVar("film_production_company")) || ($this ->sessionVar("film_production_company") == "")) {
      return $this ->sessionVar("film_production_company");
    } else {
      return false;
    }
  }
  
  function setFilmProductionCompany( $str ) {
    $this ->Film -> setFilmProductionCompany( $str );
  }
  
  function getFilmLogo() {
    if (($this ->postVar("film_logo")) || ($this ->postVar("film_logo") === "")) {
      return $this ->postVar("film_logo");
    } elseif (($this ->getVar("film_logo")) || ($this ->getVar("film_logo") === "")) {
      return $this ->getVar("film_logo");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmLogo();
    } elseif (($this ->sessionVar("film_logo")) || ($this ->sessionVar("film_logo") == "")) {
      return $this ->sessionVar("film_logo");
    } else {
      return false;
    }
  }
  
  function setFilmLogo( $str ) {
    $this ->Film -> setFilmLogo( $str );
  }
  
  function getFilmHomelogo() {
    if (($this ->postVar("film_homelogo")) || ($this ->postVar("film_homelogo") === "")) {
      return $this ->postVar("film_homelogo");
    } elseif (($this ->getVar("film_homelogo")) || ($this ->getVar("film_homelogo") === "")) {
      return $this ->getVar("film_homelogo");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmHomelogo();
    } elseif (($this ->sessionVar("film_homelogo")) || ($this ->sessionVar("film_homelogo") == "")) {
      return $this ->sessionVar("film_homelogo");
    } else {
      return false;
    }
  }
  
  function setFilmHomelogo( $str ) {
    $this ->Film -> setFilmHomelogo( $str );
  }
  
  function getFilmTrailerFile() {
    if (($this ->postVar("film_trailer_file")) || ($this ->postVar("film_trailer_file") === "")) {
      return $this ->postVar("film_trailer_file");
    } elseif (($this ->getVar("film_trailer_file")) || ($this ->getVar("film_trailer_file") === "")) {
      return $this ->getVar("film_trailer_file");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmTrailerFile();
    } elseif (($this ->sessionVar("film_trailer_file")) || ($this ->sessionVar("film_trailer_file") == "")) {
      return $this ->sessionVar("film_trailer_file");
    } else {
      return false;
    }
  }
  
  function setFilmTrailerFile( $str ) {
    $this ->Film -> setFilmTrailerFile( $str );
  }
  
  function getFilmMovieFile() {
    if (($this ->postVar("film_movie_file")) || ($this ->postVar("film_movie_file") === "")) {
      return $this ->postVar("film_movie_file");
    } elseif (($this ->getVar("film_movie_file")) || ($this ->getVar("film_movie_file") === "")) {
      return $this ->getVar("film_movie_file");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmMovieFile();
    } elseif (($this ->sessionVar("film_movie_file")) || ($this ->sessionVar("film_movie_file") == "")) {
      return $this ->sessionVar("film_movie_file");
    } else {
      return false;
    }
  }
  
  function setFilmMovieFile( $str ) {
    $this ->Film -> setFilmMovieFile( $str );
  }
  
  function getFilmMakerMessage() {
    if (($this ->postVar("film_maker_message")) || ($this ->postVar("film_maker_message") === "")) {
      return $this ->postVar("film_maker_message");
    } elseif (($this ->getVar("film_maker_message")) || ($this ->getVar("film_maker_message") === "")) {
      return $this ->getVar("film_maker_message");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmMakerMessage();
    } elseif (($this ->sessionVar("film_maker_message")) || ($this ->sessionVar("film_maker_message") == "")) {
      return $this ->sessionVar("film_maker_message");
    } else {
      return false;
    }
  }
  
  function setFilmMakerMessage( $str ) {
    $this ->Film -> setFilmMakerMessage( $str );
  }
  
  function getFilmTicketPrice() {
    if (($this ->postVar("film_ticket_price")) || ($this ->postVar("film_ticket_price") === "")) {
      return $this ->postVar("film_ticket_price");
    } elseif (($this ->getVar("film_ticket_price")) || ($this ->getVar("film_ticket_price") === "")) {
      return $this ->getVar("film_ticket_price");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmTicketPrice();
    } elseif (($this ->sessionVar("film_ticket_price")) || ($this ->sessionVar("film_ticket_price") == "")) {
      return $this ->sessionVar("film_ticket_price");
    } else {
      return false;
    }
  }
  
  function setFilmTicketPrice( $str ) {
    $this ->Film -> setFilmTicketPrice( $str );
  }
  
  function getFilmHostbyrequestPrice() {
    if (($this ->postVar("film_hostbyrequest_price")) || ($this ->postVar("film_hostbyrequest_price") === "")) {
      return $this ->postVar("film_hostbyrequest_price");
    } elseif (($this ->getVar("film_hostbyrequest_price")) || ($this ->getVar("film_hostbyrequest_price") === "")) {
      return $this ->getVar("film_hostbyrequest_price");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmHostbyrequestPrice();
    } elseif (($this ->sessionVar("film_hostbyrequest_price")) || ($this ->sessionVar("film_hostbyrequest_price") == "")) {
      return $this ->sessionVar("film_hostbyrequest_price");
    } else {
      return false;
    }
  }
  
  function setFilmHostbyrequestPrice( $str ) {
    $this ->Film -> setFilmHostbyrequestPrice( $str );
  }
  
  function getFilmStatus() {
    if (($this ->postVar("film_status")) || ($this ->postVar("film_status") === "")) {
      return $this ->postVar("film_status");
    } elseif (($this ->getVar("film_status")) || ($this ->getVar("film_status") === "")) {
      return $this ->getVar("film_status");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmStatus();
    } elseif (($this ->sessionVar("film_status")) || ($this ->sessionVar("film_status") == "")) {
      return $this ->sessionVar("film_status");
    } else {
      return false;
    }
  }
  
  function setFilmStatus( $str ) {
    $this ->Film -> setFilmStatus( $str );
  }
  
  function getFilmFeatured() {
    if (($this ->postVar("film_featured")) || ($this ->postVar("film_featured") === "")) {
      return $this ->postVar("film_featured");
    } elseif (($this ->getVar("film_featured")) || ($this ->getVar("film_featured") === "")) {
      return $this ->getVar("film_featured");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmFeatured();
    } elseif (($this ->sessionVar("film_featured")) || ($this ->sessionVar("film_featured") == "")) {
      return $this ->sessionVar("film_featured");
    } else {
      return false;
    }
  }
  
  function setFilmFeatured( $str ) {
    $this ->Film -> setFilmFeatured( $str );
  }
  
  function getFilmCreatedAt() {
    if (($this ->postVar("film_created_at")) || ($this ->postVar("film_created_at") === "")) {
      return $this ->postVar("film_created_at");
    } elseif (($this ->getVar("film_created_at")) || ($this ->getVar("film_created_at") === "")) {
      return $this ->getVar("film_created_at");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmCreatedAt();
    } elseif (($this ->sessionVar("film_created_at")) || ($this ->sessionVar("film_created_at") == "")) {
      return $this ->sessionVar("film_created_at");
    } else {
      return false;
    }
  }
  
  function setFilmCreatedAt( $str ) {
    $this ->Film -> setFilmCreatedAt( $str );
  }
  
  function getFilmUpdatedAt() {
    if (($this ->postVar("film_updated_at")) || ($this ->postVar("film_updated_at") === "")) {
      return $this ->postVar("film_updated_at");
    } elseif (($this ->getVar("film_updated_at")) || ($this ->getVar("film_updated_at") === "")) {
      return $this ->getVar("film_updated_at");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmUpdatedAt();
    } elseif (($this ->sessionVar("film_updated_at")) || ($this ->sessionVar("film_updated_at") == "")) {
      return $this ->sessionVar("film_updated_at");
    } else {
      return false;
    }
  }
  
  function setFilmUpdatedAt( $str ) {
    $this ->Film -> setFilmUpdatedAt( $str );
  }
  
  function getFilmSetupPrice() {
    if (($this ->postVar("film_setup_price")) || ($this ->postVar("film_setup_price") === "")) {
      return $this ->postVar("film_setup_price");
    } elseif (($this ->getVar("film_setup_price")) || ($this ->getVar("film_setup_price") === "")) {
      return $this ->getVar("film_setup_price");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmSetupPrice();
    } elseif (($this ->sessionVar("film_setup_price")) || ($this ->sessionVar("film_setup_price") == "")) {
      return $this ->sessionVar("film_setup_price");
    } else {
      return false;
    }
  }
  
  function setFilmSetupPrice( $str ) {
    $this ->Film -> setFilmSetupPrice( $str );
  }
  
  function getFilmInfo() {
    if (($this ->postVar("film_info")) || ($this ->postVar("film_info") === "")) {
      return $this ->postVar("film_info");
    } elseif (($this ->getVar("film_info")) || ($this ->getVar("film_info") === "")) {
      return $this ->getVar("film_info");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmInfo();
    } elseif (($this ->sessionVar("film_info")) || ($this ->sessionVar("film_info") == "")) {
      return $this ->sessionVar("film_info");
    } else {
      return false;
    }
  }
  
  function setFilmInfo( $str ) {
    $this ->Film -> setFilmInfo( $str );
  }
  
  function getFilmCast() {
    if (($this ->postVar("film_cast")) || ($this ->postVar("film_cast") === "")) {
      return $this ->postVar("film_cast");
    } elseif (($this ->getVar("film_cast")) || ($this ->getVar("film_cast") === "")) {
      return $this ->getVar("film_cast");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmCast();
    } elseif (($this ->sessionVar("film_cast")) || ($this ->sessionVar("film_cast") == "")) {
      return $this ->sessionVar("film_cast");
    } else {
      return false;
    }
  }
  
  function setFilmCast( $str ) {
    $this ->Film -> setFilmCast( $str );
  }
  
  function getFilmRunningTime() {
    if (($this ->postVar("film_running_time")) || ($this ->postVar("film_running_time") === "")) {
      return $this ->postVar("film_running_time");
    } elseif (($this ->getVar("film_running_time")) || ($this ->getVar("film_running_time") === "")) {
      return $this ->getVar("film_running_time");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmRunningTime();
    } elseif (($this ->sessionVar("film_running_time")) || ($this ->sessionVar("film_running_time") == "")) {
      return $this ->sessionVar("film_running_time");
    } else {
      return false;
    }
  }
  
  function setFilmRunningTime( $str ) {
    $this ->Film -> setFilmRunningTime( $str );
  }
  
  function getFilmTotalSeats() {
    if (($this ->postVar("film_total_seats")) || ($this ->postVar("film_total_seats") === "")) {
      return $this ->postVar("film_total_seats");
    } elseif (($this ->getVar("film_total_seats")) || ($this ->getVar("film_total_seats") === "")) {
      return $this ->getVar("film_total_seats");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmTotalSeats();
    } elseif (($this ->sessionVar("film_total_seats")) || ($this ->sessionVar("film_total_seats") == "")) {
      return $this ->sessionVar("film_total_seats");
    } else {
      return false;
    }
  }
  
  function setFilmTotalSeats( $str ) {
    $this ->Film -> setFilmTotalSeats( $str );
  }
  
  function getFilmShortName() {
    if (($this ->postVar("film_short_name")) || ($this ->postVar("film_short_name") === "")) {
      return $this ->postVar("film_short_name");
    } elseif (($this ->getVar("film_short_name")) || ($this ->getVar("film_short_name") === "")) {
      return $this ->getVar("film_short_name");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmShortName();
    } elseif (($this ->sessionVar("film_short_name")) || ($this ->sessionVar("film_short_name") == "")) {
      return $this ->sessionVar("film_short_name");
    } else {
      return false;
    }
  }
  
  function setFilmShortName( $str ) {
    $this ->Film -> setFilmShortName( $str );
  }
  
  function getFilmSynopsis() {
    if (($this ->postVar("film_synopsis")) || ($this ->postVar("film_synopsis") === "")) {
      return $this ->postVar("film_synopsis");
    } elseif (($this ->getVar("film_synopsis")) || ($this ->getVar("film_synopsis") === "")) {
      return $this ->getVar("film_synopsis");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmSynopsis();
    } elseif (($this ->sessionVar("film_synopsis")) || ($this ->sessionVar("film_synopsis") == "")) {
      return $this ->sessionVar("film_synopsis");
    } else {
      return false;
    }
  }
  
  function setFilmSynopsis( $str ) {
    $this ->Film -> setFilmSynopsis( $str );
  }
  
  function getFilmStillImage() {
    if (($this ->postVar("film_still_image")) || ($this ->postVar("film_still_image") === "")) {
      return $this ->postVar("film_still_image");
    } elseif (($this ->getVar("film_still_image")) || ($this ->getVar("film_still_image") === "")) {
      return $this ->getVar("film_still_image");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmStillImage();
    } elseif (($this ->sessionVar("film_still_image")) || ($this ->sessionVar("film_still_image") == "")) {
      return $this ->sessionVar("film_still_image");
    } else {
      return false;
    }
  }
  
  function setFilmStillImage( $str ) {
    $this ->Film -> setFilmStillImage( $str );
  }
  
  function getFilmBackgroundImage() {
    if (($this ->postVar("film_background_image")) || ($this ->postVar("film_background_image") === "")) {
      return $this ->postVar("film_background_image");
    } elseif (($this ->getVar("film_background_image")) || ($this ->getVar("film_background_image") === "")) {
      return $this ->getVar("film_background_image");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmBackgroundImage();
    } elseif (($this ->sessionVar("film_background_image")) || ($this ->sessionVar("film_background_image") == "")) {
      return $this ->sessionVar("film_background_image");
    } else {
      return false;
    }
  }
  
  function setFilmBackgroundImage( $str ) {
    $this ->Film -> setFilmBackgroundImage( $str );
  }
  
  function getFilmSplashImage() {
    if (($this ->postVar("film_splash_image")) || ($this ->postVar("film_splash_image") === "")) {
      return $this ->postVar("film_splash_image");
    } elseif (($this ->getVar("film_splash_image")) || ($this ->getVar("film_splash_image") === "")) {
      return $this ->getVar("film_splash_image");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmSplashImage();
    } elseif (($this ->sessionVar("film_splash_image")) || ($this ->sessionVar("film_splash_image") == "")) {
      return $this ->sessionVar("film_splash_image");
    } else {
      return false;
    }
  }
  
  function setFilmSplashImage( $str ) {
    $this ->Film -> setFilmSplashImage( $str );
  }
  
  function getFilmGeoblockingEnabled() {
    if (($this ->postVar("film_geoblocking_enabled")) || ($this ->postVar("film_geoblocking_enabled") === "")) {
      return $this ->postVar("film_geoblocking_enabled");
    } elseif (($this ->getVar("film_geoblocking_enabled")) || ($this ->getVar("film_geoblocking_enabled") === "")) {
      return $this ->getVar("film_geoblocking_enabled");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmGeoblockingEnabled();
    } elseif (($this ->sessionVar("film_geoblocking_enabled")) || ($this ->sessionVar("film_geoblocking_enabled") == "")) {
      return $this ->sessionVar("film_geoblocking_enabled");
    } else {
      return false;
    }
  }
  
  function setFilmGeoblockingEnabled( $str ) {
    $this ->Film -> setFilmGeoblockingEnabled( $str );
  }
  
  function getFilmGeoblockingType() {
    if (($this ->postVar("film_geoblocking_type")) || ($this ->postVar("film_geoblocking_type") === "")) {
      return $this ->postVar("film_geoblocking_type");
    } elseif (($this ->getVar("film_geoblocking_type")) || ($this ->getVar("film_geoblocking_type") === "")) {
      return $this ->getVar("film_geoblocking_type");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmGeoblockingType();
    } elseif (($this ->sessionVar("film_geoblocking_type")) || ($this ->sessionVar("film_geoblocking_type") == "")) {
      return $this ->sessionVar("film_geoblocking_type");
    } else {
      return false;
    }
  }
  
  function setFilmGeoblockingType( $str ) {
    $this ->Film -> setFilmGeoblockingType( $str );
  }
  
  function getFilmShortUrl() {
    if (($this ->postVar("film_short_url")) || ($this ->postVar("film_short_url") === "")) {
      return $this ->postVar("film_short_url");
    } elseif (($this ->getVar("film_short_url")) || ($this ->getVar("film_short_url") === "")) {
      return $this ->getVar("film_short_url");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmShortUrl();
    } elseif (($this ->sessionVar("film_short_url")) || ($this ->sessionVar("film_short_url") == "")) {
      return $this ->sessionVar("film_short_url");
    } else {
      return false;
    }
  }
  
  function setFilmShortUrl( $str ) {
    $this ->Film -> setFilmShortUrl( $str );
  }
  
  function getFilmReview() {
    if (($this ->postVar("film_review")) || ($this ->postVar("film_review") === "")) {
      return $this ->postVar("film_review");
    } elseif (($this ->getVar("film_review")) || ($this ->getVar("film_review") === "")) {
      return $this ->getVar("film_review");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmReview();
    } elseif (($this ->sessionVar("film_review")) || ($this ->sessionVar("film_review") == "")) {
      return $this ->sessionVar("film_review");
    } else {
      return false;
    }
  }
  
  function setFilmReview( $str ) {
    $this ->Film -> setFilmReview( $str );
  }
  
  function getFilmStartdate() {
    if (($this ->postVar("film_startdate")) || ($this ->postVar("film_startdate") === "")) {
      return $this ->postVar("film_startdate");
    } elseif (($this ->getVar("film_startdate")) || ($this ->getVar("film_startdate") === "")) {
      return $this ->getVar("film_startdate");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmStartdate();
    } elseif (($this ->sessionVar("film_startdate")) || ($this ->sessionVar("film_startdate") == "")) {
      return $this ->sessionVar("film_startdate");
    } else {
      return false;
    }
  }
  
  function setFilmStartdate( $str ) {
    $this ->Film -> setFilmStartdate( $str );
  }
  
  function getFilmEnddate() {
    if (($this ->postVar("film_enddate")) || ($this ->postVar("film_enddate") === "")) {
      return $this ->postVar("film_enddate");
    } elseif (($this ->getVar("film_enddate")) || ($this ->getVar("film_enddate") === "")) {
      return $this ->getVar("film_enddate");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmEnddate();
    } elseif (($this ->sessionVar("film_enddate")) || ($this ->sessionVar("film_enddate") == "")) {
      return $this ->sessionVar("film_enddate");
    } else {
      return false;
    }
  }
  
  function setFilmEnddate( $str ) {
    $this ->Film -> setFilmEnddate( $str );
  }
  
  function getFkFilmSponsorId() {
    if (($this ->postVar("fk_film_sponsor_id")) || ($this ->postVar("fk_film_sponsor_id") === "")) {
      return $this ->postVar("fk_film_sponsor_id");
    } elseif (($this ->getVar("fk_film_sponsor_id")) || ($this ->getVar("fk_film_sponsor_id") === "")) {
      return $this ->getVar("fk_film_sponsor_id");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFkFilmSponsorId();
    } elseif (($this ->sessionVar("fk_film_sponsor_id")) || ($this ->sessionVar("fk_film_sponsor_id") == "")) {
      return $this ->sessionVar("fk_film_sponsor_id");
    } else {
      return false;
    }
  }
  
  function setFkFilmSponsorId( $str ) {
    $this ->Film -> setFkFilmSponsorId( $str );
  }
  
  function getFilmBitrateMinimum() {
    if (($this ->postVar("film_bitrate_minimum")) || ($this ->postVar("film_bitrate_minimum") === "")) {
      return $this ->postVar("film_bitrate_minimum");
    } elseif (($this ->getVar("film_bitrate_minimum")) || ($this ->getVar("film_bitrate_minimum") === "")) {
      return $this ->getVar("film_bitrate_minimum");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmBitrateMinimum();
    } elseif (($this ->sessionVar("film_bitrate_minimum")) || ($this ->sessionVar("film_bitrate_minimum") == "")) {
      return $this ->sessionVar("film_bitrate_minimum");
    } else {
      return false;
    }
  }
  
  function setFilmBitrateMinimum( $str ) {
    $this ->Film -> setFilmBitrateMinimum( $str );
  }
  
  function getFilmBitrateLow() {
    if (($this ->postVar("film_bitrate_low")) || ($this ->postVar("film_bitrate_low") === "")) {
      return $this ->postVar("film_bitrate_low");
    } elseif (($this ->getVar("film_bitrate_low")) || ($this ->getVar("film_bitrate_low") === "")) {
      return $this ->getVar("film_bitrate_low");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmBitrateLow();
    } elseif (($this ->sessionVar("film_bitrate_low")) || ($this ->sessionVar("film_bitrate_low") == "")) {
      return $this ->sessionVar("film_bitrate_low");
    } else {
      return false;
    }
  }
  
  function setFilmBitrateLow( $str ) {
    $this ->Film -> setFilmBitrateLow( $str );
  }
  
  function getFilmBitrateSmall() {
    if (($this ->postVar("film_bitrate_small")) || ($this ->postVar("film_bitrate_small") === "")) {
      return $this ->postVar("film_bitrate_small");
    } elseif (($this ->getVar("film_bitrate_small")) || ($this ->getVar("film_bitrate_small") === "")) {
      return $this ->getVar("film_bitrate_small");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmBitrateSmall();
    } elseif (($this ->sessionVar("film_bitrate_small")) || ($this ->sessionVar("film_bitrate_small") == "")) {
      return $this ->sessionVar("film_bitrate_small");
    } else {
      return false;
    }
  }
  
  function setFilmBitrateSmall( $str ) {
    $this ->Film -> setFilmBitrateSmall( $str );
  }
  
  function getFilmBitrateMedium() {
    if (($this ->postVar("film_bitrate_medium")) || ($this ->postVar("film_bitrate_medium") === "")) {
      return $this ->postVar("film_bitrate_medium");
    } elseif (($this ->getVar("film_bitrate_medium")) || ($this ->getVar("film_bitrate_medium") === "")) {
      return $this ->getVar("film_bitrate_medium");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmBitrateMedium();
    } elseif (($this ->sessionVar("film_bitrate_medium")) || ($this ->sessionVar("film_bitrate_medium") == "")) {
      return $this ->sessionVar("film_bitrate_medium");
    } else {
      return false;
    }
  }
  
  function setFilmBitrateMedium( $str ) {
    $this ->Film -> setFilmBitrateMedium( $str );
  }
  
  function getFilmBitrateLarge() {
    if (($this ->postVar("film_bitrate_large")) || ($this ->postVar("film_bitrate_large") === "")) {
      return $this ->postVar("film_bitrate_large");
    } elseif (($this ->getVar("film_bitrate_large")) || ($this ->getVar("film_bitrate_large") === "")) {
      return $this ->getVar("film_bitrate_large");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmBitrateLarge();
    } elseif (($this ->sessionVar("film_bitrate_large")) || ($this ->sessionVar("film_bitrate_large") == "")) {
      return $this ->sessionVar("film_bitrate_large");
    } else {
      return false;
    }
  }
  
  function setFilmBitrateLarge( $str ) {
    $this ->Film -> setFilmBitrateLarge( $str );
  }
  
  function getFilmBitrateLargest() {
    if (($this ->postVar("film_bitrate_largest")) || ($this ->postVar("film_bitrate_largest") === "")) {
      return $this ->postVar("film_bitrate_largest");
    } elseif (($this ->getVar("film_bitrate_largest")) || ($this ->getVar("film_bitrate_largest") === "")) {
      return $this ->getVar("film_bitrate_largest");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmBitrateLargest();
    } elseif (($this ->sessionVar("film_bitrate_largest")) || ($this ->sessionVar("film_bitrate_largest") == "")) {
      return $this ->sessionVar("film_bitrate_largest");
    } else {
      return false;
    }
  }
  
  function setFilmBitrateLargest( $str ) {
    $this ->Film -> setFilmBitrateLargest( $str );
  }
  
  function getFilmUseSponsorCodes() {
    if (($this ->postVar("film_use_sponsor_codes")) || ($this ->postVar("film_use_sponsor_codes") === "")) {
      return $this ->postVar("film_use_sponsor_codes");
    } elseif (($this ->getVar("film_use_sponsor_codes")) || ($this ->getVar("film_use_sponsor_codes") === "")) {
      return $this ->getVar("film_use_sponsor_codes");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmUseSponsorCodes();
    } elseif (($this ->sessionVar("film_use_sponsor_codes")) || ($this ->sessionVar("film_use_sponsor_codes") == "")) {
      return $this ->sessionVar("film_use_sponsor_codes");
    } else {
      return false;
    }
  }
  
  function setFilmUseSponsorCodes( $str ) {
    $this ->Film -> setFilmUseSponsorCodes( $str );
  }
  
  function getFilmAllowHostbyrequest() {
    if (($this ->postVar("film_allow_hostbyrequest")) || ($this ->postVar("film_allow_hostbyrequest") === "")) {
      return $this ->postVar("film_allow_hostbyrequest");
    } elseif (($this ->getVar("film_allow_hostbyrequest")) || ($this ->getVar("film_allow_hostbyrequest") === "")) {
      return $this ->getVar("film_allow_hostbyrequest");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmAllowHostbyrequest();
    } elseif (($this ->sessionVar("film_allow_hostbyrequest")) || ($this ->sessionVar("film_allow_hostbyrequest") == "")) {
      return $this ->sessionVar("film_allow_hostbyrequest");
    } else {
      return false;
    }
  }
  
  function setFilmAllowHostbyrequest( $str ) {
    $this ->Film -> setFilmAllowHostbyrequest( $str );
  }
  
  function getFilmAllowUserHosting() {
    if (($this ->postVar("film_allow_user_hosting")) || ($this ->postVar("film_allow_user_hosting") === "")) {
      return $this ->postVar("film_allow_user_hosting");
    } elseif (($this ->getVar("film_allow_user_hosting")) || ($this ->getVar("film_allow_user_hosting") === "")) {
      return $this ->getVar("film_allow_user_hosting");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmAllowUserHosting();
    } elseif (($this ->sessionVar("film_allow_user_hosting")) || ($this ->sessionVar("film_allow_user_hosting") == "")) {
      return $this ->sessionVar("film_allow_user_hosting");
    } else {
      return false;
    }
  }
  
  function setFilmAllowUserHosting( $str ) {
    $this ->Film -> setFilmAllowUserHosting( $str );
  }
  
  function getFilmAlternateTemplate() {
    if (($this ->postVar("film_alternate_template")) || ($this ->postVar("film_alternate_template") === "")) {
      return $this ->postVar("film_alternate_template");
    } elseif (($this ->getVar("film_alternate_template")) || ($this ->getVar("film_alternate_template") === "")) {
      return $this ->getVar("film_alternate_template");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmAlternateTemplate();
    } elseif (($this ->sessionVar("film_alternate_template")) || ($this ->sessionVar("film_alternate_template") == "")) {
      return $this ->sessionVar("film_alternate_template");
    } else {
      return false;
    }
  }
  
  function setFilmAlternateTemplate( $str ) {
    $this ->Film -> setFilmAlternateTemplate( $str );
  }
  
  function getFilmAlternateOpts() {
    if (($this ->postVar("film_alternate_opts")) || ($this ->postVar("film_alternate_opts") === "")) {
      return $this ->postVar("film_alternate_opts");
    } elseif (($this ->getVar("film_alternate_opts")) || ($this ->getVar("film_alternate_opts") === "")) {
      return $this ->getVar("film_alternate_opts");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmAlternateOpts();
    } elseif (($this ->sessionVar("film_alternate_opts")) || ($this ->sessionVar("film_alternate_opts") == "")) {
      return $this ->sessionVar("film_alternate_opts");
    } else {
      return false;
    }
  }
  
  function setFilmAlternateOpts( $str ) {
    $this ->Film -> setFilmAlternateOpts( $str );
  }
  
  function getFilmCdn() {
    if (($this ->postVar("film_cdn")) || ($this ->postVar("film_cdn") === "")) {
      return $this ->postVar("film_cdn");
    } elseif (($this ->getVar("film_cdn")) || ($this ->getVar("film_cdn") === "")) {
      return $this ->getVar("film_cdn");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmCdn();
    } elseif (($this ->sessionVar("film_cdn")) || ($this ->sessionVar("film_cdn") == "")) {
      return $this ->sessionVar("film_cdn");
    } else {
      return false;
    }
  }
  
  function setFilmCdn( $str ) {
    $this ->Film -> setFilmCdn( $str );
  }
  
  function getFilmShare() {
    if (($this ->postVar("film_share")) || ($this ->postVar("film_share") === "")) {
      return $this ->postVar("film_share");
    } elseif (($this ->getVar("film_share")) || ($this ->getVar("film_share") === "")) {
      return $this ->getVar("film_share");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmShare();
    } elseif (($this ->sessionVar("film_share")) || ($this ->sessionVar("film_share") == "")) {
      return $this ->sessionVar("film_share");
    } else {
      return false;
    }
  }
  
  function setFilmShare( $str ) {
    $this ->Film -> setFilmShare( $str );
  }
  
  function getFilmPreuser() {
    if (($this ->postVar("film_preuser")) || ($this ->postVar("film_preuser") === "")) {
      return $this ->postVar("film_preuser");
    } elseif (($this ->getVar("film_preuser")) || ($this ->getVar("film_preuser") === "")) {
      return $this ->getVar("film_preuser");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmPreuser();
    } elseif (($this ->sessionVar("film_preuser")) || ($this ->sessionVar("film_preuser") == "")) {
      return $this ->sessionVar("film_preuser");
    } else {
      return false;
    }
  }
  
  function setFilmPreuser( $str ) {
    $this ->Film -> setFilmPreuser( $str );
  }
  
  function getFilmFreewithinvite() {
    if (($this ->postVar("film_freewithinvite")) || ($this ->postVar("film_freewithinvite") === "")) {
      return $this ->postVar("film_freewithinvite");
    } elseif (($this ->getVar("film_freewithinvite")) || ($this ->getVar("film_freewithinvite") === "")) {
      return $this ->getVar("film_freewithinvite");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmFreewithinvite();
    } elseif (($this ->sessionVar("film_freewithinvite")) || ($this ->sessionVar("film_freewithinvite") == "")) {
      return $this ->sessionVar("film_freewithinvite");
    } else {
      return false;
    }
  }
  
  function setFilmFreewithinvite( $str ) {
    $this ->Film -> setFilmFreewithinvite( $str );
  }
  
  function getFilmFreeScreening() {
    if (($this ->postVar("film_free_screening")) || ($this ->postVar("film_free_screening") === "")) {
      return $this ->postVar("film_free_screening");
    } elseif (($this ->getVar("film_free_screening")) || ($this ->getVar("film_free_screening") === "")) {
      return $this ->getVar("film_free_screening");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmFreeScreening();
    } elseif (($this ->sessionVar("film_free_screening")) || ($this ->sessionVar("film_free_screening") == "")) {
      return $this ->sessionVar("film_free_screening");
    } else {
      return false;
    }
  }
  
  function setFilmFreeScreening( $str ) {
    $this ->Film -> setFilmFreeScreening( $str );
  }
  
  function getFilmTwitterTags() {
    if (($this ->postVar("film_twitter_tags")) || ($this ->postVar("film_twitter_tags") === "")) {
      return $this ->postVar("film_twitter_tags");
    } elseif (($this ->getVar("film_twitter_tags")) || ($this ->getVar("film_twitter_tags") === "")) {
      return $this ->getVar("film_twitter_tags");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmTwitterTags();
    } elseif (($this ->sessionVar("film_twitter_tags")) || ($this ->sessionVar("film_twitter_tags") == "")) {
      return $this ->sessionVar("film_twitter_tags");
    } else {
      return false;
    }
  }
  
  function setFilmTwitterTags( $str ) {
    $this ->Film -> setFilmTwitterTags( $str );
  }
  
  function getFkUserId() {
    if (($this ->postVar("fk_user_id")) || ($this ->postVar("fk_user_id") === "")) {
      return $this ->postVar("fk_user_id");
    } elseif (($this ->getVar("fk_user_id")) || ($this ->getVar("fk_user_id") === "")) {
      return $this ->getVar("fk_user_id");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFkUserId();
    } elseif (($this ->sessionVar("fk_user_id")) || ($this ->sessionVar("fk_user_id") == "")) {
      return $this ->sessionVar("fk_user_id");
    } else {
      return false;
    }
  }
  
  function setFkUserId( $str ) {
    $this ->Film -> setFkUserId( $str );
  }
  
  function getFilmWebsite() {
    if (($this ->postVar("film_website")) || ($this ->postVar("film_website") === "")) {
      return $this ->postVar("film_website");
    } elseif (($this ->getVar("film_website")) || ($this ->getVar("film_website") === "")) {
      return $this ->getVar("film_website");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmWebsite();
    } elseif (($this ->sessionVar("film_website")) || ($this ->sessionVar("film_website") == "")) {
      return $this ->sessionVar("film_website");
    } else {
      return false;
    }
  }
  
  function setFilmWebsite( $str ) {
    $this ->Film -> setFilmWebsite( $str );
  }
  
  function getFilmFacebook() {
    if (($this ->postVar("film_facebook")) || ($this ->postVar("film_facebook") === "")) {
      return $this ->postVar("film_facebook");
    } elseif (($this ->getVar("film_facebook")) || ($this ->getVar("film_facebook") === "")) {
      return $this ->getVar("film_facebook");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmFacebook();
    } elseif (($this ->sessionVar("film_facebook")) || ($this ->sessionVar("film_facebook") == "")) {
      return $this ->sessionVar("film_facebook");
    } else {
      return false;
    }
  }
  
  function setFilmFacebook( $str ) {
    $this ->Film -> setFilmFacebook( $str );
  }
  
  function getFilmTwitter() {
    if (($this ->postVar("film_twitter")) || ($this ->postVar("film_twitter") === "")) {
      return $this ->postVar("film_twitter");
    } elseif (($this ->getVar("film_twitter")) || ($this ->getVar("film_twitter") === "")) {
      return $this ->getVar("film_twitter");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmTwitter();
    } elseif (($this ->sessionVar("film_twitter")) || ($this ->sessionVar("film_twitter") == "")) {
      return $this ->sessionVar("film_twitter");
    } else {
      return false;
    }
  }
  
  function setFilmTwitter( $str ) {
    $this ->Film -> setFilmTwitter( $str );
  }
  
  function getFilmYoutubeTrailer() {
    if (($this ->postVar("film_youtube_trailer")) || ($this ->postVar("film_youtube_trailer") === "")) {
      return $this ->postVar("film_youtube_trailer");
    } elseif (($this ->getVar("film_youtube_trailer")) || ($this ->getVar("film_youtube_trailer") === "")) {
      return $this ->getVar("film_youtube_trailer");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmYoutubeTrailer();
    } elseif (($this ->sessionVar("film_youtube_trailer")) || ($this ->sessionVar("film_youtube_trailer") == "")) {
      return $this ->sessionVar("film_youtube_trailer");
    } else {
      return false;
    }
  }
  
  function setFilmYoutubeTrailer( $str ) {
    $this ->Film -> setFilmYoutubeTrailer( $str );
  }
  
  function getFilmOoyalaEmbed() {
    if (($this ->postVar("film_ooyala_embed")) || ($this ->postVar("film_ooyala_embed") === "")) {
      return $this ->postVar("film_ooyala_embed");
    } elseif (($this ->getVar("film_ooyala_embed")) || ($this ->getVar("film_ooyala_embed") === "")) {
      return $this ->getVar("film_ooyala_embed");
    } elseif (($this ->Film) || ($this ->Film === "")){
      return $this ->Film -> getFilmOoyalaEmbed();
    } elseif (($this ->sessionVar("film_ooyala_embed")) || ($this ->sessionVar("film_ooyala_embed") == "")) {
      return $this ->sessionVar("film_ooyala_embed");
    } else {
      return false;
    }
  }
  
  function setFilmOoyalaEmbed( $str ) {
    $this ->Film -> setFilmOoyalaEmbed( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Film = FilmPeer::retrieveByPK( $id );
    }
    
    if ($this ->Film ) {
       
    	       (is_numeric(WTVRcleanString($this ->Film->getFilmId()))) ? $itemarray["film_id"] = WTVRcleanString($this ->Film->getFilmId()) : null;
          (WTVRcleanString($this ->Film->getFilmName())) ? $itemarray["film_name"] = WTVRcleanString($this ->Film->getFilmName()) : null;
          (WTVRcleanString($this ->Film->getFilmAltName())) ? $itemarray["film_alt_name"] = WTVRcleanString($this ->Film->getFilmAltName()) : null;
          (WTVRcleanString($this ->Film->getFilmMakers())) ? $itemarray["film_makers"] = WTVRcleanString($this ->Film->getFilmMakers()) : null;
          (WTVRcleanString($this ->Film->getFilmProductionCompany())) ? $itemarray["film_production_company"] = WTVRcleanString($this ->Film->getFilmProductionCompany()) : null;
          (WTVRcleanString($this ->Film->getFilmLogo())) ? $itemarray["film_logo"] = WTVRcleanString($this ->Film->getFilmLogo()) : null;
          (WTVRcleanString($this ->Film->getFilmHomelogo())) ? $itemarray["film_homelogo"] = WTVRcleanString($this ->Film->getFilmHomelogo()) : null;
          (WTVRcleanString($this ->Film->getFilmTrailerFile())) ? $itemarray["film_trailer_file"] = WTVRcleanString($this ->Film->getFilmTrailerFile()) : null;
          (WTVRcleanString($this ->Film->getFilmMovieFile())) ? $itemarray["film_movie_file"] = WTVRcleanString($this ->Film->getFilmMovieFile()) : null;
          (WTVRcleanString($this ->Film->getFilmMakerMessage())) ? $itemarray["film_maker_message"] = WTVRcleanString($this ->Film->getFilmMakerMessage()) : null;
          (WTVRcleanString($this ->Film->getFilmTicketPrice())) ? $itemarray["film_ticket_price"] = WTVRcleanString($this ->Film->getFilmTicketPrice()) : null;
          (WTVRcleanString($this ->Film->getFilmHostbyrequestPrice())) ? $itemarray["film_hostbyrequest_price"] = WTVRcleanString($this ->Film->getFilmHostbyrequestPrice()) : null;
          (WTVRcleanString($this ->Film->getFilmStatus())) ? $itemarray["film_status"] = WTVRcleanString($this ->Film->getFilmStatus()) : null;
          (WTVRcleanString($this ->Film->getFilmFeatured())) ? $itemarray["film_featured"] = WTVRcleanString($this ->Film->getFilmFeatured()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Film->getFilmCreatedAt())) ? $itemarray["film_created_at"] = formatDate($this ->Film->getFilmCreatedAt('%Y-%m-%d %T'),"TS") : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Film->getFilmUpdatedAt())) ? $itemarray["film_updated_at"] = formatDate($this ->Film->getFilmUpdatedAt('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->Film->getFilmSetupPrice())) ? $itemarray["film_setup_price"] = WTVRcleanString($this ->Film->getFilmSetupPrice()) : null;
          (WTVRcleanString($this ->Film->getFilmInfo())) ? $itemarray["film_info"] = WTVRcleanString($this ->Film->getFilmInfo()) : null;
          (WTVRcleanString($this ->Film->getFilmCast())) ? $itemarray["film_cast"] = WTVRcleanString($this ->Film->getFilmCast()) : null;
          (WTVRcleanString($this ->Film->getFilmRunningTime())) ? $itemarray["film_running_time"] = WTVRcleanString($this ->Film->getFilmRunningTime()) : null;
          (is_numeric(WTVRcleanString($this ->Film->getFilmTotalSeats()))) ? $itemarray["film_total_seats"] = WTVRcleanString($this ->Film->getFilmTotalSeats()) : null;
          (WTVRcleanString($this ->Film->getFilmShortName())) ? $itemarray["film_short_name"] = WTVRcleanString($this ->Film->getFilmShortName()) : null;
          (WTVRcleanString($this ->Film->getFilmSynopsis())) ? $itemarray["film_synopsis"] = WTVRcleanString($this ->Film->getFilmSynopsis()) : null;
          (WTVRcleanString($this ->Film->getFilmStillImage())) ? $itemarray["film_still_image"] = WTVRcleanString($this ->Film->getFilmStillImage()) : null;
          (WTVRcleanString($this ->Film->getFilmBackgroundImage())) ? $itemarray["film_background_image"] = WTVRcleanString($this ->Film->getFilmBackgroundImage()) : null;
          (WTVRcleanString($this ->Film->getFilmSplashImage())) ? $itemarray["film_splash_image"] = WTVRcleanString($this ->Film->getFilmSplashImage()) : null;
          (WTVRcleanString($this ->Film->getFilmGeoblockingEnabled())) ? $itemarray["film_geoblocking_enabled"] = WTVRcleanString($this ->Film->getFilmGeoblockingEnabled()) : null;
          (WTVRcleanString($this ->Film->getFilmGeoblockingType())) ? $itemarray["film_geoblocking_type"] = WTVRcleanString($this ->Film->getFilmGeoblockingType()) : null;
          (WTVRcleanString($this ->Film->getFilmShortUrl())) ? $itemarray["film_short_url"] = WTVRcleanString($this ->Film->getFilmShortUrl()) : null;
          (WTVRcleanString($this ->Film->getFilmReview())) ? $itemarray["film_review"] = WTVRcleanString($this ->Film->getFilmReview()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Film->getFilmStartdate())) ? $itemarray["film_startdate"] = formatDate($this ->Film->getFilmStartdate('%Y-%m-%d %T'),"TS") : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Film->getFilmEnddate())) ? $itemarray["film_enddate"] = formatDate($this ->Film->getFilmEnddate('%Y-%m-%d %T'),"TS") : null;
          (is_numeric(WTVRcleanString($this ->Film->getFkFilmSponsorId()))) ? $itemarray["fk_film_sponsor_id"] = WTVRcleanString($this ->Film->getFkFilmSponsorId()) : null;
          (is_numeric(WTVRcleanString($this ->Film->getFilmBitrateMinimum()))) ? $itemarray["film_bitrate_minimum"] = WTVRcleanString($this ->Film->getFilmBitrateMinimum()) : null;
          (is_numeric(WTVRcleanString($this ->Film->getFilmBitrateLow()))) ? $itemarray["film_bitrate_low"] = WTVRcleanString($this ->Film->getFilmBitrateLow()) : null;
          (is_numeric(WTVRcleanString($this ->Film->getFilmBitrateSmall()))) ? $itemarray["film_bitrate_small"] = WTVRcleanString($this ->Film->getFilmBitrateSmall()) : null;
          (is_numeric(WTVRcleanString($this ->Film->getFilmBitrateMedium()))) ? $itemarray["film_bitrate_medium"] = WTVRcleanString($this ->Film->getFilmBitrateMedium()) : null;
          (is_numeric(WTVRcleanString($this ->Film->getFilmBitrateLarge()))) ? $itemarray["film_bitrate_large"] = WTVRcleanString($this ->Film->getFilmBitrateLarge()) : null;
          (is_numeric(WTVRcleanString($this ->Film->getFilmBitrateLargest()))) ? $itemarray["film_bitrate_largest"] = WTVRcleanString($this ->Film->getFilmBitrateLargest()) : null;
          (WTVRcleanString($this ->Film->getFilmUseSponsorCodes())) ? $itemarray["film_use_sponsor_codes"] = WTVRcleanString($this ->Film->getFilmUseSponsorCodes()) : null;
          (WTVRcleanString($this ->Film->getFilmAllowHostbyrequest())) ? $itemarray["film_allow_hostbyrequest"] = WTVRcleanString($this ->Film->getFilmAllowHostbyrequest()) : null;
          (WTVRcleanString($this ->Film->getFilmAllowUserHosting())) ? $itemarray["film_allow_user_hosting"] = WTVRcleanString($this ->Film->getFilmAllowUserHosting()) : null;
          (WTVRcleanString($this ->Film->getFilmAlternateTemplate())) ? $itemarray["film_alternate_template"] = WTVRcleanString($this ->Film->getFilmAlternateTemplate()) : null;
          (WTVRcleanString($this ->Film->getFilmAlternateOpts())) ? $itemarray["film_alternate_opts"] = WTVRcleanString($this ->Film->getFilmAlternateOpts()) : null;
          (WTVRcleanString($this ->Film->getFilmCdn())) ? $itemarray["film_cdn"] = WTVRcleanString($this ->Film->getFilmCdn()) : null;
          (WTVRcleanString($this ->Film->getFilmShare())) ? $itemarray["film_share"] = WTVRcleanString($this ->Film->getFilmShare()) : null;
          (WTVRcleanString($this ->Film->getFilmPreuser())) ? $itemarray["film_preuser"] = WTVRcleanString($this ->Film->getFilmPreuser()) : null;
          (WTVRcleanString($this ->Film->getFilmFreewithinvite())) ? $itemarray["film_freewithinvite"] = WTVRcleanString($this ->Film->getFilmFreewithinvite()) : null;
          (WTVRcleanString($this ->Film->getFilmFreeScreening())) ? $itemarray["film_free_screening"] = WTVRcleanString($this ->Film->getFilmFreeScreening()) : null;
          (WTVRcleanString($this ->Film->getFilmTwitterTags())) ? $itemarray["film_twitter_tags"] = WTVRcleanString($this ->Film->getFilmTwitterTags()) : null;
          (is_numeric(WTVRcleanString($this ->Film->getFkUserId()))) ? $itemarray["fk_user_id"] = WTVRcleanString($this ->Film->getFkUserId()) : null;
          (WTVRcleanString($this ->Film->getFilmWebsite())) ? $itemarray["film_website"] = WTVRcleanString($this ->Film->getFilmWebsite()) : null;
          (WTVRcleanString($this ->Film->getFilmFacebook())) ? $itemarray["film_facebook"] = WTVRcleanString($this ->Film->getFilmFacebook()) : null;
          (WTVRcleanString($this ->Film->getFilmTwitter())) ? $itemarray["film_twitter"] = WTVRcleanString($this ->Film->getFilmTwitter()) : null;
          (WTVRcleanString($this ->Film->getFilmYoutubeTrailer())) ? $itemarray["film_youtube_trailer"] = WTVRcleanString($this ->Film->getFilmYoutubeTrailer()) : null;
          (WTVRcleanString($this ->Film->getFilmOoyalaEmbed())) ? $itemarray["film_ooyala_embed"] = WTVRcleanString($this ->Film->getFilmOoyalaEmbed()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Film = FilmPeer::retrieveByPK( $id );
    } elseif (! $this ->Film) {
      $this ->Film = new Film;
    }
        
  	 ($this -> getFilmId())? $this ->Film->setFilmId( WTVRcleanString( $this -> getFilmId()) ) : null;
    ($this -> getFilmName())? $this ->Film->setFilmName( WTVRcleanString( $this -> getFilmName()) ) : null;
    ($this -> getFilmAltName())? $this ->Film->setFilmAltName( WTVRcleanString( $this -> getFilmAltName()) ) : null;
    ($this -> getFilmMakers())? $this ->Film->setFilmMakers( WTVRcleanString( $this -> getFilmMakers()) ) : null;
    ($this -> getFilmProductionCompany())? $this ->Film->setFilmProductionCompany( WTVRcleanString( $this -> getFilmProductionCompany()) ) : null;
    ($this -> getFilmLogo())? $this ->Film->setFilmLogo( WTVRcleanString( $this -> getFilmLogo()) ) : null;
    ($this -> getFilmHomelogo())? $this ->Film->setFilmHomelogo( WTVRcleanString( $this -> getFilmHomelogo()) ) : null;
    ($this -> getFilmTrailerFile())? $this ->Film->setFilmTrailerFile( WTVRcleanString( $this -> getFilmTrailerFile()) ) : null;
    ($this -> getFilmMovieFile())? $this ->Film->setFilmMovieFile( WTVRcleanString( $this -> getFilmMovieFile()) ) : null;
    ($this -> getFilmMakerMessage())? $this ->Film->setFilmMakerMessage( WTVRcleanString( $this -> getFilmMakerMessage()) ) : null;
    ($this -> getFilmTicketPrice())? $this ->Film->setFilmTicketPrice( WTVRcleanString( $this -> getFilmTicketPrice()) ) : null;
    ($this -> getFilmHostbyrequestPrice())? $this ->Film->setFilmHostbyrequestPrice( WTVRcleanString( $this -> getFilmHostbyrequestPrice()) ) : null;
    ($this -> getFilmStatus())? $this ->Film->setFilmStatus( WTVRcleanString( $this -> getFilmStatus()) ) : null;
    ($this -> getFilmFeatured())? $this ->Film->setFilmFeatured( WTVRcleanString( $this -> getFilmFeatured()) ) : null;
          if (is_valid_date( $this ->Film->getFilmCreatedAt())) {
        $this ->Film->setFilmCreatedAt( formatDate($this -> getFilmCreatedAt(), "TS" ));
      } else {
      $Filmfilm_created_at = $this -> sfDateTime( "film_created_at" );
      ( $Filmfilm_created_at != "01/01/1900 00:00:00" )? $this ->Film->setFilmCreatedAt( formatDate($Filmfilm_created_at, "TS" )) : $this ->Film->setFilmCreatedAt( null );
      }
          if (is_valid_date( $this ->Film->getFilmUpdatedAt())) {
        $this ->Film->setFilmUpdatedAt( formatDate($this -> getFilmUpdatedAt(), "TS" ));
      } else {
      $Filmfilm_updated_at = $this -> sfDateTime( "film_updated_at" );
      ( $Filmfilm_updated_at != "01/01/1900 00:00:00" )? $this ->Film->setFilmUpdatedAt( formatDate($Filmfilm_updated_at, "TS" )) : $this ->Film->setFilmUpdatedAt( null );
      }
    ($this -> getFilmSetupPrice())? $this ->Film->setFilmSetupPrice( WTVRcleanString( $this -> getFilmSetupPrice()) ) : null;
    ($this -> getFilmInfo())? $this ->Film->setFilmInfo( WTVRcleanString( $this -> getFilmInfo()) ) : null;
    ($this -> getFilmCast())? $this ->Film->setFilmCast( WTVRcleanString( $this -> getFilmCast()) ) : null;
    ($this -> getFilmRunningTime())? $this ->Film->setFilmRunningTime( WTVRcleanString( $this -> getFilmRunningTime()) ) : null;
    ($this -> getFilmTotalSeats())? $this ->Film->setFilmTotalSeats( WTVRcleanString( $this -> getFilmTotalSeats()) ) : null;
    ($this -> getFilmShortName())? $this ->Film->setFilmShortName( WTVRcleanString( $this -> getFilmShortName()) ) : null;
    ($this -> getFilmSynopsis())? $this ->Film->setFilmSynopsis( WTVRcleanString( $this -> getFilmSynopsis()) ) : null;
    ($this -> getFilmStillImage())? $this ->Film->setFilmStillImage( WTVRcleanString( $this -> getFilmStillImage()) ) : null;
    ($this -> getFilmBackgroundImage())? $this ->Film->setFilmBackgroundImage( WTVRcleanString( $this -> getFilmBackgroundImage()) ) : null;
    ($this -> getFilmSplashImage())? $this ->Film->setFilmSplashImage( WTVRcleanString( $this -> getFilmSplashImage()) ) : null;
    ($this -> getFilmGeoblockingEnabled())? $this ->Film->setFilmGeoblockingEnabled( WTVRcleanString( $this -> getFilmGeoblockingEnabled()) ) : null;
    ($this -> getFilmGeoblockingType())? $this ->Film->setFilmGeoblockingType( WTVRcleanString( $this -> getFilmGeoblockingType()) ) : null;
    ($this -> getFilmShortUrl())? $this ->Film->setFilmShortUrl( WTVRcleanString( $this -> getFilmShortUrl()) ) : null;
    ($this -> getFilmReview())? $this ->Film->setFilmReview( WTVRcleanString( $this -> getFilmReview()) ) : null;
          if (is_valid_date( $this ->Film->getFilmStartdate())) {
        $this ->Film->setFilmStartdate( formatDate($this -> getFilmStartdate(), "TS" ));
      } else {
      $Filmfilm_startdate = $this -> sfDateTime( "film_startdate" );
      ( $Filmfilm_startdate != "01/01/1900 00:00:00" )? $this ->Film->setFilmStartdate( formatDate($Filmfilm_startdate, "TS" )) : $this ->Film->setFilmStartdate( null );
      }
          if (is_valid_date( $this ->Film->getFilmEnddate())) {
        $this ->Film->setFilmEnddate( formatDate($this -> getFilmEnddate(), "TS" ));
      } else {
      $Filmfilm_enddate = $this -> sfDateTime( "film_enddate" );
      ( $Filmfilm_enddate != "01/01/1900 00:00:00" )? $this ->Film->setFilmEnddate( formatDate($Filmfilm_enddate, "TS" )) : $this ->Film->setFilmEnddate( null );
      }
    ($this -> getFkFilmSponsorId())? $this ->Film->setFkFilmSponsorId( WTVRcleanString( $this -> getFkFilmSponsorId()) ) : null;
    ($this -> getFilmBitrateMinimum())? $this ->Film->setFilmBitrateMinimum( WTVRcleanString( $this -> getFilmBitrateMinimum()) ) : null;
    ($this -> getFilmBitrateLow())? $this ->Film->setFilmBitrateLow( WTVRcleanString( $this -> getFilmBitrateLow()) ) : null;
    ($this -> getFilmBitrateSmall())? $this ->Film->setFilmBitrateSmall( WTVRcleanString( $this -> getFilmBitrateSmall()) ) : null;
    ($this -> getFilmBitrateMedium())? $this ->Film->setFilmBitrateMedium( WTVRcleanString( $this -> getFilmBitrateMedium()) ) : null;
    ($this -> getFilmBitrateLarge())? $this ->Film->setFilmBitrateLarge( WTVRcleanString( $this -> getFilmBitrateLarge()) ) : null;
    ($this -> getFilmBitrateLargest())? $this ->Film->setFilmBitrateLargest( WTVRcleanString( $this -> getFilmBitrateLargest()) ) : null;
    ($this -> getFilmUseSponsorCodes())? $this ->Film->setFilmUseSponsorCodes( WTVRcleanString( $this -> getFilmUseSponsorCodes()) ) : null;
    ($this -> getFilmAllowHostbyrequest())? $this ->Film->setFilmAllowHostbyrequest( WTVRcleanString( $this -> getFilmAllowHostbyrequest()) ) : null;
    ($this -> getFilmAllowUserHosting())? $this ->Film->setFilmAllowUserHosting( WTVRcleanString( $this -> getFilmAllowUserHosting()) ) : null;
    ($this -> getFilmAlternateTemplate())? $this ->Film->setFilmAlternateTemplate( WTVRcleanString( $this -> getFilmAlternateTemplate()) ) : null;
    ($this -> getFilmAlternateOpts())? $this ->Film->setFilmAlternateOpts( WTVRcleanString( $this -> getFilmAlternateOpts()) ) : null;
    ($this -> getFilmCdn())? $this ->Film->setFilmCdn( WTVRcleanString( $this -> getFilmCdn()) ) : null;
    ($this -> getFilmShare())? $this ->Film->setFilmShare( WTVRcleanString( $this -> getFilmShare()) ) : null;
    ($this -> getFilmPreuser())? $this ->Film->setFilmPreuser( WTVRcleanString( $this -> getFilmPreuser()) ) : null;
    ($this -> getFilmFreewithinvite())? $this ->Film->setFilmFreewithinvite( WTVRcleanString( $this -> getFilmFreewithinvite()) ) : null;
    ($this -> getFilmFreeScreening())? $this ->Film->setFilmFreeScreening( WTVRcleanString( $this -> getFilmFreeScreening()) ) : null;
    ($this -> getFilmTwitterTags())? $this ->Film->setFilmTwitterTags( WTVRcleanString( $this -> getFilmTwitterTags()) ) : null;
    ($this -> getFkUserId())? $this ->Film->setFkUserId( WTVRcleanString( $this -> getFkUserId()) ) : null;
    ($this -> getFilmWebsite())? $this ->Film->setFilmWebsite( WTVRcleanString( $this -> getFilmWebsite()) ) : null;
    ($this -> getFilmFacebook())? $this ->Film->setFilmFacebook( WTVRcleanString( $this -> getFilmFacebook()) ) : null;
    ($this -> getFilmTwitter())? $this ->Film->setFilmTwitter( WTVRcleanString( $this -> getFilmTwitter()) ) : null;
    ($this -> getFilmYoutubeTrailer())? $this ->Film->setFilmYoutubeTrailer( WTVRcleanString( $this -> getFilmYoutubeTrailer()) ) : null;
    ($this -> getFilmOoyalaEmbed())? $this ->Film->setFilmOoyalaEmbed( WTVRcleanString( $this -> getFilmOoyalaEmbed()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Film ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Film = FilmPeer::retrieveByPK($id);
    }
    
    if (! $this ->Film ) {
      return;
    }
    
    $this ->Film -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Film_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "FilmPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Film = FilmPeer::doSelect($c);
    
    if (count($Film) >= 1) {
      $this ->Film = $Film[0];
      return true;
    } else {
      $this ->Film = new Film();
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
      $name = "FilmPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Film = FilmPeer::doSelect($c);
    
    if (count($Film) >= 1) {
      $this ->Film = $Film[0];
      return true;
    } else {
      $this ->Film = new Film();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>