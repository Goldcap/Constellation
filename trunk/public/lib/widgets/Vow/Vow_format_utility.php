<?php

class Vow_format_utility {
  
  public $context;
  public $result;
  public $terms;
  public $maplinks;
  
  function __construct( $context ) {
    $this -> context = $context;
    $this -> terms = false;  
  }
  
  function getDesc( $val ) {
    return substr($val,0,50)."...";
  }
  
  function formatImage( $item ) {
    return "/uploads/vows/".$item["fk_user_id"]."/vow_small_".$item["vow_asset_guid"].".jpg";
  }
  
  function formatFullImage( $item ) {
    return "/uploads/vows/".$item["fk_user_id"]."/vow_medium_".$item["vow_asset_guid"].".jpg";
  }
  
  function setTerm( $termname, $termvalue) {
    $term["name"] = $termname;
    $term["value"] = $termvalue;
    $this -> terms[] = $term;
  }
  
  /*
  function getConf( $xmlObj ) {
    //Use this method to modify the conf object prior to execution of the query
  }
  */
  
}
?>
