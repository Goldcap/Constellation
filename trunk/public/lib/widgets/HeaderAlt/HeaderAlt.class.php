<?php

include_once(sfConfig::get('sf_lib_dir')."/helper/ImageHelper.php");
  
class HeaderAlt_PageWidget extends Widget_PageWidget {
	
	var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
	 
	 if ($this -> getUser() -> isAuthenticated()) {
		 sfConfig::set("startdate","[".formatDate(now(),"W3XMLIN")." TO * ]");
	   $purchases = $this -> dataMap( sfConfig::get("sf_lib_dir")."/widgets/HeaderAlt/query/Screening_list_datamap.xml", true, "array", $util );
		 $this -> widget_vars["total_purchases"] = $purchases["meta"]["totalresults"];
	 }
	 $vars = explode("?",$_SERVER["REQUEST_URI"]);
	 $this -> widget_vars["LOGOUT_URL"] = $vars[0];
	 if ($this -> getVar("action") == "account") {
	 	$this -> widget_vars["LOGOUT_URL"] = "/";
	 }
	 $this -> widget_vars["action"] = $this -> getVar("action");
	 return $this -> widget_vars;
    
  }
  
}

?>
