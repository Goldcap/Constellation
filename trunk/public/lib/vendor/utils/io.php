<?php


function dump($var) {
	echo "
	<TABLE>
		<TR>
			<TD align='left'>
	<pre>";
	var_dump($var);
	echo "</pre>
	</TD>
		</TR>
	</TABLE>";
	die();
	}

function kickdump($var) {
	echo "
	<TABLE>
		<TR>
			<TD align='left'>
	<pre>";
	var_dump($var);
	echo "</pre>
	</TD>
		</TR>
	</TABLE>";
	}

function debugMail( $subject=false ) {
  
  if (! $subject)
    $subject = "Debug Mail ".now();
    
  $mailer = new PHPMailer();
  $mailer -> IsSendmail();
  $mailer -> Host = "localhost";
  $mailer -> Encoding = "quoted-printable";
  $mailer -> From = "webmaster@".$_SERVER["SERVER_NAME"];
  $mailer -> FromName = "Admin on ".$_SERVER["SERVER_NAME"];
  $mailer -> AddAddress ("amadsen@gmail.com");
  $mailer -> IsHTML(true);
  $mailer -> Subject = $subject;
  
  $message_body_test .= "========================================================\n";
  $message_body_test .= "============     CLIENT DEBUG INFO      ================\n";
  $message_body_test .= "========================================================\n\n";
  
  if (isset($_SESSION)) {
  foreach($_SESSION as $key => $value) {
    $message_body_test .= $key . " = '" . $value . "'\n";
  }}
  
  foreach($_POST as $key => $value) {
    $message_body_test .= $key . " = '" . $value . "'\n";
  }
  
  foreach($_GET as $key => $value) {
    $message_body_test .= $key . " = '" . $value . "'\n";
  }
  
  foreach($_SERVER as $key => $value) {
    $message_body_test .= $key . " = '" . $value . "'\n";
  }
  
  foreach($_COOKIE as $key => $value) {
    $message_body_test .= $key . " = '" . $value . "'\n";
  }
  
  putLog( $message_body_test );
  
  $mailer -> Body = nl2br($message_body_test);
  $mailer -> Send(); 
}

?>
