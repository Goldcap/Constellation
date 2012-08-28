<?php

//Set Specific Session Vars in the LoginHelper
//Vendor ID => user_vendor_id
//Artist => user_artist_id
//etc...
  
class cleanupFilter extends sfFilter {
  
  public function execute( $filterChain ) {
    
    if ($this->getContext()->getRequest()->getParameter('cs_referer') != "") {
        setcookie('cs_referer',$this->getContext()->getRequest()->getParameter('cs_referer'), null, "/",".constellation.tv");
    } else if (($this->getContext()->getRequest()->getCookie('cs_referer') == "")
        && ($_SERVER["HTTP_REFERER"] != "")){
        setcookie('cs_referer',$_SERVER["HTTP_REFERER"], null, "/",".constellation.tv");
    } else if ($this->getContext()->getRequest()->getCookie('cs_referer') != "") {
		  //dump($this->getContext()->getRequest()->getCookie('cs_referer'));
		}
    
		$redirect = new TitleLookup_PageWidget( $this->getContext(), null, null );
    $redirect -> getPartnerByUrl(); 
      
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
