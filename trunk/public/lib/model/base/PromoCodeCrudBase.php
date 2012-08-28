<?php
       
   class PromoCodeCrudBase extends Utils_PageWidget { 
   
    var $PromoCode;
   
       var $promo_code_id;
   var $promo_code_type;
   var $promo_code_value;
   var $promo_code_code;
   var $fk_film_id;
   var $promo_code_uses;
   var $promo_code_total_usage;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getPromoCodeId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->PromoCode = PromoCodePeer::retrieveByPK( $id );
    } else {
      $this ->PromoCode = new PromoCode;
    }
  }
  
  function hydrate( $id ) {
      $this ->PromoCode = PromoCodePeer::retrieveByPK( $id );
  }
  
  function getPromoCodeId() {
    if (($this ->postVar("promo_code_id")) || ($this ->postVar("promo_code_id") === "")) {
      return $this ->postVar("promo_code_id");
    } elseif (($this ->getVar("promo_code_id")) || ($this ->getVar("promo_code_id") === "")) {
      return $this ->getVar("promo_code_id");
    } elseif (($this ->PromoCode) || ($this ->PromoCode === "")){
      return $this ->PromoCode -> getPromoCodeId();
    } elseif (($this ->sessionVar("promo_code_id")) || ($this ->sessionVar("promo_code_id") == "")) {
      return $this ->sessionVar("promo_code_id");
    } else {
      return false;
    }
  }
  
  function setPromoCodeId( $str ) {
    $this ->PromoCode -> setPromoCodeId( $str );
  }
  
  function getPromoCodeType() {
    if (($this ->postVar("promo_code_type")) || ($this ->postVar("promo_code_type") === "")) {
      return $this ->postVar("promo_code_type");
    } elseif (($this ->getVar("promo_code_type")) || ($this ->getVar("promo_code_type") === "")) {
      return $this ->getVar("promo_code_type");
    } elseif (($this ->PromoCode) || ($this ->PromoCode === "")){
      return $this ->PromoCode -> getPromoCodeType();
    } elseif (($this ->sessionVar("promo_code_type")) || ($this ->sessionVar("promo_code_type") == "")) {
      return $this ->sessionVar("promo_code_type");
    } else {
      return false;
    }
  }
  
  function setPromoCodeType( $str ) {
    $this ->PromoCode -> setPromoCodeType( $str );
  }
  
  function getPromoCodeValue() {
    if (($this ->postVar("promo_code_value")) || ($this ->postVar("promo_code_value") === "")) {
      return $this ->postVar("promo_code_value");
    } elseif (($this ->getVar("promo_code_value")) || ($this ->getVar("promo_code_value") === "")) {
      return $this ->getVar("promo_code_value");
    } elseif (($this ->PromoCode) || ($this ->PromoCode === "")){
      return $this ->PromoCode -> getPromoCodeValue();
    } elseif (($this ->sessionVar("promo_code_value")) || ($this ->sessionVar("promo_code_value") == "")) {
      return $this ->sessionVar("promo_code_value");
    } else {
      return false;
    }
  }
  
  function setPromoCodeValue( $str ) {
    $this ->PromoCode -> setPromoCodeValue( $str );
  }
  
  function getPromoCodeCode() {
    if (($this ->postVar("promo_code_code")) || ($this ->postVar("promo_code_code") === "")) {
      return $this ->postVar("promo_code_code");
    } elseif (($this ->getVar("promo_code_code")) || ($this ->getVar("promo_code_code") === "")) {
      return $this ->getVar("promo_code_code");
    } elseif (($this ->PromoCode) || ($this ->PromoCode === "")){
      return $this ->PromoCode -> getPromoCodeCode();
    } elseif (($this ->sessionVar("promo_code_code")) || ($this ->sessionVar("promo_code_code") == "")) {
      return $this ->sessionVar("promo_code_code");
    } else {
      return false;
    }
  }
  
  function setPromoCodeCode( $str ) {
    $this ->PromoCode -> setPromoCodeCode( $str );
  }
  
  function getFkFilmId() {
    if (($this ->postVar("fk_film_id")) || ($this ->postVar("fk_film_id") === "")) {
      return $this ->postVar("fk_film_id");
    } elseif (($this ->getVar("fk_film_id")) || ($this ->getVar("fk_film_id") === "")) {
      return $this ->getVar("fk_film_id");
    } elseif (($this ->PromoCode) || ($this ->PromoCode === "")){
      return $this ->PromoCode -> getFkFilmId();
    } elseif (($this ->sessionVar("fk_film_id")) || ($this ->sessionVar("fk_film_id") == "")) {
      return $this ->sessionVar("fk_film_id");
    } else {
      return false;
    }
  }
  
  function setFkFilmId( $str ) {
    $this ->PromoCode -> setFkFilmId( $str );
  }
  
  function getPromoCodeUses() {
    if (($this ->postVar("promo_code_uses")) || ($this ->postVar("promo_code_uses") === "")) {
      return $this ->postVar("promo_code_uses");
    } elseif (($this ->getVar("promo_code_uses")) || ($this ->getVar("promo_code_uses") === "")) {
      return $this ->getVar("promo_code_uses");
    } elseif (($this ->PromoCode) || ($this ->PromoCode === "")){
      return $this ->PromoCode -> getPromoCodeUses();
    } elseif (($this ->sessionVar("promo_code_uses")) || ($this ->sessionVar("promo_code_uses") == "")) {
      return $this ->sessionVar("promo_code_uses");
    } else {
      return false;
    }
  }
  
  function setPromoCodeUses( $str ) {
    $this ->PromoCode -> setPromoCodeUses( $str );
  }
  
  function getPromoCodeTotalUsage() {
    if (($this ->postVar("promo_code_total_usage")) || ($this ->postVar("promo_code_total_usage") === "")) {
      return $this ->postVar("promo_code_total_usage");
    } elseif (($this ->getVar("promo_code_total_usage")) || ($this ->getVar("promo_code_total_usage") === "")) {
      return $this ->getVar("promo_code_total_usage");
    } elseif (($this ->PromoCode) || ($this ->PromoCode === "")){
      return $this ->PromoCode -> getPromoCodeTotalUsage();
    } elseif (($this ->sessionVar("promo_code_total_usage")) || ($this ->sessionVar("promo_code_total_usage") == "")) {
      return $this ->sessionVar("promo_code_total_usage");
    } else {
      return false;
    }
  }
  
  function setPromoCodeTotalUsage( $str ) {
    $this ->PromoCode -> setPromoCodeTotalUsage( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->PromoCode = PromoCodePeer::retrieveByPK( $id );
    }
    
    if ($this ->PromoCode ) {
       
    	       (is_numeric(WTVRcleanString($this ->PromoCode->getPromoCodeId()))) ? $itemarray["promo_code_id"] = WTVRcleanString($this ->PromoCode->getPromoCodeId()) : null;
          (WTVRcleanString($this ->PromoCode->getPromoCodeType())) ? $itemarray["promo_code_type"] = WTVRcleanString($this ->PromoCode->getPromoCodeType()) : null;
          (is_numeric(WTVRcleanString($this ->PromoCode->getPromoCodeValue()))) ? $itemarray["promo_code_value"] = sprintf("%01.2f",WTVRcleanString($this ->PromoCode->getPromoCodeValue())) : null;
          (WTVRcleanString($this ->PromoCode->getPromoCodeCode())) ? $itemarray["promo_code_code"] = WTVRcleanString($this ->PromoCode->getPromoCodeCode()) : null;
          (is_numeric(WTVRcleanString($this ->PromoCode->getFkFilmId()))) ? $itemarray["fk_film_id"] = WTVRcleanString($this ->PromoCode->getFkFilmId()) : null;
          (is_numeric(WTVRcleanString($this ->PromoCode->getPromoCodeUses()))) ? $itemarray["promo_code_uses"] = WTVRcleanString($this ->PromoCode->getPromoCodeUses()) : null;
          (is_numeric(WTVRcleanString($this ->PromoCode->getPromoCodeTotalUsage()))) ? $itemarray["promo_code_total_usage"] = WTVRcleanString($this ->PromoCode->getPromoCodeTotalUsage()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->PromoCode = PromoCodePeer::retrieveByPK( $id );
    } elseif (! $this ->PromoCode) {
      $this ->PromoCode = new PromoCode;
    }
        
  	 ($this -> getPromoCodeId())? $this ->PromoCode->setPromoCodeId( WTVRcleanString( $this -> getPromoCodeId()) ) : null;
    ($this -> getPromoCodeType())? $this ->PromoCode->setPromoCodeType( WTVRcleanString( $this -> getPromoCodeType()) ) : null;
          (is_numeric($this ->getPromoCodeValue())) ? $this ->PromoCode->setPromoCodeValue( (float) $this -> getPromoCodeValue() ) : null;
    ($this -> getPromoCodeCode())? $this ->PromoCode->setPromoCodeCode( WTVRcleanString( $this -> getPromoCodeCode()) ) : null;
    ($this -> getFkFilmId())? $this ->PromoCode->setFkFilmId( WTVRcleanString( $this -> getFkFilmId()) ) : null;
    ($this -> getPromoCodeUses())? $this ->PromoCode->setPromoCodeUses( WTVRcleanString( $this -> getPromoCodeUses()) ) : null;
    ($this -> getPromoCodeTotalUsage())? $this ->PromoCode->setPromoCodeTotalUsage( WTVRcleanString( $this -> getPromoCodeTotalUsage()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->PromoCode ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->PromoCode = PromoCodePeer::retrieveByPK($id);
    }
    
    if (! $this ->PromoCode ) {
      return;
    }
    
    $this ->PromoCode -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('PromoCode_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "PromoCodePeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $PromoCode = PromoCodePeer::doSelect($c);
    
    if (count($PromoCode) >= 1) {
      $this ->PromoCode = $PromoCode[0];
      return true;
    } else {
      $this ->PromoCode = new PromoCode();
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
      $name = "PromoCodePeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $PromoCode = PromoCodePeer::doSelect($c);
    
    if (count($PromoCode) >= 1) {
      $this ->PromoCode = $PromoCode[0];
      return true;
    } else {
      $this ->PromoCode = new PromoCode();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>