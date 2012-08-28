<?php

//http://www.ultramegatech.com/blog/2009/04/working-with-time-zones-in-php/

function zoneList() {
  $zones = timezone_identifiers_list();
          
  foreach ($zones as $zone) 
  {
      $zone = explode('/', $zone); // 0 => Continent, 1 => City
      
      // Only use "friendly" continent names
      if ($zone[0] == 'Africa' || $zone[0] == 'America' || $zone[0] == 'Antarctica' || $zone[0] == 'Arctic' || $zone[0] == 'Asia' || $zone[0] == 'Atlantic' || $zone[0] == 'Australia' || $zone[0] == 'Europe' || $zone[0] == 'Indian' || $zone[0] == 'Pacific')
      {        
          if (isset($zone[1]) != '')
          {
              $locations[$zone[0]][$zone[0]. '/' . $zone[1]] = str_replace('_', ' ', $zone[1]); // Creates array(DateTimeZone => 'Friendly name')
          } 
      }
  }
  /*  [0]=>
  string(6) "Africa"
  [1]=>
  string(7) "America"
  [2]=>
  string(10) "Antarctica"
  [3]=>
  string(6) "Arctic"
  [4]=>
  string(4) "Asia"
  [5]=>
  string(8) "Atlantic"
  [6]=>
  string(9) "Australia"
  [7]=>
  string(6) "Europe"
  [8]=>
  string(6) "Indian"
  [9]=>
  string(7) "Pacific"
  */
  //return $locations;
  //dump(array_keys($locations));
  
  $tz["America"] = $locations["America"];
  $tz["Atlantic"] = $locations["Atlantic"];
  $tz["Europe"] = $locations["Europe"];
  $tz["Africa"] = $locations["Africa"];
  $tz["Indian"] = $locations["Indian"];
  $tz["Asia"] = $locations["Asia"];
  $tz["Australia"] = $locations["Australia"];
  $tz["Pacific"] = $locations["Pacific"];
  $tz["Antarctica"] = $locations["Antarctica"];
  $tz["Arctic"] = $locations["Arctic"];
  
  return $tz;
  //$
}

function shortZoneList() {
  $zones = timezone_identifiers_list();
          
  foreach ($zones as $zone) 
  {
      $zone = explode('/', $zone); // 0 => Continent, 1 => City
      
      // Only use "friendly" continent names
      if ($zone[0] == 'Africa' || $zone[0] == 'America' || $zone[0] == 'Antarctica' || $zone[0] == 'Arctic' || $zone[0] == 'Asia' || $zone[0] == 'Atlantic' || $zone[0] == 'Australia' || $zone[0] == 'Europe' || $zone[0] == 'Indian' || $zone[0] == 'Pacific')
      {
       $location["sel_key"] = $zone[0]. '/' . $zone[1];
       $location["sel_value"] = str_replace('_', ' ', $zone[1]); // Creates array(DateTimeZone => 'Friendly name')
      
       $locations[] = $location; 
      }
  }
  return $locations;
}

function getZoneList() {
  $zonelist = array('Kwajalein' => '(GMT-12:00) International Date Line West',
  		'Pacific/Midway' => '(GMT-11:00) Midway Island',
  		'Pacific/Samoa' => '(GMT-11:00) Samoa',
  		'Pacific/Honolulu' => '(GMT-10:00) Hawaii',
  		'America/Anchorage' => '(GMT-09:00) Alaska',
  		'America/Los_Angeles' => '(GMT-08:00) Pacific Time (US &amp; Canada)',
  		'America/Tijuana' => '(GMT-08:00) Tijuana, Baja California',
  		'America/Denver' => '(GMT-07:00) Mountain Time (US &amp; Canada)',
  		'America/Chihuahua' => '(GMT-07:00) Chihuahua',
  		'America/Mazatlan' => '(GMT-07:00) Mazatlan',
  		'America/Phoenix' => '(GMT-07:00) Arizona',
  		'America/Regina' => '(GMT-06:00) Saskatchewan',
  		'America/Tegucigalpa' => '(GMT-06:00) Central America',
  		'America/Chicago' => '(GMT-06:00) Central Time (US &amp; Canada)',
  		'America/Mexico_City' => '(GMT-06:00) Mexico City',
  		'America/Monterrey' => '(GMT-06:00) Monterrey',
  		'America/New_York' => '(GMT-05:00) Eastern Time (US &amp; Canada)',
  		'America/Bogota' => '(GMT-05:00) Bogota',
  		'America/Lima' => '(GMT-05:00) Lima',
  		'America/Rio_Branco' => '(GMT-05:00) Rio Branco',
  		'America/Indiana/Indianapolis' => '(GMT-05:00) Indiana (East)',
  		'America/Caracas' => '(GMT-04:30) Caracas',
  		'America/Halifax' => '(GMT-04:00) Atlantic Time (Canada)',
  		'America/Manaus' => '(GMT-04:00) Manaus',
  		'America/Santiago' => '(GMT-04:00) Santiago',
  		'America/La_Paz' => '(GMT-04:00) La Paz',
  		'America/St_Johns' => '(GMT-03:30) Newfoundland',
  		'America/Argentina/Buenos_Aires' => '(GMT-03:00) Georgetown',
  		'America/Sao_Paulo' => '(GMT-03:00) Brasilia',
  		'America/Godthab' => '(GMT-03:00) Greenland',
  		'America/Montevideo' => '(GMT-03:00) Montevideo',
  		'Atlantic/South_Georgia' => '(GMT-02:00) Mid-Atlantic',
  		'Atlantic/Azores' => '(GMT-01:00) Azores',
  		'Atlantic/Cape_Verde' => '(GMT-01:00) Cape Verde Is.',
  		'Europe/Dublin' => '(GMT) Dublin',
  		'Europe/Lisbon' => '(GMT) Lisbon',
  		'Europe/London' => '(GMT) London',
  		'Africa/Monrovia' => '(GMT) Monrovia',
  		'Atlantic/Reykjavik' => '(GMT) Reykjavik',
  		'Africa/Casablanca' => '(GMT) Casablanca',
  		'Europe/Belgrade' => '(GMT+01:00) Belgrade',
  		'Europe/Bratislava' => '(GMT+01:00) Bratislava',
  		'Europe/Budapest' => '(GMT+01:00) Budapest',
  		'Europe/Ljubljana' => '(GMT+01:00) Ljubljana',
  		'Europe/Prague' => '(GMT+01:00) Prague',
  		'Europe/Sarajevo' => '(GMT+01:00) Sarajevo',
  		'Europe/Skopje' => '(GMT+01:00) Skopje',
  		'Europe/Warsaw' => '(GMT+01:00) Warsaw',
  		'Europe/Zagreb' => '(GMT+01:00) Zagreb',
  		'Europe/Brussels' => '(GMT+01:00) Brussels',
  		'Europe/Copenhagen' => '(GMT+01:00) Copenhagen',
  		'Europe/Madrid' => '(GMT+01:00) Madrid',
  		'Europe/Paris' => '(GMT+01:00) Paris',
  		'Africa/Algiers' => '(GMT+01:00) West Central Africa',
  		'Europe/Amsterdam' => '(GMT+01:00) Amsterdam',
  		'Europe/Berlin' => '(GMT+01:00) Berlin',
  		'Europe/Rome' => '(GMT+01:00) Rome',
  		'Europe/Stockholm' => '(GMT+01:00) Stockholm',
  		'Europe/Vienna' => '(GMT+01:00) Vienna',
  		'Europe/Minsk' => '(GMT+02:00) Minsk',
  		'Africa/Cairo' => '(GMT+02:00) Cairo',
  		'Europe/Helsinki' => '(GMT+02:00) Helsinki',
  		'Europe/Riga' => '(GMT+02:00) Riga',
  		'Europe/Sofia' => '(GMT+02:00) Sofia',
  		'Europe/Tallinn' => '(GMT+02:00) Tallinn',
  		'Europe/Vilnius' => '(GMT+02:00) Vilnius',
  		'Europe/Athens' => '(GMT+02:00) Athens',
  		'Europe/Bucharest' => '(GMT+02:00) Bucharest',
  		'Europe/Istanbul' => '(GMT+02:00) Istanbul',
  		'Asia/Jerusalem' => '(GMT+02:00) Jerusalem',
  		'Asia/Amman' => '(GMT+02:00) Amman',
  		'Asia/Beirut' => '(GMT+02:00) Beirut',
  		'Africa/Windhoek' => '(GMT+02:00) Windhoek',
  		'Africa/Harare' => '(GMT+02:00) Harare',
  		'Asia/Kuwait' => '(GMT+03:00) Kuwait',
  		'Asia/Riyadh' => '(GMT+03:00) Riyadh',
  		'Asia/Baghdad' => '(GMT+03:00) Baghdad',
  		'Africa/Nairobi' => '(GMT+03:00) Nairobi',
  		'Asia/Tbilisi' => '(GMT+03:00) Tbilisi',
  		'Europe/Moscow' => '(GMT+03:00) Moscow',
  		'Europe/Volgograd' => '(GMT+03:00) Volgograd',
  		'Asia/Tehran' => '(GMT+03:30) Tehran',
  		'Asia/Muscat' => '(GMT+04:00) Muscat',
  		'Asia/Baku' => '(GMT+04:00) Baku',
  		'Asia/Yerevan' => '(GMT+04:00) Yerevan',
  		'Asia/Yekaterinburg' => '(GMT+05:00) Ekaterinburg',
  		'Asia/Karachi' => '(GMT+05:00) Karachi',
  		'Asia/Tashkent' => '(GMT+05:00) Tashkent',
  		'Asia/Kolkata' => '(GMT+05:30) Calcutta',
  		'Asia/Colombo' => '(GMT+05:30) Sri Jayawardenepura',
  		'Asia/Katmandu' => '(GMT+05:45) Kathmandu',
  		'Asia/Dhaka' => '(GMT+06:00) Dhaka',
  		'Asia/Almaty' => '(GMT+06:00) Almaty',
  		'Asia/Novosibirsk' => '(GMT+06:00) Novosibirsk',
  		'Asia/Rangoon' => '(GMT+06:30) Yangon (Rangoon)',
  		'Asia/Krasnoyarsk' => '(GMT+07:00) Krasnoyarsk',
  		'Asia/Bangkok' => '(GMT+07:00) Bangkok',
  		'Asia/Jakarta' => '(GMT+07:00) Jakarta',
  		'Asia/Brunei' => '(GMT+08:00) Beijing',
  		'Asia/Chongqing' => '(GMT+08:00) Chongqing',
  		'Asia/Hong_Kong' => '(GMT+08:00) Hong Kong',
  		'Asia/Urumqi' => '(GMT+08:00) Urumqi',
  		'Asia/Irkutsk' => '(GMT+08:00) Irkutsk',
  		'Asia/Ulaanbaatar' => '(GMT+08:00) Ulaan Bataar',
  		'Asia/Kuala_Lumpur' => '(GMT+08:00) Kuala Lumpur',
  		'Asia/Singapore' => '(GMT+08:00) Singapore',
  		'Asia/Taipei' => '(GMT+08:00) Taipei',
  		'Australia/Perth' => '(GMT+08:00) Perth',
  		'Asia/Seoul' => '(GMT+09:00) Seoul',
  		'Asia/Tokyo' => '(GMT+09:00) Tokyo',
  		'Asia/Yakutsk' => '(GMT+09:00) Yakutsk',
  		'Australia/Darwin' => '(GMT+09:30) Darwin',
  		'Australia/Adelaide' => '(GMT+09:30) Adelaide',
  		'Australia/Canberra' => '(GMT+10:00) Canberra',
  		'Australia/Melbourne' => '(GMT+10:00) Melbourne',
  		'Australia/Sydney' => '(GMT+10:00) Sydney',
  		'Australia/Brisbane' => '(GMT+10:00) Brisbane',
  		'Australia/Hobart' => '(GMT+10:00) Hobart',
  		'Asia/Vladivostok' => '(GMT+10:00) Vladivostok',
  		'Pacific/Guam' => '(GMT+10:00) Guam',
  		'Pacific/Port_Moresby' => '(GMT+10:00) Port Moresby',
  		'Asia/Magadan' => '(GMT+11:00) Magadan',
  		'Pacific/Fiji' => '(GMT+12:00) Fiji',
  		'Asia/Kamchatka' => '(GMT+12:00) Kamchatka',
  		'Pacific/Auckland' => '(GMT+12:00) Auckland',
  		'Pacific/Tongatapu' => '(GMT+13:00) Nukualofa');
  return $zonelist;
}

function getUsTz( $timezone ) {
  // OFFICIAL US TIMEZONES
  $official_us_timezones = array(
      'America/Puerto_Rico' => 'AST',
      'America/New_York'    => 'EDT',
      'America/Chicago'     => 'CDT',
      'America/Boise'       => 'MDT',
      'America/Phoenix'     => 'MST',
      'America/Los_Angeles' => 'PDT',
      'America/Juneau'      => 'AKDT',
      'Pacific/Honolulu'    => 'HST',
      'Pacific/Guam'        => 'ChST',
      'Pacific/Samoa'       => 'SST',
      'Pacific/Wake'        => 'WAKT'
    );
  if (isset($official_us_timezones[$timezone])) {
    return $official_us_timezones[$timezone];
  } else {
    return "Non-US";
  }
}

function getUsTzSub( $timezone ) {
  // TIMEZONE OFFSETS FROM AMERICA / NEW YORK -- ADD THIS TO THE LOCAL TIME TO GET THE REMOTE TIME
  $official_us_timezone_subtractions = array(
      'AST'  => ' +1 hour',
      'EDT'  => ' +0 hour',
      'CDT'  => ' -1 hour',
      'MDT'  => ' -2 hour',
      'MST'  => ' -3 hour',
      'PDT'  => ' -3 hour',
      'AKDT' => ' -4 hour',
      'HST'  => ' -6 hour'
    );
  return $official_us_timezone_subtractions[$timezone];
}

function getUsTzAdd( $timezone ) {
  // TIMEZONE OFFSETS FROM AMERICA / NEW YORK -- ADD THIS TO THE REMOTE TIME TO GET THE LOCAL TIME
  $official_us_timezone_additions = array(
      'AST'  => ' -1 hour',
      'EDT'  => ' +0 hour',
      'CDT'  => ' +1 hour',
      'MDT'  => ' +2 hour',
      'MST'  => ' +3 hour',
      'PDT'  => ' +3 hour',
      'AKDT' => ' +4 hour',
      'HST'  => ' +6 hour'
    );
  return $official_us_timezone_additions[$timezone];
}

// GET TIME ZONE BY STATE
// IMPRECISE FOR SPLIT STATES: http://www.timetemperature.com/tzus/time_zone_boundaries.shtml
function get_timezone_by_state($s)
{
    $z['WI'] = 'CDT';
    $z['IL'] = 'CDT';
    $z['AL'] = 'CDT';
    $z['MN'] = 'CDT'; 
    $z['IA'] = 'CDT';
    $z['MO'] = 'CDT';
    $z['AR'] = 'CDT';
    $z['MS'] = 'CDT';
    $z['LA'] = 'CDT';
    $z['ND'] = 'CDT'; // SPLIT MDT
    $z['SD'] = 'CDT'; // SPLIT MDT
    $z['KS'] = 'CDT'; // SPLIT MDT
    $z['OK'] = 'CDT';
    $z['TX'] = 'CDT';

    $z['NE'] = 'MDT'; // SPLIT CDT
    $z['MT'] = 'MDT';
    $z['WY'] = 'MDT'; 
    $z['CO'] = 'MDT';
    $z['NM'] = 'MDT';
    $z['ID'] = 'MDT'; // SPLIT PDT
    $z['UT'] = 'MDT';

    $z['AZ'] = 'MST';

    $z['WA'] = 'PDT';
    $z['OR'] = 'PDT';
    $z['NV'] = 'PDT';
    $z['CA'] = 'PDT';

    $z['ME'] = 'EDT';
    $z['VT'] = 'EDT';
    $z['NH'] = 'EDT';
    $z['MA'] = 'EDT';
    $z['RI'] = 'EDT';
    $z['CT'] = 'EDT';
    $z['NY'] = 'EDT';
    $z['PA'] = 'EDT';
    $z['NJ'] = 'EDT';
    $z['DE'] = 'EDT';
    $z['MD'] = 'EDT';
    $z['DC'] = 'EDT';
    $z['VA'] = 'EDT';
    $z['NC'] = 'EDT';
    $z['SC'] = 'EDT';
    $z['GA'] = 'EDT';
    $z['FL'] = 'EDT'; // SPLIT CDT
    $z['MI'] = 'EDT';
    $z['OH'] = 'EDT';
    $z['IN'] = 'EDT'; // SPLIT CDT
    $z['KY'] = 'EDT'; // SPLIT CDT
    $z['TN'] = 'EDT'; // SPLIT CDT
    $z['WV'] = 'EDT';

    $z['HI'] = 'HST';
    $z['AK'] = 'AKDT';

    $z['ZZ'] = 'PDT'; // TESTING SIGNAL IF STATE = ZZ

    if (!isset($z[$s])) return 'PDT';
    return $z[$s];
}


function getZoneTime ( $timestamp, $tz='America/New_York', $format="U" ) {
  
  // create the DateTimeZone object for later
  $dtzone = new DateTimeZone($tz);
   
  // create a DateTime object
  $dtime = new DateTime();
   
  // set it to the timestamp (PHP >= 5.3.0)
  $dtime->setTimestamp($timestamp);
   
  // convert this to the user's timezone using the DateTimeZone object
  $dtime->setTimeZone($dtzone);
   
  return $dtime->format($format);
  
  // print the time using your preferred format
  //$time = $dtime->format('g:i A m/d/y');

}
function getTimeZoneFromOffset($offset) { 
  
  //Are we in DST?
  if (date("I") == 1) {
    $offset = $offset - 1;
  }
  
  $timezones = array( 
          '-12'=>'Pacific/Kwajalein', 
          '-11'=>'Pacific/Samoa', 
          '-10'=>'Pacific/Honolulu', 
          '-9'=>'America/Juneau', 
          '-8'=>'America/Los_Angeles', 
          '-7'=>'America/Denver', 
          '-6'=>'America/Mexico_City', 
          '-5'=>'America/New_York', 
          '-4'=>'America/Caracas', 
          '-3.5'=>'America/St_Johns', 
          '-3'=>'America/Argentina/Buenos_Aires', 
          '-2'=>'Atlantic/Azores',// no cities here so just picking an hour ahead 
          '-1'=>'Atlantic/Azores', 
          '0'=>'Europe/London', 
          '1'=>'Europe/Paris', 
          '2'=>'Europe/Helsinki', 
          '3'=>'Europe/Moscow', 
          '3.5'=>'Asia/Tehran', 
          '4'=>'Asia/Baku', 
          '4.5'=>'Asia/Kabul', 
          '5'=>'Asia/Karachi', 
          '5.5'=>'Asia/Calcutta', 
          '6'=>'Asia/Colombo', 
          '7'=>'Asia/Bangkok', 
          '8'=>'Asia/Singapore', 
          '9'=>'Asia/Tokyo', 
          '9.5'=>'Australia/Darwin', 
          '10'=>'Pacific/Guam', 
          '11'=>'Asia/Magadan', 
          '12'=>'Asia/Kamchatka' 
      ); 
  return ($timezones[$offset]);
}
?>
