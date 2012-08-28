<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/UserFeedback_crud.php';
  
   class UserFeedback_PageWidget extends Widget_PageWidget {
	
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
	 
	 //return $this -> widget_vars;
   
	  if ($_SERVER["REQUEST_METHOD"] == "POST") {  
      $this -> doPost();
    }
    
  }

  function doPost(){
    
    $user = uniqid();
    $questions = array("feedback_problems","feedback_heard","feedback_brought","feedback_suggestions");
    
    for ($i=1;$i<5;$i++){
      $this -> crud = new FeedbackCrud( $context );
      $this -> crud -> setFkUserHash($user);
      $this -> crud -> setFkQuestionId($i);
      $this -> crud -> setResponse($_POST[$questions[$i-1]]);
      $this -> crud -> save();
      
    }
    //dump($_POST);
    /*
    array(4) {
    []=>
    string(14) "ONe one one on"
    []=>
    string(15) "Two two two two"
    []=>
    string(23) "Three three three three"
    [=>
    string(19) "Four four four four"
    }
    */
  }

}

?>
