<?php

class cachePageWidgetFilter extends sfFilter {
  
  var $request;
  
  public function execute( $filterChain) {
    
    $this -> request = $this->getContext()->getRequest();
    
    if (sfConfig::get("app_pagewidget_cache") == "true") {
      $cache = new Cache_PageWidget( $this -> context );
      $cache -> readCache();
    }
    
    $filterChain->execute();

  }
  
  
}
?>
