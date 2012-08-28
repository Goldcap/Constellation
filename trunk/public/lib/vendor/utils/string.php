<?php

function doImage($image,$height="",$width="",$alt="",$map=false,$border=0)
{
  //This feature doesn't do anything, the image output is cleaned with the Pagewidget "Output" Class
  echo( " <img src=\"".$image."\" height=\"".$height."\" width=\"".$width."\" alt=\"".$alt."\" border=\"".$border."\" ".(($map) ? " usemap=\"".$map."\"" : null) . " />" );
  //echo( " <img src=\"".sfConfig::get("app_image_server").$image."\" height=\"".$height."\" width=\"".$width."\" alt=\"".$alt."\" border=\"".$border."\" ".(($map) ? " usemap=\"".$map."\"" : null) . " />" );
}

function startsWith($s, $test)
{
  return (substr($s, 0, strlen($test)) == $test);
}

function linkSplit($val) {
  $vals = explode("|",$val);
  return '<a href="'.$vals[0].'" target="_new">'.$vals[1].'</a>';
}

function linkEncode( $value ) {
  $value = str_replace("/","_x2f",$value);
  $value = str_replace("?","_x3f",$value);
  $value = str_replace(".","_x2e",$value);
  $value = str_replace("\"","_x22",$value);
  $value = str_replace("'","_x21",$value);
  $value = str_replace("&","_x26",$value);
  $value = str_replace(",","_x2c",$value);
  $value = urlencode($value);
  return $value;
}

function linkDecode ( $value ) {
  $value = strtolower(str_replace("_x2f","/",$value));
  $value = str_replace("_x3f","?",$value);
  $value = str_replace("_x2e",".",$value);
  $value = str_replace("_x22","\"",$value);
  $value = str_replace("_x21","'",$value);
  $value = str_replace("_x26","&",$value);
  $value = str_replace("_x2c",",",$value);
  return $value;
}

function initCap($str) {
  $len = strlen($str);
  $firstlet = strtoupper(substr($str,0,1));
  $restlet = substr($str,1,($len-1));
  return $firstlet.$restlet;
}

function fixString($str) {
  for ($i=0;$i<strlen($str);$i++) {
    $chr = $str{$i};
    $ord = ord($chr);
    if ($ord<32 or $ord>126) {
      $chr = ".";
      $str{$i} = $chr;
    }
  }
  return $str;
}

function stripandsanitize($str) {
  $str = strip_tags($str);
  $str = preg_replace("/[^a-z\d\s\/\.]/i", "", $str);
  $str = str_replace(".","", $str);
  return $str;
}

function cleanString($str,$encoded=true) {
  $str = preg_replace('/([\xc0-\xdf].)/se', "'&#' . ((ord(substr('$1', 0, 1)) - 192) * 64 + (ord(substr('$1', 1, 1)) - 128)) . ';'", $str);
  $str = preg_replace('/([\xe0-\xef]..)/se', "'&#' . ((ord(substr('$1', 0, 1)) - 224) * 4096 + (ord(substr('$1', 1, 1)) - 128) * 64 + (ord(substr('$1', 2, 1)) - 128)) . ';'", $str); 
  
  $str = html_entity_decode($str);
  
  $str = str_replace("’", "'", $str);
  $str = str_replace("“", "\"", $str);
  $str = str_replace("”", "\"", $str);
  $newstr = "";
  
  for ($i=0;$i<strlen($str);$i++) {
    $chr = $str{$i};
    $ord = ord($chr);
    if (($ord<31 || $ord>126) && ($ord != 13)) {
      $chr = "";
    }
    $newstr .= $chr;
  }
  
  if ($encoded)
  $str = htmlentities($newstr);
  else
  $str = $newstr;
  
  return $str;
}

//removes specific invalid characters
function smartString( $str ) {
  $badchars = array("—");
  $goodchars = array("-");
  $str = str_replace($badchars,$goodchars,$str);
  return $str;
}


function cleanFileName( $str ) {
  $str = preg_replace("/[^a-z0-9-_]/", "-", strtolower($str));
  return $str;
}

function left( $str, $count=1 ) {
  return substr( $str, 0, $count );
}

function right( $str, $count=1 ) {
  $len  = -1 * $count;
  return substr( $str, $len );
}

function elipses( $str, $count=1 ) {
  if (strlen($str) > $count) {
    return substr( $str, 0, $count-3 )."...";
  } else {
    return substr( $str, 0, $count );
  }
}

function URLDelim ( $URL ) {
   return (right($_SERVER["REQUEST_URI"]) == "?") ? "&" : "?";
}

// Generate a random character string
function rand_str($length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890')
{
    // Length of character list
    $chars_length = (strlen($chars) - 1);

    // Start our string
    $string = $chars{rand(0, $chars_length)};
   
    // Generate random string
    for ($i = 1; $i < $length; $i = strlen($string))
    {
        // Grab a random character from our list
        $r = $chars{rand(0, $chars_length)};
       
        // Make sure the same two characters don't appear next to each other
        if ($r != $string{$i - 1}) $string .=  $r;
    }
   
    // Return the string
    return $string;
}
?>
