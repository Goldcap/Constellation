<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/BodyLeftColumn_crud.php';
  
  class BodyLeftColumn_PageWidget extends Widget_PageWidget {
	
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
   $carousel = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/ScreeningFeatured_list_datamap.xml");
   $this -> widget_vars["carousel"] = $carousel["data"];
   
   return $this -> widget_vars;
  
  }

}
?>
