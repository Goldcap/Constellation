<?php

class ClickTrackAdmin_format_utility {
  
  public $context;
  public $result;
  public $terms;
  public $maplinks;
  public $term;
  public $date;
  public $skip;
  
  function __construct( $context ) {
    $this -> context = $context;
    $this -> terms = false;
    $this -> skip = false;
  }
  
  function formatUsername( $item ) {
  	if ($item["fk_click_type"] == 1) {
			return "NA/Partner";
		}
    return $item["Username"];
  }
  
  function formatSignups( $item ) {
  	$d = new WTVRData( $this -> context );
    $sql = "select count(click_action_id) from click_action where fk_click_id = ".$item["ID"]." and fk_click_action_type in (3,7,8)";
    $res = $d -> propelQuery($sql);
    $num = $res -> fetchAll();
    return $num[0][0];
  }
  
  function formatScreeningPurchases( $item ) {
  	$d = new WTVRData( $this -> context );
    $sql = "select count(click_action_id) from click_action where fk_click_id = ".$item["ID"]." and fk_click_action_type = 1";
    $res = $d -> propelQuery($sql);
    $num = $res -> fetchAll();
    return $num[0][0];
  }
  
  function formatHostingPurchases( $item ) {
  	$d = new WTVRData( $this -> context );
    $sql = "select count(click_action_id) from click_action where fk_click_id = ".$item["ID"]." and fk_click_action_type = 2";
    $res = $d -> propelQuery($sql);
    $num = $res -> fetchAll();
    return $num[0][0];
  }
  
}
?>
