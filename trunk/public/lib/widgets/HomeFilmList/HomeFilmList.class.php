<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/HomeFilmList_crud.php';
  
   class HomeFilmList_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
	 
   if ($this -> getUser() -> getAttribute("user_id")) {
      $userid = $this -> getUser() -> getAttribute("user_id");
   }	else {
      $userid = -1;
   }
    
   //Screening Lists, Time Offset
   //Anything starting now... to whenever
   sfConfig::set("startdate","[".formatDate(now(),"W3XMLIN")." TO * ]");
   
   //Next Screening
	 $next = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/HomeFilmList/query/HomeFilmListNext_list_datamap.xml");
	 $i=0;
	 
	 if ($next["meta"]["totalresults"] > 0) {
   foreach ($next["data"] as $afilm) {
    $sids[] = $afilm["screening_id"];
	  $sql = "select audience_id, fk_user_id from audience where fk_screening_id = ".$afilm["screening_id"]." and audience_paid_status = 2 and fk_user_id = ".$userid;
    $res = $this -> propelQuery($sql);
    $val = $res -> fetchall();
    $next["data"][$i]["screening_attending"] = false;
    foreach($val as $aval) {
			if ($aval["fk_user_id"] == $userid) {
				$next["data"][$i]["screening_attending"] = true;
			}
		}
    $i++;
    
   }}
   
   $this -> widget_vars["next"] = $next["data"];
   
   $util = new HomeFilmList_format_utility($this -> context);
	 //Featured Screenings
   if (count($sids) > 0) {
   	 //sfConfig::set("sid",0);
		 $util -> sids = $sids;
	 } else {
	 	sfConfig::set("sid",0);
	 }
   
	 //$this -> showData();
	 //$conf, $limit=true, $output_type="array", $util=false
   $carousel = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/HomeFilmList/query/HomeFilmListFeatured_list_datamap.xml",true,"array",$util);
	 
   $i=0;
   
	 if ($carousel["meta"]["totalresults"] > 0) {
   foreach ($carousel["data"] as $afilm) {
	  $carousel["data"][$i]["screening_attending"] = false;
    $carousel["data"][$i]["screening_hosting"] = false;
    $sids[] = $afilm["screening_id"];
		if ($afilm["screening_user_id"] == $userid) {
			$carousel["data"][$i]["screening_hosting"] = true;
		}
		$sql = "select audience_id, fk_user_id from audience where fk_screening_id = ".$afilm["screening_id"]." and audience_paid_status = 2";
    $res = $this -> propelQuery($sql);
    $val = $res -> fetchall();
    foreach($val as $aval) {
			if ($aval["fk_user_id"] == $userid) {
				$carousel["data"][$i]["screening_attending"] = true;
			}
		}
    $list["data"][$i]["screening_audience_count"] = count($val);
    $i++;
    
   }}
   
   $this -> widget_vars["carousel"] = $carousel["data"];
   $this -> widget_vars["carouselcount"] = $carousel["meta"]["totalresults"];
   
   $util = new HomeFilmListNextCount_format_utility($this -> context);
	 //Featured Screenings
   if (count($sids) > 0) {
		 $util -> sids = $sids;
	 } else {
	 	sfConfig::set("sid",0);
	 }
   
   //$this -> showData();
   $nextcount = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/HomeFilmList/query/HomeFilmListNextCount_list_datamap.xml",true,"array",$util);
	 $this -> widget_vars["nextcount"] = $nextcount["meta"]["totalresults"];
   //dump($nextcount["meta"]["totalresults"]);
   /*
   //All Screenings
   $screens = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/HomeFilmList/query/HomeFilmListAll_list_datamap.xml");
   $i=0;
   if ($screens["meta"]["totalresults"] > 0) {
   foreach ($screens["data"] as $afilm) {
     $sql = "select count(audience_id) from audience where fk_screening_id = ".$afilm["screening_id"]." and audience_paid_status = 2";
     $res = $this -> propelQuery($sql);
     $val = $res -> fetchall();
     $screens["data"][$i]["screening_audience_count"] = $val[0][0];
     $i++;
   }}
   
	 $this -> widget_vars["screenings"] = $screens["data"];
   */
      
	 return $this -> widget_vars;
   
  }

	}

  ?>
