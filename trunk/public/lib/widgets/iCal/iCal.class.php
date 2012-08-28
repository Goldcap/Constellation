<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/iCal_crud.php';
  
   class iCal_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
	 
	 $film = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/iCal/query/Screening_list_datamap.xml");
   date_default_timezone_set('UTC');
   
   $time = new DateTime(date("Y-m-d H:i:s",strtotime($film["data"][0]["screening_date"])));
   $starttime = $time -> format("Ymd\THi\Z");
   $timeparts = explode(":",$film["data"][0]["screening_film_running_time"]);
   $i = new DateInterval( 'P0Y0M0DT'.sprintf("%02d",$timeparts[0])."H".sprintf("%02d",$timeparts[1])."M".sprintf("%02d",$timeparts[2])."S" );
   $time->add($i);
   $endtime = $time -> format("Ymd\THi\Z");
   $time = new DateTime();
   $timestamp = $time -> format("Ymd\THi\Z");
   //$date = new DateTime('2000-01-01');
   //$date->add();
   //dump($endtime);
   //date("Ymd\THi\Z",strtotime($film["data"][0]["screening_date"]))."
	 $ical = "BEGIN:VCALENDAR
            PRODID:-//Constellation.tv//Constellation Calendar //EN
            VERSION:2.0
            CALSCALE:GREGORIAN
            METHOD:REQUEST
            BEGIN:VEVENT
            DTSTART:".$starttime."
            DTEND:".$endtime."
            DTSTAMP:".$timestamp."
            ORGANIZER;CN=Constellation.tv:mailto:info@constellation.tv
            UID:".md5(uniqid(mt_rand(), true))."@constellation.tv
            DESCRIPTION:".$film["data"][0]["screening_film_name"]."\nView this screening at http://".sfConfig::get("app_domain")."/screening/".$film["data"][0]["screening_unique_key"]."
            LOCATION:http://".sfConfig::get("app_domain")."/screening/".$film["data"][0]["screening_unique_key"]."
            SUMMARY:Constellation.tv Screening of ".$film["data"][0]["screening_film_name"]."
            END:VEVENT
            END:VCALENDAR";
    
    //set correct content-type-header
    header('Content-type: text/calendar; charset=utf-8');
    header('Content-Disposition: inline; filename='.cleanFileName($film["data"][0]["screening_film_name"]).'.ics');
    echo $ical;
    exit;
	 
    
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
