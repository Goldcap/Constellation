<?php

class preuserFilter extends sfFilter {
  
  public function execute( $filterChain ) {
    $go = false;
    
    //$this->getContext()->getRequest()->getParameter('action')
    if (($this->getContext()->getRequest()->getCookie('cs_user') == "")
        || ($this->getContext()->getRequest()->getCookie('cs_user') == "test@constellation.tv")){
        $go = false;
    } else {
      $go = true;
    }
    
    if (preg_match("/^\/(film|host|screening|purchase|services|theater|forward|lobby|logout|facebook|account)/",$_SERVER["REQUEST_URI"])) {
      $go = true;
    }
    
    /* Moved this to the action class (and TitleLookup widget)
    /* As we'd prefer not to enumerate the films
    /* This is now a lookup via db 
    if (preg_match("/^\/film/",$_SERVER["REQUEST_URI"])) {
      $dir = sfConfig::get('sf_app_dir').'/config/preUser.yml';
      $this -> preuserinfo = sfYaml::load($dir);
      if (in_array($this -> getContext() -> getRequest() -> getParameter("op"), $this -> preuserinfo["film"])) {
        $go = true;
      }
    }
    
    if (! $this -> preuserinfo) {
      $dir = sfConfig::get('sf_app_dir').'/config/preUser.yml';
      $this -> preuserinfo = sfYaml::load($dir);
    }
    if (in_array($this -> getContext() -> getRequest() -> getParameter("action"), $this -> preuserinfo["url"])) {
      $film = $this -> preuserinfo["film"][array_search($this -> getContext() -> getRequest() -> getParameter("action"), $this -> preuserinfo["url"])];
      if ($film)
        $uri = "http://".sfConfig::get("app_domain")."/film/".$film;
        header( "Location: ".$uri ) ;
        die();
    }
    */
    
    if  (($_SERVER["REQUEST_URI"] == "/preuser")
        || ($_SERVER["REQUEST_URI"] == "/preuser?err=login&errs=email")
        || ($_SERVER["REQUEST_URI"] == "/preuser?err=login&errs=pass")
        || ($_SERVER["REQUEST_URI"] == "/preuser/join_success")
        || ($_SERVER["REQUEST_URI"] == "/services/PreUser/join")
        || ($_SERVER["REQUEST_URI"] == "/services/PreUser/signin")) {
      $go = true;
    }
    
    if (! $go) {
    
      //If the name lookup works, you'll be redirected
      //Otherwise, it'll continue to the redirect after
      $redirect = new TitleLookup_PageWidget( $this->getContext(), null, null );
      $redirect -> getPartnerByUrl(); 
      
      $uri = "http://".sfConfig::get("app_domain")."/preuser";
      header( "Location: ".$uri ) ;
      die();
      
    } else {
      $filterChain->execute();
    }
    
  }
  
}
?>
