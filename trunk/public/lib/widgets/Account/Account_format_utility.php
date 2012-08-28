<?php

class Account_format_utility {
  
  public $context;
  public $result;
  public $terms;
  public $maplinks;
  
  function __construct( $context ) {
    $this -> context = $context;
    $this -> terms = false;  
  }
  
  function getTimeRemaining( $value ) {
    $var = daySpan(now(),formatDate($value,"TSRound"));
    return (30 + $var);
  }
  
  function formatUpcomingName( $item ) {
    /*
    array(4) {
      ["screening_short_url"]=>
      string(32) "/theater/zr7FRA4kt9Na/pwHLimiQN3"
      ["Film_Name"]=>
      string(11) "The Lottery"
      ["screening_end"]=>
      string(19) "2011-03-11 14:50:35"
      ["Date"]=>
      string(37) "Friday 11th of March 2011 01:00:00 PM"
    }
    */
    if(strtotime($item["screening_end"]) > timestamp()){
      return '<a href="'.$item["screening_short_url"].'">'.$item["Film_Name"].'</a>';
    } else {
      return $item["Film_Name"];
    }
  }
  
  function formatUpcomingLink( $item ) {
    /*
    array(4) {
      ["screening_short_url"]=>
      string(32) "/theater/zr7FRA4kt9Na/pwHLimiQN3"
      ["Film_Name"]=>
      string(11) "The Lottery"
      ["screening_end"]=>
      string(19) "2011-03-11 14:50:35"
      ["Date"]=>
      string(37) "Friday 11th of March 2011 01:00:00 PM"
    }
    */
    if(strtotime($item["screening_end"]) > timestamp()){
      return '<a href="'.$item["screening_short_url"].'">'.formatDate($item["Date"],"pretty").'</a>';
    } else {
      return formatDate($item["Date"],"pretty");
    }
  }
  
}
?>
