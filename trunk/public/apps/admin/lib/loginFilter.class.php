<?php

//Set Specific Session Vars in the LoginHelper
//Vendor ID => user_vendor_id
//Artist => user_artist_id
//etc...
  
include_once(sfConfig::get('sf_lib_dir')."/helper/LoginHelper.php");
include_once(sfConfig::get('sf_lib_dir')."/helper/CryptHelper.php");

class loginFilter extends sfFilter {
  
  public function execute( $filterChain ) {
    
    if (strtolower($this->getContext()->getRequest()->getParameter('action')) == "logout") {
      
      $login = new AdminLogin_PageWidget( $this->getContext(), null, null );
      $login -> logOut();
    
    } elseif (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST["styroname"])) && ($_POST["styroname"] == "Login")) {
      
      $login = new AdminLogin_PageWidget( $this->getContext(), null, null );
      $login -> doPost();
      
    }
    
    $filterChain->execute();

  }
  
}
?>
