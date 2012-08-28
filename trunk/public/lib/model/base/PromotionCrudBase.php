<?php
       
   class PromotionCrudBase extends Utils_PageWidget { 
   
    var $Promotion;
   
       var $promotion_id;
   var $promotion_name;
   var $promotion_html;
   var $promotion_start_date;
   var $promotion_end_date;
   var $promotion_duration;
   var $promotion_priority;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getPromotionId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Promotion = PromotionPeer::retrieveByPK( $id );
    } else {
      $this ->Promotion = new Promotion;
    }
  }
  
  function hydrate( $id ) {
      $this ->Promotion = PromotionPeer::retrieveByPK( $id );
  }
  
  function getPromotionId() {
    if (($this ->postVar("promotion_id")) || ($this ->postVar("promotion_id") === "")) {
      return $this ->postVar("promotion_id");
    } elseif (($this ->getVar("promotion_id")) || ($this ->getVar("promotion_id") === "")) {
      return $this ->getVar("promotion_id");
    } elseif (($this ->Promotion) || ($this ->Promotion === "")){
      return $this ->Promotion -> getPromotionId();
    } elseif (($this ->sessionVar("promotion_id")) || ($this ->sessionVar("promotion_id") == "")) {
      return $this ->sessionVar("promotion_id");
    } else {
      return false;
    }
  }
  
  function setPromotionId( $str ) {
    $this ->Promotion -> setPromotionId( $str );
  }
  
  function getPromotionName() {
    if (($this ->postVar("promotion_name")) || ($this ->postVar("promotion_name") === "")) {
      return $this ->postVar("promotion_name");
    } elseif (($this ->getVar("promotion_name")) || ($this ->getVar("promotion_name") === "")) {
      return $this ->getVar("promotion_name");
    } elseif (($this ->Promotion) || ($this ->Promotion === "")){
      return $this ->Promotion -> getPromotionName();
    } elseif (($this ->sessionVar("promotion_name")) || ($this ->sessionVar("promotion_name") == "")) {
      return $this ->sessionVar("promotion_name");
    } else {
      return false;
    }
  }
  
  function setPromotionName( $str ) {
    $this ->Promotion -> setPromotionName( $str );
  }
  
  function getPromotionHtml() {
    if (($this ->postVar("promotion_html")) || ($this ->postVar("promotion_html") === "")) {
      return $this ->postVar("promotion_html");
    } elseif (($this ->getVar("promotion_html")) || ($this ->getVar("promotion_html") === "")) {
      return $this ->getVar("promotion_html");
    } elseif (($this ->Promotion) || ($this ->Promotion === "")){
      return $this ->Promotion -> getPromotionHtml();
    } elseif (($this ->sessionVar("promotion_html")) || ($this ->sessionVar("promotion_html") == "")) {
      return $this ->sessionVar("promotion_html");
    } else {
      return false;
    }
  }
  
  function setPromotionHtml( $str ) {
    $this ->Promotion -> setPromotionHtml( $str );
  }
  
  function getPromotionStartDate() {
    if (($this ->postVar("promotion_start_date")) || ($this ->postVar("promotion_start_date") === "")) {
      return $this ->postVar("promotion_start_date");
    } elseif (($this ->getVar("promotion_start_date")) || ($this ->getVar("promotion_start_date") === "")) {
      return $this ->getVar("promotion_start_date");
    } elseif (($this ->Promotion) || ($this ->Promotion === "")){
      return $this ->Promotion -> getPromotionStartDate();
    } elseif (($this ->sessionVar("promotion_start_date")) || ($this ->sessionVar("promotion_start_date") == "")) {
      return $this ->sessionVar("promotion_start_date");
    } else {
      return false;
    }
  }
  
  function setPromotionStartDate( $str ) {
    $this ->Promotion -> setPromotionStartDate( $str );
  }
  
  function getPromotionEndDate() {
    if (($this ->postVar("promotion_end_date")) || ($this ->postVar("promotion_end_date") === "")) {
      return $this ->postVar("promotion_end_date");
    } elseif (($this ->getVar("promotion_end_date")) || ($this ->getVar("promotion_end_date") === "")) {
      return $this ->getVar("promotion_end_date");
    } elseif (($this ->Promotion) || ($this ->Promotion === "")){
      return $this ->Promotion -> getPromotionEndDate();
    } elseif (($this ->sessionVar("promotion_end_date")) || ($this ->sessionVar("promotion_end_date") == "")) {
      return $this ->sessionVar("promotion_end_date");
    } else {
      return false;
    }
  }
  
  function setPromotionEndDate( $str ) {
    $this ->Promotion -> setPromotionEndDate( $str );
  }
  
  function getPromotionDuration() {
    if (($this ->postVar("promotion_duration")) || ($this ->postVar("promotion_duration") === "")) {
      return $this ->postVar("promotion_duration");
    } elseif (($this ->getVar("promotion_duration")) || ($this ->getVar("promotion_duration") === "")) {
      return $this ->getVar("promotion_duration");
    } elseif (($this ->Promotion) || ($this ->Promotion === "")){
      return $this ->Promotion -> getPromotionDuration();
    } elseif (($this ->sessionVar("promotion_duration")) || ($this ->sessionVar("promotion_duration") == "")) {
      return $this ->sessionVar("promotion_duration");
    } else {
      return false;
    }
  }
  
  function setPromotionDuration( $str ) {
    $this ->Promotion -> setPromotionDuration( $str );
  }
  
  function getPromotionPriority() {
    if (($this ->postVar("promotion_priority")) || ($this ->postVar("promotion_priority") === "")) {
      return $this ->postVar("promotion_priority");
    } elseif (($this ->getVar("promotion_priority")) || ($this ->getVar("promotion_priority") === "")) {
      return $this ->getVar("promotion_priority");
    } elseif (($this ->Promotion) || ($this ->Promotion === "")){
      return $this ->Promotion -> getPromotionPriority();
    } elseif (($this ->sessionVar("promotion_priority")) || ($this ->sessionVar("promotion_priority") == "")) {
      return $this ->sessionVar("promotion_priority");
    } else {
      return false;
    }
  }
  
  function setPromotionPriority( $str ) {
    $this ->Promotion -> setPromotionPriority( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Promotion = PromotionPeer::retrieveByPK( $id );
    }
    
    if ($this ->Promotion ) {
       
    	       (is_numeric(WTVRcleanString($this ->Promotion->getPromotionId()))) ? $itemarray["promotion_id"] = WTVRcleanString($this ->Promotion->getPromotionId()) : null;
          (WTVRcleanString($this ->Promotion->getPromotionName())) ? $itemarray["promotion_name"] = WTVRcleanString($this ->Promotion->getPromotionName()) : null;
          (WTVRcleanString($this ->Promotion->getPromotionHtml())) ? $itemarray["promotion_html"] = WTVRcleanString($this ->Promotion->getPromotionHtml()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Promotion->getPromotionStartDate())) ? $itemarray["promotion_start_date"] = formatDate($this ->Promotion->getPromotionStartDate('%Y-%m-%d %T'),"TS") : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Promotion->getPromotionEndDate())) ? $itemarray["promotion_end_date"] = formatDate($this ->Promotion->getPromotionEndDate('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->Promotion->getPromotionDuration())) ? $itemarray["promotion_duration"] = WTVRcleanString($this ->Promotion->getPromotionDuration()) : null;
          (is_numeric(WTVRcleanString($this ->Promotion->getPromotionPriority()))) ? $itemarray["promotion_priority"] = WTVRcleanString($this ->Promotion->getPromotionPriority()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Promotion = PromotionPeer::retrieveByPK( $id );
    } elseif (! $this ->Promotion) {
      $this ->Promotion = new Promotion;
    }
        
  	 ($this -> getPromotionId())? $this ->Promotion->setPromotionId( WTVRcleanString( $this -> getPromotionId()) ) : null;
    ($this -> getPromotionName())? $this ->Promotion->setPromotionName( WTVRcleanString( $this -> getPromotionName()) ) : null;
    ($this -> getPromotionHtml())? $this ->Promotion->setPromotionHtml( WTVRcleanString( $this -> getPromotionHtml()) ) : null;
          if (is_valid_date( $this ->Promotion->getPromotionStartDate())) {
        $this ->Promotion->setPromotionStartDate( formatDate($this -> getPromotionStartDate(), "TS" ));
      } else {
      $Promotionpromotion_start_date = $this -> sfDateTime( "promotion_start_date" );
      ( $Promotionpromotion_start_date != "01/01/1900 00:00:00" )? $this ->Promotion->setPromotionStartDate( formatDate($Promotionpromotion_start_date, "TS" )) : $this ->Promotion->setPromotionStartDate( null );
      }
          if (is_valid_date( $this ->Promotion->getPromotionEndDate())) {
        $this ->Promotion->setPromotionEndDate( formatDate($this -> getPromotionEndDate(), "TS" ));
      } else {
      $Promotionpromotion_end_date = $this -> sfDateTime( "promotion_end_date" );
      ( $Promotionpromotion_end_date != "01/01/1900 00:00:00" )? $this ->Promotion->setPromotionEndDate( formatDate($Promotionpromotion_end_date, "TS" )) : $this ->Promotion->setPromotionEndDate( null );
      }
    ($this -> getPromotionDuration())? $this ->Promotion->setPromotionDuration( WTVRcleanString( $this -> getPromotionDuration()) ) : null;
    ($this -> getPromotionPriority())? $this ->Promotion->setPromotionPriority( WTVRcleanString( $this -> getPromotionPriority()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Promotion ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Promotion = PromotionPeer::retrieveByPK($id);
    }
    
    if (! $this ->Promotion ) {
      return;
    }
    
    $this ->Promotion -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Promotion_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "PromotionPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Promotion = PromotionPeer::doSelect($c);
    
    if (count($Promotion) >= 1) {
      $this ->Promotion = $Promotion[0];
      return true;
    } else {
      $this ->Promotion = new Promotion();
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
      $name = "PromotionPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Promotion = PromotionPeer::doSelect($c);
    
    if (count($Promotion) >= 1) {
      $this ->Promotion = $Promotion[0];
      return true;
    } else {
      $this ->Promotion = new Promotion();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>