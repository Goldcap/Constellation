<?php
       
   class StaticPageCrudBase extends Utils_PageWidget { 
   
    var $StaticPage;
   
       var $static_page_id;
   var $static_page_unique_name;
   var $static_page_content;
   var $static_page_created_at;
   var $static_page_updated_at;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getStaticPageId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->StaticPage = StaticPagePeer::retrieveByPK( $id );
    } else {
      $this ->StaticPage = new StaticPage;
    }
  }
  
  function hydrate( $id ) {
      $this ->StaticPage = StaticPagePeer::retrieveByPK( $id );
  }
  
  function getStaticPageId() {
    if (($this ->postVar("static_page_id")) || ($this ->postVar("static_page_id") === "")) {
      return $this ->postVar("static_page_id");
    } elseif (($this ->getVar("static_page_id")) || ($this ->getVar("static_page_id") === "")) {
      return $this ->getVar("static_page_id");
    } elseif (($this ->StaticPage) || ($this ->StaticPage === "")){
      return $this ->StaticPage -> getStaticPageId();
    } elseif (($this ->sessionVar("static_page_id")) || ($this ->sessionVar("static_page_id") == "")) {
      return $this ->sessionVar("static_page_id");
    } else {
      return false;
    }
  }
  
  function setStaticPageId( $str ) {
    $this ->StaticPage -> setStaticPageId( $str );
  }
  
  function getStaticPageUniqueName() {
    if (($this ->postVar("static_page_unique_name")) || ($this ->postVar("static_page_unique_name") === "")) {
      return $this ->postVar("static_page_unique_name");
    } elseif (($this ->getVar("static_page_unique_name")) || ($this ->getVar("static_page_unique_name") === "")) {
      return $this ->getVar("static_page_unique_name");
    } elseif (($this ->StaticPage) || ($this ->StaticPage === "")){
      return $this ->StaticPage -> getStaticPageUniqueName();
    } elseif (($this ->sessionVar("static_page_unique_name")) || ($this ->sessionVar("static_page_unique_name") == "")) {
      return $this ->sessionVar("static_page_unique_name");
    } else {
      return false;
    }
  }
  
  function setStaticPageUniqueName( $str ) {
    $this ->StaticPage -> setStaticPageUniqueName( $str );
  }
  
  function getStaticPageContent() {
    if (($this ->postVar("static_page_content")) || ($this ->postVar("static_page_content") === "")) {
      return $this ->postVar("static_page_content");
    } elseif (($this ->getVar("static_page_content")) || ($this ->getVar("static_page_content") === "")) {
      return $this ->getVar("static_page_content");
    } elseif (($this ->StaticPage) || ($this ->StaticPage === "")){
      return $this ->StaticPage -> getStaticPageContent();
    } elseif (($this ->sessionVar("static_page_content")) || ($this ->sessionVar("static_page_content") == "")) {
      return $this ->sessionVar("static_page_content");
    } else {
      return false;
    }
  }
  
  function setStaticPageContent( $str ) {
    $this ->StaticPage -> setStaticPageContent( $str );
  }
  
  function getStaticPageCreatedAt() {
    if (($this ->postVar("static_page_created_at")) || ($this ->postVar("static_page_created_at") === "")) {
      return $this ->postVar("static_page_created_at");
    } elseif (($this ->getVar("static_page_created_at")) || ($this ->getVar("static_page_created_at") === "")) {
      return $this ->getVar("static_page_created_at");
    } elseif (($this ->StaticPage) || ($this ->StaticPage === "")){
      return $this ->StaticPage -> getStaticPageCreatedAt();
    } elseif (($this ->sessionVar("static_page_created_at")) || ($this ->sessionVar("static_page_created_at") == "")) {
      return $this ->sessionVar("static_page_created_at");
    } else {
      return false;
    }
  }
  
  function setStaticPageCreatedAt( $str ) {
    $this ->StaticPage -> setStaticPageCreatedAt( $str );
  }
  
  function getStaticPageUpdatedAt() {
    if (($this ->postVar("static_page_updated_at")) || ($this ->postVar("static_page_updated_at") === "")) {
      return $this ->postVar("static_page_updated_at");
    } elseif (($this ->getVar("static_page_updated_at")) || ($this ->getVar("static_page_updated_at") === "")) {
      return $this ->getVar("static_page_updated_at");
    } elseif (($this ->StaticPage) || ($this ->StaticPage === "")){
      return $this ->StaticPage -> getStaticPageUpdatedAt();
    } elseif (($this ->sessionVar("static_page_updated_at")) || ($this ->sessionVar("static_page_updated_at") == "")) {
      return $this ->sessionVar("static_page_updated_at");
    } else {
      return false;
    }
  }
  
  function setStaticPageUpdatedAt( $str ) {
    $this ->StaticPage -> setStaticPageUpdatedAt( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->StaticPage = StaticPagePeer::retrieveByPK( $id );
    }
    
    if ($this ->StaticPage ) {
       
    	       (is_numeric(WTVRcleanString($this ->StaticPage->getStaticPageId()))) ? $itemarray["static_page_id"] = WTVRcleanString($this ->StaticPage->getStaticPageId()) : null;
          (WTVRcleanString($this ->StaticPage->getStaticPageUniqueName())) ? $itemarray["static_page_unique_name"] = WTVRcleanString($this ->StaticPage->getStaticPageUniqueName()) : null;
          (WTVRcleanString($this ->StaticPage->getStaticPageContent())) ? $itemarray["static_page_content"] = WTVRcleanString($this ->StaticPage->getStaticPageContent()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->StaticPage->getStaticPageCreatedAt())) ? $itemarray["static_page_created_at"] = formatDate($this ->StaticPage->getStaticPageCreatedAt('%Y-%m-%d %T'),"TS") : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->StaticPage->getStaticPageUpdatedAt())) ? $itemarray["static_page_updated_at"] = formatDate($this ->StaticPage->getStaticPageUpdatedAt('%Y-%m-%d %T'),"TS") : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->StaticPage = StaticPagePeer::retrieveByPK( $id );
    } elseif (! $this ->StaticPage) {
      $this ->StaticPage = new StaticPage;
    }
        
  	 ($this -> getStaticPageId())? $this ->StaticPage->setStaticPageId( WTVRcleanString( $this -> getStaticPageId()) ) : null;
    ($this -> getStaticPageUniqueName())? $this ->StaticPage->setStaticPageUniqueName( WTVRcleanString( $this -> getStaticPageUniqueName()) ) : null;
    ($this -> getStaticPageContent())? $this ->StaticPage->setStaticPageContent( WTVRcleanString( $this -> getStaticPageContent()) ) : null;
          if (is_valid_date( $this ->StaticPage->getStaticPageCreatedAt())) {
        $this ->StaticPage->setStaticPageCreatedAt( formatDate($this -> getStaticPageCreatedAt(), "TS" ));
      } else {
      $StaticPagestatic_page_created_at = $this -> sfDateTime( "static_page_created_at" );
      ( $StaticPagestatic_page_created_at != "01/01/1900 00:00:00" )? $this ->StaticPage->setStaticPageCreatedAt( formatDate($StaticPagestatic_page_created_at, "TS" )) : $this ->StaticPage->setStaticPageCreatedAt( null );
      }
          if (is_valid_date( $this ->StaticPage->getStaticPageUpdatedAt())) {
        $this ->StaticPage->setStaticPageUpdatedAt( formatDate($this -> getStaticPageUpdatedAt(), "TS" ));
      } else {
      $StaticPagestatic_page_updated_at = $this -> sfDateTime( "static_page_updated_at" );
      ( $StaticPagestatic_page_updated_at != "01/01/1900 00:00:00" )? $this ->StaticPage->setStaticPageUpdatedAt( formatDate($StaticPagestatic_page_updated_at, "TS" )) : $this ->StaticPage->setStaticPageUpdatedAt( null );
      }
          
    $this -> save();
     
  }
  
  function save() {
    $this ->StaticPage ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->StaticPage = StaticPagePeer::retrieveByPK($id);
    }
    
    if (! $this ->StaticPage ) {
      return;
    }
    
    $this ->StaticPage -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('StaticPage_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "StaticPagePeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $StaticPage = StaticPagePeer::doSelect($c);
    
    if (count($StaticPage) >= 1) {
      $this ->StaticPage = $StaticPage[0];
      return true;
    } else {
      $this ->StaticPage = new StaticPage();
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
      $name = "StaticPagePeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $StaticPage = StaticPagePeer::doSelect($c);
    
    if (count($StaticPage) >= 1) {
      $this ->StaticPage = $StaticPage[0];
      return true;
    } else {
      $this ->StaticPage = new StaticPage();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>