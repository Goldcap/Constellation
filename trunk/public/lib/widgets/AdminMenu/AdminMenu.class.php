<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/AdminMenu_crud.php';
  
   class AdminMenu_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
	 /*
  <VALUE value="1" class="col2a">Films</VALUE>
  <VALUE value="2" class="col2a">Screenings</VALUE>
  <VALUE value="3" class="col2a">Users</VALUE>
  <VALUE value="4" class="col2a">Administrators</VALUE>
  <VALUE value="5" class="col2a">Payments</VALUE>
  <VALUE value="6" class="col2a">Credits</VALUE>
  <VALUE value="7" class="col2a">Timing</VALUE>
  */
   $this -> user = $this -> getUser();
	 $cred["films"] = $this -> user->hasCredential(1);
   $cred["screenings"] = $this -> user->hasCredential(2);
   $cred["users"] = $this -> user->hasCredential(3);
   $cred["administrators"] = $this -> user->hasCredential(4);
   $cred["payments"] = $this -> user->hasCredential(5);
   $cred["credits"] = $this -> user->hasCredential(6);
   $cred["timing"] = $this -> user->hasCredential(7);
   $cred["promotions"] = $this -> user->hasCredential(8);
   $cred["genres"] = $this -> user->hasCredential(9);
   $cred["preuser"] = $this -> user->hasCredential(10);
   $cred["sponsoruser"] = $this -> user->hasCredential(11);
   $cred["reviews"] = $this -> user->hasCredential(12);
   $cred["reports"] = $this -> user->hasCredential(13);
   $cred["programs"] = $this -> user->hasCredential(14);
   $cred["feedback"] = $this -> user->hasCredential(15);
   $cred["code"] = $this -> user->hasCredential(16);
   $cred["tests"] = $this -> user->hasCredential(17);  
   $cred["log"] = $this -> user->hasCredential(18);   
   $cred["track"] = $this -> user->hasCredential(19);
   $cred["metric"] = $this -> user->hasCredential(20);
   
   return array("cred"=>$cred);
    
  }


	}

  ?>
