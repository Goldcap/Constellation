<?php
  include_once(sfConfig::get('sf_lib_dir')."/helper/ImageHelper.php");
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/FilmList_crud.php';
  
   class FilmList_PageWidget extends Widget_PageWidget {
	
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
   
   $list = $this-> page_vars["FilmMarquee"]["marqee_moon"][0]["film"];
	 $this -> widget_vars["film"] = $list;
   
   $util = new FilmList_format_utility($this -> context);
	 //Screening Lists, Time Offset
   //Add the Film Running time to "Midnight"
   //TSCeiling is a Timestamp Ceiling (i.e. 11:59:59 today)
   $starttime = formatDate(now(),'TSCeiling');
   $times = explode(":",$list["data"][0]["film_running_time"]);
   $totaltime = ($times[0] * 3600) + ($times[1] * 60) + ($times[2]);
   //Add Running time in seconds to "TSCeiling as Epoch"
   
   sfConfig::set("startdate","[".formatDate(now(),"W3XMLIN")." TO * ]");
   
	 //$this -> showXML();
	 $carousel = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/FilmList/query/FilmListFeatured_list_datamap.xml",true,"array",$util);
	 $i=0;
	 if ($carousel["meta"]["totalresults"] > 0) {
   foreach ($carousel["data"] as $afilm) {
	 //  $sql = "select audience_id, fk_user_id from audience where fk_screening_id = ".$afilm["screening_id"]." and audience_paid_status = 2";
  //   $res = $this -> propelQuery($sql);
  //   $val = $res -> fetchall();
  //   $carousel["data"][$i]["screening_attending"] = false;
  //   foreach($val as $aval) {
		// 	if ($aval["fk_user_id"] == $userid) {
		// 		$carousel["data"][$i]["screening_attending"] = true;
		// 	}
		// }
    $carousel["data"][$i]["date"] = date("g:i A T, F dS, Y", strtotime($carousel["data"][$i]['screening_date']));
    // $carousel["data"][$i]["screening_audience_count"] = count($val);
    $i++;
    
   }}
   
   $this -> widget_vars["carousel"] = $carousel["data"];
   $this -> widget_vars["carouselcount"] = $carousel["meta"]["totalresults"];

   if($this->getVar("action") == 'film'){
     if(!empty($carousel["data"])){
      $key = $carousel["data"][0]['screening_unique_key'];
      $this -> redirect('/event/' . $key );

     } else {
      $this -> redirect('/');
     }
   }


   //$this -> showData();
	 //$this -> showXML();
	 $endtime = strtotime(formatDate(null,"MDY") . " 23:59:59");
   sfConfig::set("startdate","[".formatDate(now(),"W3XMLIN")." TO ".formatDate($endtime,"W3XMLIN")." ]");
   
	 $daily = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/FilmList/query/FilmListNext_list_datamap.xml",true,"array",$util);
	 $i=0;
   
	 if ($daily["meta"]["totalresults"] > 0) {
   foreach ($daily["data"] as $afilm) {
	  $sql = "select audience_id, fk_user_id from audience where fk_screening_id = ".$afilm["screening_id"]." and audience_paid_status = 2";
    $res = $this -> propelQuery($sql);
    $val = $res -> fetchall();
    $daily["data"][$i]["screening_attending"] = false;
    foreach($val as $aval) {
			if ($aval["fk_user_id"] == $userid) {
				$daily["data"][$i]["screening_attending"] = true;
			}
		}
    $daily["data"][$i]["date"] = date("g:i A T, F dS, Y", strtotime($daily["data"][$i]['screening_date']));
    $daily["data"][$i]["screening_audience_count"] = count($val);
    $i++;
    
   }}
   
   $this -> widget_vars["daily"] = $daily["data"];
   $this -> widget_vars["dailycount"] = $daily["meta"]["totalresults"];
   
	 return $this -> widget_vars;
   
  }

	}

?>
