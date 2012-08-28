<?php

class BodyTest_PageWidget {
  
  var $test;
  var $widget;
  var $context;
  
  function __construct( $context ) {
  
    $this -> context = $context;
    $this -> test_name = str_replace("Test_PageWidget","",get_class($this));
    
  }
  
  function test() {
    
    //$this -> test = new TestForm_PageWidget( $this -> context, $this -> test_name );
    //$this -> test -> run();
    //$this -> test -> end();
    
    $this -> test = new Test_PageWidget( $this -> context, $this -> test_name, $this );
    $this -> test -> run();
    $this -> test -> end();
    
  }
  
  function getGenres( $vars ) {
    if (count($vars["genres"]) > 0) {
    return true;
    } return false;
  }
  
  function getCarousel( $vars ) {
    if (count($vars["carousel"]) > 0) {
    return true;
    } return false;
  }
  
  function getUpcomingFilms( $vars ) {
    if (count($vars["upcoming_films"]) > 0) {
    return true;
    } return false;
  }
  
  function getFeaturedFilms( $vars ) {
    if (count($vars["featured_films"]) > 0) {
    return true;
    } return false;
  }
  
}
?>
