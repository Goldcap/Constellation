<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Review_crud.php';
  
   class Review_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	  $this -> XMLForm -> item_forcehidden = true;
    $this -> crud = new AudienceCrud( $context );
    parent::__construct( $context );
  }

	function parse() {
	 
	  if ($this -> as_service) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          switch ($this -> postVar("method")) {
            case "write":
              $vars = array("fk_screening_id"=>$this -> postVar("screening_id"),"fk_user_id"=>$this -> postVar("user_id"));
              $this -> crud -> checkUnique($vars);
              $this -> crud -> setFkScreeningId( $this -> postVar("screening_id")  );
              $this -> crud -> setFkUserId( $this -> postVar("user_id") );
              $this -> crud -> setAudienceReview( WTVRFlatString(WTVRCleanString($this -> postVar("messagebody"))) );
              $this -> crud -> save();
              die("{'response':[{'panel':'write','success': true, 'id': '".$this -> crud -> Audience -> getAudienceId()."','html': '<div class=\"question\" id=\"r".$this -> crud -> Audience -> getAudienceId()."\">You wrote <span style=\"color: grey\">\"".$this -> crud -> Audience -> getAudienceReview()."\"</span></div>'}]}");
            
              break;
            case "send":
              //dump($_POST);
              
              $invite = new Invite_PageWidget();
              $response = $invite -> sendInvites( $this -> postVar("screening_id"), $this -> postVar("messagebody"), $users );
              
              die($response);
            
              break;
          }
          }
     }
     
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
