<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/LoginHelper.php"); 
  include_once(sfConfig::get('sf_lib_dir')."/helper/ImageHelper.php");
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Profile_crud.php';
  
   class Profile_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
	 
	 if ($this -> getVar("op") == 0) {
	  	redirect("/");
	 }
	 
	 if (! is_numeric($this -> getVar("op"))) {
	 	 $users = getUserByName($this -> getVar("op"));
	   if (count($users) > 1) {
		 	 $this -> widget_vars["users"] = $users;
			 $this -> setTemplate("ProfileDisambiguation");
		 } elseif (count($users) == 1) {
		 	$this ->  getFollowers();
		 	$this -> getUpcomingScreenings();
		 	//$this -> getConversationCount();
		 	$this -> isFollowing();
		 	$this -> widget_vars["user"] = $users[0];
		 } else {
		 	redirect("/");
		 }
	 } else {
	 	 $this ->  getFollowers();         
		 $this -> getUpcomingScreenings();
		 //$this -> getConversationCount();
		 $this -> isFollowing();
		 $this -> widget_vars["user"] = getUserById($this -> getVar("op"));
	 }
	 return $this -> widget_vars;
   
  }

  function getConversationCount() {
  		$sql = "select count(conversation_id) from conversation where fk_author_id = ".$this -> getVar("op");
  		$res = $this -> propelQuery($sql);
  		$cntObj = $res -> fetchall();
  		$this -> widget_vars["conversation_count"] = $cntObj[0][0];
  		
  }

	function getFollowers() {
		$util = new Profile_format_utility( $this -> context );
		$util -> session_user = $this -> sessionVar("user_id");
		$this -> widget_vars["followed"] = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Profile/query/Followed_list_datamap.xml",true,"array",$util);
   		$this -> widget_vars["following"] = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Profile/query/Following_list_datamap.xml",true,"array",$util);
    
	}
	
	function getUpcomingScreenings() {

	    $util = new Account_format_utility( $this -> context );
	    sfConfig::set("user_id",$this -> getVar("op"));

	    sfConfig::set("startdate","[".formatDate(now(),"W3XMLIN")." TO * ]"); 
		$this -> widget_vars["purchases"] = $this -> dataMap( sfConfig::get("sf_lib_dir")."/widgets/Profile/query/ScreeningUser_list_datamap.xml", true, "array", $util );
			
		sfConfig::set("startdate","[ * TO ".formatDate(now(),"W3XMLIN")."]"); 
	    $this -> widget_vars["history"] = $this -> dataMap( sfConfig::get("sf_lib_dir")."/widgets/Profile/query/ScreeningUserPrevious_list_datamap.xml", true, "array", $util );
			                                                                                        
	}
	
	function isFollowing() {
		$this -> widget_vars["allow_fallow"] = "yes";
		
    if (! $this -> sessionVar("user_id")) {
      return "no_login";  
    }
    
		if ($this -> sessionVar("user_id") == $this -> getVar("op")) {
			$this -> widget_vars["allow_fallow"] = "self";	
			return;
		}
		
		if (count($this -> widget_vars["followed"]["data"]) > 0) {
			foreach	($this -> widget_vars["followed"]["data"] as $follower) {
				if (($follower["fk_follower_id"] == $this -> sessionVar("user_id")) && ($follower["fk_followed_id"] == $this -> getVar("op"))) {
					$this -> widget_vars["allow_fallow"] = "no";
				}
			}
		}
	}
}

  ?>