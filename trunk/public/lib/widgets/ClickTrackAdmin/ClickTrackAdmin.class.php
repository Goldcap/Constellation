<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/TrackHelper.php");
   
  //Data Abstraction classes as needed from propel
  //require_once 'crud/CodeTrackAdmin_crud.php';
  
 class ClickTrackAdmin_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	  $this -> XMLForm -> item_forcehidden = true;
    $this -> crud = new ClickTrackCrud( $context );
    parent::__construct( $context );
  }

	function parse() {
	 
   if ($this -> as_cli) {
    if ($this -> widget_vars["args"][0] == "user") {
      $sql = "select user_id from user";
      $res = $this -> propelQuery($sql);
      while ($row = $res -> fetch()) {
	      cli_text("Adding User " .$row[0],"green");
        addUserBeacons($row[0]);
      }
    }
   }
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
          if ($this -> crud -> ClickTrack -> getClickTrackCount() < 1) {
            $this -> crud -> ClickTrack -> setClickTrackCount(0);
            $this -> crud -> save(); 
          }
          break;
          case "delete":
          $this -> crud -> remove();
          break;
        }
      }
    
  }

  function doGet(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      if (($this -> getId() == 0) && ($_SERVER["REQUEST_METHOD"] != "POST")) {
        $this -> XMLForm -> item["click_track_guid"] = createBeacon();
      } else {
        $this -> pushItem();
      }
    }
    
  }

  function drawPage(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      return $this -> returnForm();
    } elseif ($this ->getOp() == "list" ) {
    	$util = new ClickTrackAdmin_format_utility( $this -> context );
      return $this -> returnList( "ClickTrackAdmin_list_datamap.xml", true, true, "standard", $util );
    }
    
  }

	}

  ?>
