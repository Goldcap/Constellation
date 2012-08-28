<?php

function embedImage( $image, $type="png", $width=false, $height=false ) {
    //$filesize = @filesize( $image );
    //header("Content-Type: image/".$type);
    //header("Content-length: " . $filesize);
    if (file_exists($image)) {
      $img = base64_encode(file_get_contents($image));
    } else {
      echo("No File Available.");
    }
    $alt="";
    if($width){$alt.='width="'.$width.'" ';}
    if($height){$alt.='height="'.$height.'" ';}
    echo "<img alt=\"Embedded Image\" ".$alt." src=\"data:image/".$type.";base64,".$img."\" />";
}
  
function showSwf( $hash, $download=false ) {
  
  $file = sfConfig::get("app_download_image_dir")."/data/".left($hash,1)."/".left($hash,2)."/".left($hash,3)."/".$hash.".jpg";
  createDirectory( sfConfig::get("app_download_image_dir")."/data/".left($hash,1)."/".left($hash,2)."/".left($hash,3)."/" );
  $filesize = @filesize($file);
  if ((! $filesize) || ($filesize < 10)) {
    $cli = new CLI();
    $command = sfConfig::get("sf_root_dir")."/../bin/createswf.sh ".$hash." \"http:\/\/stage.tattoojohnny.com\/images\/data\/\" \"".sfConfig::get("app_shared_image_dir")."/data/\"";
    $result = $cli -> exec($command);
  }
  
  if ($download == "true") {
    return true;
  } else {
    header("Content-Type: application/x-shockwave-flash ");
    header("Content-length: " . $filesize);
  }
  echo file_get_contents($file);
  die();

}

function showImage( $context, $sku, $color="color", $download="false", $path=false, $type="jpg", $name=false) {
  
  if ($color == "bw") {
    $color = "stensil";
  }
  if (! $path) {
    $file = sfConfig::get("app_download_image_dir")."/products/".left($sku,1)."/".left($sku,2)."/".left($sku,3)."/".$sku."/".$sku."_".$color.".jpg";
  } else {
    $file = $path;
  }
  $filesize = @filesize($file);

  if ((! $filesize) || ($filesize < 10)) {
    $d = new WTVRData( null );
    $sql = "select product_id from product where product_sku = '".$sku."' limit 1;";
    $rs = $d -> propelQuery($sql);
    while ($rs->next()) {
      getProductImage( $rs->get(1), false, false );
    }
  }
  
  switch ($type) {
    case "jpg":
      header("Content-Type: image/jpg");
    	break;
    case "png":
      header("Content-Type: image/jpg");
      break;
    case "gif":
      header("Content-Type: image/gif");
      break;
  }
  
  if (! $name) {
    $name = $sku.'_'.$color.'.'.$type;
  }
  
  if ($download == "true") {
     
     $response = $context->getResponse()->clearHttpHeaders();
     
     header('Content-Description: File Transfer');
     header('Content-Disposition: attachment; filename="'.$name.'"');
     header("Content-Transfer-Encoding: binary");
     header('Accept-Ranges: bytes');
     header("Content-length: " . $filesize);
     
     /* The three lines below basically make the 
        download non-cacheable */
     header("Cache-control: private");
     header('Pragma: private');
     header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

  } else {
    
    header("Content-length: " . $filesize);
  
  }
  echo file_get_contents($file);
  die();

}

function showPDF( $context, $sku, $download="false", $path=false, $color="") {
  
  if ($color != "") {
     $color="_".$color;
  }
  if (! $path) {
    $file = sfConfig::get("app_download_image_dir")."/products/".left($sku,1)."/".left($sku,2)."/".left($sku,3)."/".$sku."/".$sku.$color.".pdf";
  } else {
    $file = $path;
  }
  $filesize = @filesize($file);
  header("Content-Type", "application/pdf");
  
  if ($download == "true") {
     $response = $context->getResponse()->clearHttpHeaders();
     
     header('Content-Description: File Transfer');
     header('Content-Disposition: attachment; filename="'.$sku.'.pdf"');
     header("Content-Transfer-Encoding: binary");
     header('Accept-Ranges: bytes');
     header("Content-length: " . $filesize);
     
     /* The three lines below basically make the 
        download non-cacheable */
     header("Cache-control: private");
     header('Pragma: private');
     header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

  } else {
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Type: application/pdf");
    header("Content-Transfer-Encoding: binary");
    header("Content-length: " . $filesize);
    header("Content-Transfer-Encoding: binary");
     
  }
  echo file_get_contents($file);
  die();

}

function showExcel( $context, $file=false, $download="false", $name=false ) {
  
  if (! $file) {
    return false;
  }
  
  if (! $name) {
    $name = "Download_file_".formatDate(null,"FULLTIME").".xls";
  }
  $filesize = filesize($file);
  
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=\"".$name."\"");
  $response = $context->getResponse()->clearHttpHeaders();
     
  header('Content-Description: File Transfer');
  header("Content-Transfer-Encoding: binary");
  header('Accept-Ranges: bytes');
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  
  if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')){
    header('Cache-Control: public');
  }
  
  //Just FYI, this kills the download for some reason
  //header("Content-Length: ".$filesize); 
  
  /* The three lines below basically make the 
      download non-cacheable */
  header("Cache-control: private");
  header('Pragma: private');
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

  echo file_get_contents($file);
  die(); 
 
}

function getProductImagePath( $sku ) {
  
  return left($sku,1) . "/".left($sku,2)."/".left($sku,3);
  
}

function getImageDir( $sku ) {
  
  return left($sku,1) . "/".left($sku,2)."/".left($sku,3)."/".$sku;
  
}

function getShowtimeUserAvatar($screening = array(),$size="medium"){
		 $imageUrl = '/images/icon-custom.png';
      if ($screening["screening_user_photo_url"] != '') {
        if (left($screening["screening_user_photo_url"],4) != 'http') {
              //$imageUrl = '//'.sfConfig::get("app_domain");
              $imageUrl = '/uploads/hosts/'. $screening["screening_user_id"] . '/icon_'.$size.'_'.$screening["screening_user_photo_url"];
          } else { 
              $imageUrl = $screening["screening_user_photo_url"];
          }
      } elseif ($screening["screening_user_image"] != '') {
        if (left($screening["screening_user_image"],4) != 'http') {
            //$imageUrl = '//'. sfConfig::get("app_domain");
            $imageUrl = '/uploads/hosts/'. $screening["screening_user_id"] . '/icon_'.$size.'_'.$screening["screening_user_image"];
        } else {
            $imageUrl = $screening["screening_user_image"];
        }
      }
      
    return $imageUrl;
}
function getAudienceUserAvatar($screening = array(),$size="medium"){
     $imageUrl = '/images/icon-custom.png';
      if ($screening["audience_screening_user_photo_url"] != '') {
        if (left($screening["audience_screening_user_photo_url"],4) != 'http') {
              //$imageUrl = '//'.sfConfig::get("app_domain");
              $imageUrl = '/uploads/hosts/'. $screening["audience_screening_user_id"] . '/icon_'.$size.'_'.$screening["audience_screening_user_photo_url"];
          } else { 
              $imageUrl = $screening["audience_screening_user_photo_url"];
          }
      } elseif ($screening["audience_screening_user_image"] != '') {
        if (left($screening["audience_screening_user_image"],4) != 'http') {
            //$imageUrl = '//'. sfConfig::get("app_domain");
            $imageUrl = '/uploads/hosts/'. $screening["audience_screening_user_id"] . '/icon_'.$size.'_'.$screening["audience_screening_user_image"];
        } else {
            $imageUrl = $screening["audience_screening_user_image"];
        }
      }
      
    return $imageUrl;
}
function getUserAvatar($user = array(),$size="medium"){
		 $imageUrl = '/images/icon-custom.png';
      if ($user["user_photo_url"] != '') {
        if (left($user["user_photo_url"],4) != 'http') {
              $imageUrl = '/uploads/hosts/'. $user["user_id"] . '/icon_'.$size.'_'.$user["user_photo_url"];
          } else { 
              $imageUrl = $user["user_photo_url"];
          }
      } elseif ($user["user_image"] != '') {
        if (left($user["user_image"],4) != 'http') {
            $imageUrl = '/uploads/hosts/'. $user["user_id"] . '/icon_'.$size.'_'.$user["user_image"];
        } else {
            $imageUrl = $user["user_image"];
        }
      } 
      
    return $imageUrl;
}

function getSessionAvatar($user, $size="medium"){
		 $imageUrl = '/images/icon-custom.png';

      if ($user -> getAttribute("user_image") != '' && $user -> getAttribute("user_image") != '/images/icon-custom.png') {
        if (left($user -> getAttribute("user_image"),4) != 'http') {
              $imageUrl = '/uploads/hosts/'. $user -> getAttribute("user_id") . '/icon_'.$size.'_'.$user -> getAttribute("user_image");
          } else { 
              $imageUrl = $user -> getAttribute("user_image");
          }
      }
      
    return $imageUrl;
}

function getUserAvatarByClass($user, $size = "medium"){
  $imageUrl = "/images/alt/featured_still.jpg";
  
  if ($user -> getUserPhotoUrl() != '') {
      if (left($user -> getUserPhotoUrl(),4) == 'http') {
        $imageUrl = $user -> getUserPhotoUrl();
      } else {
        $imageUrl = '/uploads/hosts/' . $user -> getUserId() . '/' . $user -> getUserPhotoUrl();
    }
  }
  return $imageUrl;

}

?>
