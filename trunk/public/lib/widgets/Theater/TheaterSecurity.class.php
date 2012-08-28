<?php

include_once(sfConfig::get('sf_lib_dir')."/helper/OrderHelper.php");
include_once(sfConfig::get('sf_lib_dir')."/helper/UserHelper.php");
  
class TheaterSecurity_PageWidget extends Widget_PageWidget {
  
  var $context;
  var $user;
  
  var $film;
  var $seat;
  var $isHost;
  var $valid;
  var $state;
  var $etoken;
  
  var $debug;
  var $cypherkey;
  
  function __construct( $context ) {
    $this -> context = $context;
    $this -> user = $this -> getUser();
    $this -> valid = true;
    $this -> debug = 1;
    $this -> testing = false;
    $this -> cypherkey = "lockmeAmadeus256";
    
    parent::__construct( $context );
  }
  
  function confirm( $film, $hmac=true ) {
  
    $this -> film = $film;
    $this -> getScreeningTicket( $this -> film["data"][0]["screening_unique_key"] );
    
    $this -> checkPreUser();
    
    //$this -> checkAdmin();
    
    if (! $this -> checkTime()) {
      $this -> log("USER:: ".$this -> sessionVar("user_id")." - Time",false);
      if (! $this -> testing)
        return false;
    }
    if (! $this -> checkSoldOut()) {
      $this -> log("USER:: ".$this -> sessionVar("user_id")." - Time",false);
      if (! $this -> testing)
        return false;
    }
    
    if (! $this -> checkLogin()) {
      $this -> log("USER:: ".$this -> sessionVar("user_id")." - Login",false);
      if (! $this -> testing)
        return false;
    }   

    
    if (! $this -> checkSeat()) {
      $this -> log("USER:: ".$this -> sessionVar("user_id")." - Seat",false);
      if (! $this -> testing)
        return false;
    }
    
    $this -> getEToken();
    
    if (! $this -> checkPaid()) {
      $this -> log("USER:: ".$this -> sessionVar("user_id")." - Paid",false);
      if (! $this -> testing)
        return false;
    }
    
    if (! $this -> checkInUse()) {
      $this -> log("USER:: ".$this -> sessionVar("user_id")." - Used",false);
      //return false;
    }
    
    if (! $this -> checkBrowser()) {
      $this -> log("USER:: ".$this -> sessionVar("user_id")." - Browser",false);
      if (! $this -> testing)
        return false;
    }
    
    if ($hmac) {
    if (! $this -> checkSig()) {
      $this -> log("USER:: ".$this -> sessionVar("user_id")." - Signature",false);
      //if (! $this -> testing)
      //  return false;
    }}
    
    if ($this -> checkGeoBlock()) {
      $this -> log("USER:: ".$this -> sessionVar("user_id")." - GeoBlocked",false);
      if (! $this -> testing)
        return false;
    }
    
    if ($this -> valid) {
      $this -> setCurrentAttributes();
      if (! $this -> testing)
        $this -> assign();
    }
    
    if ($this -> testing) {
      $this -> valid = true;
    }
      
    return $this -> valid;
  }
  
  function assign() {
  
    $this -> seat -> setFkUserId( $this -> user -> getAttribute("user_id") );
    $this -> seat -> setAudienceIpAddr( REMOTE_ADDR() );
    $this -> seat -> setAudienceUpdatedAt( now() );
    //Moved To Heartbeat
		//$this -> seat -> setAudienceStatus( 1 );
    $this -> seat -> save();
    
  }
  
  function checkPreUser( $film=null ) {
  	return true;
  	
    if ($this->cookieVar('cs_user') != "") {
      return true;
    }
    if (is_null($film)) {
      $film = $this -> film["data"][0]["screening_film_id"];
    }
    
    $c = new Criteria();
    $c->add(FilmPeer::FILM_ID,$film);
    $c->add(FilmPeer::FILM_PREUSER,1);
    $thefilm = FilmPeer::doSelect($c);
    
    if(($thefilm[0]) && ($thefilm[0] -> getFilmId() > 0)) {
      return true;
    }
    
    $uri = "http://".sfConfig::get("app_domain")."/preuser";
    header( "Location: ".$uri ) ;
    die();
      
  }
  
  function checkAdmin() {
    
    if (($this -> user) && ($this -> user -> hasCredential(2)) && (! $this -> seat)) {
      $order = new OrderManager_PageWidget( $this -> widget_vars, $this -> page_vars, $this -> context, null );
      //Put the Audience Reservation in the DB
      $this -> seat = $order -> postAdminScreeningItem( $this -> film );
    }
  
  }
  
  function checkLogin() {
    
    if (! $this -> user -> isAuthenticated()) {
      $this -> valid = false;
      $this -> state = "Not Logged In";
      return false;
    }
    return true;
  }
  
  function checkSeat() {
    if (! $this -> seat) {
      $this -> valid = false;
      $this -> state = "Seat doesn't exist";
      return false;
    }
    return true;
  }
  
  function checkSoldOut() {
  	if (($this -> user) && ($this -> user -> hasCredential(2))) {
	    return true;
    }
		
		if ($this -> film["data"][0]["screening_highlighted"] == "true") {
	    putLog("USER:: ".$this -> sessionVar("user_id")." | MESSAGE:: TheaterSecurity Screening is Limited: " . $this -> film["data"][0]["screening_id"]);
    	$sql = "select count(fk_user_id)
            from payment
            inner join `user`
            on user.user_id = fk_user_id
            where fk_screening_id = ". $this -> film["data"][0]["screening_id"]."
            and payment.payment_status = 2";
	  	$res = $this -> propelQuery($sql);
	    while( $row = $res-> fetch()) {
	    	$count = $row[0];
	    }

	    putLog("USER:: ".$this -> sessionVar("user_id")." | MESSAGE:: TheaterSecurity Limited Screening Occupancy: " . $count);
    	
			if ($this -> film["data"][0]["screening_total_seats"] > 0) {	
		    putLog("USER:: ".$this -> sessionVar("user_id")." | MESSAGE:: TheaterSecurity Limited Screening Capacity (Screening): " . $this -> film["data"][0]["screening_total_seats"]);
				if ($this -> film["data"][0]["screening_total_seats"] <= $count) {
		      $this -> valid = false;
		      $this -> state = "Sold Out";
		      return false;	
				}
			} elseif ($this -> film["data"][0]["screening_film_total_seats"] > 0) {

		    putLog("USER:: ".$this -> sessionVar("user_id")." | MESSAGE:: TheaterSecurity Limited Screening Capacity (Film): " . $this -> film["data"][0]["screening_film_total_seats"]);
				if ($this -> film["data"][0]["screening_film_total_seats"] <= $count) {
					$this -> valid = false;
		      $this -> state = "Sold Out";
		      return false;	
				}
			}
		}
		return true;
	}
	
  function checkTime() {
    //This is a "Meta" item that was added to the film collection
    //By the "Theater" class
    
		$time = $this -> film["data"][0]["film_end_time"] + 1800;
   	
    if (($this -> film["data"][0]["screening_allow_latecomers"] != "true") && ($time < timestamp())) {
      $this -> valid = false;
      $this -> state = "Screening Time";
      return false;
    }
    return true;
  }
  
  function checkPaid() {
    if ($this -> seat -> getAudiencePaidStatus() != 2) {
      $this -> valid = false;
      $this -> state = "Ticket Not Purchased";
      return false;
    }
    return true;
  }
  
  function checkInUse() {
  
    if ($this -> seat -> getAudienceStatus() == -1) {
      $this -> valid = false;
      $this -> state = "Seat Blocked";
      return false;
    } 
    
    if (($this -> seat -> getAudiencePaidStatus() == 2) && 
       ($this -> seat -> getAudienceStatus() == 1) && 
       ($this -> seat -> getAudienceIpAddr() !== REMOTE_ADDR()) &&
			 ($this -> seat -> getFkUserId() != $this -> user -> getAttribute("user_id"))) {
      $this -> valid = false;
      $this -> state = "Ticket In Use";
      return false;
    }
    return true;
  }
  
  function checkBrowser() {
    return true;
  }
  
  function checkSig() {
    $hash = new TheaterToken_PageWidget($this -> context, $this -> seat);
    //If nobody has claimed the seat, it's open season
    if (is_null($this -> seat -> getAudienceHmacKey())) {
      $hash -> setSig();
      return true;
    }
    
    //Otherwise, make sure we're good
	  if (! $hash -> readSig()) {
      $this -> valid = false;
      $this -> state = "Signature Incorrect";
      return false;
    } else {
			if ($hash -> intercept) {
				$this -> seat = $hash -> seat;
			}
		}
    return true;
  }
  
  function getEToken() {
    //Defaults to "large" size
    $this -> etoken = new TheaterAkamaiEtoken( $this -> context, $this -> seat );
    $this -> etoken -> movietype = $this -> film["data"][0]["screening_film_movie_file"];
    $this -> etoken -> generateViewUrl( $this -> film );
  }
  
  function setSig() {
    $hash = new TheaterToken_PageWidget($this -> context, $this -> seat);
	  $hash -> setSig();
  }
  
  function getScreeningTicket( $screening, $code=null ) {
    
		if (is_null($code)) {
      $this -> context ->getLogger()->info("{Order Helper} Looking for Seat in:: ".$screening."\"");
      
      if ($this -> getVar("id")) {
        $code = $this -> getVar("id");
      } elseif ($this -> sessionVar("audience")) {
				$audience = $this -> sessionVar("audience");
         //$audience is an array of audience codes
         //If not in session, 
         
        if (! array_key_exists($screening,$audience)) {
          //"op" here is the screening
          $order = new OrderManager_PageWidget( $this -> widget_vars, $this -> page_vars, $this -> context, null );
          $oid = $order -> checkPriorSeat( $this -> sessionVar("user_id"), $this -> getVar("op") );
          if (! $oid) {
            return false;
          } else {
            $code = $oid;
          }
        } else {
          $code = $audience[$screening];
        }
      } elseif (($this -> user -> getAttribute("user_id") != "") && ($this -> getVar("op") != "")) {
        $order = new OrderManager_PageWidget( $this -> widget_vars, $this -> page_vars, $this -> context, null );
        $oid = $order -> checkPriorSeat( $this -> sessionVar("user_id"), $this -> getVar("op") );
        if (! $oid) {
          if (! is_null($order -> orderitems[0])) {
            $this -> seat = $order -> orderitems[0];
            $this -> valid = false;
            $this -> state = "Ticket In Use";
            return false;
          }
          return false;
        } else {
          $code = $oid;
        }
      } elseif ($this -> user -> getAttribute("user_id") != "") { 
        return false;
      } else {
        return false;
      }
    }
    
    $c = new Criteria();
    $c->add(AudiencePeer::AUDIENCE_INVITE_CODE,$code);
    $c->add(AudiencePeer::FK_SCREENING_UNIQUE_KEY,$screening); 
    $c->add(AudiencePeer::FK_USER_ID,$this -> sessionVar("user_id"));
    $theseat = AudiencePeer::doSelect($c);
   
    if (! $theseat) {
    	//One last try, look for any seat for this user
    	
	    $c = new Criteria();
	    $c->add(AudiencePeer::FK_USER_ID,$this -> sessionVar("user_id"));
	    $c->add(AudiencePeer::FK_SCREENING_UNIQUE_KEY,$screening);
	    $theseat = AudiencePeer::doSelect($c);
	    if (! $theseat) {
      	return false;
    	} else {
				$hash = new TheaterToken_PageWidget($this -> context, $theseat[0]);
    	  $hash -> setSig();
      }
			
		}
    
    $this -> seat = $theseat[0];
  }
  
  function setScreeningTicket( $code, $screening ) {
    putLog("USER:: ".$this -> sessionVar("user_id")." | MESSAGE:: TheaterSecurity setScreeningTicket is: " . $code . " and screening is: " . $screening);
    $audience = $this -> sessionVar("audience");
    if (is_array($audience[$screening])) {
    if (! in_array($code,$audience[$screening])) {
      $audience[$screening] = $code;
    }} else {
      $audience[$screening] = $code;
    }
    $this -> setSessionVar("audience",$audience);
  }
  
  function getScreeningSeat( $screening, $user ) {
    
    $c = new Criteria();
    $c->add(AudiencePeer::FK_USER_ID,$user);
    $c->add(AudiencePeer::FK_SCREENING_UNIQUE_KEY,$screening);
    $theseat = AudiencePeer::doSelect($c);
    
    if (! $theseat) {
      return false;
    }
    
    $this -> seat = $theseat[0];
  }
  
  //Some users in the theater haven't logged in,
  //So put some vars in their session variables
  //Note, the user has to be valid for this to do anything
  function setCurrentAttributes() {
    
    if (($this -> getUser() -> isAuthenticated()) && ($this -> getUser() -> getAttribute("user_id") > 0)) {
      $this -> user = $this -> getUser();
      return true;
    } elseif ($this -> valid) {
      $this -> log("Valid Unauthenticated User Being Set In Theater",$this -> seat -> getAudienceInviteCode());
      $this -> user = setAudienceUser( $this -> getUser(), $this -> seat );
      return true;
    }
  }
  
  function getSeatPair() {
    if (! $this -> seat) return null;
    $seat = ($this -> getUser() -> getAttribute("user_id") > $this -> seat -> getFkUserId()) ? $this -> seat -> getFkUserId() . "-" . $this -> getUser()-> getAttribute("user_id") : $this -> getUser() -> getAttribute("user_id") . "-" . $this -> seat -> getFkUserId();
    return $seat;
  }
  
  function getSeatUser() {
    if (! $this -> seat) return null;
    return $this -> seat -> getFkUserId();
  }
  
  function getSeatOwner( $screening=null ) {
    if (! $this -> seat) return null;
    if (is_null($screening)) {
      $screening = $this -> getVar("op");
    }
    $order = new OrderManager_PageWidget( $this -> widget_vars, $this -> page_vars, $this -> context, null );
    $uid = $order -> checkPriorOwner( $this -> user -> getAttribute("user_id"), $screening );
    if ($uid) {
      return $this -> user -> getAttribute("user_id");
    }
    return false;
  }
  
  function surrenderSeat() {
    //$this -> seat -> setFkUserId( $this -> getUser() -> getAttribute("user_id") );
    $this -> seat -> setAudienceStatus( 0 );
    $this -> seat -> setAudienceHmacKey( null );
    $this -> seat -> setAudienceIpAddr( null );
    $this -> seat -> save();
  }
  
  function captureSeat() {
    $this -> seat -> setFkUserId( $this -> getUser() -> getAttribute("user_id") );
    $this -> seat -> setAudienceStatus( 0 );
    $this -> seat -> setAudienceHmacKey( null );
    $this -> seat -> setAudienceIpAddr( null );
    $this -> seat -> save();
  }
  
  function killSeat() {
    $this -> seat -> setAudienceStatus( -1 );
    $this -> seat -> save();
  }
  
  function checkGeoBlock( $filter=null, $address=null ) {
    
	if (is_null($filter)) {
      $filter = $this -> film["data"][0];
    }
    
    if (is_numeric($filter)) {
      return false;
    }
    
    if (is_null($address)) {
      $address = REMOTE_ADDR();
    }
 
    //US: 
    //$address = "69.203.204.144";
    //Canada:
    //$address = "131.202.157.166";
    
    if ($this -> debug == 1)
      $this -> log("GEOIP DB INFO",geoip_database_info(GEOIP_COUNTRY_EDITION));
    
    $this -> log("GEOIP FILTERS FOR FILM",$filter);
    
		$data = geoip_record_by_name($address);
    
		$this -> log("GEOIP CONTINENT",$data["continent_code"]);
    if (($data["continent_code"] != '') && (preg_match("/".$data["continent_code"]."/",$filter))) {
      $this -> log("GEOIP BLOCKED FOR CONTINENT",$filter);
      
      $this -> valid = false;
      $this -> state = "Geo Block";
      return true;
    }
    
    $this -> log("GEOIP COUNTRY",$data["country_code"]);
    if (($data["country_code"] != '') && (preg_match("/".$data["country_code"]."/",$filter))) {
      $this -> log("GEOIP BLOCKED FOR COUNTRY",$filter);
      
      $this -> valid = false;
      $this -> state = "Geo Block";
      return true;
    }
    
    $this -> log("GEOIP CITY",$data["city"]);
    if (($data["city"] != '') && (preg_match("/".$data["city"]."/",$filter))) {
      $this -> log("GEOIP BLOCKED FOR CITY",$filter);
      
      $this -> valid = false;
      $this -> state = "Geo Block";
      return true;
    }
    
    $this -> log("GEOIP ADDRESS",$address);
    if (is_array($filter)) { $filter = implode(",",$filter); }
    if (preg_match("/".str_replace(".","",$address)."/",str_replace(".","",$filter))) {
      $this -> log("GEOIP BLOCKED FOR ADDRESS",$filter);
      
      $this -> valid = false;
      $this -> state = "Geo Block";
      return true;
    }
    
    return false;
    
  }
  
  function encodeAesData( $date=null ) {
    try {
      if ($this -> seat) {
        //kickdump($this -> seat -> getAudienceInviteCode()."|" . $this -> film["data"][0]["screening_film_id"] . "|" . $this -> seat ->  getAudienceHmacKey());
        //$data = AesCtr::encrypt($this -> seat -> getAudienceInviteCode()."|" . $this -> film["data"][0]["screening_film_id"] . "|" . $this -> seat ->  getAudienceHmacKey()."|".time(), $this -> cypherkey, 256);
        //dump($data);
        if ($date != null) {
					$data = AesCtr::encrypt($this -> seat -> getAudienceInviteCode()."|" . $this -> film["data"][0]["screening_film_id"] . "|" . $this -> seat ->  getAudienceHmacKey() . "|" . $date, $this -> cypherkey, 256);
        } else {
					$data = AesCtr::encrypt($this -> seat -> getAudienceInviteCode()."|" . $this -> film["data"][0]["screening_film_id"] . "|" . $this -> seat ->  getAudienceHmacKey(), $this -> cypherkey, 256);
        }
				return $data;
        //$data = str_replace("+",".",$data);
        //return str_replace("=","-",$data);
      } else {
        return "";
      }
    } catch ( Exception $e ) {
      return "";
    }
  }
  
  function decodeAesData( $data ) {
    $data = str_replace("=","-",$data);
    $data = str_replace(".","+",$data);
    return AesCtr::decrypt($data, $this -> cypherkey, 256);
  }
  
  function log($message,$result) {
    
    putlog("USER:: ".$this -> sessionVar("user_id")." | MESSAGE:: ".$message." - ".$result);
    
    if ($this -> debug == 1)
      $this -> context ->getLogger()->info("{Ticket} ".$message.":: ".$result);
      
    if ($this -> debug == 2) {
      if (! $result) {
        dump("{Ticket} ".$message.":: ".$result."\"");
      }
    }
  }
}

?>
