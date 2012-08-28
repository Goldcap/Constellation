<?php
       
   class SponsorCodeUsageCrudBase extends Utils_PageWidget { 
   
    var $SponsorCodeUsage;
   
       var $sponsor_code_usage_id;
   var $fk_sponsor_code_id;
   var $fk_user_id;
   var $sponsor_code_usage_date;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getSponsorCodeUsageId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->SponsorCodeUsage = SponsorCodeUsagePeer::retrieveByPK( $id );
    } else {
      $this ->SponsorCodeUsage = new SponsorCodeUsage;
    }
  }
  
  function hydrate( $id ) {
      $this ->SponsorCodeUsage = SponsorCodeUsagePeer::retrieveByPK( $id );
  }
  
  function getSponsorCodeUsageId() {
    if (($this ->postVar("sponsor_code_usage_id")) || ($this ->postVar("sponsor_code_usage_id") === "")) {
      return $this ->postVar("sponsor_code_usage_id");
    } elseif (($this ->getVar("sponsor_code_usage_id")) || ($this ->getVar("sponsor_code_usage_id") === "")) {
      return $this ->getVar("sponsor_code_usage_id");
    } elseif (($this ->SponsorCodeUsage) || ($this ->SponsorCodeUsage === "")){
      return $this ->SponsorCodeUsage -> getSponsorCodeUsageId();
    } elseif (($this ->sessionVar("sponsor_code_usage_id")) || ($this ->sessionVar("sponsor_code_usage_id") == "")) {
      return $this ->sessionVar("sponsor_code_usage_id");
    } else {
      return false;
    }
  }
  
  function setSponsorCodeUsageId( $str ) {
    $this ->SponsorCodeUsage -> setSponsorCodeUsageId( $str );
  }
  
  function getFkSponsorCodeId() {
    if (($this ->postVar("fk_sponsor_code_id")) || ($this ->postVar("fk_sponsor_code_id") === "")) {
      return $this ->postVar("fk_sponsor_code_id");
    } elseif (($this ->getVar("fk_sponsor_code_id")) || ($this ->getVar("fk_sponsor_code_id") === "")) {
      return $this ->getVar("fk_sponsor_code_id");
    } elseif (($this ->SponsorCodeUsage) || ($this ->SponsorCodeUsage === "")){
      return $this ->SponsorCodeUsage -> getFkSponsorCodeId();
    } elseif (($this ->sessionVar("fk_sponsor_code_id")) || ($this ->sessionVar("fk_sponsor_code_id") == "")) {
      return $this ->sessionVar("fk_sponsor_code_id");
    } else {
      return false;
    }
  }
  
  function setFkSponsorCodeId( $str ) {
    $this ->SponsorCodeUsage -> setFkSponsorCodeId( $str );
  }
  
  function getFkUserId() {
    if (($this ->postVar("fk_user_id")) || ($this ->postVar("fk_user_id") === "")) {
      return $this ->postVar("fk_user_id");
    } elseif (($this ->getVar("fk_user_id")) || ($this ->getVar("fk_user_id") === "")) {
      return $this ->getVar("fk_user_id");
    } elseif (($this ->SponsorCodeUsage) || ($this ->SponsorCodeUsage === "")){
      return $this ->SponsorCodeUsage -> getFkUserId();
    } elseif (($this ->sessionVar("fk_user_id")) || ($this ->sessionVar("fk_user_id") == "")) {
      return $this ->sessionVar("fk_user_id");
    } else {
      return false;
    }
  }
  
  function setFkUserId( $str ) {
    $this ->SponsorCodeUsage -> setFkUserId( $str );
  }
  
  function getSponsorCodeUsageDate() {
    if (($this ->postVar("sponsor_code_usage_date")) || ($this ->postVar("sponsor_code_usage_date") === "")) {
      return $this ->postVar("sponsor_code_usage_date");
    } elseif (($this ->getVar("sponsor_code_usage_date")) || ($this ->getVar("sponsor_code_usage_date") === "")) {
      return $this ->getVar("sponsor_code_usage_date");
    } elseif (($this ->SponsorCodeUsage) || ($this ->SponsorCodeUsage === "")){
      return $this ->SponsorCodeUsage -> getSponsorCodeUsageDate();
    } elseif (($this ->sessionVar("sponsor_code_usage_date")) || ($this ->sessionVar("sponsor_code_usage_date") == "")) {
      return $this ->sessionVar("sponsor_code_usage_date");
    } else {
      return false;
    }
  }
  
  function setSponsorCodeUsageDate( $str ) {
    $this ->SponsorCodeUsage -> setSponsorCodeUsageDate( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->SponsorCodeUsage = SponsorCodeUsagePeer::retrieveByPK( $id );
    }
    
    if ($this ->SponsorCodeUsage ) {
       
    	       (is_numeric(WTVRcleanString($this ->SponsorCodeUsage->getSponsorCodeUsageId()))) ? $itemarray["sponsor_code_usage_id"] = WTVRcleanString($this ->SponsorCodeUsage->getSponsorCodeUsageId()) : null;
          (is_numeric(WTVRcleanString($this ->SponsorCodeUsage->getFkSponsorCodeId()))) ? $itemarray["fk_sponsor_code_id"] = WTVRcleanString($this ->SponsorCodeUsage->getFkSponsorCodeId()) : null;
          (is_numeric(WTVRcleanString($this ->SponsorCodeUsage->getFkUserId()))) ? $itemarray["fk_user_id"] = WTVRcleanString($this ->SponsorCodeUsage->getFkUserId()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->SponsorCodeUsage->getSponsorCodeUsageDate())) ? $itemarray["sponsor_code_usage_date"] = formatDate($this ->SponsorCodeUsage->getSponsorCodeUsageDate('%Y-%m-%d %T'),"TS") : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->SponsorCodeUsage = SponsorCodeUsagePeer::retrieveByPK( $id );
    } elseif (! $this ->SponsorCodeUsage) {
      $this ->SponsorCodeUsage = new SponsorCodeUsage;
    }
        
  	 ($this -> getSponsorCodeUsageId())? $this ->SponsorCodeUsage->setSponsorCodeUsageId( WTVRcleanString( $this -> getSponsorCodeUsageId()) ) : null;
    ($this -> getFkSponsorCodeId())? $this ->SponsorCodeUsage->setFkSponsorCodeId( WTVRcleanString( $this -> getFkSponsorCodeId()) ) : null;
    ($this -> getFkUserId())? $this ->SponsorCodeUsage->setFkUserId( WTVRcleanString( $this -> getFkUserId()) ) : null;
          if (is_valid_date( $this ->SponsorCodeUsage->getSponsorCodeUsageDate())) {
        $this ->SponsorCodeUsage->setSponsorCodeUsageDate( formatDate($this -> getSponsorCodeUsageDate(), "TS" ));
      } else {
      $SponsorCodeUsagesponsor_code_usage_date = $this -> sfDateTime( "sponsor_code_usage_date" );
      ( $SponsorCodeUsagesponsor_code_usage_date != "01/01/1900 00:00:00" )? $this ->SponsorCodeUsage->setSponsorCodeUsageDate( formatDate($SponsorCodeUsagesponsor_code_usage_date, "TS" )) : $this ->SponsorCodeUsage->setSponsorCodeUsageDate( null );
      }
          
    $this -> save();
     
  }
  
  function save() {
    $this ->SponsorCodeUsage ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->SponsorCodeUsage = SponsorCodeUsagePeer::retrieveByPK($id);
    }
    
    if (! $this ->SponsorCodeUsage ) {
      return;
    }
    
    $this ->SponsorCodeUsage -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('SponsorCodeUsage_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "SponsorCodeUsagePeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $SponsorCodeUsage = SponsorCodeUsagePeer::doSelect($c);
    
    if (count($SponsorCodeUsage) >= 1) {
      $this ->SponsorCodeUsage = $SponsorCodeUsage[0];
      return true;
    } else {
      $this ->SponsorCodeUsage = new SponsorCodeUsage();
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
      $name = "SponsorCodeUsagePeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $SponsorCodeUsage = SponsorCodeUsagePeer::doSelect($c);
    
    if (count($SponsorCodeUsage) >= 1) {
      $this ->SponsorCodeUsage = $SponsorCodeUsage[0];
      return true;
    } else {
      $this ->SponsorCodeUsage = new SponsorCodeUsage();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>