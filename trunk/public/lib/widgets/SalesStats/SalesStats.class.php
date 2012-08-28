<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/SalesStats_crud.php';
  
   class SalesStats_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	  $this -> XMLForm -> item_forcehidden = true;
    $this -> crud = new SalesStatCrud( $context );
    parent::__construct( $context );
  }

	function parse() {
	  
	  //php symfony widget exec SalesStats admin dev "2011-07-08"
    if ($this -> as_cli) {
      if ($this ->widget_vars["args"][0]) {
        $date = formatDate($this ->widget_vars["args"][0],"TSFloor");
        $start_date = formatDate( $date,"TSFloor");
        $end_date = formatDate( $date,"TSCeiling");
      } else {
        $date = now();
        $start_date = formatDate( dateDiff($date,-1),"TSFloor");
        $end_date = formatDate( dateDiff($date,-1),"TSCeiling");
      }
      $this -> generateSalesStats( true, $start_date, $end_date );
      die();
    }
    
	  if ($this -> postVar("stats_start_date")) {
     sfConfig::set("timespan",formatDate($this -> postVar("stats_start_date"),"TSFloor"));
    } else {
     sfConfig::set("timespan",formatDate(null,"TSFloor"));
    }
   
    $savecount = true;
    if ($this -> postVar("stats_span")) {
    
      if ($this -> postVar("stats_span") == "Today") {
        $start_date = formatDate(null,"TSFloor");
        $end_date = formatDate(null,"TSCeiling");
        $savecount = false;
      }
      
      if ($this -> postVar("stats_span") == "Yesterday") {
        $start_date = formatDate( dateDiff(now(),-1),"TSFloor");
        $end_date = formatDate( dateDiff(now(),-1),"TSCeiling");
      }
      
    } else {
    
      if ($this -> postVar("stats_start_date")) {
        //$start_date = $this -> postVar("stats_start_date");
        $start_date = formatDate($this -> postVar("stats_start_date"),"TSFloor");
        $end_date = formatDate($this -> postVar("stats_start_date"),"TSCeiling");
      } else {
        $start_date = formatDate(null,"TSFloor");
        $end_date = formatDate(null,"TSCeiling");
      }
      
      /*
      if ($this -> postVar("stats_end_date")) {
        $end_date = $this -> postVar("stats_end_date");
      } else {
        $end_date = formatDate(null,"TSCeiling");
      }
      */
    }
    $savecount = false;
    $result = $this -> generateSalesStats( $savecount, $start_date, $end_date );
    
    if (is_array($result)) {
      $list = $result[0];
    }
    
    $this -> widget_vars["total"] = ($result[1] == null) ? 0 : $result[1];
    $this -> widget_vars["total_paypal"] = ($result[2] == null) ? 0 : $result[2];
    $this -> widget_vars["total_paypal_wpp"] = ($result[3] == null) ? 0 : $result[3];
    
    //$this -> addJs("/js/timeplot-api.js");
    //$this -> addJs("/js/timeplot.js");
    
    $searchform = $this -> XMLForm -> drawForm();
    $this -> widget_vars["searchform"] = $searchform["form"];
      
    $this -> widget_vars["form"] = $list["form"];
    $this -> XMLForm -> validated= false;
   
    return $this -> widget_vars;
    
  }

  function generateSalesStats( $savecount, $start_date, $end_date ) {
      
      //$PFPro = new PaypalWPP_Gateway( $this -> context );
      //$PFPro -> getDailyReport( formatDate($start_date,"TSFloor") );
      
      $PPal = new PaypalInterface();
      $PPal -> searchTransactions( $start_date, $end_date );
      
      //$pfrs = $PFPro -> mapToStyroformArray($start_date, $end_date);
      $pprs = $PPal -> mapToStyroformArray($start_date, $end_date);
      
      //$rs = $pfrs;
      $rs = $pprs;
      //$rs = array_merge($pfrs,$pprs);
      
      foreach($rs as $rs_item) {
        
        if ($rs_item["user_order_vtype"] == "Delayed Capture") {
          continue;
        }
        
        /*
        $item["user_order_id"] = $ppresult['L_TRANSACTIONID'];
        $item["user_order_user_fname"] = $ppresult['L_NAME'];
        $item["user_order_user_lname"] = "null";
        $item["user_order_user_email"] = $ppresult['L_EMAIL'];
        $item["user_order_guid"] = $ppresult['L_TRANSACTIONID'];
        $item["user_order_total_fs"] = $ppresult['L_AMT'];
        $item["user_order_fee"] = $ppresult["L_FEEAMT"];
        $item["user_order_total_net"] = $ppresult["L_NETAMT"];
        $item["user_order_status"] = 2;
        $item["user_order_vtype"] = "Paypal ".$ppresult["L_TYPE"];;
        $item["user_order_payment_processor"] = "Paypal";
        */
        if ($savecount) {
          $tx = new PaypalTransactionCrud( $this -> context );
          $tx -> populate("paypal_transaction_guid",$rs_item["user_order_guid"]);
          $tx -> setPaypalTransactionGuid( $rs_item["user_order_guid"] );
          $tx -> setPaypalTransactionAmount( $rs_item["user_order_total_fs"] );
          $tx -> setPaypalTransactionFee( $rs_item["user_order_fee"] );
          $tx -> setPaypalTransactionNet( $rs_item["user_order_total_net"] );
          $tx -> setPaypalTransactionEmail( $rs_item["user_order_user_email"] );
          $tx -> setPaypalTransactionName( $rs_item["user_order_user_fname"] );
          $tx -> setPaypalTransactionDate( $rs_item["user_order_date"] );
          $tx -> setPaypalTransactionTimestamp( $rs_item["user_order_timestamp"] );
          $tx -> setPaypalTransactionType( $rs_item["user_order_vtype"] );
          $tx -> setPaypalTransactionStatus( $rs_item["user_order_transaction_status"] );
          $tx -> save();
          
        }
        
        $rs_final[] = $rs_item;
        
        //Only add Download Items between $0 and $20
        if (($rs_item["user_order_vtype"] == "Paypal Payment")
              && ($rs_item["user_order_status"] > 0)){
              
          if ($savecount) {
            $total_paypal += $rs_item["user_order_total_fs"];
          }
          $total_paypal += $rs_item["user_order_total_fs"];
          $total += $rs_item["user_order_total_fs"];
        }
        $rs_date[] = $rs_item["user_order_date"];
      }
      
      if ($savecount) {
      
        //print out the $report array
      	$stat = new SalesStatCrud( null, null );
      	$vars = array("sales_stat_date"=>formatDate($start_date,"MDY-"),"sales_stat_type"=>1);
      	$stat -> checkUnique($vars);
      	$stat -> setSalesStatDate(formatDate($start_date,"MDY-"));
      	if ($total_paypal > 0) {
          $stat -> setSalesStatSalesPaypal($total_paypal);
      	} else {
          $stat -> setSalesStatSalesPaypal(0);
        }
      	$stat -> setSalesStatType(1);
        $stat -> save();
      }
      
      if (is_array($rs_date)) {
        array_multisort($rs_date, SORT_DESC, $rs_final);
      } else {
        $rs_final = array();
      }
      
      //$args["location"] = "/var/www/html/sites/drop";
      //$list = $this -> createExcel( $rs_final, "paypal_search", "Daily Orders", $args );
      //dump($list);
      
      if ($savecount) {
        $list = null;
      } else {
        $list = $this -> createList( $rs_final, "paypal_search", "Daily Orders", false );
      }
      return array($list, $total, $total_paypal, $total_paypal_wpp );
  }

	}

  ?>
