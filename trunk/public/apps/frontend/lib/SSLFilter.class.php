<?php

class SSLFilter extends sfFilter {
  
  var $request;
  
  public function execute( $filterChain ) {
    
		$this -> request = $this->getContext()->getRequest();
    
    if (sfConfig::get("app_enforce_ssl")) {
      $ssl = new SSL_PageWidget( $this -> context );
      $ssl -> checkSSL();
    } else {
      $ssl = new SSL_PageWidget( $this -> context );
      $ssl -> set80();
    }
    
    $filterChain->execute();

  }
  
}
?>
