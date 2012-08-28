<?php

class sponsorFilter extends sfFilter {
  
  public function execute( $filterChain ) {
    
    if ($this->getContext()->getRequest()->getCookie($_SERVER["HTTP_HOST"]) == "") {
      
      $d = new WTVRData ( $this->getContext() );
      $sql = "select fk_film_id from sponsor_domain where sponsor_domain_domain = ? limit 1";
      $args[0] = $_SERVER["HTTP_HOST"];
      $res = $d -> propelArgs($sql,$args);
      if ($res -> rowCount() == 0) {
        header("Location: http://".sfConfig::get("app_domain"));
        die();
      }
      while($row = $res -> fetch()) {
        $film = $row[0];
      }
      $this -> context -> getResponse() -> setCookie ($_SERVER["HTTP_HOST"], $film, time() + 10800, "/", ".constellation.tv", 0);
      $this -> context -> getRequest() -> setAttribute("sponsor_film_id",$film);
    } else {
      $this -> context -> getRequest() -> setAttribute("sponsor_film_id",$this->getContext()->getRequest()->getCookie($_SERVER["SERVER_NAME"]));
    }
    
    $filterChain->execute();
  }
  
}
?>
