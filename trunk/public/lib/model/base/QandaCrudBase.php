<?php
       
   class QandaCrudBase extends Utils_PageWidget { 
   
    var $Qanda;
   
       var $qanda_id;
   var $fk_screening_id;
   var $fk_user_id;
   var $qanda_body;
   var $qanda_response;
   var $qanda_answered;
   var $qanda_sequence;
   var $qanda_current;
   var $qanda_created_at;
   var $qanda_updated_at;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getQandaId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Qanda = QandaPeer::retrieveByPK( $id );
    } else {
      $this ->Qanda = new Qanda;
    }
  }
  
  function hydrate( $id ) {
      $this ->Qanda = QandaPeer::retrieveByPK( $id );
  }
  
  function getQandaId() {
    if (($this ->postVar("qanda_id")) || ($this ->postVar("qanda_id") === "")) {
      return $this ->postVar("qanda_id");
    } elseif (($this ->getVar("qanda_id")) || ($this ->getVar("qanda_id") === "")) {
      return $this ->getVar("qanda_id");
    } elseif (($this ->Qanda) || ($this ->Qanda === "")){
      return $this ->Qanda -> getQandaId();
    } elseif (($this ->sessionVar("qanda_id")) || ($this ->sessionVar("qanda_id") == "")) {
      return $this ->sessionVar("qanda_id");
    } else {
      return false;
    }
  }
  
  function setQandaId( $str ) {
    $this ->Qanda -> setQandaId( $str );
  }
  
  function getFkScreeningId() {
    if (($this ->postVar("fk_screening_id")) || ($this ->postVar("fk_screening_id") === "")) {
      return $this ->postVar("fk_screening_id");
    } elseif (($this ->getVar("fk_screening_id")) || ($this ->getVar("fk_screening_id") === "")) {
      return $this ->getVar("fk_screening_id");
    } elseif (($this ->Qanda) || ($this ->Qanda === "")){
      return $this ->Qanda -> getFkScreeningId();
    } elseif (($this ->sessionVar("fk_screening_id")) || ($this ->sessionVar("fk_screening_id") == "")) {
      return $this ->sessionVar("fk_screening_id");
    } else {
      return false;
    }
  }
  
  function setFkScreeningId( $str ) {
    $this ->Qanda -> setFkScreeningId( $str );
  }
  
  function getFkUserId() {
    if (($this ->postVar("fk_user_id")) || ($this ->postVar("fk_user_id") === "")) {
      return $this ->postVar("fk_user_id");
    } elseif (($this ->getVar("fk_user_id")) || ($this ->getVar("fk_user_id") === "")) {
      return $this ->getVar("fk_user_id");
    } elseif (($this ->Qanda) || ($this ->Qanda === "")){
      return $this ->Qanda -> getFkUserId();
    } elseif (($this ->sessionVar("fk_user_id")) || ($this ->sessionVar("fk_user_id") == "")) {
      return $this ->sessionVar("fk_user_id");
    } else {
      return false;
    }
  }
  
  function setFkUserId( $str ) {
    $this ->Qanda -> setFkUserId( $str );
  }
  
  function getQandaBody() {
    if (($this ->postVar("qanda_body")) || ($this ->postVar("qanda_body") === "")) {
      return $this ->postVar("qanda_body");
    } elseif (($this ->getVar("qanda_body")) || ($this ->getVar("qanda_body") === "")) {
      return $this ->getVar("qanda_body");
    } elseif (($this ->Qanda) || ($this ->Qanda === "")){
      return $this ->Qanda -> getQandaBody();
    } elseif (($this ->sessionVar("qanda_body")) || ($this ->sessionVar("qanda_body") == "")) {
      return $this ->sessionVar("qanda_body");
    } else {
      return false;
    }
  }
  
  function setQandaBody( $str ) {
    $this ->Qanda -> setQandaBody( $str );
  }
  
  function getQandaResponse() {
    if (($this ->postVar("qanda_response")) || ($this ->postVar("qanda_response") === "")) {
      return $this ->postVar("qanda_response");
    } elseif (($this ->getVar("qanda_response")) || ($this ->getVar("qanda_response") === "")) {
      return $this ->getVar("qanda_response");
    } elseif (($this ->Qanda) || ($this ->Qanda === "")){
      return $this ->Qanda -> getQandaResponse();
    } elseif (($this ->sessionVar("qanda_response")) || ($this ->sessionVar("qanda_response") == "")) {
      return $this ->sessionVar("qanda_response");
    } else {
      return false;
    }
  }
  
  function setQandaResponse( $str ) {
    $this ->Qanda -> setQandaResponse( $str );
  }
  
  function getQandaAnswered() {
    if (($this ->postVar("qanda_answered")) || ($this ->postVar("qanda_answered") === "")) {
      return $this ->postVar("qanda_answered");
    } elseif (($this ->getVar("qanda_answered")) || ($this ->getVar("qanda_answered") === "")) {
      return $this ->getVar("qanda_answered");
    } elseif (($this ->Qanda) || ($this ->Qanda === "")){
      return $this ->Qanda -> getQandaAnswered();
    } elseif (($this ->sessionVar("qanda_answered")) || ($this ->sessionVar("qanda_answered") == "")) {
      return $this ->sessionVar("qanda_answered");
    } else {
      return false;
    }
  }
  
  function setQandaAnswered( $str ) {
    $this ->Qanda -> setQandaAnswered( $str );
  }
  
  function getQandaSequence() {
    if (($this ->postVar("qanda_sequence")) || ($this ->postVar("qanda_sequence") === "")) {
      return $this ->postVar("qanda_sequence");
    } elseif (($this ->getVar("qanda_sequence")) || ($this ->getVar("qanda_sequence") === "")) {
      return $this ->getVar("qanda_sequence");
    } elseif (($this ->Qanda) || ($this ->Qanda === "")){
      return $this ->Qanda -> getQandaSequence();
    } elseif (($this ->sessionVar("qanda_sequence")) || ($this ->sessionVar("qanda_sequence") == "")) {
      return $this ->sessionVar("qanda_sequence");
    } else {
      return false;
    }
  }
  
  function setQandaSequence( $str ) {
    $this ->Qanda -> setQandaSequence( $str );
  }
  
  function getQandaCurrent() {
    if (($this ->postVar("qanda_current")) || ($this ->postVar("qanda_current") === "")) {
      return $this ->postVar("qanda_current");
    } elseif (($this ->getVar("qanda_current")) || ($this ->getVar("qanda_current") === "")) {
      return $this ->getVar("qanda_current");
    } elseif (($this ->Qanda) || ($this ->Qanda === "")){
      return $this ->Qanda -> getQandaCurrent();
    } elseif (($this ->sessionVar("qanda_current")) || ($this ->sessionVar("qanda_current") == "")) {
      return $this ->sessionVar("qanda_current");
    } else {
      return false;
    }
  }
  
  function setQandaCurrent( $str ) {
    $this ->Qanda -> setQandaCurrent( $str );
  }
  
  function getQandaCreatedAt() {
    if (($this ->postVar("qanda_created_at")) || ($this ->postVar("qanda_created_at") === "")) {
      return $this ->postVar("qanda_created_at");
    } elseif (($this ->getVar("qanda_created_at")) || ($this ->getVar("qanda_created_at") === "")) {
      return $this ->getVar("qanda_created_at");
    } elseif (($this ->Qanda) || ($this ->Qanda === "")){
      return $this ->Qanda -> getQandaCreatedAt();
    } elseif (($this ->sessionVar("qanda_created_at")) || ($this ->sessionVar("qanda_created_at") == "")) {
      return $this ->sessionVar("qanda_created_at");
    } else {
      return false;
    }
  }
  
  function setQandaCreatedAt( $str ) {
    $this ->Qanda -> setQandaCreatedAt( $str );
  }
  
  function getQandaUpdatedAt() {
    if (($this ->postVar("qanda_updated_at")) || ($this ->postVar("qanda_updated_at") === "")) {
      return $this ->postVar("qanda_updated_at");
    } elseif (($this ->getVar("qanda_updated_at")) || ($this ->getVar("qanda_updated_at") === "")) {
      return $this ->getVar("qanda_updated_at");
    } elseif (($this ->Qanda) || ($this ->Qanda === "")){
      return $this ->Qanda -> getQandaUpdatedAt();
    } elseif (($this ->sessionVar("qanda_updated_at")) || ($this ->sessionVar("qanda_updated_at") == "")) {
      return $this ->sessionVar("qanda_updated_at");
    } else {
      return false;
    }
  }
  
  function setQandaUpdatedAt( $str ) {
    $this ->Qanda -> setQandaUpdatedAt( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Qanda = QandaPeer::retrieveByPK( $id );
    }
    
    if ($this ->Qanda ) {
       
    	       (is_numeric(WTVRcleanString($this ->Qanda->getQandaId()))) ? $itemarray["qanda_id"] = WTVRcleanString($this ->Qanda->getQandaId()) : null;
          (is_numeric(WTVRcleanString($this ->Qanda->getFkScreeningId()))) ? $itemarray["fk_screening_id"] = WTVRcleanString($this ->Qanda->getFkScreeningId()) : null;
          (is_numeric(WTVRcleanString($this ->Qanda->getFkUserId()))) ? $itemarray["fk_user_id"] = WTVRcleanString($this ->Qanda->getFkUserId()) : null;
          (WTVRcleanString($this ->Qanda->getQandaBody())) ? $itemarray["qanda_body"] = WTVRcleanString($this ->Qanda->getQandaBody()) : null;
          (WTVRcleanString($this ->Qanda->getQandaResponse())) ? $itemarray["qanda_response"] = WTVRcleanString($this ->Qanda->getQandaResponse()) : null;
          (WTVRcleanString($this ->Qanda->getQandaAnswered())) ? $itemarray["qanda_answered"] = WTVRcleanString($this ->Qanda->getQandaAnswered()) : null;
          (WTVRcleanString($this ->Qanda->getQandaSequence())) ? $itemarray["qanda_sequence"] = WTVRcleanString($this ->Qanda->getQandaSequence()) : null;
          (WTVRcleanString($this ->Qanda->getQandaCurrent())) ? $itemarray["qanda_current"] = WTVRcleanString($this ->Qanda->getQandaCurrent()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Qanda->getQandaCreatedAt())) ? $itemarray["qanda_created_at"] = formatDate($this ->Qanda->getQandaCreatedAt('%Y-%m-%d %T'),"TS") : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->Qanda->getQandaUpdatedAt())) ? $itemarray["qanda_updated_at"] = formatDate($this ->Qanda->getQandaUpdatedAt('%Y-%m-%d %T'),"TS") : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Qanda = QandaPeer::retrieveByPK( $id );
    } elseif (! $this ->Qanda) {
      $this ->Qanda = new Qanda;
    }
        
  	 ($this -> getQandaId())? $this ->Qanda->setQandaId( WTVRcleanString( $this -> getQandaId()) ) : null;
    ($this -> getFkScreeningId())? $this ->Qanda->setFkScreeningId( WTVRcleanString( $this -> getFkScreeningId()) ) : null;
    ($this -> getFkUserId())? $this ->Qanda->setFkUserId( WTVRcleanString( $this -> getFkUserId()) ) : null;
    ($this -> getQandaBody())? $this ->Qanda->setQandaBody( WTVRcleanString( $this -> getQandaBody()) ) : null;
    ($this -> getQandaResponse())? $this ->Qanda->setQandaResponse( WTVRcleanString( $this -> getQandaResponse()) ) : null;
    ($this -> getQandaAnswered())? $this ->Qanda->setQandaAnswered( WTVRcleanString( $this -> getQandaAnswered()) ) : null;
    ($this -> getQandaSequence())? $this ->Qanda->setQandaSequence( WTVRcleanString( $this -> getQandaSequence()) ) : null;
    ($this -> getQandaCurrent())? $this ->Qanda->setQandaCurrent( WTVRcleanString( $this -> getQandaCurrent()) ) : null;
          if (is_valid_date( $this ->Qanda->getQandaCreatedAt())) {
        $this ->Qanda->setQandaCreatedAt( formatDate($this -> getQandaCreatedAt(), "TS" ));
      } else {
      $Qandaqanda_created_at = $this -> sfDateTime( "qanda_created_at" );
      ( $Qandaqanda_created_at != "01/01/1900 00:00:00" )? $this ->Qanda->setQandaCreatedAt( formatDate($Qandaqanda_created_at, "TS" )) : $this ->Qanda->setQandaCreatedAt( null );
      }
          if (is_valid_date( $this ->Qanda->getQandaUpdatedAt())) {
        $this ->Qanda->setQandaUpdatedAt( formatDate($this -> getQandaUpdatedAt(), "TS" ));
      } else {
      $Qandaqanda_updated_at = $this -> sfDateTime( "qanda_updated_at" );
      ( $Qandaqanda_updated_at != "01/01/1900 00:00:00" )? $this ->Qanda->setQandaUpdatedAt( formatDate($Qandaqanda_updated_at, "TS" )) : $this ->Qanda->setQandaUpdatedAt( null );
      }
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Qanda ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Qanda = QandaPeer::retrieveByPK($id);
    }
    
    if (! $this ->Qanda ) {
      return;
    }
    
    $this ->Qanda -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Qanda_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "QandaPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Qanda = QandaPeer::doSelect($c);
    
    if (count($Qanda) >= 1) {
      $this ->Qanda = $Qanda[0];
      return true;
    } else {
      $this ->Qanda = new Qanda();
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
      $name = "QandaPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Qanda = QandaPeer::doSelect($c);
    
    if (count($Qanda) >= 1) {
      $this ->Qanda = $Qanda[0];
      return true;
    } else {
      $this ->Qanda = new Qanda();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>