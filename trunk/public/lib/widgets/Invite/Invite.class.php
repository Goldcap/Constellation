<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/LoginHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/TrackHelper.php"); 
  include_once(sfConfig::get('sf_lib_dir')."/helper/FacebookHelper.php");
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Invite_crud.php';
  
   class Invite_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	var $keys;
	var $film;
	var $total;
	var $haz_screening;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> haz_screening = true;
    parent::__construct( $context );
  }

	function parse() {
	
	 if ($this -> as_service) {
      switch($this -> getVar("id")) {
        case "record":
          
					$this -> keys[0] = $this -> sessionVar("user_id");
          $this -> keys[1] = $this -> getVar("film");
          $crud = new ScreeningCrud( $this -> context );
          $crud -> populate("screening_unique_key", $this -> getVar("screening"));
          if ($crud -> Screening -> getScreeningId() < 1) return false;
					$this -> keys[2] = $crud -> Screening -> getScreeningId();
          
					$this -> recordMessages( $this -> getVar("type"), $this->getVar("user_type"), $this -> getVar("count"), $this -> getVar("source") );
          print json_encode(array("result"=>"success","message"=>"Your invitations were recorded successfully.","count"=>$this -> getVar("count")));
          die();
        	break;
				case "send":
          $this -> keys[0] = $this -> sessionVar("user_id");
      
      		if ((! $this -> postVar("screening")) && ($this -> postVar("film"))) {
      			$this -> haz_screening = false;
          	$this -> setgetVar("op",$this -> postVar("film"));
            $this -> film = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/FilmMarquee/query/Film_list_datamap.xml");
            $this -> keys[1] = $this -> film["data"][0]["film_id"];
            $this -> keys[2] = null;
            $object = $this -> postVar("film");
					} else if (is_numeric($this -> postVar("screening"))) {
            sfConfig::set("screening_id",$this -> postVar("screening"));
            $this -> film = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Theater/query/ScreeningById_list_datamap.xml");
            $this -> keys[1] = $this -> film["data"][0]["screening_film_id"];
            $this -> keys[2] = $this -> film["data"][0]["screening_id"];
            $object = $this -> postVar("screening");
          } else {
            sfConfig::set("screening_unique_key",$this -> postVar("screening"));
            $this -> film = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Theater/query/ScreeningByKey_list_datamap.xml");
            $this -> keys[1] = $this -> film["data"][0]["screening_film_id"];
            $this -> keys[2] = $this -> film["data"][0]["screening_id"]; 
            $object = $this -> postVar("screening");
          }
          $this -> film["data"][0]["time_tz"] = date("h:i A, T",strtotime($this -> film["data"][0]["screening_date"]));
			    $this -> film["data"][0]["time_dayofweek"] = date("l",strtotime($this -> film["data"][0]["screening_date"]));
			    $this -> film["data"][0]["time_date"] = date("m/d",strtotime($this -> film["data"][0]["screening_date"]));
    			
          if ($this -> postVar("tweets")) {
          	$res = $this -> sendTweets( $object, $this -> postVar("tweets"), $this -> postVar("message") );
          }
					if ($this -> postVar("facebooks")) {
          	$res = $this -> postWalls( $object, $this -> postVar("facebooks"), $this -> postVar("message") );
          }
					if ($this -> postVar("emails")) {
          	$res = $this -> sendInvites( $object, $this -> postVar("emails"), $this -> postVar("message"), $this -> postVar("subject") );
          }
					//,"emails"=>implode(",",$this -> postVar("emails"))
          print json_encode(array("result"=>"success","message"=>"Your invitations were sent successfully.","count"=>$this -> total));
          //print json_encode(array("result"=>"failure","message"=>"There was an error sending your invitiations, please try again.","emails"=>""));
          die();
          break;
        case "info":
        	//SCREENING ONLY -- NOT FOR FILMS
          print $this -> getInfo( $this -> getVar("screening"));
          die();
          break;
        default:
          //SCREENING ONLY -- NOT FOR FILMS
          print $this -> previewInvite( $this -> getVar("screening"), $this -> getVar("message"), $this -> getVar("film") );
          die();
          break;
      }
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
  
  function getInfo( $screening ) {
    $user = "[Attendee]";
    if (is_numeric($screening)) {
      sfConfig::set("screening_id",$screening);
      $film = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Theater/query/ScreeningById_list_datamap.xml");
    } else {
      sfConfig::set("screening_unique_key",$screening);
	    $film = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Theater/query/ScreeningByKey_list_datamap.xml");
			if (file_exists(sfConfig::get("sf_app_dir")."/templates/text/facebook_".$screening.".txt")) {
			 	$d = file_get_contents(sfConfig::get("sf_app_dir")."/templates/text/facebook_".$screening.".txt");
				list($screening_image,$screening_film_name,$screening_film_synopsis) = explode("|",$d);
				$film["data"][0]["screening_image"] = $screening_image;
				$film["data"][0]["screening_film_name"] = $screening_film_name;
				$film["data"][0]["screening_film_synopsis"] = $screening_film_synopsis;
			}
    }
    if (! isset($film["data"][0]["screening_image"])) {
			$film["data"][0]["screening_image"] = "";
		}
    $film["data"][0]["time_tz"] = date("h:i A, T",strtotime($film["data"][0]["screening_date"]));
    $film["data"][0]["time_dayofweek"] = date("l",strtotime($film["data"][0]["screening_date"]));
    $film["data"][0]["time_date"] = date("m/d",strtotime($film["data"][0]["screening_date"]));
    die(json_encode($film["data"][0]));
  
  }
  
  function previewInvite( $screening, $message, $film=null ) {
  
    $user = "[Attendee]";
    if (is_numeric($screening)) {
      sfConfig::set("screening_id",$screening);
      $film = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Theater/query/ScreeningById_list_datamap.xml");
    } else {
      sfConfig::set("screening_unique_key",$screening);
      $film = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Theater/query/ScreeningByKey_list_datamap.xml");
    }
    
    $mail_view = new sfPartialView($this -> context, 'widgets', 'invitiation_email', 'invitiation_email' );
    $mail_view->getAttributeHolder()->add(array("film"=>$film["data"][0],"user"=>$user,"message"=>$message));
    $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/Invite_html.template.php";
    $mail_view->setTemplate($templateloc);
    $messagebody = $mail_view->render();
    
    return $messagebody;
  
  }
  
  function sendInvites( $object, $emails, $message=null, $subject=null, $preview=false ) {
  
    if ((! $emails) || (count($emails) == 0)) return;
    
    $this -> recordMessages( "email", $this->postVar("user_type"), count($emails) );
    
    if (! is_array($emails)) {
      $users = explode(",",$emails );
    } else {
      $users = $emails;
    }
    $beacon = "?".getBeaconByType( $this -> sessionVar("user_id"), 4);
    $object = preg_replace("/(#.+)/","",$object);
    
    //This is specific to screenings with unique data templates
    //So generally won't be used
    //Built specifically for "thevowevent"
    if (file_exists(sfConfig::get("sf_app_dir")."/templates/text/facebook_".$object.".txt")) {
		 	$d = file_get_contents(sfConfig::get("sf_app_dir")."/templates/text/facebook_".$object.".txt");
			list($picture,$name,$synopsis,$page) = explode("|",$d);
			$link = 'http://'.sfConfig::get("app_domain").'/'.$page.$beacon;
			$description = $this->postVar("name") . " has invited you to a showing of '" . $name . "' on Constellation, at " . $this -> film["data"][0]["time_tz"] . " on " . $this -> film["data"][0]["time_dayofweek"] . ", " . $this -> film["data"][0]["time_date"] . ". - '" . $synopsis . "'";
		}
		
		if ($this -> getUser() -> getAttribute("user_username") != '')  {
			$name = $this -> getUser() -> getAttribute("user_username");
		} else {
		  $name = "A Friend";
		}
		
		if (! $this -> haz_screening) {
			$subject = $subject ." - " .$name." invited you to ".$this -> film["data"][0]["film_name"];
		} else {
			$subject = $subject ." - " .$name." has invited you to ".$this -> film["data"][0]["screening_film_name"];
		}
		
		foreach ($emails as $a_user){
      $mail_view = new sfPartialView($this -> context, 'widgets', 'invitiation_email', 'invitiation_email' );
      $mail_view->getAttributeHolder()->add(array("name"=>$name,"film"=>$this -> film["data"][0],"user"=>$a_user,"message"=>$message, "beacon"=>$beacon));
      if (file_exists(sfConfig::get("sf_lib_dir")."/widgets/MessageManager/Invite_".$object."_html.template.php")) {
        $templateloc = sfConfig::get("sf_lib_dir")."/widgets/MessageManager/Invite_".$object."_html.template.php";
      } else  {
				if (! $this -> haz_screening) {
					$templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/InviteGeneric_html.template.php";
				} else {
				 	$templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/Invite_html.template.php";
				}
      }
			$mail_view->setTemplate($templateloc);
      $messagebody = $mail_view->render();
      
      if ($preview) {
				print $messagebody;
				die();
			}
      
      if (file_exists(sfConfig::get("sf_lib_dir")."/widgets/MessageManager/Invite_".$object."_text.template.php")) {
        $templateloc = sfConfig::get("sf_lib_dir")."/widgets/MessageManager/Invite_".$object."_text.template.php";
      } else  {
				if (! $this -> haz_screening) {
					$templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/InviteGeneric_text.template.php";
				} else {
				  $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/Invite_text.template.php";
				}
      }
      $mail_view->setTemplate($templateloc);
      $altbody = $mail_view->render();
      
      //$recips[0]["email"] = "amadsen@gmail.com";
      $recips[0]["email"] = $a_user;
      $recips[0]["fname"] = " ";
      $recips[0]["lname"] = " ";
      $mail = new WTVRMail( $this -> context );
      $mail -> user_session_id = "user_id";
      $mail -> queueMessage("UserInvite",$subject,$messagebody,$altbody,sfConfig::get("app_mail_from_address"),sfConfig::get("app_mail_from_fname"),sfConfig::get("app_mail_from_lname"),$recips,"mails",$this -> keys);
      
			$this -> total++;
    }
    
    //return ("{'response':[{'panel':'send','success': true, 'id': '000','html': '<div class=\"question\" id=\"r000\">You invited<br /><br /><span style=\"color: grey\">\"".implode($emails,"\"<br />\"")."\"</span></div>'}]}");
  
  }
  
  function sendTweets( $screening, $ids, $message=null ) {
    
    if ((! $ids) || (count($ids) == 0)) return;
    $beacon = "?".getBeaconByType( $this -> sessionVar("user_id"), 3);
    
    $this -> recordMessages( "twitter", $this->postVar("user_type"), count($ids) );
    
    $this -> inviter= new OpenInviter();
    $this -> oi_services = $this -> inviter->getPlugins();
    $this -> inviter->startPlugin( "twitter" );
    $text["body"] = 'Constellation.tv presents '.$this -> film["data"][0]["screening_film_name"].' (http://'.sfConfig::get("app_domain").'/film/'.$this -> film["data"][0]["screening_film_id"].$beacon.') '. $message;
    $this -> inviter->sendMessage($this -> postVar('tw_session'),$text,$ids);
		$this -> inviter->logout();
  }
  
  function postWalls( $screening=null, $ids, $message=null ) {
    
    if ((! $ids) || (count($ids) == 0)) return;
    
    $name = $this->postVar("name");
    $screening = preg_replace("/(#.+)/","",$screening);

    $beacon = "?".getBeaconByType( $this -> sessionVar("user_id"), 2);
    
    if (! is_null($screening)) {
    	$link = 'http://'.sfConfig::get("app_domain").'/theater/'.$this -> film["data"][0]["screening_unique_key"].$beacon;
			$description = $this->postVar("name") . " has invited you to a showing of " . $this -> film["data"][0]["screening_film_name"] . " on Constellation, at " . $this -> film["data"][0]["time_tz"] . " on " . $this -> film["data"][0]["time_dayofweek"] . ", " . $this -> film["data"][0]["time_date"] . ". - '" . $this -> film["data"][0]["screening_film_synopsis"] . "'";
	    $picture = 'http://www.constellation.tv/uploads/screeningResources/'.$this -> film["data"][0]["screening_film_id"].'/logo/film_logo'.$this -> film["data"][0]["screening_film_logo"];
			if (file_exists(sfConfig::get("sf_app_dir")."/templates/text/facebook_".$screening.".txt")) {
			 	$d = file_get_contents(sfConfig::get("sf_app_dir")."/templates/text/facebook_".$screening.".txt");
				list($picture,$name,$synopsis,$page) = explode("|",$d);
				$link = 'http://'.sfConfig::get("app_domain").'/'.$page.$beacon;
				$description = $this->postVar("name") . " has invited you to a showing of '" . $name . "' on Constellation, at " . $this -> film["data"][0]["time_tz"] . " on " . $this -> film["data"][0]["time_dayofweek"] . ", " . $this -> film["data"][0]["time_date"] . ". - '" . $synopsis . "'";
			}
		} else {
			$link = 'http://'.sfConfig::get("app_domain").'/'.$beacon;
			$description = 'Constellation.tv (http://'.sfConfig::get("app_domain").')';
			$picture = 'http://s3.amazonaws.com/cdn.constellation.tv/prod/images/alt1/logo-fb.png';
		}
		$message =  $this->postVar("message");
		$this -> recordMessages( "facebook", $this->postVar("user_type"), count($ids) );
    
    $this -> total = sendFacebookWalls( $ids, $this -> postVar('fb_session'), $name, $link, $description, $message, $picture );
    
  }
  
  function recordMessages( $type, $usertype, $count, $source=null ) {
  
    $ic = new UserInvite();
    $ic -> setFkUserId( $this -> keys[0] );
    $ic -> setUserInviteType( $type );
    $ic -> setUserType( $usertype );
    $ic -> setFkFilmId( $this -> keys[1] );
    $ic -> setFkScreeningId( $this -> keys[2] );
    $ic -> setUserInviteCount( $count );
    $ic -> setUserInviteDate( now() ); 
    $ic -> setUserInviteSource( $source );
    $ic -> save();
    
  }
}

?>
