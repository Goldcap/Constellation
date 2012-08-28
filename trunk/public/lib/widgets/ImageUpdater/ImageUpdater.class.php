<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  include_once(sfConfig::get('sf_lib_dir')."/helper/ImageHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/ImageUpdater_crud.php';
  
   class ImageUpdater_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
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
	  $this -> locations["vow_update"] = sfConfig::get("sf_upload_dir"). "/vows/";
	
	}

	function parse() {
	 
	 if ($this -> as_cli) {
     if ($this -> widget_vars["args"][0] == "vow") {
      $sql = "select vow_id
							from vow
							where right(vow_asset_filename,3) = 'jpg' order by vow_date_created desc;"; 
			/*$sql = "select user_id,
							user_photo_url
							from user
							where user_id in (2,118);"; 
			*/
			$res = $this -> propelQuery($sql);
			 while ($row = $res -> fetch()) {
			 	echo $row[0]."\n";
			 }
     } else if ($this -> widget_vars["args"][0] == "film_logo") {
       $sql = "select film_id,
              film_logo
              from film
              where film_logo is not null;"; 
      $res = $this -> propelQuery($sql);
       while ($row = $res -> fetch()) {
          cli_text("Updating Film Logo ".$row[0]." with ".$row[1],"green");
          $this -> imageUpdate( $row[0], str_replace(".jpg","",$row[1]), "film" );
        // echo $row[0]."\n";
       }
     } else if ($this -> widget_vars["args"][0] == "film_home") {
       $sql = "select film_id,
              film_homelogo
              from film
              where film_homelogo is not null;"; 
      $res = $this -> propelQuery($sql);
       while ($row = $res -> fetch()) {
          cli_text("Updating Film Logo ".$row[0]." with ".$row[1],"green");
          $this -> imageUpdate( $row[0], str_replace(".jpg","",$row[1]), "homefilm" );
        // echo $row[0]."\n";
       }
		 } else if ($this -> widget_vars["args"][0] == "list") {
			 $sql = "select user_id,
							user_photo_url
							from user
							where user_photo_url is not null
							and user_photo_url not like 'http%';"; 
			/*$sql = "select user_id,
							user_photo_url
							from user
							where user_id in (2,118);"; 
			*/
			$res = $this -> propelQuery($sql);
			 while ($row = $res -> fetch()) {
			 	echo $row[0]."\n";
			 }
		 //return $this -> widget_vars;
		 } elseif ($this -> widget_vars["args"][0] == "process") {
		 		 $sql = "select user_id,
								user_photo_url
								from user
								where user_id = ".$this -> widget_vars["args"][1].";"; 
				$res = $this -> propelQuery($sql);
				 while ($row = $res -> fetch()) {
					cli_text("Updating User ".$row[0]." with ".$row[1],"green");
				 	$this -> imageUpdate( $row[0], str_replace(".jpg","",$row[1]), "host" );
				 }
		 } elseif ($this -> widget_vars["args"][0] == "vow_update") {
		 		 $sql = "select vow_id,
                fk_user_id,
                vow_asset_guid
  							from vow
  							where vow_id = ".$this -> widget_vars["args"][1].";"; 
				$res = $this -> propelQuery($sql);
				 while ($row = $res -> fetch()) {
					cli_text("Updating User ".$row[1]." with ".$row[2],"green");
				 	$this -> imageUpdate( $row[1], str_replace(".jpg","",$row[2]), "vow_update" );
				 }
		 } elseif ($this -> widget_vars["args"][0] == "vow_check") {
		 		 $sql = "select vow_id,
                fk_user_id,
                vow_asset_guid
  							from vow
  							where vow_image_generated is null;"; 
				$res = $this -> propelQuery($sql);
				 while ($row = $res -> fetch()) {
				 	cli_text("Updating Vow ".$row[1]." with ".$row[2],"green"); 
				 	$sql = "update vow set vow_image_generated = 1 where vow_id = ".$row[0].";";
				 	$this -> propelQuery($sql);
				 	$this -> imageUpdate( $row[1], str_replace(".jpg","",$row[2]), "vow_update" );
				 }
		 }
	 }   
	 
  }
  
  function imageUpdate( $id, $hash, $type ) {
	
		$save_path = $this -> locations[$type];
		$thumbfile = new WTVRImage( false, false );
		$imagedir = $id;

		//Add specific Image Director Subdirectories
    switch ($type) {
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
    if($type = 'film'){
      $toFind = 'small_poster';
    }
    createDirectory($save_path.$imagedir);
		$thumbfile -> destination_dir = $save_path.$imagedir."/";
    if ($type == "vow_update") {
      $dir = $this -> locations["vow"]."temp/".getImageDir($hash)."/";
      
      // Open a known directory, and proceed to read its contents
      if (is_dir($dir)) {
          if ($dh = opendir($dir)) {
              while (($file = readdir($dh)) !== false) {
                if (($file != "..") && ($file != ".")) {
                  $thefile = $this -> locations["vow"]."temp/".getImageDir($hash)."/";
                  //echo $thefile.$file;
                  $vowfile = $thefile.$file;
                  $thumbfile -> setFile($thefile.$file);
                  break;
                }
              }
              closedir($dh);
          }
      }
    } else {
      dump($save_path.$imagedir."/".(isSet($toFind)?$toFind:'').$hash.".jpg");
      die;
		  $thumbfile -> setFile($save_path.$imagedir."/".(isSet($toFind)?$toFind:'').$hash.".jpg");
		}
    $thumbfile -> hash = $hash;
		$file_name = $thumbfile -> hash.".jpg";
	
		//Set the Name, and create the "original" file
    switch ($type) {
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
        $thumbfile -> destination_name = $file_name;
        break;
      case "vow":
      	$thumbfile -> destination_name = "asset-".$this -> sessionVar("user_id")."-".nowAsId();
        $thumbfile -> move();
        break;
      case "vow_update":
        $thumbfile -> destination_name = $hash.".jpg";
        if (!copy($vowfile, $save_path.$imagedir."/".$hash.".jpg")) {
            echo "failed to copy $vowfile...\n";
        }
      	break;
      case "movie":
        $thumbfile -> destination_name = "movie-".$this -> getVar("rev");
        $thumbfile -> move();
        break;
      case "trailer":
        //Don't keep the original, just copy
        $thumbfile -> destination_name = $thumbfile -> hash;
        break;
    }
        
    $thumbfile -> destination_fullname = $save_path.$imagedir."/".$thumbfile -> destination_name;

    /*Create Thumbnails*/
    /*Limit, Resize, or Minimum*/
    switch ($type) {
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
        $thumbfile -> resize( 'user_medium_icon' ,  "icon_medium_".$thumbfile -> hash, 'jpg' );
        $thumbfile -> resize( 'user_small_icon' , "icon_small_".$thumbfile -> hash, 'jpg' );
        $thumbfile -> resize( 'host' , "host_".$thumbfile -> hash, 'jpg' );
        $this -> PutS3 ( $thumbfile );
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
        $thumbfile -> resize( 'logo_event' , "logo_event_".$thumbfile -> hash, 'jpg' );
        $thumbfile -> resize( 'logo_small_poster' , "small_poster".$thumbfile -> hash, 'jpg' );
        $thumbfile -> resize( 'logo_poster' , "poster".$thumbfile -> hash, 'jpg' );
        $thumbfile -> resize( 'logo_purchase_email_poster' , "purchase_email_poster".$thumbfile -> hash, 'jpg' );
        $thumbfile -> resize( 'logo_invite_email_poster' , "invite_email_poster".$thumbfile -> hash, 'jpg' );
        $thumbfile -> resize( 'logo_screening_poster' , "screening_poster".$thumbfile -> hash, 'jpg' );
        $thumbfile -> resize( 'logo_thumb' , "thumb_".$thumbfile -> hash, 'jpg' );
        $thumbfile -> resize( 'logo' , $thumbfile -> hash, 'jpg' );
        $thumbfile -> resize( 'logo_film_logo' , "film_logo".$thumbfile -> hash, 'jpg' ); ;
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
        $thumbfile -> resize( 'screening_event' , "screening_event_".$thumbfile -> hash, 'jpg' );
        $thumbfile -> resize( 'screening_small' , "screening_small_".$thumbfile -> hash, 'jpg' );
        $thumbfile -> resize( 'screening_large' , "screening_large_".$thumbfile -> hash, 'jpg' );
        $thumbfile -> resize( 'film_screening_small' , "film_screening_small_".$thumbfile -> hash, 'jpg' );
        $thumbfile -> resize( 'film_screening_large' , "film_screening_large_".$thumbfile -> hash, 'jpg' );
        $thumbfile -> resize( 'film_screening_next' , "film_screening_next_".$thumbfile -> hash, 'jpg' );
        $thumbfile -> resize( 'screening_event' , "screening_event_".$thumbfile -> hash, 'jpg' );
        $preview = "film_screening_large_".$thumbfile -> hash;
        break;
      case "vow_update":
        $thumbfile -> destination_dir = $save_path.$imagedir."/";
        $thumbfile -> resize( 'vow_small_icon' , "vow_small_".$thumbfile -> hash, 'jpg' );
        $thumbfile -> resize( 'vow_medium' , "vow_medium_".$thumbfile -> hash, 'jpg' );
        $preview = $thumbfile -> hash;
        break;
    }
    
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
