<?php
       
   class FeedbackQuestionCrudBase extends Utils_PageWidget { 
   
    var $FeedbackQuestion;
   
       var $feedback_question_id;
   var $feedback_question_name;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getFeedbackQuestionId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->FeedbackQuestion = FeedbackQuestionPeer::retrieveByPK( $id );
    } else {
      $this ->FeedbackQuestion = new FeedbackQuestion;
    }
  }
  
  function hydrate( $id ) {
      $this ->FeedbackQuestion = FeedbackQuestionPeer::retrieveByPK( $id );
  }
  
  function getFeedbackQuestionId() {
    if (($this ->postVar("feedback_question_id")) || ($this ->postVar("feedback_question_id") === "")) {
      return $this ->postVar("feedback_question_id");
    } elseif (($this ->getVar("feedback_question_id")) || ($this ->getVar("feedback_question_id") === "")) {
      return $this ->getVar("feedback_question_id");
    } elseif (($this ->FeedbackQuestion) || ($this ->FeedbackQuestion === "")){
      return $this ->FeedbackQuestion -> getFeedbackQuestionId();
    } elseif (($this ->sessionVar("feedback_question_id")) || ($this ->sessionVar("feedback_question_id") == "")) {
      return $this ->sessionVar("feedback_question_id");
    } else {
      return false;
    }
  }
  
  function setFeedbackQuestionId( $str ) {
    $this ->FeedbackQuestion -> setFeedbackQuestionId( $str );
  }
  
  function getFeedbackQuestionName() {
    if (($this ->postVar("feedback_question_name")) || ($this ->postVar("feedback_question_name") === "")) {
      return $this ->postVar("feedback_question_name");
    } elseif (($this ->getVar("feedback_question_name")) || ($this ->getVar("feedback_question_name") === "")) {
      return $this ->getVar("feedback_question_name");
    } elseif (($this ->FeedbackQuestion) || ($this ->FeedbackQuestion === "")){
      return $this ->FeedbackQuestion -> getFeedbackQuestionName();
    } elseif (($this ->sessionVar("feedback_question_name")) || ($this ->sessionVar("feedback_question_name") == "")) {
      return $this ->sessionVar("feedback_question_name");
    } else {
      return false;
    }
  }
  
  function setFeedbackQuestionName( $str ) {
    $this ->FeedbackQuestion -> setFeedbackQuestionName( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->FeedbackQuestion = FeedbackQuestionPeer::retrieveByPK( $id );
    }
    
    if ($this ->FeedbackQuestion ) {
       
    	       (is_numeric(WTVRcleanString($this ->FeedbackQuestion->getFeedbackQuestionId()))) ? $itemarray["feedback_question_id"] = WTVRcleanString($this ->FeedbackQuestion->getFeedbackQuestionId()) : null;
          (WTVRcleanString($this ->FeedbackQuestion->getFeedbackQuestionName())) ? $itemarray["feedback_question_name"] = WTVRcleanString($this ->FeedbackQuestion->getFeedbackQuestionName()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->FeedbackQuestion = FeedbackQuestionPeer::retrieveByPK( $id );
    } elseif (! $this ->FeedbackQuestion) {
      $this ->FeedbackQuestion = new FeedbackQuestion;
    }
        
  	 ($this -> getFeedbackQuestionId())? $this ->FeedbackQuestion->setFeedbackQuestionId( WTVRcleanString( $this -> getFeedbackQuestionId()) ) : null;
    ($this -> getFeedbackQuestionName())? $this ->FeedbackQuestion->setFeedbackQuestionName( WTVRcleanString( $this -> getFeedbackQuestionName()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->FeedbackQuestion ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->FeedbackQuestion = FeedbackQuestionPeer::retrieveByPK($id);
    }
    
    if (! $this ->FeedbackQuestion ) {
      return;
    }
    
    $this ->FeedbackQuestion -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('FeedbackQuestion_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "FeedbackQuestionPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $FeedbackQuestion = FeedbackQuestionPeer::doSelect($c);
    
    if (count($FeedbackQuestion) >= 1) {
      $this ->FeedbackQuestion = $FeedbackQuestion[0];
      return true;
    } else {
      $this ->FeedbackQuestion = new FeedbackQuestion();
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
      $name = "FeedbackQuestionPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $FeedbackQuestion = FeedbackQuestionPeer::doSelect($c);
    
    if (count($FeedbackQuestion) >= 1) {
      $this ->FeedbackQuestion = $FeedbackQuestion[0];
      return true;
    } else {
      $this ->FeedbackQuestion = new FeedbackQuestion();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>