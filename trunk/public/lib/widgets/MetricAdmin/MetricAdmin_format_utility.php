<?php

class MetricAdmin_format_utility {
  
  public $context;
  public $result;
  public $terms;
  public $maplinks;
  public $rpp;
  public $ttl; 
  public $ttp;
  
  function __construct( $context ) {
    $this -> context = $context;
    $this -> terms = false;
    $this -> ttl = 0;
    $this -> ttp = 0;
    $this -> ttc = 0;
  }
  
  function totalCount($item) {
			$this -> ttl = $item["Users"] + $this -> ttl;
			return $this -> ttl;
	}
  
	function FBCount( $item ) {
    
    $d = new WTVRData($this -> context);
    
    $sql = "select count(user_id)
            from user
            where user_fb_uid is not null 
						and user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
						and month(user_created_at) = ".$item["Month"]."
						and day(user_created_at) = ".$item["Day"]."
						and year(user_created_at) = ".$item["Year"].";";
		$res = $d -> propelQuery($sql);
		$c = $res->fetchAll();
		return ($c[0][0]);
  }
  
  function TWCount( $item ) {
    
    $d = new WTVRData($this -> context);
    
    $sql = "select count(user_id)
            from user
            where user_t_uid is not null 
						and user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
						and month(user_created_at) = ".$item["Month"]."
						and day(user_created_at) = ".$item["Day"]."
						and year(user_created_at) = ".$item["Year"].";";
		$res = $d -> propelQuery($sql);
		$c = $res->fetchAll();
		return ($c[0][0]);
  }
  
  function ECount( $item ) {
    
    $d = new WTVRData($this -> context);
    
    $sql = "select count(user_id)
            from user
            where user_t_uid is null
						and user_fb_uid is null
						and user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}' 
						and month(user_created_at) = ".$item["Month"]."
						and day(user_created_at) = ".$item["Day"]."
						and year(user_created_at) = ".$item["Year"].";";
		$res = $d -> propelQuery($sql);
		$c = $res->fetchAll();
		return ($c[0][0]);
  }
  
  function TicketCount( $item ) {
    
    $d = new WTVRData($this -> context);
    
    $sql = "select count(payment_id)
            from payment
            inner join user
            on payment.fk_user_id = user.user_id
						where payment_status = 2
						and payment_type = 'screening'
						and user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and payment_amount > 0 
						and payment_amount < 50
						and month(payment_created_at) = ".$item["Month"]."
						and day(payment_created_at) = ".$item["Day"]."
						and year(payment_created_at) = ".$item["Year"].";";
		$res = $d -> propelQuery($sql);
		$c = $res->fetchAll();
		$this -> ttp += $c[0][0];
		return (sprintf("%0d",$c[0][0]));
  }
  
  function totalTicketCount($item) {
		return sprintf("%0d",$this -> ttp);
	}
	
	function TicketAmount( $item ) {
    
    $d = new WTVRData($this -> context);
    
    $sql = "select sum(payment_amount)
            from payment
            inner join user
            on payment.fk_user_id = user.user_id
						where payment_status = 2
						and payment_type = 'screening'
						and user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and payment_amount > 0
						and payment_amount < 50
						and month(payment_created_at) = ".$item["Month"]."
						and day(payment_created_at) = ".$item["Day"]."
						and year(payment_created_at) = ".$item["Year"].";";
		$res = $d -> propelQuery($sql);
		$c = $res->fetchAll();
		$this -> ttc += $c[0][0];
		return (sprintf("$%01.2f",$c[0][0]));
  }
  
	function totalTicketAmount( $item ) {
		return (sprintf("$%01.2f",$this -> ttc));
	}
  
}
?>
