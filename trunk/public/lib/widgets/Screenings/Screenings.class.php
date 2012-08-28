<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Screenings_crud.php';
  
   class Screenings_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {

  	//$this -> showData();
    if($this->getVar("id") == "events"){
      if ($this -> getUser() -> getAttribute("user_id")) {
        $userid = $this -> getUser() -> getAttribute("user_id");
      }  else {
        $userid = -1;
      }

      $endDate = time() + 30;
      // sfConfig::set("end_date","[* TO ".formatDate($endDate,"W3XMLIN")."]");
      sfConfig::set("end_date","[".formatDate($endDate,"W3XMLIN")." TO *]");

      $term = $this->getVar("search");
      sfConfig::set("search", $term);
      
      if($this->getVar('type') == 'past'){
				$events = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/EventsPastBySearch_list_datamap.xml");
      } else {
				// sfConfig::set("end_date","[".formatDate($endDate,"W3XMLIN")." TO * ]");
        $events = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/EventsUpcomingBySearch_list_datamap.xml");
      }
      $temp = array();
			

      if ($events["meta"]["totalresults"] > 0) {
        $i = 0;
        foreach ($events["data"] as $event) {
          $events["data"][$i]["is_attending"] = false;
          $events["data"][$i]["screening_is_past"] = $this->getVar('type') == 'past';
          $events["data"][$i]["is_inprogress"] = strtotime($event["screening_date"]) < strtotime(date("Y-m-d H:i:s")) && (strtotime($event["screening_end_time"]) + 120 )> strtotime(date("Y-m-d H:i:s"));
          if ((int) $event["screening_user_id"] == $userid) {
            $events["data"][$i]["is_attending"] = true;
          } else {
            $sql = "select audience_id, fk_user_id from audience where fk_screening_id = ".$event["screening_id"]." and audience_paid_status = 2";
            $res = $this -> propelQuery($sql);
            $val = $res -> fetchall();
            foreach($val as $aval) {
              if ($aval["fk_user_id"] == $userid) {
                $events["data"][$i]["is_attending"] = true;
              }
            }
          }
        $i++;
        }
      }
      if($events['data']){
        foreach($events['data'] as $event){
          if($event["screening_film_free_screening"] == 2){
            $event["is_free"] = false;
            $event["uses_coupon"] = true;
          } elseif($event["screening_film_free_screening"] == 1){
            $event["is_free"] = true;
            $event["uses_coupon"] = false;
          } else {
            $event["is_free"] = false;
            $event["uses_coupon"] = false;
          }
          
          if($event['screening_qa'] != ''){
            $imp = explode(',', $event['screening_qa']);
            $imp2 = explode('|', $imp[0]);
            $qas = array('title' => $imp2[0], 'youtubeId' => $imp2[1]);
            $event['screening_qa'] = $qas;
          }
          $event['screening_date'] = date("D, M dS \@ g:i A T", strtotime($event['screening_date']));
          $event['screening_type'] = $this->getVar('type');
          $temp[] = $event;
        }
      }

      header('content-type: application/json; charset=utf-8');    
      print json_encode(array(
        "totalresults" => (int) $events['meta']['totalresults'],
        "events" =>  $temp,
      ));
      die();
    }


    if($this -> getVar("id") == 'ical'){

      sfConfig::set("screening_unique_key",$this->getVar('screening'));
      $event = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/Event_list_datamap.xml");
      $event = $event['data'][0];
      // dump($event);

      $ical = "BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
BEGIN:VEVENT
UID:" . md5(uniqid(mt_rand(), true)) . "@constellation.tv
DTSTAMP:" . gmdate('Ymd').'T'. gmdate('His') . "Z
DTSTART:". gmdate('Ymd', strtotime($event['screening_date'])).'T'. gmdate('His', strtotime($event['screening_date'])) . "Z
DTEND:". gmdate('Ymd', strtotime($event['screening_end_time'])).'T'. gmdate('His', strtotime($event['screening_end_time'])) . "Z
SUMMARY:". ($event['screening_name'] !=''? $event['screening_name'] : $event['screening_film_name']) ."
END:VEVENT
END:VCALENDAR";

      //set correct content-type-header
      header('Content-type: text/calendar; charset=utf-8');
      header('Content-Disposition: inline; filename=calendar.ics');
      echo $ical;
      die;

    }


    if($this -> getVar("id") == 'outlook'){

      sfConfig::set("screening_unique_key",$this->getVar('screening'));
      $event = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/Event_list_datamap.xml");
      $event = $event['data'][0];
      // dump($event);

      $ical = "BEGIN:VCALENDAR
BEGIN:VCALENDAR
PRODID:-//Microsoft Corporation//Outlook 12.0 MIMEDIR//EN
VERSION:2.0
METHOD:PUBLISH
X-MS-OLK-FORCEINSPECTOROPEN:TRUE
BEGIN:VEVENT
CLASS:PUBLIC
CREATED:20091109T101015Z
SUMMARY:". str_replace(array( "\n"), '', $event["screening_description"]) ."
DTEND:". gmdate('Ymd', strtotime($event['screening_end_time'])).'T'. gmdate('His', strtotime($event['screening_end_time'])) . "Z
DTSTAMP:" . gmdate('Ymd').'T'. gmdate('His') . "Z
DTSTART:". gmdate('Ymd', strtotime($event['screening_date'])).'T'. gmdate('His', strtotime($event['screening_date'])) . "Z
LAST-MODIFIED:20091109T101015Z
LOCATION:Anywhere have internet
PRIORITY:5
SEQUENCE:0
SUMMARY;LANGUAGE=en-us:". ($event['screening_name'] !=''? $event['screening_name'] : $event['screening_film_name']) ."
TRANSP:OPAQUE
UID:" . md5(uniqid(mt_rand(), true)) . "@constellation.tv
X-MICROSOFT-CDO-BUSYSTATUS:BUSY
X-MICROSOFT-CDO-IMPORTANCE:1
X-MICROSOFT-DISALLOW-COUNTER:FALSE
X-MS-OLK-ALLOWEXTERNCHECK:TRUE
X-MS-OLK-AUTOFILLLOCATION:FALSE
X-MS-OLK-CONFTYPE:0
BEGIN:VALARM
TRIGGER:-PT1440M
ACTION:DISPLAY
DESCRIPTION:Reminder
END:VALARM
END:VEVENT
END:VCALENDAR";


      //set correct content-type-header
      header('Content-type: text/calendar; charset=utf-8');
      header('Content-Disposition: inline; filename=calendar.ics');
      echo $ical;
      die;

    }


	 if ($this -> getVar("id") == "latest") {
	 	
    $startdate = time();
     
		$util = new Screenings_format_utility($this -> context);
		if ($this -> getVar("viewed")){
			$util -> sids = explode(",",urldecode($this -> getVar("viewed")));
		}
		
		sfConfig::set("startdate","[".formatDate($startdate,"W3XMLIN")." TO * ]");
    

    if (($this -> getVar("type")) && ($this -> getVar("type") == "featured")) {
      if ($this -> getVar("film")) {
				sfConfig::set("film_id",$this -> getVar("film"));
    		$list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/ScreeningLatestFeaturedFilm_list_datamap.xml",true,"array",$util);
			} else {
				//$this -> showData();
				$list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/ScreeningLatestFeatured_list_datamap.xml",true,"array",$util);
    	}
		} else {
      //$list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Body/query/Screening_list_datamap.xml");
      if ($this -> getVar("film")) {
				$film = FilmPeer::retrieveByPk($this -> getVar("film"));
        sfConfig::set("film_id",$this -> getVar("film"));
    		if ($film->getFilmUseSponsorCodes() == 1) {
          $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/ScreeningLatestFilmSponsored_list_datamap.xml",true,"array",$util);
        } else {
          $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/ScreeningLatestFilm_list_datamap.xml",true,"array",$util);
			  }
      } else {
				//$this->showData();
				$util = new ScreeningsUpcoming_format_utility($this -> context);
    		if ($this -> getVar("viewed")){
    			$util -> sids = explode(",",urldecode($this -> getVar("viewed")));
    		}
        $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/ScreeningLatest_list_datamap.xml",true,"array",$util);
    	}
		}
		if ($this -> getUser() -> getAttribute("user_id")) {
      $userid = $this -> getUser() -> getAttribute("user_id");
    }	else {
      $userid = -1;
    }
    	
		$i=0;
		if (($list["meta"]["totalresults"] > 0) && (count($list["data"]) > 0)){
	   foreach ($list["data"] as $afilm) {
	   	$list["data"][$i]["screening_attending"] = false;
	    $list["data"][$i]["screening_hosting"] = false;
			if ($afilm["screening_user_id"] == $userid) {
				$list["data"][$i]["screening_hosting"] = true;
			}
			$sql = "select audience_id, fk_user_id from audience where fk_screening_id = ".$afilm["screening_id"]." and audience_paid_status = 2";
	    $res = $this -> propelQuery($sql);
	    $val = $res -> fetchall();
	    foreach($val as $aval) {
				if ($aval["fk_user_id"] == $userid) {
					$list["data"][$i]["screening_attending"] = true;
				}
			}
	    $list["data"][$i]["screening_audience_count"] = count($val);
	    $i++;
	  }}
    //dump($list["meta"]["totalresults"]);
		die(json_encode(array("ScreeningListResponse"=>$list["data"],"meta"=>$list["meta"])));
	 }
	 
   if ($this -> getVar("id") == "upcoming") {
     $sql = "select 
            screening.screening_id,
            concat(screening.screening_date,' ',screening.screening_time,' ',screening.screening_default_timezone)
            from screening
            inner join film
            on film.film_id = screening.fk_film_id
            where ADDTIME(screening.screening_end_time,screening.screening_prescreening_time) >= convert_tz(NOW(),'".getTzBase()."','".getTzOffset()."')
            and screening_date <= convert_tz(DATE_ADD(CURRENT_DATE(), INTERVAL 2 MONTH),'".getTzBase()."','".getTzOffset()."')
            and screening_paid_status = 2
						and film.film_status = 1";
      if ($this -> getVar('film')) {
        $sql .= " and fk_film_id = " . $this -> getVar('film');
      }
	   $rs = $this -> propelQuery($sql);
	   while($row = $rs -> fetch(PDO::FETCH_NUM)) {
	     //Do local TZ Conversions
	     //$item[$row[0]] = $row[1];
       $dates[] = formatDate($row[1],"MDY-");
       //$days[(int)$row[2]][(int)$row[0]][(int)$row[1]] = (int)$row[1];
     }
     
     if (count($dates) == 0) {
        print json_encode($response);
        die();
     }
     foreach ($dates as $aday) {
      $row = explode("-",$aday);
     	$days[(int)$row[0]][(int)$row[1]][(int)$row[2]] = (int)$row[1];
     }
     
     if (is_array($days)) {
     $response["days"] = array_unique($days);
     } else {
     $response["days"] = null;
     }
     print json_encode($response);
     die();
     
	 }
	 
   if ($this -> getVar("id") == "date") {
   
      if(date("m/d/Y") == $_GET["date"]){
        $startdate = time();
      } else {
        $startdate = strtotime($_GET["date"]." 00:00:00");
      }
      
      if (($this -> getVar("film")) && ($this -> getVar("film") > 0)) {
        $obj = FilmPeer::retrieveByPk($this -> getVar("film"));
        $times = explode(":",$obj -> getFilmRunningTime());
        $totaltime = ($times[0] * 3600) + ($times[1] * 60) + ($times[2]);
        $enddate = strtotime($_GET["date"]." 00:00:00") + 86400 + $totaltime;
      } else {
        $enddate = strtotime($_GET["date"]." 00:00:00") + 86400;
      }
      
      sfConfig::set("startdate","[".formatDate($startdate,"W3XMLIN")." TO ".formatDate($enddate,"W3XMLIN")."]");
      //dump(sfConfig::get("startdate"));
      //$this -> showData();
      if (($this -> getVar("film")) && ($this -> getVar("film") > 0)) {
        $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/ScreeningFilm_list_datamap.xml");
      } else {
        //$list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Body/query/Screening_list_datamap.xml");
        $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/Screening_list_datamap.xml");
      }
      
      $view = new sfPartialView($this -> context, 'widgets', 'Home Screenings', 'Home Screenings' );
      $view->getAttributeHolder()->add(array("featured_films"=>$list["data"]));
      $templateloc = sfConfig::get("sf_app_dir") . "/modules/default/templates/_Homescreenings.php";
      $view->setTemplate($templateloc);
      $result = $view->render();
      
      print($result);
      die();
      
    }
    
    if ($this -> getVar("id") == "film") {
      if(date("m/d/Y") == $_GET["date"]){
        $startdate = time();
      } else {
        $startdate = strtotime($_GET["date"]." 00:00:00");
      }
      
      $alternate = false;
      if ($this -> getVar("template")) {
        $alternate = true;
      }
      
      $obj = FilmPeer::retrieveByPk($this -> getVar("film"));
      $times = explode(":",$obj -> getFilmRunningTime());
      $totaltime = ($times[0] * 3600) + ($times[1] * 60) + ($times[2]);
      $enddate = strtotime($_GET["date"]." 00:00:00") + 86400 + $totaltime;
      
      sfConfig::set("startdate","[".formatDate($startdate,"W3XMLIN")." TO ".formatDate($enddate,"W3XMLIN")."]");
      if ($this -> getVar("film")) {
        $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/ScreeningFilm_list_datamap.xml");
      } else {
        $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/Screening_list_datamap.xml");
      }
      
      $view = new sfPartialView($this -> context, 'widgets', 'Film Screenings', 'Film Screenings' );
      $view->getAttributeHolder()->add(array('title'=>'showtimes for '.formatDate($startdate,"MDY"),"screenings"=>$list["data"],"alternate"=>$alternate));
      $templateloc = sfConfig::get("sf_app_dir") . "/modules/default/templates/_Screeninglist.php";
      $view->setTemplate($templateloc);
      $result = $view->render();
      
      print($result);
      die();
      
    }
    
    if ($this -> getVar("id") == "featured") {
      if(date("m/d/Y") == $_GET["date"]){
        $startdate = time();
      } else {
        $startdate = strtotime($_GET["date"]." 00:00:00");
      }
      
      $alternate = false;
      if ($this -> getVar("template")) {
        $alternate = true;
      }
      
      $obj = FilmPeer::retrieveByPk($this -> getVar("film"));
      $times = explode(":",$obj -> getFilmRunningTime());
      $totaltime = ($times[0] * 3600) + ($times[1] * 60) + ($times[2]);
      $enddate = strtotime($_GET["date"]." 00:00:00") + 86400 + $totaltime;
      
      $this -> setGetVar("op",$this -> getVar("film"));
      sfConfig::set("startdate","[".formatDate($startdate,"W3XMLIN")." TO ".formatDate($enddate,"W3XMLIN")."]");
      $carousel = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/ScreeningFilmFeatured_list_datamap.xml");
	    
      $view = new sfPartialView($this -> context, 'widgets', 'Film Screenings', 'Film Screenings' );
      $view->getAttributeHolder()->add(array('size'=>'film_',"screenings"=>$carousel["data"],'alternate'=>$alternate,'title'=>'showtimes for '.formatDate($startdate,"MDY")));
      $templateloc = sfConfig::get("sf_app_dir") . "/modules/default/templates/_Carousel.php";
      $view->setTemplate($templateloc);
      $result = $view->render();
      
      print($result);
      die();
      
    }
	  
	  if ($this -> getVar("id") == "alternate") {
      $this -> setGetVar("op",$this -> getVar("film"));
      $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/FilmView/query/Film_list_datamap.xml");
      $film = $list["data"][0];
      
      //Pulled from the component class...
      $startdate = time();
    
      $times = explode(":",$this -> film["film_running_time"]);
      $totaltime = ($times[0] * 3600) + ($times[1] * 60) + ($times[2]);
      $enddate = strtotime($_GET["date"]." 00:00:00") + 86400 + $totaltime;
    
      $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/ScreeningByKey_list_datamap.xml");
      $screening = $list["data"][0];
      
      $thistime = date("Y|m|d|H|i|s");
      $counttime = date("Y|m|d|H|i|s",strtotime($this->screening["screening_date"]));
      
      
      $response["screening_key"] = $screening["screening_unique_key"];
      $response["film_name"] = $film["film_name"];
      $response["host"] = $screening["screening_user_full_name"];
      $response["date"] = date("l, F d, Y",strtotime($screening["screening_date"]));
      $response["time"] = date("g:iA T",strtotime($screening["screening_date"]));
      $response["currenttime"] = $thistime;
      $response["counttime"] = $counttime;
      $response["time_val"] = formatDate($screening["screening_date"],"prettyshort");
      $response["host_val"] = $screening["screening_user_full_name"];
            
      print json_encode($response);
      die();
      
    }
    
    if ($this -> getVar("id") == "users") {
	    if (! is_numeric($this -> getVar("screening"))) {
        return null;
      }
	    $sql = "select distinct fk_user_id,
	            user.user_username,
              case when user.user_photo_url is not NULL
              then user.user_photo_url
              else 'https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png'
              end
              from payment
              inner join `user`
              on user.user_id = fk_user_id
              where fk_screening_id = ".$this -> getVar("screening")."
              and payment.payment_status = 2
              order by payment_created_at desc";
      if ($this -> getVar("limit")) {
        $sql = $sql . " limit " . $this -> getVar("limit");
      }
      
			$res = $this -> propelQuery($sql);
      $ii = 0;
      while( $row = $res-> fetch()) {
        $user["id"] = $row[0];
        $user["username"] = $row[1];
        if (preg_match("/twimg/",$row[2])) {
          $user["image"] = "https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png";
        } else {
        	if (left($row[2],4) == 'http') {
          	$user["image"] = str_replace("http:","https",$row[2]);
          } else {
						$user["image"] = '/uploads/hosts/'.$row[0].'/'.$row[2];
					}
        }
        if( $ii < 100 || $user['id'] == $this -> getUser() -> getAttribute("user_id")){
          $users[] = $user;
        }
        $ii++;
        
      }
      $sql = "select count(fk_user_id)
              from payment
              inner join `user`
              on user.user_id = fk_user_id
              where fk_screening_id = ".$this -> getVar("screening")."
              and payment.payment_status = 2";
    	$res = $this -> propelQuery($sql);
      while( $row = $res-> fetch()) {
      	$count = $row[0];
      }
      if($this -> getVar("callback")){
        header('content-type: application/json; charset=utf-8');    
        print ($this -> getVar("callback") . "(" . json_encode(array("totalresults"=>$count,"users"=>$users)). ");");
        die();
      } else {
        header('content-type: application/json; charset=utf-8');    
        print json_encode(array("totalresults"=>$count,"users"=>$users));
        die();
      }

      
    }
    
    if ($this -> getVar("id") == "colorme") {
	    
	    if (! is_numeric($this -> getVar("screening"))) {
        return null;
      }
	    $sql = "select distinct payment.fk_user_id,
	            user.user_username,
              payment.payment_created_at,
              case when user.user_photo_url is not NULL
              then user.user_photo_url
              else 'https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png'
              end
              from payment
              inner join `user`
              on user.user_id = fk_user_id
              inner join audience
              on audience.fk_screening_id = payment.fk_screening_id
              and audience.fk_user_id = payment.fk_user_id
              where payment.fk_screening_id = ".$this -> getVar("screening")."
              and payment.payment_status = 2
              and audience.audience_hmac_key is not null
              order by payment_created_at desc";
      
      if ($this -> getVar("records")) {
        $sql = $sql . " limit " . $this -> getVar("records");
      }

      if ($this -> getVar("offset")) {
        $sql = $sql . " offset " . $this -> getVar("offset");
      }

      // dump($sql);

      $res = $this -> propelQuery($sql);
      while( $row = $res-> fetch()) {
      	$user["userid"] = $row[0];
        $user["username"] = $row[1];
        if (preg_match("/twimg/",$row[3])) {
          $user["image"] = "https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png";
        } else {
          if (left($row[3],4) == 'http') {
          	$user["image"] = str_replace("http:","https",$row[3]);
          } else {
						$user["image"] = '/uploads/hosts/'.$row[0].'/'.$row[3];
					}
        }
        $users[] = $user;
      }
      $sql = "select count(distinct payment.fk_user_id),
	            user.user_username,
              payment.payment_created_at,
              case when user.user_photo_url is not NULL
              then user.user_photo_url
              else 'https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png'
              end
              from payment
              inner join `user`
              on user.user_id = fk_user_id
              inner join audience
              on audience.fk_screening_id = payment.fk_screening_id
              and audience.fk_user_id = payment.fk_user_id
              where payment.fk_screening_id = ".$this -> getVar("screening")."
              and payment.payment_status = 2
              and audience.audience_hmac_key is not null";
      $res = $this -> propelQuery($sql);
      while( $row = $res-> fetch()) {
      	$count = $row[0];
      }

    /* $tempUsers = array();

      $total = (int) $this -> getVar("offset") + (int) $this -> getVar("records");
      $offset = (int) $this -> getVar("offset");

      for($i = $offset; $i < $total && $i < 100 ; $i++){
        $tempUsers[] = array(
          "userid" => $i,
          "username" => $i,
          "image" => "https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png"
        );
      }
//*/
      header('Content-type: text/json');
      header('Content-type: application/json');
      print json_encode(
        array(
          "totalresults" => (int) $count,
          // "totalresults" => 100,
          // "users" => $tempUsers
          "users" => $users
        )
      );
      die();
      
    }
    
	  //List User Screenings
	  if ($this -> getVar("id") == "get") {
	    if ($this -> sessionVar("user_id") > 0) {
	    //$this -> showData();
	    sfConfig::set("startdate","[".formatDate(now(),"W3XMLIN")." TO * ]");
      $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/ScreeningUser_list_datamap.xml");
      
      $text = '<div class="row">';
      if ($list["meta"]["totalresults"] > 0) {
      foreach($list["data"] as $screen) {
        $text .= '<span class="screening"><a href="'.$screen["audience_short_url"].'">'.$screen["audience_film_name"].', '.formatDate($screen["audience_screening_date"],"m/d/y, h:iA").'</a>, HOSTED BY '.$screen["audience_screening_user_full_name"].'</span>';
      }
      } else {
        $text .= '<span class="screening">No Screenings Available</span>';
      }} else {
        $text .= '<span class="screening">No Screenings Available</span>';
      }
      $text .= '</div>';
      print($text);
      die();
      
    }
    
    if ($this -> getVar("id") == "host") {
	    
	    //$this -> showData();
	    sfConfig::set("startdate","[".formatDate(now(),"W3XMLIN")." TO * ]");
      $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/ScreeningHost_list_datamap.xml");
      
      $text = '<div class="row">';
      if ($list["meta"]["totalresults"] > 0) {
      foreach($list["data"] as $screen) {
        $text .= '<span class="screening"><a href="/theater/'.$screen["screening_unique_key"].'">'.$screen["screening_film_name"].', '.formatDate($screen["screening_date"],"m/d/y, h:iA T").'</a></span>';
      }
      } else {
        $text .= '<span class="screening">No Screenings Available</span>';
      }
      $text .= '</div>';
      print($text);
      die();
      
    }
    
    if ($this -> getVar("id") == "test") {
	     
	      $vars[1] = 4;
        $vars[2] = 4984;
        $starttime = now();
        $obj = FilmPeer::retrieveByPk($vars[1]);
        $times = explode(":",$obj -> getFilmRunningTime());
        $totaltime = ($times[0] * 3600) + ($times[1] * 60) + ($times[2]);
        $starttime = strtotime($starttime) + $totaltime;
     
        sfConfig::set("screening_id",$vars[2]);
        sfConfig::set("startdate","[".formatDate($startdate,"W3XMLIN")." TO ".formatDate($enddate,"W3XMLIN")."]");
        //dump(sfConfig::get("startdate"));
        $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/ScreeningById_list_datamap.xml");
        dump($list);
      
    }
        
  }

  function doPost(){
     
     if ($this -> XMLForm -> validateForm()) {
        switch ($this -> getFormMethod()) {
          case "submit":
          $this -> crud -> write();
          break;
          case "delete":
          $this -> crud -> remove();
          break;
        }
      }
    
  }

  function doGet(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      $this -> pushItem();
    }
    
  }

  function drawPage(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      return $this -> returnForm();
    } elseif ($this ->getOp() == "list" ) {
      return $this -> returnList();
    }
    
  }

	}

  ?>
