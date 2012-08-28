<?php
       
   class ClickActionCrudBase extends Utils_PageWidget { 
   
    var $ClickAction;
   
       var $click_action_id;
   var $fk_user_id;
   var $fk_film_id;
   var $fk_screening_id;
   var $fk_payment_id;
   var $fk_click_guid;
   var $fk_click_id;
   var $click_action_date;
   var $fk_click_action_type;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getClickActionId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->ClickAction = ClickActionPeer::retrieveByPK( $id );
    } else {
      $this ->ClickAction = new ClickAction;
    }
  }
  
  function hydrate( $id ) {
      $this ->ClickAction = ClickActionPeer::retrieveByPK( $id );
  }
  
  function getClickActionId() {
    if (($this ->postVar("click_action_id")) || ($this ->postVar("click_action_id") === "")) {
      return $this ->postVar("click_action_id");
    } elseif (($this ->getVar("click_action_id")) || ($this ->getVar("click_action_id") === "")) {
      return $this ->getVar("click_action_id");
    } elseif (($this ->ClickAction) || ($this ->ClickAction === "")){
      return $this ->ClickAction -> getClickActionId();
    } elseif (($this ->sessionVar("click_action_id")) || ($this ->sessionVar("click_action_id") == "")) {
      return $this ->sessionVar("click_action_id");
    } else {
      return false;
    }
  }
  
  function setClickActionId( $str ) {
    $this ->ClickAction -> setClickActionId( $str );
  }
  
  function getFkUserId() {
    if (($this ->postVar("fk_user_id")) || ($this ->postVar("fk_user_id") === "")) {
      return $this ->postVar("fk_user_id");
    } elseif (($this ->getVar("fk_user_id")) || ($this ->getVar("fk_user_id") === "")) {
      return $this ->getVar("fk_user_id");
    } elseif (($this ->ClickAction) || ($this ->ClickAction === "")){
      return $this ->ClickAction -> getFkUserId();
    } elseif (($this ->sessionVar("fk_user_id")) || ($this ->sessionVar("fk_user_id") == "")) {
      return $this ->sessionVar("fk_user_id");
    } else {
      return false;
    }
  }
  
  function setFkUserId( $str ) {
    $this ->ClickAction -> setFkUserId( $str );
  }
  
  function getFkFilmId() {
    if (($this ->postVar("fk_film_id")) || ($this ->postVar("fk_film_id") === "")) {
      return $this ->postVar("fk_film_id");
    } elseif (($this ->getVar("fk_film_id")) || ($this ->getVar("fk_film_id") === "")) {
      return $this ->getVar("fk_film_id");
    } elseif (($this ->ClickAction) || ($this ->ClickAction === "")){
      return $this ->ClickAction -> getFkFilmId();
    } elseif (($this ->sessionVar("fk_film_id")) || ($this ->sessionVar("fk_film_id") == "")) {
      return $this ->sessionVar("fk_film_id");
    } else {
      return false;
    }
  }
  
  function setFkFilmId( $str ) {
    $this ->ClickAction -> setFkFilmId( $str );
  }
  
  function getFkScreeningId() {
    if (($this ->postVar("fk_screening_id")) || ($this ->postVar("fk_screening_id") === "")) {
      return $this ->postVar("fk_screening_id");
    } elseif (($this ->getVar("fk_screening_id")) || ($this ->getVar("fk_screening_id") === "")) {
      return $this ->getVar("fk_screening_id");
    } elseif (($this ->ClickAction) || ($this ->ClickAction === "")){
      return $this ->ClickAction -> getFkScreeningId();
    } elseif (($this ->sessionVar("fk_screening_id")) || ($this ->sessionVar("fk_screening_id") == "")) {
      return $this ->sessionVar("fk_screening_id");
    } else {
      return false;
    }
  }
  
  function setFkScreeningId( $str ) {
    $this ->ClickAction -> setFkScreeningId( $str );
  }
  
  function getFkPaymentId() {
    if (($this ->postVar("fk_payment_id")) || ($this ->postVar("fk_payment_id") === "")) {
      return $this ->postVar("fk_payment_id");
    } elseif (($this ->getVar("fk_payment_id")) || ($this ->getVar("fk_payment_id") === "")) {
      return $this ->getVar("fk_payment_id");
    } elseif (($this ->ClickAction) || ($this ->ClickAction === "")){
      return $this ->ClickAction -> getFkPaymentId();
    } elseif (($this ->sessionVar("fk_payment_id")) || ($this ->sessionVar("fk_payment_id") == "")) {
      return $this ->sessionVar("fk_payment_id");
    } else {
      return false;
    }
  }
  
  function setFkPaymentId( $str ) {
    $this ->ClickAction -> setFkPaymentId( $str );
  }
  
  function getFkClickGuid() {
    if (($this ->postVar("fk_click_guid")) || ($this ->postVar("fk_click_guid") === "")) {
      return $this ->postVar("fk_click_guid");
    } elseif (($this ->getVar("fk_click_guid")) || ($this ->getVar("fk_click_guid") === "")) {
      return $this ->getVar("fk_click_guid");
    } elseif (($this ->ClickAction) || ($this ->ClickAction === "")){
      return $this ->ClickAction -> getFkClickGuid();
    } elseif (($this ->sessionVar("fk_click_guid")) || ($this ->sessionVar("fk_click_guid") == "")) {
      return $this ->sessionVar("fk_click_guid");
    } else {
      return false;
    }
  }
  
  function setFkClickGuid( $str ) {
    $this ->ClickAction -> setFkClickGuid( $str );
  }
  
  function getFkClickId() {
    if (($this ->postVar("fk_click_id")) || ($this ->postVar("fk_click_id") === "")) {
      return $this ->postVar("fk_click_id");
    } elseif (($this ->getVar("fk_click_id")) || ($this ->getVar("fk_click_id") === "")) {
      return $this ->getVar("fk_click_id");
    } elseif (($this ->ClickAction) || ($this ->ClickAction === "")){
      return $this ->ClickAction -> getFkClickId();
    } elseif (($this ->sessionVar("fk_click_id")) || ($this ->sessionVar("fk_click_id") == "")) {
      return $this ->sessionVar("fk_click_id");
    } else {
      return false;
    }
  }
  
  function setFkClickId( $str ) {
    $this ->ClickAction -> setFkClickId( $str );
  }
  
  function getClickActionDate() {
    if (($this ->postVar("click_action_date")) || ($this ->postVar("click_action_date") === "")) {
      return $this ->postVar("click_action_date");
    } elseif (($this ->getVar("click_action_date")) || ($this ->getVar("click_action_date") === "")) {
      return $this ->getVar("click_action_date");
    } elseif (($this ->ClickAction) || ($this ->ClickAction === "")){
      return $this ->ClickAction -> getClickActionDate();
    } elseif (($this ->sessionVar("click_action_date")) || ($this ->sessionVar("click_action_date") == "")) {
      return $this ->sessionVar("click_action_date");
    } else {
      return false;
    }
  }
  
  function setClickActionDate( $str ) {
    $this ->ClickAction -> setClickActionDate( $str );
  }
  
  function getFkClickActionType() {
    if (($this ->postVar("fk_click_action_type")) || ($this ->postVar("fk_click_action_type") === "")) {
      return $this ->postVar("fk_click_action_type");
    } elseif (($this ->getVar("fk_click_action_type")) || ($this ->getVar("fk_click_action_type") === "")) {
      return $this ->getVar("fk_click_action_type");
    } elseif (($this ->ClickAction) || ($this ->ClickAction === "")){
      return $this ->ClickAction -> getFkClickActionType();
    } elseif (($this ->sessionVar("fk_click_action_type")) || ($this ->sessionVar("fk_click_action_type") == "")) {
      return $this ->sessionVar("fk_click_action_type");
    } else {
      return false;
    }
  }
  
  function setFkClickActionType( $str ) {
    $this ->ClickAction -> setFkClickActionType( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->ClickAction = ClickActionPeer::retrieveByPK( $id );
    }
    
    if ($this ->ClickAction ) {
       
    	       (is_numeric(WTVRcleanString($this ->ClickAction->getClickActionId()))) ? $itemarray["click_action_id"] = WTVRcleanString($this ->ClickAction->getClickActionId()) : null;
          (is_numeric(WTVRcleanString($this ->ClickAction->getFkUserId()))) ? $itemarray["fk_user_id"] = WTVRcleanString($this ->ClickAction->getFkUserId()) : null;
          (is_numeric(WTVRcleanString($this ->ClickAction->getFkFilmId()))) ? $itemarray["fk_film_id"] = WTVRcleanString($this ->ClickAction->getFkFilmId()) : null;
          (is_numeric(WTVRcleanString($this ->ClickAction->getFkScreeningId()))) ? $itemarray["fk_screening_id"] = WTVRcleanString($this ->ClickAction->getFkScreeningId()) : null;
          (is_numeric(WTVRcleanString($this ->ClickAction->getFkPaymentId()))) ? $itemarray["fk_payment_id"] = WTVRcleanString($this ->ClickAction->getFkPaymentId()) : null;
          (WTVRcleanString($this ->ClickAction->getFkClickGuid())) ? $itemarray["fk_click_guid"] = WTVRcleanString($this ->ClickAction->getFkClickGuid()) : null;
          (is_numeric(WTVRcleanString($this ->ClickAction->getFkClickId()))) ? $itemarray["fk_click_id"] = WTVRcleanString($this ->ClickAction->getFkClickId()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->ClickAction->getClickActionDate())) ? $itemarray["click_action_date"] = formatDate($this ->ClickAction->getClickActionDate('%Y-%m-%d %T'),"TS") : null;
          (is_numeric(WTVRcleanString($this ->ClickAction->getFkClickActionType()))) ? $itemarray["fk_click_action_type"] = WTVRcleanString($this ->ClickAction->getFkClickActionType()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->ClickAction = ClickActionPeer::retrieveByPK( $id );
    } elseif (! $this ->ClickAction) {
      $this ->ClickAction = new ClickAction;
    }
        
  	 ($this -> getClickActionId())? $this ->ClickAction->setClickActionId( WTVRcleanString( $this -> getClickActionId()) ) : null;
    ($this -> getFkUserId())? $this ->ClickAction->setFkUserId( WTVRcleanString( $this -> getFkUserId()) ) : null;
    ($this -> getFkFilmId())? $this ->ClickAction->setFkFilmId( WTVRcleanString( $this -> getFkFilmId()) ) : null;
    ($this -> getFkScreeningId())? $this ->ClickAction->setFkScreeningId( WTVRcleanString( $this -> getFkScreeningId()) ) : null;
    ($this -> getFkPaymentId())? $this ->ClickAction->setFkPaymentId( WTVRcleanString( $this -> getFkPaymentId()) ) : null;
    ($this -> getFkClickGuid())? $this ->ClickAction->setFkClickGuid( WTVRcleanString( $this -> getFkClickGuid()) ) : null;
    ($this -> getFkClickId())? $this ->ClickAction->setFkClickId( WTVRcleanString( $this -> getFkClickId()) ) : null;
          if (is_valid_date( $this ->ClickAction->getClickActionDate())) {
        $this ->ClickAction->setClickActionDate( formatDate($this -> getClickActionDate(), "TS" ));
      } else {
      $ClickActionclick_action_date = $this -> sfDateTime( "click_action_date" );
      ( $ClickActionclick_action_date != "01/01/1900 00:00:00" )? $this ->ClickAction->setClickActionDate( formatDate($ClickActionclick_action_date, "TS" )) : $this ->ClickAction->setClickActionDate( null );
      }
    ($this -> getFkClickActionType())? $this ->ClickAction->setFkClickActionType( WTVRcleanString( $this -> getFkClickActionType()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->ClickAction ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->ClickAction = ClickActionPeer::retrieveByPK($id);
    }
    
    if (! $this ->ClickAction ) {
      return;
    }
    
    $this ->ClickAction -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('ClickAction_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "ClickActionPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $ClickAction = ClickActionPeer::doSelect($c);
    
    if (count($ClickAction) >= 1) {
      $this ->ClickAction = $ClickAction[0];
      return true;
    } else {
      $this ->ClickAction = new ClickAction();
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
      $name = "ClickActionPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $ClickAction = ClickActionPeer::doSelect($c);
    
    if (count($ClickAction) >= 1) {
      $this ->ClickAction = $ClickAction[0];
      return true;
    } else {
      $this ->ClickAction = new ClickAction();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>