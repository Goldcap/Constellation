<?php

//http://www.w3schools.com/media/media_mimeref.asp
define('awsSecretKey', 'slKjHzCvnMdRwu7ruj+8iIsQxrkYhhgVt09Jp+XK');

function showS3Asset( $name ) {
  $s3 = new S3(sfConfig::get("app_aws_access_key"), awsSecretKey, false);
  $image = $s3->getObject(sfConfig::get("app_aws_bucket"), $name);
  header("Content-Type: ".$image->headers["type"]);
	header("Content-length: ".$image->headers["size"]);
  print($image->body);
  die();
}

?>
