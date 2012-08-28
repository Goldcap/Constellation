<?php

class LogAkamaiSearch_format_utility {
  
  public $context;
  public $result;
  public $maplinks;
  public $term;
  public $date;
  public $skip;
  public $errors;
  
  function __construct( $context ) {
    $this -> context = $context;
    $this -> term = false;
    $this -> skip = false;
  }
  
  function getTimeRemaining( $value ) {
    $var = daySpan(now(),formatDate($value,"TSRound"));
    return (30 + $var);
  }
  
  function getConf( $xmlObj ) {
    $this -> map = $xmlObj;
    
    if ($this -> date) {
    	$this -> map -> createSingleElementByPath("criteria","//criteria[@column='log_akamailog_ip']",array("scope"=>"PROCESS","value"=>$this -> date,"column"=>"log_akamailog_date","type"=>"daterange"));
    }
    
    if (! $this -> term) {
      $this -> map -> removeSingleElementByPath("//criteria[@column='log_akamailog_ip']");
    }
    
    //$this -> map -> saveXML();
    return $this -> map;
    
  }
  
  /*
  array(13) {
  [0]=>
  string(19) "FP: WIN 11,1,102,55"
  [1]=>
  string(11) "ver: 0.2.36"
  [2]=>
  string(6) "Pt: 35"
  [3]=>
  string(7) "ft: f4m"
  [4]=>
  string(13) "curTime: 4422"
  [5]=>
  string(11) "curIndex: 4"
  [6]=>
  string(13) "Buffer: 0.001"
  [7]=>
  string(10) "BW: 847.00"
  [8]=>
  string(13) "Avg BW: 54.65"
  [9]=>
  string(15) "Peak BW: 847.00"
  [10]=>
  string(4) "OSMF"
  [11]=>
  string(5) "ERROR"
  [12]=>
  string(13) "BaseInterface"
  */
  function formatFlashMessage($value) {
    $vals = preg_match("/\[(.*)\](.+)/",$value,$matches);
    if($matches) {
      $matches[1]=str_replace("] [","][",$matches[1]);
      $arr = explode("][",$matches[1]);
      $res = "<div class=\"bracketitem\">";
      foreach($arr as $info) {
        $item = explode(": ",$info);
        switch($item[0]) {
          case "FP":
            $res .= "<span class=\"entry\" style=\"color:blue\">Flash Player:</span><span class=\"entry\">".$item[1]."</span>";
          	break;
          case "ver":
            $res .= "<span class=\"entry\" style=\"color:blue\">Version:</span><span class=\"entry\">".$item[1]."</span>";
          	break;
          case "Pt":
            $res .= "<span class=\"entry\" style=\"color:blue\">PT:</span><span class=\"entry\">".$item[1]."</span>";
          	break;
          case "ft":
            $res .= "<span class=\"entry\" style=\"color:blue\">File Type:</span><span class=\"entry\">".$item[1]."</span>";
          	break;
          case "curTime":
            $res .= "<span class=\"entry\" style=\"color:blue\">Seek Time:</span><span class=\"entry\">".$item[1]."</span>";
          	break;
          case "curIndex":
            $res .= "<span class=\"entry\" style=\"color:blue\">File Index:</span><span class=\"entry\">".$item[1]."</span>";
          	break;
          case "Buffer":
            $res .= "<span class=\"entry\" style=\"color:blue\">Buffer:</span><span class=\"entry\">".$item[1]."</span>";
          	break; 
          case "BW":
            $res .= "<span class=\"entry\" style=\"color:blue\">Bandwidth:</span><span class=\"entry\">".$item[1]."</span>";
          	break;
          case "Avg BW":
            $res .= "<span class=\"entry\" style=\"color:blue\">Average Bandwidth:</span><span class=\"entry\">".$item[1]."</span>";
          	break;
          case "Peak BW":
            $res .= "<span class=\"entry\" style=\"color:blue\">Peak Bandwidth:</span><span class=\"entry\">".$item[1]."</span>";
          	break;
          default:
            $res .= "<span class=\"entry\" style=\"color:blue\">".$item[0].":</span><span class=\"entry\">".$item[1]."</span>";
          	break;
        }
      }
      $res .= "</div><div class=\"breakitem\"><span class=\"entry\" style=\"color:green\">".$matches[2]."</span></div>";
    }
    return $res;  
  }
  function formatFlashError($value) {
    if ($value == 1) {
      return '<span style="color: red">Error</span>';
    }
  }
  
}
?>
