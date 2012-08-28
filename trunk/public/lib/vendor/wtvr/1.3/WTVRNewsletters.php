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

include_once("WTVRMail.php");

include_once("wtvr/WTVRBaseMail.php");
/**
* XML Form Utilites for string manipulation and flow control
*/
include_once("crud/wtvr_newsletter_crud.php");

/**
* XML Form Utilites for string manipulation and flow control
*/
include_once("crud/wtvr_newsletter_asset_crud.php");

/**
* XML Form Utilites for string manipulation and flow control
*/
include_once("crud/wtvr_newsletter_asset_type_crud.php");


/**
 * The Form Controller class, responsible for drawing the form, calling the validator, adding "Items" and "ItemLists", and navigating through formsets
 * @package WTVRMail
 * @subpackage classes
 */
class WTVRNewsletters extends GlobalBase {
  
  var $MAILOBJ;
  var $VARIABLES;
  var $newsletter;
  
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
  function __construct( $vars = false ) {
    parent::__construct( $vars );
    $this -> VARIABLES = $vars;
    $this -> debug = false;
    $this -> MAILOBJ = new WTVRBaseMail( $this -> VARIABLES );
    $this -> newsletter = new wtvr_newsletter_crud( $this -> VARIABLES );
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
  function queueNewsletter($newsletter, $subject, $recips ) {
    include_once("crud/wtvr_user_crud.php");
    
    $user = new wtvr_user_crud( $this -> VARIABLES , $this -> sessionVar( $this -> user_session_id ));
    $this -> newsletter -> hydrate( $newsletter );
    
    $message_id = $this -> MAILOBJ -> queueMessage(
      $this -> newsletter -> getWtvrNewsletterTemplate(),
      $subject,
      null,
      null,
      $user -> getWtvrUserEmail(),
      $fname,
      $lname,
      $recips,
      "newsletters"
    );
    
    $this -> newsletter -> setWtvrNewsletterStatus(1);
    $this -> newsletter -> save();
    
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
  function getWtvrNewsletterByUUID( $uuid ) {
    $c = new Criteria();
    $c->add(WtvrNewsletterPeer::FK_WTVR_MESSAGE_IDENTIFIER,$uuid);
    $c -> setLimit(1);
    $wtvr_message = WtvrNewsletterPeer::doSelect($c);
    
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
  function getWtvrNewsletterByID( $id ) {
    $c = new Criteria();
    $c->add(WtvrNewsletterPeer::WTVR_NEWSLETTER_ID,$id);
    $c -> setLimit(1);
    $wtvr_newsletter = WtvrNewsletterPeer::doSelect($c);
    
    if ($wtvr_newsletter) {
      return $wtvr_newsletter[0];
    } else {
      return false;
    }
  }
  
  //$this -> message_admin = (($this -> getVar("action") == "view") && ($this -> getVar("id") > 0) && ($this -> sessionVar($this -> user_session_id) > 0));
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
  function getWtvrNewsletterAdmin( $id, $user_id ) {
    $c = new Criteria();
    $c->add(WtvrNewsletterPeer::WTVR_NEWSLETTER_ID,$id);
    $c->add(WtvrNewsletterPeer::FK_WTVR_USER_ID,$user_id);
    $c -> setLimit(1);
    $count = WtvrNewsletterPeer::doCount($c);
    
    if ($count > 0) {
      return true;
    } else {
      return false;
    }
  }
  
  function getNewsletterAssetsByNewsletter( $id = false ) {
        
    $c = new Criteria();
    
    if ($id > 0) {
      $c->add(WtvrNewsletterAssetPeer::FK_WTVR_NEWSLETTER_ID,$id);
    } else {
      return false;
    }
    if ($limit > 0) {
      $c->setLimit( $limit );
    }
    
    if ($offset > 0) {
      $c->setOffset( $offset );
    }
    
    $c->addAscendingOrderByColumn(WTVR_ASSET_GROUP_ID);
    $c->setDistinct();
    
    $results = WtvrNewsletterAssetPeer::doSelect($c);
    
    return $results;
  }
  
}
?>
