<?php

class WTVRBaseMail extends WTVRMail {
  
  var $debug;
  var $mailer;
  var $template;
  var $error;
  var $BadRecips = array();
  var $context;
  
  function __construct( $context ) {
    parent::__construct( $context );
    $this -> context = $context;
  }
  
  function __destruct() {
  }
  
  
}
?>
