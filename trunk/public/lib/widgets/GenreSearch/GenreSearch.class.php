<?php
  
  include_once(sfConfig::get('sf_lib_dir')."/helper/ContentHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/GenreSearch_crud.php';
  
   class GenreSearch_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> crud = new GenreCrud( $context );
    parent::__construct( $context );
  }

	function parse() {
	 
	 //$this -> showData();
	 $films = $this -> dataMap(sfConfig::get("sf_lib_dir")."/widgets/GenreSearch/query/genre_search_list_datamap.xml");
	 //sfConfig::set("startdate",now()."|".dateAddExt(now(),7,'d'));
   //dump("[".formatDate(now(),"W3XMLIN")." TO *]");
   sfConfig::set("startdate","[".formatDate(now(),"W3XMLIN")." TO *]");
   $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Body/query/Screening_list_datamap.xml");
   $res = jsonFilms( $this -> context, $films, $list );
   print($res);
	 die();
   
  }

}

?>
