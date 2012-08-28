<?php
       
   class FeedbackCrudBase extends Utils_PageWidget { 
   
    var $Feedback;
   
       var $feedback_id;
   var $fk_question_id;
   var $fk_user_hash;
   var $response;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getFeedbackId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Feedback = FeedbackPeer::retrieveByPK( $id );
    } else {
      $this ->Feedback = new Feedback;
    }
  }
  
  function hydrate( $id ) {
      $this ->Feedback = FeedbackPeer::retrieveByPK( $id );
  }
  
  function getFeedbackId() {
    if (($this ->postVar("feedback_id")) || ($this ->postVar("feedback_id") === "")) {
      return $this ->postVar("feedback_id");
    } elseif (($this ->getVar("feedback_id")) || ($this ->getVar("feedback_id") === "")) {
      return $this ->getVar("feedback_id");
    } elseif (($this ->Feedback) || ($this ->Feedback === "")){
      return $this ->Feedback -> getFeedbackId();
    } elseif (($this ->sessionVar("feedback_id")) || ($this ->sessionVar("feedback_id") == "")) {
      return $this ->sessionVar("feedback_id");
    } else {
      return false;
    }
  }
  
  function setFeedbackId( $str ) {
    $this ->Feedback -> setFeedbackId( $str );
  }
  
  function getFkQuestionId() {
    if (($this ->postVar("fk_question_id")) || ($this ->postVar("fk_question_id") === "")) {
      return $this ->postVar("fk_question_id");
    } elseif (($this ->getVar("fk_question_id")) || ($this ->getVar("fk_question_id") === "")) {
      return $this ->getVar("fk_question_id");
    } elseif (($this ->Feedback) || ($this ->Feedback === "")){
      return $this ->Feedback -> getFkQuestionId();
    } elseif (($this ->sessionVar("fk_question_id")) || ($this ->sessionVar("fk_question_id") == "")) {
      return $this ->sessionVar("fk_question_id");
    } else {
      return false;
    }
  }
  
  function setFkQuestionId( $str ) {
    $this ->Feedback -> setFkQuestionId( $str );
  }
  
  function getFkUserHash() {
    if (($this ->postVar("fk_user_hash")) || ($this ->postVar("fk_user_hash") === "")) {
      return $this ->postVar("fk_user_hash");
    } elseif (($this ->getVar("fk_user_hash")) || ($this ->getVar("fk_user_hash") === "")) {
      return $this ->getVar("fk_user_hash");
    } elseif (($this ->Feedback) || ($this ->Feedback === "")){
      return $this ->Feedback -> getFkUserHash();
    } elseif (($this ->sessionVar("fk_user_hash")) || ($this ->sessionVar("fk_user_hash") == "")) {
      return $this ->sessionVar("fk_user_hash");
    } else {
      return false;
    }
  }
  
  function setFkUserHash( $str ) {
    $this ->Feedback -> setFkUserHash( $str );
  }
  
  function getResponse() {
    if (($this ->postVar("response")) || ($this ->postVar("response") === "")) {
      return $this ->postVar("response");
    } elseif (($this ->getVar("response")) || ($this ->getVar("response") === "")) {
      return $this ->getVar("response");
    } elseif (($this ->Feedback) || ($this ->Feedback === "")){
      return $this ->Feedback -> getResponse();
    } elseif (($this ->sessionVar("response")) || ($this ->sessionVar("response") == "")) {
      return $this ->sessionVar("response");
    } else {
      return false;
    }
  }
  
  function setResponse( $str ) {
    $this ->Feedback -> setResponse( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Feedback = FeedbackPeer::retrieveByPK( $id );
    }
    
    if ($this ->Feedback ) {
       
    	       (is_numeric(WTVRcleanString($this ->Feedback->getFeedbackId()))) ? $itemarray["feedback_id"] = WTVRcleanString($this ->Feedback->getFeedbackId()) : null;
          (is_numeric(WTVRcleanString($this ->Feedback->getFkQuestionId()))) ? $itemarray["fk_question_id"] = WTVRcleanString($this ->Feedback->getFkQuestionId()) : null;
          (WTVRcleanString($this ->Feedback->getFkUserHash())) ? $itemarray["fk_user_hash"] = WTVRcleanString($this ->Feedback->getFkUserHash()) : null;
          (WTVRcleanString($this ->Feedback->getResponse())) ? $itemarray["response"] = WTVRcleanString($this ->Feedback->getResponse()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Feedback = FeedbackPeer::retrieveByPK( $id );
    } elseif (! $this ->Feedback) {
      $this ->Feedback = new Feedback;
    }
        
  	 ($this -> getFeedbackId())? $this ->Feedback->setFeedbackId( WTVRcleanString( $this -> getFeedbackId()) ) : null;
    ($this -> getFkQuestionId())? $this ->Feedback->setFkQuestionId( WTVRcleanString( $this -> getFkQuestionId()) ) : null;
    ($this -> getFkUserHash())? $this ->Feedback->setFkUserHash( WTVRcleanString( $this -> getFkUserHash()) ) : null;
    ($this -> getResponse())? $this ->Feedback->setResponse( WTVRcleanString( $this -> getResponse()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Feedback ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Feedback = FeedbackPeer::retrieveByPK($id);
    }
    
    if (! $this ->Feedback ) {
      return;
    }
    
    $this ->Feedback -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Feedback_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "FeedbackPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Feedback = FeedbackPeer::doSelect($c);
    
    if (count($Feedback) >= 1) {
      $this ->Feedback = $Feedback[0];
      return true;
    } else {
      $this ->Feedback = new Feedback();
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
      $name = "FeedbackPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Feedback = FeedbackPeer::doSelect($c);
    
    if (count($Feedback) >= 1) {
      $this ->Feedback = $Feedback[0];
      return true;
    } else {
      $this ->Feedback = new Feedback();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>