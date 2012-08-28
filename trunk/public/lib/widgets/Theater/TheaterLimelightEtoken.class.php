<?php

//Documentation is here:
//https://control.akamai.com/dl/customers/VOD/SecureStreamingIntegrationGuide.pdf
//01/21/2011 - A. Madsen

class TheaterLimelightEtoken {
  
  var $context;
  var $baseVODUrl;
  var $streamType;
  var $streamUrl;
  var $viewingUrl;
  var $seat;
  var $film;
  var $etoken;
  var $movietype;
  var $e;
  var $s;
  var $referer;
  
  public function __construct( $context, $seat=null  ) {
    $this -> context = $context;
    $this -> baseVODUrl = "rtmp://consteltv.fcod.llnwd.net";
    $this -> baseAppName = "a6284/o38";
    $this -> baseSMILUrl = "mp4:s";
    $this -> streamType = "theater";
    $this -> secret = "PWmEOmNZtT/vg";
    $this -> seat = $seat;
    $this -> movietype = "mov";
  }
  
  public function generateStreamUrl( $film, $size="large" ) {
    switch ($this -> streamType) {
      case "theater":
        $this -> streamUrl = $this -> movietype.":s/movies/".$film["data"][0]["screening_film_id"]."/movie-".$size.".".$this -> movietype;
        $this -> pathEndUrl = "s/movies/".$film["data"][0]["screening_film_id"]."/movie-".$size;
        break;
      case "film":
        $this -> streamUrl = $this -> baseVODUrl . "/uploads/screeningResources/".$film["data"][0]["film_id"]."/trailerFile/".$film["data"][0]["film_trailer_file"];
        $this -> pathEndUrl = "s/uploads/screeningResources/".$film["data"][0]["film_id"]."/trailerFile/".str_replace(array(".mov",".mp4"),"",$film["data"][0]["film_trailer_file"]);
        break;
      case "home":
        $this -> streamUrl = $this -> baseVODUrl . "/uploads/screeningResources/".$film["film_id"]."/trailerFile/".$film["film_trailer_file"];
        $this -> pathEndUrl = "s/uploads/screeningResources/".$film["film_id"]."/trailerFile/".str_replace(array(".mov",".mp4"),"",$film["film_trailer_file"]);
        break;
      case "smil":
        //$this -> streamUrl = $this -> movietype.":s/movies/".$film."/movie-".$size.".".$this -> movietype;
        $this -> streamUrl = "mp4:s/movies/".$film."/movie-".$size.".".$this -> movietype;
        $this -> pathEndUrl = "s/movies/".$film."/movie-".$size.".".$this -> movietype;
        break;
      default:
        $this -> streamUrl = $this -> baseVODUrl . "/uploads/screeningResources/".$film["data"][0]["screening_film_id"]."/trailerFile/".$film["data"][0]["screening_film_trailer_file"];
        $this -> pathEndUrl = "constellation/uploads/screeningResources/".$film["data"][0]["screening_film_id"]."/trailerFile/".str_replace(array(".mov",".mp4"),"",$film["data"][0]["screening_film_trailer_file"]);
        break;
    }
  }
  
  public function generateLimelightEtoken( $referer, $window=1000 ) {
    
    $this -> referer = "http://" . sfConfig::get("app_domain") ."/";
    $this -> page = $referer;
    
    //$this -> targetUri = sprintf("%s?s=%d&e=%d&ru=%d&ip=%s", $this -> streamUrl, time(), time() + $window, strlen($referrer),REMOTE_ADDR());
    $this -> s = time();
    $this -> context ->getLogger()->debug("{Limelight Token Start Time}:: ".$this -> s);
    $this -> e = time() + $window;
    $this -> context ->getLogger()->debug("{Limelight Token End Time}:: ".$this -> e);
    $targetUri = sprintf("%s?s=%d&e=%d&ru=%d&pu=%d", "/" . $this -> baseAppName . "/" . $this -> pathEndUrl, $this -> s, $this -> e, strlen($this -> referer), strlen($this -> page));
    $this -> etoken = md5($this -> secret . $this -> referer . $this -> page .$targetUri);
    $this -> context ->getLogger()->debug("{Limelight Token} HASH:: ".$this -> etoken);
  }
  
  public function generateViewUrl( $film, $size="large", $referer, $window=1000 ) {
    
    $this -> generateStreamUrl( $film, $size );
    
    $this -> generateLimelightEtoken( $referer, $window  );
    
    //$path = "auth=" . $this -> etoken . "&aifp=v0001&slist=".$this -> pathEndUrl;
    //$path = urlencode("auth=" . $this -> etoken . "&aifp=".$this->payload."&slist=".$this -> pathEndUrl);
    $this -> viewingUrl = $this -> streamUrl . "?s=".$this -> s."&e=".$this -> e."&ru=".strlen($this -> referer)."&pu=".strlen($this -> page)."&h=" . $this -> etoken;
    $this -> context ->getLogger()->info("{Limelight Token Class} Generated ".$this -> viewingUrl);
    //return $this -> streamUrl;
    return $this -> viewingUrl;
    
  }
}
?>
