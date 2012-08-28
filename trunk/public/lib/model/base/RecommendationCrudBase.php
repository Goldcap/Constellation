<?php
       
   class RecommendationCrudBase extends Utils_PageWidget { 
   
    var $Recommendation;
   
       var $recommendation_id;
   var $fk_screening_id;
   var $fk_audience_id;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getRecommendationId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Recommendation = RecommendationPeer::retrieveByPK( $id );
    } else {
      $this ->Recommendation = new Recommendation;
    }
  }
  
  function hydrate( $id ) {
      $this ->Recommendation = RecommendationPeer::retrieveByPK( $id );
  }
  
  function getRecommendationId() {
    if (($this ->postVar("recommendation_id")) || ($this ->postVar("recommendation_id") === "")) {
      return $this ->postVar("recommendation_id");
    } elseif (($this ->getVar("recommendation_id")) || ($this ->getVar("recommendation_id") === "")) {
      return $this ->getVar("recommendation_id");
    } elseif (($this ->Recommendation) || ($this ->Recommendation === "")){
      return $this ->Recommendation -> getRecommendationId();
    } elseif (($this ->sessionVar("recommendation_id")) || ($this ->sessionVar("recommendation_id") == "")) {
      return $this ->sessionVar("recommendation_id");
    } else {
      return false;
    }
  }
  
  function setRecommendationId( $str ) {
    $this ->Recommendation -> setRecommendationId( $str );
  }
  
  function getFkScreeningId() {
    if (($this ->postVar("fk_screening_id")) || ($this ->postVar("fk_screening_id") === "")) {
      return $this ->postVar("fk_screening_id");
    } elseif (($this ->getVar("fk_screening_id")) || ($this ->getVar("fk_screening_id") === "")) {
      return $this ->getVar("fk_screening_id");
    } elseif (($this ->Recommendation) || ($this ->Recommendation === "")){
      return $this ->Recommendation -> getFkScreeningId();
    } elseif (($this ->sessionVar("fk_screening_id")) || ($this ->sessionVar("fk_screening_id") == "")) {
      return $this ->sessionVar("fk_screening_id");
    } else {
      return false;
    }
  }
  
  function setFkScreeningId( $str ) {
    $this ->Recommendation -> setFkScreeningId( $str );
  }
  
  function getFkAudienceId() {
    if (($this ->postVar("fk_audience_id")) || ($this ->postVar("fk_audience_id") === "")) {
      return $this ->postVar("fk_audience_id");
    } elseif (($this ->getVar("fk_audience_id")) || ($this ->getVar("fk_audience_id") === "")) {
      return $this ->getVar("fk_audience_id");
    } elseif (($this ->Recommendation) || ($this ->Recommendation === "")){
      return $this ->Recommendation -> getFkAudienceId();
    } elseif (($this ->sessionVar("fk_audience_id")) || ($this ->sessionVar("fk_audience_id") == "")) {
      return $this ->sessionVar("fk_audience_id");
    } else {
      return false;
    }
  }
  
  function setFkAudienceId( $str ) {
    $this ->Recommendation -> setFkAudienceId( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Recommendation = RecommendationPeer::retrieveByPK( $id );
    }
    
    if ($this ->Recommendation ) {
       
    	       (is_numeric(WTVRcleanString($this ->Recommendation->getRecommendationId()))) ? $itemarray["recommendation_id"] = WTVRcleanString($this ->Recommendation->getRecommendationId()) : null;
          (is_numeric(WTVRcleanString($this ->Recommendation->getFkScreeningId()))) ? $itemarray["fk_screening_id"] = WTVRcleanString($this ->Recommendation->getFkScreeningId()) : null;
          (is_numeric(WTVRcleanString($this ->Recommendation->getFkAudienceId()))) ? $itemarray["fk_audience_id"] = WTVRcleanString($this ->Recommendation->getFkAudienceId()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Recommendation = RecommendationPeer::retrieveByPK( $id );
    } elseif (! $this ->Recommendation) {
      $this ->Recommendation = new Recommendation;
    }
        
  	 ($this -> getRecommendationId())? $this ->Recommendation->setRecommendationId( WTVRcleanString( $this -> getRecommendationId()) ) : null;
    ($this -> getFkScreeningId())? $this ->Recommendation->setFkScreeningId( WTVRcleanString( $this -> getFkScreeningId()) ) : null;
    ($this -> getFkAudienceId())? $this ->Recommendation->setFkAudienceId( WTVRcleanString( $this -> getFkAudienceId()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Recommendation ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Recommendation = RecommendationPeer::retrieveByPK($id);
    }
    
    if (! $this ->Recommendation ) {
      return;
    }
    
    $this ->Recommendation -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Recommendation_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "RecommendationPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Recommendation = RecommendationPeer::doSelect($c);
    
    if (count($Recommendation) >= 1) {
      $this ->Recommendation = $Recommendation[0];
      return true;
    } else {
      $this ->Recommendation = new Recommendation();
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
      $name = "RecommendationPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Recommendation = RecommendationPeer::doSelect($c);
    
    if (count($Recommendation) >= 1) {
      $this ->Recommendation = $Recommendation[0];
      return true;
    } else {
      $this ->Recommendation = new Recommendation();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>