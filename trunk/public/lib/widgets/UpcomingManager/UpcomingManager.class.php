<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/SolrHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/UpcomingManager_crud.php';
  
   class UpcomingManager_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
      sfConfig::set("startdate","[".formatDate(now(),"W3XMLIN")." TO * ]");
      
      cli_text("Removing Old Screenings ".formatDate(null,"pretty"),"blue","white");
      deleteQuery("upcoming_end_time: [ * TO ".formatDate(now(),"W3XMLIN")." ]");
      
      $next = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/UpcomingManager/query/UpcomingFilmListNext_list_datamap.xml");
	    $i=0;
	 
      $filmlist = Array();
      $progresslist = Array();
      $shows= Array();
      
      if ($next["meta"]["totalresults"] > 0) {
       foreach($next["data"] as $showtime) {
       	//Quick Check on the Date for "in progress"
       	if ((strtotime($showtime["screening_date"])) < (strtotime( now()))) {
      	 	$showtime["upcoming_in_progress"] = true;
      	} else {
      		$showtime["upcoming_in_progress"] = false;
      	}
      	//If we already have a show in progress for this film, and this is also in progress, move on
      	if (isset($progresslist[ $showtime["screening_film_id"] ]) && ($showtime["in_progress"])) {
      		continue;
      	}
      	if (! in_array($showtime["screening_film_id"],$filmlist)) {
      		cli_text("Adding Screening ".$showtime["screening_id"],"blue","white");
          $shows["data"][] = $showtime["screening_id"];
      		//If this film is not "in progress", mark this film as represented
      		if (! $showtime["in_progress"]) {
      			$filmlist[] = $showtime["screening_film_id"];
      		} else {
      			$progresslist[ $showtime["screening_film_id"] ] = true;
      		}
      	}
       }
       
       foreach($shows["data"] as $ashow) {
         cli_text("Indexing Screening ".$ashow,"green","white");
         $solr = new SolrManager_PageWidget(null, null, $this -> context);
			   $solr -> execute("add","upcoming",$ashow);
       }
      }
      return $this -> widget_vars;
   
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