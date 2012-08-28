<?php

//Documentation is here:
//https://control.akamai.com/dl/customers/VOD/SecureStreamingIntegrationGuide.pdf
//01/21/2011 - A. Madsen

class TheaterAkamaiEtoken {
  
  var $context;
  var $baseVODUrl;
  var $streamType;
  var $streamUrl;
  var $viewingUrl;
  var $seat;
  var $film;
  var $etoken;
  var $movietype;
  var $duration;
  
  public function __construct( $context, $seat=null  ) {
    $this -> context = $context;
    $this -> baseVODUrl = "rtmp://cp113558.edgefcs.net/ondemand/mp4:constellation";
    $this -> baseSMILUrl = "mp4:constellation";
    $this -> streamType = "theater";
    $this -> seat = $seat;
    $this -> movietype = "mov";
  }
  
  public function generateStreamUrl( $film, $size="large" ) {
  	
  	switch ($this -> streamType) {
      case "theater":
        $this -> streamUrl = $this -> baseVODUrl . "/movies/".$film["data"][0]["screening_film_id"]."/movie-".$size.".".$this -> movietype;
        $this -> pathEndUrl = "constellation/movies/".$film["data"][0]["screening_film_id"]."/movie-".$size;
        $this -> payload = $this -> seat -> getFkScreeningUniqueKey();
        break;
      case "film":
        $this -> streamUrl = $this -> baseVODUrl . "/uploads/screeningResources/".$film["data"][0]["film_id"]."/trailerFile/".$film["data"][0]["film_trailer_file"];
        $this -> pathEndUrl = "constellation/uploads/screeningResources/".$film["data"][0]["film_id"]."/trailerFile/".str_replace(array(".mov",".mp4"),"",$film["data"][0]["film_trailer_file"]);
        $this -> payload = "preview_".$film["data"][0]["film_name"];
        break;
      case "home":
        $this -> streamUrl = $this -> baseVODUrl . "/uploads/screeningResources/".$film["film_id"]."/trailerFile/".$film["film_trailer_file"];
        $this -> pathEndUrl = "constellation/uploads/screeningResources/".$film["film_id"]."/trailerFile/".str_replace(array(".mov",".mp4"),"",$film["film_trailer_file"]);
        $this -> payload = "preview_".$film["film_name"];
        break;
      case "smil":
	      if (! $this -> seat) {
          return null;
        }
        $this -> streamUrl = $this -> baseSMILUrl . "/movies/".$film."/movie-".$size.".".$this -> movietype;
	      $this -> pathEndUrl = "constellation/movies/".$film."/movie-large;constellation/movies/".$film."/movie-medium;constellation/movies/".$film."/movie-small;constellation/movies/".$film."/movie-low;constellation/movies/".$film."/movie-minimum";
        $this -> payload = $this -> seat -> getFkScreeningUniqueKey();

        //$this -> streamUrl = $this -> baseSMILUrl . "/movies/".$film."/movie-".$size.".".$this -> movietype;
        //$this -> pathEndUrl = "constellation/movies/".$film."/movie-".$size;
        //$this -> payload = $this -> seat -> getFkScreeningUniqueKey();
        break;
      default:
        $this -> streamUrl = $this -> baseVODUrl . "/uploads/screeningResources/".$film["data"][0]["screening_film_id"]."/trailerFile/".$film["data"][0]["screening_film_trailer_file"];
        $this -> pathEndUrl = "constellation/uploads/screeningResources/".$film["data"][0]["screening_film_id"]."/trailerFile/".str_replace(array(".mov",".mp4"),"",$film["data"][0]["screening_film_trailer_file"]);
        $this -> payload = "preview_".$film["data"][0]["screening_unique_key"];
        break;
    }
  }
  
  public function getStreamDuration( $film ) {
  	if ($film){
			$duration = strtotime($film["data"][0]["screening_end_time"]) - strtotime(now()) + 1800;
			//putLog("USER:: ".$this -> sessionVar("user_id")." | MESSAGE:: FILM DURATION:: ".$film["data"][0]["screening_film_id"]." is ".$duration);
			return $duration;
		}
		return 84600;
	}
	
  public function generateAkamaiEtoken( $window=10, $duration=84600 ) {
    
    $token = new StreamTokenFactory();
    //$userKey = file_get_contents(sfConfig::get("sf_root_dir")."/../cert/testProfile_113558_etoken.bin");
    //putLog("TheaterAkamaiEtoken::pathEndUrl::" . $this -> pathEndUrl);
    //putLog("TheaterAkamaiEtoken::payload::" . $this -> payload);
    $AkamaiToken = $token->getToken('d',
        $this -> pathEndUrl,  // path
        null,         // User IP
        "testProfile", // User Profile
        "rjr457#2",  // password
        NULL,         // Time (Null causes current time to be used.)
        $window,        // User window
        NULL,         // User duration
        $this -> payload,         // User payload
        null);    // User key (shared secret) 
    
    $this -> etoken = $AkamaiToken->getToken();
    
  }
  
  public function generateViewUrl( $film, $size="large", $window=86400, $duration=84600 ) {
    
    $this -> generateStreamUrl( $film, $size );
    
    $this -> generateAkamaiEtoken( $window, $duration );
    
    //$path = "auth=" . $this -> etoken . "&aifp=v0001&slist=".$this -> pathEndUrl;
    //$path = urlencode("auth=" . $this -> etoken . "&aifp=".$this->payload."&slist=".$this -> pathEndUrl);
    $path = "auth=".$this -> etoken ."%26aifp=v0006%26slist=" . urlencode($this -> pathEndUrl);
    $this -> viewingUrl = $this -> streamUrl . "?" . $path;
    $this -> context ->getLogger()->info("{Akamai Token Class} Generated ".$this -> viewingUrl);
    //return $this -> streamUrl;
    return $this -> viewingUrl;
    
  }
}
?>
