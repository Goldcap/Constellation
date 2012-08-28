<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Tokenizer_crud.php';
  
  class Tokenizer_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	var $timediff;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> timediff = 10;
    parent::__construct( $context );
  }

	function parse() {
	 
   if ($this -> as_service) {
    
    $k = $this -> postVar("k");
    putLog("K is: " . $k . " at ".formatDate(null,"pretty"));
    putLog("User is: " . $this -> sessionVar("user_id"));
    $user = $this -> sessionVar("user_id");
    
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
      putLog("Decrypted " .$data);
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
        putLog(count($vars)."<4");
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
        putLog("Movie File Type is ".$movie_file_type);
      }
      
      //Timestamp
      if ($vars[3] < $vars[4]) {
        putLog("Timeout error:" .$vars[3]."<".$vars[4]);
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
        putLog("No Audience: ".$args[0].",".$args[1].",".$args[2]);
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
        putLog("No Screening: ".$sid." at".sfConfig::get("startdate"));
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
      
      //putLog( $_SERVER["REQUEST_URI"] );
      //putLog($this -> getVar("k"));
      
  	  if ($k != '') {
        
        $var = str_replace(" ","+",$k);
  	    //putlog($var);
        $data = AesCtr::decrypt($var, 'lockmeAmadeus256', 256);
  	    putLog("USER:: ".$user." - Decrypted " .$data);
        
        $vars = explode("|",$data);
        
        /*
        if (sfConfig::get("sf_environment") != "prod") {
          if (strlen($this -> getVar("k")) < 4) {
            $vars = $this -> getFakeVars("smil",$k);
          }
        }
        */
        
        //Make sure the decrypt worked properly
        if ((! is_array($vars)) || (count($vars) < 4)) {
          putLog("USER:: ".$user." - Not enouch vars! ".count($vars)."<4");
          die("unauth");
        }
        
        $sql = "select film_bitrate_small,
                film_bitrate_medium,
                film_bitrate_large,
                film_movie_file,
                film_cdn
                from film
                where film_id = ?";
        $res = $this -> propelArgs($sql,array($vars[1]));
        while($row = $res->fetch()) {
          $smallbr = $row[0] * 1000;
          $mediumbr = $row[1] * 1000;
          $largebr = $row[2] * 1000;
          $movie_file_type = $row[3];
          $movie_cdn = $row[4];
          putLog("USER:: ".$user." - Movie File Type is ".$movie_file_type);
        }
        
        //$vars[0] = Ticket Number
        //$vars[1] = Film Id
        //$vars[2] = HMAC Key
        //Truncate the Timestamp value
        $vars[3] = substr($vars[3],0,10);
        $vars[4] = timestamp() - $this -> timediff;
        $args[0] = $vars[0];
        $args[1] = $this -> sessionVar("user_id");
        $args[2] = $vars[2];
        
        //Timestamp
        if ($vars[3] < $vars[4]) {
          putLog("USER:: ".$user." - Timeout error:" .$vars[3]."<".$vars[4]);
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
        putLog("USER:: ".$user." - Rows Found: " . $res->rowcount());
        if($res->rowcount() != 1) {
          putLog("USER:: ".$user." - No Screening:" .$args[0].",".$args[1].",".$args[2]);
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
          putLog("USER:: ".$user." - No Screening: ".$sid." at".sfConfig::get("startdate"));
          die("unauth");
        }
        
        putLog("USER:: ".$user." - SUCCESSFUL PARSE FINISHED for ".$this -> sessionVar("user_id") ." with screening ".$sid);
        
        //$token = $this -> getEToken( $vars[1], $vars[0], $movie_file_type, "large", $movie_cdn );
        $token_small = $this -> getEToken( $vars[1], $vars[0], $movie_file_type, $list, "small", $movie_cdn );
        $token_medium = $this -> getEToken( $vars[1], $vars[0], $movie_file_type, $list, "medium", $movie_cdn );
        $token_large = $this -> getEToken( $vars[1], $vars[0], $movie_file_type, $list, "large", $movie_cdn );
        
        putLog("USER:: ".$user." - Small Token is: ".$token_small);
        
        
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
        $smil -> createSingleElementByPath("video","//map",array("src"=>$token_small,"system-bitrate"=>$smallbr));
        $smil -> createSingleElementByPath("video","//map",array("src"=>$token_medium,"system-bitrate"=>$mediumbr));
        $smil -> createSingleElementByPath("video","//map",array("src"=>$token_large,"system-bitrate"=>$largebr));
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
      putLog("Rows Found: " . $res->rowcount());
      
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
      putLog("Rows Found: " . $res->rowcount());
      
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
