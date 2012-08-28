<?php
       
   class ScreeningReminderCrudBase extends Utils_PageWidget { 
   
    var $ScreeningReminder;
   
       var $screening_reminder_id;
   var $fk_screening_unique_key;
   var $fk_screening_id;
   var $fk_user_id;
   var $fk_user_email;
   var $date_added;
   var $screening_reminder_sent;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getScreeningReminderId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->ScreeningReminder = ScreeningReminderPeer::retrieveByPK( $id );
    } else {
      $this ->ScreeningReminder = new ScreeningReminder;
    }
  }
  
  function hydrate( $id ) {
      $this ->ScreeningReminder = ScreeningReminderPeer::retrieveByPK( $id );
  }
  
  function getScreeningReminderId() {
    if (($this ->postVar("screening_reminder_id")) || ($this ->postVar("screening_reminder_id") === "")) {
      return $this ->postVar("screening_reminder_id");
    } elseif (($this ->getVar("screening_reminder_id")) || ($this ->getVar("screening_reminder_id") === "")) {
      return $this ->getVar("screening_reminder_id");
    } elseif (($this ->ScreeningReminder) || ($this ->ScreeningReminder === "")){
      return $this ->ScreeningReminder -> getScreeningReminderId();
    } elseif (($this ->sessionVar("screening_reminder_id")) || ($this ->sessionVar("screening_reminder_id") == "")) {
      return $this ->sessionVar("screening_reminder_id");
    } else {
      return false;
    }
  }
  
  function setScreeningReminderId( $str ) {
    $this ->ScreeningReminder -> setScreeningReminderId( $str );
  }
  
  function getFkScreeningUniqueKey() {
    if (($this ->postVar("fk_screening_unique_key")) || ($this ->postVar("fk_screening_unique_key") === "")) {
      return $this ->postVar("fk_screening_unique_key");
    } elseif (($this ->getVar("fk_screening_unique_key")) || ($this ->getVar("fk_screening_unique_key") === "")) {
      return $this ->getVar("fk_screening_unique_key");
    } elseif (($this ->ScreeningReminder) || ($this ->ScreeningReminder === "")){
      return $this ->ScreeningReminder -> getFkScreeningUniqueKey();
    } elseif (($this ->sessionVar("fk_screening_unique_key")) || ($this ->sessionVar("fk_screening_unique_key") == "")) {
      return $this ->sessionVar("fk_screening_unique_key");
    } else {
      return false;
    }
  }
  
  function setFkScreeningUniqueKey( $str ) {
    $this ->ScreeningReminder -> setFkScreeningUniqueKey( $str );
  }
  
  function getFkScreeningId() {
    if (($this ->postVar("fk_screening_id")) || ($this ->postVar("fk_screening_id") === "")) {
      return $this ->postVar("fk_screening_id");
    } elseif (($this ->getVar("fk_screening_id")) || ($this ->getVar("fk_screening_id") === "")) {
      return $this ->getVar("fk_screening_id");
    } elseif (($this ->ScreeningReminder) || ($this ->ScreeningReminder === "")){
      return $this ->ScreeningReminder -> getFkScreeningId();
    } elseif (($this ->sessionVar("fk_screening_id")) || ($this ->sessionVar("fk_screening_id") == "")) {
      return $this ->sessionVar("fk_screening_id");
    } else {
      return false;
    }
  }
  
  function setFkScreeningId( $str ) {
    $this ->ScreeningReminder -> setFkScreeningId( $str );
  }
  
  function getFkUserId() {
    if (($this ->postVar("fk_user_id")) || ($this ->postVar("fk_user_id") === "")) {
      return $this ->postVar("fk_user_id");
    } elseif (($this ->getVar("fk_user_id")) || ($this ->getVar("fk_user_id") === "")) {
      return $this ->getVar("fk_user_id");
    } elseif (($this ->ScreeningReminder) || ($this ->ScreeningReminder === "")){
      return $this ->ScreeningReminder -> getFkUserId();
    } elseif (($this ->sessionVar("fk_user_id")) || ($this ->sessionVar("fk_user_id") == "")) {
      return $this ->sessionVar("fk_user_id");
    } else {
      return false;
    }
  }
  
  function setFkUserId( $str ) {
    $this ->ScreeningReminder -> setFkUserId( $str );
  }
  
  function getFkUserEmail() {
    if (($this ->postVar("fk_user_email")) || ($this ->postVar("fk_user_email") === "")) {
      return $this ->postVar("fk_user_email");
    } elseif (($this ->getVar("fk_user_email")) || ($this ->getVar("fk_user_email") === "")) {
      return $this ->getVar("fk_user_email");
    } elseif (($this ->ScreeningReminder) || ($this ->ScreeningReminder === "")){
      return $this ->ScreeningReminder -> getFkUserEmail();
    } elseif (($this ->sessionVar("fk_user_email")) || ($this ->sessionVar("fk_user_email") == "")) {
      return $this ->sessionVar("fk_user_email");
    } else {
      return false;
    }
  }
  
  function setFkUserEmail( $str ) {
    $this ->ScreeningReminder -> setFkUserEmail( $str );
  }
  
  function getDateAdded() {
    if (($this ->postVar("date_added")) || ($this ->postVar("date_added") === "")) {
      return $this ->postVar("date_added");
    } elseif (($this ->getVar("date_added")) || ($this ->getVar("date_added") === "")) {
      return $this ->getVar("date_added");
    } elseif (($this ->ScreeningReminder) || ($this ->ScreeningReminder === "")){
      return $this ->ScreeningReminder -> getDateAdded();
    } elseif (($this ->sessionVar("date_added")) || ($this ->sessionVar("date_added") == "")) {
      return $this ->sessionVar("date_added");
    } else {
      return false;
    }
  }
  
  function setDateAdded( $str ) {
    $this ->ScreeningReminder -> setDateAdded( $str );
  }
  
  function getScreeningReminderSent() {
    if (($this ->postVar("screening_reminder_sent")) || ($this ->postVar("screening_reminder_sent") === "")) {
      return $this ->postVar("screening_reminder_sent");
    } elseif (($this ->getVar("screening_reminder_sent")) || ($this ->getVar("screening_reminder_sent") === "")) {
      return $this ->getVar("screening_reminder_sent");
    } elseif (($this ->ScreeningReminder) || ($this ->ScreeningReminder === "")){
      return $this ->ScreeningReminder -> getScreeningReminderSent();
    } elseif (($this ->sessionVar("screening_reminder_sent")) || ($this ->sessionVar("screening_reminder_sent") == "")) {
      return $this ->sessionVar("screening_reminder_sent");
    } else {
      return false;
    }
  }
  
  function setScreeningReminderSent( $str ) {
    $this ->ScreeningReminder -> setScreeningReminderSent( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->ScreeningReminder = ScreeningReminderPeer::retrieveByPK( $id );
    }
    
    if ($this ->ScreeningReminder ) {
       
    	       (is_numeric(WTVRcleanString($this ->ScreeningReminder->getScreeningReminderId()))) ? $itemarray["screening_reminder_id"] = WTVRcleanString($this ->ScreeningReminder->getScreeningReminderId()) : null;
          (WTVRcleanString($this ->ScreeningReminder->getFkScreeningUniqueKey())) ? $itemarray["fk_screening_unique_key"] = WTVRcleanString($this ->ScreeningReminder->getFkScreeningUniqueKey()) : null;
          (is_numeric(WTVRcleanString($this ->ScreeningReminder->getFkScreeningId()))) ? $itemarray["fk_screening_id"] = WTVRcleanString($this ->ScreeningReminder->getFkScreeningId()) : null;
          (is_numeric(WTVRcleanString($this ->ScreeningReminder->getFkUserId()))) ? $itemarray["fk_user_id"] = WTVRcleanString($this ->ScreeningReminder->getFkUserId()) : null;
          (WTVRcleanString($this ->ScreeningReminder->getFkUserEmail())) ? $itemarray["fk_user_email"] = WTVRcleanString($this ->ScreeningReminder->getFkUserEmail()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->ScreeningReminder->getDateAdded())) ? $itemarray["date_added"] = formatDate($this ->ScreeningReminder->getDateAdded('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->ScreeningReminder->getScreeningReminderSent())) ? $itemarray["screening_reminder_sent"] = WTVRcleanString($this ->ScreeningReminder->getScreeningReminderSent()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->ScreeningReminder = ScreeningReminderPeer::retrieveByPK( $id );
    } elseif (! $this ->ScreeningReminder) {
      $this ->ScreeningReminder = new ScreeningReminder;
    }
        
  	 ($this -> getScreeningReminderId())? $this ->ScreeningReminder->setScreeningReminderId( WTVRcleanString( $this -> getScreeningReminderId()) ) : null;
    ($this -> getFkScreeningUniqueKey())? $this ->ScreeningReminder->setFkScreeningUniqueKey( WTVRcleanString( $this -> getFkScreeningUniqueKey()) ) : null;
    ($this -> getFkScreeningId())? $this ->ScreeningReminder->setFkScreeningId( WTVRcleanString( $this -> getFkScreeningId()) ) : null;
    ($this -> getFkUserId())? $this ->ScreeningReminder->setFkUserId( WTVRcleanString( $this -> getFkUserId()) ) : null;
    ($this -> getFkUserEmail())? $this ->ScreeningReminder->setFkUserEmail( WTVRcleanString( $this -> getFkUserEmail()) ) : null;
          if (is_valid_date( $this ->ScreeningReminder->getDateAdded())) {
        $this ->ScreeningReminder->setDateAdded( formatDate($this -> getDateAdded(), "TS" ));
      } else {
      $ScreeningReminderdate_added = $this -> sfDateTime( "date_added" );
      ( $ScreeningReminderdate_added != "01/01/1900 00:00:00" )? $this ->ScreeningReminder->setDateAdded( formatDate($ScreeningReminderdate_added, "TS" )) : $this ->ScreeningReminder->setDateAdded( null );
      }
    ($this -> getScreeningReminderSent())? $this ->ScreeningReminder->setScreeningReminderSent( WTVRcleanString( $this -> getScreeningReminderSent()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->ScreeningReminder ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->ScreeningReminder = ScreeningReminderPeer::retrieveByPK($id);
    }
    
    if (! $this ->ScreeningReminder ) {
      return;
    }
    
    $this ->ScreeningReminder -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('ScreeningReminder_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "ScreeningReminderPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $ScreeningReminder = ScreeningReminderPeer::doSelect($c);
    
    if (count($ScreeningReminder) >= 1) {
      $this ->ScreeningReminder = $ScreeningReminder[0];
      return true;
    } else {
      $this ->ScreeningReminder = new ScreeningReminder();
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
      $name = "ScreeningReminderPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $ScreeningReminder = ScreeningReminderPeer::doSelect($c);
    
    if (count($ScreeningReminder) >= 1) {
      $this ->ScreeningReminder = $ScreeningReminder[0];
      return true;
    } else {
      $this ->ScreeningReminder = new ScreeningReminder();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>