<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/LoginHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/UserHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/CryptHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/AdminHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/user_crud.php';
  
  class UserAdmin_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
   
    parent::__construct( $context );
     if ($this ->getOp() == "address") {
      $this -> XMLForm = new XMLForm($this -> widget_name,"useraddressconf.php");
      $this -> crud = new UserAddressCrud( $context, $this -> getVar("rev") );
    } else {
      $this -> XMLForm = new XMLForm($this -> widget_name);
  	  $this -> crud = new UserCrud( $context );
    }
    $this -> XMLForm -> item_forcehidden = true;
    
  }

	function parse() {
	 
	 if ($this -> as_service) {
    $audience = AudiencePeer::retrieveByPk($this -> getVar("id"));
    if (($audience) && ($audience -> getAudienceId() > 0)) {
      $audience -> setAudienceStatus( 0 );
      $audience -> setAudienceHmacKey( null );
      $audience -> setAudienceIpAddr( null );
      $audience -> save();
      $result = true;
      $title = "Success";
      $message = "The Seat Was Reset";
    } else {
      $result = false;
      $title = "Failure";
      $message = "The Seat Wasn't Found";
    }
    $resetResponse = array("result"=>$result,"title"=>$title,"message"=>$message,"id"=>$this -> getVar("id"));
    print json_encode($resetResponse);
    die();
    
   }
    $this -> XMLForm -> registerArray("timezones",shortZoneList());
    
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
          if (($this -> getOp() == "address") && ($this -> getVar("rev") > 0)) {
            $this -> crud -> setUserUpdatedAt( now() );
            
            $this -> crud -> write();
            
            //Update the SOLR Search Engine
            $solr = new SolrManager_PageWidget(null, null, $this -> context);
            $solr -> execute("add","user",$this -> crud -> UserAddress -> getFkUserId());
            
          } else {
            $this -> crud -> write();
            if (is_null($this -> crud -> getUserCreatedAt())) {
              $this -> crud -> setUserCreatedAt( now() );
            }
            
            if ($this -> getVar("FILE_user_image_guid")) {
							$go = explode("/",$this -> getVar("FILE_user_image_guid"));
							$guid = end($go);
							$this -> crud -> setUserPhotoUrl( $guid.".jpg" );
						}
            $user_ual = serialize($this -> postVar("user_ual_array"));
            $this -> crud -> User -> setUserPassword(encrypt($this -> postVar("user_password")));
            $this -> crud -> User -> setUserUal($user_ual);
            $this -> crud -> User -> save();
            
            //Update the SOLR Search Engine
            $solr = new SolrManager_PageWidget(null, null, $this -> context);
            $solr -> execute("add","user",$this -> crud -> User -> getUserId());
          	
	          try {
	            //Update all screenings this user is hosting, too
			        $sql = "select screening_id from screening where fk_host_id = ".$this -> crud -> User -> getUserId();
			        $res = $this -> propelQuery($sql);
			        while ($row = $res -> fetch()) {
			          $solr = new SolrManager_PageWidget(null, null, $this -> context);
			          $solr -> execute("add","screening",$row[0]);
			        }
			        
	          } catch ( Exception $e ) {
	          }
          }
          
          
          break;
          case "delete":
          //Update the SOLR Search Engine
          $solr = new SolrManager_PageWidget(null, null, $this -> context);
          $solr -> execute("delete","user",$this -> crud -> User -> getUserId());
          
          $this -> crud -> remove();
          break;
        }
      }
    
  }

  function doGet(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      $this -> pushItem();
      //dump($this -> XMLForm -> ObjXMLDoc -> saveXML());
      $vals = unserialize($this -> crud -> getUserUal());
      $this -> XMLForm -> item["user_ual_array[]"] = $vals;
      $this -> XMLForm -> item["user_password"] = decrypt($this -> XMLForm -> item["user_password"]);
      
      $this -> XMLForm -> item["user_payments"] = $this -> returnList("PurchaseUserAdmin_list_datamap.xml");
      $this -> XMLForm -> item["user_screenings"] = $this -> returnList("ScreeningUserAdmin_list_datamap.xml");
      $this -> XMLForm -> item["user_subscriptions"] = $this -> returnList("SubscriptionUserAdmin_list_datamap.xml");
      $this -> XMLForm -> item["user_photo_url"] = '/uploads/hosts/'.$this -> XMLForm -> item["user_id"].'/'.$this -> XMLForm -> item["user_photo_url"];
      //$this -> showXML();
       
    } else if ($this ->getOp() == "address") {
      $this -> pushItem( $this -> getVar("rev") );
      
    }
  }

  function drawPage(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      return $this -> returnForm();
    } elseif ($this ->getOp() == "search" ) {
      return $this -> userSearch();
    } elseif ($this ->getOp() == "list" ) {
    	return $this -> returnList();
    } elseif ($this -> getOp() == "address") {
      return $this -> returnForm();
    }
    
  }
  
  function searchUser($phrase) {
    sfConfig::set("search_phrase",$phrase);
    //$this -> showData();
    $util = new UserAdmin_format_utility( $this -> context );
    return $this -> returnList( sfConfig::get('sf_lib_dir')."/widgets/UserAdmin/query/UserAdmin_search_datamap.xml", false, true, "standard", $util );
  
  }
  
  function convertPassword() {
    $c = new Criteria();
    $c -> add(UserPeer::PCONVERT,0);
    //$c -> setLimit(10);
    $users = UserPeer::doSelect($c);
    foreach($users as $user) {
      $newpass= convertPassword($user->getUserPassword());
      $user -> setUserPassword($newpass);
      $user -> setPconvert(1);
      $user -> save();
    }
    
  }
  
  function encryptPassword() {
      $c = new Criteria();
      $c -> add(UserPeer::PCONVERT,1);
      //$c -> setLimit(10);
      $users = UserPeer::doSelect($c);
      foreach($users as $user) {
        $newpass= encrypt($user->getUserPassword());
        $user -> setUserPassword($newpass);
        $user -> setPconvert(2);
        $user -> save();
      }
      
  }
  
  function userSearch( $query=false ) {
    
    //$this -> showData();
    //$this -> showXML();  
  
    $util = new UserAdminSearch_format_utility( $this -> context );
    
    if (! $query) {
    	if (($this -> greedyVar("user_start_date")) && (! $this -> greedyVar("user_end_date"))) {
        //$end = formatDate($this -> postVar("cms_object_end_date"),"W3XML");
        $util -> date = "'".formatDate($this -> greedyVar("user_start_date"),"TS")."|".formatDate(null,"TS")."'";
      }
      if ((! $this -> greedyVar("user_start_date")) && ($this -> greedyVar("user_end_date"))) {
        //$start = formatDate($this -> postVar("cms_object_start_date"),"W3XML");
        $util -> date = "1970-01-01 00:00:00|".formatDate($this -> greedyVar("user_end_date"),"TS");
      }
      if (($this -> greedyVar("user_start_date")) && ($this -> greedyVar("user_end_date"))) {
        $util -> date = formatDate($this -> greedyVar("user_start_date"),"TS")."|".formatDate($this -> greedyVar("user_end_date"),"TS");
      }
      if (($this -> greedyVar("user_term"))) {
        $term = strtolower($this -> greedyVar("user_term"));
      }
      
      //echo $term; exit;
      $search = ($term != "") ? $term : "[ * TO * ]";
      
      if (is_numeric($term) && ($term < 1000000)) {
        $num = $term;
      } else {
        $num = 0;
      }
      sfConfig::set("email",$term);
      sfConfig::set("email_user",$term."%");
      sfConfig::set("email_domain","%".$term);
      sfConfig::set("search",$term);
      sfConfig::set("num",$num);
      
    }
    //$this -> showData();
    return $this -> returnList( "UserAdmin_search_datamap.xml", true, true, "standard", $util );
    
  }

}

  ?>
