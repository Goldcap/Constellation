<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/OrderHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/UserHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/CryptHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/MaxmindHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/ProductHelper.php");
  
  
 class AdminTerminal_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> crud = new UserOrderCrud( $context );
    parent::__construct( $context );
    
    $this -> doPost = false;
    $this -> isError = false;
    $this -> XMLForm = new XMLForm($this -> widget_name);
    $this -> XMLForm -> item_forcehidden = true;
  }

	function parse() {
	 
	 $fail = false;
    
	  if ($this -> XMLForm -> isPosted()) {
      $this -> doPost();
    }
    
    $this -> doGet();
    
    $this -> drawPage();
    
    return $this -> widget_vars;
    
  }

  function doPost(){
     
    $valid = true;
    if ($this -> postVar("user_order_product_price") === 0 ) {
      $valid = false;
    }
    if (cctypeMask($this -> postVar("user_order_cc_number"))=="Not A Valid") {
      $valid = false;
    }
    if (cvv2Mask($this -> postVar("user_order_cvv2"),$this -> postVar("user_order_cc_number"))=="Not A Valid") {
      $valid = false;
    }
    if ($this -> postVar("user_order_cc_exp_month")=="Select") {
      $valid = false;
    }
    if ($this -> postVar("user_order_cc_exp_year")=="Select") {
      $valid = false;
    }
    if ($this -> postVar("user_order_product_name")=="") {
      $valid = false;
    }
    if (! is_numeric($this -> postVar("user_order_product_price"))) {
      $valid = false;
    }
    if (! is_numeric($this -> postVar("user_order_product_quantity"))) {
      $valid = false;
    }
    if (! is_numeric($this -> postVar("user_order_ship_price"))) {
      $valid = false;
    }
    if (! is_numeric($this -> postVar("user_order_product_discount"))) {
      $valid = false;
    }
    
     //dump($this -> XMLForm -> validateForm());
     if (($this -> XMLForm -> validateForm()) && ($valid)) {
        switch ($this -> getFormMethod()) {
          case false:
          case "Submit":
            
            $this -> doPost = true;
            //Give them the "Please Wait" screen
            $order = new OrderManager_PageWidget( $this -> widget_vars, $this -> page_vars, $this -> context, $this -> XMLForm );
            //Put the stuff in the db!
            //Put the User In
            $order -> postOrderUser();

            //Check for this SKU and User, and redirect if exists
            //Create the Order
            $order -> postOrder();
            //Put the Item in the DB
            $order -> postGenericItem();
            //Send info to PayFlow
            $order -> processGenericMOneOrder();
            
            //Record the billing address for future
            $order -> addOrderAddresses();
            //Update the SOLR Search Engine
            $order -> postSolrOrder();
            
            $this -> XMLForm -> item["order_total"] = $this -> postVar("user_order_product_price") * $this -> postVar("user_order_product_quantity");
                  
          break;
          case "delete":
            
            //Update the SOLR Search Engine
            $solr = new SolrManager_PageWidget(null, null, $this -> context);
            $solr -> execute("delete","order",$this -> crud -> UserOrder -> getUserOrderId());
            
            $this -> crud -> remove();
          break;
        }
      } else {
     
        //dump($this -> XMLForm -> objValidateDoc-> errors);
        $this -> isError = true;
        foreach ($this -> XMLForm -> objValidateDoc-> errors as $key => $error) {
          if ($key == "user_order_user_fname") {
            $str .= "Your First Name is empty.<br />";
          }
          if ($key == "user_order_user_lname") {
            $str .= "Your Last Name is empty.<br />";
          } 
          if ($key == "user_order_billing_address") {
            $str .= "Your Address is empty.<br />";
          }
          if ($key == "user_order_billing_city") {
            $str .= "Your City is empty.<br />";
          }
          if (($this -> postVar("user_order_billing_country") != "US") && ($key == "user_order_billing_state_text")) {
            $str .= "Your State is empty.<br />";
          }
          if (($this -> postVar("user_order_billing_country") == "US") && ($key == "user_order_billing_state_select")) {
            $str .= "Your State is empty.<br />";
          }
          if ($key == "user_order_billing_zip") {
            $str .= "Your Postal Code is empty.<br />";
          }
          if ($key == "user_order_billing_phone") {
            $str .= "Your Phone Number is empty.<br />";
          }
          if ($key == "user_order_user_email") {
            $str .= "Your Email Address is empty.<br />";
          }
          /* else {
            $str .= $key."<br />";
          }*/
          
        }
        if ($key == "user_order_ship_type" ) {
          $str .= "Please select a shipping type.<br />";
        }
        if ($key == "user_order_product_price" ) {
          $str .= "Total amount must be greater than zero.<br />";
        }
        if ($this -> postVar("user_order_product_name")=="") {
          $str .= "Your Product Name is empty.<br />";
        }
        if (! is_numeric($this -> postVar("user_order_product_price"))) {
           $str .= "Your Product Price is incorrect.<br />";
        }
        if (! is_numeric($this -> postVar("user_order_product_quantity"))) {
          $str .= "Your Product Quantity is empty.<br />";
        }
        if (! is_numeric($this -> postVar("user_order_ship_price"))) {
          $str .= "Your Ship Price is incorrect.<br />";
        }
        if (! is_numeric($this -> postVar("user_order_product_discount"))) {
          $str .= "Your Discount Price is incorrect.<br />";
        }
        if (($this -> postVar("user_order_billing_country")!="US") && (($this -> postVar("user_order_billing_state_text")=="") || ($this -> postVar("user_order_billing_state_text")=="Select One"))) {
          $str .= "Your State is empty.<br />";
        }
        if ($this -> postVar("user_order_cc_number")=="") {
          $str .= "Please enter your credit card number.<br />";
        }
        if (cctypeMask($this -> postVar("user_order_cc_number"))=="Not A Valid") {
          $str .= "Your Credit Card Number is invalid<br />";
        }
        if (cvv2Mask($this -> postVar("user_order_cvv2"),$this -> postVar("user_order_cc_number"))=="Not A Valid") {
          $str .= "Your Card Code is invalid<br />";
        }
        if ($this -> postVar("user_order_cc_exp_month")=="Select") {
          $str .= "Your Card Code Expiration Month is invalid.<br />";
        }
        if ($this -> postVar("user_order_cc_exp_year")=="Select") {
          $str .= "Your Card Code Expiration Year is invalid.<br />";
        }
        //dump($_POST);
        $this -> XMLForm -> addError("carderror","<span style='float:left;background:#F7D7DE;border:1px solid #D66573;width:92%;padding:10px;padding-bottom:20px;margin-bottom:10px;'>Your information was incorrect, please check your entry and try again.<br /><br />".$str."</span>");
      }
    
  }

  function doGet(){
    
    
  }

  function drawPage(){
      
    if (! $this -> isError) {
    if ($this -> getVar("e") == "-2") {
      $this -> XMLForm -> addError("Download","<span style='float:left;background:#F7D7DE;border:1px solid #D66573;width:92%;padding:10px;padding-bottom:20px;margin-bottom:10px;'>Your information was incorrect, please check your entry and try again.<br /><br />You can also call Customer Service at 1-(404)-592-2901.</span>");
    }elseif ($this -> getVar("e") == "-1") {
      $this -> XMLForm -> addError("Download","<span style='float:left;background:#F7D7DE;border:1px solid #D66573;width:92%;padding:10px;padding-bottom:20px;margin-bottom:10px;'>Your credit card was declined, please use a different card and try again.</span>");
    }elseif ($this -> getVar("e")) {
      $this -> XMLForm -> addError("Download","<span style='float:left;background:#F7D7DE;border:1px solid #D66573;width:92%;padding:10px;padding-bottom:20px;margin-bottom:10px;'>There was an error in processing your credit card, please check your entry and try again.<br /><br />You can also call Customer Service at 1-(404)-592-2901.</span>");
    }
    }
    $this -> form = $this -> returnForm();
    
    $this -> widget_vars = array("form"=>$this -> form["form"],"item"=>$this -> product["data"][0],"auth"=>$this -> isAuthenticated());
  
  }

	}

  ?>
