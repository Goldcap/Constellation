<?php
       
   class UserInviteCrudBase extends Utils_PageWidget { 
   
    var $UserInvite;
   
       var $user_invite_id;
   var $fk_user_id;
   var $user_invite_type;
   var $user_type;
   var $fk_film_id;
   var $fk_screening_id;
   var $user_invite_count;
   var $user_invite_date;
   var $user_invite_source;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getUserInviteId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->UserInvite = UserInvitePeer::retrieveByPK( $id );
    } else {
      $this ->UserInvite = new UserInvite;
    }
  }
  
  function hydrate( $id ) {
      $this ->UserInvite = UserInvitePeer::retrieveByPK( $id );
  }
  
  function getUserInviteId() {
    if (($this ->postVar("user_invite_id")) || ($this ->postVar("user_invite_id") === "")) {
      return $this ->postVar("user_invite_id");
    } elseif (($this ->getVar("user_invite_id")) || ($this ->getVar("user_invite_id") === "")) {
      return $this ->getVar("user_invite_id");
    } elseif (($this ->UserInvite) || ($this ->UserInvite === "")){
      return $this ->UserInvite -> getUserInviteId();
    } elseif (($this ->sessionVar("user_invite_id")) || ($this ->sessionVar("user_invite_id") == "")) {
      return $this ->sessionVar("user_invite_id");
    } else {
      return false;
    }
  }
  
  function setUserInviteId( $str ) {
    $this ->UserInvite -> setUserInviteId( $str );
  }
  
  function getFkUserId() {
    if (($this ->postVar("fk_user_id")) || ($this ->postVar("fk_user_id") === "")) {
      return $this ->postVar("fk_user_id");
    } elseif (($this ->getVar("fk_user_id")) || ($this ->getVar("fk_user_id") === "")) {
      return $this ->getVar("fk_user_id");
    } elseif (($this ->UserInvite) || ($this ->UserInvite === "")){
      return $this ->UserInvite -> getFkUserId();
    } elseif (($this ->sessionVar("fk_user_id")) || ($this ->sessionVar("fk_user_id") == "")) {
      return $this ->sessionVar("fk_user_id");
    } else {
      return false;
    }
  }
  
  function setFkUserId( $str ) {
    $this ->UserInvite -> setFkUserId( $str );
  }
  
  function getUserInviteType() {
    if (($this ->postVar("user_invite_type")) || ($this ->postVar("user_invite_type") === "")) {
      return $this ->postVar("user_invite_type");
    } elseif (($this ->getVar("user_invite_type")) || ($this ->getVar("user_invite_type") === "")) {
      return $this ->getVar("user_invite_type");
    } elseif (($this ->UserInvite) || ($this ->UserInvite === "")){
      return $this ->UserInvite -> getUserInviteType();
    } elseif (($this ->sessionVar("user_invite_type")) || ($this ->sessionVar("user_invite_type") == "")) {
      return $this ->sessionVar("user_invite_type");
    } else {
      return false;
    }
  }
  
  function setUserInviteType( $str ) {
    $this ->UserInvite -> setUserInviteType( $str );
  }
  
  function getUserType() {
    if (($this ->postVar("user_type")) || ($this ->postVar("user_type") === "")) {
      return $this ->postVar("user_type");
    } elseif (($this ->getVar("user_type")) || ($this ->getVar("user_type") === "")) {
      return $this ->getVar("user_type");
    } elseif (($this ->UserInvite) || ($this ->UserInvite === "")){
      return $this ->UserInvite -> getUserType();
    } elseif (($this ->sessionVar("user_type")) || ($this ->sessionVar("user_type") == "")) {
      return $this ->sessionVar("user_type");
    } else {
      return false;
    }
  }
  
  function setUserType( $str ) {
    $this ->UserInvite -> setUserType( $str );
  }
  
  function getFkFilmId() {
    if (($this ->postVar("fk_film_id")) || ($this ->postVar("fk_film_id") === "")) {
      return $this ->postVar("fk_film_id");
    } elseif (($this ->getVar("fk_film_id")) || ($this ->getVar("fk_film_id") === "")) {
      return $this ->getVar("fk_film_id");
    } elseif (($this ->UserInvite) || ($this ->UserInvite === "")){
      return $this ->UserInvite -> getFkFilmId();
    } elseif (($this ->sessionVar("fk_film_id")) || ($this ->sessionVar("fk_film_id") == "")) {
      return $this ->sessionVar("fk_film_id");
    } else {
      return false;
    }
  }
  
  function setFkFilmId( $str ) {
    $this ->UserInvite -> setFkFilmId( $str );
  }
  
  function getFkScreeningId() {
    if (($this ->postVar("fk_screening_id")) || ($this ->postVar("fk_screening_id") === "")) {
      return $this ->postVar("fk_screening_id");
    } elseif (($this ->getVar("fk_screening_id")) || ($this ->getVar("fk_screening_id") === "")) {
      return $this ->getVar("fk_screening_id");
    } elseif (($this ->UserInvite) || ($this ->UserInvite === "")){
      return $this ->UserInvite -> getFkScreeningId();
    } elseif (($this ->sessionVar("fk_screening_id")) || ($this ->sessionVar("fk_screening_id") == "")) {
      return $this ->sessionVar("fk_screening_id");
    } else {
      return false;
    }
  }
  
  function setFkScreeningId( $str ) {
    $this ->UserInvite -> setFkScreeningId( $str );
  }
  
  function getUserInviteCount() {
    if (($this ->postVar("user_invite_count")) || ($this ->postVar("user_invite_count") === "")) {
      return $this ->postVar("user_invite_count");
    } elseif (($this ->getVar("user_invite_count")) || ($this ->getVar("user_invite_count") === "")) {
      return $this ->getVar("user_invite_count");
    } elseif (($this ->UserInvite) || ($this ->UserInvite === "")){
      return $this ->UserInvite -> getUserInviteCount();
    } elseif (($this ->sessionVar("user_invite_count")) || ($this ->sessionVar("user_invite_count") == "")) {
      return $this ->sessionVar("user_invite_count");
    } else {
      return false;
    }
  }
  
  function setUserInviteCount( $str ) {
    $this ->UserInvite -> setUserInviteCount( $str );
  }
  
  function getUserInviteDate() {
    if (($this ->postVar("user_invite_date")) || ($this ->postVar("user_invite_date") === "")) {
      return $this ->postVar("user_invite_date");
    } elseif (($this ->getVar("user_invite_date")) || ($this ->getVar("user_invite_date") === "")) {
      return $this ->getVar("user_invite_date");
    } elseif (($this ->UserInvite) || ($this ->UserInvite === "")){
      return $this ->UserInvite -> getUserInviteDate();
    } elseif (($this ->sessionVar("user_invite_date")) || ($this ->sessionVar("user_invite_date") == "")) {
      return $this ->sessionVar("user_invite_date");
    } else {
      return false;
    }
  }
  
  function setUserInviteDate( $str ) {
    $this ->UserInvite -> setUserInviteDate( $str );
  }
  
  function getUserInviteSource() {
    if (($this ->postVar("user_invite_source")) || ($this ->postVar("user_invite_source") === "")) {
      return $this ->postVar("user_invite_source");
    } elseif (($this ->getVar("user_invite_source")) || ($this ->getVar("user_invite_source") === "")) {
      return $this ->getVar("user_invite_source");
    } elseif (($this ->UserInvite) || ($this ->UserInvite === "")){
      return $this ->UserInvite -> getUserInviteSource();
    } elseif (($this ->sessionVar("user_invite_source")) || ($this ->sessionVar("user_invite_source") == "")) {
      return $this ->sessionVar("user_invite_source");
    } else {
      return false;
    }
  }
  
  function setUserInviteSource( $str ) {
    $this ->UserInvite -> setUserInviteSource( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->UserInvite = UserInvitePeer::retrieveByPK( $id );
    }
    
    if ($this ->UserInvite ) {
       
    	       (is_numeric(WTVRcleanString($this ->UserInvite->getUserInviteId()))) ? $itemarray["user_invite_id"] = WTVRcleanString($this ->UserInvite->getUserInviteId()) : null;
          (is_numeric(WTVRcleanString($this ->UserInvite->getFkUserId()))) ? $itemarray["fk_user_id"] = WTVRcleanString($this ->UserInvite->getFkUserId()) : null;
          (WTVRcleanString($this ->UserInvite->getUserInviteType())) ? $itemarray["user_invite_type"] = WTVRcleanString($this ->UserInvite->getUserInviteType()) : null;
          (WTVRcleanString($this ->UserInvite->getUserType())) ? $itemarray["user_type"] = WTVRcleanString($this ->UserInvite->getUserType()) : null;
          (is_numeric(WTVRcleanString($this ->UserInvite->getFkFilmId()))) ? $itemarray["fk_film_id"] = WTVRcleanString($this ->UserInvite->getFkFilmId()) : null;
          (is_numeric(WTVRcleanString($this ->UserInvite->getFkScreeningId()))) ? $itemarray["fk_screening_id"] = WTVRcleanString($this ->UserInvite->getFkScreeningId()) : null;
          (is_numeric(WTVRcleanString($this ->UserInvite->getUserInviteCount()))) ? $itemarray["user_invite_count"] = WTVRcleanString($this ->UserInvite->getUserInviteCount()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->UserInvite->getUserInviteDate())) ? $itemarray["user_invite_date"] = formatDate($this ->UserInvite->getUserInviteDate('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->UserInvite->getUserInviteSource())) ? $itemarray["user_invite_source"] = WTVRcleanString($this ->UserInvite->getUserInviteSource()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->UserInvite = UserInvitePeer::retrieveByPK( $id );
    } elseif (! $this ->UserInvite) {
      $this ->UserInvite = new UserInvite;
    }
        
  	 ($this -> getUserInviteId())? $this ->UserInvite->setUserInviteId( WTVRcleanString( $this -> getUserInviteId()) ) : null;
    ($this -> getFkUserId())? $this ->UserInvite->setFkUserId( WTVRcleanString( $this -> getFkUserId()) ) : null;
    ($this -> getUserInviteType())? $this ->UserInvite->setUserInviteType( WTVRcleanString( $this -> getUserInviteType()) ) : null;
    ($this -> getUserType())? $this ->UserInvite->setUserType( WTVRcleanString( $this -> getUserType()) ) : null;
    ($this -> getFkFilmId())? $this ->UserInvite->setFkFilmId( WTVRcleanString( $this -> getFkFilmId()) ) : null;
    ($this -> getFkScreeningId())? $this ->UserInvite->setFkScreeningId( WTVRcleanString( $this -> getFkScreeningId()) ) : null;
    ($this -> getUserInviteCount())? $this ->UserInvite->setUserInviteCount( WTVRcleanString( $this -> getUserInviteCount()) ) : null;
          if (is_valid_date( $this ->UserInvite->getUserInviteDate())) {
        $this ->UserInvite->setUserInviteDate( formatDate($this -> getUserInviteDate(), "TS" ));
      } else {
      $UserInviteuser_invite_date = $this -> sfDateTime( "user_invite_date" );
      ( $UserInviteuser_invite_date != "01/01/1900 00:00:00" )? $this ->UserInvite->setUserInviteDate( formatDate($UserInviteuser_invite_date, "TS" )) : $this ->UserInvite->setUserInviteDate( null );
      }
    ($this -> getUserInviteSource())? $this ->UserInvite->setUserInviteSource( WTVRcleanString( $this -> getUserInviteSource()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->UserInvite ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->UserInvite = UserInvitePeer::retrieveByPK($id);
    }
    
    if (! $this ->UserInvite ) {
      return;
    }
    
    $this ->UserInvite -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('UserInvite_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "UserInvitePeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $UserInvite = UserInvitePeer::doSelect($c);
    
    if (count($UserInvite) >= 1) {
      $this ->UserInvite = $UserInvite[0];
      return true;
    } else {
      $this ->UserInvite = new UserInvite();
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
      $name = "UserInvitePeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $UserInvite = UserInvitePeer::doSelect($c);
    
    if (count($UserInvite) >= 1) {
      $this ->UserInvite = $UserInvite[0];
      return true;
    } else {
      $this ->UserInvite = new UserInvite();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>