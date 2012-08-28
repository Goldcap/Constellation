<?php
       
   class ConversationVoteCrudBase extends Utils_PageWidget { 
   
    var $ConversationVote;
   
       var $conversation_vote_id;
   var $fk_conversation_guid;
   var $fk_user_id;
   var $conversation_vote_date_created;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getConversationVoteId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->ConversationVote = ConversationVotePeer::retrieveByPK( $id );
    } else {
      $this ->ConversationVote = new ConversationVote;
    }
  }
  
  function hydrate( $id ) {
      $this ->ConversationVote = ConversationVotePeer::retrieveByPK( $id );
  }
  
  function getConversationVoteId() {
    if (($this ->postVar("conversation_vote_id")) || ($this ->postVar("conversation_vote_id") === "")) {
      return $this ->postVar("conversation_vote_id");
    } elseif (($this ->getVar("conversation_vote_id")) || ($this ->getVar("conversation_vote_id") === "")) {
      return $this ->getVar("conversation_vote_id");
    } elseif (($this ->ConversationVote) || ($this ->ConversationVote === "")){
      return $this ->ConversationVote -> getConversationVoteId();
    } elseif (($this ->sessionVar("conversation_vote_id")) || ($this ->sessionVar("conversation_vote_id") == "")) {
      return $this ->sessionVar("conversation_vote_id");
    } else {
      return false;
    }
  }
  
  function setConversationVoteId( $str ) {
    $this ->ConversationVote -> setConversationVoteId( $str );
  }
  
  function getFkConversationGuid() {
    if (($this ->postVar("fk_conversation_guid")) || ($this ->postVar("fk_conversation_guid") === "")) {
      return $this ->postVar("fk_conversation_guid");
    } elseif (($this ->getVar("fk_conversation_guid")) || ($this ->getVar("fk_conversation_guid") === "")) {
      return $this ->getVar("fk_conversation_guid");
    } elseif (($this ->ConversationVote) || ($this ->ConversationVote === "")){
      return $this ->ConversationVote -> getFkConversationGuid();
    } elseif (($this ->sessionVar("fk_conversation_guid")) || ($this ->sessionVar("fk_conversation_guid") == "")) {
      return $this ->sessionVar("fk_conversation_guid");
    } else {
      return false;
    }
  }
  
  function setFkConversationGuid( $str ) {
    $this ->ConversationVote -> setFkConversationGuid( $str );
  }
  
  function getFkUserId() {
    if (($this ->postVar("fk_user_id")) || ($this ->postVar("fk_user_id") === "")) {
      return $this ->postVar("fk_user_id");
    } elseif (($this ->getVar("fk_user_id")) || ($this ->getVar("fk_user_id") === "")) {
      return $this ->getVar("fk_user_id");
    } elseif (($this ->ConversationVote) || ($this ->ConversationVote === "")){
      return $this ->ConversationVote -> getFkUserId();
    } elseif (($this ->sessionVar("fk_user_id")) || ($this ->sessionVar("fk_user_id") == "")) {
      return $this ->sessionVar("fk_user_id");
    } else {
      return false;
    }
  }
  
  function setFkUserId( $str ) {
    $this ->ConversationVote -> setFkUserId( $str );
  }
  
  function getConversationVoteDateCreated() {
    if (($this ->postVar("conversation_vote_date_created")) || ($this ->postVar("conversation_vote_date_created") === "")) {
      return $this ->postVar("conversation_vote_date_created");
    } elseif (($this ->getVar("conversation_vote_date_created")) || ($this ->getVar("conversation_vote_date_created") === "")) {
      return $this ->getVar("conversation_vote_date_created");
    } elseif (($this ->ConversationVote) || ($this ->ConversationVote === "")){
      return $this ->ConversationVote -> getConversationVoteDateCreated();
    } elseif (($this ->sessionVar("conversation_vote_date_created")) || ($this ->sessionVar("conversation_vote_date_created") == "")) {
      return $this ->sessionVar("conversation_vote_date_created");
    } else {
      return false;
    }
  }
  
  function setConversationVoteDateCreated( $str ) {
    $this ->ConversationVote -> setConversationVoteDateCreated( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->ConversationVote = ConversationVotePeer::retrieveByPK( $id );
    }
    
    if ($this ->ConversationVote ) {
       
    	       (is_numeric(WTVRcleanString($this ->ConversationVote->getConversationVoteId()))) ? $itemarray["conversation_vote_id"] = WTVRcleanString($this ->ConversationVote->getConversationVoteId()) : null;
          (WTVRcleanString($this ->ConversationVote->getFkConversationGuid())) ? $itemarray["fk_conversation_guid"] = WTVRcleanString($this ->ConversationVote->getFkConversationGuid()) : null;
          (is_numeric(WTVRcleanString($this ->ConversationVote->getFkUserId()))) ? $itemarray["fk_user_id"] = WTVRcleanString($this ->ConversationVote->getFkUserId()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->ConversationVote->getConversationVoteDateCreated())) ? $itemarray["conversation_vote_date_created"] = formatDate($this ->ConversationVote->getConversationVoteDateCreated('%Y-%m-%d %T'),"TS") : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->ConversationVote = ConversationVotePeer::retrieveByPK( $id );
    } elseif (! $this ->ConversationVote) {
      $this ->ConversationVote = new ConversationVote;
    }
        
  	 ($this -> getConversationVoteId())? $this ->ConversationVote->setConversationVoteId( WTVRcleanString( $this -> getConversationVoteId()) ) : null;
    ($this -> getFkConversationGuid())? $this ->ConversationVote->setFkConversationGuid( WTVRcleanString( $this -> getFkConversationGuid()) ) : null;
    ($this -> getFkUserId())? $this ->ConversationVote->setFkUserId( WTVRcleanString( $this -> getFkUserId()) ) : null;
          if (is_valid_date( $this ->ConversationVote->getConversationVoteDateCreated())) {
        $this ->ConversationVote->setConversationVoteDateCreated( formatDate($this -> getConversationVoteDateCreated(), "TS" ));
      } else {
      $ConversationVoteconversation_vote_date_created = $this -> sfDateTime( "conversation_vote_date_created" );
      ( $ConversationVoteconversation_vote_date_created != "01/01/1900 00:00:00" )? $this ->ConversationVote->setConversationVoteDateCreated( formatDate($ConversationVoteconversation_vote_date_created, "TS" )) : $this ->ConversationVote->setConversationVoteDateCreated( null );
      }
          
    $this -> save();
     
  }
  
  function save() {
    $this ->ConversationVote ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->ConversationVote = ConversationVotePeer::retrieveByPK($id);
    }
    
    if (! $this ->ConversationVote ) {
      return;
    }
    
    $this ->ConversationVote -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('ConversationVote_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "ConversationVotePeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $ConversationVote = ConversationVotePeer::doSelect($c);
    
    if (count($ConversationVote) >= 1) {
      $this ->ConversationVote = $ConversationVote[0];
      return true;
    } else {
      $this ->ConversationVote = new ConversationVote();
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
      $name = "ConversationVotePeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $ConversationVote = ConversationVotePeer::doSelect($c);
    
    if (count($ConversationVote) >= 1) {
      $this ->ConversationVote = $ConversationVote[0];
      return true;
    } else {
      $this ->ConversationVote = new ConversationVote();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>