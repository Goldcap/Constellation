<?php

/*  This will take images from the $_FILES array
    And upload them to a directory inside /public/web/images
    Note that these images are optionally linked to a parent CMS Object
*/

/* Files array is provided from PHP as follows:
//The FILE array has this in it:
    //["name"]=>
    //string(10) "bowtie.jpg"
    //["type"]=>
    //string(10) "image/jpeg"
    //["tmp_name"]=>
    //string(14) "/tmp/phpVd3XyX"
    //["error"]=>
    //int(0)
    //["size"]=>
    //int(11486)
*/
function imageUpload( $type, $ids, $attribs, $cms_object=false, $rev=false ) {
  $imagedir = sfConfig::get("app_shared_image_dir")."/";
  $uploaded = false;
  
  $errors = array();
  //Create an array of uploaded files
  $images = array();
  foreach ($ids as $id) {
    if (! isset($attribs[$id])) {
      $attribs[$id] = array();
    }
    //Transfer the uploaded file(s) into an array
    if (! is_array($_FILES["FILE_".$id])) {
      if ($_POST["delete_".$id] =="false"){
        $images[0] = $_FILES["FILE_".$id];
        $images[0]["parent_id"] = $id;
        $images[0]["sequence"] = 0;
      }
    } else {
      for($i=0;$i<count($_FILES["FILE_".$id]["name"]);$i++) {
        $image=null;
        if (($_POST["delete_".$id][$i] =="false") && (strlen($_FILES["FILE_".$id]["name"][$i]) > 0)) {
          if (! isset($attribs[$id][$i])) {
            $attribs[$id][$i] = array();
          }
          if (($_FILES["FILE_".$id]["error"][$i] == 0) || ($_FILES["FILE_".$id]["error"][$i] == 4)) {
            $image["name"] = $_FILES["FILE_".$id]["name"][$i];
            $image["type"] = $_FILES["FILE_".$id]["type"][$i];
            $image["tmp_name"] = $_FILES["FILE_".$id]["tmp_name"][$i];
            $image["size"] = $_FILES["FILE_".$id]["size"][$i];
            $image["parent_id"] = $id;
            $image["sequence"] = $i;
            $images[] = $image;
          }
        }
      }
    }
  }
  
  //If there is a parent object, remove all image attatchments for this rev.
  if ($cms_object) {
    if (! $rev) {
      $rev = $cms_object -> CmsObject -> getCmsObjectCurrentRev();
    }
    $sql = "delete from cms_object_group where fk_cms_object_parent_id = ".$cms_object -> CmsObject -> getCmsObjectId()." and fk_cms_object_parent_rev = ".$rev;
    $query = new WTVRData( null );
    $query -> propelQuery($sql);
  }      
  
  //Process Uploads
  if (count($images) > 0) {
    $i=0;
    foreach($images as $image) {
      $basedir = $imagedir.$type."/images/";
      
      if($image["tmp_name"] != "") {
        $file = new WTVRImage( "" );
        $file -> thefile = $image;
        $file -> setFileAttributes();
        
        $image_object = new CmsObjectCrud( null, 0 );
        $image_rev = new CmsObjectRevCrud( null, 0 );
        
        if (isset($attribs[$image["parent_id"]][$image["sequence"]]["id"]) && ($attribs[$image["parent_id"]][$image["sequence"]]["id"] != null)) {
          $vars = array("cms_object_uuid"=>$attribs[$image["parent_id"]][$image["sequence"]]["id"]);
          $image_object -> checkUnique( $vars );
        }
        
        //This image was already uploaded
        //So we skip it!
        //TODO:: Need to notify the user that the image was already uploaded
        if ($image_object -> CmsObject -> getCmsObjectId() > 0) {
          $errors[] = "The image ".$file -> thefile["name"]." was already uploaded, pease try again.";
          $uploaded = "true";
          //continue;
        }
        
        $props = new stdClass();
        
        $destinationdir = $imagedir.$type."/".left($file->hash,1)."/".left($file->hash,2)."/".$file->hash;
        $webdir = "/images/".$type."/".left($file->hash,1)."/".left($file->hash,2)."/".$file->hash."/";
        
        $props -> baselocation = $webdir;
        
        $name=explode(".",$file -> thefile["name"]);
        
        if (! $uploaded ) {
          $file -> destination_name = strtolower($name[0]);
          $file -> destination_dir = $destinationdir;
          $file -> move();
          
          $props -> imagesizes['source']['location'] = $webdir.strtolower($name[0]).".jpg";
          
          //Create Thumbs of Various Sizes
          $file -> resize( 'title_icon' , strtolower($name[0])."_icon", 'jpg' );
          
          $props -> imagesizes['title_icon']['location'] = $webdir.strtolower($name[0])."_icon.jpg";
          $props -> imagesizes['title_icon']['height'] = $file -> dest_size["height"];
          $props -> imagesizes['title_icon']['width'] = $file -> dest_size["width"];
          
          $file -> resize( 'smallicon' , strtolower($name[0])."_smallicon", 'jpg' );
          $props -> imagesizes['smallicon']['location'] = $webdir.strtolower($name[0])."_smallicon.jpg";
          $props -> imagesizes['smallicon']['height'] = $file -> dest_size["height"];
          $props -> imagesizes['smallicon']['width'] = $file -> dest_size["width"];
          
          $props -> imagesizes['source']['height'] = $file -> source_size["height"];
          $props -> imagesizes['source']['width'] = $file -> source_size["width"];
          
          $attribs[$image["parent_id"]][$image["sequence"]]["id"] = $file->hash;
        }
      }
      
      if (! $uploaded ) {
        if ((! isset($attribs[$image["parent_id"]][$image["sequence"]]["name"])) & (isset($name[0]))) {
          $attribs[$image["parent_id"]][$image["sequence"]]["name"] = ucwords(str_replace("_"," ",$name[0]));
        }
        if ((! isset($attribs[$image["parent_id"]][$image["sequence"]]["title"])) & (isset($name[0]))) {
          $attribs[$image["parent_id"]][$image["sequence"]] = ucwords(str_replace("_"," ",$name[0]));
        }
        if (! isset($attribs[$image["parent_id"]][$image["sequence"]]["position"])) {
          $attribs[$image["parent_id"]][$image["sequence"]]["position"] = "right";
        }
        
        if (isset($attribs[$image["parent_id"]][$image["sequence"]]["name"])) {
          $image_rev -> setCmsObjectRevName( strtolower( $attribs[$image["parent_id"]][$image["sequence"]]["name"] ) );
        }
        if (isset($attribs[$image["parent_id"]][$image["sequence"]]["title"])) {
          $image_rev -> setCmsObjectRevTitle( $attribs[$image["parent_id"]][$image["sequence"]]["title"] );
        }
        $image_object -> setCmsObjectLive( 1 );
        $image_object -> setCmsObjectDateCreated( now() );
        $image_object -> setFkCmsObjectCategory( 2 );
        if (isset($file->hash)) {
          $image_object -> setCmsObjectUuid( $file -> hash );
        }
        
        if (isset($props)) {
          $image_rev -> setCmsObjectRevProperties( serialize($props) );
        }
        $image_object -> save();
        $image_rev -> setFkCmsObjectId( $image_object -> CmsObject -> getCmsObjectId() );
        $image_rev -> setCmsObjectRevCount( $rev );
        $image_rev -> save();
      }
      
      if ($cms_object) {
        
        $link = new CmsObjectGroup( null );
        $link -> setFkCmsObjectParentId( $cms_object -> CmsObject -> getCmsObjectId() );
        if ($rev) {
          $link -> setFkCmsObjectParentRev( $rev );
        } else {
          $link -> setFkCmsObjectParentRev( $cms_object  -> CmsObject -> getCmsObjectCurrentRev() );
        }
        $link -> setFkCmsObjectChildId( $image_object -> CmsObject -> getCmsObjectId() );
        $link -> setCmsObjectGroupPosition( $attribs[$image["parent_id"]][$image["sequence"]]["position"] );
        $link -> save();
        
        
      }
      
      $i++;
    }
    
  }
  return $errors;
}
?>
