<?php

class sfConfig {
  static function get( $name ) {
    return "";
  }
}

//Prerequisites
include_once("../../utils.php");
include_once("../../WideXML/XML.php");
include_once("../../WideXML/XSL.php");
include_once("../../PageWidgets/UtilWidget.class.php");

//XMLForm Classes
include_once("../1.2/clsXMLFormUtils.php");
include_once("../1.2/clsXMLForm.php");
include_once("../1.2/clsFormValidator.php");
include_once("../1.2/clsXMLSelect.php");


$XMLForm = new XMLForm();
$XMLForm -> item_forcehidden = true;
$XMLForm -> assetlocation = "/var/www/html/sites/dev.constellation.tv/public/lib/vendor/styroform/example/";

$films[0]["sel_key"] = 1;
$films[0]["sel_value"] = "A Film 1";
$films[1]["sel_key"] = 2;
$films[1]["sel_value"] = "A Film 2";
$films[2]["sel_key"] = 3;
$films[2]["sel_value"] = "A Film 3";

$XMLForm -> registerArray("films",$films);

if ($XMLForm -> isPosted()) {  
  doPost();
}

doGet( $XMLForm );

function doPost() {
  //Post stuff here
}

function doGet( $XMLForm  ) {
  $XMLForm -> item["screening_unique_key"] = "blah";
  $XMLForm -> item["screening_name"] = "hoho";
}   

$result = $XMLForm -> drawForm();
	  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="title" content="Constellation.tv Admin" />
  <title>Constellation.tv Admin</title>
  <link rel="shortcut icon" href="/favicon.ico" />
  
  <script type="text/javascript" src="/js/formvalidate_v6.js"></script> 
  <link rel="stylesheet" type="text/css" media="screen" href="/css/styroform.css" />
  
  </head>

  <body>
  
    <?php print($result["form"]);?>
    
  </body>
  
  
  </html>
