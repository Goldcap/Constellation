<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php"); 
  include_once(sfConfig::get('sf_lib_dir')."/helper/OrderHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/LoginHelper.php");
  
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/ConversationNotificationEmail_crud.php';
  
   class ConversationNotificationEmail_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
	 
	 	//Every Five Minutes, find upcoming screenings for the next hour
	 	//$this -> showData();
    $notifications = $this -> dataMap( sfConfig::get("sf_lib_dir")."/widgets/ConversationNotificationEmail/query/ConversationNotificationEmail_list_datamap.xml");
		
		if (count($notifications["data"]) > 0) {
	    foreach($notifications["data"] as $notice) {
		    cli_text("Sending Notification for ".$notice["fk_conversation_guid"],"yellow");
		    $author = getUserById($notice["fk_author_id"]);
		    $cv = new ConversationCrud( $this -> context );
		    $vars = array("conversation_guid"=>$notice["fk_conversation_guid"],"conversation_sequence"=>0);
		    $cv -> checkUnique($vars);
		    dump($cv -> Conversation);
		    $user = getUserById($notice["audience_user_id"]);
		    sendReminderEmail( $user, $tc -> Audience, $screen, $film, $this -> context );
			}
		}
		cli_text("Done","cyan");
	 //return $this -> widget_vars;
   
  }


}

?>
