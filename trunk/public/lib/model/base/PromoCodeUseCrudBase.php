<?php
       
   class PromoCodeUseCrudBase extends Utils_PageWidget { 
   
    var $PromoCodeUse;
   
       var $promo_code_use_id;
   var $promo_code_use_code;
   var $fk_promo_code_id;
   var $fk_user_id;
   var $fk_film_id;
   var $fk_audience_id;
   var $fk_screening_id;
   var $promo_code_use_date;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getPromoCodeUseId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->PromoCodeUse = PromoCodeUsePeer::retrieveByPK( $id );
    } else {
      $this ->PromoCodeUse = new PromoCodeUse;
    }
  }
  
  function hydrate( $id ) {
      $this ->PromoCodeUse = PromoCodeUsePeer::retrieveByPK( $id );
  }
  
  function getPromoCodeUseId() {
    if (($this ->postVar("promo_code_use_id")) || ($this ->postVar("promo_code_use_id") === "")) {
      return $this ->postVar("promo_code_use_id");
    } elseif (($this ->getVar("promo_code_use_id")) || ($this ->getVar("promo_code_use_id") === "")) {
      return $this ->getVar("promo_code_use_id");
    } elseif (($this ->PromoCodeUse) || ($this ->PromoCodeUse === "")){
      return $this ->PromoCodeUse -> getPromoCodeUseId();
    } elseif (($this ->sessionVar("promo_code_use_id")) || ($this ->sessionVar("promo_code_use_id") == "")) {
      return $this ->sessionVar("promo_code_use_id");
    } else {
      return false;
    }
  }
  
  function setPromoCodeUseId( $str ) {
    $this ->PromoCodeUse -> setPromoCodeUseId( $str );
  }
  
  function getPromoCodeUseCode() {
    if (($this ->postVar("promo_code_use_code")) || ($this ->postVar("promo_code_use_code") === "")) {
      return $this ->postVar("promo_code_use_code");
    } elseif (($this ->getVar("promo_code_use_code")) || ($this ->getVar("promo_code_use_code") === "")) {
      return $this ->getVar("promo_code_use_code");
    } elseif (($this ->PromoCodeUse) || ($this ->PromoCodeUse === "")){
      return $this ->PromoCodeUse -> getPromoCodeUseCode();
    } elseif (($this ->sessionVar("promo_code_use_code")) || ($this ->sessionVar("promo_code_use_code") == "")) {
      return $this ->sessionVar("promo_code_use_code");
    } else {
      return false;
    }
  }
  
  function setPromoCodeUseCode( $str ) {
    $this ->PromoCodeUse -> setPromoCodeUseCode( $str );
  }
  
  function getFkPromoCodeId() {
    if (($this ->postVar("fk_promo_code_id")) || ($this ->postVar("fk_promo_code_id") === "")) {
      return $this ->postVar("fk_promo_code_id");
    } elseif (($this ->getVar("fk_promo_code_id")) || ($this ->getVar("fk_promo_code_id") === "")) {
      return $this ->getVar("fk_promo_code_id");
    } elseif (($this ->PromoCodeUse) || ($this ->PromoCodeUse === "")){
      return $this ->PromoCodeUse -> getFkPromoCodeId();
    } elseif (($this ->sessionVar("fk_promo_code_id")) || ($this ->sessionVar("fk_promo_code_id") == "")) {
      return $this ->sessionVar("fk_promo_code_id");
    } else {
      return false;
    }
  }
  
  function setFkPromoCodeId( $str ) {
    $this ->PromoCodeUse -> setFkPromoCodeId( $str );
  }
  
  function getFkUserId() {
    if (($this ->postVar("fk_user_id")) || ($this ->postVar("fk_user_id") === "")) {
      return $this ->postVar("fk_user_id");
    } elseif (($this ->getVar("fk_user_id")) || ($this ->getVar("fk_user_id") === "")) {
      return $this ->getVar("fk_user_id");
    } elseif (($this ->PromoCodeUse) || ($this ->PromoCodeUse === "")){
      return $this ->PromoCodeUse -> getFkUserId();
    } elseif (($this ->sessionVar("fk_user_id")) || ($this ->sessionVar("fk_user_id") == "")) {
      return $this ->sessionVar("fk_user_id");
    } else {
      return false;
    }
  }
  
  function setFkUserId( $str ) {
    $this ->PromoCodeUse -> setFkUserId( $str );
  }
  
  function getFkFilmId() {
    if (($this ->postVar("fk_film_id")) || ($this ->postVar("fk_film_id") === "")) {
      return $this ->postVar("fk_film_id");
    } elseif (($this ->getVar("fk_film_id")) || ($this ->getVar("fk_film_id") === "")) {
      return $this ->getVar("fk_film_id");
    } elseif (($this ->PromoCodeUse) || ($this ->PromoCodeUse === "")){
      return $this ->PromoCodeUse -> getFkFilmId();
    } elseif (($this ->sessionVar("fk_film_id")) || ($this ->sessionVar("fk_film_id") == "")) {
      return $this ->sessionVar("fk_film_id");
    } else {
      return false;
    }
  }
  
  function setFkFilmId( $str ) {
    $this ->PromoCodeUse -> setFkFilmId( $str );
  }
  
  function getFkAudienceId() {
    if (($this ->postVar("fk_audience_id")) || ($this ->postVar("fk_audience_id") === "")) {
      return $this ->postVar("fk_audience_id");
    } elseif (($this ->getVar("fk_audience_id")) || ($this ->getVar("fk_audience_id") === "")) {
      return $this ->getVar("fk_audience_id");
    } elseif (($this ->PromoCodeUse) || ($this ->PromoCodeUse === "")){
      return $this ->PromoCodeUse -> getFkAudienceId();
    } elseif (($this ->sessionVar("fk_audience_id")) || ($this ->sessionVar("fk_audience_id") == "")) {
      return $this ->sessionVar("fk_audience_id");
    } else {
      return false;
    }
  }
  
  function setFkAudienceId( $str ) {
    $this ->PromoCodeUse -> setFkAudienceId( $str );
  }
  
  function getFkScreeningId() {
    if (($this ->postVar("fk_screening_id")) || ($this ->postVar("fk_screening_id") === "")) {
      return $this ->postVar("fk_screening_id");
    } elseif (($this ->getVar("fk_screening_id")) || ($this ->getVar("fk_screening_id") === "")) {
      return $this ->getVar("fk_screening_id");
    } elseif (($this ->PromoCodeUse) || ($this ->PromoCodeUse === "")){
      return $this ->PromoCodeUse -> getFkScreeningId();
    } elseif (($this ->sessionVar("fk_screening_id")) || ($this ->sessionVar("fk_screening_id") == "")) {
      return $this ->sessionVar("fk_screening_id");
    } else {
      return false;
    }
  }
  
  function setFkScreeningId( $str ) {
    $this ->PromoCodeUse -> setFkScreeningId( $str );
  }
  
  function getPromoCodeUseDate() {
    if (($this ->postVar("promo_code_use_date")) || ($this ->postVar("promo_code_use_date") === "")) {
      return $this ->postVar("promo_code_use_date");
    } elseif (($this ->getVar("promo_code_use_date")) || ($this ->getVar("promo_code_use_date") === "")) {
      return $this ->getVar("promo_code_use_date");
    } elseif (($this ->PromoCodeUse) || ($this ->PromoCodeUse === "")){
      return $this ->PromoCodeUse -> getPromoCodeUseDate();
    } elseif (($this ->sessionVar("promo_code_use_date")) || ($this ->sessionVar("promo_code_use_date") == "")) {
      return $this ->sessionVar("promo_code_use_date");
    } else {
      return false;
    }
  }
  
  function setPromoCodeUseDate( $str ) {
    $this ->PromoCodeUse -> setPromoCodeUseDate( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->PromoCodeUse = PromoCodeUsePeer::retrieveByPK( $id );
    }
    
    if ($this ->PromoCodeUse ) {
       
    	       (is_numeric(WTVRcleanString($this ->PromoCodeUse->getPromoCodeUseId()))) ? $itemarray["promo_code_use_id"] = WTVRcleanString($this ->PromoCodeUse->getPromoCodeUseId()) : null;
          (WTVRcleanString($this ->PromoCodeUse->getPromoCodeUseCode())) ? $itemarray["promo_code_use_code"] = WTVRcleanString($this ->PromoCodeUse->getPromoCodeUseCode()) : null;
          (is_numeric(WTVRcleanString($this ->PromoCodeUse->getFkPromoCodeId()))) ? $itemarray["fk_promo_code_id"] = WTVRcleanString($this ->PromoCodeUse->getFkPromoCodeId()) : null;
          (is_numeric(WTVRcleanString($this ->PromoCodeUse->getFkUserId()))) ? $itemarray["fk_user_id"] = WTVRcleanString($this ->PromoCodeUse->getFkUserId()) : null;
          (is_numeric(WTVRcleanString($this ->PromoCodeUse->getFkFilmId()))) ? $itemarray["fk_film_id"] = WTVRcleanString($this ->PromoCodeUse->getFkFilmId()) : null;
          (is_numeric(WTVRcleanString($this ->PromoCodeUse->getFkAudienceId()))) ? $itemarray["fk_audience_id"] = WTVRcleanString($this ->PromoCodeUse->getFkAudienceId()) : null;
          (is_numeric(WTVRcleanString($this ->PromoCodeUse->getFkScreeningId()))) ? $itemarray["fk_screening_id"] = WTVRcleanString($this ->PromoCodeUse->getFkScreeningId()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->PromoCodeUse->getPromoCodeUseDate())) ? $itemarray["promo_code_use_date"] = formatDate($this ->PromoCodeUse->getPromoCodeUseDate('%Y-%m-%d %T'),"TS") : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->PromoCodeUse = PromoCodeUsePeer::retrieveByPK( $id );
    } elseif (! $this ->PromoCodeUse) {
      $this ->PromoCodeUse = new PromoCodeUse;
    }
        
  	 ($this -> getPromoCodeUseId())? $this ->PromoCodeUse->setPromoCodeUseId( WTVRcleanString( $this -> getPromoCodeUseId()) ) : null;
    ($this -> getPromoCodeUseCode())? $this ->PromoCodeUse->setPromoCodeUseCode( WTVRcleanString( $this -> getPromoCodeUseCode()) ) : null;
    ($this -> getFkPromoCodeId())? $this ->PromoCodeUse->setFkPromoCodeId( WTVRcleanString( $this -> getFkPromoCodeId()) ) : null;
    ($this -> getFkUserId())? $this ->PromoCodeUse->setFkUserId( WTVRcleanString( $this -> getFkUserId()) ) : null;
    ($this -> getFkFilmId())? $this ->PromoCodeUse->setFkFilmId( WTVRcleanString( $this -> getFkFilmId()) ) : null;
    ($this -> getFkAudienceId())? $this ->PromoCodeUse->setFkAudienceId( WTVRcleanString( $this -> getFkAudienceId()) ) : null;
    ($this -> getFkScreeningId())? $this ->PromoCodeUse->setFkScreeningId( WTVRcleanString( $this -> getFkScreeningId()) ) : null;
          if (is_valid_date( $this ->PromoCodeUse->getPromoCodeUseDate())) {
        $this ->PromoCodeUse->setPromoCodeUseDate( formatDate($this -> getPromoCodeUseDate(), "TS" ));
      } else {
      $PromoCodeUsepromo_code_use_date = $this -> sfDateTime( "promo_code_use_date" );
      ( $PromoCodeUsepromo_code_use_date != "01/01/1900 00:00:00" )? $this ->PromoCodeUse->setPromoCodeUseDate( formatDate($PromoCodeUsepromo_code_use_date, "TS" )) : $this ->PromoCodeUse->setPromoCodeUseDate( null );
      }
          
    $this -> save();
     
  }
  
  function save() {
    $this ->PromoCodeUse ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->PromoCodeUse = PromoCodeUsePeer::retrieveByPK($id);
    }
    
    if (! $this ->PromoCodeUse ) {
      return;
    }
    
    $this ->PromoCodeUse -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('PromoCodeUse_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "PromoCodeUsePeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $PromoCodeUse = PromoCodeUsePeer::doSelect($c);
    
    if (count($PromoCodeUse) >= 1) {
      $this ->PromoCodeUse = $PromoCodeUse[0];
      return true;
    } else {
      $this ->PromoCodeUse = new PromoCodeUse();
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
      $name = "PromoCodeUsePeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $PromoCodeUse = PromoCodeUsePeer::doSelect($c);
    
    if (count($PromoCodeUse) >= 1) {
      $this ->PromoCodeUse = $PromoCodeUse[0];
      return true;
    } else {
      $this ->PromoCodeUse = new PromoCodeUse();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>