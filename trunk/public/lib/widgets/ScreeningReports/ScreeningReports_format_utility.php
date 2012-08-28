<?php

class ScreeningReports_format_utility {
  
  public $context;
  public $result;
  public $terms;
  public $maplinks;
  public $rpp;
  public $pastScreenings;
  
  function __construct( $context ) {
    $this -> context = $context;
    $this -> terms = false;
  }
  
  function modTime( $item ) {
  	return date('M-d-Y H:i:s',$item);
  }
  
  function userSource( $item ) {
    
		$d = new WTVRData($this -> context);
    
    $sql = "select click_action_type_name
            from click_action
            inner join click_action_type
            on fk_click_action_type = click_action_type_id
            where fk_user_id = ".$item["fk_user_id"]."
            /*and click_action.fk_screening_id = ".$item["screening_id"]."*/
						and fk_click_action_type in (3,7,8);";
		
		$res = $d -> propelQuery($sql);
		$c = $res->fetchAll();
		return $c[0][0];
  }
  
  function userBeacon( $item ) {
    
		$d = new WTVRData($this -> context);
    
    $sql = "select click_guid
            from click
            inner join click_action
            on fk_click_id = click_id
            where fk_user_id = ".$item["fk_user_id"]."
            and click_action.fk_screening_id = ".$item["screening_id"]." 
						and fk_click_action_type = 1;";
		
		$res = $d -> propelQuery($sql);
		$c = $res->fetchAll();
		return $c[0][0];
  }
  
  function userPurchase( $item ) {
    
		$d = new WTVRData($this -> context);
    
    $sql = "select click_action_type_name
            from click_action
            inner join click_action_type
            on fk_click_action_type = click_action_type_id
            where fk_user_id = ".$item["fk_user_id"]."
            /*and click_action.fk_screening_id = ".$item["fk_screening_id"]."*/
						and fk_click_action_type in (1);";
		
		$res = $d -> propelQuery($sql);
		$c = $res->fetchAll();
		return $c[0][0];
  }
  
  function timeIn( $item ) {
    
		$d = new WTVRData($this -> context);
    
    $sql = "select min(chat_usage_date_added)
						from chat_usage
						where fk_user_id = ".$item["fk_user_id"]." 
						and fk_screening_unique_key = '".$item["screening_key"]."'";
		
		$res = $d -> propelQuery($sql);
		$c = $res->fetchAll();
		return $c[0][0];
  }
  
  function fbShare( $item ) {
    
		$d = new WTVRData($this -> context);
    
    $sql = "select sum(user_invite_count),
						fk_user_id
						from user_invite
						where fk_screening_id = ".$item["screening_id"]."
						and fk_user_id = ".$item["fk_user_id"]." 
						and user_invite_type = 'facebook'";
		
		$res = $d -> propelQuery($sql);
		$c = $res->fetchAll();
		return $c[0][0];
  }
  
	function twShare( $item ) {
    
		$d = new WTVRData($this -> context);
    
    $sql = "select sum(user_invite_count),
						fk_user_id
						from user_invite
						where fk_screening_id = ".$item["screening_id"]."
						and fk_user_id = ".$item["fk_user_id"]." 
						and user_invite_type = 'twitter'";
		
		$res = $d -> propelQuery($sql);
		$c = $res->fetchAll();
		return $c[0][0];
  }
  
	function emShare( $item ) {
    
		$d = new WTVRData($this -> context);
    
    $sql = "select sum(user_invite_count),
						fk_user_id
						from user_invite
						where fk_screening_id = ".$item["screening_id"]."
						and fk_user_id = ".$item["fk_user_id"]." 
						and user_invite_type = 'email'";
		
		$res = $d -> propelQuery($sql);
		$c = $res->fetchAll();
		return $c[0][0];
  }
  
  function pastScreenings( $item ) {
    
		$d = new WTVRData($this -> context);
    
    $sql = "select distinct fk_screening_id,
						film_name,
						screening_date,
						screening_time
						from audience
						inner join screening
						on fk_screening_id = screening_id
						inner join film
						on fk_film_id = film_id
						where audience.fk_user_id = ".$item["fk_user_id"]." 
						and fk_screening_id != ".$item["screening_id"]."
						limit 5;";
		
		$res = $d -> propelQuery($sql);
		$c = $res->fetchAll();
		$this -> pastScreenings = count($c);
		$text = '';
		if (count($c) > 0) {
		foreach ($c as $screen) {
			$text .= $screen["film_name"] . ' ' . $screen["screening_date"] . ' ' . $screen["screening_time"] . ',';
		}}
		return $text;
  }
  
  function pastScreeningCount( $item ) {
    
		return $this -> pastScreenings;
  }
  
  
  function userCount( $item ) {
    
    $d = new WTVRData($this -> context);
    
    $sql = "select count(audience_id)
            from audience
            inner join screening
            on fk_screening_id = screening_id
            where screening_id = ".$item["screening_id"].";";
		$res = $d -> propelQuery($sql);
		$c = $res->fetchAll();
		return ($c[0][0]);
  }
  
}
?>
