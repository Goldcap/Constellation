<?php
  
  class Auth_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));

    parent::__construct( $context );
  }

	function parse() {
	  // dump($this -> context -> getRequest() );

    switch($this->getVar('action')){
      case 'login':
        $template = 'Login';
        break;
      case 'signup':
        if($this->getVar('op') == 'connect'){
          $template = 'Connect';
        } else if($this->getVar('op') == 'invite'){
          $template = 'Invite';
        } else {
          $template = 'Signup';
        }
        break;
      case 'reset-password':
        $template = 'ResetPassword';
        break;
      default:
        $template = 'Login';
        break;       
    }



    $this -> setTemplate($template);


	  return $this -> widget_vars;

  }

  function doPost(){}

  function doGet(){}

  function drawPage(){}

}