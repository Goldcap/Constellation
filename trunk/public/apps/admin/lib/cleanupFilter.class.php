<?php

//Set Specific Session Vars in the LoginHelper
//Vendor ID => user_vendor_id
//Artist => user_artist_id
//etc...
  
class cleanupFilter extends sfFilter {
  
  public function execute( $filterChain ) {
    
    if ($this->getContext()->getRequest()->getCookie('tattoojohnny_showwishlist_cookie') != "") {
      $this -> context -> getResponse() -> setCookie ('tattoojohnny_showwishlist_cookie', "", time() - 3600, "/", ".tattoojohnny.com", 0);
    }
    
    if (($this->getContext()->getRequest()->getCookie('tattoojohnny_wishlist_cookie') != "") && ($this->getContext()->getRequest()->getCookie('ttjwishlist') != "")) {
      $this -> context -> getResponse() -> setCookie ('tattoojohnny_wishlist_cookie', "", time() - 3600, "/", ".tattoojohnny.com", 0);  
    }
    
    
    if (($this->getContext()->getRequest()->getCookie('ttjcart') == "") && ($this->getContext()->getUser()->getAttribute('ttjcart') != "")) {
      $this -> context -> getResponse() -> setCookie ('ttjcart', $this->getContext()->getUser()->getAttribute('ttjcart'), time() + (120 * 60 * 60), "/", ".tattoojohnny.com", 0);  
    }
    
    if ($this -> context -> getRequest() -> getParameter("action") != "services") {
      //Do Wishlist Conversion ASAP
      if ($this->getContext()->getRequest()->getCookie('ttjwishlist') == "") {
        
        if ($this->getContext()->getRequest()->getCookie('tattoojohnny_wishlist_cookie') != "") {
          $wl = new Wishlist_PageWidget( null,null,null );
          $guid = $wl -> saveProducts();
        } else {
          $guid = uniqid();
        }
        
        $this -> context -> getResponse() -> setCookie ('ttjwishlist', $guid, time() + ( 60 * 60 * 24 * 30 ), "/", ".tattoojohnny.com", 0);
        
      }
    }
    //$this -> context -> getResponse() -> setCookie ('ttjwishlist', "", time() - 3600, "/", ".tattoojohnny.com", 0);
    
    
    $filterChain->execute();

  }
  
}
?>
