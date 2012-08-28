<?php

//Documentation is here:
//https://control.akamai.com/dl/customers/VOD/SecureStreamingIntegrationGuide.pdf
//01/21/2011 - A. Madsen

include_once(sfConfig::get('sf_lib_dir')."/vendor/SecureStreamingPHP-3.0.1/URLToken.php");

class TheaterAkamaiHDEtoken {
  
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
    //$this -> baseVODUrl = "rtmp://cp113558.edgefcs.net/ondemand/mp4:constellation";
    $this -> baseVODUrl = "http://contest-f.akamaihd.net";
    $this -> baseSMILUrl = "mp4:constellation";
    $this -> streamType = "theater";
    $this -> seat = $seat;
    $this -> movietype = "mov";
  }
  
  public function generateStreamUrl( $film, $size="large" ) {
  	
  	switch ($this -> streamType) {
      case "smil":
	      if (! $this -> seat) {
          return null;
        }
        //$this -> movietype = "mp4";
        $this -> pathEndUrl = "/movies/".$film."/movie-".$size.".".$this -> movietype;
        //$this -> streamUrl = "movies/".$film."/movie-".$size.".".$this -> movietype;
	      //$this -> streamUrl = "f/movies/".$film."/,movie-".$size.",.mp4.csmil";
	      $this -> streamUrl = "/movies/".$film."/movie-".$size.".".$this -> movietype;
	      $this -> payload = $this -> seat -> getFkScreeningUniqueKey();

        //$this -> streamUrl = $this -> baseSMILUrl . "/movies/".$film."/movie-".$size.".".$this -> movietype;
        //$this -> pathEndUrl = "constellation/movies/".$film."/movie-".$size;
        //$this -> payload = $this -> seat -> getFkScreeningUniqueKey();
        break;
      default:
        die("uauth");
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
	
	public function generateAkamaiEtoken( $window=84600, $duration=84600 ) {
    /*
		Authenticated URLs are created as follows:
		*/
		//dump($this -> pathEndUrl);
		//dump($window);
		$this -> etoken = urlauth_gen_url($this -> pathEndUrl,"primaryToken",time() + $window,"SaLt!@#","",$duration);
		//dump($this -> etoken);
		/*
		where:
    * $sUrlPath     is a string containing the path portion of
                    the URL with the authorization information
                    appended
    * $sUrl         is a string containing the path portion of
                    the URL (i.e., "/path/to/file.ext")
    * $sParam       is a string containing the query string
                    parameter containing the authentication
                    information
    * $nWindow      is an integer containing the length of time
                    the authentication will remain valid
    * $sSalt        is a string that will be used as a salt
                    for the authentication hash
    * $sExtract     is a string containing a specific value
                    that must be present for authorization to
                    succeed
    * $nTime        is an integer containing the time (in Unix
                    epoch format) when the token should become
                    valid
    
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
    */
    return $this -> etoken;
  }
  
  public function generateViewUrl( $film, $size="large", $window=86400, $duration=84600 ) {
    
    $this -> generateStreamUrl( $film, $size );
    
    $this -> generateAkamaiEtoken( $window, $duration );
    
    //$path = "auth=" . $this -> etoken . "&aifp=v0001&slist=".$this -> pathEndUrl;
    //$path = urlencode("auth=" . $this -> etoken . "&aifp=".$this->payload."&slist=".$this -> pathEndUrl);
    //$path = "auth=".$this -> etoken ."%26aifp=v0006%26slist=" . urlencode($this -> pathEndUrl);
    $this -> viewingUrl = $this -> streamUrl . "?primaryToken=" . $this -> etoken;
    $this -> context ->getLogger()->info("{Akamai Token Class} Generated ".$this -> viewingUrl);
    //return $this -> streamUrl;
    //dump($this -> viewingUrl);
		return $this -> viewingUrl;
    
  }
}
?>
