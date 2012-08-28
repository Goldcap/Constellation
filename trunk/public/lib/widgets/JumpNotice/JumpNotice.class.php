<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/21JumpNotice_crud.php';
  
  class JumpNotice_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
	 
	    if (! $this -> postVar("email")) {
				$response = new stdClass();
        $response -> response = array(
          'result'=>'failure',
          'message'=>'Sorry, that email isn\'t in our database.'
        );
        print json_encode($response);
        die();
			}
						
			//Check for email in database
      $c = new Criteria();
      $c->add(JumpPeer::JUMP_EMAIL,$this -> postVar('email'));
      $c->setLimit(1);
      $theuser = JumpPeer::doSelect($c);
      
      //Email is in the database
      if (! $theuser) {
        $tju = new Jump();
        $tju -> setJumpEmail($this -> postVar('email'));
        $tju -> setJumpReferer($this -> postVar('referer'));
        $tju -> save();
        $theuser[0] = $tju;
        
        $mail_view = new sfPartialView($this -> context, 'widgets', '21jumpstreet', '21jumpstreet' );
			  $mail_view->getAttributeHolder()->add(array("user"=>$theuser[0]));
			  $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/PreRegister_21JumpStreet_html.template.php";
			  $mail_view->setTemplate($templateloc);
			  $message = $mail_view->render();
			  
			  $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/PreRegister_21JumpStreet_text.template.php";
			  $mail_view->setTemplate($templateloc);
			  $altbody = $mail_view->render();
			  
				$subject = "Thank you for registering for the 21 Jump Street VIP List";
	      
	      //$recips[0]["email"] = "amadsen@gmail.com";
	      $recips[0]["email"] = $this -> postVar('email');
	      $recips[0]["fname"] = " ";
	      $recips[0]["lname"] = " ";
	      
	      $mail = new WTVRMail( $context );
	      $mail -> user_session_id = "user_id";
	      $mail -> queueMessage("blank",$subject,$message,$altbody,sfConfig::get("app_mail_from_address"),sfConfig::get("app_mail_from_fname"),sfConfig::get("app_mail_from_lname"),$recips);
	      
      }
			
      
      $response = new stdClass();
      $response -> response = array(
        'result'=>'success',
        'message'=>'Your confirmation has been sent to you via email.'
      );
      print json_encode($response);
      die();
      
			/*
			{
        $response = new stdClass();
        $response -> response = array(
          'result'=>'failure',
          'message'=>'Sorry, that email isn\'t in our database.'
        );
        print json_encode($response);
        die();
      }
      */
    
  }
  
}

?>
