<?php
  
  //This is an unusual widget, in that it handles reading and streaming
  //Content from our S3 Bucket
  //It will need to add security restrictions, etc...
  //But is generally accessed from /s3/$resourcename
  //And it streams specific mime types from our s3 bucket
  include_once(sfConfig::get('sf_lib_dir')."/helper/S3Helper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/S3Source_crud.php';
  
  class S3Source_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	var $cdn_root;
	var $base;
	var $bucketName;
	var $s3;
	var $supress_output;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
		$this -> base = sfConfig::get("sf_web_dir");
	  $this -> bucketName = "cdn.constellation.tv";
	  $this -> supress_output = false;
		switch (sfConfig::get("sf_environment")) {
			case "dev":
			case "stage":
				$this -> cdn_root = "dev";
			break;
			case "test":
				$this -> cdn_root = "test";
			break;
			case "prod":
				$this -> cdn_root = "prod";
			break;
			default:
				die("No Root Directory");
			break;
		}
		
		$this -> s3 = new S3(sfConfig::get("app_aws_access_key"), awsSecretKey, false);

    parent::__construct( $context );
    
  }

	function parse() {
	
		 if ($this -> as_cli) {
      
      if ($this ->widget_vars["args"][0] == "help") {
       
       cli_text( "listBuckets", "green" ) ;
       cli_text( " -- List Available AWS Buckets", "cyan") ;
       
       cli_text( "listContents", "green" ) ;
       cli_text( " -- List Bucket Contents", "cyan") ;
       
       cli_text( "getFile \"\$file\" (string)", "green" ) ;
       cli_text( " -- Return File Object", "cyan") ;
       
       cli_text( "copyFile \"\$file\|\$destination\" (string|string)", "green" ) ;
       cli_text( " -- Copy file to Local Destination", "cyan") ;
       
       cli_text( "getFileInfo \"\$file\" (string)", "green" ) ;
       cli_text( " -- Get AWS File Info", "cyan") ;
       
       cli_text( "checkFile \"\$file\" (string)", "green" ) ;
       cli_text( " -- Check Local FIle Permissions", "cyan") ;
       
       cli_text( "putFile \"\$file\|\$overwrite\|\$environment\" (string)", "green" ) ;
       cli_text( " -- Add a File to AWS", "cyan") ;
       
       die();
      }
      
      $this -> action = $this ->widget_vars["args"][0];
    }
    
    if (! $this -> action) {
      return null;
    }
    
    switch ($this -> action) {
      case "listBuckets":
        $this -> listBuckets();
        break;
      case "listContents":
        $this -> listContents($this ->widget_vars["args"][1]);
        break;
      case "getFile":
        $this -> getFile($this ->widget_vars["args"][1]);
        break;
      case "copyFile":
        $this -> copyFile($this ->widget_vars["args"][1],$this ->widget_vars["args"][2]);
        break;
      case "getFileInfo":
        $this -> getFileInfo($this ->widget_vars["args"][1]);
        break;
      case "checkFile":
        $this -> getCheckFile($this ->widget_vars["args"][1]);
        break;
      case "putFile":
        $this -> putFile($this ->widget_vars["args"][1],$this ->widget_vars["args"][2],$this ->widget_vars["args"][3]);
        break;
    }
    
	   
	 }
	 
	 function listBuckets() {
	 	$buckets = $this -> s3->listBuckets();
	 	foreach($buckets as $bucket) {
	 		cli_text($bucket,"yellow");
		}
	 }
	 
	 function listContents( $directory ) {
	 	cli_text("Not Implemented","red");
	 	die();
	 	$contents = $this -> s3->getBucket($this -> bucketName);
	 	foreach($contents as $file) {
	 		//dump($file);
			 //header("Content-Type: ".$asset->headers["type"]);
			 //header("Content-length: ".$asset->headers["size"]);
			 //print($asset -> body);
		}
	 }
	 
	 function getFile( $file ) {
		 return $this -> s3->getObject($this -> bucketName, $file);
	 }
	 
	 function copyFile( $file,$destination ) {
		 $this -> s3->getObject($this -> bucketName, $file, $destination);
	 }
	 
	 function getFileInfo( $file ) {
	 	
		$info = $this -> s3->getObjectInfo($this -> bucketName, $file);
		foreach(array_keys($info) as $att) {
	 		cli_text($att,"yellow");
	 		cli_text($info[$att],"cyan");
		}
	 }
	 
	 function checkFile( $file ) {
	 	 cli_text("EXISTS::","yellow");
		 cli_text(file_exists($this -> base . $file),"cyan");

	 	 cli_text("FILE::","yellow");
		 cli_text(is_file($this -> base . $file),"cyan");

	 	 cli_text("READABLE::","yellow");
		 cli_text(is_readable($this -> base . $file),"cyan");
		
	 }
	 
	 function putFile( $file, $overwrite=false, $env=null ) {
	 
		 //$uploadFile = "/uploads/screeningResources/4/logo/4c344b45275b4.jpg";
	   //$uploadFile = $file;
		 $uploadFile = str_replace(sfConfig::get("sf_web_dir"),"",$file);
		 $uploadFile = str_replace("//","/",$uploadFile);
		 putLog("Clean File: " . $uploadFile);
		 if (left($uploadFile,1) != "/") {
		 	$uploadFile = "/" . $uploadFile;
		 }
		 
		 if (! is_null($env)) {
		 	$this -> cdn_root = $env;
		 }
		 
		 //cli_text($this -> cdn_root,"yellow");
		 
		 if (! $overwrite) {
			 $info = $this -> s3->getObjectInfo($this -> bucketName, $this -> cdn_root.$uploadFile);
			 //echo "S3::getObjecInfo(): Info for {$this -> bucketName}/".$this -> cdn_root.$uploadFile.': '.print_r($info, 1);
			 if ($info) {
			 	if (! $this -> supress_output)
					cli_text("File Exists, Overwrite Forbidden (use 'true' in your options to allow overwriting)","red");
			 	die();
			 }
		 } else {
		 	if (! $this -> supress_output)
				cli_text("Note: overwrite set to 'true'","yellow");
		 }
		 
		 putLog("LOCAL File: " . $this -> base.$uploadFile);
		 putLog("CDN File: " . $this -> cdn_root.$uploadFile);
		 if ($this -> s3->putObjectFile($this -> base.$uploadFile, $this -> bucketName, $this -> cdn_root.$uploadFile, S3::ACL_PUBLIC_READ)) {
			if (! $this -> supress_output)
				cli_text("S3::putObjectFile(): File copied to {$this -> bucketName}/".$this -> cdn_root.$uploadFile,"green");
		 } else {
		 	if (! $this -> supress_output)
				cli_text("S3::putObjectFile(): File not copied to {$this -> bucketName}/".$this -> cdn_root.$uploadFile,"red");
		 }
	
  }
  
  function parse_last() {
	
   $s3 = new S3(sfConfig::get("app_aws_access_key"), awsSecretKey, false);
	 //dump($s3->listBuckets());
	 $contents = $s3->getBucket("streams.constellation.tv");
	 foreach($contents as $file) {
    //kickdump($file["name"]);
    $nl = explode("/",$file["name"]);
    if (right(end($nl),3) == "mp4") { 
      $dest = str_replace(end($nl),"",$file["name"]);
      createDirectory(sfConfig::get("sf_data_dir")."/".$dest);
      kickdump(sfConfig::get("sf_data_dir")."/".$file["name"]);
      $s3->getObject("streams.constellation.tv", $file["name"], sfConfig::get("sf_data_dir")."/".$file["name"]);
    }
    //$s3->getObject($bucketName, baseName($uploadFile), 'savefile.txt')
   }
   die();
   //$image = $s3->getObject(sfConfig::get("app_aws_bucket"), baseName($name));
   //print($image->body);
  
	 $request = str_replace("/s3/","",$_SERVER["REQUEST_URI"]);
	 $type = right($request,4);
	 $type = str_replace(".","",$type);
	 //createDirectory(sfConfig::get("sf_data_dir")."/".$request);
	// $s3->getObject(sfConfig::get("app_aws_bucket"), $request, sfConfig::get("sf_data_dir")."/".$request);
	// die();
   	$asset = $s3->getObject(sfConfig::get("app_aws_bucket"), $request);
	 //print ($asset->headers["code"]);
	 header("Content-Type: ".$asset->headers["type"]);
	 header("Content-length: ".$asset->headers["size"]);
	 print($asset -> body);
	 die();
   
  }

}
?>
