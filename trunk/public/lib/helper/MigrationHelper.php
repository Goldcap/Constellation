<?php

function fipsupdate( $limit = 1000 ) {
  
  $d = new WTVRData( null );
  
  
  cli_text("Pulling unindexed orders","red","white"); 
  $truncSql = "select *
              from user_order
              where fips_code is null
              and user_order_billing_country = 'US'
              and user_order_status > -21
              order by user_order_id desc
              limit ".$limit;
        
  $rs = $d -> propelQuery($truncSql);
  while ($rs->next()) {
    if (is_numeric($rs->get(13))) {
      $nsql = "select fips
              from fips_zip
              where fips_state = '".$rs->get(12)."'
              and fips_zip_start <= ".$rs->get(13)."
              and fips_zip_end >= ".$rs->get(13)."
              limit 1;";
      $nrs = $d -> propelQuery($nsql);
      
      while ($nrs->next()) {
        $isql = "update user_order
                set fips_code = ".$nrs->get(1)."
                where user_order_id = ".$rs->get(1);
        $d -> propelQuery($isql);
      }
    cli_text("Inserted " . $rs -> get(1),"green");
    } else {
    cli_text("Skipped " . $rs -> get(1)." with zip ". $rs -> get(13),"red");
    }
  }
}

function nullCVV2() {
  $c = new Criteria;
  $c->add(UserOrderPeer::USER_ORDER_CC_CVV2,null,Criteria::ISNOTNULL);
  $c->setLimit(10000);
  
  $orders = UserOrderPeer::doSelect( $c );
  
  foreach ($orders as $order ) {
    
    //cli_text("Setting NULL for order ".$order -> getUserOrderGuid(),"red","white"); 
    $order -> setUserOrderCcCvv2( null );
    $order -> save();
    
    $solr = new SolrManager_PageWidget(null, null, sfContext::getInstance() );    
    $solr -> execute("add","order",$order -> getUserOrderId());
    
  }
}

function captureSales( $date ) {
  
  if ($date == "") {
    $date = formatdate(now(),"MDY-");
    //$date = formatdate(dateDiff(now(),-1,"d"),"MDY-");
  }
  
  $startdate = $date." 00:00:00";
  $enddate = $date." 23:59:59";
  $d = new WTVRData( null );
  
  $am = new SolrManager_PageWidget( null, null, null );  
  $am -> action = "add";
  $am -> object_type = "orders";   
  $am -> object_id = $startdate."|".$enddate;
  
  //Clear All Paypal Orders for the day
  $sql = "update user_order set user_order_status = 3 where user_order_status = 2 and user_order_date < '".$enddate."' and user_order_payment_processor = 'Paypal';";
  $rs = $d -> propelQuery($sql);
 
  //Get all unclear sales
  //$sql = "select user_order_id, user_order_transaction_number from user_order where user_order_status > 0 and user_order_status < 3 and user_order_date > '".$startdate."' and  user_order_date < '".$enddate."' and user_order_payment_processor = 'Verisign';";
  $sql = "select user_order_id, user_order_transaction_number from user_order where user_order_status > 1 and user_order_status < 3 and user_order_date < '".$enddate."' and user_order_payment_processor = 'MerchantOne';";
  $rs = $d -> propelQuery($sql);
 
  cli_text("Starting Merchant One Capture at ".formatDate(null,"pretty"),"blue","white");
  while ($rs->next()) {
    //$PFPro = new PFPro_Gateway();
    $PFPro = new MerchantOne_Gateway();
    cli_text("Capture order ".$rs -> get(1),"blue","white");

    $PFPro -> trans_id = $rs->get(2);
    $PFPro -> captureTransaction();

    switch ($PFPro -> nvpArray["response_code"]) {
      /*
      case "111":
        $sql = "update user_order 
              set user_order_status = -1,
              user_order_transaction_number = '".$PFPro -> nvpArray["PNREF"]."'
              where user_order_id = ".$rs -> get(1).";";
      break;
      */
      //Correct Result
      case "100":
        $sql = "update user_order 
              set user_order_status = 3
              where user_order_id = ".$rs -> get(1).";";
        break;
        //Removed this from update
        //,user_order_transaction_number = '".$PFPro -> nvpArray["PNREF"]."'
      //Duplicate Result
      case "300":
        $sql = "update user_order 
              set user_order_status = 3
              where user_order_id = ".$rs -> get(1).";";
      break;
        //Removed this from update
        //,user_order_transaction_number = '".$PFPro -> nvpArray["PNREF"]."'
      /*
      case "25":
        $sql = "update user_order 
              set user_order_status = 1,
              user_order_transaction_number = '".$PFPro -> nvpArray["PNREF"]."'
              where user_order_id = ".$rs -> get(1).";";
      break;
      */
      default:
        //dump($PFPro -> nvpArray);
      break;
    }
    $d -> propelQuery($sql);
  }
  
  cli_text("Captured orders ".$am -> object_id." to Solr.","blue","white");
  
  $am -> addDocument();
  
  QAMail("Done Capturing Orders for ".$date,"Paypal Order Capture ".$date);
  
  return true;
}

?>
