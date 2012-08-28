<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/ImageManager_crud.php';
  
  include_once(sfConfig::get('sf_lib_dir')."/helper/ImageHelper.php");
  
  class ImageManager_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	var $locations;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
    
    //Where Do The Files Go?
    $this -> locations["host"] = sfConfig::get("sf_upload_dir"). "/hosts/";
    $this -> locations["user"] = sfConfig::get("sf_upload_dir"). "/hosts/";
    $this -> locations["trailer"] = sfConfig::get("sf_upload_dir"). "/screeningResources/";
    $this -> locations["movie"] = sfConfig::get("sf_data_dir"). "/movies/";
    $this -> locations["film"] = sfConfig::get("sf_upload_dir"). "/screeningResources/";
    $this -> locations["homefilm"] = sfConfig::get("sf_upload_dir"). "/screeningResources/";
    $this -> locations["still"] = sfConfig::get("sf_upload_dir"). "/screeningResources/";
    $this -> locations["screening"] = sfConfig::get("sf_upload_dir"). "/screeningResources/";
    $this -> locations["background"] = sfConfig::get("sf_upload_dir"). "/screeningResources/";
    $this -> locations["splash"] = sfConfig::get("sf_upload_dir"). "/screeningResources/";
    $this -> locations["program"] = sfConfig::get("sf_upload_dir"). "/programResources/";
    $this -> locations["program_still"] = sfConfig::get("sf_upload_dir"). "/programResources/";
    $this -> locations["program_background"] = sfConfig::get("sf_upload_dir"). "/programResources/";
    $this -> locations["vow"] = sfConfig::get("sf_data_dir"). "/vow/";                              
    $this -> locations["twentyonejump"] = sfConfig::get("sf_upload_dir"). "/21jump/";
    
    //Form Input Names, Limiting Form Element Upload Names
    $this -> formelements["host"] = "host_image";
    $this -> formelements["user"] = "user_image";
    $this -> formelements["trailer"] = "film_trailer_file";
    $this -> formelements["movie"] = "film_movie_file";
    $this -> formelements["film"] = "film_logo";  
    $this -> formelements["homefilm"] = "film_homelogo";
    $this -> formelements["still"] = "film_still_image";
    $this -> formelements["screening"] = "screening_still_image";
    $this -> formelements["background"] = "background";
    $this -> formelements["splash"] = "film_splash_image";
    $this -> formelements["program"] = "program_logo";
    $this -> formelements["program_still"] = "program_still_image";
    $this -> formelements["program_background"] = "program_background"; 
    $this -> formelements["vow"] = "vow_element";
    $this -> formelements["twentyonejump"] = "jump_element";
  }

	function parse() {

		putLog("STARTED IMAGE UPLOAD");
				
		$this -> context ->getLogger()->info("{Uploading Image} Type--".$this -> getVar("id")."\"");
    $this -> context ->getLogger()->info("{User Authentication} ".$this -> isAuthenticated()."\"");
    
    /*Limit ACCESS to this Widget*/
	  /*TODO This needs to be more methodical*/
    switch ($this -> getVar("id")) {
      case "twentyonejump":
			case "vow":
        $extension_whitelist = array("mp3", "mp4", "wmv", "mov", "avi", "png", "jpg", "jpeg", "gif", "bmp");	// Allowed file extensions
        if (! $this -> getUser() -> isAuthenticated() ) {
          $this -> HandleError("Not Authenticated.");
        }
        break;
			case "trailer":
      case "movie":
        $extension_whitelist = array("mp4", "wmv", "mov", "avi");	// Allowed file extensions
        if (! $this -> getUser() -> isAuthenticated() ) {
          $this -> HandleError("Not Authenticated.");
        }
        break;
      case "host":
      case "user":
      case "film":  
      case "homefilm":
      case "still":
      case "screening":
      case "background":
      case "splash":
      case "program":
      case "program_still":
      case "program_background":
      default:
        $extension_whitelist = array("jpg", "jpeg", "gif", "png");	// Allowed file extensions
        if (! $this -> getUser() -> isAuthenticated() ) {
          $this -> HandleError("Not Authenticated.");
        }
      break;
    }
    $this -> context ->getLogger()->info("{Uploading Image} Type--".$this -> getVar("id")."\"");
    
    // Settings
    	$save_path = $this -> locations[$this -> getVar("id")];				// The path were we will save the file (getcwd() may not be reliable and should be tested in your environment)
    	$upload_name = "Filedata";
    	$valid_chars_regex = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';				// Characters allowed in the file name (in a Regular Expression format)
    	
    // Other variables	
    	$MAX_FILENAME_LENGTH = 260;
    	$file_name = "";
    	$file_extension = "";
    	$uploadErrors = array(
            0=>"There is no error, the file uploaded with success",
            1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini",
            2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
            3=>"The uploaded file was only partially uploaded",
            4=>"No file was uploaded",
            6=>"Missing a temporary folder"
    	);
    
      // Validate the upload
    	if (!isset($_FILES[$upload_name])) {
    		$this -> HandleError("No upload found in \$_FILES for " . $upload_name);
    		die();
    	} else if (isset($_FILES[$upload_name]["error"]) && $_FILES[$upload_name]["error"] != 0) {
    		$this -> HandleError($uploadErrors[$_FILES[$upload_name]["error"]]);
    		die();
    	} else if (!isset($_FILES[$upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$upload_name]["tmp_name"])) {
    		$this -> HandleError("Upload failed is_uploaded_file test.");
    		die();
    	} else if (!isset($_FILES[$upload_name]['name'])) {
    		$this -> HandleError("File has no name.");
    		die();
    	}
    	
      // Validate file name (for our purposes we'll just remove invalid characters)
    	$file_name = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "", basename($_FILES[$upload_name]['name']));
    	if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
    		$this -> HandleError("Invalid file name");
    		die();
    	}
    
      // Validate that we won't over-write an existing file
    	if (file_exists($save_path . $file_name)) {
    		//$this -> HandleError("File with this name already exists");
    		//die();
    	}
    
      // Validate file extension
    	$path_info = pathinfo($_FILES[$upload_name]['name']);
    	$file_extension = $path_info["extension"];
    	$is_valid_extension = false;
    	
    	foreach ($extension_whitelist as $extension) {
    		if (strcasecmp($file_extension, $extension) == 0) {
    			$is_valid_extension = true;
    			break;
    		}
    	}
    	
    	if (!$is_valid_extension) {
    		$this -> HandleError("Invalid file extension");
    		die();
    	}
    	
      //Create the upload controller
      switch ($this -> getVar("id")) {
        case "host":
        case "user":
				case "film": 
				case "homefilm":
        case "still":
        case "screening":
        case "background":
        case "splash":
        case "program":
        case "program_still":
        case "program_background":
          $thumbfile = new WTVRImage( $upload_name, false );
          break;
        case "trailer":
        case "movie":
				case "twentyonejump":
          $thumbfile = new WTVRImage( $upload_name, false );
          break; 
        case "vow":
          $thumbfile = new WTVRFile( $upload_name, false );
          break;
      }
      
      if ($thumbfile -> isUploaded()) {
        /*Create Thumbnails*/
        /*Limit, Resize, or Minimum*/
        //Set the specific Item Directory
        switch ($this -> getVar("id")) {
          case "twentyonejump":
            $imagedir = getImageDir( $thumbfile -> hash );
          	break;
          case "movie":
            $up = explode("?",$_SERVER["REQUEST_URI"]);
            $vars = explode("/",$up[0]);
            $imagedir = end($vars);
            break;
          default:
            if ($this -> getVar("rev")) {
              $imagedir = $this -> getVar("rev");
            } else {
              $imagedir = "temp/".getImageDir( $thumbfile -> hash );
            }
            break;
        }
        
        //Add specific Image Director Subdirectories
        switch ($this -> getVar("id")) {
          case "twentyonejump":
          case "vow":
          case "movie":
            break;
          case "trailer":
            $imagedir .= "/trailerFile";
            break;
          case "film": 
          case "homefilm":
          case "program":
            $imagedir .= "/logo";
            break;
          case "still":
          case "program_still":
            $imagedir .= "/still";
            break;
          case "background":
          case "splash":
          case "program_background":
            $imagedir .= "/background";
            break;
          case "screening":
            $imagedir .= "/screenings";
            break;
          default:
            break;
        }
        
        createDirectory($save_path.$imagedir);
        
        $file_name = str_replace(".".$file_extension,"",$file_name);
        //Save Original
        $thumbfile -> destination_dir = $save_path.$imagedir."/";
        
        //Set the Name, and create the "original" file
        switch ($this -> getVar("id")) {
          case "twentyonejump":
            //Keep the original File
            $thumbfile -> destination_name = $file_name."_orig";
            $thumbfile -> move();
            $thumbfile -> copy( false, $thumbfile -> hash );
            if (in_array($thumbfile -> source_type,array("png", "jpg", "jpeg", "gif", "bmp"))) {
							$thumbfile -> convert( 'original_' . $thumbfile -> hash, 'jpg' );
            }
						break;
          case "host":
          case "user":
          case "film": 
          case "homefilm":
          case "still":
          case "screening":
          case "background":
          case "splash":
          case "program":
          case "program_still":
          case "program_background":
            //Keep the original File
            $thumbfile -> destination_name = $file_name."_orig";
            $thumbfile -> move();
            $thumbfile -> copy( false, $thumbfile -> hash );
            break;
          case "vow":
          	$thumbfile -> destination_name = "asset-".$this -> sessionVar("user_id")."-".nowAsId();
            $thumbfile -> move();
            break;
          case "movie":
            $thumbfile -> destination_name = "movie-".$this -> getVar("rev");
            $thumbfile -> move();
            break;
          case "trailer":
            //Don't keep the original, just copy
            $thumbfile -> destination_name = $thumbfile -> hash;
            $thumbfile -> move();
            break;
        }
        
        /*Create Thumbnails*/
        /*Limit, Resize, or Minimum*/
        switch ($this -> getVar("id")) {
          case "twentyonejump":
            if (in_array($thumbfile -> source_type,array("png", "jpg", "jpeg", "gif", "bmp"))) {
							$thumbfile -> set( 'jump_thumb' , "thumb_".$thumbfile -> hash, 'jpg' );
	            $thumbfile -> resize( 'vow_medium' , "medium_".$thumbfile -> hash, 'jpg' );
	            $preview = "thumb_".$thumbfile -> hash;
            } else {
							$preview = $thumbfile -> hash;
						}
						break;
					case "host":
            $thumbfile -> resize( 'user_large' , $thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'user_medium' , "medium_".$thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'user_small' , "thumb_".$thumbfile -> hash, 'jpg' ); 
            $thumbfile -> resize( 'user_large_icon' ,  "icon_large_".$thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'user_medium_icon' ,  "icon_medium_".$thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'user_small_icon' , "icon_small_".$thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'host' , "host_".$thumbfile -> hash, 'jpg' );
            //$this -> PutS3 ( $thumbfile );
            $preview = "host_".$thumbfile -> hash;
            break;
          case "user":
            $thumbfile -> resize( 'user_large' , $thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'user_medium' , "medium_".$thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'user_small' , "thumb_".$thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'user_large_icon' ,  "icon_large_".$thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'user_medium_icon' ,  "icon_medium_".$thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'user_small_icon' , "icon_small_".$thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'host' , "host_".$thumbfile -> hash, 'jpg' );
            //$this -> PutS3 ( $thumbfile );
            $preview = $thumbfile -> hash;
            break;
          case "movie":
            $preview = "movie-".$this -> getVar("rev");
            break;
          case "vow": 
          case "trailer":
            $preview = $thumbfile -> hash;
            //"/images/Neu/48x48/video.png"; 
            //$thumbfile -> hash;
            break;
          case "film":
          case "program":
            $thumbfile -> resize( 'logo_small_poster' , "small_poster".$thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'logo_poster' , "poster".$thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'logo_purchase_email_poster' , "purchase_email_poster".$thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'logo_invite_email_poster' , "invite_email_poster".$thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'logo_screening_poster' , "screening_poster".$thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'logo_thumb' , "thumb_".$thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'logo' , $thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'logo_film_logo' , "film_logo".$thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'logo_event' , "logo_event_".$thumbfile -> hash, 'jpg' );


            $preview = "screening_poster".$thumbfile -> hash;
            break;
          case "homefilm":  
            $thumbfile -> resize( 'logo_wide_poster' , "wide_poster".$thumbfile -> hash, 'jpg' );
          	$preview = "wide_poster".$thumbfile -> hash;
            break;
					case "still":
          case "program_still":
            $thumbfile -> resize( 'still_image' , $thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'film_next' , 'film_next_'.$thumbfile -> hash, 'jpg' );
            $preview = 'film_next_'.$thumbfile -> hash;
            break;
          case "background":
          case "program_background":
            $thumbfile -> resize( 'background_image' , $thumbfile -> hash, 'jpg' );
            $preview = $thumbfile -> hash;
            break;
          case "splash":
            $thumbfile -> resize( 'splash_image' , $thumbfile -> hash, 'jpg' );
            $preview = $thumbfile -> hash;
            break;
					case "screening":
            $thumbfile -> resize( 'screening_small' , "screening_small_".$thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'screening_large' , "screening_large_".$thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'film_screening_small' , "film_screening_small_".$thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'film_screening_large' , "film_screening_large_".$thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'film_screening_next' , "film_screening_next_".$thumbfile -> hash, 'jpg' );
            $thumbfile -> resize( 'screening_event' , "screening_event_".$thumbfile -> hash, 'jpg' );

            $preview = "film_screening_large_".$thumbfile -> hash;
            break;
        }
        
        /*DBOps, If ANy*/
        switch ($this -> getVar("id")) {
        	case "user":
          	$user = new UserCrud($this -> context);
          	$user -> setUserPhotoUrl($thumbfile -> hash.".jpg");
          	$user -> save();
          default:
          	break;
        }
        header("HTTP/1.0 200 OK");
				header('Cache-Control: no-cache, must-revalidate');
				header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
				header('Content-type: text/html');
        putLog("IMAGE " . $thumbfile -> hash . " SAVED");
				die($imagedir."/".$preview);  
      }
      
    	$this -> HandleError("File could not be saved.");
    	die();
    
  }
  
  public function HandleError($message) {
    throw new Exception($message);
  }
  
  public function PutS3 ($thumbfile) {
    //Upload Image to S3, for the appropriate Environment
    putLog("Put Image: " . $thumbfile -> output_location);
    $S3 = new S3Source_PageWidget( null, null, $this -> context );
    $S3 -> supress_output = true;
    $S3 -> putFile( $thumbfile -> output_location );
    
	}
}

?>
