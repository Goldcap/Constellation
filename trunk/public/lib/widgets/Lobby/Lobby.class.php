<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  include_once(sfConfig::get('sf_lib_dir')."/helper/OrderHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/UserHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/LoginHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/ServiceHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Lobby_crud.php';
  
   class Lobby_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> security = new TheaterSecurity_PageWidget( $context );
    
    parent::__construct( $context );
  }

	function parse() {
	 if ($this -> as_service) {
    switch($this -> getVar("id")) {
      case "surrender":
        $this -> security -> getScreeningTicket( $this -> getVar("room"), $this -> getVar("seat") );
        //If the person surrendering actually owns the seat, let it happen;
        if($this -> security -> getSeatUser() == $this -> security -> user -> getAttribute("user_id")) {
          $this -> security -> surrenderSeat();
          $response = new stdClass();
          $response -> response = array(
            'result'=>'success',
            'pair'=>$this -> getVar("pair"),
            'author'=>$this -> getVar("author"),
            'to'=>$this -> getVar("to"),
            'cursor'=>$this -> getVar("cursor")
          );
          print json_encode($response);
          die();
        }
      break;
      case "leave":
        $this -> security -> getScreeningTicket( $this -> getVar("room"), $this -> getVar("seat") );
        //If the person surrendering actually owns the seat, let it happen;
        if($this -> security -> getSeatUser() == $this -> security -> user -> getAttribute("user_id")) {
          $this -> security -> surrenderSeat();
          $this -> redirect("/film/".$this -> getVar("film"));
          //$this -> redirect("/lobby/".$this -> getVar("room")."/".$this -> getVar("seat")."/surrender");
          die();
        } else {
          $this -> redirect("/");
          die();
        }
      break;
      case "kill":
        $this -> security -> getScreeningSeat( $this -> getVar("room"), $this -> getVar("to") );
        //If the person surrendering actually owns the seat, let it happen;
        if ($this -> security -> user -> hasCredential(2)) {
          $this -> security -> killSeat();
          $response = new stdClass();
          $response -> response = array(
            'result'=>'success',
            'pair'=>$this -> getVar("pair"),
            'author'=>$this -> getVar("author"),
            'to'=>$this -> getVar("to"),
            'cursor'=>$this -> getVar("cursor")
          );
          print json_encode($response);
          die();
        }
      break;
      case "capture":
        $this -> security -> getScreeningTicket( $this -> getVar("room"), $this -> getVar("seat") );
        //If the person surrendering actually owns the seat, let it happen;
        if($this -> security -> getSeatOwner( $this -> getVar("room") ) == $this -> security -> user -> getAttribute("user_id")) {
          $this -> security -> captureSeat();
          $response = new stdClass();
          $response -> response = array(
            'result'=>'success',
            'pair'=>$this -> getVar("pair"),
            'author'=>$this -> getVar("author"),
            'to'=>$this -> getVar("to"),
            'cursor'=>$this -> getVar("cursor")
          );
          print json_encode($response);
          die();
        }
      break;
    }
   $response -> response = array(
          'result'=>'failure',
          'pair'=>$this -> getVar("pair"),
          'to'=>$this -> getVar("to"),
          'cursor'=>$this -> getVar("cursor")
        );
    print json_encode($response);
    die();
   }
	 $film = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Lobby/query/Screening_list_datamap.xml");
   $this -> widget_vars["film"] = $film["data"][0];
   
   if ($this -> getUser() -> isAuthenticated()) {
    $this -> widget_vars["userid"] = $this -> sessionVar("user_id");
   } else {
    setRandomUser( $this -> getUser() );
   }
   
   $this -> security -> getScreeningTicket( $film["data"][0]["screening_unique_key"] );
   $this -> security -> checkPreUser( $film["data"][0]["screening_film_id"] );
   $this -> widget_vars["seat"] = $this -> security -> seat;
   $this -> widget_vars["pair"] = $this -> security -> getSeatPair();
   $this -> widget_vars["owner"] = $this -> security -> getSeatOwner();
   $this -> widget_vars["user"] = $this -> security -> getSeatUser();
   $this -> widget_vars["surrender"] = $this -> getVar("surrender");
   $this -> widget_vars["sponsor"] = $film["data"][0]["screening_film_sponsor_id"];
  //dump($this -> widget_vars["pair"]);   
   $chat = new TheaterChat( $this -> widget_vars, $this -> page_vars, $this -> context );
   $this -> widget_vars = $chat -> getChatInstance();
  
	 return $this -> widget_vars;
   
  }

	}

?>
