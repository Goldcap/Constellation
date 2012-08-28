<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/CryptHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/ImageHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/UserHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/MigrationHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/SolrHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/OrderHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Account_crud.php';
  
   class Account_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	var $user;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	  $this -> XMLForm -> item_forcehidden = true;
	  $this -> crud = new UserCrud( $context );
	  $this -> user = $context -> getUser();
    parent::__construct( $context );
    
    if ($this -> getVar("rev") > 0 ) {
      //This adds a JS in the template for address state modification
      $this -> widget_vars["doadd"] = true;
    } else {
      //This adds a JS in the template for address state modification
      $this -> widget_vars["doadd"] = false;
    }
    
		$this -> widget_vars["zones"] = zoneList(); 

  }

	function parse() {
	
	 if ($this -> as_service) {
	    switch ($this -> getVar("id")) {
				case "modify":
					if (! $this -> getUser() -> isAuthenticated()) {
						die();
					}
					$user = new UserCrud( $this -> context, $this -> sessionVar("user_id"));
					$field = $this -> postVar("field");
					$value = $this -> postVar("value");
					$function = "set" . str_replace(" ","",ucwords(str_replace("_"," ",$field)));
					eval("\$user -> ".$function."('".$value."');");
					$user -> save();
					
					$res = new stdClass;
			    $res -> accountResponse = 
			                        array("status"=>"success",
			                              "result"=>"updated",
			                              "field"=>$field,
			                              "value"=> $value);
			    print(json_encode($res));
			    die();
				default:
					$this -> doPost();
					break;
			}
				
	 }
	 
	 switch ($this -> getVar("op")) {
      
      case "upload":
      	
        $c =  new Criteria();
        $c -> addAscendingOrderByColumn(GenrePeer::GENRE_NAME);
				$this -> widget_vars["genre"] = GenrePeer::doSelect($c);
				if ($this -> getVar("id")) {
					$film = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Account/query/Film_list_datamap.xml");
					$this -> widget_vars["post"] = $film["data"][0];
				}
				$this -> setTemplate("AccountUpload");
        $this -> addJs('/js/CTV.Uploader.js');
        $this -> addJs('/js/CTV.AddTemplate.js');
        $this -> addJs('/js/CTV.UploadController.js');
        return $this -> widget_vars;
        break;

      case "films":
        $this -> setTemplate("AccountFilms");
        return $this -> widget_vars;
        break;        
      case "credit":
        $this -> setTemplate("AccountCredit");
        return $this -> widget_vars;
        break;       
      case "report":
        $this -> addJs('/js/excanvas.min.js');
        $this -> addJs('/js/jquery.flot.min.js');
        $this -> addJs('/js/Moment.js');

        $this -> setTemplate("AccountReport");
        return $this -> widget_vars;
        break;  
      case "showtimes":
      	$this -> widget_vars["type"] = $this -> getVar("id");
        $this -> setTemplate("AccountShowtimes");
        $util = new Account_format_utility( $this -> context );
        sfConfig::set("startdate","[".formatDate(now(),"W3XMLIN")." TO * ]");
   			if ($this -> getVar("id") != "hosting") {
        	$this -> widget_vars["purchases"] = $this -> dataMap( sfConfig::get("sf_lib_dir")."/widgets/Account/query/ScreeningUser_list_datamap.xml", true, "array", $util );
				}
				if ($this -> getVar("id") != "attending") {
					//$this -> showXML();
					$this -> widget_vars["hosting"] = $this -> dataMap( sfConfig::get("sf_lib_dir")."/widgets/Account/query/ScreeningHost_list_datamap.xml", true, "array", $util );
        }
				return $this -> widget_vars;
				break;
      case "purchase":
      	$this -> setTemplate("AccountPurchase");
        $this -> widget_vars["purchases"] = $this -> dataMap( sfConfig::get("sf_lib_dir")."/widgets/Account/query/PurchaseUser_list_datamap.xml" );
        //$this -> showXML();
        return $this -> widget_vars;
        break;
      case "subscriptions":
        //$this -> crud = new UserAddressCrud( $this -> context );
        
        if ($this -> getVar("rev") > 0 ) {
          $this -> XMLForm = new XMLForm($this -> widget_name,"formconfshowtimes.php");
        }
        
        //$this -> showXML();
        return $this -> returnList( "SubscriptionUser_list_datamap.xml" );
        break;
      case "password":
        $this -> XMLForm = new XMLForm($this -> widget_name,"formconfpassword.php");
        break;
      case "address":
        $this -> crud = new UserAddressCrud( $this -> context );
        
        if ($this -> getVar("rev") > 0 ) {
          $this -> XMLForm = new XMLForm($this -> widget_name,"formconfaddress.php");
        } else {
          //return $this -> returnList( "User_Address_list_datamap.xml", true, true );
          
          $addresses = getUserAddresses();
          $keys = array();
          if (count($addresses > 0)) {
          foreach ($addresses as $addr) {
            $key = str_replace(" ","",strtolower($addr["Address"].$addr["Address2"].$addr["City"].$addr["State"].$addr["Zip"]));
            if (! in_array($key,$keys)) {
              $this -> widget_vars["addresses"][] = $addr;
              $keys[] = $key;
            }
          }}
          return $this -> widget_vars;
        }
        
        break;
      default:
        break;
    }
    
	  if ($_SERVER["REQUEST_METHOD"] == "POST") {
	    $this -> doPost();
    }
    
    $this -> doGet();
    
    return $this -> widget_vars;
    
  }
  
  function doPost(){
      switch ($this -> postVar("styroname")) {
			  case "accountFilm":
				$film = new FilmCrud( $this -> context, $this -> postVar("film_id") );

				if (  (int) $film -> getFilmId() > 0 ) {
					//dump($_POST);

					$c = new Criteria();
					$ct1 = $c->getNewCriterion(FilmPeer::FILM_NAME,$this -> postVar("film_name"));
				  $ct2 = $c->getNewCriterion(FilmPeer::FILM_SHORT_NAME,$this -> postVar("film_short_name"));
				  $c -> addOr($ct1);
				  if (FilmPeer::doCount($c) > 0) {
					  //$this -> outputStatus( "failure", "Film name or short name exists.", "A film with this name (or short name) already exists, please try another.", null );
					}
					$film -> setFkUserId($this -> sessionVar("user_id"));
					$film -> write();
				
					$sql = "delete from film_info where fk_film_id=".$film -> getFilmId();
          $this -> propelQuery($sql);
          
          $this -> addInfo("directors",1,$film -> getFilmId());
          $this -> addInfo("producers",2,$film -> getFilmId());
          $this -> addInfo("actors",3,$film -> getFilmId());
          $this -> addInfo("writers",4,$film -> getFilmId());
          /*
          $this -> addInfo("executive_producers",5,$film -> getFilmId());
          $this -> addInfo("director_of_photography",6,$film -> getFilmId());
          $this -> addInfo("music",7,$film -> getFilmId());
          $this -> addInfo("co-producers",8,$film -> getFilmId());
          $this -> addInfo("co-executive_producers",9,$film -> getFilmId());
          $this -> addInfo("associate_producers",10,$film -> getFilmId());
          $this -> addInfo("supported",11,$film -> getFilmId());
          $this -> addInfo("link",12,$film -> getFilmId());
          */
          
          $sql = "delete from film_genre where fk_film_id = " . $film->getFilmId();
          $this -> propelQuery($sql);
          if ($this -> postVar("film_genre")) {
            foreach($this -> postVar("film_genre") as $genre) {
              //$vars = array("fk_film_id"=>$this->crud->Film->getFilmId(),"fk_genre_id"=>$genre);
              //$filmgenre -> checkUnique($vars);
              $filmgenre = new FilmGenreCrud($this -> context);
              $filmgenre -> setFkFilmId($film->getFilmId());
              $filmgenre -> setFkGenreId($genre);
              $filmgenre -> save();
            }
          }
           
					if ((int) $this -> postVar("film_id") == 0) {
            
						$file = new WTVRFile();
            $id = $film -> getFilmId();
            if ($this -> postVar("poster_image_guid")) {
              $sourcr = explode("/",$this -> postVar("poster_image_guid"));
              $filename = array_pop($sourcr);
              $sourcer = implode("/",$sourcr);
              $source = sfConfig::get("sf_web_dir")."/uploads/screeningResources/".$sourcer;
              $dest = sfConfig::get("sf_web_dir")."/uploads/screeningResources/".$id."/logo";
              createDirectory($dest);
              try {
							$file -> moveFile($source."/".$this -> postVar("poster_image_filename"),$dest."/".$this -> postVar("poster_image_filename"));
              $file -> moveFile($source."/".$filename.".jpg",$dest."/".$filename.".jpg");
              $film -> setFilmLogo( str_replace("screening_poster","",$filename) . ".jpg" );
							$newfilename = str_replace("screening_poster","small_poster",$filename);
							$file -> moveFile($source."/".$newfilename.".jpg",$dest."/".$newfilename.".jpg");
              $newfilename = str_replace("screening_poster","purchase_email_poster",$filename); 
              $file -> moveFile($source."/".$newfilename.".jpg",$dest."/".$newfilename.".jpg");
              $newfilename = str_replace("screening_poster","poster",$filename); 
              $file -> moveFile($source."/".$newfilename.".jpg",$dest."/".$newfilename.".jpg");
              $newfilename = str_replace("screening_poster","thumb_",$filename); 
              $file -> moveFile($source."/".$newfilename.".jpg",$dest."/".$newfilename.".jpg"); 
              $newfilename = str_replace("screening_poster","invite_email_poster",$filename); 
              $file -> moveFile($source."/".$newfilename.".jpg",$dest."/".$newfilename.".jpg"); 
              $newfilename = str_replace("screening_poster","film_logo",$filename); 
              $file -> moveFile($source."/".$newfilename.".jpg",$dest."/".$newfilename.".jpg");
              $newfilename = str_replace("screening_poster","",$filename); 
              $file -> moveFile($source."/".$newfilename.".jpg",$dest."/".$newfilename.".jpg"); 
              } catch (Exception $e) {}
            }
            if ($this -> postVar("splash_image_guid")) {
            
              $sourcr = explode("/",$this -> postVar("splash_image_guid"));
              $filename = array_pop($sourcr);
              array_pop($sourcr);
              $sourcer = implode("/",$sourcr);
              $source = sfConfig::get("sf_web_dir")."/uploads/screeningResources/".$sourcer;
              $dest = sfConfig::get("sf_web_dir")."/uploads/screeningResources/".$id;
              createDirectory($dest);
              try {
							$file -> moveFile($source."/".$this -> postVar("splash_image_filename"),$dest."/".$this -> postVar("splash_image_filename"));
              $file -> moveFile($source."/background/".$filename.".jpg",$dest."/background/".$filename.".jpg");
              $film -> setFilmSplashImage( $filename . ".jpg" );
              } catch (Exception $e) {}
            }
            if ($this -> postVar("trailer_guid")) {
              
              $sourcr = explode("/",$this -> postVar("trailer_guid"));
              $filename = array_pop($sourcr);
              array_pop($sourcr);
              $sourcer = implode("/",$sourcr);
              $source = sfConfig::get("sf_web_dir")."/uploads/screeningResources/".$sourcer;
              $dest = sfConfig::get("sf_web_dir")."/uploads/screeningResources/".$id;
              createDirectory($dest."/trailerFile/");
              if (is_dir($source)) {
                  if ($dh = opendir($dir)) {
                      while (($file = readdir($dh)) !== false) {
                          if ( $file == '.' || $file == '..' )
                            {  continue; }
                          $file -> moveFile($source."/trailerFile/".$file,$dest."/trailerFile/".$file);
                      }
                      closedir($dh);
                  }
              }
              
              $tar = explode("/",$this -> postVar("trailer_guid"));
              $ttar = explode(".",$this -> postVar("trailer_filename"));
              $film -> setFilmTrailerFile( end($tar) . "." . end($ttar) );
              
            }
            
            $film -> setFilmCreatedAt(now());
            $film -> save();
            //Move Images Where they need to go
          } else {
            

            if ($this -> postVar("poster_image_guid")) {
              $lar = explode("/",$this -> postVar("poster_image_guid"));
              $film -> setFilmLogo( str_replace("screening_poster","",end($lar)) . ".jpg" );
            }
            if ($this -> postVar("splash_image_guid")) {

               $sar = explode("/",$this -> postVar("splash_image_guid"));
            echo end($sar); die;

              $film -> setFilmSplashImage( end($sar) . ".jpg" );
            }
            if ($this -> postVar("FILE_film_trailer_file_guid")) {
              $tar = explode("/",$this -> postVar("trailer_guid"));
              $ttar = explode(".",$this -> postVar("trailer_filename"));
              $film -> setFilmTrailerFile( end($tar) . "." . end($ttar) );
            }
            $film -> setFilmUpdatedAt(now());
            $film -> save();
          }
				}
			
				$this -> outputStatus( "success", "Your film was updated.", "Your film was updated.", $film -> getFilmId() );
				break;
				
				case "accountFilmSettings":
				
				$this -> outputStatus( "success", "Your film settings were updated.", "Your film settings were updated.", null );
				break;
				
				case "accountFilmScreenings":
				
				$this -> outputStatus( "success", "Your film screening was updated.", "Your film screening was updated.", null );
				break;
				
				case "accountFilmUpload":
				
				$this -> outputStatus( "success", "Your file was uploaded.", "Your file was uploaded.", null );
				break;
				
				case "account":
          $isValid = true;
					$this -> crud -> hydrate($this -> getUser() -> getAttribute("user_id"));
					// $this -> crud -> write();
				
					if (($this -> postVar("current_password") == decrypt($this -> crud -> User -> getUserPassword())) && ($this -> postVar("new_password")== $this -> postVar("new_password_confirm"))) {
						$this -> crud -> setUserPassword(encrypt($this -> postVar("new_password")));
						// $this -> crud -> save();
					} elseif ($this -> postVar("current_password")) {
            $isValid = false;
						$this -> setflashVar("error","There was an error with your password, please try again.");
					}
					
					if ($this -> postVar("user_email_new")) {
						
			      $c = new Criteria();
			      $c->add(UserPeer::USER_EMAIL,$this -> postVar("user_email_new"));
			      $res = UserPeer::doSelect($c);
			      
			      if ($res) {
							$this -> setflashVar("error","That email is already in use, please try a different address.");
			        // return;
              $isValid = false;
			      } else {
			      	$this -> crud -> setUserEmail($this -> postVar("user_email_new"));
							// $this -> crud -> save();
		          addMailChimpUser($this -> getUser() -> getAttribute("user_id"), $this -> crud -> User -> getUserFname(),$this -> crud -> User -> getUserLname(),$this -> postVar("user_email_new"));
		  			}

					} 
          if ($this -> postVar("tzSelectorPop")){
              $this -> crud -> setUserDefaultTimezone($this -> postVar("tzSelectorPop"));
              $this -> crud -> save();
          } 
          if($isValid){
              $this -> setflashVar("success","Your account settings have been successfully updated");
              $this -> crud -> write();
              $this -> crud -> save();
              setUser( $this -> user, $this -> crud -> User );
              //Update the SOLR Search Engine
              $solr = new SolrManager_PageWidget(null, null, null);
              $solr -> execute("add","user",$this -> crud -> User -> getUserId());
            }
     //      elseif ($this -> postVar("user_email_new")) {
					// 	$this -> setflashVar("error","There was an error with your email address, please try again.");
					// }
					

					
					

				 
    		break;
        // case 'password':
        // $this -> outputStatus( "success", "Your film was updated.", "Your film was updated.", $film -> getFilmId() );
        // break;
        // case 'email':
        // $this -> outputStatus( "success", "Your film was updated.", "Your film was updated.", $film -> getFilmId() );
        // break;
			}
  }

  function doGet(){
  
    switch ($this -> getVar("op")) {
      case "address":
          if ($this -> getVar("rev") > 0) {
            $address = getUserAddress( $this -> getVar("rev") );
            $this -> XMLForm -> item = $address;
            $this -> XMLForm -> item["user_state_select"] = $address["user_state"];
            $this -> XMLForm -> item["user_state_text"] = $address["user_state"];
            
          }
          break;
      default:
        try {
          if ($this -> sessionVar("user_id") > 0) {
            $user = getUserById( $this -> sessionVar("user_id") );
            if ($user) {
              $this -> widget_vars["user_id"] = $this -> sessionVar("user_id");
              $this -> widget_vars["user_email"] = $user -> getUserEmail();
              $this -> widget_vars["user_fb_uid"] = $user -> getUserFbUid();
              $this -> widget_vars["user_t_uid"] = $user -> getUserTUid();
              $this -> widget_vars["user_fname"] = utf8_encode($user -> getUserFname());
              $this -> widget_vars["user_lname"] = utf8_encode($user -> getUserLname());
              $this -> widget_vars["user_username"] = $user -> getUserUsername();
              $this -> widget_vars["user_photo_url"] = $user -> getUserPhotoUrl();  
              $this -> widget_vars["user_facebook_url"] = $user -> getUserFacebookUrl();
              $this -> widget_vars["user_twitter_url"] = $user -> getUserTwitterUrl();
              $this -> widget_vars["user_tagline"] = $user -> getUserTagline();
              $this -> widget_vars["user_website_url"] = $user -> getUserWebsiteUrl();
              $this -> widget_vars["user_bio"] = $user -> getUserBio();
              $this -> widget_vars["current_password"] = decrypt($user -> getUserPassword());
              $this -> widget_vars["current_timezone"] = $user -> getUserDefaultTimezone();
              $this -> widget_vars["error"] = $this -> sessionVar("error");
            }
          }         
        } catch (Exception $e) {
          redirect('error');
          die();     
        } 
        break;
    }
  }
  
  function outputStatus( $status, $result, $message, $object ) {
    $res = new stdClass;
    $res -> accountFilmResponse = 
                        array("status"=>$status,
                              "result"=>$result,
                              "message"=>$message,
                              "object"=> $object);
    print(json_encode($res));
    die();
  }
  
  function addInfo($type,$tid,$film) {
    if ($this -> postVar("film_".$type)) {
      $i=0;
      $items = $this->postVar("film_".$type);
      foreach($items as $item) {
        if ($item == "")
          continue;
        $crud = new FilmInfoCrud( $this -> context );
        $crud -> setFkFilmId( $film );
        $crud -> setFilmInfoType( $tid );
        $crud -> setFilmInfo( $item );
        $crud -> setFilmInfoUpdatedAt( now() );
        $crud -> setFilmInfoCreatedAt( now() );
        $crud -> save();
        $i++;
      }
    }
  }

}

function confirmEmail() {
  if (($_POST["user_email_new"] == '') || ($_POST["user_email_confirm"] == '') || ($_POST["user_email_confirm"] != $_POST["user_email_new"])) {
    return false;
  }
  return true;
}

function confirmPassword() {
  if (($_POST["new_password"] == '') || ($_POST["new_password_confirm"] == '') || ($_POST["new_password_confirm"] != $_POST["new_password"])) {
    return false;
  }
  return true;
}
  ?>
