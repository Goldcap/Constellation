<?php

class phpTimer {
  
  var $stimer;
  var $etimer;
  var $timer;
  
  function __construct() {
    $this -> startTimer();
  }
  
  function startTimer() {
    $this -> stimer = explode( ' ', microtime() );
    $this -> stimer = $this -> stimer[1] + $this -> stimer[0];
  }
  
  function endTimer() {
    $this -> etimer = explode( ' ', microtime() );
    $this -> etimer = $this -> etimer[1] + $this -> etimer[0];
  }
  
  function setTime() {
    $this -> timer = $this -> etimer - $this -> stimer;
  }
  
  function getTime() {
    $this -> setTime();
    return $this -> timer;
  }
  
  function writeTime() {
    $this -> endTimer();
    if ($GLOBALS["dev"]) {
      echo '<div id="wtvr_timer">';
      printf( "Script timer: <b>%f</b> seconds.", ($this -> getTime()) );
      echo '</div>';
    }
  }

}
?>
