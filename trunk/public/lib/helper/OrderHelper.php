<?php

function getOrder($order_id) {
  
  throw new exception("Moved to Order Manager");
  
}

function setScreeningTicket( $obj, $code, $screening ) {
  $security = new TheaterSecurity_PageWidget( $obj -> context );
  $security -> setScreeningTicket( $code, $screening );
}

function getScreeningTicket( $obj, $screening ) {
  $security = new TheaterSecurity_PageWidget( $obj -> context );
  return $security -> getScreeningTicket( $screening );
}

function getUserAddressById( $id ) {
  //Check for email in database
  sfConfig::set("user_id",$id);
  //Update the SOLR Search Engine
  $data = new Utils_PageWidget();
  $address = $data -> dataMap( sfConfig::get('sf_lib_dir')."/widgets/OrderManager/query/User_Address_datamap.xml");
  
  return $address["data"][0];
}

//Create a 17-Digit Random
function getRandomUserId() {
  return timestamp();
  //$string = sfConfig::get("app_site_abbr")."-".sprintf("%06d",$order -> UserOrder -> getUserOrderId())."-".formatDate(null,"order");
  $start = rand(1,12);
  return  substr($number_in,$start,17);
}

//Create a 20-Char Random String
function setUserOrderGuid() {
  $number_in = timestamp()*rand(10000,99999);
  $string = BaseIntEncoder::encode($number_in);
  $number_in = timestamp()*rand(10000,99999);
  $string .= BaseIntEncoder::encode($number_in);
  $number_in = timestamp()*rand(10000,99999);
  $string .= BaseIntEncoder::encode($number_in);
  $number_in = timestamp()*rand(10000,99999);
  $string .= BaseIntEncoder::encode($number_in);
  //$string = sfConfig::get("app_site_abbr")."-".sprintf("%06d",$order -> UserOrder -> getUserOrderId())."-".formatDate(null,"order");
  $start = rand(1,12);
  
  $code = substr($string,$start,20);
  
  //Haha Hack four iterations
  if (strlen($code) == 0) {
    $code = setUserOrderGuid();
    if (strlen($code) == 0) {
      $code = setUserOrderGuid();
      if (strlen($code) == 0) {
        $code = setUserOrderGuid();
        if (strlen($code) == 0) {
          $code = setUserOrderGuid();
        }
      }
    }
  }
  
  return $code;
}

//Create a 10-Char Random String
function setUserOrderTicket() {
  $number_in = timestamp()*rand(10000,99999);
  $string = BaseIntEncoder::encode($number_in);
  $number_in = timestamp()*rand(10000,99999);
  $string .= BaseIntEncoder::encode($number_in);
  //$string = sfConfig::get("app_site_abbr")."-".sprintf("%06d",$order -> UserOrder -> getUserOrderId())."-".formatDate(null,"order");
  $start = rand(1,6);
  $code = substr($string,$start,10);
  
  //Haha Hack four iterations
  if (strlen($code) == 0) {
    $code = setUserOrderTicket();
    if (strlen($code) == 0) {
      $code = setUserOrderTicket();
      if (strlen($code) == 0) {
        $code = setUserOrderTicket();
        if (strlen($code) == 0) {
          $code = setUserOrderTicket();
        }
      }
    }
  }
  
  return $code;
}

//Create a 15-Char Random String
function setScreeningId() {
  $number_in = timestamp()*rand(10000,99999);
  $string = BaseIntEncoder::encode($number_in);
  $number_in = timestamp()*rand(10000,99999);
  $string .= BaseIntEncoder::encode($number_in);
  $number_in = timestamp()*rand(10000,99999);
  $string .= BaseIntEncoder::encode($number_in);
  //$string = sfConfig::get("app_site_abbr")."-".sprintf("%06d",$order -> UserOrder -> getUserOrderId())."-".formatDate(null,"order");
  $start = rand(1,12);
  
  $code = substr($string,$start,15);
  
  //Haha Hack four iterations
  if (strlen($code) == 0) {
    $code = setScreeningId();
    if (strlen($code) == 0) {
      $code = setScreeningId();
      if (strlen($code) == 0) {
        $code = setScreeningId();
        if (strlen($code) == 0) {
          $code = setScreeningId();
        }
      }
    }
  }
  
  return $code;
}

function getTicketTemplate( $shortname, $type ) {
	// @@@ THIS IS A HACK - NEED A BETTER WAY TO SKIN THESE
	if (file_exists(sfConfig::get("sf_lib_dir")."/widgets/MessageManager/".$type."_".$shortname."_html.template.php")) {
		return $type."_".$shortname."_html.template.php";
	} else {
		return $type . "_html.template.php";
	}
}

function getTicketTextTemplate( $shortname, $type ) {
	// @@@ THIS IS A HACK - NEED A BETTER WAY TO SKIN THESE
	if (file_exists(sfConfig::get("sf_lib_dir")."/widgets/MessageManager/".$type."_".$shortname."_text.template.php")) {
		return $type."_".$shortname."_text.template.php";
	} else {
		return $type . "_text.template.php";
	}
}

function getTicketSubject( $shortname, $name ) {
	// @@@ THIS IS A HACK - NEED A BETTER WAY TO SKIN THESE
	if ($shortname == "vow") {
		return "Your ticket to An Evening of Vows";
	} else {
		return  "Your ticket to ".$name;
	}
}

function sendOrderEmail( $user, $order, $item, $film, $context ) {
  
  $otz = date_default_timezone_get();
  //Do a temporary timezone conversion
  //Since these are sent as part of the client's browser process
  //Their timezone would muck up the Ticket
  date_default_timezone_set($film["data"][0]["screening_default_timezone_id"]);
  $mail_view = new sfPartialView($context, 'widgets', 'ticket_email', 'ticket_email' );
  $mail_view->getAttributeHolder()->add(array("user"=>$user,"film"=>$film["data"][0],"order"=>$order,"item"=>$item[0]));
  $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/" . getTicketTemplate($film["data"][0]["screening_film_short_name"], "Ticket");
  $mail_view->setTemplate($templateloc);
  $message = $mail_view->render();
  
  $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/" . getTicketTextTemplate($film["data"][0]["screening_film_short_name"], "Ticket");
  $mail_view->setTemplate($templateloc);
  $altbody = $mail_view->render();
  
  date_default_timezone_set($otz);
	
  //$recips[0]["email"] = "amadsen@gmail.com";
  $recips[0]["email"] = $user -> getUserEmail();
  $recips[0]["fname"] = $user -> getUserFname();
  $recips[0]["lname"] = $user -> getUserLname();
  $subject = getTicketSubject( $film["data"][0]["screening_film_short_name"], $film["data"][0]["screening_film_name"] );
  $mail = new WTVRMail( $context );
  $mail -> user_session_id = "user_id";
  $mail -> queueMessage("blank",$subject,$message,$altbody,sfConfig::get("app_mail_from_address"),sfConfig::get("app_mail_from_fname"),sfConfig::get("app_mail_from_lname"),$recips);
  
  return $message;
}

function sendReminderEmail( $user, $item, $screen, $context ) {
  
	$mail_view = new sfPartialView($context, 'widgets', 'reminder', 'reminder' );
  $mail_view->getAttributeHolder()->add(array("user"=>$user,"item"=>$item,"screening"=>$screen));
  $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/" . getTicketTemplate($screen["screening_film_short_name"], "Reminder");
  $mail_view->setTemplate($templateloc);
  $message = $mail_view->render();
  
  $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/" . getTicketTextTemplate($screen["screening_film_short_name"], "Reminder");
  $mail_view->setTemplate($templateloc);
  $altbody = $mail_view->render();
  
  //$recips[0]["email"] = "amadsen@gmail.com";
  $recips[0]["email"] = $user -> getUserEmail();
  $recips[0]["fname"] = $user -> getUserFname();
  $recips[0]["lname"] = $user -> getUserLname();
  $subject = "Your Showtime of ".$screen["screening_film_name"]." Starts Soon";
  $mail = new WTVRMail( $context );
  $mail -> queueMessage("blank",$subject,$message,$altbody,sfConfig::get("app_mail_from_address"),sfConfig::get("app_mail_from_fname"),sfConfig::get("app_mail_from_lname"),$recips);
  
}

function sendPostScreeningEmail( $user, $item, $screen, $context ) {
  
  $mail_view = new sfPartialView($context, 'widgets', 'post-screening', 'post-screening' );
  $mail_view->getAttributeHolder()->add(array("user"=>$user,"item"=>$item,"screening"=>$screen));
  $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/" . getTicketTemplate($screen["screening_film_short_name"], "PostScreening");
  $mail_view->setTemplate($templateloc);
  $message = $mail_view->render();
  
  $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/" . getTicketTextTemplate($screen["screening_film_short_name"], "PostScreening");
  $mail_view->setTemplate($templateloc);
  $altbody = $mail_view->render();
  
  //$recips[0]["email"] = "amadsen@gmail.com";
  $recips[0]["email"] = $user -> getUserEmail();
  $recips[0]["fname"] = $user -> getUserFname();
  $recips[0]["lname"] = $user -> getUserLname();
  $subject = "Thanks for Watching ".$screen["screening_film_name"];
  $mail = new WTVRMail( $context );
  $mail -> queueMessage("blank",$subject,$message,$altbody,sfConfig::get("app_mail_from_address"),sfConfig::get("app_mail_from_fname"),sfConfig::get("app_mail_from_lname"),$recips);
  
}

function sendHostEmail( $user, $order, $item, $film, $ticket, $context ) {

  $mail_view = new sfPartialView($context, 'widgets', 'ticket_email', 'ticket_email' );
  $mail_view->getAttributeHolder()->add(array("user"=>$user,"film"=>$film["data"][0],"order"=>$order,"item"=>$item[0],"ticket"=>$ticket));
  $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/" . getTicketTemplate($film["data"][0]["film_short_name"], "Host");
  $mail_view->setTemplate($templateloc);
  $message = $mail_view->render();
  
  $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/Host_text.template.php";
  $mail_view->setTemplate($templateloc);
  $altbody = $mail_view->render();
  
  //$recips[0]["email"] = "amadsen@gmail.com";
  $recips[0]["email"] = $user -> getUserEmail();
  $recips[0]["fname"] = " ";
  $recips[0]["lname"] = " ";
  $subject = "Your ticket to ".$film["data"][0]["film_name"];
  $mail = new WTVRMail( $context );
  $mail -> user_session_id = "user_id";
  $mail -> queueMessage("blank",$subject,$message,$altbody,sfConfig::get("app_mail_from_address"),sfConfig::get("app_mail_from_fname"),sfConfig::get("app_mail_from_lname"),$recips);
  
}

function getSite() {
  return SitePeer::retrieveByPK( sfConfig::get("app_site_id") );
}

//Much information on CC's is here:
//http://www.merriampark.com/anatomycc.htm
function ccMask( $value ) {
  if ($value == "") {
    return "";
  }
  return str_repeat("*",strlen(decrypt($value)) -4).right(decrypt($value),4);
}

function ccexpMask( $value ) {
  if ($value == "") {
    return "";
  }
  return left(sprintf("%04d",$value),2)."/".right(sprintf("%04d",$value),2);
}

/*
* Visa: ^4[0-9]{12}(?:[0-9]{3})?$ All Visa card numbers start with a 4. New cards have 16 digits. Old cards have 13.
* MasterCard: ^5[1-5][0-9]{14}$ All MasterCard numbers start with the numbers 51 through 55. All have 16 digits.
* American Express: ^3[47][0-9]{13}$ American Express card numbers start with 34 or 37 and have 15 digits.
* Diners Club: ^3(?:0[0-5]|[68][0-9])[0-9]{11}$ Diners Club card numbers begin with 300 through 305, 36 or 38. All have 14 digits. There are Diners Club cards that begin with 5 and have 16 digits. These are a joint venture between Diners Club and MasterCard, and should be processed like a MasterCard.
* Discover: ^6(?:011|5[0-9]{2})[0-9]{12}$ Discover card numbers begin with 6011 or 65. All have 16 digits.
* JCB: ^(?:2131|1800|35\d{3})\d{11}$ JCB cards beginning with 2131 or 1800 have 15 digits. JCB cards beginning with 35 have 16 digits. 
* For more, go here:
* http://en.wikipedia.org/wiki/Bank_card_number* 
*/
function cctypeMask( $value ) {
  if (preg_match("/^4[0-9]{12}(?:[0-9]{3})?$/",$value))
    return "Visa";  
  if (preg_match("/^5[1-5][0-9]{14}$/",$value))
    return "Master Card";
  if (preg_match("/^3[47][0-9]{13}$/",$value))
    return "American Express";
  if (preg_match("/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/",$value))
    return "Diners Club";
  if (preg_match("/^6(?:011|5[0-9]{2})[0-9]{12}$/",$value))
    return "Discover";
  if (preg_match("/^(?:2131|1800|35\d{3})\d{11}$/",$value))
    return "JCB";
  
  return "Not A Valid";
}

/*
* Visa: ^4[0-9]{12}(?:[0-9]{3})?$ All Visa card numbers start with a 4. New cards have 16 digits. Old cards have 13.
* MasterCard: ^5[1-5][0-9]{14}$ All MasterCard numbers start with the numbers 51 through 55. All have 16 digits.
* American Express: ^3[47][0-9]{13}$ American Express card numbers start with 34 or 37 and have 15 digits.
* Diners Club: ^3(?:0[0-5]|[68][0-9])[0-9]{11}$ Diners Club card numbers begin with 300 through 305, 36 or 38. All have 14 digits. There are Diners Club cards that begin with 5 and have 16 digits. These are a joint venture between Diners Club and MasterCard, and should be processed like a MasterCard.
* Discover: ^6(?:011|5[0-9]{2})[0-9]{12}$ Discover card numbers begin with 6011 or 65. All have 16 digits.
* JCB: ^(?:2131|1800|35\d{3})\d{11}$ JCB cards beginning with 2131 or 1800 have 15 digits. JCB cards beginning with 35 have 16 digits. 
* For more, go here:
* http://en.wikipedia.org/wiki/Bank_card_number* 
*/
function cvv2Mask( $value, $cvv2 ) {
  if (preg_match("/^4[0-9]{12}(?:[0-9]{3})?$/",$value))
    return sprintf("%03d",$cvv2);  
  if (preg_match("/^5[1-5][0-9]{14}$/",$value))
    return sprintf("%03d",$cvv2);  
  if (preg_match("/^3[47][0-9]{13}$/",$value))
    return sprintf("%04d",$cvv2);
  if (preg_match("/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/",$value))
    return sprintf("%03d",$cvv2);
  if (preg_match("/^6(?:011|5[0-9]{2})[0-9]{12}$/",$value))
    return sprintf("%03d",$cvv2);
  if (preg_match("/^(?:2131|1800|35\d{3})\d{11}$/",$value))
    return sprintf("%03d",$cvv2);
  if ($value = "371449635398431")
    return sprintf("%03d",$cvv2);
    
  //return "Not A Valid";
  return "";
}
?>
