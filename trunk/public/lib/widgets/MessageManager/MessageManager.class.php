<?php
  
  include_once(sfConfig::get('sf_lib_dir')."/helper/OrderHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/ImageHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/MessageManager_crud.php';
  
   class MessageManager_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	var $debug = true;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
    
    if ($this -> as_service) {
      if ($this -> getVar("id") == "reminder") {
        
				$user = getUserById( $this -> sessionVar("user_id") );
        $obj = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Purchase/query/Screening_list_datamap.xml");
        
				$c = new Criteria();
        $c -> add(AudiencePeer::FK_SCREENING_ID,$obj["data"][0]["screening_id"]);
        $c -> add(AudiencePeer::FK_USER_ID,$this -> sessionVar("user_id"));
        $audienceObj = AudiencePeer::doSelect($c);
				if (! $audienceObj) die();
				$audience = $audienceObj[0];
        $payment = PaymentPeer::retrieveByPk( $audience -> getFkPaymentId() );
        
        $message = sendOrderEmail( $user, $payment, array($audience), $obj, $this -> context );
        
        $res = new stdClass;
		    $res -> messageResponse = 
		                        array("status"=>"success",
		                              "result"=>"updated");
		    print(json_encode($res));
		    die();
        break;
      } else if ($this -> getVar("id") == "harness") {
        switch ($this -> getVar("rev")) {
          case "ticket":
            $user = getUserById( 124 );
            $payment = PaymentPeer::retrieveByPk( 788 );
            $audience = AudiencePeer::retrieveByPk( 5991 );
            $this -> setGetVar("rev","usKYvtqzXVmB");
            $obj = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Purchase/query/Screening_list_datamap.xml");
            
            $mail_view = new sfPartialView($this -> context, 'widgets', 'ticket_email', 'ticket_email' );
            $mail_view->getAttributeHolder()->add(array("user"=>$user,"film"=>$obj["data"][0],"order"=>$payment,"item"=>$audience));
            $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/Ticket_html.template.php";
            $mail_view->setTemplate($templateloc);
            $message = $mail_view->render();
            
            $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/Ticket_text.template.php";
            $mail_view->setTemplate($templateloc);
            $altbody = $mail_view->render();
            
            print($message);
            die();
            //$recips[0]["email"] = "amadsen@gmail.com";
            $recips[0]["email"] = "amadsen@operislabs.com";
            $recips[0]["fname"] = " ";
            $recips[0]["lname"] = " ";
            $subject = "Your ticket to ".$obj["data"][0]["screening_film_name"];
            $mail = new WTVRMail( $this -> context );
            $mail -> user_session_id = "user_id";
            $mail -> queueMessage("blank",$subject,$message,$altbody,sfConfig::get("app_mail_from_address"),sfConfig::get("app_mail_from_fname"),sfConfig::get("app_mail_from_lname"),$recips);
            
            print($message);
            die();
            break;
          case "host":
            $user = getUserById( 2367 );
            $payment = PaymentPeer::retrieveByPk( 197 );
            $screening = ScreeningPeer::retrieveByPk( 149 );
            $audience = AudiencePeer::retrieveByPk( 177 );
            $this -> setGetVar("op","14");
            $obj = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/FilmView/query/Film_list_datamap.xml");
            
            $mail_view = new sfPartialView($this -> context, 'widgets', 'ticket_email', 'ticket_email' );
            $mail_view->getAttributeHolder()->add(array("user"=>$user,"film"=>$obj["data"][0],"order"=>$payment,"item"=>$audience));
            $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/" . getTicketTemplate($film["data"][0]["film_short_name"], "Host");
            $mail_view->setTemplate($templateloc);
            $message = $mail_view->render();
            
            $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/Host_text.template.php";
            $mail_view->setTemplate($templateloc);
            $altbody = $mail_view->render();
            
            //$recips[0]["email"] = "amadsen@gmail.com";
            $recips[0]["email"] = "amadsen@operislabs.com";
            $recips[0]["fname"] = " ";
            $recips[0]["lname"] = " ";
            $subject = "Your ticket to ".$obj["data"][0]["film_name"];
            $mail = new WTVRMail( $this -> context );
            $mail -> user_session_id = "user_id";
            $mail -> queueMessage("blank",$subject,$message,$altbody,sfConfig::get("app_mail_from_address"),sfConfig::get("app_mail_from_fname"),sfConfig::get("app_mail_from_lname"),$recips);
            
            print($message);
            die();
            break;
          case "invite":
            
            $this -> setSessionVar("user_id",124);
            $this -> setgetVar("user_type","test");
            
						$widgie = new Invite_PageWidget(null,null,$this -> context);
            sfConfig::set("screening_unique_key","usKYvtqzXVmB");
            $widgie -> film = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Theater/query/ScreeningByKey_list_datamap.xml");
           	
						$message = $widgie -> sendInvites( "usKYvtqzXVmB", array("amadsen@gmail.com"), "Testing the message system", "Testing the message system", true );
            $altbody = "";
            
            print($message);
            die();
             
            $recips[0]["email"] = "amadsen@operislabs.com";
            $recips[0]["fname"] = " ";
            $recips[0]["lname"] = " ";
            $subject = "Test User invites you to a screening of ".$film["data"][0]["screening_film_name"];
            $mail = new WTVRMail( $this -> context );
            $mail -> user_session_id = "user_id";
            $mail -> queueMessage("blank",$subject,$message,$altbody,sfConfig::get("app_mail_from_address"),sfConfig::get("app_mail_from_fname"),sfConfig::get("app_mail_from_lname"),$recips);
            
            print($message);
            die();
            break;
          case "invite-film":
            
            $this -> setSessionVar("user_id",124);
            $this -> setgetVar("user_type","test");
            
						$widgie = new Invite_PageWidget(null,null,$this -> context);
            $this -> setgetVar("op",56);
						$widgie -> film = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/FilmMarquee/query/Film_list_datamap.xml");
           	$widgie -> haz_screening = false;
						$message = $widgie -> sendInvites( 56, array("amadsen@gmail.com"), "Testing the message system", "Testing the message system", true );
            $altbody = "";
            
            //print($message);
            //die();
             
            $recips[0]["email"] = "amadsen@operislabs.com";
            $recips[0]["fname"] = " ";
            $recips[0]["lname"] = " ";
            $subject = "Test User invites you to a screening of ".$film["data"][0]["screening_film_name"];
            $mail = new WTVRMail( $this -> context );
            $mail -> user_session_id = "user_id";
            $mail -> queueMessage("blank",$subject,$message,$altbody,sfConfig::get("app_mail_from_address"),sfConfig::get("app_mail_from_fname"),sfConfig::get("app_mail_from_lname"),$recips);
            
            print($message);
            die();
            break;
          case "signup":
            
						$user = new UserCrud( null, null );
						$user -> populate("user_id",124);
						
						//Do a temporary timezone conversion
					  //Since these are sent as part of the client's browser process
					  //Their timezone would muck up the Ticket
					  date_default_timezone_set($film["data"][0]["screening_default_timezone_id"]);
					  
					  $mail_view = new sfPartialView($this -> context, 'widgets', 'signup_email', 'signup_email' );
					  $mail_view->getAttributeHolder()->add(array("user"=>$user -> User));
					  $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/Signup_html.template.php";
					  $mail_view->setTemplate($templateloc);
					  $message = $mail_view->render();
					  print($message);
					  die();
					  $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/Signup_text.template.php";
					  $mail_view->setTemplate($templateloc);
					  $altbody = $mail_view->render();
					  
					  date_default_timezone_set($otz);
					  
            die();
            break;
          case "post-screening":
            
						//Do a temporary timezone conversion
					  //Since these are sent as part of the client's browser process
					  //Their timezone would muck up the Ticket
					  date_default_timezone_set($film["data"][0]["screening_default_timezone_id"]);
					  sfConfig::set("screening_unique_key","usKYvtqzXVmB");
            $screens = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Theater/query/ScreeningByKey_list_datamap.xml");
           	$screen = $screens["data"][0];
						$tc = new AudienceCrud($this -> context);
			      $vars = array("audience_id"=>5991);
			      $tc->checkUnique($vars);  
			      $item = $tc -> Audience;
			      $user = getUserById(124);
			     
					  $mail_view = new sfPartialView($this -> context, 'widgets', 'ticket_email', 'ticket_email' );
					  $mail_view->getAttributeHolder()->add(array("user"=>$user,"item"=>$item,"screening"=>$screen));
					  $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/" . getTicketTemplate($screen["screening_film_short_name"], "PostScreening");
					  $mail_view->setTemplate($templateloc);
					  $message = $mail_view->render();
					  print($message);
					  die();
					  
					  $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/" . getTicketTextTemplate($screen["screening_film_short_name"], "PostScreening");
					  $mail_view->setTemplate($templateloc);
					  $altbody = $mail_view->render();
					  
            break;
          
					case "password":
            
						//Do a temporary timezone conversion
					  //Since these are sent as part of the client's browser process
					  //Their timezone would muck up the Ticket
						$theuser = UserPeer::retrieveByPk(124);
						$pass = "newpass";
							  
		        $mail_view = new sfPartialView($this -> context, 'widgets', 'password', 'password' );
					  $mail_view->getAttributeHolder()->add(array("user"=>$theuser,"pass"=>$pass));
					  $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/Password_html.template.php";
					  $mail_view->setTemplate($templateloc);
					  $message = $mail_view->render();
					  
					  $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/Password_text.template.php";
					  $mail_view->setTemplate($templateloc);
					  $altbody = $mail_view->render();
					  
					  print($message);
					  die();
					  
            break;
					
					case "reminder":
            
						//Do a temporary timezone conversion
					  //Since these are sent as part of the client's browser process
					  //Their timezone would muck up the Ticket
						//Do a temporary timezone conversion
					  //Since these are sent as part of the client's browser process
					  //Their timezone would muck up the Ticket
					  date_default_timezone_set($film["data"][0]["screening_default_timezone_id"]);
					  sfConfig::set("screening_unique_key","21jumpstlive");
            $screens = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Theater/query/ScreeningByKey_list_datamap.xml");
           	$screen = $screens["data"][0];
						$tc = new AudienceCrud($this -> context);
			      $vars = array("audience_id"=>5991);
			      $tc->checkUnique($vars);  
			      $item = $tc -> Audience;
			      $user = getUserById(124);
							  
		        $mail_view = new sfPartialView($this -> context, 'widgets', 'reminder', 'reminder' );
					  $mail_view->getAttributeHolder()->add(array("user"=>$user,"item"=>$item,"screening"=>$screen));
					  $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/Reminder_html.template.php";
					  $mail_view->setTemplate($templateloc);
					  $message = $mail_view->render();
					  print($message);
					  die();
					  
					  $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/Reminder_text.template.php";
					  $mail_view->setTemplate($templateloc);
					  $altbody = $mail_view->render();
					  
					  
            break;
								  
          default:
          	
          	break;
        }
      }
    }
    
    if ($this -> as_cli) {
	    switch ($this ->widget_vars["args"][0]) {
        case "DownloadExpiration":
          $this -> mailDownloadExpiration();
          break;
        
        case "processlocalqueue":
          
          $this -> doDebug("Initializing Local Queue","blue");
          
          $mail = new WTVRBaseMailTemplate( $this -> context );
          $mail -> debug = true;
          $mail -> processLocalMessageQueue();
          break;
          
        case "processglobalqueue":
   
          $this -> doDebug("Initializing Global Queue","blue");

          $mail = new WTVRBaseMail( $this -> context );
          $mail -> debug = true;
          $mail -> processGlobalMessageQueue();
          break;
          
        default:
        
          $mail = new WTVRBaseMailTemplate( $this -> context );
          $mail -> debug = true;
          $mail -> processLocalMessageQueue();
      
          $mail = new WTVRBaseMail( $this -> context );
          $mail -> debug = true;
          $mail -> processGlobalMessageQueue();
          break;
      }
      
      
    }
    
  }
  
  function mailDownloadExpiration() {
    $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/MessageManager/query/DownloadExpiration_list_datamap.xml");
    foreach ($list["data"] as $order) {
      $user = UserPeer::retrieveByPk($order["fk_user_id"]);
      if ($user)
        sendExpirationEmail( $order, $user, $this -> context );
    }    
  }
  
  function doDebug($message,$color="green",$background=false) {
    if ($this -> debug) {
      cli_text("[".$message."]",$color,$background);
    }
      
  }
}

?>
