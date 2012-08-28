<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/CryptHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/UserHelper.php");
  //Data Abstraction classes as needed from propel
  //require_once 'crud/PasswordReminder_crud.php';
  
  class PasswordReminder_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	  $this -> XMLForm -> item_forcehidden = true;
    parent::__construct( $context );
  }

	function parse() {
	  
    if ($this -> as_service) {
      $this -> resetPassword();
    }
    
    if ($this -> XMLForm -> isPosted()) {  
      $this -> doPost();
    }
    
    return $this -> drawPage();
    
  }

  function doPost(){
    if ($this -> as_service) {
      $this -> resetPassword();
    }
   /*
   if ($this -> XMLForm -> validateForm()) {
      switch ($this -> getFormMethod()) {
        case "submit":
        $this -> resetPassword();
        break;
      }
    }
    */
  }
  
  function resetPassword() {
      //Check for email in database
      $c = new Criteria();
      $c->add(UserPeer::USER_EMAIL,$this -> postVar('email'));
      $c->setLimit(1);
      $theuser = UserPeer::doSelect($c);
      
      //Email is in the database
      if ($theuser) {
        
        //$pass = $this -> generatePassword();
        
        //$theuser[0] -> setUserPassword( encrypt($pass) );
        //$theuser[0] -> save();
        
        //Update the SOLR Search Engine
        //$solr = new SolrManager_PageWidget(null, null, null);
        //$solr -> execute("add","user",$theuser[0]->getUserId());
        
        $pass = decrypt($theuser[0] -> getUserPassword());
        if ($pass == "") {
          $pass = generatePassword();
          $theuser[0] -> setUserPassword(encrypt($pass));
          $theuser[0] -> save();
          
          //Update the SOLR Search Engine
          $solr = new SolrManager_PageWidget(null, null, $this -> context);
          $solr -> execute("add","user",$theuser[0] -> getUserId());
          
          //QAMail("Null password for ".$this -> postVar('email'));
           
        }
        
        $mail_view = new sfPartialView($this -> context, 'widgets', 'password', 'password' );
			  $mail_view->getAttributeHolder()->add(array("user"=>$theuser[0],"pass"=>$pass));
			  $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/Password_html.template.php";
			  $mail_view->setTemplate($templateloc);
			  $message = $mail_view->render();
			  
			  $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/Password_text.template.php";
			  $mail_view->setTemplate($templateloc);
			  $altbody = $mail_view->render();
			  
				$subject = "Your Constellation.tv Password";
        
        //$recips[0]["email"] = "amadsen@gmail.com";
        $recips[0]["email"] = $this -> postVar('email');
        $recips[0]["fname"] = " ";
        $recips[0]["lname"] = " ";
        
        $mail = new WTVRMail( $context );
        $mail -> user_session_id = "user_id";
        $mail -> queueMessage("blank",$subject,$message,$altbody,sfConfig::get("app_mail_from_address"),sfConfig::get("app_mail_from_fname"),sfConfig::get("app_mail_from_lname"),$recips);
        
        $response = new stdClass();
        $response -> response = array(
          'result'=>'success',
          'message'=>'Your password has been sent to you via email.'
        );
        print json_encode($response);
        die();
        
      } else {
        $response = new stdClass();
        $response -> response = array(
          'result'=>'failure',
          'message'=>'Sorry, that email isn\'t in our database.'
        );
        print json_encode($response);
        die();
      }
      
      
  }
  
  function generatePassword ($length = 5) {

    return generatePassword ($length);

  }
  
  function drawPage(){
    
    //$this -> XMLForm -> item["destination"] = $_SERVER["REQUEST_URI"];
    return $this -> returnForm();
    
  }

	}

  ?>
