<?php
       
   class VowCrudBase extends Utils_PageWidget { 
   
    var $Vow;
   
       var $vow_id;
   var $vow_date_created;
   var $fk_user_id;
   var $vow_description;
   var $vow_asset_guid;
   var $vow_asset_filename;
   var $vow_image_generated;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getVowId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Vow = VowPeer::retrieveByPK( $id );
    } else {
      $this ->Vow = new Vow;
    }
  }
  
  function hydrate( $id ) {
      $this ->Vow = VowPeer::retrieveByPK( $id );
  }
  
  function getVowId() {
    if (($this ->postVar("vow_id")) || ($this ->postVar("vow_id") === "")) {
      return $this ->postVar("vow_id");
    } elseif (($this ->getVar("vow_id")) || ($this ->getVar("vow_id") === "")) {
      return $this ->getVar("vow_id");
    } elseif (($this ->Vow) || ($this ->Vow === "")){
      return $this ->Vow -> getVowId();
    } elseif (($this ->sessionVar("vow_id")) || ($this ->sessionVar("vow_id") == "")) {
      return $this ->sessionVar("vow_id");
    } else {
      return false;
    }
  }
  
  function setVowId( $str ) {
    $this ->Vow -> setVowId( $str );
  }
  
  function getVowDateCreated() {
    if (($this ->postVar("vow_date_created")) || ($this ->postVar("vow_date_created") === "")) {
      return $this ->postVar("vow_date_created");
    } elseif (($this ->getVar("vow_date_created")) || ($this ->getVar("vow_date_created") === "")) {
      return $this ->getVar("vow_date_created");
    } elseif (($this ->Vow) || ($this ->Vow === "")){
      return $this ->Vow -> getVowDateCreated();
    } elseif (($this ->sessionVar("vow_date_created")) || ($this ->sessionVar("vow_date_created") == "")) {
      return $this ->sessionVar("vow_date_created");
    } else {
      return false;
    }
  }
  
  function setVowDateCreated( $str ) {
    $this ->Vow -> setVowDateCreated( $str );
  }
  
  function getFkUserId() {
    if (($this ->postVar("fk_user_id")) || ($this ->postVar("fk_user_id") === "")) {
      return $this ->postVar("fk_user_id");
    } elseif (($this ->getVar("fk_user_id")) || ($this ->getVar("fk_user_id") === "")) {
      return $this ->getVar("fk_user_id");
    } elseif (($this ->Vow) || ($this ->Vow === "")){
      return $this ->Vow -> getFkUserId();
    } elseif (($this ->sessionVar("fk_user_id")) || ($this ->sessionVar("fk_user_id") == "")) {
      return $this ->sessionVar("fk_user_id");
    } else {
      return false;
    }
  }
  
  function setFkUserId( $str ) {
    $this ->Vow -> setFkUserId( $str );
  }
  
  function getVowDescription() {
    if (($this ->postVar("vow_description")) || ($this ->postVar("vow_description") === "")) {
      return $this ->postVar("vow_description");
    } elseif (($this ->getVar("vow_description")) || ($this ->getVar("vow_description") === "")) {
      return $this ->getVar("vow_description");
    } elseif (($this ->Vow) || ($this ->Vow === "")){
      return $this ->Vow -> getVowDescription();
    } elseif (($this ->sessionVar("vow_description")) || ($this ->sessionVar("vow_description") == "")) {
      return $this ->sessionVar("vow_description");
    } else {
      return false;
    }
  }
  
  function setVowDescription( $str ) {
    $this ->Vow -> setVowDescription( $str );
  }
  
  function getVowAssetGuid() {
    if (($this ->postVar("vow_asset_guid")) || ($this ->postVar("vow_asset_guid") === "")) {
      return $this ->postVar("vow_asset_guid");
    } elseif (($this ->getVar("vow_asset_guid")) || ($this ->getVar("vow_asset_guid") === "")) {
      return $this ->getVar("vow_asset_guid");
    } elseif (($this ->Vow) || ($this ->Vow === "")){
      return $this ->Vow -> getVowAssetGuid();
    } elseif (($this ->sessionVar("vow_asset_guid")) || ($this ->sessionVar("vow_asset_guid") == "")) {
      return $this ->sessionVar("vow_asset_guid");
    } else {
      return false;
    }
  }
  
  function setVowAssetGuid( $str ) {
    $this ->Vow -> setVowAssetGuid( $str );
  }
  
  function getVowAssetFilename() {
    if (($this ->postVar("vow_asset_filename")) || ($this ->postVar("vow_asset_filename") === "")) {
      return $this ->postVar("vow_asset_filename");
    } elseif (($this ->getVar("vow_asset_filename")) || ($this ->getVar("vow_asset_filename") === "")) {
      return $this ->getVar("vow_asset_filename");
    } elseif (($this ->Vow) || ($this ->Vow === "")){
      return $this ->Vow -> getVowAssetFilename();
    } elseif (($this ->sessionVar("vow_asset_filename")) || ($this ->sessionVar("vow_asset_filename") == "")) {
      return $this ->sessionVar("vow_asset_filename");
    } else {
      return false;
    }
  }
  
  function setVowAssetFilename( $str ) {
    $this ->Vow -> setVowAssetFilename( $str );
  }
  
  function getVowImageGenerated() {
    if (($this ->postVar("vow_image_generated")) || ($this ->postVar("vow_image_generated") === "")) {
      return $this ->postVar("vow_image_generated");
    } elseif (($this ->getVar("vow_image_generated")) || ($this ->getVar("vow_image_generated") === "")) {
      return $this ->getVar("vow_image_generated");
    } elseif (($this ->Vow) || ($this ->Vow === "")){
      return $this ->Vow -> getVowImageGenerated();
    } elseif (($this ->sessionVar("vow_image_generated")) || ($this ->sessionVar("vow_image_generated") == "")) {
      return $this ->sessionVar("vow_image_generated");
    } else {
      return false;
    }
  }
  
  function setVowImageGenerated( $str ) {
    $this ->Vow -> setVowImageGenerated( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Vow = VowPeer::retrieveByPK( $id );
    }
    
    if ($this ->Vow ) {
       
    	       (is_numeric(WTVRcleanString($this ->Vow->getVowId()))) ? $itemarray["vow_id"] = WTVRcleanString($this ->Vow->getVowId()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Vow->getVowDateCreated())) ? $itemarray["vow_date_created"] = formatDate($this ->Vow->getVowDateCreated('%Y-%m-%d %T'),"TS") : null;
          (is_numeric(WTVRcleanString($this ->Vow->getFkUserId()))) ? $itemarray["fk_user_id"] = WTVRcleanString($this ->Vow->getFkUserId()) : null;
          (WTVRcleanString($this ->Vow->getVowDescription())) ? $itemarray["vow_description"] = WTVRcleanString($this ->Vow->getVowDescription()) : null;
          (WTVRcleanString($this ->Vow->getVowAssetGuid())) ? $itemarray["vow_asset_guid"] = WTVRcleanString($this ->Vow->getVowAssetGuid()) : null;
          (WTVRcleanString($this ->Vow->getVowAssetFilename())) ? $itemarray["vow_asset_filename"] = WTVRcleanString($this ->Vow->getVowAssetFilename()) : null;
          (WTVRcleanString($this ->Vow->getVowImageGenerated())) ? $itemarray["vow_image_generated"] = WTVRcleanString($this ->Vow->getVowImageGenerated()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Vow = VowPeer::retrieveByPK( $id );
    } elseif (! $this ->Vow) {
      $this ->Vow = new Vow;
    }
        
  	 ($this -> getVowId())? $this ->Vow->setVowId( WTVRcleanString( $this -> getVowId()) ) : null;
          if (is_valid_date( $this ->Vow->getVowDateCreated())) {
        $this ->Vow->setVowDateCreated( formatDate($this -> getVowDateCreated(), "TS" ));
      } else {
      $Vowvow_date_created = $this -> sfDateTime( "vow_date_created" );
      ( $Vowvow_date_created != "01/01/1900 00:00:00" )? $this ->Vow->setVowDateCreated( formatDate($Vowvow_date_created, "TS" )) : $this ->Vow->setVowDateCreated( null );
      }
    ($this -> getFkUserId())? $this ->Vow->setFkUserId( WTVRcleanString( $this -> getFkUserId()) ) : null;
    ($this -> getVowDescription())? $this ->Vow->setVowDescription( WTVRcleanString( $this -> getVowDescription()) ) : null;
    ($this -> getVowAssetGuid())? $this ->Vow->setVowAssetGuid( WTVRcleanString( $this -> getVowAssetGuid()) ) : null;
    ($this -> getVowAssetFilename())? $this ->Vow->setVowAssetFilename( WTVRcleanString( $this -> getVowAssetFilename()) ) : null;
    ($this -> getVowImageGenerated())? $this ->Vow->setVowImageGenerated( WTVRcleanString( $this -> getVowImageGenerated()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Vow ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Vow = VowPeer::retrieveByPK($id);
    }
    
    if (! $this ->Vow ) {
      return;
    }
    
    $this ->Vow -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Vow_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "VowPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Vow = VowPeer::doSelect($c);
    
    if (count($Vow) >= 1) {
      $this ->Vow = $Vow[0];
      return true;
    } else {
      $this ->Vow = new Vow();
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
      $name = "VowPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Vow = VowPeer::doSelect($c);
    
    if (count($Vow) >= 1) {
      $this ->Vow = $Vow[0];
      return true;
    } else {
      $this ->Vow = new Vow();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>