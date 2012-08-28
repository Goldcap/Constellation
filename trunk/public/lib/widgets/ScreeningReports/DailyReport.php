<?php

error_reporting(0);

$array = null;
$film = FilmPeer::retrieveByPk( $this -> getVar("id") );

$args["filename"] = cleanFileName($film -> getFilmShortName().'_FilmPayments_'.date("mdY-hi")).'.xls';
$args["location"] = sfConfig::get("sf_data_dir")."/exports";
createDirectory($args["location"]);

// We give the path to our file here
$workbook = new Spreadsheet_Excel_Writer($args["location"]."/".$args["filename"]);
$worksheet = $workbook->addWorksheet("Daily Usage Report");
$worksheet->freezePanes(array(3, 0));

//Get some formats ready!
$format_bold =& $workbook->addFormat();
$format_bold->setBold();

$currency_format =& $workbook->addFormat();
$currency_format->setNumFormat('$0.00;[Red]($0.00)');
$currency_format->setHAlign('right');

$percent_format =& $workbook->addFormat();
$percent_format->setNumFormat('0.00%');

$total_format =& $workbook->addFormat();
$total_format->setNumFormat('$0.00;[Red]($0.00)');
$total_format->setColor("green");

$title_format =& $workbook->addFormat();
$title_format->setFgColor("yellow");
$title_format->setBold();

$mgr_format =& $workbook->addFormat();
$mgr_format->setFgColor(27);
$mgr_format->setBold();

$academy_format =& $workbook->addFormat();
$academy_format->setFgColor(18);
$academy_format->setBold();

$avg_format =& $workbook->addFormat();
$avg_format->setFgColor(29);
$avg_format->setBold();

$tot_format =& $workbook->addFormat();
$tot_format->setFgColor(31);
$tot_format->setBold();

//Column Widths
$worksheet->setColumn(0,0,40);
$worksheet->setColumn(1,1,40);

$j=0;
$worksheet->write($j,0,$film -> getFilmName() . "",$title_format);
$worksheet->write($j,1,"",$title_format);
$j++;
$worksheet->write($j,0,"Daily Reporting",$title_format);
$worksheet->write($j,1,"",$title_format);
$j++;
$worksheet->write($j,0,"",$title_format);
$worksheet->write($j,1,"",$title_format);
$j++;
$j++;

            
//***************************
//     PURCHASE ITEMS
//***************************
$worksheet->write($j,0,"PURCHASES",$format_bold);
$j++;
      
if ($film -> getFilmUseSponsorCodes() == 1) {
  $worksheet->write($j,0,"Total Codes Used");
} else {
  $worksheet->write($j,0,"'Watch Now' Screenings Sold");
  $worksheet->write($j,1,"Line 6 = (Non-Zero Payments");
}
$j++;

$worksheet->write($j,0,"'Create A Showtime' Screenings Sold");
$worksheet->write($j,1,"Line 7 = (Non-Zero Payments)");
$j++;

if ($film -> getFilmUseSponsorCodes() == 1) {
  $worksheet->write($j,0,"Screening By Demand (Code Uses)");
} else {
  $worksheet->write($j,0,"'Highlight' Tickets Sold");
  $worksheet->write($j,1,"Line 8 = 'Highlight' Tickets Sold");
}
$j++;

if ($film -> getFilmUseSponsorCodes() == 0) {
  $worksheet->write($j,0,"'Featured' Tickets Sold"); 
  $worksheet->write($j,1,"Line 9 = 'Featured' Tickets Sold");
  $j++;
}

if ($film -> getFilmUseSponsorCodes() == 0) {
  $worksheet->write($j,0,"'Featured and Highlighted' Tickets Sold"); 
  $worksheet->write($j,1,"Line 10 = 'Featured' and 'Highlighted' Tickets Sold");
  $j++;
}

if ($film -> getFilmUseSponsorCodes() == 0) {
  $worksheet->write($j,0,"'Regular' Tickets Sold");
  $worksheet->write($j,1,"Line 11 = 'Regular' Tickets Sold");
  $j++;
}

$worksheet->write($j,0,"Hosted Tickets"); 
$worksheet->write($j,1,"Line 12 = Hosted Tickets");
$j++;

$worksheet->write($j,0,"Total Tickets Sold");
$worksheet->write($j,1,"Line 13 = Total Tickets Sold");
$j++; 

//Line 14 
$j++;

//***************************
//     ATTENDANCE ITEMS
//***************************
//Line 15
$worksheet->write($j,0,"ATTENDANCE",$format_bold);
$j++;

if ($film -> getFilmUseSponsorCodes() == 1) {
  $worksheet->write($j,0,"Total Seats Occupied (Includes User Re-Entry)");
} else {
  $worksheet->write($j,0,"'Watch Now' Screenings Attended");
  $worksheet->write($j,1,"Line 16 = 'Watch Now' Screenings Attended");
}
$j++;

if ($film -> getFilmUseSponsorCodes() == 0) {
  $worksheet->write($j,0,"'Create A Showtime' Screenings Attended");    
  $worksheet->write($j,1,"Line 17 = 'Create A Showtime' Screenings Attended");
  $j++;
}

$worksheet->write($j,0,"'Highlight' Showtime Attendance");
$worksheet->write($j,1,"Line 18 = How Many People Attended Highlight Showtimes");
$j++;

$worksheet->write($j,0,"'Featured' Showtime Attendance");
$worksheet->write($j,1,"Line 19 = How Many People Attended Featured Showtimes");
$j++;

$worksheet->write($j,0,"'Featured and Highlighted' Showtime Attendance");
$worksheet->write($j,1,"Line 20 = How Many People Attended Featured and Highlighted Showtimes");
$j++;

$worksheet->write($j,0,"'Regular' Showtime Attendance");
$worksheet->write($j,1,"Line 21 = How Many People Attended Non-Highlighted and Non-Featured Showtimes");
$j++;

$worksheet->write($j,0,"Hosted Showtimes");
$worksheet->write($j,1,"Line 22 = How Many People Attended Non-Hosted and Non-Featured Showtimes");
$j++;

$worksheet->write($j,0,"Total Attendance");
$worksheet->write($j,1,"Line 23 = How Many People Attended Showtimes");
$j++;

$worksheet->write($j,0,"Total Showtimes");
$worksheet->write($j,1,"Line 24 = How Many People Attended Showtimes");
$j++;

$worksheet->write($j,0,"Average Attendance");
$worksheet->write($j,1,"Line 25 = How Many People Attended Showtimes");
$j++;

//Line 26
$j++;

//***************************
//     VIRALITY/SHARING
//***************************
//Line 27
$worksheet->write($j,0,"VIRALITY/SHARING",$format_bold);
$j++;

$worksheet->write($j,0,"Facebook Invitations Sent by Users"); 
$worksheet->write($j,1,"Line 28");
$j++;
$worksheet->write($j,0,"Email Invitations Sent by Users");   
$worksheet->write($j,1,"Line 29");
$j++;

$worksheet->write($j,0,"Facebook Invitations Sent by Hosts"); 
$worksheet->write($j,1,"Line 30");
$j++;
$worksheet->write($j,0,"Email Invitations Sent by Hosts");
$worksheet->write($j,1,"Line 31");
$j++;

$worksheet->write($j,0,"#FB Posts from Film Page");
$worksheet->write($j,1,"Line 32");
$j++;
$worksheet->write($j,0,"#Tweets from Film Page");
$worksheet->write($j,1,"Line 33");
$j++;

//Line 34
$j++;

if ($film -> getFilmUseSponsorCodes() != 1) {
  
  //***************************
  //     TICKET PRICING 
  //***************************
  //Line 35
  $worksheet->write($j,0,"TICKET PRICING",$format_bold);
  $j++;

  $worksheet->write($j,0,"Highlight Showtime Price");       
  $worksheet->write($j,1,"Line 36 = ");
  $worksheet->write($j,2,$film -> getFilmTicketPrice(),$currency_format);
  $j++;
  
  $worksheet->write($j,0,"Featured Showtime Price");          
  $worksheet->write($j,1,"Line 37 = ");   
  $worksheet->write($j,2,$film -> getFilmTicketPrice(),$currency_format);
  $j++;
  
  $worksheet->write($j,0,"Watch Now Price");         
  $worksheet->write($j,1,"Line 38 = ");          
  $worksheet->write($j,2,$film -> getFilmSetupPrice(),$currency_format);
  $j++;
  
  $worksheet->write($j,0,"Create A Showtime Price");        
  $worksheet->write($j,1,"Line 39 = ");     
  $worksheet->write($j,2,$film -> getFilmSetupPrice(),$currency_format);
  $j++; 
  
  $worksheet->write($j,0,"Ticket Price");        
  $worksheet->write($j,1,"Line 40 = ");     
  $worksheet->write($j,2,$film -> getFilmTicketPrice(),$currency_format);
  $j++;
  
  //Line 41
  $j++;
  
  //***************************
  //     DAILY SALES
  //***************************
  //Line 42
  $worksheet->write($j,0,"DAILY SALES",$format_bold); 
  $worksheet->write($j,0,"Purchases x Ticket Price",$format_bold);
  $j++;
  
  $worksheet->write($j,0,"Watch Now");  
  $worksheet->write($j,1,"Line 43 = ");     
  $j++;
  
  $worksheet->write($j,0,"Create a Showtime");  
  $worksheet->write($j,1,"Line 44 = ");  
  $j++;
  
  $worksheet->write($j,0,"Highlight Showtimes");  
  $worksheet->write($j,1,"Line 45 = "); 
  $j++;
  
  $worksheet->write($j,0,"Featured Showtimes");  
  $worksheet->write($j,1,"Line 46 = "); 
  $j++;
  
  $worksheet->write($j,0,"Featured and Highlighted Showtimes");  
  $worksheet->write($j,1,"Line 47 = "); 
  $j++;
  
  $worksheet->write($j,0,"Regular Showtimes");  
  $worksheet->write($j,1,"Line 48 = "); 
  $j++;
  
  $worksheet->write($j,0,"User Hosted Showtimes Showtimes");  
  $worksheet->write($j,1,"Line 49 = "); 
  $j++;
  
  $worksheet->write($j,0,"Total Gross Revenue");  
  $worksheet->write($j,1,"Line 50 = "); 
  $j++;
  
  $worksheet->write($j,0,"Applied Discounts");  
  $worksheet->write($j,1,"Line 51 = "); 
  $j++;
  
  $worksheet->write($j,0,"Total Net Revenue");  
  $worksheet->write($j,1,"Line 52 = "); 
  $j++;
  
  //***************************
  //     REVENUE AND SPLITS
  //***************************
  //Line 53
  $worksheet->write($j,0,"REVENUE AND SPLITS",$format_bold);
  $j++;
  
  $worksheet->write($j,0,"% to Rights Holder");  
  $worksheet->write($j,1,"Line 54 = "); 
  $j++;
  
  $worksheet->write($j,0,"Total Paypal Receipts");  
  $worksheet->write($j,1,"Line 55 = "); 
  $j++;
  
  $worksheet->write($j,0,"Total Paypal Refunds");  
  $worksheet->write($j,1,"Line 56 = "); 
  $j++;
  
  $worksheet->write($j,0,"Total Paypal Transaction Charges");  
  $worksheet->write($j,1,"Line 57 = "); 
  $j++;
  
  $worksheet->write($j,0,"Net PayPal Receipts");  
  $worksheet->write($j,1,"Line 58 = "); 
  $j++;
  
  //Line 59
  $j++;
  
  $worksheet->write($j,0,"Revenue Share to Rights Holder");
  $worksheet->write($j,1,"Line 60 = "); 
  $j++;
  
  //Line 61
  $j++;      
  //Line 62
  $j++;
    
  $worksheet->write($j,0,"Revenue to Constellation");
  $worksheet->write($j,1,"Line 63 = "); 
  $j++;
  
  //Line 64
  $j++;
  
  //Line 65
  $worksheet->write($j,0,"OTHER PAYMENTS",$bold_format);
  $worksheet->write($j,1,"Line 65 = "); 
  $j++;
  
  //Line 66
  $j++; 
  
  /*
  //***************************
  //     GROSS REVENUE
  //***************************
  //Line 67
  $worksheet->write($j,0,"GROSS REVENUE",$format_bold);
  $worksheet->write($j,0,"Attendance x Ticket Price",$format_bold);
  $j++;
  
  $worksheet->write($j,0,"Watch Now");  
  $worksheet->write($j,1,"Line 68 = ");     
  $j++;
  
  $worksheet->write($j,0,"Create a Showtime");  
  $worksheet->write($j,1,"Line 69 = ");  
  $j++;
  
  $worksheet->write($j,0,"Highlight Showtimes");  
  $worksheet->write($j,1,"Line 70 = "); 
  $j++;
  
  $worksheet->write($j,0,"Featured Showtimes");  
  $worksheet->write($j,1,"Line 71 = "); 
  $j++;
  
  $worksheet->write($j,0,"Featured and Highlighted Showtimes");  
  $worksheet->write($j,1,"Line 72 = "); 
  $j++;
  
  $worksheet->write($j,0,"Regular Showtimes");  
  $worksheet->write($j,1,"Line 73 = "); 
  $j++;
  
  $worksheet->write($j,0,"User Hosted Showtimes Showtimes");  
  $worksheet->write($j,1,"Line 74 = "); 
  $j++;
  
  $worksheet->write($j,0,"Total Gross Revenue");  
  $worksheet->write($j,1,"Line 75 = "); 
  $j++;
  
  //Line 76
  $j++;
  */
  
}

//$start = "2011-11-11";
//$end = "2011-11-15";
$start = $this -> postVar("startdate");
$end = $this -> postVar("enddate");

$datestart = date('Y-m-d',strtotime('sunday',strtotime($start . " 00:00:00"))); 
$dateend = date('Y-m-d',strtotime('sunday',strtotime($end . " 23:59:59")));

$days = ((strtotime($end) - strtotime($start)) / 86400 + 1);

for ($k=0;$k<$days;$k++) {

  $bdate = "'".date('Y-m-d 00:00:00',strtotime($start) + ($k * 86400))."'";
  $edate = "'".date('Y-m-d 23:59:59',strtotime($start) + ($k * 86400))."'";
  
  $i= 2 + $k;
  $j=0;
 
  $worksheet->setColumn($i,$i,20);
  $worksheet->write($j,$i,"",$title_format);
  //Line 1
  $j++;
  $worksheet->write($j,$i,formatDate(strtotime($start) + ($k * 86400),"MDY-"),$title_format);
  //Line 2
  $j++;
  $worksheet->write($j,$i,"",$title_format);
  //Line 3
  $j++;
  //Line 4
  $j++;
  
  //Line 5
  $worksheet->write($j,$i,"");
  $j++;
  
  
  //***************************
  //     PURCHASE ITEMS
  //***************************
  
  //Watch Now Tickets Sold, (User hosts own screening)
  //This is for Sponsored Screenings too
  //Line 6
  if ($film -> getFilmUseSponsorCodes() == 1) {
    $bp = null;
    $sql = "select count(distinct(payment_id))
            from payment
            inner join `user`
            on fk_user_id = user.user_id
            inner join screening
            on payment.fk_screening_id = screening.screening_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and payment.fk_film_id = ".$film -> getFilmId()."
            and payment_type = 'screening'
            and screening.screening_type = 3
            and screening.fk_host_id = payment.fk_user_id
            and payment_order_processor != 'Admin'
            and payment_status = 2
            and payment_created_at >= ".$bdate."
            and payment_created_at <= ".$edate.";";
    //kickdump($sql);
    $brs = $this -> propelQuery($sql);
    $bp = $brs -> fetchAll();
  } else {
    $bp = null;
    $sql = "select count(distinct(payment_id))
            from payment
            inner join `user`
            on fk_user_id = user.user_id
            inner join screening
            on payment.fk_screening_id = screening.screening_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and payment.fk_film_id = ".$film -> getFilmId()."
            and payment_type = 'screening'
            and screening.screening_type = 3
            and screening.fk_host_id = payment.fk_user_id
            and payment_order_processor != 'Admin'
            and payment_status = 2
            and payment_created_at >= ".$bdate."
            and payment_created_at <= ".$edate.";";
    $brs = $this -> propelQuery($sql);
    $bp = $brs -> fetchAll();
  }
  if ($bp[0][0] != null) {  
    $p7 = $bp[0][0];
    $worksheet->write($j,$i,$bp[0][0]);
  } else {              
    $p7 = 0;
    $worksheet->write($j,$i,0);
  }
  $j++;
  
  //Create A Showtime Screenings Sold
  //Line 7
  if ($film -> getFilmUseSponsorCodes() == 1) {
    $ap[0][0] = "N/A";
  } else {
    $ap = null;
    $sql = "select count(distinct(payment_id))
              from payment
              inner join `user`
              on payment.fk_user_id = user.user_id
              where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
              and fk_film_id = ".$film -> getFilmId()."
              and payment_type = 'host'
              and payment_status = 2
              and payment_created_at >= ".$bdate."
              and payment_created_at <= ".$edate."
              and payment_amount > 0;";
    $ars = $this -> propelQuery($sql);
    $ap = $ars -> fetchAll();
  }
  $p6 = $ap[0][0];
  $worksheet->write($j,$i,$ap[0][0]);
  $j++;
  //kickdump($sql);
  
   
  //Highlight Showtime Tickets Sold
  //Line 8
  if ($film -> getFilmUseSponsorCodes() == 1) {
    $cp[0][0] = "N/A";
  } else {
    $cp = null;
    $sql = "select count(distinct(payment.payment_id))
            from payment
            inner join user
            on payment.fk_user_id = user.user_id
            inner join screening
            on payment.fk_screening_id = screening.screening_id
            inner join payment p1
            on p1.fk_screening_id = screening.screening_id
            where payment.fk_film_id = ".$film -> getFilmId()."
            and user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and payment.payment_type = 'screening'
            and screening_highlighted = 1
            and screening_featured = 0
            and payment.payment_created_at >= ".$bdate."
            and payment.payment_created_at <= ".$edate."
            and screening_status = 2
            and payment.payment_status = 2
            and ((p1.payment_type = 'host'
            and p1.payment_order_processor = 'Admin')
            or (p1.payment_id is null));";
    $crs = $this -> propelQuery($sql);
    $cp = $crs -> fetchAll();
  }
  if ($cp[0][0] != null) {
    $p8 = $cp[0][0];
    $worksheet->write($j,$i,$cp[0][0]);
  } else {
    $p8 = 0;
    $worksheet->write($j,$i,0);
  }
  $j++;
  
  //Featured Showtime Tickets Sold
  //These are showtimes created by the admin
  //Line 9
  if ($film -> getFilmUseSponsorCodes() == 1) {
     $dp = null;
     $sql = "select count(distinct(payment.payment_id))
            from payment
            inner join user
            on payment.fk_user_id = user.user_id
            inner join screening
            on payment.fk_screening_id = screening.screening_id
            inner join payment p1
            on p1.fk_screening_id = screening.screening_id
            where payment.fk_film_id = ".$film -> getFilmId()." 
            and user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and payment.payment_type = 'screening'
            and (screening_highlighted is null
                 or screening_highlighted = 0)
            and screening_featured = 1
            and payment.payment_created_at >= ".$bdate."
            and payment.payment_created_at <= ".$edate."
            and screening_status = 2
            and payment.payment_status = 2
            and ((p1.payment_type = 'host'
            and p1.payment_order_processor = 'Admin')
            or (p1.payment_id is null));";
    $drs = $this -> propelQuery($sql);
    $dp = $drs -> fetchAll();
    
  } else {
    $dp = null;
    $sql = "select count(distinct(payment.payment_id))
            from payment
            inner join user
            on payment.fk_user_id = user.user_id
            inner join screening
            on payment.fk_screening_id = screening.screening_id
            inner join payment p1
            on p1.fk_screening_id = screening.screening_id
            where payment.fk_film_id = ".$film -> getFilmId()." 
            and user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and payment.payment_type = 'screening'
            and (screening_highlighted is null
                 or screening_highlighted = 0)
            and screening_featured = 1
            and payment.payment_created_at >= ".$bdate."
            and payment.payment_created_at <= ".$edate."
            and screening_status = 2
            and payment.payment_status = 2
            and ((p1.payment_type = 'host'
            and p1.payment_order_processor = 'Admin')
            or (p1.payment_id is null));";
    $drs = $this -> propelQuery($sql);
    $dp = $drs -> fetchAll();
    
  }
  
  if ($dp[0][0] != null) {
    $p9 = $dp[0][0];
    $worksheet->write($j,$i,$dp[0][0]);
  } else {
    $p9 = 0;
    $worksheet->write($j,$i,0);
  }   
  $j++;
  
  //Featured and Highlighted Showtime Tickets Sold
  //These are showtimes created by the admin
  //Line 10
  if ($film -> getFilmUseSponsorCodes() == 1) {
    $ep = null;
    $sql = "select count(distinct(payment.payment_id))
            from payment
            inner join user
            on payment.fk_user_id = user.user_id
            inner join screening
            on payment.fk_screening_id = screening.screening_id   
            left join payment p1
            on p1.fk_screening_id = screening.screening_id       
            where payment.fk_film_id = ".$film -> getFilmId()." 
            and user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and payment.payment_type = 'screening'
            and screening_highlighted = 1
            and screening_featured = 1
            and payment.payment_created_at >= ".$bdate."
            and payment.payment_created_at <= ".$edate."
            and screening_status = 2
            and payment.payment_status = 2
            and ((p1.payment_type = 'host'
            and p1.payment_order_processor = 'Admin')
            or (p1.payment_id is null));";
    $ers = $this -> propelQuery($sql);
    $ep = $ers -> fetchAll();
  } else {
    $ep = null;
    $sql = "select count(distinct(payment.payment_id))
            from payment
            inner join user
            on payment.fk_user_id = user.user_id
            inner join screening
            on payment.fk_screening_id = screening.screening_id   
            left join payment p1
            on p1.fk_screening_id = screening.screening_id       
            where payment.fk_film_id = ".$film -> getFilmId()." 
            and user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and payment.payment_type = 'screening'
            and screening_highlighted = 1
            and screening_featured = 1
            and payment.payment_created_at >= ".$bdate."
            and payment.payment_created_at <= ".$edate."
            and screening_status = 2
            and payment.payment_status = 2
            and ((p1.payment_type = 'host'
            and p1.payment_order_processor = 'Admin')
            or (p1.payment_id is null));";
    $ers = $this -> propelQuery($sql);
    $ep = $ers -> fetchAll();
  }
  
  if ($ep[0][0] != null) {
    $p10 = $ep[0][0];
    $worksheet->write($j,$i,$ep[0][0]);
  } else {
    $p10 = 0;
    $worksheet->write($j,$i,0);
  }   
  $j++;
  
  //Regular Showtime Tickets Sold 
  //These are showtimes created by the admin
  //Line 11
  if ($film -> getFilmUseSponsorCodes() == 1) {
    $fp = null;
    $sql = "select count(distinct(payment.payment_id))
            from payment
            inner join user
            on payment.fk_user_id = user.user_id
            inner join screening
            on payment.fk_screening_id = screening.screening_id
            inner join payment p1
            on p1.fk_screening_id = screening.screening_id
            and p1.payment_type = 'host'
            and p1.payment_order_processor = 'Admin'
            where payment.fk_film_id = ".$film -> getFilmId()."
            and user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and payment.payment_type = 'screening'
            and (screening_highlighted is null
                 or screening_highlighted = 0)
            and screening_featured = 0
            and payment.payment_created_at >= ".$bdate."
            and payment.payment_created_at <= ".$edate."
            and screening_status = 2
            and payment.payment_status = 2
            and ((p1.payment_type = 'host'
            and p1.payment_order_processor = 'Admin')
            or (p1.payment_id is null));";
    //kickdump($sql);
    $frs = $this -> propelQuery($sql);
    $fp = $frs -> fetchAll();
  } else {
    $fp = null;
    $sql = "select count(distinct(payment.payment_id))
            from payment
            inner join user
            on payment.fk_user_id = user.user_id
            inner join screening
            on payment.fk_screening_id = screening.screening_id
            inner join payment p1
            on p1.fk_screening_id = screening.screening_id
            and p1.payment_type = 'host'
            and p1.payment_order_processor = 'Admin'
            where payment.fk_film_id = ".$film -> getFilmId()."
            and user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and payment.payment_type = 'screening'
            and (screening_highlighted is null
                 or screening_highlighted = 0)
            and screening_featured = 0
            and payment.payment_created_at >= ".$bdate."
            and payment.payment_created_at <= ".$edate."
            and screening_status = 2
            and payment.payment_status = 2
            and ((p1.payment_type = 'host'
            and p1.payment_order_processor = 'Admin')
            or (p1.payment_id is null));";
    //kickdump($sql);
    $frs = $this -> propelQuery($sql);
    $fp = $frs -> fetchAll();
  }

  if ($fp[0][0] != null) {
    $p11 = $fp[0][0];
    $worksheet->write($j,$i,$fp[0][0]);
  } else {
    $p11 = 0;
    $worksheet->write($j,$i,0);
  }
  $j++;
  
  //Hosted Showtime Tickets Sold 
  //These are showtimes created by the users
  //Line 12
  if ($film -> getFilmUseSponsorCodes() == 1) {
    $gp[0][0] = "N/A";
    $p12 = 0;
    //kickdump($sql);
  } else {
    $gp = null;
    $screenings = null;
    $sql = "select count(distinct(payment.payment_id))
            from payment
            inner join user
            on payment.fk_user_id = user.user_id
            inner join screening
            on payment.fk_screening_id = screening.screening_id
            inner join payment p1
            on p1.fk_screening_id = screening.screening_id
            where payment.fk_film_id = ".$film -> getFilmId()."
            and user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and payment.payment_type = 'screening'
            and (screening_highlighted is null
                 or screening_highlighted = 0)
            and screening_featured = 0
            and payment.payment_created_at >= ".$bdate."
            and payment.payment_created_at <= ".$edate."
            and screening_status = 2
            and payment.payment_status = 2
            and (p1.payment_type = 'host'
            and p1.payment_order_processor != 'Admin');";
    $grs = $this -> propelQuery($sql);
    $gp = $grs -> fetchAll();
    $p12 = $gp[0][0];
    //kickdump($sql);
  }
  $worksheet->write($j,$i,$gp[0][0]);
  $j++;
  
  //Total Tickets Sold
  //Line 13
  if ($film -> getFilmUseSponsorCodes() == 1) {
    $hp = null;
    $screenings = null;
    $sql = "select count(distinct(payment.payment_id))
            from payment
            inner join user
            on payment.fk_user_id = user.user_id
            inner join screening
            on payment.fk_screening_id = screening.screening_id
            where payment.fk_film_id = ".$film -> getFilmId()."  
            and user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and payment.payment_type = 'screening'
            and payment.payment_created_at >= ".$bdate."
            and payment.payment_created_at <= ".$edate."
            and screening_status = 2
            and payment.payment_status = 2;";
    $hrs = $this -> propelQuery($sql);
    $hp = $hrs -> fetchAll();
    //kickdump($sql);
  } else {
    $hp = null;
    $screenings = null;
    $sql = "select count(distinct(payment.payment_id))
            from payment
            inner join user
            on payment.fk_user_id = user.user_id
            inner join screening
            on payment.fk_screening_id = screening.screening_id
            where payment.fk_film_id = ".$film -> getFilmId()."  
            and user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and payment.payment_type = 'screening'
            and payment.payment_created_at >= ".$bdate."
            and payment.payment_created_at <= ".$edate."
            and screening_status = 2
            and payment.payment_status = 2;";
    $hrs = $this -> propelQuery($sql);
    $hp = $hrs -> fetchAll();
    //kickdump($sql);
  }
  $worksheet->write($j,$i,$hp[0][0]);
  $p13 = $hp[0][0];
  $j++;
  
  //Line 14
  $j++;
  //Line 15
  $j++;
  
  //Watch Now Screenings Created
  //Line 16
  if ($film -> getFilmUseSponsorCodes() == 1) {
    $ip = null;
    $sql = "select count(distinct(audience_id))
            from audience
            inner join `user`
            on fk_user_id = user.user_id
            inner join screening
            on audience.fk_screening_id = screening.screening_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and screening.fk_film_id = ".$film -> getFilmId()."
            and screening.screening_type = 3
            and screening.fk_host_id = audience.fk_user_id
            and audience_paid_status = 2
            and screening_date >= ".$bdate."
            and screening_date <= ".$edate.";";
    $irs = $this -> propelQuery($sql);
    $ip = $irs -> fetchAll();
  } else {
    $ip = null;
    $sql = "select count(distinct(audience_id))
            from audience
            inner join `user`
            on fk_user_id = user.user_id
            inner join screening
            on audience.fk_screening_id = screening.screening_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and screening.fk_film_id = ".$film -> getFilmId()."
            and screening.screening_type = 3
            and screening.fk_host_id = audience.fk_user_id
            and audience_paid_status = 2
            and screening_date >= ".$bdate."
            and screening_date <= ".$edate.";";
    $irs = $this -> propelQuery($sql);
    $ip = $irs -> fetchAll();
  }
  if ($ip[0][0] != null) {
    $a16 = $ip[0][0];
    $worksheet->write($j,$i,$ip[0][0]);
  } else {
    $a16 = 0;
    $worksheet->write($j,$i,0);
  }
  $j++;
  
  //Create a Showtime Tickets Sold
  //If a user buys a shotime, they are the host
  //Line 17
  if ($film -> getFilmUseSponsorCodes() == 1) {
    $jq[0][0]="N/A";
    $a17 = 0;
  } else {
    $jq = null;
    $sql = "select count(distinct(audience_id))
            from audience
            inner join screening
            on screening.screening_id = audience.fk_screening_id
            inner join `user`
            on audience.fk_user_id = user.user_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and fk_film_id = ".$film -> getFilmId()."  
            and screening.fk_host_id = user.user_id          
  					and audience_paid_status = 2
            and screening_type = 1          
            and screening_date >= ".$bdate."
            and screening_date <= ".$edate.";";
    //kickdump($sql);
    
    $jrs = $this -> propelQuery($sql);
    $jq = $jrs -> fetchAll();
            
    if ($jp[0][0] != null) {
      $a17 = $jq[0][0];
    } else {
      $a17 = 0;
    }
    
  }
  
  $worksheet->write($j,$i,$jq[0][0]);
  $j++;
  
  //Highlight Showtime Tickets
  //Line 18
  if ($film -> getFilmUseSponsorCodes() == 1) {
    $lq = null;
    $sql = "select count(distinct(audience_id))
            from audience
            inner join screening
            on screening.screening_id = audience.fk_screening_id
            inner join `user`
            on fk_user_id = user.user_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and fk_film_id = ".$film -> getFilmId()."
  					and screening_highlighted = 1
            and screening_featured = 0
            and screening.fk_host_id != user.user_id          
  					and audience_paid_status = 2
            and screening_date >= ".$bdate."
            and screening_date <= ".$edate.";";
    
    $lrs = $this -> propelQuery($sql);
    $lq = $lrs -> fetchAll();
  } else {
    $lq = null;
    $sql = "select count(distinct(audience_id))
            from audience
            inner join screening
            on screening.screening_id = audience.fk_screening_id
            inner join `user`
            on fk_user_id = user.user_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and fk_film_id = ".$film -> getFilmId()."
  					and screening_highlighted = 1
            and screening_featured = 0
            and screening.fk_host_id != user.user_id          
  					and audience_paid_status = 2
            and screening_date >= ".$bdate."
            and screening_date <= ".$edate.";";
    
    $lrs = $this -> propelQuery($sql);
    $lq = $lrs -> fetchAll();
  
  }
  if ($lp[0][0] != null) {
    $a18 = $lp[0][0];
    $worksheet->write($j,$i,$lp[0][0]);
  } else {
    $a18 = 0;
    $worksheet->write($j,$i,0);
  }
  $j++;
               
  //Featured Showtime Tickets
  //Line 19
  if ($film -> getFilmUseSponsorCodes() == 1) {
    $mq = null;
    $sql = "select count(distinct(audience_id))
            from audience
            inner join screening
            on screening.screening_id = audience.fk_screening_id
            inner join `user`
            on fk_user_id = user.user_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and fk_film_id = ".$film -> getFilmId()."
  					and (screening_highlighted is null
                 or screening_highlighted = 0)
            and screening_featured = 1
            and screening.fk_host_id != user.user_id          
  					and audience_paid_status = 2
            and screening_date >= ".$bdate."
            and screening_date <= ".$edate.";";
    //kickdump($sql);
    
    $mrs = $this -> propelQuery($sql);
    $mq = $mrs -> fetchAll();
  } else {
    $mq = null;
    $sql = "select count(distinct(audience_id))
            from audience
            inner join screening
            on screening.screening_id = audience.fk_screening_id
            inner join `user`
            on fk_user_id = user.user_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and fk_film_id = ".$film -> getFilmId()."
  					and (screening_highlighted is null
                 or screening_highlighted = 0)
            and screening_featured = 1
            and screening.fk_host_id != user.user_id          
  					and audience_paid_status = 2
            and screening_date >= ".$bdate."
            and screening_date <= ".$edate.";";
    //kickdump($sql);
    
    $mrs = $this -> propelQuery($sql);
    $mq = $mrs -> fetchAll();
  
  }
  if ($mq[0][0] != null) {
    $a19 = $mq[0][0];
    $worksheet->write($j,$i,$mq[0][0]);
  } else {
    $a19 = 0;
    $worksheet->write($j,$i,0);
  }
  $j++;
  
  //Featured and Highlighted Showtime Tickets
  //Line 20
  if ($film -> getFilmUseSponsorCodes() == 1) {
    $nq = null;
    $sql = "select count(distinct(audience_id))
            from audience
            inner join screening
            on screening.screening_id = audience.fk_screening_id
            inner join `user`
            on fk_user_id = user.user_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and fk_film_id = ".$film -> getFilmId()."
  					and screening_highlighted = 1
            and screening_featured = 1
            and screening.fk_host_id != user.user_id          
  					and audience_paid_status = 2
            and screening_date >= ".$bdate."
            and screening_date <= ".$edate.";";
    
    $nrs = $this -> propelQuery($sql);
    $nq = $nrs -> fetchAll();
  } else {
    $nq = null;
    $sql = "select count(distinct(audience_id))
            from audience
            inner join screening
            on screening.screening_id = audience.fk_screening_id
            inner join `user`
            on fk_user_id = user.user_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and fk_film_id = ".$film -> getFilmId()."
  					and screening_highlighted = 1
            and screening_featured = 1
            and screening.fk_host_id != user.user_id          
  					and audience_paid_status = 2
            and screening_date >= ".$bdate."
            and screening_date <= ".$edate.";";
    
    $nrs = $this -> propelQuery($sql);
    $nq = $nrs -> fetchAll();
  
  }
  if ($nq[0][0] != null) {
    $a20 = $nq[0][0];
    $worksheet->write($j,$i,$nq[0][0]);
  } else {
    $a20 = 0;
    $worksheet->write($j,$i,0);
  }
  $j++;
  
  //Regular Showtime Tickets
  //Line 21
  if ($film -> getFilmUseSponsorCodes() == 1) {
    $oq = null;
    $sql = "select count(distinct(audience_id))
            from audience
            inner join screening
            on screening.screening_id = audience.fk_screening_id
            inner join `user`
            on fk_user_id = user.user_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and fk_film_id = ".$film -> getFilmId()."
  					and (screening_highlighted is null
                 or screening_highlighted = 0)
            and screening_featured = 0
            and screening.fk_host_id != user.user_id          
  					and audience_paid_status = 2
            and screening_date >= ".$bdate."
            and screening_date <= ".$edate.";";
    
    $ors = $this -> propelQuery($sql);
    $oq = $ors -> fetchAll();
  } else {
    $oq = null;
    $sql = "select count(distinct(audience_id))
            from audience
            inner join screening
            on screening.screening_id = audience.fk_screening_id
            inner join `user`
            on fk_user_id = user.user_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and fk_film_id = ".$film -> getFilmId()."
  					and (screening_highlighted is null
                 or screening_highlighted = 0)
            and screening_featured = 0
            and screening.fk_host_id != user.user_id          
  					and audience_paid_status = 2
            and screening_date >= ".$bdate."
            and screening_date <= ".$edate.";";
    
    $ors = $this -> propelQuery($sql);
    $oq = $ors -> fetchAll();
  
  }
  if ($oq[0][0] != null) {
    $a21 = $oq[0][0];
    $worksheet->write($j,$i,$oq[0][0]);
  } else {
    $a21 = 0;
    $worksheet->write($j,$i,0);
  }
  $j++;
  
  //Hosted Showtime Tickets Sold
  //These are showtimes created by the users
  //Attended by other users
  //Line 22
  $pq = null;
  $sql = "select count(distinct(audience_id))
          from audience
          inner join screening
          on audience.fk_screening_id = screening.screening_id
          inner join `user`
          on fk_user_id = user.user_id
          where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
          and screening.fk_host_id != audience.fk_user_id
          and audience.audience_paid_status = 2
          and fk_screening_id in
          (select screening_id
          from screening
          inner join payment
          on payment.fk_screening_id = screening.screening_id
          where payment.fk_user_id = screening.fk_host_id
  				and payment.fk_user_id is not null
          and payment.payment_order_processor != 'Admin'
          and screening.screening_type = 1
          and screening.fk_film_id = ".$film -> getFilmId()."
          and screening.screening_date >= ".$bdate."
          and screening.screening_date <= ".$edate.");";
  
  $prs = $this -> propelQuery($sql);
  $pq = $prs -> fetchAll();
  
  if ($pq[0][0] != null) {
    $a22 = $pq[0][0];
    $worksheet->write($j,$i,$pq[0][0]);
  } else {
    $a22 = 0;
    $worksheet->write($j,$i,0);
  }
  $j++;
  
  //Total Attendance
  //Line 23
  if ($film -> getFilmUseSponsorCodes() == 1) {
    $qq = null;
    $sql = "select count(distinct(audience_id))
            from audience
            inner join screening
            on screening.screening_id = audience.fk_screening_id
            inner join `user`
            on fk_user_id = user.user_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and fk_film_id = ".$film -> getFilmId()." 
            and screening.fk_host_id != user.user_id
  					and audience_paid_status = 2
            and screening_date >= ".$bdate."
            and screening_date <= ".$edate.";";
    
    $qrs = $this -> propelQuery($sql);
    $qq = $qrs -> fetchAll();
    
  } else {
    $qq = null;
    $sql = "select count(distinct(audience_id))
            from audience
            inner join screening
            on screening.screening_id = audience.fk_screening_id
            inner join `user`
            on fk_user_id = user.user_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and fk_film_id = ".$film -> getFilmId()." 
            and screening.fk_host_id != user.user_id
  					and audience_paid_status = 2
            and screening_date >= ".$bdate."
            and screening_date <= ".$edate.";";
    
    $qrs = $this -> propelQuery($sql);
    $qq = $qrs -> fetchAll();
  
  }
  if ($qq[0][0] != null) {
    $a23 = $qq[0][0];
    $worksheet->write($j,$i,$qq[0][0]);
    $tickets = $qq[0][0];
  } else {
    $a23 = 0;
    $worksheet->write($j,$i,0);
    $tickets = 0;
  }
  $j++;
  
  //Total Showtimes
  //Line 24
  if ($film -> getFilmUseSponsorCodes() == 1) {
    $rq = null;
    $sql = "select count(distinct(screening_id))
            from screening
            where fk_film_id = ".$film -> getFilmId()."
  					/*and audience_status = 1*/
            and screening_paid_status = 2
            and screening_date >= ".$bdate."
            and screening_date <= ".$edate.";";
    
    $rrs = $this -> propelQuery($sql);
    $rq = $rrs -> fetchAll();
    
  } else {
    $rq = null;
    $sql = "select count(distinct(screening_id))
            from screening
            where fk_film_id = ".$film -> getFilmId()."
  					/*and audience_status = 1*/
            and screening_paid_status = 2
            and screening_date >= ".$bdate."
            and screening_date <= ".$edate.";";
    
    $rrs = $this -> propelQuery($sql);
    $rq = $rrs -> fetchAll();
  
  }
  if ($rq[0][0] != null) {
    $a24 = $rq[0][0];
    $worksheet->write($j,$i,$rq[0][0]);
    $screenings = $rq[0][0];
  } else {
    $a24 = 0;
    $worksheet->write($j,$i,0);
    $screenings = 0;
  }
  $j++;
  
  
  //Average Attendance
  //Line 25
  if ($screenings > 0) {
    $worksheet->write($j,$i,sprintf("%.02f",($tickets/$screenings)));
  } else {
    $worksheet->write($j,$i,0);
  }
  $j++; 
    
  //Line 26
  $j++;
  //Line 27
  $j++;
  
  
  //User Invitations
  //Line 28 & 29
  $fmail_sent = 0;
  $ffacebook_sent = 0;
  
  $sql = "select user_invite_type, 
          sum(user_invite_count)
          from user_invite
          where fk_film_id = ".$film -> getFilmId()."
          and user_type = 'screening'
          and user_invite_date >= ".$bdate."
          and user_invite_date <= ".$edate."
  				group by user_invite_type;";
  $srs = $this -> propelQuery($sql);
  while ($row = $srs -> fetch()){
    if ($row[0] == 'email') {
      $fmail_sent = $row[1];
    }
    if ($row[0] == 'facebook') {
      $ffacebook_sent = $row[1];
    }
  }
  $worksheet->write($j,$i,sprintf("%0d",$ffacebook_sent));
  $j++;
  $worksheet->write($j,$i,sprintf("%0d",$fmail_sent));
  $j++;
  
  //Host Invitations
  //Line 30 & 31
  $email_sent = 0;
  $efacebook_sent = 0;
  
  $sql = "select user_invite_type, 
          sum(user_invite_count)
          from user_invite
          where fk_film_id = ".$film -> getFilmId()."
          and user_type = 'host'
          and user_invite_date >= ".$bdate."
          and user_invite_date <= ".$edate."
  				group by user_invite_type;";
  $ers = $this -> propelQuery($sql);
  
  while ($row = $ers -> fetch()){
    if ($row[0] == 'email') {
      $email_sent = $row[1];
    }
    if ($row[0] == 'facebook') {
      $efacebook_sent = $row[1];
    }
  }
  
  $worksheet->write($j,$i,sprintf("%0d",$efacebook_sent));
  $j++;
  $worksheet->write($j,$i,sprintf("%0d",$email_sent));
  $j++;
  
  //Line 32  
  $worksheet->write($j,$i,"N/A");
  $j++;
  //Line 33                      
  $worksheet->write($j,$i,"N/A");
  $j++;    
  
  //Line 34
  $j++;    
  //Line 35
  $j++;
  
  if ($film -> getFilmUseSponsorCodes() != 1) {
    
    /***************************************************/
    /*****         TICKET PRICES 
    /***************************************************/
    //Line 36
    $j++;
    //Line 37
    $j++;
    //Line 38
    $j++;
    //Line 39
    $j++;  
    //Line 40
    $j++;
    //Line 41
    $j++;
    //Line 42
    $j++;
    
    //Watch Now Daily
    //Line 43
    $worksheet->write($j,$i,sprintf("%.02f",$p6 * $film -> getFilmSetupPrice()),$currency_format);
    $j++;
    
    //Create a Showtime
    //Line 44
    $worksheet->write($j,$i,sprintf("%.02f",$p7 * $film -> getFilmSetupPrice()),$currency_format);
    $j++;
    
    //Highlight Showtimes
    //Line 45
    $worksheet->write($j,$i,sprintf("%.02f",$p8 * $film -> getFilmTicketPrice()),$currency_format);
    $j++;
    
    //Featured Showtimes
    //Line 46
    $worksheet->write($j,$i,sprintf("%.02f",$p9 * $film -> getFilmTicketPrice()),$currency_format);
    $j++;
    
    //Featured + Highlighted Showtimes
    //Line 47
    $worksheet->write($j,$i,sprintf("%.02f",$p10 * $film -> getFilmTicketPrice()),$currency_format);
    $j++;
    
    //Regular Showtimes
    //Line 48
    $worksheet->write($j,$i,sprintf("%.02f",$p11 * $film -> getFilmTicketPrice()),$currency_format);
    $j++;
    
    //Hosted Showtimes
    //Line 49
    $worksheet->write($j,$i,sprintf("%.02f",$p12 * $film -> getFilmTicketPrice()),$currency_format);
    $j++;
    
    //Total Gross Revenue
    //Line 50
    $worksheet->write($j,$i,sprintf("%.02f",$p13 * $film -> getFilmTicketPrice()),$currency_format);
    $j++;
    
    //Applied Discounts
    //Line 51
    $worksheet->write($j,$i,sprintf("%.02f","N/A"),$currency_format);
    $j++;
    
    //Total Net Discounts
    //Line 52
    $worksheet->write($j,$i,sprintf("%.02f","N/A"),$currency_format);
    $j++;
    
  }
  
  //Line 53
  $j++;
  
  //% to Rights Holder
  //Line 54
  $worksheet->write($j,$i,"N/A");
  $j++;
  
  //Total Paypal Receipts
  //Line 55
  $sp = null;
  $sql = "select sum(paypal_transaction.paypal_transaction_amount)
          from paypal_transaction
          inner join payment
          on payment.payment_transaction_id = paypal_transaction.paypal_transaction_guid
          inner join `user`
          on fk_user_id = user.user_id
          where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
          and fk_film_id = ".$film -> getFilmId()."
          and payment_status = 2
          and payment_created_at >= ".$bdate."
          and payment_created_at <= ".$edate."
          and paypal_transaction.paypal_transaction_amount > 0;";
  $srs = $this -> propelQuery($sql);
  $sp = $jrs -> fetchAll();
  
  $worksheet->write($j,$i,sprintf("%.02f",$sp[0][0]),$currency_format);
  $j++;
  
  //Total Paypal Refunds
  //Line 56
  $tp = null;
  $sql = "select sum(paypal_transaction.paypal_transaction_amount)
          from paypal_transaction
          inner join payment
          on payment.payment_transaction_id = paypal_transaction.paypal_transaction_guid
          inner join `user`
          on fk_user_id = user.user_id
          where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
          and fk_film_id = ".$film -> getFilmId()."
          and payment_status = 2
          and payment_created_at >= ".$bdate."
          and payment_created_at <= ".$edate."
          and paypal_transaction.paypal_transaction_amount < 0;";
  $trs = $this -> propelQuery($sql);
  $tp = $qrs -> fetchAll();
  
  $worksheet->write($j,$i,sprintf("%.02f",$tp[0][0]),$currency_format);
  $j++;
  
  //Total Paypal Transaction Charges
  //Line 57
  $up = null;
  $sql = "select sum(paypal_transaction.paypal_transaction_fee)
          from paypal_transaction
          inner join payment
          on payment.payment_transaction_id = paypal_transaction.paypal_transaction_guid
          inner join `user`
          on fk_user_id = user.user_id
          where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
          and fk_film_id = ".$film -> getFilmId()."
          and payment_status = 2
          and payment_created_at >= ".$bdate."
          and payment_created_at <= ".$edate.";";
  $urs = $this -> propelQuery($sql);
  $up = $urs -> fetchAll();
  
  $worksheet->write($j,$i,sprintf("%.02f",$up[0][0]),$currency_format);
  $j++;
  
  //Net Paypal Receipts
  //Line 58
  $vp = null;
  $sql = "select sum(paypal_transaction.paypal_transaction_fee)
          from paypal_transaction
          inner join payment
          on payment.payment_transaction_id = paypal_transaction.paypal_transaction_guid
          inner join `user`
          on fk_user_id = user.user_id
          where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
          and fk_film_id = ".$film -> getFilmId()."
          and payment_status = 2
          and payment_created_at >= ".$bdate."
          and payment_created_at <= ".$edate."
          and paypal_transaction.paypal_transaction_amount > 0;";
  $vrs = $this -> propelQuery($sql);
  $vp = $vrs -> fetchAll();
  
  $worksheet->write($j,$i,sprintf("%.02f",$vp[0][0]),$currency_format);
  $j++;
  
  //Line 59
  $j++;
  
  //Revenue Shares to Rights Holder
  //Line 60
  $worksheet->write($j,$i,"N/A");
  $j++;
  
  //Line 61
  $j++;
           
  //Line 62
  $j++;
  
  //Revenue to Constellation
  //Line 63
  $worksheet->write($j,$i,"N/A");
  $j++;
        
  //Line 64
  $j++;
  
  //Line 64
  $j++;
  
  //Line 66
  $j++;
  
  //Line 67
  $j++;
  
  /*
  //Watch Now Gross
  //Line 68
  $worksheet->write($j,$i,sprintf("%.02f",$a16 * $film -> getFilmSetupPrice()),$currency_format);
  $j++;
  
  //Create a Showtime
  //Line 69
  $worksheet->write($j,$i,sprintf("%.02f",$a17 * $film -> getFilmSetupPrice()),$currency_format);
  $j++;
  
  //Highlight Showtimes
  //Line 70
  $worksheet->write($j,$i,sprintf("%.02f",$a18 * $film -> getFilmTicketPrice()),$currency_format);
  $j++;
  
  //Featured Showtimes
  //Line 71
  $worksheet->write($j,$i,sprintf("%.02f",$a19 * $film -> getFilmTicketPrice()),$currency_format);
  $j++;
  
  //Featured + Highlighted Showtimes
  //Line 72
  $worksheet->write($j,$i,sprintf("%.02f",$a20 * $film -> getFilmTicketPrice()),$currency_format);
  $j++;
  
  //Regular Showtimes
  //Line 73
  $worksheet->write($j,$i,sprintf("%.02f",$a21 * $film -> getFilmTicketPrice()),$currency_format);
  $j++;
  
  //Hosted Showtimes
  //Line 74
  $worksheet->write($j,$i,sprintf("%.02f",$a22 * $film -> getFilmTicketPrice()),$currency_format);
  $j++;
  
  //Total Gross Revenue
  //Line 75
  $worksheet->write($j,$i,sprintf("%.02f",$a23 * $film -> getFilmTicketPrice()),$currency_format);
  $j++;
  
  */
}

//We still need to explicitly close the workbook
$workbook->close();
//die();

?>
