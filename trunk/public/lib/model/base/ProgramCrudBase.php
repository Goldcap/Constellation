<?php
       
   class ProgramCrudBase extends Utils_PageWidget { 
   
    var $Program;
   
       var $program_id;
   var $program_created_at;
   var $program_updated_at;
   var $fk_program_sponsor_id;
   var $program_name;
   var $program_short_name;
   var $fk_film_id;
   var $program_logo;
   var $program_featured;
   var $program_production_company;
   var $program_start_date;
   var $program_end_date;
   var $program_preuser;
   var $program_total_seats;
   var $program_ticket_price;
   var $program_hostbyrequest_price;
   var $program_setup_price;
   var $program_info;
   var $program_status;
   var $program_still_image;
   var $program_background_image;
   var $program_geoblocking_enabled;
   var $program_geoblocking_type;
   var $program_use_sponsor_codes;
   var $program_allow_hostbyrequest;
   var $program_allow_user_hosting;
   var $program_alternate_template;
   var $program_alternate_opts;
   var $program_share;
   var $program_synopsis;
   var $program_url;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getProgramId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Program = ProgramPeer::retrieveByPK( $id );
    } else {
      $this ->Program = new Program;
    }
  }
  
  function hydrate( $id ) {
      $this ->Program = ProgramPeer::retrieveByPK( $id );
  }
  
  function getProgramId() {
    if (($this ->postVar("program_id")) || ($this ->postVar("program_id") === "")) {
      return $this ->postVar("program_id");
    } elseif (($this ->getVar("program_id")) || ($this ->getVar("program_id") === "")) {
      return $this ->getVar("program_id");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramId();
    } elseif (($this ->sessionVar("program_id")) || ($this ->sessionVar("program_id") == "")) {
      return $this ->sessionVar("program_id");
    } else {
      return false;
    }
  }
  
  function setProgramId( $str ) {
    $this ->Program -> setProgramId( $str );
  }
  
  function getProgramCreatedAt() {
    if (($this ->postVar("program_created_at")) || ($this ->postVar("program_created_at") === "")) {
      return $this ->postVar("program_created_at");
    } elseif (($this ->getVar("program_created_at")) || ($this ->getVar("program_created_at") === "")) {
      return $this ->getVar("program_created_at");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramCreatedAt();
    } elseif (($this ->sessionVar("program_created_at")) || ($this ->sessionVar("program_created_at") == "")) {
      return $this ->sessionVar("program_created_at");
    } else {
      return false;
    }
  }
  
  function setProgramCreatedAt( $str ) {
    $this ->Program -> setProgramCreatedAt( $str );
  }
  
  function getProgramUpdatedAt() {
    if (($this ->postVar("program_updated_at")) || ($this ->postVar("program_updated_at") === "")) {
      return $this ->postVar("program_updated_at");
    } elseif (($this ->getVar("program_updated_at")) || ($this ->getVar("program_updated_at") === "")) {
      return $this ->getVar("program_updated_at");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramUpdatedAt();
    } elseif (($this ->sessionVar("program_updated_at")) || ($this ->sessionVar("program_updated_at") == "")) {
      return $this ->sessionVar("program_updated_at");
    } else {
      return false;
    }
  }
  
  function setProgramUpdatedAt( $str ) {
    $this ->Program -> setProgramUpdatedAt( $str );
  }
  
  function getFkProgramSponsorId() {
    if (($this ->postVar("fk_program_sponsor_id")) || ($this ->postVar("fk_program_sponsor_id") === "")) {
      return $this ->postVar("fk_program_sponsor_id");
    } elseif (($this ->getVar("fk_program_sponsor_id")) || ($this ->getVar("fk_program_sponsor_id") === "")) {
      return $this ->getVar("fk_program_sponsor_id");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getFkProgramSponsorId();
    } elseif (($this ->sessionVar("fk_program_sponsor_id")) || ($this ->sessionVar("fk_program_sponsor_id") == "")) {
      return $this ->sessionVar("fk_program_sponsor_id");
    } else {
      return false;
    }
  }
  
  function setFkProgramSponsorId( $str ) {
    $this ->Program -> setFkProgramSponsorId( $str );
  }
  
  function getProgramName() {
    if (($this ->postVar("program_name")) || ($this ->postVar("program_name") === "")) {
      return $this ->postVar("program_name");
    } elseif (($this ->getVar("program_name")) || ($this ->getVar("program_name") === "")) {
      return $this ->getVar("program_name");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramName();
    } elseif (($this ->sessionVar("program_name")) || ($this ->sessionVar("program_name") == "")) {
      return $this ->sessionVar("program_name");
    } else {
      return false;
    }
  }
  
  function setProgramName( $str ) {
    $this ->Program -> setProgramName( $str );
  }
  
  function getProgramShortName() {
    if (($this ->postVar("program_short_name")) || ($this ->postVar("program_short_name") === "")) {
      return $this ->postVar("program_short_name");
    } elseif (($this ->getVar("program_short_name")) || ($this ->getVar("program_short_name") === "")) {
      return $this ->getVar("program_short_name");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramShortName();
    } elseif (($this ->sessionVar("program_short_name")) || ($this ->sessionVar("program_short_name") == "")) {
      return $this ->sessionVar("program_short_name");
    } else {
      return false;
    }
  }
  
  function setProgramShortName( $str ) {
    $this ->Program -> setProgramShortName( $str );
  }
  
  function getFkFilmId() {
    if (($this ->postVar("fk_film_id")) || ($this ->postVar("fk_film_id") === "")) {
      return $this ->postVar("fk_film_id");
    } elseif (($this ->getVar("fk_film_id")) || ($this ->getVar("fk_film_id") === "")) {
      return $this ->getVar("fk_film_id");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getFkFilmId();
    } elseif (($this ->sessionVar("fk_film_id")) || ($this ->sessionVar("fk_film_id") == "")) {
      return $this ->sessionVar("fk_film_id");
    } else {
      return false;
    }
  }
  
  function setFkFilmId( $str ) {
    $this ->Program -> setFkFilmId( $str );
  }
  
  function getProgramLogo() {
    if (($this ->postVar("program_logo")) || ($this ->postVar("program_logo") === "")) {
      return $this ->postVar("program_logo");
    } elseif (($this ->getVar("program_logo")) || ($this ->getVar("program_logo") === "")) {
      return $this ->getVar("program_logo");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramLogo();
    } elseif (($this ->sessionVar("program_logo")) || ($this ->sessionVar("program_logo") == "")) {
      return $this ->sessionVar("program_logo");
    } else {
      return false;
    }
  }
  
  function setProgramLogo( $str ) {
    $this ->Program -> setProgramLogo( $str );
  }
  
  function getProgramFeatured() {
    if (($this ->postVar("program_featured")) || ($this ->postVar("program_featured") === "")) {
      return $this ->postVar("program_featured");
    } elseif (($this ->getVar("program_featured")) || ($this ->getVar("program_featured") === "")) {
      return $this ->getVar("program_featured");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramFeatured();
    } elseif (($this ->sessionVar("program_featured")) || ($this ->sessionVar("program_featured") == "")) {
      return $this ->sessionVar("program_featured");
    } else {
      return false;
    }
  }
  
  function setProgramFeatured( $str ) {
    $this ->Program -> setProgramFeatured( $str );
  }
  
  function getProgramProductionCompany() {
    if (($this ->postVar("program_production_company")) || ($this ->postVar("program_production_company") === "")) {
      return $this ->postVar("program_production_company");
    } elseif (($this ->getVar("program_production_company")) || ($this ->getVar("program_production_company") === "")) {
      return $this ->getVar("program_production_company");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramProductionCompany();
    } elseif (($this ->sessionVar("program_production_company")) || ($this ->sessionVar("program_production_company") == "")) {
      return $this ->sessionVar("program_production_company");
    } else {
      return false;
    }
  }
  
  function setProgramProductionCompany( $str ) {
    $this ->Program -> setProgramProductionCompany( $str );
  }
  
  function getProgramStartDate() {
    if (($this ->postVar("program_start_date")) || ($this ->postVar("program_start_date") === "")) {
      return $this ->postVar("program_start_date");
    } elseif (($this ->getVar("program_start_date")) || ($this ->getVar("program_start_date") === "")) {
      return $this ->getVar("program_start_date");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramStartDate();
    } elseif (($this ->sessionVar("program_start_date")) || ($this ->sessionVar("program_start_date") == "")) {
      return $this ->sessionVar("program_start_date");
    } else {
      return false;
    }
  }
  
  function setProgramStartDate( $str ) {
    $this ->Program -> setProgramStartDate( $str );
  }
  
  function getProgramEndDate() {
    if (($this ->postVar("program_end_date")) || ($this ->postVar("program_end_date") === "")) {
      return $this ->postVar("program_end_date");
    } elseif (($this ->getVar("program_end_date")) || ($this ->getVar("program_end_date") === "")) {
      return $this ->getVar("program_end_date");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramEndDate();
    } elseif (($this ->sessionVar("program_end_date")) || ($this ->sessionVar("program_end_date") == "")) {
      return $this ->sessionVar("program_end_date");
    } else {
      return false;
    }
  }
  
  function setProgramEndDate( $str ) {
    $this ->Program -> setProgramEndDate( $str );
  }
  
  function getProgramPreuser() {
    if (($this ->postVar("program_preuser")) || ($this ->postVar("program_preuser") === "")) {
      return $this ->postVar("program_preuser");
    } elseif (($this ->getVar("program_preuser")) || ($this ->getVar("program_preuser") === "")) {
      return $this ->getVar("program_preuser");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramPreuser();
    } elseif (($this ->sessionVar("program_preuser")) || ($this ->sessionVar("program_preuser") == "")) {
      return $this ->sessionVar("program_preuser");
    } else {
      return false;
    }
  }
  
  function setProgramPreuser( $str ) {
    $this ->Program -> setProgramPreuser( $str );
  }
  
  function getProgramTotalSeats() {
    if (($this ->postVar("program_total_seats")) || ($this ->postVar("program_total_seats") === "")) {
      return $this ->postVar("program_total_seats");
    } elseif (($this ->getVar("program_total_seats")) || ($this ->getVar("program_total_seats") === "")) {
      return $this ->getVar("program_total_seats");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramTotalSeats();
    } elseif (($this ->sessionVar("program_total_seats")) || ($this ->sessionVar("program_total_seats") == "")) {
      return $this ->sessionVar("program_total_seats");
    } else {
      return false;
    }
  }
  
  function setProgramTotalSeats( $str ) {
    $this ->Program -> setProgramTotalSeats( $str );
  }
  
  function getProgramTicketPrice() {
    if (($this ->postVar("program_ticket_price")) || ($this ->postVar("program_ticket_price") === "")) {
      return $this ->postVar("program_ticket_price");
    } elseif (($this ->getVar("program_ticket_price")) || ($this ->getVar("program_ticket_price") === "")) {
      return $this ->getVar("program_ticket_price");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramTicketPrice();
    } elseif (($this ->sessionVar("program_ticket_price")) || ($this ->sessionVar("program_ticket_price") == "")) {
      return $this ->sessionVar("program_ticket_price");
    } else {
      return false;
    }
  }
  
  function setProgramTicketPrice( $str ) {
    $this ->Program -> setProgramTicketPrice( $str );
  }
  
  function getProgramHostbyrequestPrice() {
    if (($this ->postVar("program_hostbyrequest_price")) || ($this ->postVar("program_hostbyrequest_price") === "")) {
      return $this ->postVar("program_hostbyrequest_price");
    } elseif (($this ->getVar("program_hostbyrequest_price")) || ($this ->getVar("program_hostbyrequest_price") === "")) {
      return $this ->getVar("program_hostbyrequest_price");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramHostbyrequestPrice();
    } elseif (($this ->sessionVar("program_hostbyrequest_price")) || ($this ->sessionVar("program_hostbyrequest_price") == "")) {
      return $this ->sessionVar("program_hostbyrequest_price");
    } else {
      return false;
    }
  }
  
  function setProgramHostbyrequestPrice( $str ) {
    $this ->Program -> setProgramHostbyrequestPrice( $str );
  }
  
  function getProgramSetupPrice() {
    if (($this ->postVar("program_setup_price")) || ($this ->postVar("program_setup_price") === "")) {
      return $this ->postVar("program_setup_price");
    } elseif (($this ->getVar("program_setup_price")) || ($this ->getVar("program_setup_price") === "")) {
      return $this ->getVar("program_setup_price");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramSetupPrice();
    } elseif (($this ->sessionVar("program_setup_price")) || ($this ->sessionVar("program_setup_price") == "")) {
      return $this ->sessionVar("program_setup_price");
    } else {
      return false;
    }
  }
  
  function setProgramSetupPrice( $str ) {
    $this ->Program -> setProgramSetupPrice( $str );
  }
  
  function getProgramInfo() {
    if (($this ->postVar("program_info")) || ($this ->postVar("program_info") === "")) {
      return $this ->postVar("program_info");
    } elseif (($this ->getVar("program_info")) || ($this ->getVar("program_info") === "")) {
      return $this ->getVar("program_info");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramInfo();
    } elseif (($this ->sessionVar("program_info")) || ($this ->sessionVar("program_info") == "")) {
      return $this ->sessionVar("program_info");
    } else {
      return false;
    }
  }
  
  function setProgramInfo( $str ) {
    $this ->Program -> setProgramInfo( $str );
  }
  
  function getProgramStatus() {
    if (($this ->postVar("program_status")) || ($this ->postVar("program_status") === "")) {
      return $this ->postVar("program_status");
    } elseif (($this ->getVar("program_status")) || ($this ->getVar("program_status") === "")) {
      return $this ->getVar("program_status");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramStatus();
    } elseif (($this ->sessionVar("program_status")) || ($this ->sessionVar("program_status") == "")) {
      return $this ->sessionVar("program_status");
    } else {
      return false;
    }
  }
  
  function setProgramStatus( $str ) {
    $this ->Program -> setProgramStatus( $str );
  }
  
  function getProgramStillImage() {
    if (($this ->postVar("program_still_image")) || ($this ->postVar("program_still_image") === "")) {
      return $this ->postVar("program_still_image");
    } elseif (($this ->getVar("program_still_image")) || ($this ->getVar("program_still_image") === "")) {
      return $this ->getVar("program_still_image");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramStillImage();
    } elseif (($this ->sessionVar("program_still_image")) || ($this ->sessionVar("program_still_image") == "")) {
      return $this ->sessionVar("program_still_image");
    } else {
      return false;
    }
  }
  
  function setProgramStillImage( $str ) {
    $this ->Program -> setProgramStillImage( $str );
  }
  
  function getProgramBackgroundImage() {
    if (($this ->postVar("program_background_image")) || ($this ->postVar("program_background_image") === "")) {
      return $this ->postVar("program_background_image");
    } elseif (($this ->getVar("program_background_image")) || ($this ->getVar("program_background_image") === "")) {
      return $this ->getVar("program_background_image");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramBackgroundImage();
    } elseif (($this ->sessionVar("program_background_image")) || ($this ->sessionVar("program_background_image") == "")) {
      return $this ->sessionVar("program_background_image");
    } else {
      return false;
    }
  }
  
  function setProgramBackgroundImage( $str ) {
    $this ->Program -> setProgramBackgroundImage( $str );
  }
  
  function getProgramGeoblockingEnabled() {
    if (($this ->postVar("program_geoblocking_enabled")) || ($this ->postVar("program_geoblocking_enabled") === "")) {
      return $this ->postVar("program_geoblocking_enabled");
    } elseif (($this ->getVar("program_geoblocking_enabled")) || ($this ->getVar("program_geoblocking_enabled") === "")) {
      return $this ->getVar("program_geoblocking_enabled");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramGeoblockingEnabled();
    } elseif (($this ->sessionVar("program_geoblocking_enabled")) || ($this ->sessionVar("program_geoblocking_enabled") == "")) {
      return $this ->sessionVar("program_geoblocking_enabled");
    } else {
      return false;
    }
  }
  
  function setProgramGeoblockingEnabled( $str ) {
    $this ->Program -> setProgramGeoblockingEnabled( $str );
  }
  
  function getProgramGeoblockingType() {
    if (($this ->postVar("program_geoblocking_type")) || ($this ->postVar("program_geoblocking_type") === "")) {
      return $this ->postVar("program_geoblocking_type");
    } elseif (($this ->getVar("program_geoblocking_type")) || ($this ->getVar("program_geoblocking_type") === "")) {
      return $this ->getVar("program_geoblocking_type");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramGeoblockingType();
    } elseif (($this ->sessionVar("program_geoblocking_type")) || ($this ->sessionVar("program_geoblocking_type") == "")) {
      return $this ->sessionVar("program_geoblocking_type");
    } else {
      return false;
    }
  }
  
  function setProgramGeoblockingType( $str ) {
    $this ->Program -> setProgramGeoblockingType( $str );
  }
  
  function getProgramUseSponsorCodes() {
    if (($this ->postVar("program_use_sponsor_codes")) || ($this ->postVar("program_use_sponsor_codes") === "")) {
      return $this ->postVar("program_use_sponsor_codes");
    } elseif (($this ->getVar("program_use_sponsor_codes")) || ($this ->getVar("program_use_sponsor_codes") === "")) {
      return $this ->getVar("program_use_sponsor_codes");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramUseSponsorCodes();
    } elseif (($this ->sessionVar("program_use_sponsor_codes")) || ($this ->sessionVar("program_use_sponsor_codes") == "")) {
      return $this ->sessionVar("program_use_sponsor_codes");
    } else {
      return false;
    }
  }
  
  function setProgramUseSponsorCodes( $str ) {
    $this ->Program -> setProgramUseSponsorCodes( $str );
  }
  
  function getProgramAllowHostbyrequest() {
    if (($this ->postVar("program_allow_hostbyrequest")) || ($this ->postVar("program_allow_hostbyrequest") === "")) {
      return $this ->postVar("program_allow_hostbyrequest");
    } elseif (($this ->getVar("program_allow_hostbyrequest")) || ($this ->getVar("program_allow_hostbyrequest") === "")) {
      return $this ->getVar("program_allow_hostbyrequest");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramAllowHostbyrequest();
    } elseif (($this ->sessionVar("program_allow_hostbyrequest")) || ($this ->sessionVar("program_allow_hostbyrequest") == "")) {
      return $this ->sessionVar("program_allow_hostbyrequest");
    } else {
      return false;
    }
  }
  
  function setProgramAllowHostbyrequest( $str ) {
    $this ->Program -> setProgramAllowHostbyrequest( $str );
  }
  
  function getProgramAllowUserHosting() {
    if (($this ->postVar("program_allow_user_hosting")) || ($this ->postVar("program_allow_user_hosting") === "")) {
      return $this ->postVar("program_allow_user_hosting");
    } elseif (($this ->getVar("program_allow_user_hosting")) || ($this ->getVar("program_allow_user_hosting") === "")) {
      return $this ->getVar("program_allow_user_hosting");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramAllowUserHosting();
    } elseif (($this ->sessionVar("program_allow_user_hosting")) || ($this ->sessionVar("program_allow_user_hosting") == "")) {
      return $this ->sessionVar("program_allow_user_hosting");
    } else {
      return false;
    }
  }
  
  function setProgramAllowUserHosting( $str ) {
    $this ->Program -> setProgramAllowUserHosting( $str );
  }
  
  function getProgramAlternateTemplate() {
    if (($this ->postVar("program_alternate_template")) || ($this ->postVar("program_alternate_template") === "")) {
      return $this ->postVar("program_alternate_template");
    } elseif (($this ->getVar("program_alternate_template")) || ($this ->getVar("program_alternate_template") === "")) {
      return $this ->getVar("program_alternate_template");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramAlternateTemplate();
    } elseif (($this ->sessionVar("program_alternate_template")) || ($this ->sessionVar("program_alternate_template") == "")) {
      return $this ->sessionVar("program_alternate_template");
    } else {
      return false;
    }
  }
  
  function setProgramAlternateTemplate( $str ) {
    $this ->Program -> setProgramAlternateTemplate( $str );
  }
  
  function getProgramAlternateOpts() {
    if (($this ->postVar("program_alternate_opts")) || ($this ->postVar("program_alternate_opts") === "")) {
      return $this ->postVar("program_alternate_opts");
    } elseif (($this ->getVar("program_alternate_opts")) || ($this ->getVar("program_alternate_opts") === "")) {
      return $this ->getVar("program_alternate_opts");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramAlternateOpts();
    } elseif (($this ->sessionVar("program_alternate_opts")) || ($this ->sessionVar("program_alternate_opts") == "")) {
      return $this ->sessionVar("program_alternate_opts");
    } else {
      return false;
    }
  }
  
  function setProgramAlternateOpts( $str ) {
    $this ->Program -> setProgramAlternateOpts( $str );
  }
  
  function getProgramShare() {
    if (($this ->postVar("program_share")) || ($this ->postVar("program_share") === "")) {
      return $this ->postVar("program_share");
    } elseif (($this ->getVar("program_share")) || ($this ->getVar("program_share") === "")) {
      return $this ->getVar("program_share");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramShare();
    } elseif (($this ->sessionVar("program_share")) || ($this ->sessionVar("program_share") == "")) {
      return $this ->sessionVar("program_share");
    } else {
      return false;
    }
  }
  
  function setProgramShare( $str ) {
    $this ->Program -> setProgramShare( $str );
  }
  
  function getProgramSynopsis() {
    if (($this ->postVar("program_synopsis")) || ($this ->postVar("program_synopsis") === "")) {
      return $this ->postVar("program_synopsis");
    } elseif (($this ->getVar("program_synopsis")) || ($this ->getVar("program_synopsis") === "")) {
      return $this ->getVar("program_synopsis");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramSynopsis();
    } elseif (($this ->sessionVar("program_synopsis")) || ($this ->sessionVar("program_synopsis") == "")) {
      return $this ->sessionVar("program_synopsis");
    } else {
      return false;
    }
  }
  
  function setProgramSynopsis( $str ) {
    $this ->Program -> setProgramSynopsis( $str );
  }
  
  function getProgramUrl() {
    if (($this ->postVar("program_url")) || ($this ->postVar("program_url") === "")) {
      return $this ->postVar("program_url");
    } elseif (($this ->getVar("program_url")) || ($this ->getVar("program_url") === "")) {
      return $this ->getVar("program_url");
    } elseif (($this ->Program) || ($this ->Program === "")){
      return $this ->Program -> getProgramUrl();
    } elseif (($this ->sessionVar("program_url")) || ($this ->sessionVar("program_url") == "")) {
      return $this ->sessionVar("program_url");
    } else {
      return false;
    }
  }
  
  function setProgramUrl( $str ) {
    $this ->Program -> setProgramUrl( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Program = ProgramPeer::retrieveByPK( $id );
    }
    
    if ($this ->Program ) {
       
    	       (is_numeric(WTVRcleanString($this ->Program->getProgramId()))) ? $itemarray["program_id"] = WTVRcleanString($this ->Program->getProgramId()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Program->getProgramCreatedAt())) ? $itemarray["program_created_at"] = formatDate($this ->Program->getProgramCreatedAt('%Y-%m-%d %T'),"TS") : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Program->getProgramUpdatedAt())) ? $itemarray["program_updated_at"] = formatDate($this ->Program->getProgramUpdatedAt('%Y-%m-%d %T'),"TS") : null;
          (is_numeric(WTVRcleanString($this ->Program->getFkProgramSponsorId()))) ? $itemarray["fk_program_sponsor_id"] = WTVRcleanString($this ->Program->getFkProgramSponsorId()) : null;
          (WTVRcleanString($this ->Program->getProgramName())) ? $itemarray["program_name"] = WTVRcleanString($this ->Program->getProgramName()) : null;
          (WTVRcleanString($this ->Program->getProgramShortName())) ? $itemarray["program_short_name"] = WTVRcleanString($this ->Program->getProgramShortName()) : null;
          (is_numeric(WTVRcleanString($this ->Program->getFkFilmId()))) ? $itemarray["fk_film_id"] = WTVRcleanString($this ->Program->getFkFilmId()) : null;
          (WTVRcleanString($this ->Program->getProgramLogo())) ? $itemarray["program_logo"] = WTVRcleanString($this ->Program->getProgramLogo()) : null;
          (WTVRcleanString($this ->Program->getProgramFeatured())) ? $itemarray["program_featured"] = WTVRcleanString($this ->Program->getProgramFeatured()) : null;
          (WTVRcleanString($this ->Program->getProgramProductionCompany())) ? $itemarray["program_production_company"] = WTVRcleanString($this ->Program->getProgramProductionCompany()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Program->getProgramStartDate())) ? $itemarray["program_start_date"] = formatDate($this ->Program->getProgramStartDate('%Y-%m-%d %T'),"TS") : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Program->getProgramEndDate())) ? $itemarray["program_end_date"] = formatDate($this ->Program->getProgramEndDate('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->Program->getProgramPreuser())) ? $itemarray["program_preuser"] = WTVRcleanString($this ->Program->getProgramPreuser()) : null;
          (is_numeric(WTVRcleanString($this ->Program->getProgramTotalSeats()))) ? $itemarray["program_total_seats"] = WTVRcleanString($this ->Program->getProgramTotalSeats()) : null;
          (is_numeric(WTVRcleanString($this ->Program->getProgramTicketPrice()))) ? $itemarray["program_ticket_price"] = sprintf("%01.2f",WTVRcleanString($this ->Program->getProgramTicketPrice())) : null;
          (is_numeric(WTVRcleanString($this ->Program->getProgramHostbyrequestPrice()))) ? $itemarray["program_hostbyrequest_price"] = sprintf("%01.2f",WTVRcleanString($this ->Program->getProgramHostbyrequestPrice())) : null;
          (is_numeric(WTVRcleanString($this ->Program->getProgramSetupPrice()))) ? $itemarray["program_setup_price"] = sprintf("%01.2f",WTVRcleanString($this ->Program->getProgramSetupPrice())) : null;
          (WTVRcleanString($this ->Program->getProgramInfo())) ? $itemarray["program_info"] = WTVRcleanString($this ->Program->getProgramInfo()) : null;
          (WTVRcleanString($this ->Program->getProgramStatus())) ? $itemarray["program_status"] = WTVRcleanString($this ->Program->getProgramStatus()) : null;
          (WTVRcleanString($this ->Program->getProgramStillImage())) ? $itemarray["program_still_image"] = WTVRcleanString($this ->Program->getProgramStillImage()) : null;
          (WTVRcleanString($this ->Program->getProgramBackgroundImage())) ? $itemarray["program_background_image"] = WTVRcleanString($this ->Program->getProgramBackgroundImage()) : null;
          (WTVRcleanString($this ->Program->getProgramGeoblockingEnabled())) ? $itemarray["program_geoblocking_enabled"] = WTVRcleanString($this ->Program->getProgramGeoblockingEnabled()) : null;
          (WTVRcleanString($this ->Program->getProgramGeoblockingType())) ? $itemarray["program_geoblocking_type"] = WTVRcleanString($this ->Program->getProgramGeoblockingType()) : null;
          (WTVRcleanString($this ->Program->getProgramUseSponsorCodes())) ? $itemarray["program_use_sponsor_codes"] = WTVRcleanString($this ->Program->getProgramUseSponsorCodes()) : null;
          (WTVRcleanString($this ->Program->getProgramAllowHostbyrequest())) ? $itemarray["program_allow_hostbyrequest"] = WTVRcleanString($this ->Program->getProgramAllowHostbyrequest()) : null;
          (WTVRcleanString($this ->Program->getProgramAllowUserHosting())) ? $itemarray["program_allow_user_hosting"] = WTVRcleanString($this ->Program->getProgramAllowUserHosting()) : null;
          (WTVRcleanString($this ->Program->getProgramAlternateTemplate())) ? $itemarray["program_alternate_template"] = WTVRcleanString($this ->Program->getProgramAlternateTemplate()) : null;
          (WTVRcleanString($this ->Program->getProgramAlternateOpts())) ? $itemarray["program_alternate_opts"] = WTVRcleanString($this ->Program->getProgramAlternateOpts()) : null;
          (WTVRcleanString($this ->Program->getProgramShare())) ? $itemarray["program_share"] = WTVRcleanString($this ->Program->getProgramShare()) : null;
          (WTVRcleanString($this ->Program->getProgramSynopsis())) ? $itemarray["program_synopsis"] = WTVRcleanString($this ->Program->getProgramSynopsis()) : null;
          (WTVRcleanString($this ->Program->getProgramUrl())) ? $itemarray["program_url"] = WTVRcleanString($this ->Program->getProgramUrl()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Program = ProgramPeer::retrieveByPK( $id );
    } elseif (! $this ->Program) {
      $this ->Program = new Program;
    }
        
  	 ($this -> getProgramId())? $this ->Program->setProgramId( WTVRcleanString( $this -> getProgramId()) ) : null;
          if (is_valid_date( $this ->Program->getProgramCreatedAt())) {
        $this ->Program->setProgramCreatedAt( formatDate($this -> getProgramCreatedAt(), "TS" ));
      } else {
      $Programprogram_created_at = $this -> sfDateTime( "program_created_at" );
      ( $Programprogram_created_at != "01/01/1900 00:00:00" )? $this ->Program->setProgramCreatedAt( formatDate($Programprogram_created_at, "TS" )) : $this ->Program->setProgramCreatedAt( null );
      }
          if (is_valid_date( $this ->Program->getProgramUpdatedAt())) {
        $this ->Program->setProgramUpdatedAt( formatDate($this -> getProgramUpdatedAt(), "TS" ));
      } else {
      $Programprogram_updated_at = $this -> sfDateTime( "program_updated_at" );
      ( $Programprogram_updated_at != "01/01/1900 00:00:00" )? $this ->Program->setProgramUpdatedAt( formatDate($Programprogram_updated_at, "TS" )) : $this ->Program->setProgramUpdatedAt( null );
      }
    ($this -> getFkProgramSponsorId())? $this ->Program->setFkProgramSponsorId( WTVRcleanString( $this -> getFkProgramSponsorId()) ) : null;
    ($this -> getProgramName())? $this ->Program->setProgramName( WTVRcleanString( $this -> getProgramName()) ) : null;
    ($this -> getProgramShortName())? $this ->Program->setProgramShortName( WTVRcleanString( $this -> getProgramShortName()) ) : null;
    ($this -> getFkFilmId())? $this ->Program->setFkFilmId( WTVRcleanString( $this -> getFkFilmId()) ) : null;
    ($this -> getProgramLogo())? $this ->Program->setProgramLogo( WTVRcleanString( $this -> getProgramLogo()) ) : null;
    ($this -> getProgramFeatured())? $this ->Program->setProgramFeatured( WTVRcleanString( $this -> getProgramFeatured()) ) : null;
    ($this -> getProgramProductionCompany())? $this ->Program->setProgramProductionCompany( WTVRcleanString( $this -> getProgramProductionCompany()) ) : null;
          if (is_valid_date( $this ->Program->getProgramStartDate())) {
        $this ->Program->setProgramStartDate( formatDate($this -> getProgramStartDate(), "TS" ));
      } else {
      $Programprogram_start_date = $this -> sfDateTime( "program_start_date" );
      ( $Programprogram_start_date != "01/01/1900 00:00:00" )? $this ->Program->setProgramStartDate( formatDate($Programprogram_start_date, "TS" )) : $this ->Program->setProgramStartDate( null );
      }
          if (is_valid_date( $this ->Program->getProgramEndDate())) {
        $this ->Program->setProgramEndDate( formatDate($this -> getProgramEndDate(), "TS" ));
      } else {
      $Programprogram_end_date = $this -> sfDateTime( "program_end_date" );
      ( $Programprogram_end_date != "01/01/1900 00:00:00" )? $this ->Program->setProgramEndDate( formatDate($Programprogram_end_date, "TS" )) : $this ->Program->setProgramEndDate( null );
      }
    ($this -> getProgramPreuser())? $this ->Program->setProgramPreuser( WTVRcleanString( $this -> getProgramPreuser()) ) : null;
    ($this -> getProgramTotalSeats())? $this ->Program->setProgramTotalSeats( WTVRcleanString( $this -> getProgramTotalSeats()) ) : null;
          (is_numeric($this ->getProgramTicketPrice())) ? $this ->Program->setProgramTicketPrice( (float) $this -> getProgramTicketPrice() ) : null;
          (is_numeric($this ->getProgramHostbyrequestPrice())) ? $this ->Program->setProgramHostbyrequestPrice( (float) $this -> getProgramHostbyrequestPrice() ) : null;
          (is_numeric($this ->getProgramSetupPrice())) ? $this ->Program->setProgramSetupPrice( (float) $this -> getProgramSetupPrice() ) : null;
    ($this -> getProgramInfo())? $this ->Program->setProgramInfo( WTVRcleanString( $this -> getProgramInfo()) ) : null;
    ($this -> getProgramStatus())? $this ->Program->setProgramStatus( WTVRcleanString( $this -> getProgramStatus()) ) : null;
    ($this -> getProgramStillImage())? $this ->Program->setProgramStillImage( WTVRcleanString( $this -> getProgramStillImage()) ) : null;
    ($this -> getProgramBackgroundImage())? $this ->Program->setProgramBackgroundImage( WTVRcleanString( $this -> getProgramBackgroundImage()) ) : null;
    ($this -> getProgramGeoblockingEnabled())? $this ->Program->setProgramGeoblockingEnabled( WTVRcleanString( $this -> getProgramGeoblockingEnabled()) ) : null;
    ($this -> getProgramGeoblockingType())? $this ->Program->setProgramGeoblockingType( WTVRcleanString( $this -> getProgramGeoblockingType()) ) : null;
    ($this -> getProgramUseSponsorCodes())? $this ->Program->setProgramUseSponsorCodes( WTVRcleanString( $this -> getProgramUseSponsorCodes()) ) : null;
    ($this -> getProgramAllowHostbyrequest())? $this ->Program->setProgramAllowHostbyrequest( WTVRcleanString( $this -> getProgramAllowHostbyrequest()) ) : null;
    ($this -> getProgramAllowUserHosting())? $this ->Program->setProgramAllowUserHosting( WTVRcleanString( $this -> getProgramAllowUserHosting()) ) : null;
    ($this -> getProgramAlternateTemplate())? $this ->Program->setProgramAlternateTemplate( WTVRcleanString( $this -> getProgramAlternateTemplate()) ) : null;
    ($this -> getProgramAlternateOpts())? $this ->Program->setProgramAlternateOpts( WTVRcleanString( $this -> getProgramAlternateOpts()) ) : null;
    ($this -> getProgramShare())? $this ->Program->setProgramShare( WTVRcleanString( $this -> getProgramShare()) ) : null;
    ($this -> getProgramSynopsis())? $this ->Program->setProgramSynopsis( WTVRcleanString( $this -> getProgramSynopsis()) ) : null;
    ($this -> getProgramUrl())? $this ->Program->setProgramUrl( WTVRcleanString( $this -> getProgramUrl()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Program ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Program = ProgramPeer::retrieveByPK($id);
    }
    
    if (! $this ->Program ) {
      return;
    }
    
    $this ->Program -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Program_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "ProgramPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Program = ProgramPeer::doSelect($c);
    
    if (count($Program) >= 1) {
      $this ->Program = $Program[0];
      return true;
    } else {
      $this ->Program = new Program();
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
      $name = "ProgramPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Program = ProgramPeer::doSelect($c);
    
    if (count($Program) >= 1) {
      $this ->Program = $Program[0];
      return true;
    } else {
      $this ->Program = new Program();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>