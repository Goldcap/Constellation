<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/CreditAdmin_crud.php';
  
   class CreditAdmin_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	  $this -> XMLForm -> item_forcehidden = true;
    
    $this -> crud = new PaymentCrud( $context );
    parent::__construct( $context );
    
    $this -> doPost = false;
    $this -> isError = false;
    $this -> XMLForm = new XMLForm($this -> widget_name);
    $this -> XMLForm -> item_forcehidden = true;
  }

	function parse() {
	 
	 //return $this -> widget_vars;
   
	  if ($this -> XMLForm -> isPosted()) {  
      $this -> doPost();
    }
    $this -> doGet();
    
    return $this -> drawPage();
    
  }

  function doPost(){
     
     if ($this -> XMLForm -> validateForm()) {
        switch ($this -> getFormMethod()) {
          case "submit":
          $this -> crud -> write();
          break;
          case "delete":
          $this -> crud -> remove();
          break;
        }
      }
    
  }

  function doGet(){
    $this -> pushItem();
    
  }

  function drawPage(){
  
    return $this -> returnForm();
    
  }

	}

  ?>
