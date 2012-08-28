<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/TimezoneRemote_crud.php';
  
   class TimezoneRemote_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
	  date_default_timezone_set("America/New_York");
	  date_default_timezone_set("Europe/Berlin");
	  sfConfig::set("startdate","[".formatDate(now(),"W3XMLIN")." TO * ]");
   
	  //Next Screening
		$next = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/HomeFilmList/query/HomeFilmListNext_list_datamap.xml");
		$this -> widget_vars["next"] = $next["data"];
		
		sfConfig::set("sid",0);
		$carousel = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/HomeFilmList/query/HomeFilmListFeatured_list_datamap.xml",true,"array",$util);
	  $this -> widget_vars["carousel"] = $carousel["data"];
	  
		$zo = date_default_timezone_get();
		$newdate = "2012-01-09T18:00:00Z";
		$tz = "America/New_York";
	  $newdate = formatDate($newdate,"W3XMLOUT",$tz);
	  $out2 = "RAW::".$newdate."<br />";
	  $out2 = $out2 . "SYSTEM TIMEZONE::".$zo."<br />";
	  $out2 = $out2 . "FORMAT TIMEZONE::".$tz."<br />";
	  $out2 = $out2 . "DATE::".formatDate($newdate,"MDY")."<br />";
	  $out2 = $out2 . "TIME::".date("H:i A",strtotime($newdate))."<br />";
	  
		QAMail($out2,"Time Out Mail",false,"amadsen@gmail.com");
		
		$this -> widget_vars["form"] = $out2;
	  return $this -> widget_vars;
   
	  
  }


	}

  ?>