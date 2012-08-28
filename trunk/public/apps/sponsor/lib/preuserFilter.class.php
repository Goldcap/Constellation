<?php

class preuserFilter extends sfFilter {
  
  public function execute( $filterChain ) {
    
    if (preg_match("/host/",$_SERVER["REQUEST_URI"])) {
      $filterChain->execute();
    }
    if (preg_match("/purchase/",$_SERVER["REQUEST_URI"])) {
      $filterChain->execute();
    }
    if (preg_match("/services/",$_SERVER["REQUEST_URI"])) {
      $filterChain->execute();
    }
    //$this->getContext()->getRequest()->getParameter('action')
    if (($this->getContext()->getRequest()->getCookie('cs_user') == "") 
        && ($_SERVER["REQUEST_URI"] != "/preuser")
        && ($_SERVER["REQUEST_URI"] != "/preuser?err=login&errs=email")
        && ($_SERVER["REQUEST_URI"] != "/preuser?err=login&errs=pass")
        && ($_SERVER["REQUEST_URI"] != "/preuser/join_success")
        && ($_SERVER["REQUEST_URI"] != "/services/PreUser/join")
        && ($_SERVER["REQUEST_URI"] != "/services/PreUser/signin")) {
      $uri = "http://".sfConfig::get("app_domain")."/preuser";
      header( "Location: ".$uri ) ;
      die();
    }
    
    $filterChain->execute();
  }
  
}
?>
