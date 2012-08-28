<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/ContentHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/FilmCarousel_crud.php';
  
   class FilmCarousel_PageWidget extends Widget_PageWidget {
	
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
			
    //$this -> showData();
    sfConfig::set("startdate","[".formatDate(now(),"W3XMLIN")." TO * ]");
    //$films = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/FilmCarousel/query/ScreeningFeatured_list_datamap.xml");
    //$screeningList = uniqueScreenings($this -> context, $films);
    //die(json_encode(array("screeningList"=>$screeningList)));
    
    //$this -> showData();
    
    //$this -> showData();
    $programarray = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/FilmCarousel/query/ProgramHome_list_datamap.xml");
    
    //$this -> showData();
    sfConfig::set("startdate",formatDate(null,"TS"));
    //dump(sfConfig::get("startdate"));
		$filmarray = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/FilmCarousel/query/FilmHome_list_datamap.xml");
    
		if (is_array($programarray["data"])) {
      $filmdata = array_merge($programarray["data"],$filmarray["data"]);
      $films = $filmarray;
      $films["data"] = $filmdata;
      $films["meta"]["totalresults"] = count($filmdata);
    } else {
      $films = $filmarray;
    }
    
    $films = screeningFilms( $this -> context, $films, "FilmCarousel" );
    $films["meta"]["totalresults"] = $films["totalresults"];
		unset($films["totalresults"]);
    
    $this -> widget_vars["films"] = $films;
    
		} else  {

	 	$this -> widget_vars["film_alt_name"] = $this -> page_vars["FilmMarquee"]["marqee_moon"][0]["film"]["film_alt_name"];
	 
	 }
	 
	 return $this -> widget_vars;
   
  }

	}

  ?>
