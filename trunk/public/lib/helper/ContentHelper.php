<?php

function uniqueScreenings( $context, $screenings ) {
  
  if ($screenings['meta']['totalresults'] == 0) {
    return false;
  } else {
  	$sortorder = Array();
  	$screens = Array();
  	foreach($screenings["data"] as $screen) {
    	
    	if (! in_array($screen["screening_film_id"],$sortorder)) {
				$screens[] = $screen;
				$sortorder[] = $screen["screening_film_id"];
			}
		/*
		<column column="screening_film_id" type="INTEGER" size="11" key="PRI" order="1" format="%0d"></column>
	  <column column="screening_film_name" type="INTEGER" size="11" key="PRI" order="1"></column>
	  <column column="screening_film_logo" type="INTEGER" size="11" key="PRI" order="1"></column>
	  <column column="screening_date" type="VARCHAR" size="shorttime" key="" order="2" format="dateformat"></column>
	  <column column="screening_unique_key" type="VARCHAR" size="" key="" order="3" ></column>
	  <column column="screening_id" type="VARCHAR" size="" key="" order="4" ></column>
	  */
	  	
    	}
  }
  $obj["data"] = $screens;
  $obj["meta"]["totalresults"] = count($screens);
	return $obj;
  
}

function screeningFilms( $context, $films, $widget="HomeMarquee" ) {
  
  if ($films['meta']['totalresults'] == 0) {
    return false;
  } else {
  	$etoken = new TheaterAkamaiEtoken( $context, null );
    $etoken -> streamType = "home";
		foreach($films["data"] as $film) {
    	$d = new WTVRData( $context );
    	sfConfig::set("startdate","[".formatDate(now(),"W3XMLIN")." TO * ]");
    	//Use for current day, if we switch
			//sfConfig::set("startdate","[".formatDate(now(),"W3XMLIN")." TO ".formatDate(formatDate(now(),'TSCeiling'),"W3XMLIN")." ]");
    	sfConfig::set("film_id",$film["film_id"]);
    	$feat = $d -> dataMap(sfConfig::get('sf_lib_dir')."/widgets/".$widget."/query/ScreeningFeatured_list_datamap.xml");
    	$screens = Array();
    	
    	//kickdump($film["film_name"]. " Has " . $feat["meta"]["totalresults"]);
			if ($feat["meta"]["currentresults"] > 0) {
    		//kickdump($feat["data"]);
    		foreach ($feat["data"] as $screen) {
    			$time = null;
    			$time["key"] = $screen["screening_unique_key"];
    			$time["date"] = $screen["screening_date"];
    			$sortorder[] = $screen["screening_date"];
    			$filmorder[] = $film["film_uid"];
    			//kickdump($time);
					$screens[] = $time;
				}
			
				$afilm["screening_times"] = $screens;
			} else {
			  $afilm["screening_times"] = array();
			}
			$afilm["screening_film_id"] = $film["film_id"];
      $afilm["screening_film_name"] = $film["film_name"];
      $afilm["screening_film_short_name"] = $film["film_short_name"];
      $afilm["screening_film_still_image"] = $film["film_still_image"];
      $afilm["screening_film_splash_image"] = $film["film_splash_image"];
      $afilm["screening_film_info"] = strip_tags(nl2br($film["film_info"]));
      if (strlen($film["film_info"]) > 132) {
				//$afilm["screening_film_info"] = $afilm["screening_film_info"] . "...";
			}
      if ($film["logo_type"] == "screeningResources") {
        $afilm["film_logo"] = "/uploads/screeningResources/".$film["film_id"]."/logo/small_poster".$film["film_logo"];
        $afilm["film_logo_small"] = "/uploads/screeningResources/".$film["film_id"]."/logo/small_poster".$film["film_logo"];
     		$afilm["screening_film_logo"] = "/uploads/screeningResources/".$film["film_id"]."/logo/".$film["film_logo"];
		  } else {
        $afilm["film_logo"] = "/uploads/programResources/".$film["film_id"]."/logo/small_poster".$film["film_logo"];
        $afilm["film_logo_small"] = "/uploads/programResources/".$film["film_id"]."/logo/small_poster".$film["film_logo"];
      	$afilm["screening_film_logo"] = "/uploads/programResources/".$film["film_id"]."/logo/".$film["film_logo"];
			}
      $afilm["screening_film_allow_user_hosting"] = $film["film_allow_user_hosting"];
      $afilm["screening_film_allow_hostbyrequest"] = $film["film_allow_hostbyrequest"];
      if ($film["film_trailer_file"] != '') {
				$etoken -> generateViewUrl( $film, "", 84600, 84600 );
      	$afilm["stream_url"] = $etoken -> viewingUrl;
      } else {
				$afilm["stream_url"] = '';
			}
      $obj["data"][$film["film_uid"]] = $afilm;
      
    }
    //kickdump($sortorder);
    //kickdump($filmorder);
    //kickdump($obj);
    if (count($sortorder) > 0) {
		array_multisort($sortorder,$filmorder);
    foreach(array_unique($filmorder) as $film) {
			$newobj["data"][$film] = $obj["data"][$film];
		}} else {
			$newobj = $obj;
		}
		
		$newobj["totalresults"] = count($obj["data"]);
		
		//kickdump($newobj);
  }
  
	return $newobj;
  
}

function jsonFilms( $context, $films, $list, $as_json = true, $sort="none" ) {
  
  if ($films['meta']['totalresults'] == 0) {
    $obj = new stdClass();
    $obj -> result = array("count"=>0);
  } else {
    $etoken = new TheaterAkamaiEtoken( $context, null );
    $etoken -> streamType = "home";
    if ($films["meta"]["currentresults"] > 0) {
    foreach($films["data"] as $film) {
    	$afilm["id"] = $film["film_id"];
      $afilm["name"] = $film["film_name"];
      $afilm["short_name"] = $film["film_name"];
      //array_walk($film["film_directors"], 'linkList');
      if (is_array($film["film_directors"])) {
        $afilm["makers"] = implode(",",$film["film_directors"]);
      }
      $afilm["logo"] = $film["film_logo"];
      $afilm["synopsis"] = $film["film_synopsis"];
      $afilm["info"] = nl2br(substr($film["film_info"],0,450));
      if ($film["logo_type"] == "screeningResources") {
        $film["film_uid"] = "f".$film["film_id"];
				$afilm["logo_src"] = "/uploads/screeningResources/".$film["film_id"]."/logo/small_poster".$film["film_logo"];
        $afilm["small_logo_src"] = "/uploads/screeningResources/".$film["film_id"]."/logo/small_poster".$film["film_logo"];
      } else {
        $film["film_uid"] = "p".$film["film_id"];
				$afilm["logo_src"] = "/uploads/programResources/".$film["film_id"]."/logo/small_poster".$film["film_logo"];
        $afilm["small_logo_src"] = "/uploads/programResources/".$film["film_id"]."/logo/small_poster".$film["film_logo"];
      }
      $thefilms[$film["film_id"]] = $film["film_uid"];
      $afilm["allow_hosting"] = $film["film_allow_user_hosting"];
      if ($film["film_trailer_file"] != '') {
				$etoken -> generateViewUrl( $film, "", 84600, 84600 );
      	$afilm["stream_url"] = $etoken -> viewingUrl;
      } else {
				$afilm["stream_url"] = '';
			}
      $obj[$film["film_uid"]] = $afilm;
      
    }
    }
    if (count($list["data"]) > 0) {
    foreach ($list["data"] as $listitem) {
      if (isset($obj[$thefilms[$listitem["screening_film_id"]]])) {
        //$screen["id"] = $listitem["screening_id"];
        //$screen["date"] = formatDate($listitem["screening_date"],"MDY-");
        //$screen["time"] = formatDate($listitem["screening_date"],"time");
        //$screen["timezone"] = formatDate($listitem["screening_date"],"time");
        //$screen["date_time_with"] = formatDate($listitem["screening_date"],"prettyshort");
        //$screen["unique_key"] = $listitem["screening_unique_key"];
        
        //Use this to limit upcoming screenings to today
				//if (formatDate($listitem["screening_date"],"MDY-") == formatDate(null,"MDY-")) {
				if (count($obj[$thefilms[$listitem["screening_film_id"]]]["showtimes"]) < 3) {
					$obj[$thefilms[$listitem["screening_film_id"]]]["showtimes"][] = formatDate($listitem["screening_date"],"daytimeplus");
					$obj[$thefilms[$listitem["screening_film_id"]]]["showids"][] = $listitem["screening_unique_key"];
    		}
				$sortorder[] = $listitem["screening_date"];
    		$filmorder[] = $listitem["screening_film_id"];
				//$screen["full_name"] = $listitem["screening_user_full_name"];
        //$sreenings[] = $screen;
        //$obj[$listitem["screening_film_id"]]["screenings"][] = $screen;
      }
    }}
  }
  
  if ((($sort == "asc") || ($sort == "desc")) && (count($sortorder) > 0)) {
		//kickdump($thefilms);
	  //kickdump($sortorder);
	  //kickdump($filmorder);
	  array_multisort($sortorder,SORT_ASC,$filmorder);
	  
	  //kickdump($sortorder);
	  //kickdump($filmorder);
	  
	  foreach(array_unique($filmorder) as $film) {
	  	$newfilms[$film] = $thefilms[$film];
			$newobj[$thefilms[$film]] = $obj[$thefilms[$film]];
			
		}
		foreach($thefilms as $afilm) {
			//kickdump($afilm);
			//kickdump($newfilms);
			if (! in_array($afilm,$newfilms)) {
				//echo ("No ".$afilm);
				$newobj[$afilm] = $obj[$afilm];
				$newfilms[] = $afilm;
			}
		}
		if ($sort == "desc") {
	  	$newobj = array_reverse($newobj);
	  	$newfilms = array_reverse($newfilms);
		}
		//dump($newfilms);
	} else {
		$newobj = $obj;
	}
		
  if (! $as_json) {
		return $newobj;
	} else {
		return json_encode($newobj);
	}
  
}

function textTrunc( $value ) {
  $o=new CmsObject_format_utility( null );
  return $o -> textTrunc( $value );
}
