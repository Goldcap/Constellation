<?php
       
   class PurchaseRefererCrudBase extends Utils_PageWidget { 
   
    var $PurchaseReferer;
   
       var $purchase_referer_id;
   var $fk_user_id;
   var $fk_film_id;
   var $fk_audience_id;
   var $fk_screening_id;
   var $purchase_referer_referer;
   var $fk_audience_date;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getPurchaseRefererId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->PurchaseReferer = PurchaseRefererPeer::retrieveByPK( $id );
    } else {
      $this ->PurchaseReferer = new PurchaseReferer;
    }
  }
  
  function hydrate( $id ) {
      $this ->PurchaseReferer = PurchaseRefererPeer::retrieveByPK( $id );
  }
  
  function getPurchaseRefererId() {
    if (($this ->postVar("purchase_referer_id")) || ($this ->postVar("purchase_referer_id") === "")) {
      return $this ->postVar("purchase_referer_id");
    } elseif (($this ->getVar("purchase_referer_id")) || ($this ->getVar("purchase_referer_id") === "")) {
      return $this ->getVar("purchase_referer_id");
    } elseif (($this ->PurchaseReferer) || ($this ->PurchaseReferer === "")){
      return $this ->PurchaseReferer -> getPurchaseRefererId();
    } elseif (($this ->sessionVar("purchase_referer_id")) || ($this ->sessionVar("purchase_referer_id") == "")) {
      return $this ->sessionVar("purchase_referer_id");
    } else {
      return false;
    }
  }
  
  function setPurchaseRefererId( $str ) {
    $this ->PurchaseReferer -> setPurchaseRefererId( $str );
  }
  
  function getFkUserId() {
    if (($this ->postVar("fk_user_id")) || ($this ->postVar("fk_user_id") === "")) {
      return $this ->postVar("fk_user_id");
    } elseif (($this ->getVar("fk_user_id")) || ($this ->getVar("fk_user_id") === "")) {
      return $this ->getVar("fk_user_id");
    } elseif (($this ->PurchaseReferer) || ($this ->PurchaseReferer === "")){
      return $this ->PurchaseReferer -> getFkUserId();
    } elseif (($this ->sessionVar("fk_user_id")) || ($this ->sessionVar("fk_user_id") == "")) {
      return $this ->sessionVar("fk_user_id");
    } else {
      return false;
    }
  }
  
  function setFkUserId( $str ) {
    $this ->PurchaseReferer -> setFkUserId( $str );
  }
  
  function getFkFilmId() {
    if (($this ->postVar("fk_film_id")) || ($this ->postVar("fk_film_id") === "")) {
      return $this ->postVar("fk_film_id");
    } elseif (($this ->getVar("fk_film_id")) || ($this ->getVar("fk_film_id") === "")) {
      return $this ->getVar("fk_film_id");
    } elseif (($this ->PurchaseReferer) || ($this ->PurchaseReferer === "")){
      return $this ->PurchaseReferer -> getFkFilmId();
    } elseif (($this ->sessionVar("fk_film_id")) || ($this ->sessionVar("fk_film_id") == "")) {
      return $this ->sessionVar("fk_film_id");
    } else {
      return false;
    }
  }
  
  function setFkFilmId( $str ) {
    $this ->PurchaseReferer -> setFkFilmId( $str );
  }
  
  function getFkAudienceId() {
    if (($this ->postVar("fk_audience_id")) || ($this ->postVar("fk_audience_id") === "")) {
      return $this ->postVar("fk_audience_id");
    } elseif (($this ->getVar("fk_audience_id")) || ($this ->getVar("fk_audience_id") === "")) {
      return $this ->getVar("fk_audience_id");
    } elseif (($this ->PurchaseReferer) || ($this ->PurchaseReferer === "")){
      return $this ->PurchaseReferer -> getFkAudienceId();
    } elseif (($this ->sessionVar("fk_audience_id")) || ($this ->sessionVar("fk_audience_id") == "")) {
      return $this ->sessionVar("fk_audience_id");
    } else {
      return false;
    }
  }
  
  function setFkAudienceId( $str ) {
    $this ->PurchaseReferer -> setFkAudienceId( $str );
  }
  
  function getFkScreeningId() {
    if (($this ->postVar("fk_screening_id")) || ($this ->postVar("fk_screening_id") === "")) {
      return $this ->postVar("fk_screening_id");
    } elseif (($this ->getVar("fk_screening_id")) || ($this ->getVar("fk_screening_id") === "")) {
      return $this ->getVar("fk_screening_id");
    } elseif (($this ->PurchaseReferer) || ($this ->PurchaseReferer === "")){
      return $this ->PurchaseReferer -> getFkScreeningId();
    } elseif (($this ->sessionVar("fk_screening_id")) || ($this ->sessionVar("fk_screening_id") == "")) {
      return $this ->sessionVar("fk_screening_id");
    } else {
      return false;
    }
  }
  
  function setFkScreeningId( $str ) {
    $this ->PurchaseReferer -> setFkScreeningId( $str );
  }
  
  function getPurchaseRefererReferer() {
    if (($this ->postVar("purchase_referer_referer")) || ($this ->postVar("purchase_referer_referer") === "")) {
      return $this ->postVar("purchase_referer_referer");
    } elseif (($this ->getVar("purchase_referer_referer")) || ($this ->getVar("purchase_referer_referer") === "")) {
      return $this ->getVar("purchase_referer_referer");
    } elseif (($this ->PurchaseReferer) || ($this ->PurchaseReferer === "")){
      return $this ->PurchaseReferer -> getPurchaseRefererReferer();
    } elseif (($this ->sessionVar("purchase_referer_referer")) || ($this ->sessionVar("purchase_referer_referer") == "")) {
      return $this ->sessionVar("purchase_referer_referer");
    } else {
      return false;
    }
  }
  
  function setPurchaseRefererReferer( $str ) {
    $this ->PurchaseReferer -> setPurchaseRefererReferer( $str );
  }
  
  function getFkAudienceDate() {
    if (($this ->postVar("fk_audience_date")) || ($this ->postVar("fk_audience_date") === "")) {
      return $this ->postVar("fk_audience_date");
    } elseif (($this ->getVar("fk_audience_date")) || ($this ->getVar("fk_audience_date") === "")) {
      return $this ->getVar("fk_audience_date");
    } elseif (($this ->PurchaseReferer) || ($this ->PurchaseReferer === "")){
      return $this ->PurchaseReferer -> getFkAudienceDate();
    } elseif (($this ->sessionVar("fk_audience_date")) || ($this ->sessionVar("fk_audience_date") == "")) {
      return $this ->sessionVar("fk_audience_date");
    } else {
      return false;
    }
  }
  
  function setFkAudienceDate( $str ) {
    $this ->PurchaseReferer -> setFkAudienceDate( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->PurchaseReferer = PurchaseRefererPeer::retrieveByPK( $id );
    }
    
    if ($this ->PurchaseReferer ) {
       
    	       (is_numeric(WTVRcleanString($this ->PurchaseReferer->getPurchaseRefererId()))) ? $itemarray["purchase_referer_id"] = WTVRcleanString($this ->PurchaseReferer->getPurchaseRefererId()) : null;
          (is_numeric(WTVRcleanString($this ->PurchaseReferer->getFkUserId()))) ? $itemarray["fk_user_id"] = WTVRcleanString($this ->PurchaseReferer->getFkUserId()) : null;
          (is_numeric(WTVRcleanString($this ->PurchaseReferer->getFkFilmId()))) ? $itemarray["fk_film_id"] = WTVRcleanString($this ->PurchaseReferer->getFkFilmId()) : null;
          (is_numeric(WTVRcleanString($this ->PurchaseReferer->getFkAudienceId()))) ? $itemarray["fk_audience_id"] = WTVRcleanString($this ->PurchaseReferer->getFkAudienceId()) : null;
          (is_numeric(WTVRcleanString($this ->PurchaseReferer->getFkScreeningId()))) ? $itemarray["fk_screening_id"] = WTVRcleanString($this ->PurchaseReferer->getFkScreeningId()) : null;
          (WTVRcleanString($this ->PurchaseReferer->getPurchaseRefererReferer())) ? $itemarray["purchase_referer_referer"] = WTVRcleanString($this ->PurchaseReferer->getPurchaseRefererReferer()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->PurchaseReferer->getFkAudienceDate())) ? $itemarray["fk_audience_date"] = formatDate($this ->PurchaseReferer->getFkAudienceDate('%Y-%m-%d %T'),"TS") : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->PurchaseReferer = PurchaseRefererPeer::retrieveByPK( $id );
    } elseif (! $this ->PurchaseReferer) {
      $this ->PurchaseReferer = new PurchaseReferer;
    }
        
  	 ($this -> getPurchaseRefererId())? $this ->PurchaseReferer->setPurchaseRefererId( WTVRcleanString( $this -> getPurchaseRefererId()) ) : null;
    ($this -> getFkUserId())? $this ->PurchaseReferer->setFkUserId( WTVRcleanString( $this -> getFkUserId()) ) : null;
    ($this -> getFkFilmId())? $this ->PurchaseReferer->setFkFilmId( WTVRcleanString( $this -> getFkFilmId()) ) : null;
    ($this -> getFkAudienceId())? $this ->PurchaseReferer->setFkAudienceId( WTVRcleanString( $this -> getFkAudienceId()) ) : null;
    ($this -> getFkScreeningId())? $this ->PurchaseReferer->setFkScreeningId( WTVRcleanString( $this -> getFkScreeningId()) ) : null;
    ($this -> getPurchaseRefererReferer())? $this ->PurchaseReferer->setPurchaseRefererReferer( WTVRcleanString( $this -> getPurchaseRefererReferer()) ) : null;
          if (is_valid_date( $this ->PurchaseReferer->getFkAudienceDate())) {
        $this ->PurchaseReferer->setFkAudienceDate( formatDate($this -> getFkAudienceDate(), "TS" ));
      } else {
      $PurchaseRefererfk_audience_date = $this -> sfDateTime( "fk_audience_date" );
      ( $PurchaseRefererfk_audience_date != "01/01/1900 00:00:00" )? $this ->PurchaseReferer->setFkAudienceDate( formatDate($PurchaseRefererfk_audience_date, "TS" )) : $this ->PurchaseReferer->setFkAudienceDate( null );
      }
          
    $this -> save();
     
  }
  
  function save() {
    $this ->PurchaseReferer ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->PurchaseReferer = PurchaseRefererPeer::retrieveByPK($id);
    }
    
    if (! $this ->PurchaseReferer ) {
      return;
    }
    
    $this ->PurchaseReferer -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('PurchaseReferer_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "PurchaseRefererPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $PurchaseReferer = PurchaseRefererPeer::doSelect($c);
    
    if (count($PurchaseReferer) >= 1) {
      $this ->PurchaseReferer = $PurchaseReferer[0];
      return true;
    } else {
      $this ->PurchaseReferer = new PurchaseReferer();
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
      $name = "PurchaseRefererPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $PurchaseReferer = PurchaseRefererPeer::doSelect($c);
    
    if (count($PurchaseReferer) >= 1) {
      $this ->PurchaseReferer = $PurchaseReferer[0];
      return true;
    } else {
      $this ->PurchaseReferer = new PurchaseReferer();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>