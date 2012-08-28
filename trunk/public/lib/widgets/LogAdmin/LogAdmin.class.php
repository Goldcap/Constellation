<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/LogAdmin_crud.php';
  
   class LogAdmin_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	  $this -> XMLForm -> item_forcehidden = true;
    parent::__construct( $context );
  }

	function parse() {
	 
	 //return $this -> widget_vars;
   
	  if ($this -> XMLForm -> isPosted()) {  
      $this -> doPost();
    }
    $this -> doGet();
   
   if ($this ->getOp() != "detail")  {
     $this -> SearchForm = new XMLForm($this -> widget_name, "searchconf.php");
     $this -> SearchForm -> validated= false;
     $searchform = $this -> SearchForm -> drawForm();
     $this -> widget_vars["search_form"] = $searchform["form"];
   }
   
   $form = $this -> drawPage();
   $this -> widget_vars["form"] = $form["form"];
   return $this -> widget_vars;
    
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
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0)) {
      return $this -> logDetail();
    } elseif ($this ->getOp() == "search" ) {
      return $this -> logSearch();
    } elseif ($this ->getOp() == "list" ) {
      //$this -> showData();
      $util = new LogAdminSearch_format_utility( $this -> context );
      return $this -> returnList( false, true, true, "standard", $util );
    }
    
  }
  
  function logDetail() {
     $c = new Criteria();
     $c->add(LogFlashlogPeer::FLASHLOG_ID,$this -> getId());
     $thelog = LogFlashlogPeer::doSelect($c);
     
     $c = new Criteria();
     $c->add(AudiencePeer::AUDIENCE_INVITE_CODE,$thelog[0] -> getFlashlogTicket());
     $theaudience = AudiencePeer::doSelect($c);
     
     //$this -> showXML();
     $ak = $this -> akamaiSearch( $theaudience[0] -> getAudienceIpAddr() );
     $list = $this -> logSearch( $thelog[0] -> getFlashlogTicket(), 20 );
     
     $this -> widget_vars["audience"] = $theaudience[0];
     $this -> widget_vars["form"] = $ak["form"];
     $this -> widget_vars["search_form"] = $list["form"];
     return $this -> widget_vars;
     
  }
  
  function akamaiSearch( $query=false ) {
    
    //$this -> showData();
    //$this -> showXML();  
    $util = new LogAkamaiSearch_format_utility( $this -> context );
    
    if ($this -> getVar("errors") == "yes") {
      $util -> errors = true;
    }
    
    if (! $query) {
    	if (($this -> greedyVar("log_start_date")) && (! $this -> greedyVar("log_end_date"))) {
        //$end = formatDate($this -> postVar("cms_object_end_date"),"W3XML");
        $util -> date = "'".formatDate($this -> greedyVar("log_start_date"),"TS")."|".formatDate(null,"TS")."'";
      }
      if ((! $this -> greedyVar("log_start_date")) && ($this -> greedyVar("log_end_date"))) {
        //$start = formatDate($this -> postVar("cms_object_start_date"),"W3XML");
        $util -> date = "1970-01-01 00:00:00|".formatDate($this -> greedyVar("log_end_date"),"TS");
      }
      if (($this -> greedyVar("log_start_date")) && ($this -> greedyVar("log_end_date"))) {
        $util -> date = formatDate($this -> greedyVar("log_start_date"),"TS")."|".formatDate($this -> greedyVar("log_end_date"),"TS");
      }
      if (($this -> greedyVar("log_term"))) {
        $util -> term = $this -> greedyVar("log_term");
      }
      
      if (is_numeric($term) && ($term < 1000000)) {
        $num = $util -> term;
      } else {
        $num = 0;
      }
      
      sfConfig::set("search",$util -> term);
      sfConfig::set("num",$num);
      
    } else {
       $util -> term = $query;
       if (is_numeric($term) && ($term < 1000000)) {
        $num = $util -> term;
      } else {
        $num = 0;
      }
      
      sfConfig::set("search",$util -> term);
      sfConfig::set("num",$num);
    }
    //$this -> showData();
    return $this -> returnList( "LogAkamai_search_datamap.xml", true, true, "standard", $util );
    
  }
      
  function logSearch( $query=false, $records=null ) {
    
    //$this -> showData();
    //$this -> showXML();  
    $util = new LogAdminSearch_format_utility( $this -> context );
    
    //if ($this -> getVar("errors") == "yes") {
    //  $util -> errors = true;
    //}
    
    if (! is_null($records)) {
      $util -> rpp = $records;
    }
    
    if (! $query) {
    	if (($this -> greedyVar("log_start_date")) && (! $this -> greedyVar("log_end_date"))) {
        //$end = formatDate($this -> postVar("cms_object_end_date"),"W3XML");
        $util -> date = "'".formatDate($this -> greedyVar("log_start_date"),"TS")."|".formatDate(null,"TS")."'";
      }
      if ((! $this -> greedyVar("log_start_date")) && ($this -> greedyVar("log_end_date"))) {
        //$start = formatDate($this -> postVar("cms_object_start_date"),"W3XML");
        $util -> date = "1970-01-01 00:00:00|".formatDate($this -> greedyVar("log_end_date"),"TS");
      }
      if (($this -> greedyVar("log_start_date")) && ($this -> greedyVar("log_end_date"))) {
        $util -> date = formatDate($this -> greedyVar("log_start_date"),"TS")."|".formatDate($this -> greedyVar("log_end_date"),"TS");
      }
      if (($this -> greedyVar("log_term"))) {
        $util -> term = $this -> greedyVar("log_term");
      }
      
      if (is_numeric($term) && ($term < 1000000)) {
        $num = $util -> term;
      } else {
        $num = 0;
      }
      
      sfConfig::set("search",$util -> term);
      sfConfig::set("num",$num);
      
    } else {
       $util -> term = $query;
       if (is_numeric($term) && ($term < 1000000)) {
        $num = $util -> term;
      } else {
        $num = 0;
      }
      
      sfConfig::set("search",$util -> term);
      sfConfig::set("num",$num);
    }
    
    //$this -> showData();
    return $this -> returnList( "LogAdmin_search_datamap.xml", true, true, "standard", $util );
    
  }
  
}?>
