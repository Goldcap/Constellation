<?php

include_once(sfConfig::get('sf_lib_dir')."/helper/CryptHelper.php");
  
class TheaterToken_PageWidget extends Widget_PageWidget {
  
  var $context;
  var $seat;
  var $token_raw;
  var $token;
  
  function __construct( $context, $seat=null ) {
    $this -> context = $context;
    $this -> user = $this -> getUser();
    $this -> seat = $seat;
    $this -> debug = false;
    parent::__construct( $context );
  }
  
  function setSig() {
    
    //Sets a signature cookie, and saves the "code" in the seat
    if (sfConfig::get("sf_private_key") !='') {
      
      $hash = md5(rand_str());
      $this -> context ->getLogger()->debug("{Theater Token Class} HASH:: ".$hash);
      putLog("USER:: ".$this -> sessionVar("user_id")." | MESSAGE:: TOKEN HASH:: ".$hash);
      
      $expires = strtotime(now());
      $this -> context ->getLogger()->debug("{Theater Token Class} EXPIRES:: ".$expires);
      putLog("USER:: ".$this -> sessionVar("user_id")." | MESSAGE:: TOKEN EXPIRES:: ".$expires);
      
      $code = setUserOrderTicket();
      $this -> seat -> setAudienceHmacKey( $code );
      $this -> seat -> save();
      $this -> context ->getLogger()->debug("{Theater Token Class} CODE:: ".$code);
      putLog("USER:: ".$this -> sessionVar("user_id")." | MESSAGE:: TOKEN CODE:: ".$code);
      
      /*
			$enc_date = encryptCookie($code, $hash."=".strtotime(now()));
      $this -> context ->getLogger()->debug("{Theater Token Class} HMAC_SIG:: ".$enc_date);
      putLog("USER:: ".$this -> sessionVar("user_id")." - TOKEN HMAC_SIG:: ".$enc_date);
      
      $this -> token_raw = $hash . "|" .  strtotime(now()) . "|" . $enc_date;
      $this -> context ->getLogger()->debug("{Theater Token Class} Set Token:: ".$this -> token_raw);
      putLog("USER:: ".$this -> sessionVar("user_id")." - TOKEN RAW:: ".$this -> token_raw);
      
      $this -> token = encryptCookie($code,$this -> token_raw);
      $this -> context ->getLogger()->debug("{Theater Token Class} Set Coookie:: ".$this -> token);
      putLog("USER:: ".$this -> sessionVar("user_id")." - SET COOKIE:: ".$this -> token);
      */
      
      $this -> setCookieVar( "csth", $code, 7 );
      return $this -> token;
      
    }
    
  }
  
  function readSig() {
  
    if (sfConfig::get("sf_private_key") !='') {
      $this -> token = $this -> cookieVar( "csth" );
      $this -> context ->getLogger()->info("{Theater Token Class} Read Coookie:: ".$this -> token);
      putLog("USER:: ".$this -> sessionVar("user_id")." | MESSAGE:: TOKEN Read Coookie:: ".$this -> token);
      
			//There may have been an issue with the cookie, so let's try to find the seat...
			//Note, this exposes a security flaw in which multiple users could potentially use the seat
			//But is captured by the "heartbeat" service which will ensure HMAC Match Values
			if ($this -> token == "") {
				putLog("USER:: ".$this -> sessionVar("user_id")." | MESSAGE:: TOKEN IS NULL");
        $c = new Criteria();
		    $c->add(AudiencePeer::FK_USER_ID,$this -> sessionVar("user_id"));
		    $c->add(AudiencePeer::FK_SCREENING_UNIQUE_KEY,$this -> getVar("op"));
		    $theseat = AudiencePeer::doSelect($c);
		    if ($theseat) {
		    	putLog("USER:: ".$this -> sessionVar("user_id")." | MESSAGE:: Intercepted");
          $this -> intercept = true;
		    	$this -> seat = $theseat[0];
					$this -> setSig();
					return true;
				}
				return false;
      
			}
			
      $code = $this -> seat -> getAudienceHmacKey();
      $this -> context ->getLogger()->debug("{Theater Token Class} CODE:: ".$code);
     	putLog("USER:: ".$this -> sessionVar("user_id")." | MESSAGE:: TOKEN CODE:: ".$code);
      
      /*
      $this -> token_raw = decryptCookie($code, $this -> token);
      $this -> context ->getLogger()->info("{Theater Token Class} Read Token:: ".$this -> token_raw);
      putLog("USER:: ".$this -> sessionVar("user_id")." - TOKEN Read Token:: ".$this -> token_raw);
     	
      $arr = explode("|",$this -> token_raw);
      
      $hash = $arr[0];
      $this -> context ->getLogger()->debug("{Theater Token Class} HASH:: ".$hash);
      putLog("USER:: ".$this -> sessionVar("user_id")." - TOKEN HASH:: ".$hash);
     	
      $expires = $arr[1];
      $this -> context ->getLogger()->debug("{Theater Token Class} EXPIRES:: ".$expires);
      putLog("USER:: ".$this -> sessionVar("user_id")." - TOKEN EXPIRES:: ".$expires);
     	
      $hmac_sig = $arr[2];
      $this -> context ->getLogger()->debug("{Theater Token Class} HMAC_SIG:: ".$hmac_sig);
      putLog("USER:: ".$this -> sessionVar("user_id")." - TOKEN HMAC_SIG:: ".$hmac_sig);
     	
      $val = decryptCookie($code,$hmac_sig);
      
      if ($val == $hash."=".$expires) {
      */
      if ($code == $this -> token) {
			  $this -> context ->getLogger()->debug("{Theater Token Class} Token Authenticated");
        putLog("USER:: ".$this -> sessionVar("user_id")." | MESSAGE:: TOKEN AUTHENTICATED");
        $this -> setSig();
        return true;
      } else {
        //Just to be safe, let's see if there is more than one user
        //In the theater
        //$this -> setSig();
        $this -> context ->getLogger()->debug("{Theater Token Class} Hash Incorrect");
        putLog("USER:: ".$this -> sessionVar("user_id")." | MESSAGE:: TOKEN Hash Incorrect");
        //$this -> setCookieVar( "csth", "", 7 );
        if ($this -> debug) return true;
        
        //Instead of kicking someone out, let's just change the sig
        //And let the heartbeat sort it out
        $this -> setSig();
				return true;
        //return false;                   
      }                                 
    }
  }
  
}

?>
