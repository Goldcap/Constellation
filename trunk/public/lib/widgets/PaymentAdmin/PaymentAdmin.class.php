<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/PaymentAdmin_crud.php';
  
   class PaymentAdmin_PageWidget extends Widget_PageWidget {
	
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
  }

	function parse() {
	 
	 //return $this -> widget_vars;
   
	  if ($this -> XMLForm -> isPosted()) {  
      $this -> doPost();
    }
    $this -> doGet();
    //$this -> showData();
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
      $film = FilmPeer::retrieveByPk($this -> XMLForm ->item["fk_film_id"]);
      $user = UserPeer::retrieveByPk($this -> XMLForm ->item["fk_user_id"]);
      $screening = ScreeningPeer::retrieveByPk($this -> XMLForm ->item["fk_screening_id"]);
      $audience = AudiencePeer::retrieveByPk($this -> XMLForm ->item["fk_audience_id"]);
      $this -> XMLForm -> item["audience_short_url"] = $audience -> getAudienceShortUrl();
      
      switch($this -> XMLForm ->item["payment_type"]) {
        case "screening":
          break;
        case "host":
          break;
        case "subscription":
          break;
      }
      
       switch($this -> XMLForm ->item["payment_status"]) {
        case 2:
          $this -> XMLForm ->item["payment_status"] = "Approved";
          break;
        case 1:
          $this -> XMLForm ->item["payment_status"] = "Declined";
          break;
        default:
          $this -> XMLForm ->item["payment_status"] = "Unidentified";
          break;
      }
      
      if ($film)
        $this -> XMLForm ->item["film_name"] = $film -> getFilmName();
      if ($user)
        $this -> XMLForm ->item["fk_user_id"] = "<a href='/user/detail/'".$user -> getUserId()."'>".$user -> getUserUsername()."</a>";
      if ($screening)
        $this -> XMLForm ->item["screening_date"] = $screening -> getScreeningDate() . " " .$screening -> getScreeningTime();
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

function formatPayment( $value ) {
  if ($value == 2) {
    return '<span style="color:green">Approved</span>';
  } elseif ($value == -1) {
    return '<span style="color:red">Declined</span>';
  } else {
    return '<span style="color:red">Unknown</span>';
  }
}
  ?>
