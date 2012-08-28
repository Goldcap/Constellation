<?php
  include_once(sfConfig::get('sf_lib_dir')."/helper/ImageHelper.php");

  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/HomeLatest_crud.php';
  
   class HomeLatest_PageWidget extends Widget_PageWidget {
	
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

    if ($this -> getUser() -> getAttribute("user_id")) {
      $userid = $this -> getUser() -> getAttribute("user_id");
    }  else {
      $userid = -1;
    }

   $util = new HomeFilmList_format_utility($this -> context);
	 sfConfig::set("sid",0);
   sfConfig::set("startdate","[".formatDate(now(),"W3XMLIN")." TO * ]");
   $carousel = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/HomeFilmList/query/HomeFilmListFeatured_list_datamap.xml",true,"array",$util);

   $i=0;
   
   if ($carousel["meta"]["totalresults"] > 0) {
     foreach ($carousel["data"] as $afilm) {
      /*$carousel["data"][$i]["screening_attending"] = false;
      $carousel["data"][$i]["screening_hosting"] = false;
      $sids[] = $afilm["screening_id"];
      if ($afilm["screening_user_id"] == $userid) {
        $carousel["data"][$i]["screening_hosting"] = true;
      }
      $sql = "select audience_id, fk_user_id from audience where fk_screening_id = ".$afilm["screening_id"]." and audience_paid_status = 2";
      $res = $this -> propelQuery($sql);
      $val = $res -> fetchall();
      $carousel["data"][$i]["screening_attending"] = false;
      foreach($val as $aval) {
        if ($aval["fk_user_id"] == $userid) {
          $carousel["data"][$i]["screening_attending"] = true;
        }
      }
      $carousel["data"][$i]["screening_audience_count"] = count($val); */
      $carousel["data"][$i]["date"] = date("g:i A T, F dS, Y", strtotime($carousel["data"][$i]['screening_date']));
      $i++;
      
     }
   }


   $this -> widget_vars["featuredFilms"] = $carousel["data"];
   $this -> widget_vars["featuredFilmsCount"] = $carousel["meta"]["totalresults"];

	  return $this -> widget_vars;
    
  }

  function doPost(){
     
     if ($this -> XMLForm -> validateForm()) {
        switch ($this -> getFormMethod()) {
          case "submit":
          $this -> crud -> write();
          break;
          case "delete":
          $this -> crud -> remove();
          break;
        }
      }
    
  }

  function doGet(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      $this -> pushItem();
    }
    
  }

  function drawPage(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      return $this -> returnForm();
    } elseif ($this ->getOp() == "list" ) {
      return $this -> returnList();
    }
    
  }

	}

  ?>