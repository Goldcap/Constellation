<?php

class debugFilter extends sfFilter {
  
  var $request;
  
  public function execute( $filterChain) {
    
    if(sfConfig::get("sf_environment") == "dev") {
      $browser = getBrowserInfo();
      if($browser[0] == "Explorer") {
        $this -> context->getResponse()->addJavascript("/js/firebug1.3/build/firebug-lite.js");
      }
    }
    
    $filterChain->execute();

  }
  
  
}
?>
