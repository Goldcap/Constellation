<?php
       
   class ActivityLogCrudBase extends Utils_PageWidget { 
   
    var $ActivityLog;
   
       var $activity_log_id;
   var $fk_screening_id;
   var $activity_log_message;
   var $activity_log_created_at;
   var $activity_log_updated_at;
   var $activity_log_timestamp;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getActivityLogId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->ActivityLog = ActivityLogPeer::retrieveByPK( $id );
    } else {
      $this ->ActivityLog = new ActivityLog;
    }
  }
  
  function hydrate( $id ) {
      $this ->ActivityLog = ActivityLogPeer::retrieveByPK( $id );
  }
  
  function getActivityLogId() {
    if (($this ->postVar("activity_log_id")) || ($this ->postVar("activity_log_id") === "")) {
      return $this ->postVar("activity_log_id");
    } elseif (($this ->getVar("activity_log_id")) || ($this ->getVar("activity_log_id") === "")) {
      return $this ->getVar("activity_log_id");
    } elseif (($this ->ActivityLog) || ($this ->ActivityLog === "")){
      return $this ->ActivityLog -> getActivityLogId();
    } elseif (($this ->sessionVar("activity_log_id")) || ($this ->sessionVar("activity_log_id") == "")) {
      return $this ->sessionVar("activity_log_id");
    } else {
      return false;
    }
  }
  
  function setActivityLogId( $str ) {
    $this ->ActivityLog -> setActivityLogId( $str );
  }
  
  function getFkScreeningId() {
    if (($this ->postVar("fk_screening_id")) || ($this ->postVar("fk_screening_id") === "")) {
      return $this ->postVar("fk_screening_id");
    } elseif (($this ->getVar("fk_screening_id")) || ($this ->getVar("fk_screening_id") === "")) {
      return $this ->getVar("fk_screening_id");
    } elseif (($this ->ActivityLog) || ($this ->ActivityLog === "")){
      return $this ->ActivityLog -> getFkScreeningId();
    } elseif (($this ->sessionVar("fk_screening_id")) || ($this ->sessionVar("fk_screening_id") == "")) {
      return $this ->sessionVar("fk_screening_id");
    } else {
      return false;
    }
  }
  
  function setFkScreeningId( $str ) {
    $this ->ActivityLog -> setFkScreeningId( $str );
  }
  
  function getActivityLogMessage() {
    if (($this ->postVar("activity_log_message")) || ($this ->postVar("activity_log_message") === "")) {
      return $this ->postVar("activity_log_message");
    } elseif (($this ->getVar("activity_log_message")) || ($this ->getVar("activity_log_message") === "")) {
      return $this ->getVar("activity_log_message");
    } elseif (($this ->ActivityLog) || ($this ->ActivityLog === "")){
      return $this ->ActivityLog -> getActivityLogMessage();
    } elseif (($this ->sessionVar("activity_log_message")) || ($this ->sessionVar("activity_log_message") == "")) {
      return $this ->sessionVar("activity_log_message");
    } else {
      return false;
    }
  }
  
  function setActivityLogMessage( $str ) {
    $this ->ActivityLog -> setActivityLogMessage( $str );
  }
  
  function getActivityLogCreatedAt() {
    if (($this ->postVar("activity_log_created_at")) || ($this ->postVar("activity_log_created_at") === "")) {
      return $this ->postVar("activity_log_created_at");
    } elseif (($this ->getVar("activity_log_created_at")) || ($this ->getVar("activity_log_created_at") === "")) {
      return $this ->getVar("activity_log_created_at");
    } elseif (($this ->ActivityLog) || ($this ->ActivityLog === "")){
      return $this ->ActivityLog -> getActivityLogCreatedAt();
    } elseif (($this ->sessionVar("activity_log_created_at")) || ($this ->sessionVar("activity_log_created_at") == "")) {
      return $this ->sessionVar("activity_log_created_at");
    } else {
      return false;
    }
  }
  
  function setActivityLogCreatedAt( $str ) {
    $this ->ActivityLog -> setActivityLogCreatedAt( $str );
  }
  
  function getActivityLogUpdatedAt() {
    if (($this ->postVar("activity_log_updated_at")) || ($this ->postVar("activity_log_updated_at") === "")) {
      return $this ->postVar("activity_log_updated_at");
    } elseif (($this ->getVar("activity_log_updated_at")) || ($this ->getVar("activity_log_updated_at") === "")) {
      return $this ->getVar("activity_log_updated_at");
    } elseif (($this ->ActivityLog) || ($this ->ActivityLog === "")){
      return $this ->ActivityLog -> getActivityLogUpdatedAt();
    } elseif (($this ->sessionVar("activity_log_updated_at")) || ($this ->sessionVar("activity_log_updated_at") == "")) {
      return $this ->sessionVar("activity_log_updated_at");
    } else {
      return false;
    }
  }
  
  function setActivityLogUpdatedAt( $str ) {
    $this ->ActivityLog -> setActivityLogUpdatedAt( $str );
  }
  
  function getActivityLogTimestamp() {
    if (($this ->postVar("activity_log_timestamp")) || ($this ->postVar("activity_log_timestamp") === "")) {
      return $this ->postVar("activity_log_timestamp");
    } elseif (($this ->getVar("activity_log_timestamp")) || ($this ->getVar("activity_log_timestamp") === "")) {
      return $this ->getVar("activity_log_timestamp");
    } elseif (($this ->ActivityLog) || ($this ->ActivityLog === "")){
      return $this ->ActivityLog -> getActivityLogTimestamp();
    } elseif (($this ->sessionVar("activity_log_timestamp")) || ($this ->sessionVar("activity_log_timestamp") == "")) {
      return $this ->sessionVar("activity_log_timestamp");
    } else {
      return false;
    }
  }
  
  function setActivityLogTimestamp( $str ) {
    $this ->ActivityLog -> setActivityLogTimestamp( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->ActivityLog = ActivityLogPeer::retrieveByPK( $id );
    }
    
    if ($this ->ActivityLog ) {
       
    	       (is_numeric(WTVRcleanString($this ->ActivityLog->getActivityLogId()))) ? $itemarray["activity_log_id"] = WTVRcleanString($this ->ActivityLog->getActivityLogId()) : null;
          (is_numeric(WTVRcleanString($this ->ActivityLog->getFkScreeningId()))) ? $itemarray["fk_screening_id"] = WTVRcleanString($this ->ActivityLog->getFkScreeningId()) : null;
          (WTVRcleanString($this ->ActivityLog->getActivityLogMessage())) ? $itemarray["activity_log_message"] = WTVRcleanString($this ->ActivityLog->getActivityLogMessage()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->ActivityLog->getActivityLogCreatedAt())) ? $itemarray["activity_log_created_at"] = formatDate($this ->ActivityLog->getActivityLogCreatedAt('%Y-%m-%d %T'),"TS") : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->ActivityLog->getActivityLogUpdatedAt())) ? $itemarray["activity_log_updated_at"] = formatDate($this ->ActivityLog->getActivityLogUpdatedAt('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->ActivityLog->getActivityLogTimestamp())) ? $itemarray["activity_log_timestamp"] = WTVRcleanString($this ->ActivityLog->getActivityLogTimestamp()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->ActivityLog = ActivityLogPeer::retrieveByPK( $id );
    } elseif (! $this ->ActivityLog) {
      $this ->ActivityLog = new ActivityLog;
    }
        
  	 ($this -> getActivityLogId())? $this ->ActivityLog->setActivityLogId( WTVRcleanString( $this -> getActivityLogId()) ) : null;
    ($this -> getFkScreeningId())? $this ->ActivityLog->setFkScreeningId( WTVRcleanString( $this -> getFkScreeningId()) ) : null;
    ($this -> getActivityLogMessage())? $this ->ActivityLog->setActivityLogMessage( WTVRcleanString( $this -> getActivityLogMessage()) ) : null;
          if (is_valid_date( $this ->ActivityLog->getActivityLogCreatedAt())) {
        $this ->ActivityLog->setActivityLogCreatedAt( formatDate($this -> getActivityLogCreatedAt(), "TS" ));
      } else {
      $ActivityLogactivity_log_created_at = $this -> sfDateTime( "activity_log_created_at" );
      ( $ActivityLogactivity_log_created_at != "01/01/1900 00:00:00" )? $this ->ActivityLog->setActivityLogCreatedAt( formatDate($ActivityLogactivity_log_created_at, "TS" )) : $this ->ActivityLog->setActivityLogCreatedAt( null );
      }
          if (is_valid_date( $this ->ActivityLog->getActivityLogUpdatedAt())) {
        $this ->ActivityLog->setActivityLogUpdatedAt( formatDate($this -> getActivityLogUpdatedAt(), "TS" ));
      } else {
      $ActivityLogactivity_log_updated_at = $this -> sfDateTime( "activity_log_updated_at" );
      ( $ActivityLogactivity_log_updated_at != "01/01/1900 00:00:00" )? $this ->ActivityLog->setActivityLogUpdatedAt( formatDate($ActivityLogactivity_log_updated_at, "TS" )) : $this ->ActivityLog->setActivityLogUpdatedAt( null );
      }
    ($this -> getActivityLogTimestamp())? $this ->ActivityLog->setActivityLogTimestamp( WTVRcleanString( $this -> getActivityLogTimestamp()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->ActivityLog ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->ActivityLog = ActivityLogPeer::retrieveByPK($id);
    }
    
    if (! $this ->ActivityLog ) {
      return;
    }
    
    $this ->ActivityLog -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('ActivityLog_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "ActivityLogPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $ActivityLog = ActivityLogPeer::doSelect($c);
    
    if (count($ActivityLog) >= 1) {
      $this ->ActivityLog = $ActivityLog[0];
      return true;
    } else {
      $this ->ActivityLog = new ActivityLog();
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
      $name = "ActivityLogPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $ActivityLog = ActivityLogPeer::doSelect($c);
    
    if (count($ActivityLog) >= 1) {
      $this ->ActivityLog = $ActivityLog[0];
      return true;
    } else {
      $this ->ActivityLog = new ActivityLog();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>