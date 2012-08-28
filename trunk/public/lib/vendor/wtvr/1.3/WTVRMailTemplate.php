<?php

/**
 * WTVRMailTemplate.php, Styroform XML Form Controller
 * 
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
// WTVRMailTemplate

/**
 * The Form Controller class, responsible for drawing the form, calling the validator, adding "Items" and "ItemLists", and navigating through formsets
 * Creates the Message Body from a specific template
 * And puts it in the mail class Property "body"
 * @package WTVRMailTemplate
 * @subpackage classes
 */
class WTVRMailTemplate extends WTVRMail {

  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $template_location 
  */
  var $template_location;
  
  
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
  * @name Constructor
  * @param array $formsettings  - Array with both Formset, and XSL Doc
  */
  function __construct( $context ) {
    parent::__construct( $context );
    $this -> debug = false;
		$this -> message_id = false;
  }
  
  /**
  * Pulls all mail from the local message queue
  * And sends them to the global message_queue
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name prepMail  
  * @param string $address - FPO copy here
  */
  function processLocalMessageQueue() {
    $this -> doDebug("Searching for Messages in Local Generator Queue","blue");
    
    $thesemessages = $this -> getLocalMessageQueue();
    
    $this -> doDebug("Found: ".count($thesemessages)." Messages in Local Generator Queue");
    
    if (count($thesemessages) > 0) {
      foreach ($thesemessages as $message) {
        $this -> doDebug("Adding message number ".$message->getFkWtvrMessageId());
        $this -> sendMessageToGlobalQueue( $message );
        $message->setWtvrMessageQueueLocalDateSent(now());
        $message->save();
      }
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
  function sendMessageToGlobalQueue( $localmessage ) {
    $this -> doDebug("Sending message");
    
    $message = new WtvrMessageCrud( $this -> context, $localmessage -> getFkWtvrMessageId() );
    
    //Get our userset, and personalize the messages
    $message_recipients = $this -> getWtvrRecipientsByMessage( $localmessage -> getFkWtvrMessageId() );
    
    if ((! $message_recipients) || (is_null($message_recipients)) || (count($message_recipients) == 0)) {
      $this -> doDebug("Message Has No Recipients, Please check Message " . $localmessage -> getFkWtvrMessageId());
      //return false;
    } else {
      foreach($message_recipients as $message_recipient) { 
        if ( ! validEmail($message_recipient -> getWtvrMessageRecipientEmail())) {
          continue;
        }
        $this -> doDebug(" to ". $message_recipient -> getWtvrMessageRecipientEmail() );
        
        //pass the UUID and Email to the WTVR Parser
        //Which will generate an internal URL for generating the mail
        $copy = $this -> transformTemplate( $message, $message_recipient );
        $this -> queueGlobalMessage($message -> getWtvrMessageSubject(),$copy["body"],$message_recipient -> getWtvrMessageRecipientEmail(),$copy["text"],$message -> getWtvrMessageSendEmail(),$message -> getWtvrMessageSendFname(),$message -> getWtvrMessageSendLname(),$message_recipient -> getWtvrMessageRecipientFname(),$message_recipient -> getWtvrMessageRecipientLname());
      }
    }
    $this -> doDebug("Done Adding to Queue");
      
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
  function transformTemplate ( $message, $message_recipient  ) {
    
    $this -> doDebug("Transforming Template... " );
    
    $factory = new Widget_PageWidget( $this -> context );
    $factory -> init("MessageManager");
    $factory -> widget_name = "MessageManager";
    $factory -> widget_vars["message_recipient"] = $message_recipient;
    $factory -> widget_vars["message"] = $message;
    $factory -> widget_vars["domain"] = sfConfig::get("app_domain");
    
    $factory -> widget_vars["template"] = $message  -> getWtvrMessageTemplate()."_html";
    $view = "MessageManager:".$message  -> getWtvrMessageTemplate()."_html:0";
    $body = $factory -> render( $view );
    
    $factory -> widget_vars["template"] = $message  -> getWtvrMessageTemplate()."_text";
    $view = "MessageManager:".$message  -> getWtvrMessageTemplate()."_text:0";
    $text = $factory -> render( $view );
    
    $this -> doDebug("Done Transforming Template" );
    
    return array("body"=>$body,"text"=>$text);
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
  * @name Destructor
  * @param array $formsettings  - Array with both Formset, and XSL Doc
  */
  function __destruct() {
  
  }
  
}

?>
