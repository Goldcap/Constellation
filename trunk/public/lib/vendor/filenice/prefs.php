<?php

// default skin 
$defaultSkin = "default";

//default sort
$defaultSort = "name";
// default sort order
$defSortDirection = "ascending"; 

// default time to show each image in a slideshow (in seconds)
$defaultSSSpeed = 6;

// Show "send to Flickr" links
$flickr = false;

// any files you don't want visible to the file browser add into this 
// array...
$ignoreFiles = array( "file1.txt",
            "file2.txt",
            "index.php",
            "fComments.txt",
            );
            
// any folders you don't want visible to the file browser add into this 
// array...           
$ignoreFolders = array("fileNice",
            "someFolder",
            "upload",
            "image"
            );

// file type handling, add file extensions to these array to have the 
// file types handled in certain ways
$imgTypes   = array("gif","jpg","jpeg","bmp","png","tif");
$embedTypes = array("mp3","mov","aif","aiff","wav","swf","mpg","avi","mpeg","mid");
$htmlTypes  = array("html","htm","txt","css");
$phpTypes   = array("php","php3","php4","asp","js");
$miscTypes  = array("pdf","doc","zip","sit","rar","rm","ram");

// date format - see http://php.net/date for details
$dateFormat = "F d Y ";

?>