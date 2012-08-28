<?php
       
   class DictionaryCrudBase extends Utils_PageWidget { 
   
    var $Dictionary;
   
       var $dictionary_id;
   var $dictionary_word;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getDictionaryId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Dictionary = DictionaryPeer::retrieveByPK( $id );
    } else {
      $this ->Dictionary = new Dictionary;
    }
  }
  
  function hydrate( $id ) {
      $this ->Dictionary = DictionaryPeer::retrieveByPK( $id );
  }
  
  function getDictionaryId() {
    if (($this ->postVar("dictionary_id")) || ($this ->postVar("dictionary_id") === "")) {
      return $this ->postVar("dictionary_id");
    } elseif (($this ->getVar("dictionary_id")) || ($this ->getVar("dictionary_id") === "")) {
      return $this ->getVar("dictionary_id");
    } elseif (($this ->Dictionary) || ($this ->Dictionary === "")){
      return $this ->Dictionary -> getDictionaryId();
    } elseif (($this ->sessionVar("dictionary_id")) || ($this ->sessionVar("dictionary_id") == "")) {
      return $this ->sessionVar("dictionary_id");
    } else {
      return false;
    }
  }
  
  function setDictionaryId( $str ) {
    $this ->Dictionary -> setDictionaryId( $str );
  }
  
  function getDictionaryWord() {
    if (($this ->postVar("dictionary_word")) || ($this ->postVar("dictionary_word") === "")) {
      return $this ->postVar("dictionary_word");
    } elseif (($this ->getVar("dictionary_word")) || ($this ->getVar("dictionary_word") === "")) {
      return $this ->getVar("dictionary_word");
    } elseif (($this ->Dictionary) || ($this ->Dictionary === "")){
      return $this ->Dictionary -> getDictionaryWord();
    } elseif (($this ->sessionVar("dictionary_word")) || ($this ->sessionVar("dictionary_word") == "")) {
      return $this ->sessionVar("dictionary_word");
    } else {
      return false;
    }
  }
  
  function setDictionaryWord( $str ) {
    $this ->Dictionary -> setDictionaryWord( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Dictionary = DictionaryPeer::retrieveByPK( $id );
    }
    
    if ($this ->Dictionary ) {
       
    	       (is_numeric(WTVRcleanString($this ->Dictionary->getDictionaryId()))) ? $itemarray["dictionary_id"] = WTVRcleanString($this ->Dictionary->getDictionaryId()) : null;
          (WTVRcleanString($this ->Dictionary->getDictionaryWord())) ? $itemarray["dictionary_word"] = WTVRcleanString($this ->Dictionary->getDictionaryWord()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Dictionary = DictionaryPeer::retrieveByPK( $id );
    } elseif (! $this ->Dictionary) {
      $this ->Dictionary = new Dictionary;
    }
        
  	 ($this -> getDictionaryId())? $this ->Dictionary->setDictionaryId( WTVRcleanString( $this -> getDictionaryId()) ) : null;
    ($this -> getDictionaryWord())? $this ->Dictionary->setDictionaryWord( WTVRcleanString( $this -> getDictionaryWord()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Dictionary ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Dictionary = DictionaryPeer::retrieveByPK($id);
    }
    
    if (! $this ->Dictionary ) {
      return;
    }
    
    $this ->Dictionary -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Dictionary_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "DictionaryPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Dictionary = DictionaryPeer::doSelect($c);
    
    if (count($Dictionary) >= 1) {
      $this ->Dictionary = $Dictionary[0];
      return true;
    } else {
      $this ->Dictionary = new Dictionary();
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
      $name = "DictionaryPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Dictionary = DictionaryPeer::doSelect($c);
    
    if (count($Dictionary) >= 1) {
      $this ->Dictionary = $Dictionary[0];
      return true;
    } else {
      $this ->Dictionary = new Dictionary();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>