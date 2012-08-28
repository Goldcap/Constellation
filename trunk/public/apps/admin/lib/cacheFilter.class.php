<?php

class cacheFilter extends sfFilter {
  
  var $request;
  
  public function execute( $filterChain) {
    
    $this -> request = $this->getContext()->getRequest();
    
    $filterChain->execute();

  }
  
  
}
?>
