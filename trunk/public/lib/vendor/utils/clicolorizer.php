<?php

/*
Color   HexFront HexBack
black   30	     40
red     31       41
green	  32       42
yellow	33       43
blue    34       44
magenta	35       45
cyan    36       46
white	  37       47
*/
function cli_text( $text, $front,$back=false,$opts=array() ) {
  $colors["black"]["front"]=30;
  $colors["black"]["back"]=40;
  $colors["red"]["front"]=31;
  $colors["red"]["back"]=41;
  $colors["green"]["front"]=32;
  $colors["green"]["back"]=42;
  $colors["yellow"]["front"]=33;
  $colors["yellow"]["back"]=43;
  $colors["blue"]["front"]=34;
  $colors["blue"]["back"]=44;
  $colors["magenta"]["front"]=35;
  $colors["magenta"]["back"]=45;
  $colors["cyan"]["front"]=36;
  $colors["cyan"]["back"]=46;
  $colors["white"]["front"]=37;
  $colors["white"]["back"]=47;
  $colors["grey"]["front"]=37;
  $colors["grey"]["back"]=30;
  $exin="";
  $exout="";
  
  if (isset($opts["bold"])) {
    $exin="1;";
    $exout="0;";
  }
  if ($back) {
    print "\033[".$exin.$colors[$front]["front"].";".$colors[$back]["back"]."m".$text."\033[".$exout."40;37m\r\n";
  }else{
    print "\033[".$exin.$colors[$front]["front"]."m".$text."\033[".$exout."37m\r\n";
  }
}

function cli_date( $text=null,$front,$back=false,$opts=array(),$date=null,$style="pretty" ) {
  $text=$text.formatDate($date,$style);
  
  $colors["black"]["front"]=30;
  $colors["black"]["back"]=40;
  $colors["red"]["front"]=31;
  $colors["red"]["back"]=41;
  $colors["green"]["front"]=32;
  $colors["green"]["back"]=42;
  $colors["yellow"]["front"]=33;
  $colors["yellow"]["back"]=43;
  $colors["blue"]["front"]=34;
  $colors["blue"]["back"]=44;
  $colors["magenta"]["front"]=35;
  $colors["magenta"]["back"]=45;
  $colors["cyan"]["front"]=36;
  $colors["cyan"]["back"]=46;
  $colors["white"]["front"]=37;
  $colors["white"]["back"]=47;
  $colors["grey"]["front"]=37;
  $colors["grey"]["back"]=30;
  $exin="";
  $exout="";
  
  if (isset($opts["bold"])) {
    $exin="1;";
    $exout="0;";
  }
  if ($back) {
    print "\033[".$exin.$colors[$front]["front"].";".$colors[$back]["back"]."m".$text."\033[".$exout."40;37m\r\n";
  }else{
    print "\033[".$exin.$colors[$front]["front"]."m".$text."\033[".$exout."37m\r\n";
  }
}
?>
