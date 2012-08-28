<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/ContentHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/HomeMarquee_crud.php';
  
   class HomeMarquee_PageWidget extends Widget_PageWidget {
	
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
		
    $sql = "select genre_name, genre_id from genre";
    $grs = $this -> propelQuery($sql);
    $this -> widget_vars["genres"] = $grs -> fetchAll();
    
    //$this -> showData();
    $programarray = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/HomeMarquee/query/ProgramHome_list_datamap.xml");
    
    //$this -> showData();
    $filmarray = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/HomeMarquee/query/FilmHome_list_datamap.xml");
   	
		if (is_array($programarray["data"])) {
      $filmdata = array_merge($programarray["data"],$filmarray["data"]);
      $films = $filmarray;
      $films["data"] = $filmdata;
      $films["meta"]["totalresults"] = count($filmdata);
    } else {
      $films = $filmarray;
    }
    $films = screeningFilms( $this -> context, $films );
    unset($films["totalresults"]);
    $this -> widget_vars["carousel"] = $films;
    //dump($this->widget_vars["carousel"]);
    //dump($this -> widget_vars["carousel"]);
		//Featured Screenings
		//sfConfig::set("startdate","[".formatDate(now(),"W3XMLIN")." TO * ]");
    //$feat = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/HomeMarquee/query/ScreeningFeatured_list_datamap.xml");
    //$this -> widget_vars["featured_films"] = $feat["data"];
    
    if ($this -> as_service) {
			die(json_encode($films));
		}
		
		$this -> widget_vars["action"] = $this -> getVar("action");
	 	return $this -> widget_vars;
   
  }


}

?>
