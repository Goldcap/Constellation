<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Help_crud.php';
  
   class Help_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name); 
	  $this -> XMLForm -> item_forcehidden = true;
    // $this -> crud = new HelpCrud( $context );
    parent::__construct( $context );
  }

	function parse() {
	  
   $this-> widget_vars["sid"] = $this -> getVar("sid"); 
   $this-> widget_vars["uid"] = $this -> getVar("uid");
   $this-> widget_vars["tid"] = $this -> getVar("tid"); 
   $this-> widget_vars["isSuccess"] = false;

   if ($_SERVER["REQUEST_METHOD"] == "POST") {   
    $this -> doPost();
    $this-> widget_vars["isSuccess"] = true; 
   }
    
	 return $this -> widget_vars;
    
  }

  function doPost(){
     $message = "MESSAGE EMAIL::" . $this -> postVar("email")."<br />"; 
     $message = $message . "MESSAGE::" . $this -> postVar("message")."<br />";
     $message = $message . "<br />";
     
     $user = UserPeer::retrieveByPk( $this -> postVar("uid") );
     
     if ($user) {
       $message = $message . "USER FULLNAME::" . $user -> getUserFullName()."<br />";
       $message = $message . "USER ID::" . $user -> getUserId()."<br />";
       $message = $message . "USER FACEBOOK ID::" . $user -> getUserFbUid()."<br />";
       $message = $message . "USER TWITTER ID::" . $user -> getUserTUid()."<br />";
       $message = $message . "USER EMAIL::" . $user -> getUserEmail()."<br />"; 
       $message = $message . "USER TIMEZONE::" . $user -> getUserDefaultTimezone()."<br />";
       $message = $message . "<br />";
     }     
     
     $message = $message . "SESSION TIMEZONE::" . date_default_timezone_get()."<br />";
     $message = $message . "<br />";
     
     $message = $message . "JAVASCRIPT::" . $this -> postVar("javascript")."<br />";
     $message = $message . "FLASH::" . $this -> postVar("flash")."<br />";
     $message = $message . "BANDWIDTH::" . $this -> postVar("bandwidth")."<br />";
     $message = $message . "BROWSER::" . $_SERVER["HTTP_USER_AGENT"]."<br />";
     $message = $message . "<br />";
     
     $c = new Criteria();
     $c->add(ScreeningPeer::SCREENING_UNIQUE_KEY,$this -> postVar("sid"));
     $screening = ScreeningPeer::doSelect($c);
     
     if($screening[0]) {
       $message = $message . "SCREENING KEY::" . $screening[0] -> getScreeningUniqueKey()."<br />";
       $message = $message . "SCREENING DATE::" . $screening[0] -> getScreeningDate()."<br />";
       $message = $message . "SCREENING TIME::" . $screening[0] -> getScreeningTime()."<br />";
       $message = $message . "SCREENING TIMEZONE::" . $screening[0] -> getScreeningDefaultTimezoneId()."<br />";
       $message = $message . "<br />";
       $this -> keys[1] = $screening[0] -> getFkFilmId();
       $this -> keys[2] = $screening[0] -> getScreeningId();
     }
     
     $c = new Criteria();
     $c->add(AudiencePeer::AUDIENCE_INVITE_CODE,$this -> postVar("tid"));
     $audience = AudiencePeer::doSelect($c);
     
     if($audience[0]) {
       $message = $message . "TICKET::" . $this -> postVar("tid")."<br />";
       $message = $message . "TICKET STATUS::" . $audience[0] -> getAudienceStatus()."<br />"; 
       $message = $message . "TICKET IP::" . $audience[0] -> getAudienceIpAddr()."<br />";  
       $message = $message . "TICKET PAYMENT::" . $audience[0] -> getFkPaymentId()."<br />";
       $message = $message . "<br />";
     }
      
     $message = $message . "DATE::" . formatDate(null,"pretty")."<br />";
      $message = $message . "<br />";
      
      $message = $message . "========================================================<br />";
      $message = $message . "============     CLIENT DEBUG INFO      ================<br />";
      $message = $message . "========================================================<br /><br />";
      $message = $message . "REMOTE ADDRESS:  ".REMOTE_ADDR()."<br /><br />";
      foreach($_SERVER as $key => $value) {
        $message = $message . $key . " = '" . $value . "'<br />";
      }
     
     if ($user) {
      $subject = "Help Message on " .formatDate(null,"pretty")." from ".$user -> getUserFullName();
     } else {
      $subject = "Help Message on " .formatDate(null,"pretty")." from ".$this -> postVar("email");      
     }
     QAMail ($message,$subject);
     $this -> keys[0] =  $this -> postVar("uid");
     $this -> keys[1] = $this -> film["data"][0]["screening_film_id"];
     $this -> keys[2] = $this -> film["data"][0]["screening_id"];
     
     $recips[0]["email"] = "contact@constellation.tv";
     $recips[0]["fname"] = "Constellation";
     $recips[0]["lname"] = "Help";
     $mail = new WTVRMail( $this -> context );
     $mail -> user_session_id = "user_id";
     $mail -> queueMessage("blank",$subject,$message,null,sfConfig::get("app_mail_from_address"),sfConfig::get("app_mail_from_fname"),sfConfig::get("app_mail_from_lname"),$recips,"mails",$this -> keys);
    
     return true;
    
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