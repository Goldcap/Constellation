<?php
  
  include_once(sfConfig::get('sf_lib_dir')."/helper/ContentHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/UserHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Body_crud.php';
  
   class Body_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
    
    //This removes users who are "temp" users
    clearUser( $this -> getUser() );
    
    $sql = "select genre_name, genre_id from genre";
    $grs = $this -> propelQuery($sql);
    $this -> widget_vars["genres"] = $grs -> fetchAll();
    
    //$this -> showData();
    $programarray = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Body/query/ProgramHome_list_datamap.xml");
    
    //$this -> showData();
    $filmarray = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Body/query/FilmHome_list_datamap.xml");
   
    if (is_array($programarray["data"])) {
      $filmdata = array_merge($programarray["data"],$filmarray["data"]);
      $films = $filmarray;
      $films["data"] = $filmdata;
      $films["meta"]["totalresults"] = count($filmdata);
    } else {
      $films = $filmarray;
    }
    
    //Upcoming Screenings
    //$this -> showData();
    sfConfig::set("startdate","[".formatDate(now(),"W3XMLIN")." TO ".formatDate(formatDate(now(),'TSCeiling'),"W3XMLIN")."]");
    $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Body/query/Screening_list_datamap.xml");
    
    //Featured Screenings
    $feat = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Body/query/ScreeningFeatured_list_datamap.xml");
    
    $this -> widget_vars["json"] = jsonFilms( $this -> context, $films, $list );
    $this -> widget_vars["carousel"] = $films["data"];
    $this -> widget_vars["upcoming_films"] = $list["data"];
    $this -> widget_vars["featured_films"] = $feat["data"];
    
    $this -> setMeta( "og:title", "Constellation.tv" );
    $this -> setMeta( "og:type", "Movie" );
    $this -> setMeta( "og:url", "http://www.constellationt.tv" );
    $this -> setMeta( "og:image", "http://cdn.constellation.tv.s3-website-us-east-1.amazonaws.com/prod/images/constellation_external.jpg" );
    $this -> setMeta( "og:site_name", "Constellation.tv" );
    $this -> setMeta( "og:description", "Constellation.tv is great films viewed online for yourself or with your friends." );
    
    return $this -> widget_vars;

  }
}

function linkList( &$val, $key ) {
  $val = linkSplit($val);
}
?>
