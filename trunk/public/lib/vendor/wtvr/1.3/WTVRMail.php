<?php
 
 /**
 * WTVRMail.php, Styroform XML Form Controller
 * Some NOTES on WTVRMail
 * This Mail Application requires the following:
 * MySQL Tables named message_address and message_queue
 * Propel and WTVR Crud Classes for those tables
 * Find all that in WTVRBaseMail.php in the local application root.
 *
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
// WTVRMail

//Settings used by Class Globally
//Note $GLOBALS is a very very legacy construct
//But this is a very very old library
$GLOBALS["mail_from"] = "no-reply@constellation.tv";
$GLOBALS["mail_fromlname"] = "Constellation.tv";
$GLOBALS["mail_fromfname"] = "Support";

require_once(dirname(__FILE__).'/../../MailChimp/MCAPI.class.php');

/**
 * The Form Controller class, responsible for drawing the form, calling the validator, adding "Items" and "ItemLists", and navigating through formsets
 * @package WTVRMail
 * @subpackage classes
 */
class WTVRMail extends Utils_PageWidget {
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $debug 
  */
  var $debug;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $mailer  
  */
  var $mailer;
  
 /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $template
  */
  var $template;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $global_from_substitution
  */
  var $global_from_substitution;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $error  
  */
  var $error;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $BadRecips 
  */
  var $BadRecips = array();
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $UnsubscribedRecips 
  */
  var $UnsubscribedRecips = array();
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $current_recip 
  */
  var $current_recip;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $VARIABLES 
  */
  var $VARIABLES;
  
/**
* Form Constructor.
* The formsettings array should look like this, either passed in the constructor or via WTVR:
* Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
* <code> 
*  $fpo = something();
*  $fpo = something();
*  $fpo = something();
*  $fpo = something();
* </code>
* @name  Constructor
* @param array $formsettings  - Array with both Formset, and XSL Doc
*/
  function __construct( $context ) {
    if ($context != null)
    parent::__construct( $context );
    $this -> debug = false;
    $this -> mailer = false;
    $this -> omit_global_from_substitution = false;
  }
  
/**
* Form Destructor.
* The formsettings array should look like this, either passed in the constructor or via WTVR:
* Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
* <code> 
*  $fpo = something();
*  $fpo = something();
*  $fpo = something();
*  $fpo = something();
* </code>
* @name Destructor.
* @param array $formsettings  - Array with both Formset, and XSL Doc
*/
  function __destruct() {
  }

  /**
  * Overrides the "sender" name and email address
  * Set in the $GLOBALS scope
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name omit_global_from_substitution
  * @param string $abool - FPO copy here
  */
  function omit_global_from_substitution( $abool ) {
    $this -> global_from_substitution = $abool;
  }
  
  
  //
  /**
  * Sends an email immediately
  * Using the Prep and Cleanup Function
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name sendMail
  * @param string $subject - FPO copy here
  * @param string $body - FPO copy here
  * @param string $to - FPO copy here
  * @param string $text - FPO copy here
  * @param string $from - FPO copy here
  * @param string $fromfname - FPO copy here
  * @param string $fromlname - FPO copy here
  * @param string $tofname - FPO copy here
  * @param string $tolname - FPO copy here
  */
  function doMail($subject,$body,$to,$text=null,$from=null,$fromfname=null,$fromlname=null,$tofname=null,$tolname=null,$type="html") {
    
    if(! $this -> prepMail($to)) {
      return false;
    }
    
    $this -> sendMail($subject,$body,$to,$text,$from,$fromfname,$fromlname,$tofname,$tolname,$type);
    
    $this -> cleanup();
    
  }
  
  /**
  * Used for RAW mail transfers
  * Use queueMail whenever possible
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name sendMail
  * @param string $subject - FPO copy here
  * @param string $body - FPO copy here
  * @param string $to - FPO copy here
  * @param string $text - FPO copy here
  * @param string $from - FPO copy here
  * @param string $fromfname - FPO copy here
  * @param string $fromlname - FPO copy here
  * @param string $tofname - FPO copy here
  * @param string $tolname - FPO copy here
  */
  function sendMail($subject,$body,$to,$text=null,$from=null,$fromfname=null,$fromlname=null,$tofname=null,$tolname=null) {
    
    $result = $this -> sendPHPMail($subject,$body,$to,$text,$from,$fromfname,$fromlname,$tofname,$tolname);
    
  }

  /**
  * Creates PHPMail object
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name prepPHPMailer
  */
  function prepPHPMailer() {
    if (! $this -> mailer) {
      $this -> mailer = new PHPMailer();
    }
    
    //$mail->SMTPAuth = true;     // turn on SMTP authentication
    //$mail->Username = "amadsen";  // SMTP username
    //$mail->Password = "1hsvy5qb"; // SMTP password
    $this -> mailer -> IsSendmail();
    $this -> mailer -> Host = $GLOBALS["mailhost"];
    $this -> mailer -> Encoding = "base64";
    $this -> mailer -> WordWrap = 50;// set word wrap
  }

  /**
  * Triggers the Mail
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name sendPHPMail
  * @param string $subject - FPO copy here
  * @param string $body - FPO copy here
  * @param string $to - FPO copy here
  * @param string $text - FPO copy here
  * @param string $from - FPO copy here
  * @param string $fromfname - FPO copy here
  * @param string $fromlname - FPO copy here
  * @param string $tofname - FPO copy here
  * @param string $tolname - FPO copy here
  * @param string $type - FPO copy here
  */
  function sendPHPMail($subject,$body,$to,$text=null,$from=null,$fromfname=null,$fromlname=null,$tofname=null,$tolname=null,$type="html") {
    
    if (! $this -> mailer) {
      $this -> prepPHPMailer();
    }
   
    $this -> mailer -> ErrorInfo = "";
    $this -> mailer -> From = ($from != null) ? $from : $GLOBALS["mail_from"];
    
    //This inserts the Admin Default Sender unless Class Property Specifies otherwise
    if (! $this -> global_from_substitution) {
      $fromfname = ($fromfname != null) ? $fromfname : (isset($GLOBALS["mail_fromfname"]) ? $GLOBALS["mail_fromfname"]: "");
      $fromlname = ($fromlname != null) ? $fromlname : (isset($GLOBALS["mail_fromlname"]) ? $GLOBALS["mail_fromlname"]: "");
    }
    $tofname = ($tofname != null) ? $tofname : "";
    $tolname = ($tolname != null) ? $tolname : "";
    
    $this -> mailer -> FromName = $fromfname." ".$fromlname;
    $this -> mailer -> AddAddress($to,$tofname." ".$tolname);
    if ($type == "html") {
      $this -> mailer -> IsHTML(true);
    }
    $this -> mailer -> Subject = $subject;
    $this -> doDebug("Subject:: ".$subject,"blue");
    
    if (preg_match_all("/<!--ATTATCHMENT\| ([^\|]*)\|-->/",$body,$matches)) {
      foreach ($matches[1] as $match) {
        $this -> mailer -> AddAttachment($match);
      }
    }
    $this -> mailer -> Body  = $body;
    //$this -> doDebug("Body".$body,"blue");
    $this -> mailer -> AltBody = $text;
    //$this -> doDebug("Text".$text,"blue");
    //$this -> mailer -> AddBCC("amadsen@onitdigital.com","Andy Madsen");
    $this -> doDebug("Attempting to send...","yellow");
    $result = $this -> mailer -> Send();
    $this -> doDebug($result,"yellow");
    
    if (! $result ) {
      $this -> logItem("mail","Message failed from " . $fromfname." ".$fromlname . "(" .$from . ") to ".$tofname." ".$tolname."{" .$to .") on ".formatDate(null,"pretty"));
      $this -> doDebug($this -> mailer -> ErrorInfo,"red");
      $this -> error = $this -> mailer -> ErrorInfo;
      $this -> BadRecips = array_merge($this -> BadRecips,$this -> mailer -> BadRecips);
    } else {
        $this -> logItem("mail","Message success from " . $fromfname." ".$fromlname . "(" .$from . ") to ".$tofname." ".$tolname."{" .$to .") on ".formatDate(null,"pretty"));
    }
    
    $this -> mailer -> ClearAddresses();
    $this -> mailer -> Subject = "";
    $this -> mailer -> Body    = "";
    $this -> mailer -> AltBody = "";
    
    return $result;
  }
  
  /**
  * Adds mail to the message_queue
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name queueMail 
  * @param string $subject - FPO copy here
  * @param string $body - FPO copy here
  * @param string $to - FPO copy here
  * @param string $text - FPO copy here
  * @param string $from - FPO copy here
  * @param string $fromfname - FPO copy here
  * @param string $fromlname - FPO copy here
  * @param string $tofname - FPO copy here
  * @param string $tolname - FPO copy here
  */
  function queueGlobalMessage($subject,$body,$to,$text=null,$from=null,$fromfname=null,$fromlname=null,$tofname=null,$tolname=null) {
    
    if(! $this -> prepMail($to)) {
      return false;
    }
    
    $text = ($text != null) ? $text : "";
    $from = ($from != null) ? $from : ((sfConfig::get("app_mail_from_address")) ? sfConfig::get("app_mail_from_address") : "");
    $fromfname = ($fromfname != null) ? $fromfname : ((sfConfig::get("app_mail_from_fname")) ? sfConfig::get("app_mail_from_fname") : "");
    $fromlname = ($fromlname != null) ? $fromlname : ((sfConfig::get("app_mail_from_lname")) ? sfConfig::get("app_mail_from_lname") : "");
    $tofname = ($tofname != null) ? $tofname : "";
    $tolname = ($tolname != null) ? $tolname : "";
    
    $message = new WtvrMessageQueueGlobalCrud( $this -> context );
    
    //Set Message Route
    $message -> setWtvrMessageRecipient($to);
    $message -> setWtvrMessageSender($from);
    $message -> setWtvrMessageRecipientFname($tofname);
    $message -> setWtvrMessageRecipientLname($tolname);
    $message -> setWtvrMessageSenderFname($fromfname);
    $message -> setWtvrMessageSenderLname($fromlname);
    
    //Set Message Payload
    $message -> setWtvrMessageSubject($subject);
    $message -> setWtvrMessageBody($body);
    $message -> setWtvrMessageText($text);
    
    //Set Message Type
    $message -> setWtvrMessageCreated(now());
    $message -> setWtvrMessageType("html");
    $message -> save();
    return true;
  }
  
  /**
  * Determines which API We're using
  * And sends them out immediately
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name processMailQueue
  */
  function processGlobalMessageQueue() {
    
    switch (sfConfig::get("app_mail_transport")) {
      case "MailChimp":
        $this -> processGlobalMessageQueueChimp();
        break;
      case "AWS":
        $this -> processGlobalMessageQueueAWS();
        break;
      case "SMTP":
        $this -> processGlobalMessageQueueSMTP();
        break;
      default:
        QAMail("No Mail Transport Specified in WTVRMail Global Queue",false,false);
        cli_text("No Mail Transport Specified","red");
        break;
    }
    die();
    
  }
  
  /**
  * Takes all the mail from the message_queue
  * And sends them out immediately
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name processMailQueue
  */
  function processGlobalMessageQueueAWS() {
    
    $this -> doDebug("Searching for Messages in Global Queue","blue");

    $thesemessages = $this -> getGlobalMessageQueue();
    
    $this -> doDebug("Found: ".count($thesemessages)." Messages in Global Queue");
    
    $mail = new AmazonSES();
    
    foreach ($thesemessages as $message) {
      
      if ($this -> debug) {
        cli_text("[Sending to ".$message -> getWtvrMessageRecipient()."]","blue");
      }
      
      if ($this -> prepMail($message -> getWtvrMessageRecipient())) {
        
        //$res = $mail -> get_send_quota();
        $destination = array("ToAddresses"=>array('"'.$message -> getWtvrMessageRecipientFname().' '.$message -> getWtvrMessageRecipientLname().'" <'.$message -> getWtvrMessageRecipient().'>'));
        $subject["Data"] = $message -> getWtvrMessageSubject();
        $body["Text"]["Data"]=$message -> getWtvrMessageText();
        $body["Html"]["Data"]=$message -> getWtvrMessageBody();
        $payload = array("Subject"=>$subject,"Body"=>$body);
        $result = $mail -> send_email("no-reply@constellation.tv",$destination, $payload);
        
        if ($result -> status != 200) {
          $message -> setWtvrMessageResponse($result -> body -> Error -> Type . " " .$result -> body -> Error -> Code. " [" . $result -> body -> Error -> Message . "]");
          array_push($this -> UnsubscribedRecips,$message -> getWtvrMessageRecipient());
          $this -> setInvalidWtvrAddress ( $message -> getWtvrMessageRecipient() );
        } else {
          $message -> setWtvrMessageSent(now());
        }
        
        $message -> save();
        
      }
      
      $this -> mailer = null;
    }
    
    
  }
  
  /**
  * Takes all the mail from the message_queue
  * And sends them out immediately
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name processMailQueue
  */
  function processGlobalMessageQueueChimp() {
    
    //Include MailChimp Default Settings
    require_once(dirname(__FILE__).'/../../MailChimp/examples/inc/config.inc.php');

    $this -> doDebug("Searching for Messages in Global Queue","blue");

    $thesemessages = $this -> getGlobalMessageQueue();
    
    $this -> doDebug("Found: ".count($thesemessages)." Messages in Global Queue");
    
    if (count($thesemessages) == 0) {
      die();
    }
    //dump($apikey);
    //Mail Chimp It!
    //Loop over all messages in the QUEUE
    foreach ($thesemessages as $message) {
      
      $content = null;
      $opts = null;
      
      if ($this -> debug) {
        cli_text("[Sending to ".$message -> getWtvrMessageRecipient()."]","yellow");
      }
      
      /*************** COPIED CODE *****************/
      //Create a Chimp message "GROUP"
      $group = "Message_".$message -> getWtvrMessageQueueId();
      
      //Add to the Chimp Batch
      if ($this -> prepMail($message -> getWtvrMessageRecipient())) {
        $to_emails = array($message -> getWtvrMessageRecipient());
        $to_names = array($message -> getWtvrMessageRecipientFname() . " " . $message -> getWtvrMessageRecipientLname());
      
      }
      
      //This message was queued, so remove from Global Queue
      $message -> setWtvrMessageResponse("chimp");
      $message -> setWtvrMessageSent(now());
      $message -> save();
      
      
      if ($this -> debug) {
        cli_text("[Sending from ".$message -> getWtvrMessageSender()."]","yellow");
      }
      
      if ($this -> debug) {
        cli_text("Subject ".$message -> getWtvrMessageSubject(),"cyan");
      }
      
      if (preg_match("/\[\!--UNSUBSCRIBE--\]/",$message -> getWtvrMessageBody())) {
        $themess = str_replace('[!--UNSUBSCRIBE--]','<a class="unsubscribe" href="http://constellation.us2.list-manage.com/unsubscribe?u=5dcb972a18f2ca884af5f6bd9&id=7c8cebf062">Unsubscribe me from Constellation.tv.</a>',$message -> getWtvrMessageBody());
      } else {
        $themess = $message -> getWtvrMessageBody().' <a class="unsubscribe" href="http://constellation.us2.list-manage.com/unsubscribe?u=5dcb972a18f2ca884af5f6bd9&id=7c8cebf062">Unsubscribe me from Constellation.tv.</a>';
      }
      
      if (preg_match("/\[\!--UNSUBSCRIBE--\]/",$message -> getWtvrMessageText())) {
        $thetextmess = str_replace('[!--UNSUBSCRIBE--]',' http://constellation.us2.list-manage.com/unsubscribe?u=5dcb972a18f2ca884af5f6bd9&id=7c8cebf062 ',$message -> getWtvrMessageText());
      } else {
        $thetextmess = $message -> getWtvrMessageText().' http://constellation.us2.list-manage.com/unsubscribe?u=5dcb972a18f2ca884af5f6bd9&id=7c8cebf062 ';
      }
      
      if (preg_match("/\[\!--PROFILE--\]/",$message -> getWtvrMessageBody())) {
        $themess = str_replace('[!--PROFILE--]','<a class="unsubscribe" href="http://constellation.us2.list-manage.com/profile?u=5dcb972a18f2ca884af5f6bd9&id=7c8cebf062">Update subscription preferences.</a>',$themess);
      } else {
        $themess = $message -> getWtvrMessageBody().' <a class="unsubscribe" href="http://constellation.us2.list-manage.com/profile?u=5dcb972a18f2ca884af5f6bd9&id=7c8cebf062">Update subscription preferences.</a>';
      }
      
      if (preg_match("/\[\!--PROFILE--\]/",$message -> getWtvrMessageText())) {
        $thetextmess = str_replace('[!--PROFILE--]',' http://constellation.us2.list-manage.com/profile?u=5dcb972a18f2ca884af5f6bd9&id=7c8cebf062 ',$thetextmess);
      } else {
        $thetextmess = $message -> getWtvrMessageText().' http://constellation.us2.list-manage.com/profile?u=5dcb972a18f2ca884af5f6bd9&id=7c8cebf062 ';
      }
      
      $message = array(
          'html'=>$themess,
          'text'=> $thetextmess,
          'subject'=>$message -> getWtvrMessageSubject(),
          'from_name'=>"Constellation.tv",
          'from_email'=>$message -> getWtvrMessageSender(),
          'to_email'=>$to_emails,
          'to_name'=>$to_names
      );
      
      $tags = array('WelcomeEmail');
      
      $params = array(
          'apikey'=>$apikey,
          'message'=>$message,
          'track_opens'=>false,
          'track_clicks'=>false,
          'tags'=>$tags
      );
       
      $url = "http://us2.sts.mailchimp.com/1.0/SendEmail";
      //cli_text($url.'?'.http_build_query($params),"red");
      //die();
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      //curl_setopt($ch, CURLOPT_URL, $url.'?'.http_build_query($params));
      curl_setopt($ch,CURLOPT_POST,true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($params)); 
       
      $result = curl_exec($ch);
      if ($this -> debug) {
        cli_text($result,"cyan");
      }
      curl_close ($ch);
       
      $data = json_decode($result);
      if ($this -> debug) {
        cli_text("Status = ".$data->status."\n","green");
      }
      /*************** COPIED CODE *****************/
      
    }
    
  }
  
  /**
  * Takes all the mail from the message_queue
  * And sends them out immediately
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name processMailQueue
  */
  function processGlobalMessageQueueSMTP() {
    
    $this -> doDebug("Searching for Messages in Global Queue","blue");

    $thesemessages = $this -> getGlobalMessageQueue();
    
    $this -> doDebug("Found: ".count($thesemessages)." Messages in Global Queue");
    
    foreach ($thesemessages as $message) {
      $this -> prepPHPMailer();
      
      if ($this -> debug) {
        cli_text("[Sending to ".$message -> getWtvrMessageRecipient()."]","blue");
      }
      
      if ($this -> prepMail($message -> getWtvrMessageRecipient())) {
        $result = $this -> sendPHPMail(
          $message -> getWtvrMessageSubject(),
          $message -> getWtvrMessageBody(),
          $message -> getWtvrMessageRecipient(),
          $message -> getWtvrMessageText(),
          $message -> getWtvrMessageSender(),
          $message -> getWtvrMessageSenderFname(),
          $message -> getWtvrMessageSenderLname(),
          $message -> getWtvrMessageRecipientFname(),
          $message -> getWtvrMessageRecipientLname(),
          $message -> getWtvrMessageType());
        
        if (! $result) {
          $message -> setWtvrMessageResponse($this -> mailer -> ErrorInfo);
          array_push($this -> UnsubscribedRecips,$message -> getWtvrMessageRecipient());
          $this -> setInvalidWtvrAddress ( $message -> getWtvrMessageRecipient() );
        } else {
          $message -> setWtvrMessageSent(now());
        }
        
        $message -> save();
        
      }
      
      $this -> mailer = null;
    }
    
  }
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name prepMail  
  * @param string $address - FPO copy here
  */
  function getGlobalMessageQueue() {
    $c = new Criteria();

    $c->add(WtvrMessageQueueGlobalPeer::WTVR_MESSAGE_SENT,null,Criteria::ISNULL);
    $c->addAscendingOrderByColumn(WTVR_MESSAGE_CREATED);
    $c->setDistinct();
    $c->setLimit( 100 );
    
    $messages = WtvrMessageQueueGlobalPeer::doSelect($c);
    
    return $messages;
  }
  
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name prepMail  
  * @param string $address - FPO copy here
  */
  function getLocalMessageQueue() {
    $c = new Criteria();

    $c->add(WtvrMessageQueueLocalPeer::WTVR_MESSAGE_QUEUE_LOCAL_DATE_SENT,null,Criteria::ISNULL);
    $c->addAscendingOrderByColumn(WTVR_MESSAGE_QUEUE_LOCAL_DATE_ADDED);
    $c->setDistinct();
    
    $messages = WtvrMessageQueueLocalPeer::doSelect($c);
    
    return $messages;
  }
   
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name prepMail  
  * @param string $address - FPO copy here
  */
  function prepMail( $address ) {
    
    $this -> doDebug("Checking for ".$address." Globally");
    
    if(! $this -> isValidWtvrAddress($address)) {
      $this -> doDebug("Failed!","red");
      array_push($this -> BadRecips,$address);
      QAMail($address ." is unsubscribed and didn't recieve a message.");
      return false;
    }
    $this -> doDebug("Passed","blue");
    
    return true;
  }
  
  /**
  * Adds mail to the local message_queue
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name prepMail  
  * @param string $address - FPO copy here
  */
  function queueMessage($template,$subject,$body,$text,$from,$fromfname,$fromlname,$to,$scope="mails",$keys=null) {
    
    $message_crud = new WtvrMessageCrud( $this -> context );
    $vars = array("Wtvr_Message_Send_Email"=>$from,"Wtvr_Message_Subject"=>WTVRCleanString($subject),"Wtvr_Message_Body"=>WTVRCleanString($body));
    $message_crud -> checkUnique($vars);
    
    if ($this-> sessionVar($this -> user_session_id)) {
      $message_crud -> setWtvrMessageSendUserId($this-> sessionVar($this -> user_session_id));
    }
    $message_crud -> setWtvrMessageScope( $scope );
    $message_crud -> setWtvrMessageSendEmail( $from );
    $message_crud -> setWtvrMessageSendFname( $fromfname );
    $message_crud -> setWtvrMessageSendLname( $fromlname );
    $message_crud -> setWtvrMessageSubject( WTVRCleanString($subject) );
    ($message_crud -> getWtvrMessageDate()) ? null : $message_crud -> setWtvrMessageDate( now() );
    $message_crud -> setWtvrMessageBody( WTVRCleanString($body) );
    $message_crud -> setWtvrMessageText( WTVRCleanString($text) );
    $message_crud -> setWtvrMessageTemplate( $template );
    $token = $this -> genUUID();
    $message_crud -> setWtvrMessageIdentifier( $token );
    if ((! is_null($keys)) && (is_array($keys))) {
      if (isset($keys[0])) {
        $message_crud -> setWtvrMessageKey1( $keys[0] );
      }
      if (isset($keys[1])) {
        $message_crud -> setWtvrMessageKey2( $keys[1] );
      }
      
      if (isset($keys[2])) {
        $message_crud -> setWtvrMessageKey3( $keys[2] );
      }
      
      if (isset($keys[3]) && is_int($keys[3])) {
        $message_crud -> setWtvrMessageKey4( $keys[3] );
      }
    }
    $message_crud -> save();
    
    foreach ($to as $recip) {
      if (! validEmail($recip["email"])) { continue; }
      $message_recipient_crud = new WtvrMessageRecipientCrud( $this -> context );
      $vars = array("Wtvr_Message_Recipient_Email"=>$recip["email"],"Fk_Wtvr_Message_Id"=>$message_crud -> getWtvrMessageId() );
      $message_recipient_crud -> checkUnique($vars);
      $message_recipient_crud -> setFkWtvrMessageId( $message_crud -> getWtvrMessageId() );
      $message_recipient_crud -> setWtvrMessageRecipientEmail( $recip["email"] );
      (isset($recip["fname"])) ? $message_recipient_crud -> setWtvrMessageRecipientFname( $recip["fname"] ) : "";
      (isset($recip["lname"])) ? $message_recipient_crud -> setWtvrMessageRecipientLname( $recip["lname"] ) : "";
      ($message_recipient_crud -> getWtvrMessageRecipientDateAdded()) ? null : $message_recipient_crud -> setWtvrMessageRecipientDateAdded( now() );
      $message_recipient_crud -> save();       
    }
    
    $thismessage = new WtvrMessageQueueLocalCrud( $this -> context );
    $vars = array("fk_wtvr_message_id"=>$message_crud -> getWtvrMessageId());
    $thismessage -> checkUnique($vars);
    if ((! is_null($thismessage -> getWtvrMessageQueueLocalId())) && (is_null($thismessage -> getWtvrMessageQueueLocalDateSent()))) {
			return $thismessage;
		}
    $thismessage -> setFkWtvrMessageId( $message_crud -> getWtvrMessageId() );
    $thismessage -> setWtvrMessageQueueLocalDateAdded( now() );
    $thismessage -> save();
    return $thismessage;
    
  }
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name prepMail  
  * @param string $address - FPO copy here
  */
  function genUUID( $iter = 0 ) {
    $iter++;
    $token = uniqid(md5(rand()), true);
    $c = new Criteria();
    $c->add(WtvrMessagePeer::WTVR_MESSAGE_IDENTIFIER,$token);
    $message = WtvrMessagePeer::doSelect($c);
    if (($iter < 5) && ($message)) {
      $this -> getUUID($iter);
    } elseif ($iter >= 5) {
      errorDirect();
    } else {
      return $token;
    }
  }
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name prepMail  
  * @param string $address - FPO copy here
  */
  function isValidWtvrAddress( $address ) {
     $c = new Criteria();
  
    $c->add(WtvrMessageAddressPeer::WTVR_MESSAGE_EMAILADDRESS,$address);
    $c->setDistinct();
    
    if (WtvrMessageAddressPeer::doCount($c) == 0) {
      $crud = new WtvrMessageAddressCrud( $this -> context );
      $crud -> setWtvrMessageEmailaddress($address);
      $crud -> setWtvrMessageAddressValid(1);
      $crud -> save();
      return true;
    } else {
      $message_address = WtvrMessageAddressPeer::doSelect($c);
      if ($message_address[0] -> getWtvrMessageAddressValid() == 1) {
        return true;
      }
      return false;
    }
  }
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name prepMail  
  * @param string $address - FPO copy here
  */
  function setInvalidWtvrAddress( $address ) {
    //Check if the user valid
    $current_recip = new WtvrMessageAddressCrud( null, null );
    $vars = array("wtvr_message_emailaddress"=>$address);
    $current_recip -> checkUnique($vars);
    $current_recip -> setWtvrMessageAddressValid(1);
    $current_recip -> save();
    
    return true;
     
  }
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name transformTemplate
  * @param string $scope - FPO copy here
  * @param string $object - FPO copy here
  * @param string $series - FPO copy here
  */
  function getWtvrRecipientsByMessage( $id ) {
    $c = new Criteria();
    $c->add(WtvrMessageRecipientPeer::FK_WTVR_MESSAGE_ID,$id);
    $c->setDistinct();
    $c->addAscendingOrderByColumn(WTVR_MESSAGE_RECIPIENT_DATE_ADDED);
    $message_users = WtvrMessageRecipientPeer::doSelect($c);
    if ($message_users) {
      return $message_users;
    } else {
      return false;
    }
  }
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name transformTemplate
  * @param string $scope - FPO copy here
  * @param string $object - FPO copy here
  * @param string $series - FPO copy here
  */ 
  function getWtvrMessageByUUID( $uuid ) {
    $c = new Criteria();
    $c->add(WtvrMessagePeer::WTVR_MESSAGE_IDENTIFIER,$uuid);
    $c -> setLimit(1);
    $wtvr_message = WtvrMessagePeer::doSelect($c);
    
    if ($wtvr_message) {
      return $wtvr_message[0];
    } else {
      return false;
    }
  }
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name transformTemplate
  * @param string $scope - FPO copy here
  * @param string $object - FPO copy here
  * @param string $series - FPO copy here
  */ 
  function getWtvrMessageRecipient( $id, $email) {
    $c = new Criteria();
    $c->add(WtvrMessageRecipientPeer::FK_WTVR_MESSAGE_ID,$id);
    $c->add(WtvrMessageRecipientPeer::WTVR_MESSAGE_RECIPIENT_EMAIL,$email);
    $c -> setLimit(1);
    $wtvr_message_recipient = WtvrMessageRecipientPeer::doSelect($c);
    
    if ($wtvr_message_recipient) {
      return $wtvr_message_recipient[0];
    } else {
      return false;
    }
  }
  
  
  /**
  * These functions create IMAP Users
  * Via the SHELL Script in /bin/user.sh
  * And the cron job running in /bin/cron_user.sh
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name addEmailUser  
  * @param string $username - FPO copy here
  * @param string $password - FPO copy here
  * @param string $forward  - FPO copy here
  */
  function getWtvrMessageAdmin( $id, $user_id ) {
    $c = new Criteria();
    $c->add(WtvrMessagePeer::WTVR_MESSAGE_ID,$id);
    $c->add(WtvrMessagePeer::WTVR_MESSAGE_SEND_USER_ID,$user_id);
    $c -> setLimit(1);
    $count = WtvrMessagePeer::doCount($c);
    
    if ($count > 0) {
      return true;
    } else {
      return false;
    }
  }
    
  /**
  * These functions create IMAP Users
  * Via the SHELL Script in /bin/user.sh
  * And the cron job running in /bin/cron_user.sh
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name addEmailUser  
  * @param string $username - FPO copy here
  * @param string $password - FPO copy here
  * @param string $forward  - FPO copy here
  */
  function addEmailUser( $username, $password, $forward ) {
    $user = new MailUser();
    $user -> addEmailUser( $username, $password, $forward );
  }
      
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name deleteEmailUser 
  * @param string $username - FPO copy here
  */
  function deleteEmailUser( $username ) {
    $user = new MailUser();
    $user -> deleteEmailUser( $username );
  }
      
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name moveEmailUser
  * @param string $username - FPO copy here
  * @param string $newname - FPO copy here
  * @param string $forward  - FPO copy here
  */
  function moveEmailUser( $username, $newname, $forward ) {
    $user = new MailUser();
    $user -> moveEmailUser( $username, $newname, $forward );
  }
        
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name changeEmailUser
  * @param string $username - FPO copy here
  * @param string $forward  - FPO copy here
  */
  function changeEmailUser( $username, $forward ) {
    $user = new MailUser();
    $user -> changeEmailUser( $username, $forward );
  }
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name doDebug
  * @param string $message - FPO copy here
  */
  function doDebug($message,$color="green",$background=false) {
    if ($this -> debug) {
      cli_text("[".$message."]",$color,$background);
    }
      
  }
  
  //Scavenges sending errors
  function cleanup() {
    if (count($this -> BadRecips) > 0) {
      foreach ($this -> BadRecips as $recip) {
        $this -> doDebug("Bad Recipient Flagged: ".$recip,"red");
        $this -> setInvalidWtvrAddress( $recip );
      }
    }
    $this -> mailer = null;
  }
  
}
?>
