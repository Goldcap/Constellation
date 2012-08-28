<?php
  
  include_once(sfConfig::get('sf_lib_dir')."/helper/ContentHelper.php");
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/AllFilms_crud.php';
  
   class AllFilms_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
	 	
	 	if ($this -> as_service) {
			
			if ($this -> getVar("sort") == "showtimes") {
				$order = "asc";
			} else {
				$order = "none";
			}
			
			if ((! $this -> getVar("sort")) || ($this -> getVar("sort") == "showtimes")) {
				//$this -> setgetVar("sort","name");
			}
			
			//$this -> showData();
			$util = new AllFilms_format_utility( $this -> context );
			if (($this -> getVar("sg")) && ($this -> getVar("sg") != "All")) {
				$films = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/AllFilms/query/AllFilmsGenre_list_datamap.xml", true, "array", $util );
			} else if ($this -> getVar("featured")) {
				$films = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/AllFilms/query/AllFilmsFeatured_list_datamap.xml", true, "array", $util );
			} else {
				$films = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/AllFilms/query/AllFilms_list_datamap.xml", true, "array", $util );
			}
			
			sfConfig::set("startdate","[".formatDate(now(),"W3XMLIN")." TO *]");
	   		$list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/AllFilms/query/Screening_list_datamap.xml");
	   	
			//dump($films);
			$res = jsonFilms( $this -> context, $films, $list, false, $order);
			$result = new StdClass();
			$result -> filmList = array("totalResults"=>$films["meta"]["totalresults"],"page"=>$this -> getVar('page'),"films"=>$res);
			echo json_encode($result);
			die();
		} else {
			$films = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/AllFilms/query/AllFilms_list_datamap.xml", true, "array", $util );
			$this -> widget_vars['films'] = $films['data'];
	 		return $this -> widget_vars;
		}
	  
		// if ($this -> XMLForm -> isPosted()) {  
  //     $this -> doPost();
  //   }
  //   $this -> doGet();
    
  //   return $this -> drawPage();
    
  }
/*
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
*/
}

?>
