<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/PreUserAdmin_crud.php';
  
   class PreUserAdmin_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	  $this -> XMLForm -> item_forcehidden = true;
    $this -> crud = new PreUserCrud( $context );
    parent::__construct( $context );
  }

	function parse() {
	 
	  //return $this -> widget_vars;
   
    if ($this -> XMLForm -> isListPosted("preuser")) {
      foreach ($this -> postVar("checkbox") as $anid) {
        $this -> sendNotice( $anid );
      }
      $this -> setTemplate("PreUserAdminSent");
      return $this -> widget_vars;
    }
     
	  if ($this -> XMLForm -> isPosted()) {  
      $this -> doPost();
    }
    $this -> doGet();
    
    return $this -> drawPage();
    
  }

  function doPost(){
     
     if ($this -> XMLForm -> validateForm()) {
        switch ($this -> getFormMethod()) {
          case "submit":
          $this -> crud -> write();
          if ($this -> postVar("preuser_status") == "approved") {
            $this -> crud -> setPreUserStatus(1);
          } else {
            $this -> crud -> setPreUserStatus(0);
          }
          $this -> crud -> save();
          
          break;
          case "delete":
          $this -> crud -> remove();
          break;
        }
      }
    
  }

  function doGet(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      $this -> pushItem();
      if ($this -> crud -> getPreUserStatus() < 1) {
        $this -> XMLForm -> item["preuser_status"] = "not-approved";
      }
    } elseif ($this ->getOp() == "send") {
      
      $this -> sendNotice( $this -> getVar("id") );
      $this -> setTemplate("PreUserAdminSent");
      //$this -> redirect("/preuser");
    }
  }

  function drawPage(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      return $this -> returnForm();
    } if ($this ->getOp() == "send") {
      return $this -> widget_vars;
    } elseif ($this ->getOp() == "list" ) {
      //$this -> showXML();
      return $this -> returnList();
    }
    
  }
  
  function sendNotice( $id ) {
    
    $this -> crud -> hydrate( $id );
      
    $mail_view = new sfPartialView($this->context, 'widgets', 'ticket_email', 'ticket_email' );
    $mail_view->getAttributeHolder()->add(array("user"=>$this -> crud -> PreUser));
    $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/Preuser_ticket_html.template.php";
    $mail_view->setTemplate($templateloc);
    $message = $mail_view->render();
    $altbody = "";
    
    //$recips[0]["email"] = "amadsen@gmail.com";
    $recips[0]["email"] = $this -> crud -> PreUser -> getPreUserEmail();
    $recips[0]["fname"] = "Constellation";
    $recips[0]["fname"] = "User";
    $subject = "Your access to Constellation.tv";
    $mail = new WTVRMail( $this -> context );
    $mail -> user_session_id = "user_id";
    $mail -> queueMessage("blank",$subject,$message,$altbody,sfConfig::get("app_mail_from_address"),sfConfig::get("app_mail_from_fname"),sfConfig::get("app_mail_from_lname"),$recips);
    
    $this -> crud -> setPreUserStatus( 1 );
    $this -> crud -> save();
  }
  
}

?>
