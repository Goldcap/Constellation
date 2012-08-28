<?php

//Set Specific Session Vars in the LoginHelper
//Vendor ID => user_vendor_id
//Artist => user_artist_id
//etc...
  
class cleanupFilter extends sfFilter {
  
  public function execute( $filterChain ) {
    $browser = getBrowserInfo();
    if ($_SERVER["REQUEST_URI"] != "/browser") {
      if (($browser[0] == "Explorer") && (floatval($browser[1]) < 7)) {
        redirect("browser");
      }
    }
    $filterChain->execute();

  }
  
}
?>
