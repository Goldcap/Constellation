<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/ImageHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/SponsorUserAdmin_crud.php';
  
   class SponsorUserAdmin_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    
    $this -> crud = new SponsorCodeCrud( $context );
    parent::__construct( $context );
    
    if ($this -> getVar("op") == "import") {
      $this -> XMLForm = new XMLForm($this -> widget_name,"formconf_import.php");
    } elseif ($this -> getVar("op") == "send") {
      $this -> XMLForm = new XMLForm($this -> widget_name,"formconf_send.php");
    } else {
      $this -> XMLForm = new XMLForm($this -> widget_name);
	  }
    $this -> XMLForm -> item_forcehidden = true;
  }

	function parse() {
	 
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
     if ($this -> XMLForm -> validateForm()) {
        switch ($this -> getFormMethod()) {
          case "submit":
          $vars = array("sponsor_code"=>$this -> postVar("sponsor_code"),
          							"sponsor_code_user_email" => $this -> postVar("sponsor_code_user_email"));
          //$this -> crud -> checkUnique($vars);
          $this -> crud -> write();
          if ($this -> postVar("sponsor_code_use") == 0) {
            $this -> crud -> setSponsorCodeUse( 0 );
          }
          if ($this -> postVar("sponsor_code_use_count") == 0) {
            $this -> crud -> setSponsorCodeUseCount( 0 );
          }
          if ($this -> postVar("sponsor_code_spawn_new_users") == 1) {
            $this -> crud -> setSponsorCodeSpawnNewUsers( 1 );
          } else {
             $this -> crud -> setSponsorCodeSpawnNewUsers( 0 );
          }
          if (($this -> postVar("send_notice") === "1") && ($this -> postVar("sponsor_code_user_email") != '')) {
            $this -> setGetVar("op",$this -> postVar("fk_film_id"));
            $film = $this->dataMap(sfConfig::get('sf_lib_dir')."/widgets/FilmView/query/Film_list_datamap.xml");
    
            $mail_view = new sfPartialView($this->context, 'widgets', 'ticket_email', 'ticket_email' );
            $mail_view->getAttributeHolder()->add(array("film"=>$film["data"][0], "code"=>$this -> postVar("sponsor_code")));
            $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/Ticket_".$film["data"][0]["film_short_name"]."_html.template.php";
            $mail_view->setTemplate($templateloc);
            $message = $mail_view->render();
            $altbody = "";
            
            //$recips[0]["email"] = "amadsen@gmail.com";
            $recips[0]["email"] = $this -> postVar("sponsor_code_user_email");
            $recips[0]["fname"] = $this -> postVar("sponsor_code_user_fname");
            $recips[0]["fname"] = $this -> postVar("sponsor_code_user_lname");
            $subject = "Your ticket to ".$film["data"][0]["film_name"];
            $mail = new WTVRMail( $this -> context );
            $mail -> user_session_id = "user_id";
            $mail -> queueMessage("blank",$subject,$message,$altbody,sfConfig::get("app_mail_from_address"),sfConfig::get("app_mail_from_fname"),sfConfig::get("app_mail_from_lname"),$recips);
            if ($this -> crud -> SponsorCode -> getSponsorCodeUserNotified() == "") {
              $notices = 0;
            } else {
              $notices = $this -> crud -> SponsorCode -> getSponsorCodeUserNotified();
            }
            $this -> crud -> setSponsorCodeUserNotified( $notices + 1 );
            $this -> setGetVar("op","detail");
          }
          
          $this -> crud -> save();
          
          break;
          case "parse":
          $this -> parseUploadFile();
          break;
          case "send":
          $this -> sendNotices();
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
      $host = UserPeer::retrieveByPk($this -> XMLForm -> item["fk_user_id"]);
      if ($host)
        $this -> XMLForm -> item["username"] = $host -> getUserFullName();
    } elseif (($this ->getOp() == "download") && ($this -> getVar("id"))) {
      showExcel($this -> context, sfConfig::get("sf_data_dir")."/exports/".$this -> getVar("id").'.xls',"true",$this -> getVar("id").'.xls');
      die();
    }
        
    
  }

  function drawPage(){
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      return $this -> returnForm();
    } elseif ($this ->getOp() == "search" ) {
      return $this -> userSearch();
    } elseif  (($this ->getOp() == "import" ) || ($this -> getOp() == "send")) {
      return $this -> returnForm();
    } elseif ($this ->getOp() == "export" ) {
      $this -> widget_vars["filename"] = $this -> exportList();
      $this -> setTemplate( "SponsorUserAdminExport" );
      return $this -> widget_vars;
    } elseif ($this ->getOp() == "list" ) {
      return $this -> returnList();
    }
    
  }
  
  function exportList() {
    $filename = "Sponsor_Codes_".nowAsId();
    $args["filename"]= $filename.'.xls';
    $args["location"]= sfConfig::get("sf_data_dir")."/exports/"; 
    $util = new SponsorUserAdminSearch_format_utility( $this -> context );
    if ($this -> greedyVar("user_start_date")) {
      $util -> startdate = formatDate($this -> greedyVar("user_start_date"),"TS");
    }
    if ($this -> greedyVar("user_end_date")) {
      $util -> enddate = formatDate($this -> greedyVar("user_end_date"),"TS");
    }
    if (($this -> greedyVar("user_term"))) {
      $util -> term = $this -> greedyVar("user_term");
    }
    if ($this -> greedyVar("fk_film_id") > 0) {
      $util -> film = $this -> greedyVar("fk_film_id");
    }
    
    if (is_numeric($term) && ($term < 1000000)) {
      $num = $util -> term;
    } else {
      $num = 0;
    }
    
    sfConfig::set("search",$util -> term);
    sfConfig::set("num",$num);
    $this -> returnExcel( "SponsorUserAdmin_export_datamap.xml", true, false, $args, $util );
    return $filename;
  }
  
  function parseUploadFile() {
    //error_reporting(0);
    
    $file = new WTVRFile("sponsor_code_file");
    
    if ($file -> isUploaded()) {
      $file -> destination_name = nowAsId()."_users_".$this -> postVar("fk_film_id");
      $file -> destination_dir = sfConfig::get("sf_data_dir")."/sponsor_uploads";
      $file -> move();
      
    }
    $objReader = PHPExcel_IOFactory::createReaderForFile($file -> destination_fullname);
    $objReader->setReadDataOnly(true);
    $objPHPExcel = $objReader->load($file -> destination_fullname);
    foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
    	
    	foreach ($worksheet->getRowIterator() as $row) {
    		if ($row->getRowIndex() > 1) {
    		  $auser = array();
          $cellIterator = $row->getCellIterator();
      		$cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
      		foreach ($cellIterator as $cell) {
      			if (!is_null($cell)) {
      			 switch(left($cell->getCoordinate(),1)) {
              case "A": 
                $auser["fname"]=$cell->getCalculatedValue();
                break;
              case "B":
                $auser["lname"]=$cell->getCalculatedValue();
                break;
              case "C":
                $auser["username"]=$cell->getCalculatedValue();
                break;
              case "D":
                $auser["email"]=$cell->getCalculatedValue();
                break;
              case "E":
                $auser["code"]=$cell->getCalculatedValue();
                break;
             } 
      			}
      		}
      		
      		$theuser = new SponsorCodeCrud( $this -> context );
      		$vars = array("sponsor_code_user_username"=>$auser["username"],
                        "sponsor_code_user_fname"=>$auser["fname"],
                        "sponsor_code_user_lname"=>$auser["lname"],
                        "sponsor_code_user_email"=>$auser["email"],
                        "sponsor_code"=>$auser["code"]);
          $theuser -> checkUnique($vars);
          $num = "";
          while (! $this -> checkUsername($auser["username"],$theuser -> SponsorCode -> getSponsorCodeId(),$num)) {
            if ($num == "") {
              $num = 1;
            } else {
              $num++;
            }
          }
          $auser["username"] = $auser["username"].$num;
      		
          $theuser -> setSponsorCodeUserFname($auser["fname"]);
          $theuser -> setSponsorCodeUserLname($auser["lname"]);
          $theuser -> setSponsorCodeUserUsername($auser["username"]);
          $theuser -> setSponsorCodeUserEmail($auser["email"]);
          $theuser -> setSponsorCode($auser["code"]);
          $theuser -> setSponsorCodeStartDate( $this -> postVar("sponsor_code_start_date") );
      		$theuser -> setSponsorCodeEndDate( $this -> postVar("sponsor_code_end_date") );
      		$theuser -> setSponsorCodeUse( $this -> postVar("sponsor_code_use") );
      		$theuser -> setSponsorCodeUseCount( $this -> postVar("sponsor_code_use_count") );
      		$theuser -> setSponsorCodeUserNotified( 0 );
      		$theuser -> setFkFilmId( $this -> postVar("fk_film_id") );
      		$theuser -> save();
    		}
    	}
    }
  }
  
  function checkUsername( $username, $id, $num) {
    if (is_null($id)) {
      $id = 0;
    }
    $username = $username.$num;
    $sql = "select * 
            from sponsor_code 
            where sponsor_code_user_username = '".addslashes($username)."'
            and sponsor_code_id <> ".$id;
    $res = $this -> propelQuery($sql);
		if($res -> rowCount() > 0){
		  return false;
		} else {
      return true;
    }
  }
  
  function sendNotices() {
    
    //return $this -> widget_vars;
    $c = new Criteria();
    $c->add(SponsorCodePeer::FK_FILM_ID,$this -> postVar("fk_film_id"));
    if ($this -> postVar("sponsor_notice_sent") >= 0) {
      $c->add(SponsorCodePeer::SPONSOR_CODE_USER_NOTIFIED,$this -> postVar("sponsor_notice_sent"));
    }
    if ($this -> postVar("sponsor_code_use") >= 0) {
      $c->add(SponsorCodePeer::SPONSOR_CODE_USE_COUNT,$this -> postVar("sponsor_code_use"));
    }
    $theusers = SponsorCodePeer::doSelect($c);
    
    $this -> setGetVar("op",$this -> postVar("fk_film_id"));
    $film = $this->dataMap(sfConfig::get('sf_lib_dir')."/widgets/FilmView/query/Film_list_datamap.xml");
     
    $i=0;
     
    foreach ($theusers as $auser) {
      
      if ($auser -> getSponsorCodeUserEmail() != '') {
        $i++;
        $mail_view = new sfPartialView($this->context, 'widgets', 'ticket_email', 'ticket_email' );
        $mail_view->getAttributeHolder()->add(array("film"=>$film["data"][0], "code"=>$auser->getSponsorCode()));
        $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/Ticket_".$film["data"][0]["film_short_name"]."_html.template.php";
        $mail_view->setTemplate($templateloc);
        $message = $mail_view->render();
        $altbody = "";
        
        //$recips[0]["email"] = "amadsen@gmail.com";
        $recips[0]["email"] = $auser -> getSponsorCodeUserEmail();
        $recips[0]["fname"] = $auser -> getSponsorCodeUserFname();
        $recips[0]["lname"] = $auser -> getSponsorCodeUserLname();
        $subject = "Your ticket to ".$film["data"][0]["film_name"];
        $mail = new WTVRMail( $this -> context );
        $mail -> user_session_id = "user_id";
        $mail -> queueMessage("blank",$subject,$message,$altbody,sfConfig::get("app_mail_from_address"),sfConfig::get("app_mail_from_fname"),sfConfig::get("app_mail_from_lname"),$recips);
        $auser -> setSponsorCodeUserNotified( $auser -> getSponsorCodeUserNotified() + 1 );
        $auser -> save();
      }
    }
    //Return this back to it's maker
    $this -> setGetVar("op","send");
    $this -> XMLForm ->item["done"] = "Sent ".$i." notifications";
  }
  
  function userSearch( $query=false ) {
    
    //$this -> showData();
    //$this -> showXML();  
  
    $util = new SponsorUserAdminSearch_format_utility( $this -> context );
    
    if (! $query) {
    
      if ($this -> greedyVar("user_start_date")) {
        $util -> startdate = formatDate($this -> greedyVar("user_start_date"),"TS");
      }
      if ($this -> greedyVar("user_end_date")) {
        $util -> enddate = formatDate($this -> greedyVar("user_end_date"),"TS");
      }
      if (($this -> greedyVar("user_term"))) {
        $util -> term = $this -> greedyVar("user_term");
      }
      if ($this -> greedyVar("fk_film_id") > 0) {
        $util -> film = $this -> greedyVar("fk_film_id");
      }
      
      if (is_numeric($term) && ($term < 1000000)) {
        $num = $util -> term;
      } else {
        $num = 0;
      }
      
      sfConfig::set("search",$util -> term);
      sfConfig::set("num",$num);
      
    }
    //$this -> showData();
    return $this -> returnList( "SponsorUserAdmin_search_datamap.xml", true, true, "standard", $util );
  }
}

  ?>
