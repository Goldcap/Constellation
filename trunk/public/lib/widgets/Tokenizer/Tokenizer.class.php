<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Tokenizer_crud.php';
  
  class Tokenizer_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	var $timediff;
  var $user;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> timediff = 30;
    parent::__construct( $context );
  }

	function parse() {
	 
   $this -> user = $this -> sessionVar("user_id");
   
   if ($this -> as_service) {
    
    $k = $this -> postVar("k");
    putLog("USER:: ".$this -> user." | MESSAGE:: K is " . $k);
    
    if (sfConfig::get("sf_environment") != "prod") {
      if (strlen($this -> getVar("k")) > 4) {
        $k = $this -> getVar("k");
        $this -> timediff = 1000;
      }
      //$k = 14;
    }
    //dump('/http:\/\/'.str_replace(".","\.",sfConfig::get("app_domain")).'/');
    
    //if (! preg_match('/http:\/\/'.str_replace(".","\.",sfConfig::get("app_domain")).'/',$_SERVER["HTTP_REFERER"])) {
    //  die("unauth");
    //}
    
    //if (sfConfig::get("sf_environment") == "dev") {
      //$smil = new XML();
      //$smil -> loadXML(sfConfig::get("sf_data_dir")."/elephants.smil");
      //$smil -> saveXML();
      //die();
      //$k = "kUnl/3Z2dnb0pJ7nX4sl3YxSVIN4RIgVN4NxlBMOZ3KCwNsazsUPyjY5AmRllT==";
      //$this -> setSessionVar("user_id",89);
      //$k = "i1Dt/yoqKirEglgQfwrupx7g022Ac/y8KIE 2rvRA5EvopvLKOu0CK9zMMVK";
      //$this -> setSessionVar("user_id",1);
    //}
    
    //if (sfConfig::get("sf_environment") == "stage") {
      //$smil = new XML();
      //$smil -> loadXML(sfConfig::get("sf_data_dir")."/elephants.smil");
      //$smil -> saveXML();
      //die();
      //$k = "E0zl/yUlJSXFgk89J+Vj7PKPWC/T3h9Xm7gPzCPBGph5aYsYlBbMqEwFbkpXBz==";
      //$this -> setSessionVar("user_id",2372);
    //}
    
    
    if ($this -> getVar("id") == "init") {
      
      $var = str_replace(" ","+",$k);
	    //putlog($var);
      $data = AesCtr::decrypt($var, 'lockmeAmadeus256', 256);
	    //dump($data);
      putLog("USER:: ".$this -> user." | MESSAGE:: Decrypted " .$data);
      $vars = explode("|",$data);
      //dump($vars);
      /*
      if (sfConfig::get("sf_environment") != "prod") {
        if (strlen($this -> getVar("k")) < 4) {
          $vars = $this -> getFakeVars("init",$k);
        }
      }
      */
      
      //Make sure the decrypt worked properly
      if ((! is_array($vars)) || (count($vars) < 4)) {
        putLog("USER:: ".$this -> user." | MESSAGE:: Var Count ".count($vars)." <4");
        die("unauth");
      }
      
      //$vars[0] = Ticket Number
      //$vars[1] = Film Id
      //$vars[2] = HMAC Key
      //Truncate the Timestamp value
      $vars[3] = intval(substr($vars[3],0,10));
      $vars[4] = timestamp() - $this -> timediff;
      $args[0] = $vars[0];
      $args[1] = $this -> sessionVar("user_id");
      $args[2] = $vars[2];
      
      $sql = "select film_movie_file,
                film_cdn
                from film
                where film_id = ?";
      $res = $this -> propelArgs($sql,array($vars[1]));
      while($row = $res->fetch()) {
        $movie_file_type = $row[0];
        $movie_cdn = $row[1];
        putLog("USER:: ".$this -> user." | MESSAGE:: Movie File Type is ".$movie_file_type);
      }
      
      //Timestamp
      if ($vars[3] < $vars[4]) {
        putLog("USER:: ".$this -> user." | MESSAGE:: Timeout error:" .$vars[3]."<".$vars[4]);
        //kickdump("Timeout error:" .$vars[3]."<".$vars[4]);
        //die("unauth");
      }
      
      //dump($args);
      $sql = "select * 
              from audience 
              where audience_invite_code = ?
              and fk_user_id = ?
              and audience_hmac_key = ?
              and audience_paid_status = 2
              limit 1";
      $res = $this -> propelArgs($sql,$args);
      putLog("Rows Found: " . $res->rowcount());
      
      if($res->rowcount() != 1) {
        putLog("USER:: ".$this -> user." | MESSAGE:: No Audience: ".$args[0].",".$args[1].",".$args[2]);
        die("unauth");
      } else {
        $row = $res -> fetch();
        $sid = $row[0];
      }
      
      $starttime = now();
      $obj = FilmPeer::retrieveByPk($vars[1]);
      $times = explode(":",$obj -> getFilmRunningTime());
      $totaltime = ($times[0] * 3600) + ($times[1] * 60) + ($times[2]);
      $starttime = strtotime($starttime) + $totaltime;
      sfConfig::set("screening_id",$sid);
      sfConfig::set("startdate","[".formatDate($startdate,"W3XMLIN")." TO ".formatDate($enddate,"W3XMLIN")."]");
      //dump(sfConfig::get("startdate"));
      $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/ScreeningById_list_datamap.xml");
      
      if ($list["meta"]["totalresults"] != 1) {
        putLog("USER:: ".$this -> user." | MESSAGE:: No Screening: ".$sid." at".sfConfig::get("startdate"));
        die("unauth");
      }
      
      $this -> getEToken( $vars[1], $vars[0], $movie_file_type, "large", $movie_cdn );
      
      $status = "success";
      $token = $this -> etoken -> etoken;
      
      $res = new stdClass;
      $res -> tokenResponse = 
                          array("status"=>$status,
                                "token"=>$token);
      print(json_encode($res));
      die();
      
    } else {
      
      if ($k != '') {
        
        $var = str_replace(" ","+",$k);
  	    //putlog($var);
        $data = AesCtr::decrypt($var, 'lockmeAmadeus256', 256);
  	    putLog("USER:: ".$this -> user." | MESSAGE:: Decrypted " .$data);
        $vars = explode("|",$data);
        
        /*
        if (sfConfig::get("sf_environment") != "prod") {
          if (strlen($this -> getVar("k")) < 4) {
            $vars = $this -> getFakeVars("smil",$k);
          }
        }
        */
        
		    //Make sure the decrypt worked properly
        if ((! is_array($vars)) || ((count($vars) < 4))) {
          putLog("USER:: ".$this -> user." | MESSAGE:: Not enouch vars! ".count($vars)."<4");
          die("unauth");
        }
        
				if ($vars[1] == 119) {
					print ('{"fileURL":"http:\/\/conseczeritest-f.akamaihd.net\/z\/movies\/119\/movie-,low,small,medium,large,largest,.mov.csmil\/manifest.f4m"}');
	    		die();
    		}
      	//putLog( $_SERVER["REQUEST_URI"] );
      	//putLog($this -> getVar("k"));
      
  	  
        
        //This is for the HD2 Network
        $sizelist = '';
        $sql = "select film_bitrate_minimum,
								film_bitrate_low, 
								film_bitrate_small,
                film_bitrate_medium,
                film_bitrate_large,
                film_bitrate_largest,
                film_movie_file,
                film_cdn
                from film
                where film_id = ?";
        $res = $this -> propelArgs($sql,array($vars[1]));
        while($row = $res->fetch()) {
        	$minbr = $row[0] * 1000;
        	if ($minbr > 0) {
						$sizelist[] = 'min';
					}
          $lowbr = $row[1] * 1000;
          if ($lowbr > 0) {
						$sizelist[] = 'low';
					}
          $smallbr = $row[2] * 1000;
          if ($smallbr > 0) {
						$sizelist[] = 'small';
					}
          $mediumbr = $row[3] * 1000;
          if ($mediumbr > 0) {
						$sizelist[] = 'medium';
					}
          $largebr = $row[4] * 1000;
          if ($largebr > 0) {
						$sizelist[] = 'large';
					}
          $largestbr = $row[5] * 1000;
          if ($largestbr > 0) {
						$sizelist[] = 'largest';
					}
          $movie_file_type = $row[6];
          $movie_cdn = $row[7];
          putLog("USER:: ".$this -> user." | MESSAGE:: Movie File Type is ".$movie_file_type);
        } 
        
        //$vars[0] = Ticket Number
        //$vars[1] = Film Id
        //$vars[2] = HMAC Key 
        //$vars[3] = Timestamp
        //Truncate the Timestamp value
        $vars[3] = substr($vars[3],0,10);
        $vars[4] = timestamp() - $this -> timediff;
        $args[0] = $vars[0];
        $args[1] = $this -> sessionVar("user_id");
        $args[2] = $vars[2];
        
        //Timestamp
        if ($vars[3] < $vars[4]) {
          putLog("USER:: ".$this -> user." | MESSAGE:: Timeout error:" .$vars[3]."<".$vars[4]);
          //kickdump("Timeout error:" .$vars[3]."<".$vars[4]);
          //die("unauth");
        }
        
        //dump($args);
        $sql = "select screening_id
                from audience 
                inner join screening
                on screening_unique_key = fk_screening_unique_key
                where audience_invite_code = ?
                and fk_user_id = ?
                and audience_hmac_key = ?
                and audience_paid_status = 2
                limit 1";
        $res = $this -> propelArgs($sql,$args);
        putLog("USER:: ".$this -> user." - Rows Found: " . $res->rowcount());
        if($res->rowcount() != 1) {
          putLog("USER:: ".$this -> user." | MESSAGE:: No Screening:" .$args[0].",".$args[1].",".$args[2]);
          die("unauth");
        } else {
          $row = $res -> fetch();
          $sid = $row[0];
        }
        
        $starttime = now();
        $obj = FilmPeer::retrieveByPk($vars[1]);
        $times = explode(":",$obj -> getFilmRunningTime());
        $totaltime = ($times[0] * 3600) + ($times[1] * 60) + ($times[2]);
        $starttime = strtotime($starttime) + $totaltime;
        sfConfig::set("screening_id",$sid);
        sfConfig::set("startdate","[".formatDate($startdate,"W3XMLIN")." TO ".formatDate($enddate,"W3XMLIN")."]");
        
        $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/ScreeningById_list_datamap.xml");
        if ($list["meta"]["totalresults"] != 1) {
          putLog("USER:: ".$this -> user." | MESSAGE:: No Screening: ".$sid." at".sfConfig::get("startdate"));
          die("unauth");
        }
        
        putLog("USER:: ".$this -> user." | MESSAGE:: SUCCESSFUL PARSE FINISHED for ".$this -> user ." with screening ".$sid);
        
        if (! is_array($sizelist)) {
          putLog("USER:: ".$this -> user." | MESSAGE:: No Asset Size List with screening ".$sid);
          die("unauth");
        }
        if (($movie_cdn == 4) || ($movie_cdn == 5)) {
          putLog("USER:: ".$this -> user." | MESSAGE:: Movie Size List is ".implode(",",$sizelist));
        	$obj = new StdClass();
					$obj -> fileURL = $this -> getEToken( $vars[1], $vars[0], $movie_file_type, $list, implode(",",$sizelist), $movie_cdn );
        	die(json_encode($obj));
					//'{"fileURL":"http://multiplatform-f.akamaihd.net/z/multi/april11/akamai/Akamai_10_Year_,512x288_450_b,640x360_700_b,768x432_1000_b,1024x576_1400_m,1280x720_1900_m,1280x720_2500_m,1280x720_3500_m,.mp4.csmil/manifest.f4m"}'
        }
        
        //$token = $this -> getEToken( $vars[1], $vars[0], $movie_file_type, "large", $movie_cdn );
        if ($movie_cdn == 3) {
          $token_minimum = $this -> getEToken( $vars[1], $vars[0], $movie_file_type, $list, "min", $movie_cdn );
        } else {
          $token_minimum = $this -> getEToken( $vars[1], $vars[0], $movie_file_type, $list, "minimum", $movie_cdn );
        }
        $token_low = $this -> getEToken( $vars[1], $vars[0], $movie_file_type, $list, "low", $movie_cdn );
        $token_small = $this -> getEToken( $vars[1], $vars[0], $movie_file_type, $list, "small", $movie_cdn );
        $token_medium = $this -> getEToken( $vars[1], $vars[0], $movie_file_type, $list, "medium", $movie_cdn );
        $token_large = $this -> getEToken( $vars[1], $vars[0], $movie_file_type, $list, "large", $movie_cdn );
        $token_largest = $this -> getEToken( $vars[1], $vars[0], $movie_file_type, $list, "largest", $movie_cdn );
        
        putLog("USER:: ".$this -> user." | MESSAGE:: Small Token is: ".$token_small);
        
        $smil = new XML();
        //$smil -> loadXML(sfConfig::get("sf_data_dir")."/elephants.smil");
        //$smil -> saveXML();
        //die();
        
        if ($movie_cdn == 1) {
          $smil -> loadXML(sfConfig::get("sf_data_dir")."/map.smil");
        } elseif ($movie_cdn == 2) {
          $smil -> loadXML(sfConfig::get("sf_data_dir")."/ll.smil");
        } elseif ($movie_cdn == 3) {
          $smil -> loadXML(sfConfig::get("sf_data_dir")."/maphd.smil");
        }
        /*
        $smil -> createSingleElementByPath("video","//map",array("src"=>$token_small,"system-bitrate"=>$smallbr));
        $smil -> createSingleElementByPath("video","//map",array("src"=>$token_medium,"system-bitrate"=>$mediumbr));
        $smil -> createSingleElementByPath("video","//map",array("src"=>$token_large,"system-bitrate"=>$largebr));
        */
        $smil -> setPathAttribute("//meta",0,"token",$this -> etoken -> etoken);
        if ($minbr > 0) {
        	$smil -> createSingleElementByPath("video","//map",array("src"=>$token_minimum,"system-bitrate"=>$minbr));
        }
				if ($lowbr > 0) {
        	$smil -> createSingleElementByPath("video","//map",array("src"=>$token_low,"system-bitrate"=>$lowbr));
        }
				if ($smallbr > 0) {
        	$smil -> createSingleElementByPath("video","//map",array("src"=>$token_small,"system-bitrate"=>$smallbr));
        }
				if ($mediumbr > 0) {
        	$smil -> createSingleElementByPath("video","//map",array("src"=>$token_medium,"system-bitrate"=>$mediumbr));
        }
				if ($largebr > 0) {
        	$smil -> createSingleElementByPath("video","//map",array("src"=>$token_large,"system-bitrate"=>$largebr));
        }
				if ($largestbr > 0) {
        	$smil -> createSingleElementByPath("video","//map",array("src"=>$token_largest,"system-bitrate"=>$largebr));
        }
				$smil -> removeSingleElementByPath("//map");
        
        //$smil -> createSingleElementByPath("video","//map",array("system-bitrate"=>"300000","src"=>"mp4:constellation/movies/".$this -> getVar("id")."/movie-300.mp4"));
        //$smil -> createSingleElementByPath("video","//map",array("system-bitrate"=>"800000","src"=>"mp4:constellation/movies/".$this -> getVar("id")."/movie-800.mp4"));
        $smil -> saveXML();
        die();
      }
     }
   }
	 
	 return $this -> widget_vars;
    
  }
  
  function getEToken( $film, $code, $type, $screening, $size="large", $movie_cdn=1 ) {
    
    $c = new Criteria();
    $c->add(AudiencePeer::AUDIENCE_INVITE_CODE,$code);
    $theseat = AudiencePeer::doSelect($c);
    
    if ($movie_cdn == 1) {
      $this -> etoken = new TheaterAkamaiEtoken( $this -> context, $theseat[0] );
      $this -> etoken -> streamType = "smil";
      $this -> etoken -> movietype = $type;
      $window = 10;
      $window = 84600;
      $duration = $this -> etoken ->getStreamDuration($screening);
      $duration = 84600;
      $this -> etoken -> generateViewUrl( $film, $size, $window, $duration );
    } elseif ($movie_cdn == 2) {
      $this -> etoken = new TheaterLimelightEtoken( $this -> context, $theseat[0] );
      $this -> etoken -> streamType = "smil";
      $this -> etoken -> movietype = $type;
      $this -> etoken -> generateViewUrl( $film, $size, $_SERVER["HTTP_REFERER"] );
    } elseif ($movie_cdn == 3) {
      $this -> etoken = new TheaterAkamaiHDEtoken( $this -> context, $theseat[0] );
      $this -> etoken -> streamType = "smil";
      $this -> etoken -> movietype = $type;
      $window = 10;
      $window = 84600;
      $duration = $this -> etoken ->getStreamDuration($screening);
      $duration = 84600;
      $this -> etoken -> generateViewUrl( $film, $size, $window, $duration );
    } elseif ($movie_cdn == 4) {
      $this -> etoken = new TheaterAkamaiHD2Etoken( $this -> context, $theseat[0] );
      $this -> etoken -> streamType = "csmil";
      $this -> etoken -> movietype = $type;
      $window = 10;
      $window = 84600;
      $duration = $this -> etoken ->getStreamDuration($screening);
      $duration = 84600;
      $this -> etoken -> generateViewUrl( $film, $size, $window, $duration );
    } elseif ($movie_cdn == 5) {
      $this -> etoken = new TheaterAkamaiHD2iEtoken( $this -> context, $theseat[0] );
      $this -> etoken -> streamType = "csmil";
      $this -> etoken -> movietype = $type;
      $window = 10;
      $window = 84600;
      $duration = $this -> etoken ->getStreamDuration($screening);
      $duration = 84600;
      $this -> etoken -> generateViewUrl( $film, $size, $window, $duration );
    }
    return $this -> etoken -> viewingUrl;
    //return $this -> etoken -> streamUrl;
  }
  
  function getFakeVars( $type="smil", $k ) {
    
    if ($type == "init") {
      $sql = "select audience_invite_code,
            fk_film_id,
            audience_hmac_key,
            fk_user_id
            from audience 
            inner join screening
            on fk_screening_id = screening_id
            where fk_film_id = ?
            and audience_paid_status = 2
            limit 1";
      $res = $this -> propelArgs($sql,array($k));
      putLog("USER:: ".$this -> user." | MESSAGE:: Rows Found: " . $res->rowcount());
       
      if($res->rowcount() != 1) {
        die("unauth");
      } else {
         while($row = $res->fetch()) {
           $vars[0] = $row[0];
           $vars[1] = $row[1];
           $this -> setSessionVar("user_id",$row[3]);
           $vars[2] = $row[2];
         }
      }
    } else {
      $sql = "select audience_invite_code,
            fk_user_id,
            audience_hmac_key,
            fk_film_id
            from audience 
            inner join screening
            on fk_screening_id = screening_id
            where fk_film_id = ?
            and audience_paid_status = 2
            limit 1";
      $res = $this -> propelArgs($sql,array($k));
      putLog("USER:: ".$this -> user." | MESSAGE:: Rows Found: " . $res->rowcount());
      
      if($res->rowcount() != 1) {
        die("unauth");
      } else {
         while($row = $res->fetch()) {
           $vars[0] = $row[0];
           $vars[1] = $row[3];
           $this -> setSessionVar("user_id",$row[1]);
           $vars[2] = $row[2];
         }
      }
    }
    return $vars;
  }

}

?>

