<?php

function getGoogleResults ( $term, $page=1 ) {
  $curl = new Curl;
  $curl->referer="http://www.tattoojohnny.com";
  
  $thestart = ($page -1) * 8;
  
  $res = $curl->get('http://ajax.googleapis.com/ajax/services/search/web?v=1.0&rsz=large&start='.$thestart.'&q='.urlencode($term))->body;
  $result = json_decode($res);
  if ($result -> responseStatus == "200"){
    return $result -> responseData;
  } else {
    return null;
  }
}


function reverseTenCount( $value ) {
  
  /*
  $actual value | $grid value
  1=10
  2=9
  3=8
  4=7
  5=6
  6=5
  7=4
  8=3
  9=2
  10=1
  */
  if ($value > 10) {
    return 0;
  }
  $val = 11-$value;
  if ($val > 10 ) {
    return 0;
  }
  return $val;
  
}
?>
