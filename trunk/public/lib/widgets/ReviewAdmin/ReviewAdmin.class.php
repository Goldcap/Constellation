<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/ReviewAdmin_crud.php';
  
   class ReviewAdmin_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	var $screening;
	var $film;
	
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
	 
	  //return $this -> widget_vars;
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      $this -> crud -> hydrate( $this -> getVar("id") );
      $this -> screening = ScreeningPeer::retrieveByPk($this -> crud -> Audience -> getFkScreeningId());
      $this -> film = FilmPeer::retrieveByPk($this -> screening -> getFkFilmId());
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
          if ($this -> postVar("audience_review_status")) {
            $this -> crud -> setAudienceReviewStatus(1);
          } else {
            $this -> crud -> setAudienceReviewStatus(0);
          }
          $this -> crud -> write();
          
           //Update the SOLR Search Engine
          $solr = new SolrManager_PageWidget(null, null, $this -> context);
          $solr -> execute("add","audience",$this -> crud -> Audience -> getAudienceId());
          $solr -> execute("add","screening",$this -> crud -> Audience -> getFkScreeningId());
          $solr -> execute("add","film",$this -> screening -> getFkFilmId());
          
          break;
          case "delete":
          
          $this -> crud -> setAudienceReviewStatus(0);
          $this -> crud -> write();
          
          //$this -> crud -> remove();
          $solr = new SolrManager_PageWidget(null, null, $this -> context);
          $solr -> execute("add","audience",$this -> crud -> Audience -> getAudienceId());
          $solr -> execute("add","screening",$this -> crud -> Audience -> getFkScreeningId());
          $solr -> execute("add","film",$this -> screening -> getFkFilmId());
          break;
        }
      }
    
  }

  function doGet(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      $this -> pushItem();
      $this -> XMLForm -> item["film_name"] = $this -> film -> getFilmName();
      if ($this -> crud -> getAudienceReviewStatus() == 1) {
        $this -> XMLForm -> item["audience_rstatus"] = "Live";
      } else {
        $this -> XMLForm -> item["audience_rstatus"] = "Not Live";
      }
    }
    
  }

  function drawPage(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      return $this -> returnForm();
    } elseif ($this ->getOp() == "film" ) {
      return $this -> returnList( "Review_list_datamap.xml", true, true, "standard" );
    }elseif ($this ->getOp() == "list" ) {
      $util = new ReviewAdmin_format_utility( $this -> context );
      return $this -> returnList( false, true, true, "standard", $util );
    }
    
  }

	}

  ?>
