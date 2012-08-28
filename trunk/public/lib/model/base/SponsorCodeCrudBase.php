<?php
       
   class SponsorCodeCrudBase extends Utils_PageWidget { 
   
    var $SponsorCode;
   
       var $sponsor_code_id;
   var $sponsor_code;
   var $fk_film_id;
   var $sponsor_code_start_date;
   var $sponsor_code_end_date;
   var $sponsor_code_use;
   var $sponsor_code_use_count;
   var $sponsor_code_user_fname;
   var $sponsor_code_user_lname;
   var $sponsor_code_user_email;
   var $sponsor_code_user_username;
   var $sponsor_code_user_notified;
   var $fk_user_id;
   var $fk_screening_id;
   var $fk_screening_unique_key;
   var $sponsor_code_spawn_new_users;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getSponsorCodeId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->SponsorCode = SponsorCodePeer::retrieveByPK( $id );
    } else {
      $this ->SponsorCode = new SponsorCode;
    }
  }
  
  function hydrate( $id ) {
      $this ->SponsorCode = SponsorCodePeer::retrieveByPK( $id );
  }
  
  function getSponsorCodeId() {
    if (($this ->postVar("sponsor_code_id")) || ($this ->postVar("sponsor_code_id") === "")) {
      return $this ->postVar("sponsor_code_id");
    } elseif (($this ->getVar("sponsor_code_id")) || ($this ->getVar("sponsor_code_id") === "")) {
      return $this ->getVar("sponsor_code_id");
    } elseif (($this ->SponsorCode) || ($this ->SponsorCode === "")){
      return $this ->SponsorCode -> getSponsorCodeId();
    } elseif (($this ->sessionVar("sponsor_code_id")) || ($this ->sessionVar("sponsor_code_id") == "")) {
      return $this ->sessionVar("sponsor_code_id");
    } else {
      return false;
    }
  }
  
  function setSponsorCodeId( $str ) {
    $this ->SponsorCode -> setSponsorCodeId( $str );
  }
  
  function getSponsorCode() {
    if (($this ->postVar("sponsor_code")) || ($this ->postVar("sponsor_code") === "")) {
      return $this ->postVar("sponsor_code");
    } elseif (($this ->getVar("sponsor_code")) || ($this ->getVar("sponsor_code") === "")) {
      return $this ->getVar("sponsor_code");
    } elseif (($this ->SponsorCode) || ($this ->SponsorCode === "")){
      return $this ->SponsorCode -> getSponsorCode();
    } elseif (($this ->sessionVar("sponsor_code")) || ($this ->sessionVar("sponsor_code") == "")) {
      return $this ->sessionVar("sponsor_code");
    } else {
      return false;
    }
  }
  
  function setSponsorCode( $str ) {
    $this ->SponsorCode -> setSponsorCode( $str );
  }
  
  function getFkFilmId() {
    if (($this ->postVar("fk_film_id")) || ($this ->postVar("fk_film_id") === "")) {
      return $this ->postVar("fk_film_id");
    } elseif (($this ->getVar("fk_film_id")) || ($this ->getVar("fk_film_id") === "")) {
      return $this ->getVar("fk_film_id");
    } elseif (($this ->SponsorCode) || ($this ->SponsorCode === "")){
      return $this ->SponsorCode -> getFkFilmId();
    } elseif (($this ->sessionVar("fk_film_id")) || ($this ->sessionVar("fk_film_id") == "")) {
      return $this ->sessionVar("fk_film_id");
    } else {
      return false;
    }
  }
  
  function setFkFilmId( $str ) {
    $this ->SponsorCode -> setFkFilmId( $str );
  }
  
  function getSponsorCodeStartDate() {
    if (($this ->postVar("sponsor_code_start_date")) || ($this ->postVar("sponsor_code_start_date") === "")) {
      return $this ->postVar("sponsor_code_start_date");
    } elseif (($this ->getVar("sponsor_code_start_date")) || ($this ->getVar("sponsor_code_start_date") === "")) {
      return $this ->getVar("sponsor_code_start_date");
    } elseif (($this ->SponsorCode) || ($this ->SponsorCode === "")){
      return $this ->SponsorCode -> getSponsorCodeStartDate();
    } elseif (($this ->sessionVar("sponsor_code_start_date")) || ($this ->sessionVar("sponsor_code_start_date") == "")) {
      return $this ->sessionVar("sponsor_code_start_date");
    } else {
      return false;
    }
  }
  
  function setSponsorCodeStartDate( $str ) {
    $this ->SponsorCode -> setSponsorCodeStartDate( $str );
  }
  
  function getSponsorCodeEndDate() {
    if (($this ->postVar("sponsor_code_end_date")) || ($this ->postVar("sponsor_code_end_date") === "")) {
      return $this ->postVar("sponsor_code_end_date");
    } elseif (($this ->getVar("sponsor_code_end_date")) || ($this ->getVar("sponsor_code_end_date") === "")) {
      return $this ->getVar("sponsor_code_end_date");
    } elseif (($this ->SponsorCode) || ($this ->SponsorCode === "")){
      return $this ->SponsorCode -> getSponsorCodeEndDate();
    } elseif (($this ->sessionVar("sponsor_code_end_date")) || ($this ->sessionVar("sponsor_code_end_date") == "")) {
      return $this ->sessionVar("sponsor_code_end_date");
    } else {
      return false;
    }
  }
  
  function setSponsorCodeEndDate( $str ) {
    $this ->SponsorCode -> setSponsorCodeEndDate( $str );
  }
  
  function getSponsorCodeUse() {
    if (($this ->postVar("sponsor_code_use")) || ($this ->postVar("sponsor_code_use") === "")) {
      return $this ->postVar("sponsor_code_use");
    } elseif (($this ->getVar("sponsor_code_use")) || ($this ->getVar("sponsor_code_use") === "")) {
      return $this ->getVar("sponsor_code_use");
    } elseif (($this ->SponsorCode) || ($this ->SponsorCode === "")){
      return $this ->SponsorCode -> getSponsorCodeUse();
    } elseif (($this ->sessionVar("sponsor_code_use")) || ($this ->sessionVar("sponsor_code_use") == "")) {
      return $this ->sessionVar("sponsor_code_use");
    } else {
      return false;
    }
  }
  
  function setSponsorCodeUse( $str ) {
    $this ->SponsorCode -> setSponsorCodeUse( $str );
  }
  
  function getSponsorCodeUseCount() {
    if (($this ->postVar("sponsor_code_use_count")) || ($this ->postVar("sponsor_code_use_count") === "")) {
      return $this ->postVar("sponsor_code_use_count");
    } elseif (($this ->getVar("sponsor_code_use_count")) || ($this ->getVar("sponsor_code_use_count") === "")) {
      return $this ->getVar("sponsor_code_use_count");
    } elseif (($this ->SponsorCode) || ($this ->SponsorCode === "")){
      return $this ->SponsorCode -> getSponsorCodeUseCount();
    } elseif (($this ->sessionVar("sponsor_code_use_count")) || ($this ->sessionVar("sponsor_code_use_count") == "")) {
      return $this ->sessionVar("sponsor_code_use_count");
    } else {
      return false;
    }
  }
  
  function setSponsorCodeUseCount( $str ) {
    $this ->SponsorCode -> setSponsorCodeUseCount( $str );
  }
  
  function getSponsorCodeUserFname() {
    if (($this ->postVar("sponsor_code_user_fname")) || ($this ->postVar("sponsor_code_user_fname") === "")) {
      return $this ->postVar("sponsor_code_user_fname");
    } elseif (($this ->getVar("sponsor_code_user_fname")) || ($this ->getVar("sponsor_code_user_fname") === "")) {
      return $this ->getVar("sponsor_code_user_fname");
    } elseif (($this ->SponsorCode) || ($this ->SponsorCode === "")){
      return $this ->SponsorCode -> getSponsorCodeUserFname();
    } elseif (($this ->sessionVar("sponsor_code_user_fname")) || ($this ->sessionVar("sponsor_code_user_fname") == "")) {
      return $this ->sessionVar("sponsor_code_user_fname");
    } else {
      return false;
    }
  }
  
  function setSponsorCodeUserFname( $str ) {
    $this ->SponsorCode -> setSponsorCodeUserFname( $str );
  }
  
  function getSponsorCodeUserLname() {
    if (($this ->postVar("sponsor_code_user_lname")) || ($this ->postVar("sponsor_code_user_lname") === "")) {
      return $this ->postVar("sponsor_code_user_lname");
    } elseif (($this ->getVar("sponsor_code_user_lname")) || ($this ->getVar("sponsor_code_user_lname") === "")) {
      return $this ->getVar("sponsor_code_user_lname");
    } elseif (($this ->SponsorCode) || ($this ->SponsorCode === "")){
      return $this ->SponsorCode -> getSponsorCodeUserLname();
    } elseif (($this ->sessionVar("sponsor_code_user_lname")) || ($this ->sessionVar("sponsor_code_user_lname") == "")) {
      return $this ->sessionVar("sponsor_code_user_lname");
    } else {
      return false;
    }
  }
  
  function setSponsorCodeUserLname( $str ) {
    $this ->SponsorCode -> setSponsorCodeUserLname( $str );
  }
  
  function getSponsorCodeUserEmail() {
    if (($this ->postVar("sponsor_code_user_email")) || ($this ->postVar("sponsor_code_user_email") === "")) {
      return $this ->postVar("sponsor_code_user_email");
    } elseif (($this ->getVar("sponsor_code_user_email")) || ($this ->getVar("sponsor_code_user_email") === "")) {
      return $this ->getVar("sponsor_code_user_email");
    } elseif (($this ->SponsorCode) || ($this ->SponsorCode === "")){
      return $this ->SponsorCode -> getSponsorCodeUserEmail();
    } elseif (($this ->sessionVar("sponsor_code_user_email")) || ($this ->sessionVar("sponsor_code_user_email") == "")) {
      return $this ->sessionVar("sponsor_code_user_email");
    } else {
      return false;
    }
  }
  
  function setSponsorCodeUserEmail( $str ) {
    $this ->SponsorCode -> setSponsorCodeUserEmail( $str );
  }
  
  function getSponsorCodeUserUsername() {
    if (($this ->postVar("sponsor_code_user_username")) || ($this ->postVar("sponsor_code_user_username") === "")) {
      return $this ->postVar("sponsor_code_user_username");
    } elseif (($this ->getVar("sponsor_code_user_username")) || ($this ->getVar("sponsor_code_user_username") === "")) {
      return $this ->getVar("sponsor_code_user_username");
    } elseif (($this ->SponsorCode) || ($this ->SponsorCode === "")){
      return $this ->SponsorCode -> getSponsorCodeUserUsername();
    } elseif (($this ->sessionVar("sponsor_code_user_username")) || ($this ->sessionVar("sponsor_code_user_username") == "")) {
      return $this ->sessionVar("sponsor_code_user_username");
    } else {
      return false;
    }
  }
  
  function setSponsorCodeUserUsername( $str ) {
    $this ->SponsorCode -> setSponsorCodeUserUsername( $str );
  }
  
  function getSponsorCodeUserNotified() {
    if (($this ->postVar("sponsor_code_user_notified")) || ($this ->postVar("sponsor_code_user_notified") === "")) {
      return $this ->postVar("sponsor_code_user_notified");
    } elseif (($this ->getVar("sponsor_code_user_notified")) || ($this ->getVar("sponsor_code_user_notified") === "")) {
      return $this ->getVar("sponsor_code_user_notified");
    } elseif (($this ->SponsorCode) || ($this ->SponsorCode === "")){
      return $this ->SponsorCode -> getSponsorCodeUserNotified();
    } elseif (($this ->sessionVar("sponsor_code_user_notified")) || ($this ->sessionVar("sponsor_code_user_notified") == "")) {
      return $this ->sessionVar("sponsor_code_user_notified");
    } else {
      return false;
    }
  }
  
  function setSponsorCodeUserNotified( $str ) {
    $this ->SponsorCode -> setSponsorCodeUserNotified( $str );
  }
  
  function getFkUserId() {
    if (($this ->postVar("fk_user_id")) || ($this ->postVar("fk_user_id") === "")) {
      return $this ->postVar("fk_user_id");
    } elseif (($this ->getVar("fk_user_id")) || ($this ->getVar("fk_user_id") === "")) {
      return $this ->getVar("fk_user_id");
    } elseif (($this ->SponsorCode) || ($this ->SponsorCode === "")){
      return $this ->SponsorCode -> getFkUserId();
    } elseif (($this ->sessionVar("fk_user_id")) || ($this ->sessionVar("fk_user_id") == "")) {
      return $this ->sessionVar("fk_user_id");
    } else {
      return false;
    }
  }
  
  function setFkUserId( $str ) {
    $this ->SponsorCode -> setFkUserId( $str );
  }
  
  function getFkScreeningId() {
    if (($this ->postVar("fk_screening_id")) || ($this ->postVar("fk_screening_id") === "")) {
      return $this ->postVar("fk_screening_id");
    } elseif (($this ->getVar("fk_screening_id")) || ($this ->getVar("fk_screening_id") === "")) {
      return $this ->getVar("fk_screening_id");
    } elseif (($this ->SponsorCode) || ($this ->SponsorCode === "")){
      return $this ->SponsorCode -> getFkScreeningId();
    } elseif (($this ->sessionVar("fk_screening_id")) || ($this ->sessionVar("fk_screening_id") == "")) {
      return $this ->sessionVar("fk_screening_id");
    } else {
      return false;
    }
  }
  
  function setFkScreeningId( $str ) {
    $this ->SponsorCode -> setFkScreeningId( $str );
  }
  
  function getFkScreeningUniqueKey() {
    if (($this ->postVar("fk_screening_unique_key")) || ($this ->postVar("fk_screening_unique_key") === "")) {
      return $this ->postVar("fk_screening_unique_key");
    } elseif (($this ->getVar("fk_screening_unique_key")) || ($this ->getVar("fk_screening_unique_key") === "")) {
      return $this ->getVar("fk_screening_unique_key");
    } elseif (($this ->SponsorCode) || ($this ->SponsorCode === "")){
      return $this ->SponsorCode -> getFkScreeningUniqueKey();
    } elseif (($this ->sessionVar("fk_screening_unique_key")) || ($this ->sessionVar("fk_screening_unique_key") == "")) {
      return $this ->sessionVar("fk_screening_unique_key");
    } else {
      return false;
    }
  }
  
  function setFkScreeningUniqueKey( $str ) {
    $this ->SponsorCode -> setFkScreeningUniqueKey( $str );
  }
  
  function getSponsorCodeSpawnNewUsers() {
    if (($this ->postVar("sponsor_code_spawn_new_users")) || ($this ->postVar("sponsor_code_spawn_new_users") === "")) {
      return $this ->postVar("sponsor_code_spawn_new_users");
    } elseif (($this ->getVar("sponsor_code_spawn_new_users")) || ($this ->getVar("sponsor_code_spawn_new_users") === "")) {
      return $this ->getVar("sponsor_code_spawn_new_users");
    } elseif (($this ->SponsorCode) || ($this ->SponsorCode === "")){
      return $this ->SponsorCode -> getSponsorCodeSpawnNewUsers();
    } elseif (($this ->sessionVar("sponsor_code_spawn_new_users")) || ($this ->sessionVar("sponsor_code_spawn_new_users") == "")) {
      return $this ->sessionVar("sponsor_code_spawn_new_users");
    } else {
      return false;
    }
  }
  
  function setSponsorCodeSpawnNewUsers( $str ) {
    $this ->SponsorCode -> setSponsorCodeSpawnNewUsers( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->SponsorCode = SponsorCodePeer::retrieveByPK( $id );
    }
    
    if ($this ->SponsorCode ) {
       
    	       (is_numeric(WTVRcleanString($this ->SponsorCode->getSponsorCodeId()))) ? $itemarray["sponsor_code_id"] = WTVRcleanString($this ->SponsorCode->getSponsorCodeId()) : null;
          (WTVRcleanString($this ->SponsorCode->getSponsorCode())) ? $itemarray["sponsor_code"] = WTVRcleanString($this ->SponsorCode->getSponsorCode()) : null;
          (is_numeric(WTVRcleanString($this ->SponsorCode->getFkFilmId()))) ? $itemarray["fk_film_id"] = WTVRcleanString($this ->SponsorCode->getFkFilmId()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->SponsorCode->getSponsorCodeStartDate())) ? $itemarray["sponsor_code_start_date"] = formatDate($this ->SponsorCode->getSponsorCodeStartDate('%Y-%m-%d %T'),"TS") : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->SponsorCode->getSponsorCodeEndDate())) ? $itemarray["sponsor_code_end_date"] = formatDate($this ->SponsorCode->getSponsorCodeEndDate('%Y-%m-%d %T'),"TS") : null;
          (is_numeric(WTVRcleanString($this ->SponsorCode->getSponsorCodeUse()))) ? $itemarray["sponsor_code_use"] = WTVRcleanString($this ->SponsorCode->getSponsorCodeUse()) : null;
          (is_numeric(WTVRcleanString($this ->SponsorCode->getSponsorCodeUseCount()))) ? $itemarray["sponsor_code_use_count"] = WTVRcleanString($this ->SponsorCode->getSponsorCodeUseCount()) : null;
          (WTVRcleanString($this ->SponsorCode->getSponsorCodeUserFname())) ? $itemarray["sponsor_code_user_fname"] = WTVRcleanString($this ->SponsorCode->getSponsorCodeUserFname()) : null;
          (WTVRcleanString($this ->SponsorCode->getSponsorCodeUserLname())) ? $itemarray["sponsor_code_user_lname"] = WTVRcleanString($this ->SponsorCode->getSponsorCodeUserLname()) : null;
          (WTVRcleanString($this ->SponsorCode->getSponsorCodeUserEmail())) ? $itemarray["sponsor_code_user_email"] = WTVRcleanString($this ->SponsorCode->getSponsorCodeUserEmail()) : null;
          (WTVRcleanString($this ->SponsorCode->getSponsorCodeUserUsername())) ? $itemarray["sponsor_code_user_username"] = WTVRcleanString($this ->SponsorCode->getSponsorCodeUserUsername()) : null;
          (is_numeric(WTVRcleanString($this ->SponsorCode->getSponsorCodeUserNotified()))) ? $itemarray["sponsor_code_user_notified"] = WTVRcleanString($this ->SponsorCode->getSponsorCodeUserNotified()) : null;
          (is_numeric(WTVRcleanString($this ->SponsorCode->getFkUserId()))) ? $itemarray["fk_user_id"] = WTVRcleanString($this ->SponsorCode->getFkUserId()) : null;
          (is_numeric(WTVRcleanString($this ->SponsorCode->getFkScreeningId()))) ? $itemarray["fk_screening_id"] = WTVRcleanString($this ->SponsorCode->getFkScreeningId()) : null;
          (WTVRcleanString($this ->SponsorCode->getFkScreeningUniqueKey())) ? $itemarray["fk_screening_unique_key"] = WTVRcleanString($this ->SponsorCode->getFkScreeningUniqueKey()) : null;
          (WTVRcleanString($this ->SponsorCode->getSponsorCodeSpawnNewUsers())) ? $itemarray["sponsor_code_spawn_new_users"] = WTVRcleanString($this ->SponsorCode->getSponsorCodeSpawnNewUsers()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->SponsorCode = SponsorCodePeer::retrieveByPK( $id );
    } elseif (! $this ->SponsorCode) {
      $this ->SponsorCode = new SponsorCode;
    }
        
  	 ($this -> getSponsorCodeId())? $this ->SponsorCode->setSponsorCodeId( WTVRcleanString( $this -> getSponsorCodeId()) ) : null;
    ($this -> getSponsorCode())? $this ->SponsorCode->setSponsorCode( WTVRcleanString( $this -> getSponsorCode()) ) : null;
    ($this -> getFkFilmId())? $this ->SponsorCode->setFkFilmId( WTVRcleanString( $this -> getFkFilmId()) ) : null;
          if (is_valid_date( $this ->SponsorCode->getSponsorCodeStartDate())) {
        $this ->SponsorCode->setSponsorCodeStartDate( formatDate($this -> getSponsorCodeStartDate(), "TS" ));
      } else {
      $SponsorCodesponsor_code_start_date = $this -> sfDateTime( "sponsor_code_start_date" );
      ( $SponsorCodesponsor_code_start_date != "01/01/1900 00:00:00" )? $this ->SponsorCode->setSponsorCodeStartDate( formatDate($SponsorCodesponsor_code_start_date, "TS" )) : $this ->SponsorCode->setSponsorCodeStartDate( null );
      }
          if (is_valid_date( $this ->SponsorCode->getSponsorCodeEndDate())) {
        $this ->SponsorCode->setSponsorCodeEndDate( formatDate($this -> getSponsorCodeEndDate(), "TS" ));
      } else {
      $SponsorCodesponsor_code_end_date = $this -> sfDateTime( "sponsor_code_end_date" );
      ( $SponsorCodesponsor_code_end_date != "01/01/1900 00:00:00" )? $this ->SponsorCode->setSponsorCodeEndDate( formatDate($SponsorCodesponsor_code_end_date, "TS" )) : $this ->SponsorCode->setSponsorCodeEndDate( null );
      }
    ($this -> getSponsorCodeUse())? $this ->SponsorCode->setSponsorCodeUse( WTVRcleanString( $this -> getSponsorCodeUse()) ) : null;
    ($this -> getSponsorCodeUseCount())? $this ->SponsorCode->setSponsorCodeUseCount( WTVRcleanString( $this -> getSponsorCodeUseCount()) ) : null;
    ($this -> getSponsorCodeUserFname())? $this ->SponsorCode->setSponsorCodeUserFname( WTVRcleanString( $this -> getSponsorCodeUserFname()) ) : null;
    ($this -> getSponsorCodeUserLname())? $this ->SponsorCode->setSponsorCodeUserLname( WTVRcleanString( $this -> getSponsorCodeUserLname()) ) : null;
    ($this -> getSponsorCodeUserEmail())? $this ->SponsorCode->setSponsorCodeUserEmail( WTVRcleanString( $this -> getSponsorCodeUserEmail()) ) : null;
    ($this -> getSponsorCodeUserUsername())? $this ->SponsorCode->setSponsorCodeUserUsername( WTVRcleanString( $this -> getSponsorCodeUserUsername()) ) : null;
    ($this -> getSponsorCodeUserNotified())? $this ->SponsorCode->setSponsorCodeUserNotified( WTVRcleanString( $this -> getSponsorCodeUserNotified()) ) : null;
    ($this -> getFkUserId())? $this ->SponsorCode->setFkUserId( WTVRcleanString( $this -> getFkUserId()) ) : null;
    ($this -> getFkScreeningId())? $this ->SponsorCode->setFkScreeningId( WTVRcleanString( $this -> getFkScreeningId()) ) : null;
    ($this -> getFkScreeningUniqueKey())? $this ->SponsorCode->setFkScreeningUniqueKey( WTVRcleanString( $this -> getFkScreeningUniqueKey()) ) : null;
    ($this -> getSponsorCodeSpawnNewUsers())? $this ->SponsorCode->setSponsorCodeSpawnNewUsers( WTVRcleanString( $this -> getSponsorCodeSpawnNewUsers()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->SponsorCode ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->SponsorCode = SponsorCodePeer::retrieveByPK($id);
    }
    
    if (! $this ->SponsorCode ) {
      return;
    }
    
    $this ->SponsorCode -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('SponsorCode_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "SponsorCodePeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $SponsorCode = SponsorCodePeer::doSelect($c);
    
    if (count($SponsorCode) >= 1) {
      $this ->SponsorCode = $SponsorCode[0];
      return true;
    } else {
      $this ->SponsorCode = new SponsorCode();
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
      $name = "SponsorCodePeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $SponsorCode = SponsorCodePeer::doSelect($c);
    
    if (count($SponsorCode) >= 1) {
      $this ->SponsorCode = $SponsorCode[0];
      return true;
    } else {
      $this ->SponsorCode = new SponsorCode();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>