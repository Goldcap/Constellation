<?php

class timezoneFilter extends sfFilter {
  
  public function execute( $filterChain ) {
    //$this->getContext()->getRequest()->getParameter('action')
    if ($this -> getContext()->getUser()->getAttribute("user_timezone") != "") {
      if ($this -> getContext()->getUser()->getAttribute("user_timezone") != date_default_timezone_get()) {
        date_default_timezone_set($this -> getContext()->getUser()->getAttribute("user_timezone"));
      }
    } else {
      $region = geoip_record_by_name("68.173.228.247");
      $az = geoip_time_zone_by_country_and_region($region['country_code'],$region['region']);
      $this -> getContext()->getUser()->setAttribute("user_timezone",$az);
      date_default_timezone_set($az);
    }
    $filterChain->execute();
  }
  
}
?>
