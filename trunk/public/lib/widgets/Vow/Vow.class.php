<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/ImageHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Vow_crud.php';
  
   class Vow_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	  $this -> XMLForm -> item_forcehidden = true;
    $this -> screening_start_time = "2012-02-09 08:00:00 EST";
		$this -> qa_length = 45;
    $this -> qa_timeout = 120;
    // $this -> crud = new VowCrud( $context );
    parent::__construct( $context );
  }

	function parse() {
  
    if ($this -> as_service) {
      switch ($this -> getVar("id")) {
        case "init":
          //<li class="clearfix"><img src="/images/vow/temp/<?php echo getImageDir( $vow -> getVowAssetGuid() );/asset-<?php echo $vow -> getFkUserId();-echo $vow -> getVowAssetGuid();.jpg" /><div>echo substr($vow -> getVowDescription(),0,30);  
          $util = new Vow_format_utility( $this -> context );
          $this -> vows = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Vow/query/Vow_list_datamap.xml",true,"array",$util);
          echo json_encode($this -> vows);
          die();
          break;
        case "flag":
        	break;
      }
    }
        $this -> setTemplate("VowPost");

	 $this -> widget_vars["facebook"] = $this -> sessionVar("user_facebook");
	 $this -> widget_vars["email"] = $this -> sessionVar("user_email");
	 $this -> widget_vars["username"] = $this -> sessionVar("user_username");
	 $this -> widget_vars["user_id"] = $this -> sessionVar("user_id"); 
	 $this -> widget_vars["hasTicket"] = false; 
		
   $this -> setgetVar("op",93);
	 $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/FilmMarquee/query/Film_list_datamap.xml");
	 $list["data"][0]["film_start_date"] = formatDate($this -> screening_start_time,"prettyshort");
   $list["data"][0]["film_start_time"] = strtotime($this -> screening_start_time);
   $list["data"][0]["film_end_time"] = $this -> widget_vars["film_end_time"] = strtotime($this -> screening_start_time) + ($this -> qa_length * 60);
   $list["data"][0]["film_block_time"] = $this -> widget_vars["film_end_time"] + ($this -> qa_timeout * 60);
   
   $this -> widget_vars["film"] = $list["data"][0];
	 /*
	 if ($this -> sessionVar("user_id") > 0) {
		 $sql = "select audience_id 
						from audience 
						inner join screening
						on screening_id = fk_screening_id
						where fk_user_id = ".$this -> sessionVar("user_id")." 
						and fk_screening_unique_key = 'thevowevent'";
		 $ticketObj = $this -> propelQuery($sql);
		 $ticket = $ticketObj -> fetchAll();
		 if (count($ticket) > 0) {
				$this -> widget_vars["hasTicket"] = true;
				if (preg_match("/login/",$_SERVER["REQUEST_URI"])) {
				 	$this -> redirect('/theater/thevowevent');
				 	die();
				}
		 } 
	 }
	 */
	 
	 $sql=	"select count(audience_id)
					from audience
					inner join screening
					on audience.fk_screening_id = screening.screening_id
					where fk_film_id = 93
					and screening_date > '2012-02-09';";
		$usersObj = $this -> propelQuery($sql);
		$users = $usersObj -> fetchAll();
		if (count($users) > 0) {
		 	$this -> widget_vars["screening_views"] = $users[0][0] + 1067;
		}
	 
	 $sql = "select screening_chat_qanda_started from screening where screening_id = 8100";
   $res = $this -> propelQuery($sql);
   $val = $res -> fetchall();
   
   if($val[0][0] == 1) {
			$this -> widget_vars["screening_qanda_status"] = "running";
   } elseif ($val[0][0] == -1) {
			$this -> widget_vars["screening_qanda_status"] = "closed";
   } else {
			$this -> widget_vars["screening_qanda_status"] = "none";
	 }
	 
	 if ($list["data"][0]["film_block_time"] < time()) {
	   $this -> widget_vars["screening_qanda_status"] = "closed";
	 }
	 
	 //http://developers.facebook.com/tools/debug
   $this -> widget_vars["title"] = "Constellation | Your Online Movie Theater | 'An Evening of Vows'";
   $this -> setMeta( "og:title", "'An Evening of Vows' on Constellation.tv" );
   $this -> setMeta( "og:type", "Movie" );
   $this -> setMeta( "og:url", "http://".$_SERVER["SERVER_NAME"]."/thevow" );
   $this -> setMeta( "og:image", "http://s3.amazonaws.com/cdn.constellation.tv/prod/images/pages/thevow/channing-tatum-film-profile.png" );
   $this -> setMeta( "og:site_name", "'An Evening of Vows' on Constellation.tv" );
   $this -> setMeta( "og:description", "The Vow star Channing Tatum will host a live interactive online event that may include your story of your vow. Channing will be live online via web-cam presenting an online movie screening of fan-submitted \"Vows\" along with with special clips from the movie. Tickets to this online event are free. The Vow is only in movie theaters, beginning 2/10/12." );
	 
	 return $this -> widget_vars;
   
	  if ($this -> XMLForm -> isPosted()) {  
      $this -> doPost();
    }
    $this -> doGet();
    


    return $this -> drawPage();
    
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
