<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Timezone_crud.php';
  
   class Timezone_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
	
	 date_default_timezone_set($this -> postVar("tzSelectorPop"));
	 $this -> getUser()->setAttribute("user_timezone",$this -> postVar("tzSelectorPop"));
	 if ($this -> getUser() -> getAttribute("user_id") > 0) {
	 $theuser = getUserById( $this -> getUser() -> getAttribute("user_id") );
	 if($theuser) {
     $theuser -> setUserDefaultTimezone($this -> postVar("tzSelectorPop"));
     $theuser -> save();
   }}
   $dst = preg_replace("/\/detail\/(.*)/","",$this -> postVar("timezone_destination"));
   $this -> redirect($dst);
	 //return $this -> widget_vars;
   
  }

}

?>
