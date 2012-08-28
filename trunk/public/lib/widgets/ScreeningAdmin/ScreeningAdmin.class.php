<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/OrderHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/AdminHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/ScreeningAdmin_crud.php';
  
   class ScreeningAdmin_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	var $repeats;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	  $this -> XMLForm -> item_forcehidden = true;
    $this -> crud = new ScreeningCrud( $context );
    $this -> repeats = 0;
    parent::__construct( $context );
  }

	function parse() {
    if ($this -> as_service) {
      $sql = "select user_id, user_full_name from user where user_fname like ? or user_lname like ? or user_full_name like ?";
      $term = "%".$this -> getVar("term")."%";
      $rs = $this -> propelArgs($sql,array($term,$term,$term));
      while ($row = $rs -> fetch()) {
        $ar["id"] = $row[0];
        $ar["value"] = $row[1]. ' (' .$row[0] . ')' ;
        $arr[] = $ar;
      }
      print json_encode($arr);
      die();
    }
    
    $this -> XMLForm -> registerArray("timezones",shortZoneList());
    
    $c = new Criteria();
    $c -> addAscendingOrderByColumn("film_name");
    $films = FilmPeer::doSelect( $c );
    foreach ($films as $film) {
    	$filma["sel_key"] = $film -> getFilmId();
    	$filma["sel_value"] = $film -> getFilmName();
    	$filmarray[] = $filma;
    }
    $this -> XMLForm -> registerArray("films",$filmarray);
    
    $c = new Criteria();
    $users = UserPeer::doSelect( $c );
    foreach ($users as $user) {
    	$usera["sel_key"] = $user -> getUserId();
    	$usera["sel_value"] = $user -> getUserFullName();
    	$userarray[] = $usera;
    }
    
    $this -> XMLForm -> registerArray("users",$userarray);
	 //return $this -> widget_vars;
   
	  if ($this -> XMLForm -> isPosted()) {  
      $this -> doPost();
    }
    $this -> doGet();
    
   $this -> SearchForm = new XMLForm($this -> widget_name, "searchconf.php");
   $this -> SearchForm -> validated= false;
   $searchform = $this -> SearchForm -> drawForm();
   $this -> widget_vars["search_form"] = $searchform["form"];
   
   $form = $this -> drawPage();
   $this -> widget_vars["form"] = $form["form"];
   return $this -> widget_vars;
    
  }

  function doPost(){
    //$this -> XMLForm -> validate_debug = 2;
     if ($this -> XMLForm -> validateForm()) {
        switch ($this -> getFormMethod()) {
          case "submit":
          if (($this -> postVar("screening_date") == "") || ($this -> postVar("screening_time") == "")) {
            return;
          }
          if (! $this -> postVar("screening_has_qanda")) {
            $this -> crud -> setScreeningHasQanda(0);     
            $this -> crud -> setScreeningLiveWebcam(0);
          }
          /*
					if (! $this -> postVar("screening_live_webcam")) {
            $this -> crud -> setScreeningLiveWebcam(0);
          }
          */
          if (! $this -> postVar("screening_featured")) {
            $this -> crud -> setScreeningFeatured(0);
          }
          if (! $this -> postVar("screening_allow_latecomers")) {
            $this -> crud -> setScreeningAllowLatecomers(0);
          }
          if (! $this -> postVar("screening_highlighted")) {
            $this -> crud -> setScreeningHighlighted(0);
          }
          if (! $this -> postVar("screening_is_private")) {
            $this -> crud -> setScreeningIsPrivate(0);
          }
          if ((! $this -> postVar("screening_name")) || ($this -> postVar("screening_name") == '')) {
            $this -> crud -> setScreeningName("");
          }
          if (! $this -> postVar("screening_record_webcam")) {
            $this -> crud -> setScreeningVideoServerHostname("akamai");
          } else {
            $this -> crud -> setScreeningVideoServerHostname("fms");
          }
          $this -> crud -> write();
          
          $starttime = formatDate($this -> postVar("screening_date"),"MDY-") . " " . formatDate($this -> postVar("screening_time"),"time");
          $obj = FilmPeer::retrieveByPk($this -> postVar("fk_film_id"));
          $times = explode(":",$obj -> getFilmRunningTime());
          $totaltime = ($times[0] * 3600) + ($times[1] * 60) + ($times[2]);
          $this -> crud ->setScreeningEndTime(strtotime($starttime) + $totaltime);
          $this -> crud ->setScreeningStatus(2);
          
          if ($this -> postVar("hostname") == '') {
          	
						$this -> crud ->setScreeningGuestName( null );
            $this -> crud ->setScreeningGuestImage( null );
            $this -> crud ->setScreeningHasQanda( null );
            $this -> crud ->setScreeningLiveWebcam( null );
            $this -> crud ->setFkHostId( null );
            
          } else if ($this -> postVar("fk_host_id") > 0) {
            
						$host = UserPeer::retrieveByPk($this -> postVar("fk_host_id"));
            if ($host) {
              $this -> crud ->setScreeningGuestName( $host -> getUserFullName() );
              if ($host -> getUserImage() != "") {
                $this -> crud ->setScreeningGuestImage( $host -> getUserImage() );
              }
              $this -> crud ->setScreeningHasQanda( 1 );
              $this -> crud ->setScreeningLiveWebcam( 1 );
            }
            if ($this -> postVar("screening_has_qanda") == 1) {
						  if ($this -> postVar('screening_video_server_instance_id') == '') {
								$hObj = new TheaterHosting_PageWidget( $this -> context );
							  $hObj -> genSession();
							  $this -> crud ->setScreeningVideoServerInstanceId( $hObj -> tokbox_sessionId );
							}
						}
						
            //Check to make sure this host has a payment
            //Put Order Post Data in the database
            $pmt = new PaymentCrud( $this -> context );
            $vars = array("fk_user_id"=>$host -> getUserId(),"fk_screening_id"=>$this -> crud -> Screening -> getScreeningId());
            $pmt -> checkUnique($vars);
            $pmt -> setPaymentFirstName($host -> getUserFname());
            $pmt -> setPaymentLastName($host -> getUserLname());
            $pmt -> setPaymentType("host");
            $pmt -> setPaymentEmail($host -> getUserEmail());
            $pmt -> setPaymentInvites( 0 );
            $pmt -> setFkFilmId($this -> crud -> Screening -> getFkFilmId());
            $pmt -> setFkScreeningId($this -> crud -> Screening -> getScreeningId());
            $pmt -> setPaymentStatus(2);
            $pmt -> setPaymentAmount(0);
            $pmt -> setFkUserId( $host -> getUserId() );
            $pmt -> setPaymentUniqueCode( setUserOrderGuid() );
            $pmt -> setPaymentCreatedAt( now() );
            $pmt -> setPaymentIp( REMOTE_ADDR() );
            $pmt -> setPaymentSiteId( sfConfig::get("app_site_id") );
            $pmt -> setPaymentOrderProcessor( "Admin" );
            $pmt -> save();
            
            //Check to make sure this host has a ticket
            $item = new AudienceCrud( $this -> context );
            $vars = array("fk_user_id"=>$host->getUserId(),"fk_payment_id"=>$pmt -> Payment -> getPaymentId(),"fk_screening_unique_key"=>$this -> crud -> Screening -> getScreeningUniqueKey());
            $item -> checkUnique( $vars );
						if ($item -> getAudienceId() < 1) {
							$item -> setFkScreeningUniqueKey( $this -> crud -> Screening -> getScreeningUniqueKey() );
	            $item -> setFkScreeningId( $this -> crud -> Screening -> getScreeningId() );
	            $item -> setFkUserId( $host -> getUserId() );
	            $item -> setAudienceTicketPrice( 0 );
	            $item -> setFkPaymentId( $pmt -> Payment -> getPaymentId() );
	            $item -> setAudiencePaidStatus( 2 );
	            $item -> setAudienceIpAddr( REMOTE_ADDR() );
	            $item -> setAudienceCreatedAt( now() );
	            $item -> setAudienceStatus( 0 );
	            if ($host -> getUserUsername() != '') {
	              $item -> setAudienceUsername( $host -> getUserUsername() );
	            }
	            $code = setUserOrderTicket();
	            $item -> setAudienceInviteCode( $code );
	            $item -> setAudienceShortUrl( '/theater/' .$this -> crud -> Screening -> getScreeningUniqueKey() . '/' . $code );
	            $item -> setAudiencePaidStatus( 2 );
	            $item -> save();
	            
	            $pmt -> setFkAudienceId( $item -> Audience -> getAudienceId() );
	            $pmt -> save();
            }
            
          }
          //dump($_POST);
          /*
          if ($this -> postVar("screening_id") == 0) {
            
            $file = new WTVRFile();
            $id = $this -> crud -> Screening -> getFkFilmId();
            if ($this -> postVar("FILE_screening_still_image_guid")) {
              $sourcr = explode("/",$this -> postVar("FILE_screening_still_image_guid"));
              $filename = array_pop($sourcr);
              array_pop($sourcr);
              $sourcer = implode("/",$sourcr);
              $source = sfConfig::get("sf_web_dir")."/uploads/screeningResources/".$sourcer;
              $dest = sfConfig::get("sf_web_dir")."/uploads/screeningResources/".$id;
              createDirectory($dest);
              $dest = $dest;
              $file -> moveFile($source."/screenings",$dest."/screenings");
              $filename = explode("_",$filename);
              $this -> crud -> setScreeningStillImage( end($filename) . ".jpg" );
            }
          } else {
          */
            if ($this -> postVar("FILE_screening_still_image_guid")) {
              //Source
              ///uploads/screeningResources/temp/b/b2/b27/b278fd1e5b3320ef277e1768a4c1941b/screenings
              $id = $this -> crud -> Screening -> getFkFilmId();
              $sourcr = explode("/",$this -> postVar("FILE_screening_still_image_guid"));
              $filename = array_pop($sourcr);
              $filename = explode("_",$filename);
              $filename =  end($filename);
              $this -> crud -> setScreeningStillImage( $filename . ".jpg" );
              array_pop($sourcr);
              $sourcer = implode("/",$sourcr);
              $source = sfConfig::get("sf_web_dir")."/uploads/screeningResources/".$sourcer."/screenings";
              $dest = sfConfig::get("sf_web_dir")."/uploads/screeningResources/".$id."/screenings";
	      			createDirectory($dest);
              $d = dir( $source );
              while ( FALSE !== ( $entry = $d->read() ) ) {
                if ( $entry == '.' || $entry == '..' ) {  continue; }
                $thafile = $source . '/' . $entry;
		//kickdump($thafile);
                if ( is_dir( $thafile ) && (! preg_match("/\.svn/",$source))) {
                    continue;
                }
                if (preg_match("/".$filename."/",$thafile)) {
                  //print("Copied ".$thafile." to ".$dest . '/' . $entry);
                  copy( $thafile, $dest . '/' . $entry );
                } else {
                  //print("Skipped ".$thafile);
                }
              }
              $d->close();

              //$lar = explode("/",$this -> postVar("FILE_screening_still_image_guid"));
              //$filename = explode("_",end($lar));
              $this -> crud -> setScreeningStillImage( $filename . ".jpg" );
            }
          /*
          }
          */
          //If it's a FREE Screening, set it to "PAID"
          if ($this -> postVar("screening_type") == "2") {
            $this -> crud -> setScreeningPaidStatus(2);
          }
          $this -> crud -> setScreeningUpdatedAt(now());
          
          if ($this -> crud -> Screening -> getScreeningCreatedAt() == "") {
            $this -> crud -> setScreeningCreatedAt(now());
          }
          
          if ($this -> postVar("screening_chat_moderated") != "Yes") {
          	$this -> crud -> setScreeningChatModerated(0);
          } else {
					  $this -> crud -> setScreeningChatModerated(1);
					}
          
          $this -> crud -> save();
              
           //Update the SOLR Search Engine
          $solr = new SolrManager_PageWidget(null, null, $this -> context);
          $solr -> execute("add","screening",$this -> crud -> Screening -> getScreeningId());
          
          if (($this -> postVar("screening_repeats") == "Yes") && ($this -> postVar("screening_repeat_interval") > 0) && ($this -> postVar("screening_repeat_start") != "") && ($this -> postVar("screening_repeat_count") > 0) )  {
            
            $interval = $this -> postVar("screening_repeat_interval") * 60;
            $thestart = strtotime($this -> postVar("screening_repeat_start"));
            
            /*
            ["screening_date"]=>
            string(10) "2011-04-02"
            ["screening_time"]=>
            string(8) "21:00:00"
            ["screening_repeats"]=>
            string(1) "2"
            ["screening_repeat_start"]=>
            string(16) "2011-04-02 08:00"
            ["screening_repeat_interval"]=>
            string(2) "60"
            */
            //We need to create screenings for today
            for ($i = 1; $i <= $this -> postVar("screening_repeat_count"); $i++) {
              if ($i > 1) {
                $thestart = $thestart + $interval;
              }
              $code = setScreeningId();
              $c = new Criteria();
              $c->add(ScreeningPeer::FK_FILM_ID,$this->crud->Screening->getFkFilmId());
              $c->add(ScreeningPeer::FK_HOST_ID,$this->crud->Screening->getFkHostId());
              $c->add(ScreeningPeer::SCREENING_DATE,formatDate($thestart,"MDY-"));
              $c->add(ScreeningPeer::SCREENING_TIME,formatDate($thestart,"time"));
              $c->add(ScreeningPeer::SCREENING_TYPE,$this -> postVar("screening_type"));
              $c->add(ScreeningPeer::SCREENING_DEFAULT_TIMEZONE_ID,$this -> postVar("screening_default_timezone_id"));
              $c->setDistinct();
              $Screening = ScreeningPeer::doSelect($c);
              
              if (count($Screening) == 0) {
                $ni = $this -> crud -> Screening -> copy();
                $ni -> setScreeningDate(formatDate($thestart,"MDY-"));
                $ni -> setScreeningTime(formatDate($thestart,"time"));
                $ni -> setScreeningUniqueKey($code);
                $astart = formatDate($thestart,"MDY-") . " " . formatDate($thestart,"time");
                //Total time is the running time from above
                $ni -> setScreeningEndTime(strtotime($astart) + $totaltime);
                $ni -> setScreeningUpdatedAt(now());
            
                if ($ni -> getScreeningCreatedAt() == "") {
                  $ni -> setScreeningCreatedAt(now());
                }
                if ($ni -> getScreeningDate() == "") {
                  continue;
                }
                if ($ni -> getScreeningtime() == "") {
                  continue;
                }
                $ni -> save();
                 //Update the SOLR Search Engine
                $solr = new SolrManager_PageWidget(null, null, $this -> context);
                $solr -> execute("add","screening",$ni -> getScreeningId());
          
                $this -> repeats++;
              }
            }
          }
          
		            
	        //Update all audiences for this film, too
	        $sql = "select audience_id from audience where fk_screening_id = ".$this -> crud -> Screening -> getScreeningId();
	        $res = $this -> propelQuery($sql);
	        while ($row = $res -> fetch()) {
	        	$solr = new SolrManager_PageWidget(null, null, $this -> context);
	          $solr -> execute("add","audience",$row[0]);
	        }
          break;
          case "delete":
          //Update the SOLR Search Engine
          try {
            //Update all audiences for this film, too
		        $sql = "select audience_id from audience where fk_screening_id = ".$this -> crud -> Screening -> getScreeningId();
		        $res = $this -> propelQuery($sql);
		        while ($row = $res -> fetch()) {
		          $solr = new SolrManager_PageWidget(null, null, $this -> context);
		          $solr -> execute("delete","audience",$row[0]);
		        }
		        
		        $solr = new SolrManager_PageWidget(null, null, $this -> context);
            $solr -> execute("delete","screening",$this -> crud -> Screening -> getScreeningId());
            $this -> crud -> remove();
            
            $solr = new SolrManager_PageWidget(null, null, $this -> context);
            $solr -> execute("delete","upcoming",$this -> crud -> Screening -> getScreeningId());
            $this -> crud -> remove();
		        
          } catch ( Exception $e ) {
          }
          $this -> redirect("/screening");
          die();
          break;
        }
        
      }
    
  }

  function doGet(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      $this -> pushItem();
      $host = UserPeer::retrieveByPk($this -> XMLForm -> item["fk_host_id"]);
      
      if ($this -> crud -> Screening -> getScreeningStillImage() != "") {
        $this -> XMLForm -> item["screening_still_image"] = "film_screening_large_".$this -> crud -> Screening -> getScreeningStillImage();
      }
      if ($host)
        $this -> XMLForm -> item["hostname"] = $host -> getUserFullName();
      if (($this -> crud -> Screening -> getScreeningHasQanda() == "") ||
        ($this -> crud -> Screening -> getScreeningHasQanda() == 0)) {
        $this -> XMLForm -> item["screening_has_qanda_enum"] = "false";
      } else {
        $this -> XMLForm -> item["screening_has_qanda_enum"] = "true";
      }
      if (($this -> crud -> Screening -> getScreeningLiveWebcam() == "") ||
        ($this -> crud -> Screening -> getScreeningLiveWebcam() == 0)) {
        $this -> XMLForm -> item["screening_live_webcam_enum"] = "false";
      } else {
        $this -> XMLForm -> item["screening_live_webcam_enum"] = "true";
      }
      if (($this -> crud -> Screening -> getScreeningChatModerated() == "") ||
        ($this -> crud -> Screening -> getScreeningChatModerated() == 0)) {
        $this -> XMLForm -> item["screening_chat_moderated"] = "No";
      } else {
        $this -> XMLForm -> item["screening_chat_moderated"] = "Yes";
      }
      if (($this -> crud -> Screening -> getScreeningVideoServerHostname() == "akamai") ||
        ($this -> crud -> Screening -> getScreeningVideoServerHostname() == "")) {
        $this -> XMLForm -> item["screening_record_webcam"] = 0;
      } else {
        $this -> XMLForm -> item["screening_record_webcam"] = 1;
      }
      if (($this -> crud -> Screening -> getScreeningIsPrivate() == "") ||
        ($this -> crud -> Screening -> getScreeningIsPrivate() == 0)) {
        $this -> XMLForm -> item["screening_is_private_enum"] = "false";
      } else {
        $this -> XMLForm -> item["screening_is_private_enum"] = "true";
      }
      if (($this -> crud -> Screening -> getScreeningFeatured() == "") ||
        ($this -> crud -> Screening -> getScreeningFeatured() == 0)) {
        $this -> XMLForm -> item["screening_featured_enum"] = "false";
      } else {
        $this -> XMLForm -> item["screening_featured_enum"] = "true";
      }
      if (($this -> crud -> Screening -> getScreeningHighlighted() == "") ||
        ($this -> crud -> Screening -> getScreeningHighlighted() == 0)) {
        $this -> XMLForm -> item["screening_highlighted_enum"] = "false";
      } else {
        $this -> XMLForm -> item["screening_highlighted_enum"] = "true";
      }
      if ($this -> crud -> Screening -> getScreeningUniqueKey() == "")
        $this -> XMLForm -> item["screening_unique_key"] = setScreeningId();
      if ($this -> crud -> Screening -> getScreeningDefaultTimezoneId() == "")
        $this -> XMLForm -> item["screening_default_timezone_id"] = "New York";
      if ($this -> repeats > 0)
        $this -> XMLForm -> item["screening_dupes"] = "Created ".$this -> repeats." duplicate screenings.";
    
      $this -> XMLForm -> item["user_screenings"] = $this -> returnList("ScreeningUserAdmin_list_datamap.xml");
     
    }
  }

  function drawPage(){
    
    if ($this -> getVar("op") == "search") {
      return $this -> screeningSearch();
    } elseif (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      return $this -> returnForm();
    } elseif ($this ->getOp() == "list" ) {
      return $this -> returnList();
    }
    
  }
  
  function screeningSearch( $query=false ) {
    
    //$this -> showData();
    //$this -> showXML();  
  
    $util = new ScreeningAdmin_format_utility( $this -> context );
    
    if (! $query) {
      
      if (($this -> greedyVar("screening_start_date")) && (! $this -> greedyVar("screening_end_date"))) {
        $start = formatDate($this -> greedyVar("screening_start_date"),"W3XMLIN");
        //$end = formatDate($this -> postVar("cms_object_end_date"),"W3XML");
        $util -> date = "[ ". $start ." TO * ]"; 
      }
      if ((! $this -> greedyVar("screening_start_date")) && ($this -> greedyVar("screening_end_date"))) {
        //$start = formatDate($this -> postVar("cms_object_start_date"),"W3XML");
        $end = formatDate($this -> greedyVar("screening_end_date"),"W3XMLIN");
        $util -> date = "[ * TO ". $end." ]"; 
      }
      if (($this -> greedyVar("screening_start_date")) && ($this -> greedyVar("screening_end_date"))) {
        $start = formatDate($this -> greedyVar("screening_start_date"),"W3XMLIN");
        $end = formatDate($this -> greedyVar("screening_end_date"),"W3XMLIN");
        $util -> date = "[ ". $start ." TO ". $end." ]"; 
      }
      if (($this -> greedyVar("screening_term"))) {
        $term = $this -> greedyVar("screening_term");
        $util -> term = $this -> greedyVar("screening_term");
      }
      if ($this -> greedyVar("fk_film_id") > 0) {
        $util -> film = $this -> greedyVar("fk_film_id");
      }
      
      $search = ($term != "") ? $term : "[ * TO * ]";
      
      if (is_numeric($term) && ($term < 1000000)) {
        $num = $term;
      } else {
        $num = 0;
      }
      
      sfConfig::set("search",$term);
      sfConfig::set("num",$num);
      
    }
    
    
    return $this -> returnList( "ScreeningSearch_list_datamap.xml", true, true, "standard", $util );
    
  }
  
}

?>
