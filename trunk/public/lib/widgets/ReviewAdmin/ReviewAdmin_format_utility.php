<?php

class ReviewAdmin_format_utility {
  
  public $context;
  public $result;
  public $terms;
  public $maplinks;
  public $rpp;
  
  function __construct( $context ) {
    $this -> context = $context;
    $this -> terms = false;
  }
  
  function reviewCount( $item ) {
    
    $d = new WTVRData($this -> context);
    
    $sql = "select count(audience_id)
            from audience
            inner join screening
            on fk_screening_id = screening_id
            where fk_film_id = ".$item["film_id"]."
						and audience_review != '';";
		$res = $d -> propelQuery($sql);
		$c = $res->fetchAll();
		return ($c[0][0]);
  }
  
}
?>
