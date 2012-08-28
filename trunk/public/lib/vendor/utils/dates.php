<?php

$MonthAsString = array  (1=>'January',
						2=>'February',
						3=>'March',
						4=>'April',
						5=>'May',
						6=>'June',
						7=>'July',
						8=>'August',
						9=>'September',
						10=>'October',
						11=>'November',
						12=>'December');
$DayAsString = array  ( 0=>'Sunday',
						1=>'Monday',
						2=>'Tuesday',
						3=>'Wednesday',
						4=>'Thursday',
						5=>'Friday',
						6=>'Saturday');
						
$thisdate = date("Y-m-d H:i:s");
$thisyear = date("Y");
$thismonth = date("n");
$thisasstring = date("F");
$thisday = date("d");
$thisdayasstring = date("l");

function timestamp() {
  return time();
}

function now() {
  return date("Y-m-d H:i:s.000000");
}

function nowAsDec() {
  return date("YmdHis");
}

function nowAsId() {
   return date("Ymdis") . substr((string)microtime(), 2, 6);
}

function minute() {
  return  date("i");
}

function hour() {
  return  date("H");
}

function month() {
  return  date("n");
}

function day() {
  return date("d");
}

function year()  {
  return  date("Y");
}

function isToday( $date ) {
	if ((is_numeric($date)) && (strlen($date) == 10)) {
    $date = $date;
  } else {
    $date = strtotime($date);
  }
  
  if (date("M/D/Y",$date) == date("M/D/Y")) {
		return true;
	}
	return false;
  
}

function formatDate($date=null,$style="pretty",$tz=null) {
  //Styles Are As Follows
  //
  $do = $date;
  if ($date == null) {
    $date = now();
  }
  
  if ((is_numeric($date)) && (strlen($date) == 10)) {
    $date = $date;
  } else {
    $date = strtotime($date);
  }
  switch ($style) {
  
  case "pretty":
  return (date('l dS \of F Y h:i:s A',$date));
  break;
  
  case "prettyshort":
  return (date('g:i A T l M dS ',$date));
  break;
  
  case "prettyshorttz":
  return (date('l F d g:iA T', $date));
  break;
  
  case "my":
  return (date('m/y', $date));
  break;
  
  case "MY":
  return (date('M/Y', $date));
  break;
  
  case "MDY":
  return (date('m/d/y', $date));
  break;
  
  case "MDY-":
  return (date('Y-m-d', $date));
  break;
  
  case "PRETTYMD":
  return (date('F d, Y', $date));
  break;
  
  case "FY":
  return (date('F/Y', $date));
  break;
  
  case "FULLTIME":
  return (date('mdYGi', $date));
  break;
  
  case "monthtime":
  return (date('m/d/y g:i', $date));
  break;
  
  case "monthtimezone":
  return (date('m/d/y g:i A T', $date));
  break;
  
  case "TS":
  return (date('Y-m-d H:i:s', $date));
  break;
  
  case "TSTZ":
  return (date('Y-m-d H:i:s T', $date));
  break;
  
  case "TSFloor":
  case "TSRound":
  return (date('Y-m-d 00:00:00', $date));
  break;
  
  case "TSCeiling":
  return (date('Y-m-d 23:59:59', $date));
  break;
  
  case "TSPacific":
  return (date('Y-m-d H:i:s', $date + (get_timezone_offset(null,'America/Los_Angeles') * 3600)));
  break;
  
  case "TSEastern":
  return (date('Y-m-d H:i:s', $date + (get_timezone_offset(null,'America/New_York') * 3600)));
  break;
  
  case "SM":
  return (date('m/d/Y G:i A', $date));
  break;
  
  case "UTC":
  return (date(DATE_ATOM, $date));
  break;
  
  case "W3XMLIN":
  if ((is_null($tz)) || ($tz == ""))  {
    $tz = date_default_timezone_get();
  }
  //Since our SOLR Dates don't remember DST, we have to add an hour
  //If we're currently in DST but the future date isn't
  if ((date("I")) && (! date("I",$date))) {
		$date = $date + 3600;
	}
	//Since our SOLR Dates don't remember DST, we have to subtract an hour
  //If we're NOT currently in DST but the future date is
	if ((! date("I")) && (date("I",$date))) {
		$date = $date - 3600;
	}
	return (date('Y-m-d\TH:i:s\Z', $date + (get_timezone_offset('UTC',$tz) * 3600)));
  break;
  
  case "W3XMLOUT":
  //1995-12-31T23:59:59Z
  if (is_null($tz)) {
    $tz = date_default_timezone_get();
  }
  //Since our SOLR Dates don't remember DST, we have to add an hour
  //If we're currently in DST but the future date isn't
  if ((date("I")) && (! date("I",$date))) {
		$date = $date + 3600;
	}
	//Since our SOLR Dates don't remember DST, we have to subtract an hour
  //If we're NOT currently in DST but the future date is
	if ((! date("I")) && (date("I",$date))) {
		$date = $date - 3600;
	}
  
  $offset = (get_timezone_offset('UTC',$tz) > 0) ? "-" : "+"; 
  //kickdump(get_timezone_offset('UTC',$tz));
	//dump($offset);
  //Every Date coming out of SOLR is in GMT, and translated back to the current default timezone
  return (date('Y-m-d\TH:i:s'.$offset.abs(get_timezone_offset('UTC',$tz)).':00', $date));
  break;
  
  case "piped":
  if ((is_null($tz)) || ($tz == ""))  {
    $tz = date_default_timezone_get();
  }
  return (date('Y|m|d|G|i|s', $date));
  break;
  
  case "W3XML":
  //1995-12-31T23:59:59Z
  return (date('Y-m-d\TH:i:s\Z', $date));
  break;
  
  case "RAW":
  return $date;
  break;
  
  case "DOY":
  return (date('z', $date));
  break;
  
  case "order":
  return (date('YmdHi', $date));
  break;
  
  case "gmt":
  return (date('m/d/Y g:i \E\S\T', $date + (get_timezone_offset() * 3600)));
  break;
  
  case "pacific":
  return (date('m/d/Y g:i \E\S\T', $date + (get_timezone_offset(null,'America/Los_Angeles') * 3600)));
  break;
  
  case "solr":
  die("Convert 'solr' to 'monthtime'");
  break;
  
  case "unsolr":
  return (date('Y-m-d H:i:00', $date + (5 * 3600)));
  break;
  
  case "js":
  return (date('M d, Y H:i:s', $date));
  break;
  
  case "jsdate":
  return (date('M d, Y', $date));
  break;
  
  case "jstime":
  return (date('H:i A e', $date));
  break;
  
  case "time":
  return (date('H:i:s', $date));
  break;
  
  case "shorttime":
  return (date('h:i A', $date));
  break;
  
  case "shorttimeplus":
  return (date('h:iA T', $date));
  break;
  
  case "daytimeplus":
  return (date('D g:iA T', $date));
  break;
  
  case "TZ":
  return (date('e', $date));
  break;
  
  case "T":
  return (date('T', $date));
  break;
  
  case "hA":
  return (date('hA T', $date));
  break;
  
  case "DABBR":
  return (date('D m/j/y', $date));
  break;
  
  case "DTZ":
  //Sunday, August 8 @8:00 PM EST
  return (date('l F j @h:i A', $date));
  break;
  
  default:
  return (date($style, $date));
  break;
  
  }
  
}

function getDay( $date ) {
 return sprintf("%02d",date("d",strtotime($date)));
}

function getMonth( $date ) {
 return sprintf("%02d",date("m",strtotime($date)));
}

function getYear( $date ) {
 return date("Y",strtotime($date));
}

function getHour( $date ) {
 return sprintf("%02d",date("h",strtotime($date)));
}

function getMinute( $date ) {
 return sprintf("%02d",date("i",strtotime($date)));
}

function getSeconds( $date ) {
 return sprintf("%02d",date("s",strtotime($date)));
}

function getMeridiem( $date ) {
 return date("A",strtotime($date));
}

//This function is NOT an effective date modifier
//use only when months/years don't switch
function dateDiff($startdate,$increment,$part='d') {
  $date = strtotime($startdate);
  if ($part == "d") {
    $inc = 60 * 60 * 24 * $increment;
  } elseif ($part == "m") {
    die("Month Not Implemented");
    $inc = date("t") * 60 * 60 * 24 * $increment;
  } elseif ($part == "Y") {
    die("Year Not Implemented");
    $inc = 60 * 60 * 24 * $increment;
  } elseif ($part == "H") {
    $inc = 60 * 60 * $increment;
  } elseif ($part == "i") {
    $inc = 60 * $increment;
  } elseif ($part == "s") {
    $inc = $increment;
  }
  
  return date('Y-m-d H:i:s',$date + $inc);
  
}

#Not good for years and months.
#Update to PHP Date
function dateAddExt($startdate,$increment,$part='d') {
  
  $date = strtotime($startdate);
  
  if ($part == "d") {
    $add = 86400 * $increment;
    $date += $add;
  } elseif ($part == "m") {
    $add = 86400 * $increment * 30;
    $date += $add;
  } elseif ($part == "Y") {
    $add = 86400 * $increment * 30 * 365;
    $date += $add;
  } elseif ($part == "H") {
    $add = 3600 * $increment;
    $date += $add;
  } elseif ($part == "i") {
    $add = 60 * $increment;
    $date += $add;
  } elseif ($part == "s") {
    $date += $increment;
  }
  
  return (date('Y-m-d H:i:s', $date));
}

function dateAdd($startdate,$increment,$part='d') {
  $day =  date("d",strtotime($startdate));
  $month = date("m",strtotime($startdate));
  $year = date("Y",strtotime($startdate));
  $hour = date("H",strtotime($startdate));
  $minute = date("i",strtotime($startdate));
  $seconds = date("s",strtotime($startdate));
  
  if ($part == "d") {
   $day = $day + $increment;
  } elseif ($part == "m") {
    $month = $month + $increment;
  } elseif ($part == "Y") {
    $year = $year + $increment;
  } elseif ($part == "H") {
    $hour = $hour + $increment;
  } elseif ($part == "i") {
    $minute = $minute + $increment;
  } elseif ($part == "s") {
    $seconds = $seconds + $increment;
  }
  
  return date('Y-m-d H:i:s',mktime($hour, $minute, $seconds, $month, $day, $year));
}

function daySpan($startdate,$enddate) {
  if ((is_numeric($startdate)) && (strlen($startdate) == 10)) {
    $startdate = $startdate;
  } else {
    $startdate = strtotime($startdate);
  }
  $start =  mktime(0, 0, 0, date("m",$startdate), date("d",$startdate), date("Y",$startdate)) ;
  
  if ((is_numeric($enddate)) && (strlen($enddate) == 10)) {
    $enddate = $enddate;
  } else {
    $enddate = strtotime($enddate);
  }
  $end =  mktime(0, 0, 0, date("m",$enddate), date("d",$enddate), date("Y",$enddate)) ;
  
  $difference =($end-$start);
  return (int) ($difference/86400) ;
}

/**
 * Checks date if matches given format and validity of the date.
 * Examples:
 * <code>
 * is_date('22.22.2222', 'mm.dd.yyyy'); // returns false
 * is_date('11/30/2008', 'mm/dd/yyyy'); // returns true
 * is_date('30-01-2008', 'dd-mm-yyyy'); // returns true
 * is_date('2008 01 30', 'yyyy mm dd'); // returns true
 * </code>
 * @param string $value the variable being evaluated.
 * @param string $format Format of the date. Any combination of <i>mm<i>, <i>dd<i>, <i>yyyy<i>
 * with single character separator between.
 */
function is_valid_date($string){
    $strings= explode(" ",$string);

    $date = $strings[0];
    $time = $strings[1];
    
    if (strlen($time) > 0) {
      if (! preg_match("/(\d{2}):(\d{2})(:\d{2})?/",$time)) {
        return false;
      }
    }
    $date = str_replace(array('\'', '-', '.', ','), '/', $date);
    $date = explode('/', $date);
    if(    count($date) == 1 // No tokens
        and    is_numeric($date[0])
        and    $date[0] < 20991231 and
        (    checkdate(substr($date[0], 4, 2)
                    , substr($date[0], 6, 2)
                    , substr($date[0], 0, 4)))
    )
    {
        return true;
    }
   
    if(    count($date) == 3
        and    is_numeric($date[0])
        and    is_numeric($date[1])
        and is_numeric($date[2]) and
        (    checkdate($date[0], $date[1], $date[2]) //mmddyyyy
        or    checkdate($date[1], $date[0], $date[2]) //ddmmyyyy
        or    checkdate($date[1], $date[2], $date[0])) //yyyymmdd
    )
    {
        return true;
    }
   
    return false;
}

//Returns Mysql TZ Offset from GMT
//Symfony APP settting must reflect MySQL Setting
function getTzBase( $basetz = "UTC" ) {
  $tz = sfConfig::get("app_base_tz_offset");
  
  $offset = (get_timezone_offset($basetz,$tz) > 0) ? "-" : "+"; 
  return $offset.get_timezone_offset('UTC',$tz).':00';
}

//Returns Enviroment TZ Offset from GMT
function getTzOffset( $tz=null, $basetz = "UTC") {
  if (is_null($tz)) {
    $tz = date_default_timezone_get();
  }
  $offset = (get_timezone_offset($basetz,$tz) > 0) ? "-" : "+"; 
  return $offset.get_timezone_offset('UTC',$tz).':00';
}

//Returns MySQL and Enviroment TZ Offset from GMT
function getTzDiff( $tz=null, $basetz = "UTC") {
  $base = sfConfig::get("app_base_tz_offset");
  $offset = get_timezone_offset($basetz,$base);
  if (is_null($tz)) {
    $tz = date_default_timezone_get();
  }
  $os = get_timezone_offset($basetz,$tz);
  return $os - $offset;
}

//Returns MySQL and Enviroment TZ Offset from GMT
function setTzDate( $date ) {
  $date = strtotime($date);
  $offset = getTzDiff() * 3600;
  $date = $date + $offset;
  return $date;
}

/**    Returns the offset from the origin timezone to the remote timezone, in seconds.
*    @param $remote_tz;
*    @param $origin_tz; If null the servers current timezone is used as the origin.
*    @return int;
*/
function get_timezone_offset($remote_tz='GMT', $origin_tz = null) {
    if($remote_tz === null) {
        if(!is_string($remote_tz = date_default_timezone_get())) {
            return false; // A UTC timestamp was returned -- bail out!
        }
    }
    if($origin_tz === null) {
        if(!is_string($origin_tz = date_default_timezone_get())) {
            return false; // A UTC timestamp was returned -- bail out!
        }
    }
    
    if ($origin_tz == "America/Indiana") {
		 	 $origin_tz = "America/Indiana/Indianapolis";
		}
		if ($remote_dtz == "America/Indiana") {
		 	 $remote_dtz = "America/Indiana/Indianapolis";
		}
    $origin_dtz = new DateTimeZone($origin_tz);
    $remote_dtz = new DateTimeZone($remote_tz);
    $origin_dt = new DateTime("now", $origin_dtz);
    $remote_dt = new DateTime("now", $remote_dtz);
    $offset = $remote_dtz->getOffset($remote_dt) - $origin_dtz->getOffset($origin_dt);
    return $offset / 3600;
}

function timeDiff($ts1, $ts2) {
  if ($ts1 < $ts2) {
    $temp = $ts1;
    $ts1 = $ts2;
    $ts2 = $temp;
  }
  $format = 'Y-m-d H:i:s';
  $ts1 = date_parse(date($format, $ts1));
  $ts2 = date_parse(date($format, $ts2));
  $arrBits = explode('|', 'year|month|day|hour|minute|second');
  $arrTimes = array(0, 12, date("t", $temp), 24, 60, 60);
  foreach ($arrBits as $key => $bit) {
    $diff[$bit] = $ts1[$bit] - $ts2[$bit];
    if ($diff[$bit] < 0) {
      $diff[$arrBits[$key - 1]]--;
      $diff[$bit] = $arrTimes[$key] - $ts2[$bit] + $ts1[$bit];
    }
  }
  return $diff;
}

function Quarter( $offset=0 ) {
  //This is the staggered "Quarter" Date Array
  $starts = array("12","03","06","09");
  $ends = array("03","06","09","12");
  
  if (month() == 12) {
    $quarter = 5 + $offset;
  } elseif ((month() > 2) && (month() < 6)) {
    $quarter = 2 + $offset;
  } elseif ((month() > 5) && (month() < 9)) {
    $quarter = 3 + $offset;
  } elseif ((month() > 8) && (month() < 12)) {
    $quarter = 4 + $offset;
  }
  switch ($quarter) {
    case 1:
      $startdate = (year() - 1)."-".$starts[0]."-01 00:00:00";
      $enddate = (year())."-".$ends[0]."-01 00:00:00";
      break;
    case 2:
      $startdate = (year())."-".$starts[1]."-01 00:00:00";
      $enddate = (year())."-".$ends[1]."-01 00:00:00";
      break;
    case 3:
      $startdate = (year())."-".$starts[2]."-01 00:00:00";
      $enddate = (year())."-".$ends[2]."-01 00:00:00";
      break;
    case 4:
      $startdate = (year())."-".$starts[3]."-01 00:00:00";
      $enddate = (year())."-".$ends[3]."-01 00:00:00";
      break;
  }
  return array ("quarter"=>$quarter,"startdate"=>$startdate,"enddate"=>$enddate);
}    
?>
