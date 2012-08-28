<?php
require_once (dirname(__FILE__).'/../lib/vendor/utils.php');
require_once (dirname(__FILE__).'/../lib/vendor/phpmailer/class.phpmailer.php'); 
require_once (dirname(__FILE__).'/../lib/vendor/PageWidgets/UtilWidget.class.php');
require_once (dirname(__FILE__).'/../lib/vendor/wtvr/1.3/WTVRMail.php');
require_once (dirname(__FILE__).'/../lib/vendor/wtvr/1.3/WTVRUtils.php');
dump($_SERVER);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1250">
  <meta name="generator" content="PSPad editor, www.pspad.com">
  <title></title>
  </head>
  <body>
		
		<?php
			//ob_start();
			
			date_default_timezone_set("Europe/Berlin");
			$tz = date_default_timezone_get();
			$newdate = "2012-01-13T02:00-1:00";
			//$tz = "America/New_York";
		  $newdate = formatDate($newdate,"W3XMLOUT",$tz);
		  echo "RAW::".$newdate."<br />";
		  echo "SYSTEM TIMEZONE::".$zo."<br />";
		  echo "FORMAT TIMEZONE::".$tz."<br />";
		  echo "DATE::".formatDate($newdate,"MDY")."<br />";
		  echo "TIME::".date("H:i A",strtotime($newdate))."<br />";
		  
			//$out2 = ob_get_contents();

			//ob_end_clean();
			
			//QAMail($out2,"Time Out Mail",false,"amadsen@gmail.com");
			//echo $out2;
		?>
		
  </body>
</html>
