<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Redeem_crud.php';
  
   class Redeem_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> crud = new TicketCrud( $context );
    parent::__construct( $context );
  }

	function parse() {
	   /*  's_unique_key' => string 'Aib9Zb5GXD0j0Ag' (length=15)
      's_id' => string '8' (length=1)
      'film_id' => string '4' (length=1)
      'ticket_code' => string 'CjrKRjAj5K' (length=10)
      'ticket_price' => string '2.99' (length=4)
      'x' => string '32' (length=2)
      'y' => string '18' (length=2)
    */
     $total_value = 0;
     $ticket_codes = explode(",",$this -> postVar("ticket_code"));
     foreach($ticket_codes as $code) {
       $vars = array("ticket_invite_code"=>$code,"ticket_paid_status"=>2,"ticket_status"=>0);
	     if ($this -> crud -> checkUnique($vars)) {
        $valids[$code] = $this -> crud -> Ticket -> getTicketTicketPrice();
        $total_value += $this -> crud -> Ticket -> getTicketTicketPrice();
        //In case there are too many codes attatched, stop adding
        if ($total_value > $this -> postVar("ticket_price")) {
          break;
        }
       }
	   }
     
     if ($this -> postVar("ticket_price") <= $total_value) {
     
       $order = new OrderManager_PageWidget( $this -> widget_vars, $this -> page_vars, $this -> context, null );
       
       //Make sure the user is set
       $order -> postOrderUser( false );
       //This isn't saved, but it is passed to the Order Manager
       $order -> orderuser -> setUserEmail( $this -> postVar("email_recipient") );
       
       //Create the Payment
       $order -> postOrder( "screening" );
       //Special to the "Ticket Redemption"
      
       //Put the Audience Reservation in the DB
       $order -> postItem( $this -> getVar("id") );
       
       //Fake a Payment Confirmation with this Ticket Code
       $order -> confirmOrder( $this -> crud -> Ticket -> getTicketInviteCode() );
       $order -> confirmItem( $order -> orderitems );
       
       //Just for simplicity, update the Audience item with the Ticket Code
       $order -> orderitems[0] -> setAudienceInviteCode( $this -> crud -> Ticket -> getTicketInviteCode() );
       $order -> orderitems[0] -> save();
       
       $sql = "update ticket set fk_user_id = ".$this -> sessionVar("user_id").",
                ticket_updated_at = '".now()."',
                ticket_status = 1
                where ticket_invite_code in ('".implode("','",$ticket_codes)."')";
       $this -> propelQuery($sql);
       
       //Ehem, not exactly correct, but good enough -- Shouldn't SET GET
       $this -> setGetVar("op",$this -> postVar("s_unique_key"));
       $obj = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Purchase/query/Screening_list_datamap.xml");
       $order -> sendOrderNotification( $obj );
       
       //Update the SOLR Search Engine
       $order -> postSolrOrder( $order -> crud -> Payment -> getPaymentId() );
       $this -> redirect("/screening/".$this -> postVar("s_unique_key")."/invite");
       
     } else {
      $this -> redirect("/screening/".$this -> postVar("s_unique_key")."/purchase?err=ticket");
     }
	 return $this -> widget_vars;
   
  }

  function doPost(){
     
     if ($this -> XMLForm -> validateForm()) {
        switch ($this -> getFormMethod()) {
          case "submit":
          $this -> crud -> write();
          break;
          case "delete":
          $this -> crud -> remove();
          break;
        }
      }
    
  }

  function doGet(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      $this -> pushItem();
    }
    
  }

  function drawPage(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      return $this -> returnForm();
    } elseif ($this ->getOp() == "list" ) {
      return $this -> returnList();
    }
    
  }

	}

  ?>
