<?php

class Profile_format_utility {
  
  public $context;
  public $session_user;
  
  function __construct( $context ) {
    $this -> context = $context;
  }
  
  function isFollowing( $item ) {
  	if ($this -> session_user > 0) {
    $d = new WTVRData($this -> context);
    
    $sql = "select following_id
            from following
            where fk_follower_id = ".$this -> session_user."
						and fk_followed_id = ".$item["fk_follower_id"].";";
		$res = $d -> propelQuery($sql);
		$c = $res->fetchAll();
		if ($c[0][0] > 0) {
			return "true";
		}}
		return "false";
  }
  
  function isFollowed( $item ) {
  	
    if ($this -> session_user > 0) {
    $d = new WTVRData($this -> context);
    
    $sql = "select following_id
            from following
            where fk_follower_id = ".$this -> session_user."
						and fk_followed_id = ".$item["fk_followed_id"].";";
		$res = $d -> propelQuery($sql);
		$c = $res->fetchAll();
		if ($c[0][0] > 0) {
			return "true";
		}}
		return "false";
		
  }
  
  /*
  function getConf( $xmlObj ) {
    //Use this method to modify the conf object prior to execution of the query
  }
  */
  
}
?>
