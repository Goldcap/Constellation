<?php

class debugFilter extends sfFilter {
  
  var $request;
  
  public function execute( $filterChain) {
    
    if(sfConfig::get("sf_environment") == "dev") {
      $browser = getBrowserInfo();
      if($browser[0] == "Explorer") {
        $this -> context->getResponse()->addJavascript("http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js");
      }
    }
    
    $filterChain->execute();

  }
  
  
}
?>
