<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/BandwidthTester_crud.php';
  
   class BandwidthTester_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
	 
	 if ($this -> getVar('id') == "report") {
		  $cu = ChatUsagePeer::retrieveByPk($this -> postVar("cud"));
		  if ($cu) {
			  $cu -> setChatUsageBandwidth($this -> postVar("bandwidth"));
				$cu -> save();
			}
	 		die();
	 }
	 return $this -> widget_vars;
   
  }

	}

  ?>
