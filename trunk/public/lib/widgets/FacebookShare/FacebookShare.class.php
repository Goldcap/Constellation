<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/ImageHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Facebook_crud.php';
  
  class FacebookShare_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	var $facebook;
	var $session;
	var $destination;
	var $film;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
    $this -> XMLForm -> item_forcehidden = true;
      
    parent::__construct( $context );
    
    $this -> curl = new Curl();
  }

	function parse() {
	    
      $app_id = sfConfig::get("app_facebook_app_id");
      $app_secret = sfConfig::get("app_facebook_secret");
      //$my_url = "http://".sfConfig::get("app_domain").$_SERVER["REQUEST_URI"];
      $my_url = "http://".sfConfig::get("app_domain")."/facebook/".$this -> getVar("op");

      
      if ($this -> cookieVar("fbt") == '') {
        $this -> code = $this -> getVar("code");
    
        if(! $this -> code) {
            $dialog_url = "http://www.facebook.com/dialog/oauth?client_id=" 
                . $app_id . "&redirect_uri=" . urlencode($my_url) . "&scope=publish_stream";
    
            header("Location: ". $dialog_url);
            die();
        }
      }
      //dump($program["data"][0]["program_hostbyrequest_price"]);
      $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Event/query/Event_datamap.xml");
      $this -> film = $list["data"][0];
      if (is_null($this -> film["screening_id"])) {
        return false;
      }
      
      if ($this -> XMLForm -> isPosted()) {
        $this -> doPost();
      }
      
      if ($this -> cookieVar("fbt") == '') {
        $token_url = "https://graph.facebook.com/oauth/access_token?client_id="
            . $app_id . "&redirect_uri=" . urlencode($my_url) . "&client_secret="
            . $app_secret . "&code=" . $this -> code;
        $this -> response = $this -> curl -> get($token_url);
        $vars = explode("&",$this -> response -> body);
        $args = explode("=",$vars[0]);
        $this -> access_token = $args[1];
        
        $this -> setCookieVar("fbt",$this -> access_token);
        
        
      }
      //dump($this -> access_token);
      //$graph_url = "https://graph.facebook.com/me?" . $access_token;
      //$this -> fbuser = json_decode($this -> curl -> get($graph_url));
      
      $this -> doGet();
      
      return $this -> returnForm();
      
      
  }

  function doPost(){
     
     if ($this -> XMLForm -> validateForm()) {
        switch ($this -> getFormMethod()) {
          case "submit":
          
          if ($this -> postVar("message")) {
            $message = $this -> postVar("message");
          } else {
            $message = "I am attending '". ( $this -> film["screening_name"] != '' ? $this -> film["screening_name"] :$this -> film["screening_film_name"] )."";
          }
          
          if ($this -> film["screening_name"] != '') {
            $name = "'".$this -> film["film_name"]."' at Constellation - Your Online Movie Theater";
          } elseif($this -> film["screening_film_name"] != ''){
          } else {
            $name = "Constellation.tv - online movie events";
          }
          
          if ($this -> film["screening_description"] != '') {
            $description = $this -> film["screening_description"];
          } else {
            $description = "Constellation - Your Online Movie Theater";
          }
          $link = "http://".sfConfig::get("app_domain")."/film/".$this -> getVar("op");
          
          //http://www.constellation.tv/uploads/screeningResources/56/logo/small_poster5068aae0449c8e00ec9a6919a4c75b30.jpg
          if ($this -> film["film_logo"] != '') {
            $picture = "http://www.constellation.tv/uploads/screeningResources/".$this -> film["film_id"]."/logo/small_poster".$this -> film["film_logo"];
          } else {
            $picture = "http://s3.amazonaws.com/cdn.constellation.tv/prod/images/constellation_host.jpg";
          }
          $preview = '<table ><tr><td><table>'.$message.'</td></tr><tr><td colspan="2"><img src="https://s3.amazonaws.com/cdn.constellation.tv/prod/images/spacer.gif" height="4" /></td></tr><tr><td valign="top"><a href="'.$link.'"><img src="'.str_replace("http:","https:",$picture).'" border="0" width="90" /></a></td><td valign="top" style="padding-left: 10px;"><a href="'.$link.'"><strong>'.$name.'</strong></a><br /><span style="color: #bbb">'.urldecode($link).'</span><br /><br /><span style="color: #bbb">'.$description.'</span></td></tr></table>';
        	
        	$facebook = new FacebookAPI(array(
			    'appId'  => sfConfig::get("app_facebook_app_id"),
			    'secret' => sfConfig::get("app_facebook_secret"),
			    'cookie' => true,
			    ));
			    
			  	$attachment =  array(   'access_token'  => $this -> postVar("token"),                        
							                    'name'          => $name,
							                    'link'          => $link,
							                    'description'   => $description,
							                    'message' => $message,
																	'picture' => $picture,
							                );
					$this -> XMLForm -> item["result"] = "Failure";
					try {
						$res = $facebook -> api('/'.$id.'/feed','post',$attachment);
						if($res["id"]) {
		          $this -> XMLForm -> item["result"] = "Success";
						}
					} catch ( Exception $e ) {}
					
		      $this -> XMLForm -> item["preview"] = $preview;
          break;
          case "delete":
          $this -> crud -> remove();
          break;
        }
      }
    
  }

  function doGet(){
    
    $this -> XMLForm -> item["message"] = "I am attending '". ($this -> film["screening_name"] != '' ? $this -> film["screening_name"] : $this -> film["screening_film_name"] )."";
    $this -> XMLForm -> item["token"] = $this -> access_token;
    if (preg_match("/OAuthException/",$this -> access_token)) {
      $this -> XMLForm -> addError("auth","There was an error with your login, please <a href=\"/facebook\">click here</a> and try again.");
    }
    
  }

}?>
