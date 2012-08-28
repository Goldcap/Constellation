<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/OrderHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/LoginHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/ReminderEmail_crud.php';
  
   class ReminderEmail_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
	 
 	 if ($this -> as_service) {
 	 	  if (is_numeric($this -> getVar("id"))) {
				$screenings = $this -> dataMap( sfConfig::get("sf_lib_dir")."/widgets/ReminderEmail/query/Screening_byid_datamap.xml");
			} else if ($this -> getVar("id") == 'test') {
			  $this -> setgetVar("id",8100);
				$screenings = $this -> dataMap( sfConfig::get("sf_lib_dir")."/widgets/ReminderEmail/query/Screening_byid_datamap.xml");
				$screen = $screenings["data"][0];
				$tc = new AudienceCrud($this -> context);
				if (sfConfig::get("sf_environment") == "dev") {
				 $vars = array("audience_id"=>5257);            
				} else {
					$vars = array("audience_id"=>6710);
				}
	      $tc->checkUnique($vars);
	      if ($tc -> Audience -> getAudienceId() > 0) {
					$user = getUserById($tc -> getFkUserId());
					cli_text("Sending invite to ".$user -> getUserEmail(),"green");
		      sendReminderEmail( $user, $tc -> Audience, $screen, $this -> context );
	      }
			  die();
			} else {
  			$screenings = $this -> dataMap( sfConfig::get("sf_lib_dir")."/widgets/ReminderEmail/query/ScreeningHarness_list_datamap.xml");
  		}
	 } else {
        cli_text( formatDate(time() + 21600,"W3XMLIN") . " TO " . formatDate(time() + 21900,"W3XMLIN"), "green");
        //$this -> showData();
        sfConfig::set("startdate","[".formatDate(time() + 21600,"W3XMLIN")." TO ".formatDate(time() + 21900,"W3XMLIN")." ]");
        $screenings = $this -> dataMap( sfConfig::get("sf_lib_dir")."/widgets/ReminderEmail/query/Screening_list_datamap.xml");
	
	 }
		 //Every Five Minutes, find upcoming screenings for the next hour
	 	//$this -> showData();
	 	//Six Hours + Five Minutes = (21600 to 21900)
	 	//One Hour + Five Minutes = (3600 to 3900)
	
    if (count($screenings["data"]) > 0) {
	    foreach($screenings["data"] as $screen) {
		    cli_text("Looking for reminders at screening ".$screen["screening_id"],"yellow");
		    //See if anyone for that screening wants a reminder
		    sfConfig::set("screening_id",$screen["screening_id"]);
				$film = FilmPeer::retrieveByPk($screen["screening_film_id"]);
				date_default_timezone_set($screen["screening_default_timezone_id"]);
				
				$audience = $this -> dataMap( sfConfig::get("sf_lib_dir")."/widgets/ReminderEmail/query/ReminderEmail_list_datamap.xml");
		    if (count($audience["data"]) > 0) {
			    foreach($audience["data"] as $ticket) {
						//If so, send them one, using the FILM, and optional Ticket
			      $tc = new AudienceCrud($this -> context);
			      $vars = array("audience_id"=>$ticket["audience_id"]);
			      $tc->checkUnique($vars);
			      if ($tc -> Audience -> getAudienceId() > 0) {
							$user = getUserById($tc -> getFkUserId());
							cli_text("Sending invite to ".$user -> getUserEmail(),"green");
				      sendReminderEmail( $user, $tc -> Audience, $screen, $this -> context );
			      }
					}
				}
				$reminders = $this -> dataMap( sfConfig::get("sf_lib_dir")."/widgets/ReminderEmail/query/ScreeningReminder_list_datamap.xml");
		    if (count($reminders["data"]) > 0) {
			    foreach($reminders["data"] as $remmer) {
						//If so, send them one, using the FILM, and optional Ticket
			      $tc = new AudienceCrud($this -> context);
			      $vars = array("fk_user_id"=>$remmer["fk_user_id"],"fk_screening_id"=>$screen["fk_screening_id"]);
			      $tc->checkUnique($vars);
			      if ($tc -> Audience -> getAudienceId() > 0) {
							$user = getUserById($tc -> getFkUserId());
							cli_text("Sending invite to ".$user -> getUserEmail(),"green");
				      sendReminderEmail( $user, $tc -> Audience, $screen, $this -> context );
							$sr = ScreeningReminderPeer::retrieveByPk($ticket["screening_reminder_id"]);
				      $sr -> setScreeningReminderSent( 1 );
				      $sr -> save();
				    }
					}
				}
			}
		}
		cli_text("Done","cyan");
	 //return $this -> widget_vars;
   
  }


}

?>
