<?php
header('Content-type: text/css');

include("../../prefs.php");

$icons = array("gif"=>"gificon.gif",
                "jpg"=>"jpgicon.gif",
                "jpeg"=>"jpgicon.gif",
                "bmp"=>"fileicon.gif",
                "png"=>"pngicon.gif",

                "mp3"=>"fileicon.gif",
                "mov"=>"fileicon.gif",
                "aif"=>"fileicon.gif",
                "aiff"=>"fileicon.gif",
                "wav"=>"fileicon.gif",
                "swf"=>"fileicon.gif",
                "mpg"=>"fileicon.gif",
                "avi"=>"fileicon.gif",
                "mpeg"=>"fileicon.gif",
                "mid"=>"fileicon.gif",
                
                "html"=>"htmlicon.gif",
                "htm"=>"htmlicon.gif",
                "txt"=>"texticon.gif",
                "css"=>"fileicon.gif",
                
                "php"=>"fileicon.gif",
                "php3"=>"fileicon.gif",
                "php4"=>"fileicon.gif",
                "asp"=>"fileicon.gif",
                "js"=>"fileicon.gif",
                
                "pdf"=>"pdficon.gif",
                "doc"=>"word2icon.gif",
                "ppt"=>"ppticon.gif",
                "zip"=>"zipicon.gif",
                "sit"=>"fileicon.gif",
                "rar"=>"fileicon.gif",
                "rm"=>"fileicon.gif",
                "ram"=>"fileicon.gif"
                );
                

$allFiles = array(); 
for($i=0; $i<count($imgTypes); $i++){
	array_push($allFiles,$imgTypes[$i]);	
}	
for($i=0; $i<count($embedTypes); $i++){
	array_push($allFiles,$embedTypes[$i]);	
}	
for($i=0; $i<count($htmlTypes); $i++){
	array_push($allFiles,$htmlTypes[$i]);	
}	
for($i=0; $i<count($phpTypes); $i++){
	array_push($allFiles,$phpTypes[$i]);	
}
for($i=0; $i<count($miscTypes); $i++){
	array_push($allFiles,$miscTypes[$i]);	
}	
              
function addArray(&$array, $key, $val)
{
   $tempArray = array($key => $val);
   $array = array_merge ($array, $tempArray);
}

// add the default fileicon.gif icon for any file type thatr doesn't have an 
// icon set in the list above
for($i = 0; $i<count($allFiles);$i++){
	if(!isset($icons[$allFiles[$i]])){
		addArray($icons,$allFiles[$i], "unknownicon.gif");
	}	
}
                
                
foreach($icons as $key => $value){
    echo "
li.file.icon_$key{ background:#DEDEDE url(\"/images/icons/$value\") 2px 2px no-repeat; }
li.file_open.icon_$key{ background:#DDC url(\"/images/icons/$value\") 2px 2px no-repeat; }
";
}


?> 