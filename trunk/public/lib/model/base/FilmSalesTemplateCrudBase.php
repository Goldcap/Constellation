<?php
       
   class FilmSalesTemplateCrudBase extends Utils_PageWidget { 
   
    var $FilmSalesTemplate;
   
       var $film_sales_template_id;
   var $film_sales_template_name;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getFilmSalesTemplateId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->FilmSalesTemplate = FilmSalesTemplatePeer::retrieveByPK( $id );
    } else {
      $this ->FilmSalesTemplate = new FilmSalesTemplate;
    }
  }
  
  function hydrate( $id ) {
      $this ->FilmSalesTemplate = FilmSalesTemplatePeer::retrieveByPK( $id );
  }
  
  function getFilmSalesTemplateId() {
    if (($this ->postVar("film_sales_template_id")) || ($this ->postVar("film_sales_template_id") === "")) {
      return $this ->postVar("film_sales_template_id");
    } elseif (($this ->getVar("film_sales_template_id")) || ($this ->getVar("film_sales_template_id") === "")) {
      return $this ->getVar("film_sales_template_id");
    } elseif (($this ->FilmSalesTemplate) || ($this ->FilmSalesTemplate === "")){
      return $this ->FilmSalesTemplate -> getFilmSalesTemplateId();
    } elseif (($this ->sessionVar("film_sales_template_id")) || ($this ->sessionVar("film_sales_template_id") == "")) {
      return $this ->sessionVar("film_sales_template_id");
    } else {
      return false;
    }
  }
  
  function setFilmSalesTemplateId( $str ) {
    $this ->FilmSalesTemplate -> setFilmSalesTemplateId( $str );
  }
  
  function getFilmSalesTemplateName() {
    if (($this ->postVar("film_sales_template_name")) || ($this ->postVar("film_sales_template_name") === "")) {
      return $this ->postVar("film_sales_template_name");
    } elseif (($this ->getVar("film_sales_template_name")) || ($this ->getVar("film_sales_template_name") === "")) {
      return $this ->getVar("film_sales_template_name");
    } elseif (($this ->FilmSalesTemplate) || ($this ->FilmSalesTemplate === "")){
      return $this ->FilmSalesTemplate -> getFilmSalesTemplateName();
    } elseif (($this ->sessionVar("film_sales_template_name")) || ($this ->sessionVar("film_sales_template_name") == "")) {
      return $this ->sessionVar("film_sales_template_name");
    } else {
      return false;
    }
  }
  
  function setFilmSalesTemplateName( $str ) {
    $this ->FilmSalesTemplate -> setFilmSalesTemplateName( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->FilmSalesTemplate = FilmSalesTemplatePeer::retrieveByPK( $id );
    }
    
    if ($this ->FilmSalesTemplate ) {
       
    	       (is_numeric(WTVRcleanString($this ->FilmSalesTemplate->getFilmSalesTemplateId()))) ? $itemarray["film_sales_template_id"] = WTVRcleanString($this ->FilmSalesTemplate->getFilmSalesTemplateId()) : null;
          (WTVRcleanString($this ->FilmSalesTemplate->getFilmSalesTemplateName())) ? $itemarray["film_sales_template_name"] = WTVRcleanString($this ->FilmSalesTemplate->getFilmSalesTemplateName()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->FilmSalesTemplate = FilmSalesTemplatePeer::retrieveByPK( $id );
    } elseif (! $this ->FilmSalesTemplate) {
      $this ->FilmSalesTemplate = new FilmSalesTemplate;
    }
        
  	 ($this -> getFilmSalesTemplateId())? $this ->FilmSalesTemplate->setFilmSalesTemplateId( WTVRcleanString( $this -> getFilmSalesTemplateId()) ) : null;
    ($this -> getFilmSalesTemplateName())? $this ->FilmSalesTemplate->setFilmSalesTemplateName( WTVRcleanString( $this -> getFilmSalesTemplateName()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->FilmSalesTemplate ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->FilmSalesTemplate = FilmSalesTemplatePeer::retrieveByPK($id);
    }
    
    if (! $this ->FilmSalesTemplate ) {
      return;
    }
    
    $this ->FilmSalesTemplate -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('FilmSalesTemplate_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "FilmSalesTemplatePeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $FilmSalesTemplate = FilmSalesTemplatePeer::doSelect($c);
    
    if (count($FilmSalesTemplate) >= 1) {
      $this ->FilmSalesTemplate = $FilmSalesTemplate[0];
      return true;
    } else {
      $this ->FilmSalesTemplate = new FilmSalesTemplate();
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
      $name = "FilmSalesTemplatePeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $FilmSalesTemplate = FilmSalesTemplatePeer::doSelect($c);
    
    if (count($FilmSalesTemplate) >= 1) {
      $this ->FilmSalesTemplate = $FilmSalesTemplate[0];
      return true;
    } else {
      $this ->FilmSalesTemplate = new FilmSalesTemplate();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>