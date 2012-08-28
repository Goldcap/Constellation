<?php
       
   class ClickCrudBase extends Utils_PageWidget { 
   
    var $Click;
   
       var $click_id;
   var $click_referer;
   var $click_ip;
   var $click_date;
   var $click_guid;
   var $fk_click_track_id;
   var $click_script;
   var $click_querystring;
   var $click_incoming;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getClickId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Click = ClickPeer::retrieveByPK( $id );
    } else {
      $this ->Click = new Click;
    }
  }
  
  function hydrate( $id ) {
      $this ->Click = ClickPeer::retrieveByPK( $id );
  }
  
  function getClickId() {
    if (($this ->postVar("click_id")) || ($this ->postVar("click_id") === "")) {
      return $this ->postVar("click_id");
    } elseif (($this ->getVar("click_id")) || ($this ->getVar("click_id") === "")) {
      return $this ->getVar("click_id");
    } elseif (($this ->Click) || ($this ->Click === "")){
      return $this ->Click -> getClickId();
    } elseif (($this ->sessionVar("click_id")) || ($this ->sessionVar("click_id") == "")) {
      return $this ->sessionVar("click_id");
    } else {
      return false;
    }
  }
  
  function setClickId( $str ) {
    $this ->Click -> setClickId( $str );
  }
  
  function getClickReferer() {
    if (($this ->postVar("click_referer")) || ($this ->postVar("click_referer") === "")) {
      return $this ->postVar("click_referer");
    } elseif (($this ->getVar("click_referer")) || ($this ->getVar("click_referer") === "")) {
      return $this ->getVar("click_referer");
    } elseif (($this ->Click) || ($this ->Click === "")){
      return $this ->Click -> getClickReferer();
    } elseif (($this ->sessionVar("click_referer")) || ($this ->sessionVar("click_referer") == "")) {
      return $this ->sessionVar("click_referer");
    } else {
      return false;
    }
  }
  
  function setClickReferer( $str ) {
    $this ->Click -> setClickReferer( $str );
  }
  
  function getClickIp() {
    if (($this ->postVar("click_ip")) || ($this ->postVar("click_ip") === "")) {
      return $this ->postVar("click_ip");
    } elseif (($this ->getVar("click_ip")) || ($this ->getVar("click_ip") === "")) {
      return $this ->getVar("click_ip");
    } elseif (($this ->Click) || ($this ->Click === "")){
      return $this ->Click -> getClickIp();
    } elseif (($this ->sessionVar("click_ip")) || ($this ->sessionVar("click_ip") == "")) {
      return $this ->sessionVar("click_ip");
    } else {
      return false;
    }
  }
  
  function setClickIp( $str ) {
    $this ->Click -> setClickIp( $str );
  }
  
  function getClickDate() {
    if (($this ->postVar("click_date")) || ($this ->postVar("click_date") === "")) {
      return $this ->postVar("click_date");
    } elseif (($this ->getVar("click_date")) || ($this ->getVar("click_date") === "")) {
      return $this ->getVar("click_date");
    } elseif (($this ->Click) || ($this ->Click === "")){
      return $this ->Click -> getClickDate();
    } elseif (($this ->sessionVar("click_date")) || ($this ->sessionVar("click_date") == "")) {
      return $this ->sessionVar("click_date");
    } else {
      return false;
    }
  }
  
  function setClickDate( $str ) {
    $this ->Click -> setClickDate( $str );
  }
  
  function getClickGuid() {
    if (($this ->postVar("click_guid")) || ($this ->postVar("click_guid") === "")) {
      return $this ->postVar("click_guid");
    } elseif (($this ->getVar("click_guid")) || ($this ->getVar("click_guid") === "")) {
      return $this ->getVar("click_guid");
    } elseif (($this ->Click) || ($this ->Click === "")){
      return $this ->Click -> getClickGuid();
    } elseif (($this ->sessionVar("click_guid")) || ($this ->sessionVar("click_guid") == "")) {
      return $this ->sessionVar("click_guid");
    } else {
      return false;
    }
  }
  
  function setClickGuid( $str ) {
    $this ->Click -> setClickGuid( $str );
  }
  
  function getFkClickTrackId() {
    if (($this ->postVar("fk_click_track_id")) || ($this ->postVar("fk_click_track_id") === "")) {
      return $this ->postVar("fk_click_track_id");
    } elseif (($this ->getVar("fk_click_track_id")) || ($this ->getVar("fk_click_track_id") === "")) {
      return $this ->getVar("fk_click_track_id");
    } elseif (($this ->Click) || ($this ->Click === "")){
      return $this ->Click -> getFkClickTrackId();
    } elseif (($this ->sessionVar("fk_click_track_id")) || ($this ->sessionVar("fk_click_track_id") == "")) {
      return $this ->sessionVar("fk_click_track_id");
    } else {
      return false;
    }
  }
  
  function setFkClickTrackId( $str ) {
    $this ->Click -> setFkClickTrackId( $str );
  }
  
  function getClickScript() {
    if (($this ->postVar("click_script")) || ($this ->postVar("click_script") === "")) {
      return $this ->postVar("click_script");
    } elseif (($this ->getVar("click_script")) || ($this ->getVar("click_script") === "")) {
      return $this ->getVar("click_script");
    } elseif (($this ->Click) || ($this ->Click === "")){
      return $this ->Click -> getClickScript();
    } elseif (($this ->sessionVar("click_script")) || ($this ->sessionVar("click_script") == "")) {
      return $this ->sessionVar("click_script");
    } else {
      return false;
    }
  }
  
  function setClickScript( $str ) {
    $this ->Click -> setClickScript( $str );
  }
  
  function getClickQuerystring() {
    if (($this ->postVar("click_querystring")) || ($this ->postVar("click_querystring") === "")) {
      return $this ->postVar("click_querystring");
    } elseif (($this ->getVar("click_querystring")) || ($this ->getVar("click_querystring") === "")) {
      return $this ->getVar("click_querystring");
    } elseif (($this ->Click) || ($this ->Click === "")){
      return $this ->Click -> getClickQuerystring();
    } elseif (($this ->sessionVar("click_querystring")) || ($this ->sessionVar("click_querystring") == "")) {
      return $this ->sessionVar("click_querystring");
    } else {
      return false;
    }
  }
  
  function setClickQuerystring( $str ) {
    $this ->Click -> setClickQuerystring( $str );
  }
  
  function getClickIncoming() {
    if (($this ->postVar("click_incoming")) || ($this ->postVar("click_incoming") === "")) {
      return $this ->postVar("click_incoming");
    } elseif (($this ->getVar("click_incoming")) || ($this ->getVar("click_incoming") === "")) {
      return $this ->getVar("click_incoming");
    } elseif (($this ->Click) || ($this ->Click === "")){
      return $this ->Click -> getClickIncoming();
    } elseif (($this ->sessionVar("click_incoming")) || ($this ->sessionVar("click_incoming") == "")) {
      return $this ->sessionVar("click_incoming");
    } else {
      return false;
    }
  }
  
  function setClickIncoming( $str ) {
    $this ->Click -> setClickIncoming( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Click = ClickPeer::retrieveByPK( $id );
    }
    
    if ($this ->Click ) {
       
    	       (is_numeric(WTVRcleanString($this ->Click->getClickId()))) ? $itemarray["click_id"] = WTVRcleanString($this ->Click->getClickId()) : null;
          (WTVRcleanString($this ->Click->getClickReferer())) ? $itemarray["click_referer"] = WTVRcleanString($this ->Click->getClickReferer()) : null;
          (WTVRcleanString($this ->Click->getClickIp())) ? $itemarray["click_ip"] = WTVRcleanString($this ->Click->getClickIp()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Click->getClickDate())) ? $itemarray["click_date"] = formatDate($this ->Click->getClickDate('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->Click->getClickGuid())) ? $itemarray["click_guid"] = WTVRcleanString($this ->Click->getClickGuid()) : null;
          (is_numeric(WTVRcleanString($this ->Click->getFkClickTrackId()))) ? $itemarray["fk_click_track_id"] = WTVRcleanString($this ->Click->getFkClickTrackId()) : null;
          (WTVRcleanString($this ->Click->getClickScript())) ? $itemarray["click_script"] = WTVRcleanString($this ->Click->getClickScript()) : null;
          (WTVRcleanString($this ->Click->getClickQuerystring())) ? $itemarray["click_querystring"] = WTVRcleanString($this ->Click->getClickQuerystring()) : null;
          (WTVRcleanString($this ->Click->getClickIncoming())) ? $itemarray["click_incoming"] = WTVRcleanString($this ->Click->getClickIncoming()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Click = ClickPeer::retrieveByPK( $id );
    } elseif (! $this ->Click) {
      $this ->Click = new Click;
    }
        
  	 ($this -> getClickId())? $this ->Click->setClickId( WTVRcleanString( $this -> getClickId()) ) : null;
    ($this -> getClickReferer())? $this ->Click->setClickReferer( WTVRcleanString( $this -> getClickReferer()) ) : null;
    ($this -> getClickIp())? $this ->Click->setClickIp( WTVRcleanString( $this -> getClickIp()) ) : null;
          if (is_valid_date( $this ->Click->getClickDate())) {
        $this ->Click->setClickDate( formatDate($this -> getClickDate(), "TS" ));
      } else {
      $Clickclick_date = $this -> sfDateTime( "click_date" );
      ( $Clickclick_date != "01/01/1900 00:00:00" )? $this ->Click->setClickDate( formatDate($Clickclick_date, "TS" )) : $this ->Click->setClickDate( null );
      }
    ($this -> getClickGuid())? $this ->Click->setClickGuid( WTVRcleanString( $this -> getClickGuid()) ) : null;
    ($this -> getFkClickTrackId())? $this ->Click->setFkClickTrackId( WTVRcleanString( $this -> getFkClickTrackId()) ) : null;
    ($this -> getClickScript())? $this ->Click->setClickScript( WTVRcleanString( $this -> getClickScript()) ) : null;
    ($this -> getClickQuerystring())? $this ->Click->setClickQuerystring( WTVRcleanString( $this -> getClickQuerystring()) ) : null;
    ($this -> getClickIncoming())? $this ->Click->setClickIncoming( WTVRcleanString( $this -> getClickIncoming()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Click ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Click = ClickPeer::retrieveByPK($id);
    }
    
    if (! $this ->Click ) {
      return;
    }
    
    $this ->Click -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Click_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "ClickPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Click = ClickPeer::doSelect($c);
    
    if (count($Click) >= 1) {
      $this ->Click = $Click[0];
      return true;
    } else {
      $this ->Click = new Click();
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
      $name = "ClickPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Click = ClickPeer::doSelect($c);
    
    if (count($Click) >= 1) {
      $this ->Click = $Click[0];
      return true;
    } else {
      $this ->Click = new Click();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>