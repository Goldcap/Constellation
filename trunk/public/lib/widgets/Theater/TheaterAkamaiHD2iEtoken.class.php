<?php

//Documentation is here:
//https://control.akamai.com/dl/customers/VOD/SecureStreamingIntegrationGuide.pdf
//01/21/2011 - A. Madsen

include_once(sfConfig::get('sf_lib_dir')."/vendor/EdgeAuth-2.0.1/php/AkamaiToken.php");

class TheaterAkamaiHD2iEtoken {
  
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
    $this -> baseVODUrl = "http://conseczeritest-f.akamaihd.net/z";
    //$this -> baseVODUrl = "http://conencrypzeri-f.akamaihd.net/z";
    $this -> baseSMILUrl = "mp4:constellation";
    $this -> streamType = "theater";
    $this -> seat = $seat;
    $this -> movietype = "mov";
  }
  
  public function generateStreamUrl( $film, $size="large" ) {
  	
  	switch ($this -> streamType) {
      case "csmil":
	      if (! $this -> seat) {
          return null;
        }
        $this -> pathEndUrl = "/movies/".$film."/movie-,".$size.",.".$this -> movietype.".csmil/manifest.f4m";
        //putLog("USER:: ".$this -> sessionVar("user_id")." | MESSAGE:: MOVIE PATH:: HD2 pathEndUrl is ".$this -> pathEndUrl);
				$this -> streamUrl = $this -> pathEndUrl;
	      
        break;
      default:
        die("unauth");
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
		$config = new Akamai_EdgeAuth_Config();
		//$config -> set_ip(REMOTE_ADDR());
		$config -> set_start_time(time());
		$config -> set_window($window);
		$config -> set_acl('/*');
		//$config -> set_url($this -> pathEndUrl);
		$config -> set_key('ae45f210');
		$config -> set_session_id($this -> seat -> getAudienceShortUrl());
		$config -> set_data($this -> seat -> getAudienceInviteCode());
		$config -> set_salt('SaLt!@#');
		
		
		$tokenizer = new Akamai_EdgeAuth_Generate();
		$this -> etoken = $tokenizer -> generate_token($config);
		
    return $this -> etoken;
  }
  
  public function generateViewUrl( $film, $size="large", $window=86400, $duration=84600 ) {
    
    $this -> generateStreamUrl( $film, $size );
    
    $this -> generateAkamaiEtoken( $window, $duration );
    
    $this -> viewingUrl = $this -> baseVODUrl . $this -> streamUrl . "?hdnts=" . $this -> etoken;
    //Comment out below to re-enable Tokens
		$this -> viewingUrl = $this -> baseVODUrl . $this -> streamUrl;
		//putLog("USER:: ".$this -> sessionVar("user_id")." | MESSAGE:: {Akamai Token Class} Generated ".$this -> viewingUrl);
		$this -> context ->getLogger()->info("{Akamai Token Class} Generated ".$this -> viewingUrl);
		return $this -> viewingUrl;
    
  }
}
?>
