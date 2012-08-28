<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/ScreeningReminder_crud.php';
  
  include_once(sfConfig::get('sf_lib_dir')."/helper/UserHelper.php");
  
  class ScreeningReminder_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> crud = new ScreeningReminderCrud( $context );
    parent::__construct( $context );
  }

	function parse() {
  
    return $this -> doGet();
    
  }

  function doGet(){
    
    $screening = new ScreeningCrud( $this -> context );
    $screening -> populate("screening_unique_key",$this -> getVar("screening"));
    if ($screening -> Screening -> getScreeningId() > 0) {
      //$user = $this -> context -> getUser();
      $user = getUser( $this -> getVar("email") );
      if (($user) && ($user[0] -> getUserId() > 0)) {
        $vars = array("fk_screening_unique_key"=>$this -> getVar("screening"),
                      "fk_user_id"=>$user[0] -> getUserId());
        $this -> crud -> checkUnique($vars);
        $this -> crud -> setFkScreeningUniqueKey($this -> getVar("screening"));
        $this -> crud -> setFkScreeningId($screening -> Screening -> getScreeningId());
        $this -> crud -> setFkUserId($user[0] -> getUserId());
        $this -> crud -> setFkUserEmail($user[0] -> getUserEmail()); 
        $this -> crud -> setDateAdded(now());
        $this -> crud -> save();
        
        $result = new StdClass();
        $result -> reminderResponse = array("result"=>"success","message"=>"Your reminder was set, you should recieve an email 1 hour prior to the screening.");
        $this -> widget_vars["result"] = json_encode($result);
        return $this -> widget_vars;
      } else {
        $result = new StdClass();
        $result -> reminderResponse = array("result"=>"failure","message"=>"Unable to find your account, please try again.");
        $this -> widget_vars["result"] = json_encode($result);
        return $this -> widget_vars;
      }
    } else{
      $result = new StdClass();
      $result -> reminderResponse = array("result"=>"failure","message"=>"Unable to find that screening, please try again.");
      $this -> widget_vars["result"] = json_encode($result);
      return $this -> widget_vars;
    }
    
  }

}

?>