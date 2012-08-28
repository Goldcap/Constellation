<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/TokBox_crud.php';
  
   class TokBox_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> crud = new TokboxVideoCrud( $context );
    parent::__construct( $context );
  }

	function parse() {
	 
	 if ($this -> getVar('id') == "get") {
	   $vars = array("fk_screening_unique_key"=>$this -> getVar("screening"));
		 $this -> crud -> checkUnique($vars);
		 print($this -> crud -> getTokboxVideoArchiveId());
	 } else {
		 $vars = array("fk_screening_unique_key"=>$this -> getVar("screening"));
		 $this -> crud -> checkUnique($vars);
		 if ($this -> crud -> TokboxVideo -> getTokboxVideoId() < 1) {
			 $this -> crud -> setFkScreeningUniqueKey($this -> getVar("screening"));
			 $this -> crud -> setTokboxVideoArchiveId($this -> getVar("archive"));
			 $this -> crud -> setTokboxVideoDateCreated(now());
			 $this -> crud -> save();
	 }}
	 die();
    
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
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      $this -> pushItem();
    }
    
  }

  function drawPage(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      return $this -> returnForm();
    } elseif ($this ->getOp() == "list" ) {
      return $this -> returnList();
    }
    
  }

	}

  ?>