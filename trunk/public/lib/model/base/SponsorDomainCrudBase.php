<?php
       
   class SponsorDomainCrudBase extends Utils_PageWidget { 
   
    var $SponsorDomain;
   
       var $sponsor_domain_id;
   var $fk_sponsor_id;
   var $fk_film_id;
   var $sponsor_domain_domain;
   var $sponsor_domain_template;
   var $sponsor_domain_css;
   var $sponsor_domain_host_image;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getSponsorDomainId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->SponsorDomain = SponsorDomainPeer::retrieveByPK( $id );
    } else {
      $this ->SponsorDomain = new SponsorDomain;
    }
  }
  
  function hydrate( $id ) {
      $this ->SponsorDomain = SponsorDomainPeer::retrieveByPK( $id );
  }
  
  function getSponsorDomainId() {
    if (($this ->postVar("sponsor_domain_id")) || ($this ->postVar("sponsor_domain_id") === "")) {
      return $this ->postVar("sponsor_domain_id");
    } elseif (($this ->getVar("sponsor_domain_id")) || ($this ->getVar("sponsor_domain_id") === "")) {
      return $this ->getVar("sponsor_domain_id");
    } elseif (($this ->SponsorDomain) || ($this ->SponsorDomain === "")){
      return $this ->SponsorDomain -> getSponsorDomainId();
    } elseif (($this ->sessionVar("sponsor_domain_id")) || ($this ->sessionVar("sponsor_domain_id") == "")) {
      return $this ->sessionVar("sponsor_domain_id");
    } else {
      return false;
    }
  }
  
  function setSponsorDomainId( $str ) {
    $this ->SponsorDomain -> setSponsorDomainId( $str );
  }
  
  function getFkSponsorId() {
    if (($this ->postVar("fk_sponsor_id")) || ($this ->postVar("fk_sponsor_id") === "")) {
      return $this ->postVar("fk_sponsor_id");
    } elseif (($this ->getVar("fk_sponsor_id")) || ($this ->getVar("fk_sponsor_id") === "")) {
      return $this ->getVar("fk_sponsor_id");
    } elseif (($this ->SponsorDomain) || ($this ->SponsorDomain === "")){
      return $this ->SponsorDomain -> getFkSponsorId();
    } elseif (($this ->sessionVar("fk_sponsor_id")) || ($this ->sessionVar("fk_sponsor_id") == "")) {
      return $this ->sessionVar("fk_sponsor_id");
    } else {
      return false;
    }
  }
  
  function setFkSponsorId( $str ) {
    $this ->SponsorDomain -> setFkSponsorId( $str );
  }
  
  function getFkFilmId() {
    if (($this ->postVar("fk_film_id")) || ($this ->postVar("fk_film_id") === "")) {
      return $this ->postVar("fk_film_id");
    } elseif (($this ->getVar("fk_film_id")) || ($this ->getVar("fk_film_id") === "")) {
      return $this ->getVar("fk_film_id");
    } elseif (($this ->SponsorDomain) || ($this ->SponsorDomain === "")){
      return $this ->SponsorDomain -> getFkFilmId();
    } elseif (($this ->sessionVar("fk_film_id")) || ($this ->sessionVar("fk_film_id") == "")) {
      return $this ->sessionVar("fk_film_id");
    } else {
      return false;
    }
  }
  
  function setFkFilmId( $str ) {
    $this ->SponsorDomain -> setFkFilmId( $str );
  }
  
  function getSponsorDomainDomain() {
    if (($this ->postVar("sponsor_domain_domain")) || ($this ->postVar("sponsor_domain_domain") === "")) {
      return $this ->postVar("sponsor_domain_domain");
    } elseif (($this ->getVar("sponsor_domain_domain")) || ($this ->getVar("sponsor_domain_domain") === "")) {
      return $this ->getVar("sponsor_domain_domain");
    } elseif (($this ->SponsorDomain) || ($this ->SponsorDomain === "")){
      return $this ->SponsorDomain -> getSponsorDomainDomain();
    } elseif (($this ->sessionVar("sponsor_domain_domain")) || ($this ->sessionVar("sponsor_domain_domain") == "")) {
      return $this ->sessionVar("sponsor_domain_domain");
    } else {
      return false;
    }
  }
  
  function setSponsorDomainDomain( $str ) {
    $this ->SponsorDomain -> setSponsorDomainDomain( $str );
  }
  
  function getSponsorDomainTemplate() {
    if (($this ->postVar("sponsor_domain_template")) || ($this ->postVar("sponsor_domain_template") === "")) {
      return $this ->postVar("sponsor_domain_template");
    } elseif (($this ->getVar("sponsor_domain_template")) || ($this ->getVar("sponsor_domain_template") === "")) {
      return $this ->getVar("sponsor_domain_template");
    } elseif (($this ->SponsorDomain) || ($this ->SponsorDomain === "")){
      return $this ->SponsorDomain -> getSponsorDomainTemplate();
    } elseif (($this ->sessionVar("sponsor_domain_template")) || ($this ->sessionVar("sponsor_domain_template") == "")) {
      return $this ->sessionVar("sponsor_domain_template");
    } else {
      return false;
    }
  }
  
  function setSponsorDomainTemplate( $str ) {
    $this ->SponsorDomain -> setSponsorDomainTemplate( $str );
  }
  
  function getSponsorDomainCss() {
    if (($this ->postVar("sponsor_domain_css")) || ($this ->postVar("sponsor_domain_css") === "")) {
      return $this ->postVar("sponsor_domain_css");
    } elseif (($this ->getVar("sponsor_domain_css")) || ($this ->getVar("sponsor_domain_css") === "")) {
      return $this ->getVar("sponsor_domain_css");
    } elseif (($this ->SponsorDomain) || ($this ->SponsorDomain === "")){
      return $this ->SponsorDomain -> getSponsorDomainCss();
    } elseif (($this ->sessionVar("sponsor_domain_css")) || ($this ->sessionVar("sponsor_domain_css") == "")) {
      return $this ->sessionVar("sponsor_domain_css");
    } else {
      return false;
    }
  }
  
  function setSponsorDomainCss( $str ) {
    $this ->SponsorDomain -> setSponsorDomainCss( $str );
  }
  
  function getSponsorDomainHostImage() {
    if (($this ->postVar("sponsor_domain_host_image")) || ($this ->postVar("sponsor_domain_host_image") === "")) {
      return $this ->postVar("sponsor_domain_host_image");
    } elseif (($this ->getVar("sponsor_domain_host_image")) || ($this ->getVar("sponsor_domain_host_image") === "")) {
      return $this ->getVar("sponsor_domain_host_image");
    } elseif (($this ->SponsorDomain) || ($this ->SponsorDomain === "")){
      return $this ->SponsorDomain -> getSponsorDomainHostImage();
    } elseif (($this ->sessionVar("sponsor_domain_host_image")) || ($this ->sessionVar("sponsor_domain_host_image") == "")) {
      return $this ->sessionVar("sponsor_domain_host_image");
    } else {
      return false;
    }
  }
  
  function setSponsorDomainHostImage( $str ) {
    $this ->SponsorDomain -> setSponsorDomainHostImage( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->SponsorDomain = SponsorDomainPeer::retrieveByPK( $id );
    }
    
    if ($this ->SponsorDomain ) {
       
    	       (is_numeric(WTVRcleanString($this ->SponsorDomain->getSponsorDomainId()))) ? $itemarray["sponsor_domain_id"] = WTVRcleanString($this ->SponsorDomain->getSponsorDomainId()) : null;
          (is_numeric(WTVRcleanString($this ->SponsorDomain->getFkSponsorId()))) ? $itemarray["fk_sponsor_id"] = WTVRcleanString($this ->SponsorDomain->getFkSponsorId()) : null;
          (is_numeric(WTVRcleanString($this ->SponsorDomain->getFkFilmId()))) ? $itemarray["fk_film_id"] = WTVRcleanString($this ->SponsorDomain->getFkFilmId()) : null;
          (WTVRcleanString($this ->SponsorDomain->getSponsorDomainDomain())) ? $itemarray["sponsor_domain_domain"] = WTVRcleanString($this ->SponsorDomain->getSponsorDomainDomain()) : null;
          (is_numeric(WTVRcleanString($this ->SponsorDomain->getSponsorDomainTemplate()))) ? $itemarray["sponsor_domain_template"] = WTVRcleanString($this ->SponsorDomain->getSponsorDomainTemplate()) : null;
          (is_numeric(WTVRcleanString($this ->SponsorDomain->getSponsorDomainCss()))) ? $itemarray["sponsor_domain_css"] = WTVRcleanString($this ->SponsorDomain->getSponsorDomainCss()) : null;
          (is_numeric(WTVRcleanString($this ->SponsorDomain->getSponsorDomainHostImage()))) ? $itemarray["sponsor_domain_host_image"] = WTVRcleanString($this ->SponsorDomain->getSponsorDomainHostImage()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->SponsorDomain = SponsorDomainPeer::retrieveByPK( $id );
    } elseif (! $this ->SponsorDomain) {
      $this ->SponsorDomain = new SponsorDomain;
    }
        
  	 ($this -> getSponsorDomainId())? $this ->SponsorDomain->setSponsorDomainId( WTVRcleanString( $this -> getSponsorDomainId()) ) : null;
    ($this -> getFkSponsorId())? $this ->SponsorDomain->setFkSponsorId( WTVRcleanString( $this -> getFkSponsorId()) ) : null;
    ($this -> getFkFilmId())? $this ->SponsorDomain->setFkFilmId( WTVRcleanString( $this -> getFkFilmId()) ) : null;
    ($this -> getSponsorDomainDomain())? $this ->SponsorDomain->setSponsorDomainDomain( WTVRcleanString( $this -> getSponsorDomainDomain()) ) : null;
    ($this -> getSponsorDomainTemplate())? $this ->SponsorDomain->setSponsorDomainTemplate( WTVRcleanString( $this -> getSponsorDomainTemplate()) ) : null;
    ($this -> getSponsorDomainCss())? $this ->SponsorDomain->setSponsorDomainCss( WTVRcleanString( $this -> getSponsorDomainCss()) ) : null;
    ($this -> getSponsorDomainHostImage())? $this ->SponsorDomain->setSponsorDomainHostImage( WTVRcleanString( $this -> getSponsorDomainHostImage()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->SponsorDomain ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->SponsorDomain = SponsorDomainPeer::retrieveByPK($id);
    }
    
    if (! $this ->SponsorDomain ) {
      return;
    }
    
    $this ->SponsorDomain -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('SponsorDomain_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "SponsorDomainPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $SponsorDomain = SponsorDomainPeer::doSelect($c);
    
    if (count($SponsorDomain) >= 1) {
      $this ->SponsorDomain = $SponsorDomain[0];
      return true;
    } else {
      $this ->SponsorDomain = new SponsorDomain();
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
      $name = "SponsorDomainPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $SponsorDomain = SponsorDomainPeer::doSelect($c);
    
    if (count($SponsorDomain) >= 1) {
      $this ->SponsorDomain = $SponsorDomain[0];
      return true;
    } else {
      $this ->SponsorDomain = new SponsorDomain();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>