<?php

class securityPageWidgetFilter extends sfFilter {
  
  var $request;
  
  public function execute( $filterChain ) {
    
    $security = new Security_PageWidget();
    //$security -> checkDOS( $this -> getContext() );
    $security -> checkIP();
    $filterChain->execute();

  }
  
  
}
?>
