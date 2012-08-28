<?php
       
   class HudCrudBase extends Utils_PageWidget { 
   
    var $Hud;
   
       var $hud_id;
   var $fk_host_id;
   var $hud_settings;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getHudId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Hud = HudPeer::retrieveByPK( $id );
    } else {
      $this ->Hud = new Hud;
    }
  }
  
  function hydrate( $id ) {
      $this ->Hud = HudPeer::retrieveByPK( $id );
  }
  
  function getHudId() {
    if (($this ->postVar("hud_id")) || ($this ->postVar("hud_id") === "")) {
      return $this ->postVar("hud_id");
    } elseif (($this ->getVar("hud_id")) || ($this ->getVar("hud_id") === "")) {
      return $this ->getVar("hud_id");
    } elseif (($this ->Hud) || ($this ->Hud === "")){
      return $this ->Hud -> getHudId();
    } elseif (($this ->sessionVar("hud_id")) || ($this ->sessionVar("hud_id") == "")) {
      return $this ->sessionVar("hud_id");
    } else {
      return false;
    }
  }
  
  function setHudId( $str ) {
    $this ->Hud -> setHudId( $str );
  }
  
  function getFkHostId() {
    if (($this ->postVar("fk_host_id")) || ($this ->postVar("fk_host_id") === "")) {
      return $this ->postVar("fk_host_id");
    } elseif (($this ->getVar("fk_host_id")) || ($this ->getVar("fk_host_id") === "")) {
      return $this ->getVar("fk_host_id");
    } elseif (($this ->Hud) || ($this ->Hud === "")){
      return $this ->Hud -> getFkHostId();
    } elseif (($this ->sessionVar("fk_host_id")) || ($this ->sessionVar("fk_host_id") == "")) {
      return $this ->sessionVar("fk_host_id");
    } else {
      return false;
    }
  }
  
  function setFkHostId( $str ) {
    $this ->Hud -> setFkHostId( $str );
  }
  
  function getHudSettings() {
    if (($this ->postVar("hud_settings")) || ($this ->postVar("hud_settings") === "")) {
      return $this ->postVar("hud_settings");
    } elseif (($this ->getVar("hud_settings")) || ($this ->getVar("hud_settings") === "")) {
      return $this ->getVar("hud_settings");
    } elseif (($this ->Hud) || ($this ->Hud === "")){
      return $this ->Hud -> getHudSettings();
    } elseif (($this ->sessionVar("hud_settings")) || ($this ->sessionVar("hud_settings") == "")) {
      return $this ->sessionVar("hud_settings");
    } else {
      return false;
    }
  }
  
  function setHudSettings( $str ) {
    $this ->Hud -> setHudSettings( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Hud = HudPeer::retrieveByPK( $id );
    }
    
    if ($this ->Hud ) {
       
    	       (is_numeric(WTVRcleanString($this ->Hud->getHudId()))) ? $itemarray["hud_id"] = WTVRcleanString($this ->Hud->getHudId()) : null;
          (is_numeric(WTVRcleanString($this ->Hud->getFkHostId()))) ? $itemarray["fk_host_id"] = WTVRcleanString($this ->Hud->getFkHostId()) : null;
          (WTVRcleanString($this ->Hud->getHudSettings())) ? $itemarray["hud_settings"] = WTVRcleanString($this ->Hud->getHudSettings()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Hud = HudPeer::retrieveByPK( $id );
    } elseif (! $this ->Hud) {
      $this ->Hud = new Hud;
    }
        
  	 ($this -> getHudId())? $this ->Hud->setHudId( WTVRcleanString( $this -> getHudId()) ) : null;
    ($this -> getFkHostId())? $this ->Hud->setFkHostId( WTVRcleanString( $this -> getFkHostId()) ) : null;
    ($this -> getHudSettings())? $this ->Hud->setHudSettings( WTVRcleanString( $this -> getHudSettings()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Hud ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Hud = HudPeer::retrieveByPK($id);
    }
    
    if (! $this ->Hud ) {
      return;
    }
    
    $this ->Hud -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Hud_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "HudPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Hud = HudPeer::doSelect($c);
    
    if (count($Hud) >= 1) {
      $this ->Hud = $Hud[0];
      return true;
    } else {
      $this ->Hud = new Hud();
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
      $name = "HudPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Hud = HudPeer::doSelect($c);
    
    if (count($Hud) >= 1) {
      $this ->Hud = $Hud[0];
      return true;
    } else {
      $this ->Hud = new Hud();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>