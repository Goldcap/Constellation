<?php
error_reporting(0);

$array = null;
$film = FilmPeer::retrieveByPk( $this -> getVar("id") );

$args["filename"] = cleanFileName($film -> getFilmShortName().'_FilmPayments_'.date("mdY-hi")).'.xls';
$args["location"] = sfConfig::get("sf_data_dir")."/exports";
createDirectory($args["location"]);

// We give the path to our file here
$workbook = new Spreadsheet_Excel_Writer($args["location"]."/".$args["filename"]);
$worksheet = $workbook->addWorksheet("Weekly Usage Report");
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
$worksheet->write($j,0,$film -> getFilmName() . " Usage Report",$title_format);
$worksheet->write($j,1,"",$title_format);
$j++;
$worksheet->write($j,0,"",$title_format);
$worksheet->write($j,1,"",$title_format);
$j++;
$worksheet->write($j,0,"",$title_format);
$worksheet->write($j,1,"",$title_format);
$j++;

$worksheet->write($j,0,"TICKET AND HOSTED SCREENING SALES",$format_bold);
$j++;

$worksheet->write($j,0,"Non-Zero Payments Received");
$worksheet->write($j,1,"Number of Tickets Sold (Non-Zero Payments)");
$j++;

if ($film -> getFilmUseSponsorCodes() == 1) {
  $worksheet->write($j,0,"Total Codes Used");
} else {
  $worksheet->write($j,0,"Total Transactions (Zero or Non-Zero)");
  $worksheet->write($j,1,"No. Tickets Distributed (Measured by # of Transations)");
}
$j++;

if ($film -> getFilmUseSponsorCodes() == 1) {
  $worksheet->write($j,0,"Screening By Demand (Code Uses)");
} else {
  $worksheet->write($j,0,"Hosted Screenings Sold to Users");
}
$j++;

if ($film -> getFilmUseSponsorCodes() == 0) {
  $worksheet->write($j,0,"Hosted Screenings Total");
  $j++;
}

$worksheet->write($j,0,"Host By Demand Screenings");
$j++;

$worksheet->write($j,0,"Screenings Total");
$j++;

if ($film -> getFilmUseSponsorCodes() == 0) {
  $worksheet->write($j,0,"Scheduled Non-Hosted, Scheduled Hosted, User-Hosted, On-Demand");
  $j++;
}

if ($film -> getFilmUseSponsorCodes() == 1) {
  $worksheet->write($j,0,"Total Seats Occupied (Includes User Re-Entry)");
} else {
  $worksheet->write($j,0,"Tickets Distributed (Measured by Users)");
}
$j++;

$worksheet->write($j,0,"Unique Users Attending (Including Hosts)");
$j++;

$worksheet->write($j,0,"Average Attendance per Screening");
$worksheet->write($j,1,"Unique Users Attending/Screenings With At Least 1 Attendee");
$j++;
$j++;

$worksheet->write($j,0,"Invitations Sent by Hosts");
$j++;
$worksheet->write($j,0,"Email Invitations Sent by Hosts");
$j++;
$worksheet->write($j,0,"Facebook Invitations Sent by Hosts");
$j++;
$worksheet->write($j,0,"Twitter Invitations Sent by Hosts");
$j++;

$worksheet->write($j,0,"Invitations Sent by Users");
$j++;
$worksheet->write($j,0,"Email Invitations Sent by Users");
$j++;
$worksheet->write($j,0,"Facebook Invitations Sent by Users");
$j++;
$worksheet->write($j,0,"Twitter Invitations Sent by Users");
$j++;
$j++;

if ($film -> getFilmUseSponsorCodes() != 1) {
  $worksheet->write($j,0,"Current User Ticket Price");
  $worksheet->write($j,1,"$".$film -> getFilmTicketPrice());
  $j++;
  
  $worksheet->write($j,0,"Current Host Setup Price");
  $worksheet->write($j,1,"$".$film -> getFilmSetupPrice());
  $j++;
  
  $worksheet->write($j,0,"Discount per User Invitee");
  $worksheet->write($j,1,"$0.10");
  $j++;
    
  //$worksheet->write($j,0,"Discount per Host Invitee");
  //$j++;
  $worksheet->write($j,0,"Average User Ticket Price Paid");
  $j++;
  $worksheet->write($j,0,"Average Host Ticket Price Paid");
  $j++;
  $j++;
  
  $worksheet->write($j,0,"Total Sales from Tickets and Hosted Screenings");
  $j++;
  $j++;
  $worksheet->write($j,0,"Total Paypal Receipts");
  $j++;
  $worksheet->write($j,0,"Total Paypal Refunds");
  $j++;
  $worksheet->write($j,0,"Total Paypal Transaction Charges");
  $j++;
  $worksheet->write($j,0,"Net paypal Sales from Tickets and Hosted Screenings");
  $j++;
  $j++;
  
  $worksheet->write($j,0,"Revenue Share to Rights Holder");
  $j++;
  $j++;
  
  /*
  $worksheet->write(32,0,"SALES FROM SUBSCRIPTION HOLDERS", $format_bold);
  $worksheet->write(33,0,"Views by Subscription Holders");
  $worksheet->write(34,0,"Amount Paid to Rights Holder per View");
  
  $worksheet->write(36,0,"Total Sales from Subscriptions");
  */
  
  $worksheet->write($j,0,"Amount Owed to Rights Holder");
  $j++;
  $worksheet->write($j,0,"Amount Owed to Constellation");
}

$datestart = date('Y-m-d',strtotime('sunday',strtotime($film -> getFilmStartdate()) - (60 * 60 * 24 * 7)));
if ($film -> getFilmEnddate() > now()) {
  $dateend = date('Y-m-d',strtotime('sunday',strtotime(now()) - (60 * 60 * 24 * 7)));
} else {
  $dateend = date('Y-m-d',strtotime('sunday',strtotime($film -> getFilmEnddate()) - (60 * 60 * 24 * 7)));
}

for ($i=2;$i<256;$i++) {
  $j=0;

  $weekend = date('Y-m-d',strtotime($datestart) + (60 * 60 * 24 * 6));
  
  $bdate = "'".$datestart." 00:00:00'";
  $edate = "'".$weekend." 11:59:59'";
  //kickdump($bdate);
  //kickdump($edate);
  
  $worksheet->setColumn($i,$i,20);
  $worksheet->write($j,$i,"",$title_format);
  $j++;
  $worksheet->write($j,$i,"Week ".($i - 1),$title_format);
  $j++;
  $worksheet->write($j,$i,$datestart." - ".$weekend,$title_format);
  $j++;
  $j++;
  
  /***************************************************/
  /*****       Non-Zero Payments Received    *********/
  /***************************************************/
  if ($film -> getFilmUseSponsorCodes() == 1) {
    $ap[0][0] = "N/A";
  } else {
    $ap = null;
    $sql = "select count(distinct(payment_id))
            from payment
            inner join `user`
            on fk_user_id = user.user_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and fk_film_id = ".$film -> getFilmId()."
            and payment_type = 'screening'
            and payment_status = 2
            and payment_created_at >= ".$bdate."
            and payment_created_at <= ".$edate."
            and payment_amount > 0;";
    
    $ars = $this -> propelQuery($sql);
    $ap = $ars -> fetchAll();
  }
  $worksheet->write($j,$i,$ap[0][0]);
  $j++;
  //kickdump($sql);
  
  
  /*************************************************************/
  /*****   Total Codes Used (Total Transactions)  *********/
  /*************************************************************/
  if ($film -> getFilmUseSponsorCodes() == 1) {
    $xp = null;
    $sql = "select count(sponsor_code_usage_id),
            fk_screening_unique_key,
            sponsor_code_usage.fk_user_id
            from sponsor_code
            inner join sponsor_code_usage
            on sponsor_code_id = fk_sponsor_code_id
            and sponsor_code.fk_film_id = ".$film -> getFilmId()."
            inner join `user`
            on sponsor_code.fk_user_id = user.user_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and sponsor_code_usage_date >= ".$bdate."
            and sponsor_code_usage_date <= ".$edate."
            group by sponsor_code_usage.fk_user_id;";
    $ars = $this -> propelQuery($sql);
    $rowc = $ars -> rowCount();
    //kickdump($sql);
    $xp[0][0] = $rowc;
  } else {
    $xp = null;
    $sql = "select count(distinct(payment_id))
            from payment
            inner join `user`
            on fk_user_id = user.user_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and fk_film_id = ".$film -> getFilmId()."
            and payment_type = 'screening'
            and payment_status = 2
            and payment_created_at >= ".$bdate."
            and payment_created_at <= ".$edate.";";
    //kickdump($sql);
    $ars = $this -> propelQuery($sql);
    $xp = $ars -> fetchAll();
  }
  
  if ($xp[0][0] != null) {
    $worksheet->write($j,$i,$xp[0][0]);
  } else {
    $worksheet->write($j,$i,0);
  }
  $j++;
  
  /***************************************************/
  /************    Screenings By Demand     **********/
  /***************************************************/
  if ($film -> getFilmUseSponsorCodes() == 1) {
    $bp = null;
    $sql = "select count(distinct(fk_screening_id))
            from payment
            inner join screening
            on fk_screening_id = screening_id
            where payment.fk_film_id = ".$film -> getFilmId()."
            and screening_type = 3
            and payment_created_at >= ".$bdate."
            and payment_created_at <= ".$edate."
            and screening_status = 2;";
    //kickdump($sql);
    $brs = $this -> propelQuery($sql);
    $bp = $brs -> fetchAll();
  } else {
    $bp = null;
    $sql = "select count(distinct(payment_id))
            from payment
            inner join `user`
            on fk_user_id = user.user_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and fk_film_id = ".$film -> getFilmId()."
            and payment_type = 'host'
            and payment_order_processor != 'Admin'
            and payment_status = 2
            and payment_created_at >= ".$bdate."
            and payment_created_at <= ".$edate."
            and payment_amount > 0;";
    $brs = $this -> propelQuery($sql);
    $bp = $brs -> fetchAll();
  }
  if ($xp[0][0] != null) {
    $worksheet->write($j,$i,$bp[0][0]);
  } else {
    $worksheet->write($j,$i,0);
  }
  $j++;
  
  /***************************************************/
  /*****       Hosted Screenings       *********/
  /***************************************************/
  if ($film -> getFilmUseSponsorCodes() == 0) {
    $bp = null;
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
    $brs = $this -> propelQuery($sql);
    $bp = $brs -> fetchAll();
    
    if ($xp[0][0] != null) {
      $worksheet->write($j,$i,$bp[0][0]);
    } else {
      $worksheet->write($j,$i,"N/A");
    }
    $j++;
  }
  
  /***************************************************/
  /************** Host By Demand Screenings ***************/
  /***************************************************/
  $mp = null;
  $sql = "select count(distinct(payment_id))
          from payment
          inner join screening
          on payment.fk_screening_id = screening.screening_id
          inner join `user`
          on fk_user_id = user.user_id
          where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
          and payment.fk_film_id = ".$film -> getFilmId()."
          and payment_type = 'screening'
          and payment_status = 2
          and payment_created_at >= ".$bdate."
          and payment_created_at <= ".$edate."
          and screening_type = 3
          and payment_amount > 0;";
  $mrs = $this -> propelQuery($sql);
  $mp = $mrs -> fetchAll();
  
  if ($mp[0][0] != null) {
    $worksheet->write($j,$i,$mp[0][0]);
  } else {
    $worksheet->write($j,$i,"N/A");
  }
  $j++;
  
  /***************************************************/
  /*****      Screenings Total        *********/
  /***************************************************/
  if ($film -> getFilmUseSponsorCodes() == 1) {
    $cp = null;
    $screenings = null;
    $sql = "select count(distinct(screening_id))
            from screening
            inner join chat_instance
            on screening.screening_unique_key = chat_instance.fk_screening_key
            where fk_film_id = ".$film -> getFilmId()."
            and screening_date >= ".$bdate."
            and screening_date <= ".$edate."
            and screening_status = 2
            and screening.fk_host_id is not null;";
    $crs = $this -> propelQuery($sql);
    $cp = $crs -> fetchAll();
    //kickdump($sql);
  } else {
    $cp = null;
    $screenings = null;
    $sql = "select count(distinct(screening_id))
            from screening
            where fk_film_id = ".$film -> getFilmId()."
            and screening_date >= ".$bdate."
            and screening_date <= ".$edate."
            and screening_status = 2
            and screening.fk_host_id is not null;";
    $crs = $this -> propelQuery($sql);
    $cp = $crs -> fetchAll();
    //kickdump($sql);
  }
  $worksheet->write($j,$i,$cp[0][0]);
  $screenings = $cp[0][0];
  $j++;
  
  /*********************************************************************************************/
  /************* Scheduled Non-Hosted, Scheduled Hosted, User-Hosted, On-Demand ****************/
  /*********************************************************************************************/
  if ($film -> getFilmUseSponsorCodes() == 0) {
     //kickdump($bdate . " - " . $edate);
    $count = 0;
    $crs = null;
    $screenings = null;
    $sql = "select distinct(screening_id),
            count(distinct(audience_id)),
            screening.fk_host_id
            from screening
            inner join chat_instance
            on screening.screening_unique_key = chat_instance.fk_screening_key           
            inner join audience
            on audience.fk_screening_id = screening_id
            inner join user
            on audience.fk_user_id = user.user_id
            and user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            where fk_film_id = ".$film -> getFilmId()."
            and screening_date >= ".$bdate."
            and screening_date <= ".$edate."
            group by screening_id
            having count(distinct(audience_id)) > 0;";
    $crs = $this -> propelQuery($sql);
    //kickdump($sql);
    while($row = $crs -> fetch()) {
      //kickdump($row);
      if (! is_null($row[2])) {
        if ($row[1] > 1) {
          //kickdump("hosted screening with a user");
          $count++;
        }
      } elseif ($row[1] > 0) {
        //kickdump("non hosted screening with a user");
        $count++;
      } else {
        //kickdump("no users");
      }
    }
    //kickdump($sql);
  
    $worksheet->write($j,$i,$count);
    $j++;
  }
  
  /***************************************************/
  /*****           Total Seats Occupied      *********/
  /***************************************************/
  if ($film -> getFilmUseSponsorCodes() == 1) {
    $dp = null;
    $sql = "select count(distinct(audience_id))
            from audience
            inner join screening
            on screening.screening_id = audience.fk_screening_id
            inner join `user`
            on fk_user_id = user.user_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and fk_film_id = ".$film -> getFilmId()."
						and audience_paid_status = 2 
            and screening_date >= ".$bdate."
            and screening_date <= ".$edate.";";
    //kickdump($sql);
    
    $drs = $this -> propelQuery($sql);
    $dp = $drs -> fetchAll();
  } else {
    $dp = null;
    $sql = "select count(distinct(audience_id))
            from audience
            inner join screening
            on screening.screening_id = audience.fk_screening_id
            inner join `user`
            on fk_user_id = user.user_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and fk_film_id = ".$film -> getFilmId()."
						and audience_paid_status = 2 
            and screening_date >= ".$bdate."
            and screening_date <= ".$edate.";";
    //kickdump($sql);
    
    $drs = $this -> propelQuery($sql);
    $dp = $drs -> fetchAll();
  
  }
  if ($dp[0][0] != null) {
    $worksheet->write($j,$i,$dp[0][0]);
  } else {
    $worksheet->write($j,$i,0);
  }
  $j++;
  
  /***************************************************/
  /*****         Unique Users Attending      *********/
  /***************************************************/
  if ($film -> getFilmUseSponsorCodes() == 1) {
    $dq = null;
    $sql = "select count(distinct(fk_user_id))
            from audience
            inner join screening
            on screening.screening_id = audience.fk_screening_id
            inner join `user`
            on fk_user_id = user.user_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and fk_film_id = ".$film -> getFilmId()."
						/*and audience_status = 1*/
            and screening_date >= ".$bdate."
            and screening_date <= ".$edate.";";
    //kickdump($sql);
    
    $drs = $this -> propelQuery($sql);
    $dq = $drs -> fetchAll();
  } else {
    $dq = null;
    $sql = "select count(distinct(fk_user_id))
            from audience
            inner join screening
            on screening.screening_id = audience.fk_screening_id
            inner join `user`
            on fk_user_id = user.user_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and fk_film_id = ".$film -> getFilmId()."
						/*and audience_status = 1*/
            and screening_date >= ".$bdate."
            and screening_date <= ".$edate.";";
    //kickdump($sql);
    
    $drs = $this -> propelQuery($sql);
    $dq = $drs -> fetchAll();
  
  }
  if ($dp[0][0] != null) {
    $worksheet->write($j,$i,$dq[0][0]);
  } else {
    $worksheet->write($j,$i,0);
  }
  $j++;
  
  /**************************************************************/
  /*****          Average Attendance per Screening      *********/
  /**************************************************************/
  if ($screenings > 0) {
    $worksheet->write($j,$i,sprintf("%.02f",($dq[0][0]/$screenings)));
  } else {
    $worksheet->write($j,$i,0);
  }
  $j++;
  $j++;
  
  /***************************************************/
  /*****       Invitations Sent by Hosts      *********/
  /***************************************************/
  $email_sent = 0;
  $efacebook_sent = 0;
  $etwitter_sent = 0;
  
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
    if ($row[0] == 'twitter') {
      $etwitter_sent = $row[1];
    }
  }
  
  $worksheet->write($j,$i,sprintf("%0d",$email_sent + $efacebook_sent + $etwitter_sent));
  $j++;
  $worksheet->write($j,$i,sprintf("%0d",$email_sent));
  $j++;
  $worksheet->write($j,$i,sprintf("%0d",$efacebook_sent));
  $j++;
  $worksheet->write($j,$i,sprintf("%0d",$etwitter_sent));
  $j++;
  
  /***************************************************/
  /*****       Invitations Sent by Users      *********/
  /***************************************************/
  $fmail_sent = 0;
  $ffacebook_sent = 0;
  $ftwitter_sent = 0;
  
  $sql = "select user_invite_type, 
          sum(user_invite_count)
          from user_invite
          where fk_film_id = ".$film -> getFilmId()."
          and user_type = 'screening'
          and user_invite_date >= ".$bdate."
          and user_invite_date <= ".$edate."
					group by user_invite_type;";
  $frs = $this -> propelQuery($sql);
  while ($row = $frs -> fetch()){
    if ($row[0] == 'email') {
      $fmail_sent = $row[1];
    }
    if ($row[0] == 'facebook') {
      $ffacebook_sent = $row[1];
    }
    if ($row[0] == 'twitter') {
      $ftwitter_sent = $row[1];
    }
  }
  
  $worksheet->write($j,$i,sprintf("%0d",$fmail_sent + $ffacebook_sent + $ftwitter_sent));
  $j++;
  $worksheet->write($j,$i,sprintf("%0d",$fmail_sent));
  $j++;
  $worksheet->write($j,$i,sprintf("%0d",$ffacebook_sent));
  $j++;
  $worksheet->write($j,$i,sprintf("%0d",$ftwitter_sent));
  $j++;
  $j++;
  
  if ($film -> getFilmUseSponsorCodes() != 1) {
    
    /***************************************************/
    /*****         PRICES AND DISCOUNTS        *********/
    /***************************************************/
    //$worksheet->write($j,$i,"$".$film -> getFilmTicketPrice());
    $j++;
    //$worksheet->write($j,$i,"$".$film -> getFilmSetupPrice());
    $j++;
    //$worksheet->write($j,$i,"$0.10");
    $j++;
    //$worksheet->write($j,$i,"$0.10");
    //$j++;
    
    /***************************************************/
    /*****         AVERAGE TICKET PRICE        *********/
    /***************************************************/
    $gp = null;
    $sql = "select avg(payment.payment_amount)
            from payment
            inner join `user`
            on fk_user_id = user.user_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and fk_film_id = ".$film -> getFilmId()."
            and payment_type = 'screening'
            and payment_status = 2
            and payment_created_at >= ".$bdate."
            and payment_created_at <= ".$edate."
            and payment_amount > 0;";
    //kickdump($sql);
    $grs = $this -> propelQuery($sql);
    $gp = $grs -> fetchAll();
    
    if ($gp[0][0] != null) {
      $worksheet->write($j,$i,sprintf("%.02f",$gp[0][0]),$currency_format);
    } else {
      $worksheet->write($j,$i,sprintf("%.02f",0),$currency_format);
    }
    $j++;
    
    /***************************************************/
    /*****        AVERAGE HOSTING PRICE        *********/
    /***************************************************/
    $hp = null;
    $sql = "select avg(payment.payment_amount)
            from payment
            inner join `user`
            on fk_user_id = user.user_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and fk_film_id = ".$film -> getFilmId()."
            and payment_type = 'host'
            and payment_status = 2
            and payment_created_at >= ".$bdate."
            and payment_created_at <= ".$edate."
            and payment_amount > 0;";
    $hrs = $this -> propelQuery($sql);
    $hp = $hrs -> fetchAll();
    
    if ($hp[0][0] != null) {
      $worksheet->write($j,$i,sprintf("%.02f",$hp[0][0]),$currency_format);
    } else {
      $worksheet->write($j,$i,sprintf("%.02f",0),$currency_format);
    }
    $j++;
    $j++;
    
    /***************************************************/
    /*****              TOTAL REVENUE          *********/
    /***************************************************/
    $ip = null;
    $sql = "select sum(payment.payment_amount)
            from payment
            inner join `user`
            on fk_user_id = user.user_id
            where user.user_ual != 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}'
            and fk_film_id = ".$film -> getFilmId()."
            and payment_status = 2
            and payment_created_at >= ".$bdate."
            and payment_created_at <= ".$edate."
            and payment_amount > 0;";
    $irs = $this -> propelQuery($sql);
    $ip = $irs -> fetchAll();
    
    $worksheet->write($j,$i,sprintf("%.02f",$ip[0][0]),$currency_format);
    $j++;
    $j++;
    
    /***************************************************/
    /*****             PAYPAL REVENUE          *********/
    /***************************************************/
    $kp = null;
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
            and payment_amount > 0;";
    $krs = $this -> propelQuery($sql);
    $kp = $krs -> fetchAll();
    
    $worksheet->write($j,$i,sprintf("%.02f",$kp[0][0]),$currency_format);
    $j++;
    
    /***************************************************/
    /*****              PAYPAL REFUNDS         *********/
    /***************************************************/
    $lp = null;
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
            and payment_amount < 0
            and paypal_transaction_name != 'PayPal Monthly Billing';";
    $lrs = $this -> propelQuery($sql);
    $lp = $lrs -> fetchAll();
    
    $worksheet->write($j,$i,sprintf("%.02f",$lp[0][0]),$currency_format);
    $j++;
    
    /***************************************************/
    /*****        PAYPAL TRANSACTION COSTS     *********/
    /***************************************************/
    $jp = null;
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
    $jrs = $this -> propelQuery($sql);
    $jp = $jrs -> fetchAll();
    
    $worksheet->write($j,$i,sprintf("%.02f",$jp[0][0]),$currency_format);
    $j++;
    
    /***************************************************/
    /*****                 NET SALES           *********/
    /***************************************************/
    $worksheet->write($j,$i,sprintf("%.02f",($kp[0][0] + $lp[0][0] + $jp[0][0])),$currency_format);
  }
  
  $datestart = date("Y-m-d",strtotime($datestart) + (60 * 60 * 24 * 7));
  if ($datestart > $dateend) {
    break;
  }
}

// We still need to explicitly close the workbook
$workbook->close();
//die();
?>
