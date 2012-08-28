<?php

function validEmail( $str ) {
  if (! is_array($str)) {
    $mail[0] = $str;
  }
  if (preg_match("/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/", $str) > 0) {
    return true;
  }
  return false;
}

function validLink($link) {
  $initStart = preg_match( "/^http(s)?/", $link);
  if ($initStart) {
    $theval = str_replace("https://", "", $link);
	  $theval = str_replace("http://", "", $link);
	  if ($theval == "") {
	    return false;
    } else {
      if (preg_match( "/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?$/", $link)) {
        return true;
      } else {
        return false;
      }
    }
  } else {
    return false;
  }
}
  
function replaceURLs( $string, $link=false, $target="new" ) {
  $match = "/(http(s)?:\/\/)?(www\.([a-z][a-z0-9_\..-]*[a-z]{2,6})([a-zA-Z0-9\/*-?&%]*))/i";
  if (! $link) {
    $mix = " <a href=\"http$2://$3\" target=\"".$target."\">$3</a>";
  } else {
    $mix = " <a href=\"http://$3\" target=\"".$target."\">".$link."</a>";
  }
  return preg_replace($match,$mix,$string);
}


?>
