<?php

class PaypalWppOrders extends Widget_PageWidget {

  var $order;
  
  public function __construct( $order ) {
    $this -> order = $order;
    parent::__construct( $order -> context );
  }
  
  function checkPrior ( $type, $id, $key, $date=null, $time=null ) {
    switch ($type){
      case "screening":
        return $this -> checkPriorScreening( $id, $key );
        break;
      case "host":
        return $this -> checkPriorHost( $id, $key, $date, $time );
        break;
      case "subscription":
        return $this -> checkPriorSubscription( $id, $key, $date, $time );
        break;
    }
  }
  
  public function checkPriorScreening( $id, $key ) {
    $sql = "select payment_unique_code,
            audience.audience_id
            from payment
            inner join audience
            on payment.fk_audience_id = audience.audience_id
            where payment.fk_user_id = ".$id." 
            and audience.fk_screening_unique_key = '".$key."' 
            and payment_status > 1;";
    
    $res = $this -> propelQuery($sql);
    while ($row = $res->fetch()) {
      if($row[0] != ''){
        $item = AudiencePeer::retrieveByPk($row[1]);
        $this -> order -> orderitems[] = $item;
        putLog("USER:: ". $id . " | MESSAGE:: PaypalWppOrders - checkPriorScreening is: " . $row[0]);
        return $row[0];
      }
    }
    return false;
  }
  
  public function checkPriorSeat( $id, $key ) {
    
    if ($id < 1) {
      return false;
    }
    /*
    $sql = "select audience_invite_code,
            audience.audience_id,
            payment.fk_user_id
            from audience
            inner join payment
            on payment.payment_id = audience.fk_payment_id
            where payment.fk_user_id = ".$id." 
            and audience.fk_screening_unique_key = '".$key."' 
            and audience_paid_status > 1;";
    */
    $sql = "select audience_invite_code,
            audience.audience_id,
            audience.fk_user_id
            from audience
            where audience.fk_screening_unique_key = '".$key."' 
            and audience.fk_user_id = ".$id."
            and audience_paid_status > 1;";
            
    $res = $this -> propelQuery($sql);
    while ($row = $res->fetch()) {
      if($row[0] != ''){
        $item = AudiencePeer::retrieveByPk($row[1]);
        $this -> order -> orderitems[] = $item;
        if ($item -> getFkUserId() != $row[2]) {
          return false;
        }
        return $row[0];
      }
    }
    return false;
  }
  
  public function checkPriorOwner( $id, $key ) {
    if (is_null($id)) {
      return false;
    }
    $sql = "select audience_invite_code,
            audience.audience_id,
            payment.fk_user_id
            from audience
            inner join payment
            on payment.payment_id = audience.fk_payment_id
            where payment.fk_user_id = ".$id." 
            and audience.fk_screening_unique_key = '".$key."' 
            and audience_paid_status > 1;";
    
    $res = $this -> propelQuery($sql);
    while ($row = $res->fetch()) {
      if($row[0] != ''){
        $item = AudiencePeer::retrieveByPk($row[1]);
        $this -> order -> orderitems[] = $item;
        if ($id == $row[2]) {
          return true;
        }
        return false;
      }
    }
    return false;
  }
  
  //this checks if a user has purchsed a prior screening
  function checkPriorHost( $id, $key, $date, $time ) {
    return false;
    //Do we get the key from the URL?
    //dump($id . "+" . $key);
    $sql = "select screening_unique_key,
            screening_id
            from screening
            where screening.fk_host_id = ".$id." 
            and screening.fk_film_id = '".$key."' 
            and screening_date = '".$date."'
            and screening_time = '".$time."'
            and payment_status > 1;";
    
    $res = $this -> propelQuery($sql);
    while ($row = $res->fetch()) {
      if($row[0] > 0){
        $item = ScreeningPeer::retrieveByPk($row[1]);
        $this -> order -> orderitems[] = $item;
        return $row[0];
      }
    }
    return false;
    
  }
  
  //this checks if a user has purchsed a prior subscription
  //Returns the key if so
  function checkPriorSubscription( $id, $key, $date, $time ) {
    return false;
    //Do we get the key from the URL?
    //dump($id . "+" . $key);
    $sql = "select screening_unique_key
            from screening
            where screening.fk_host_id = ".$id." 
            and screening.fk_film_id = '".$key."' 
            and screening_date = '".$date."'
            and screening_time = '".$time."'
            and payment_status > 1;";
    
    $res = $this -> propelQuery($sql);
    while ($row = $res->fetch()) {
      if($row[0] > 0){
        return $row[0];
      }
    }
    return false;
    
  }
  
  public function postOrder( $type = "screening" ) {
    
    //If we aren't POSTING data, return the object
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
      return true;
    }
    
    //Put Order Post Data in the database
    if ($this -> postVar("first_name")) {
      $this -> order -> crud ->  setPaymentFirstName($this -> postVar("first_name"));
      $this -> order -> crud ->  setPaymentLastName($this -> postVar("last_name"));
    } else {
      $this -> order -> crud ->  setPaymentFirstName($this -> sessionVar("user_fullname"));
    }
    $this -> order -> crud ->  setPaymentType($type);
    $this -> order -> crud ->  setPaymentEmail($this -> postVar("email"));
    $this -> order -> crud ->  setPaymentBAddr1($this -> postVar("b_address1"));
    $this -> order -> crud ->  setPaymentBAddr2($this -> postVar("b_address2"));
    $this -> order -> crud ->  setPaymentBCity($this -> postVar("b_city"));
    $this -> order -> crud ->  setPaymentBState($this -> postVar("b_state"));
    $this -> order -> crud ->  setPaymentBZipcode($this -> postVar("b_zipcode"));
    $this -> order -> crud ->  setPaymentBCountry($this -> postVar("b_country"));
    //If we're hosting, we haven't created a screening yet.
    if ($type == "screening") {
      $c = new Criteria();
			$c->add(ScreeningPeer::SCREENING_UNIQUE_KEY,$this -> getVar("rev"));
      $screeningObj = ScreeningPeer::doSelect($c);
      
      $this -> order -> crud ->  setFkScreeningId($screeningObj[0] -> getScreeningId());
      $this -> order -> crud ->  setFkScreeningName($screeningObj[0] -> getScreeningName());
      $this -> order -> crud ->  setPaymentInvites($this -> postVar("invite_count"));
      $this -> order -> crud ->  setFkFilmId($this -> getVar("op"));
      $this -> order -> crud ->  setPaymentAmount($this -> postVar("ticket_price"));
      $film = $this -> getVar("op");
      $screening = $screeningObj[0] -> getScreeningId();
    } elseif ( $type == "host" ) {
      $this -> order -> crud ->  setPaymentInvites( $this -> postVar("host_invite_count") );
      $this -> order -> crud ->  setFkFilmId($this -> postVar("film_id"));
      $this -> order -> crud ->  setPaymentAmount($this -> postVar("setup_price"));
      $film = $this -> postVar("film_id");
      $screening = null;
    } elseif ( $type == "subscription" ) {
      if ($this -> postVar("step") == "gift") {
        $total_tickets = $this -> postVar("gift_ticket_number");
        $total = $this -> postVar("fld-gift_ticket_price") * $this -> postVar("gift_ticket_number");
      } elseif ($this -> postVar("step") == "subscription") {
        $total_tickets = $this -> postVar("fld-subscription_period") * $this -> postVar("subscription_ticket_number");
        $total = $this -> postVar("fld-subscription_ticket_price") * $total_tickets;
      }
      $this -> order -> crud -> setPaymentAmount($total);
      $film = null;
      $screening = null;
    }
    //Generate the Order Id
    $this -> order -> orderguid = setUserOrderGuid();
    
    //Place the User in the Order
    if (($this -> order -> user_id) && ($this -> order -> user_id > 0)) {
      $this -> order -> crud -> setFkUserId( $this -> order -> user_id );
    } elseif (($this -> order -> orderuser) && ($this -> order -> orderuser -> getUserId() > 0)) {
      $this -> order -> crud -> setFkUserId( $this -> order -> orderuser -> getUserId() );
    } elseif ($this -> sessionVar("user_id") > 0) {
      $this -> order -> crud -> setFkUserId( $this -> sessionVar("user_id") );
    }else {
      throw new Exception('Expected User is unavailable.');
        return false;
    }
    
    $this -> order -> crud -> setPaymentUniqueCode( $this -> order -> orderguid );
    $this -> order -> crud -> setPaymentCreatedAt( now() );
    $this -> order -> crud -> setPaymentIp( REMOTE_ADDR() );
    $this -> order -> crud -> setPaymentSiteId( sfConfig::get("app_site_id") );
    
    //Since this is an optional field, we need to clear if its non-valued
    if ($this -> postVar("b_address2") == "") {
      $this -> order -> crud -> setPaymentBAddr2( null );
    }
    
    //Add some special items
    $this -> order -> crud -> setPaymentCardType( cctypeMask($this -> postVar("credit_card_number")) );
    $this -> order -> crud -> setPaymentLastFourCcDigits( right($this -> postVar("credit_card_number"),4) );
    $this -> order -> crud -> setPaymentCcExp( sprintf("%02d",$this -> postVar("expiration_date_month")).$this -> postVar("expiration_date_year") );
    $this -> order -> crud -> setPaymentOrderProcessor( "paypal_wpp" );
    
    //We are not saving CVV2 for the upcoming transaction, and are required to delete later by PCI DSS
    //$this -> crud -> setPaymentCvv2( $this -> postVar("card_verification_number") );
    
    $this -> order -> crud -> save();
    
    recordBeaconAction( $this-> sessionVar("user_id"), $type, $film, $screening, $this -> order -> crud -> Payment -> getPaymentId() );
    
    $picture = false;
    $description = false;
    if ($type == "screening") {
      $this -> setGetVar("screening",$screeningObj[0] -> getScreeningUniqueKey());
      $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/ScreeningByKey_list_datamap.xml");
      $film = $list["data"][0]["screening_name"] != '' ? $list["data"][0]["screening_name"]: $list["data"][0]["screening_film_name"];
      if (file_exists(sfConfig::get("sf_app_dir")."/templates/text/invite_".$list["data"][0]["screening_film_id"].".txt")) {
			  $vals = file_get_contents(sfConfig::get("sf_app_dir")."/templates/text/invite_".$list["data"][0]["screening_film_id"].".txt");
			  list($picture,$description,$message) = explode("|",$vals);
			} else {
				$picture = 'http://www.constellation.tv/uploads/screeningResources/'.$list["data"][0]["screening_film_id"].'/logo/film_logo'.$list["data"][0]["screening_film_logo"];
      	$description = $list["data"][0]["screening_description"] != '' ? $list["data"][0]["screening_description"] : $list["data"][0]["screening_film_info"];
    		$message = '';
			}
			$destination = "http://www.constellation.tv/theater/".$screeningObj[0] -> getScreeningUniqueKey();
		}
    if ($type == "host") {
      $this -> setGetVar("op",$this -> postVar("film_id"));
      $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/FilmView/query/Film_list_datamap.xml");
      $film = $list["data"][0]["film_name"];
      if (file_exists(sfConfig::get("sf_app_dir")."/templates/text/invite_".$list["data"][0]["film_id"].".txt")) {
			  $vals = file_get_contents(sfConfig::get("sf_app_dir")."/templates/text/invite_".$list["data"][0]["film_id"].".txt");
			  list($picture,$description,$message) = explode("|",$vals);
			} else {
				$picture = 'http://www.constellation.tv/uploads/screeningResources/'.$list["data"][0]["film_id"].'/logo/film_logo'.$list["data"][0]["film_logo"];
      	$description = $list["data"][0]["film_info"];
    		$message = '';
			}
			$destination = "http://www.constellation.tv/film/".$list["data"][0]["film_id"];
		}
    if (($this -> getUser() -> getAttribute("user_facebook")) && ($this -> getUser() -> getAttribute("user_facebook") != '')) {
      $id = $this -> getUser() -> getAttribute("user_facebook");
      if ($message == '') {
				$message = "I am attending '".$film."'";
      }
			$beacon = "?".getBeaconByType( $this -> sessionVar("user_id"), 2);
      sendFacebookWalls( array($id), false, false, $destination, $description, $message, $picture, $beacon );
    } else {
      $id = "ConstellationTV";
      $message = $this -> postVar("first_name")." just purchased a ticket to '".$film."' on Constellation, 'Your Online Movie Theater'";
    }
    
    //Note, this is saved to SOLR in the Purchase Widget, not here
    
  }
  
  public function updateOrder() {
    
    //If we aren't POSTING data, return the object
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
      return true;
    }
    
    //Put Order Post Data in the database
    $this -> order -> crud -> setPaymentFirstName($this -> postVar("first_name"));
    $this -> order -> crud -> setPaymentLastName($this -> postVar("last_name"));
    $this -> order -> crud -> setPaymentEmail($this -> postVar("email"));
    $this -> order -> crud -> setPaymentBAddr1($this -> postVar("b_address1"));
    $this -> order -> crud -> setPaymentBAddr2($this -> postVar("b_address2"));
    $this -> order -> crud -> setPaymentBCity($this -> postVar("b_city"));
    $this -> order -> crud -> setPaymentBState($this -> postVar("b_state"));
    $this -> order -> crud -> setPaymentBZipcode($this -> postVar("b_zipcode"));
    $this -> order -> crud -> setPaymentBCountry('US');
    if ( $this -> postVar("host_invite_count") ) {
      $this -> order -> crud ->  setPaymentInvites( $this -> postVar("host_invite_count") );
    }
    if ( $this -> postVar("invite_count") ) {
      $this -> order -> crud ->  setPaymentInvites( $this -> postVar("invite_count") );
    }
    if ($this -> postVar("setup_price")) {
      $this -> order -> crud ->  setPaymentAmount($this -> postVar("setup_price"));
    }
		//Add some special items
    $this -> order -> crud -> setPaymentLastFourCcDigits( right($this -> postVar("credit_card_number"),4) );
    $this -> order -> crud -> setPaymentCcExp( sprintf("%02d",$this -> postVar("expiration_date_month")).$this -> postVar("expiration_date_year") );
    
    //We are not saving CVV2 for the upcoming transaction, and are required to delete later by PCI DSS
    //$this -> crud -> setPaymentCvv2( $this -> postVar("card_verification_number") );
    
    $this -> order -> crud -> save();
    
    if ($this -> order -> orderuser -> getUserEmail() == "") {
      $this -> order -> orderuser -> setUserEmail($this -> postVar("email"));
      $this -> order -> orderuser -> save();
      $solr = new SolrManager_PageWidget(null, null, $this -> context);
      $solr -> execute("add","user",$this -> order -> orderuser -> getUserId());
    }
    
  }
  
  //This puts the items in the Database, "pending..."
  public function postItem( $obj=null, $type="screening") {
    switch ($type){
      case "screening":
        $this -> postScreeningItem($obj);
        break;
      case "host":
        $this -> postHostItem($obj);
        break;
      case "subscription":
        $this -> postSubscriptionItem($obj);
        break;
    }
  }
  
  public function postScreeningItem( $obj=null, $id=null ) {
    
    //Add the item to the database
    $item = new AudienceCrud( $this -> order -> context );
    $vars = array("fk_user_id"=>$this-> order -> orderuser -> getUserId(),"fk_payment_id"=>$this -> order -> crud -> Payment -> getPaymentId(),"fk_screening_unique_key"=>$obj["data"][0]["screening_unique_key"] );
    $item -> checkUnique( $vars );
    $item -> setFkScreeningUniqueKey( $obj["data"][0]["screening_unique_key"] );
    $item -> setFkScreeningId( $obj["data"][0]["screening_id"] );
    $item -> setFkUserId( $this -> order -> orderuser -> getUserId() );
    $item -> setAudienceTicketPrice( $this -> postVar("ticket_price") );
    $item -> setFkPaymentId( $this -> order -> crud -> Payment -> getPaymentId() );
    $item -> setAudiencePaidStatus( 0 );
    $item -> setAudienceIpAddr( REMOTE_ADDR() );
    $item -> setAudienceCreatedAt( now() );
    $item -> setAudienceStatus( 0 );
    if ($this -> order -> orderuser -> getUserUsername() != '') {
      $item -> setAudienceUsername( $this -> order -> orderuser -> getUserUsername() );
    } else {
      $item -> setAudienceUsername( $this -> sessionVar("user_username") );
    }
    $code = setUserOrderTicket();
    $item -> setAudienceInviteCode( $code );
    $item -> setAudienceShortUrl( '/theater/' . $obj["data"][0]["screening_unique_key"] . '/' . $code );
    //$item -> setAudiencePaidStatus( 2 );
    
    $item -> save(); 
    
    $solr = new SolrManager_PageWidget(null, null, $this -> context);
    $solr -> execute("add","audience",$item -> Audience -> getAudienceId());
      
    //Cross reference the item in the payment
    $this -> order -> crud -> setFkAudienceId( $item -> Audience -> getAudienceId() );
    $this -> order -> crud -> save();
    
    if (forTesting()) {
      //dump($item -> UserOrderProduct);
    }
    
    $this -> order -> ordertotal = str_replace("$","",$this -> postVar("ticket_price"));
    $this -> order -> orderskus[] = $obj;
    $this -> order -> orderitems[0] = $item;
  }
  
  //This allows admins to view any Screening
  public function postAdminScreeningItem( $obj=null ) {
    
    //Add the item to the database
    $item = new AudienceCrud( $this -> order -> context );
    $vars = array("fk_user_id"=> $this -> getUser() -> getAttribute("user_id"),"fk_screening_unique_key"=>$obj["data"][0]["screening_unique_key"] );
    $item -> checkUnique( $vars );
    if ($item -> Audience -> getAudienceInviteCode() != '') {
      return $item;
    }
    $item -> setFkScreeningUniqueKey( $obj["data"][0]["screening_unique_key"] );
    $item -> setFkScreeningId( $obj["data"][0]["screening_id"] );
    $item -> setFkUserId( $this-> getUser() -> getAttribute("user_id") );
    $item -> setAudienceTicketPrice( 0 );
    $item -> setFkPaymentId( 0 );
    $item -> setAudiencePaidStatus( 2 );
    $item -> setAudienceIpAddr( REMOTE_ADDR() );
    $item -> setAudienceCreatedAt( now() );
    $item -> setAudienceStatus( 0 );
    $item -> setAudienceUsername( $this -> sessionVar("user_username") );
    $code = setUserOrderTicket();
    $item -> setAudienceInviteCode( $code );
    $item -> setAudienceShortUrl( '/theater/' . $obj["data"][0]["screening_unique_key"] . '/' . $code );
    //$item -> setAudiencePaidStatus( 2 );
    
    $item -> save(); 
    
    //Update the SOLR Search Engine
    $solr = new SolrManager_PageWidget(null, null, $this -> context);
    $solr -> execute("add","audience",$item -> Audience -> getAudienceId());
    
    return $item -> Audience;
  }
  
  public function postHostItem( $obj ) {
    
    $user = getUserById( $this -> sessionVar("user_id") );
    
    //Convert using TZ Settings
    //kickdump(formatDate($this -> postVar("host_date") . " " . $this -> postVar("host_time"),"pretty"));
    //$date = setTzDate($this -> postVar("host_date") . " " . $this -> postVar("host_time"));
    //dump(formatDate($date,"pretty"));
    
    $screening = new ScreeningCrud( $this -> order -> context );
    //Make sure this user isn't hosting the same time
    //For the same film...
    $vars = array("fk_host_id"=>$this -> sessionVar("user_id"),
                  "fk_film_id"=>$this -> postVar("film_id"),
                  "screening_date"=>formatDate($this -> postVar("host_date"),"MDY-"),
                  "screening_time"=>formatDate($this -> postVar("host_time"),"time"),
                  "screening_default_timezone_id"=>$this -> postVar("tzSelector"),
                  "screening_paid_status"=>2);
    $screening->checkUnique( $vars );
    
    $screening->setFkHostId( $this -> sessionVar("user_id") );
    $screening->setFkFilmId( $this -> postVar("film_id") );
    $screening->setFkPaymentId( $this -> order -> crud -> Payment -> getPaymentId() );
    $screening->setScreeningDate( formatDate($this -> postVar("fld-host_date"),"MDY-") );
    $screening->setScreeningTime( $this -> postVar("fld-host_time") );
    $starttime = formatDate($this -> postVar("fld-host_date"),"MDY-") . " " . formatDate($this -> postVar("fld-host_time"),"time");
    $times = explode(":",$obj["film_running_time"]);
    $totaltime = ($times[0] * 3600) + ($times[1] * 60) + ($times[2]);
    $screening->setScreeningEndTime(strtotime($starttime) + $totaltime);
   
    $screening->setScreeningPostMessage( WTVRcleanString( $this -> postVar("greeting") ) );
    //$screening->setScreeningPaidStatus( WTVRcleanString( $screening -> getScreeningPaidStatus()) );
    //$screening->setScreeningSeatsOccupied( WTVRcleanString( $screening -> getScreeningSeatsOccupied()) );
    $screening->setScreeningCreatedAt( now() );
    $screening->setScreeningUniqueKey( setScreeningId() );
    $screening->setScreeningStatus( 0 );
    if ($this -> postVar("hosting_type") == "featured") {
      $screening->setScreeningType( 2 );
    } else {
      $screening->setScreeningType( 1 );
    }
    $screening->setScreeningTotalSeats( 200 );
    $screening->setScreeningConstellationImage( $obj["film_logo"] );
    $screening->setScreeningGuestName( $user -> getUserFullName()  );
    if ($this -> postVar("FILE_host_image_original_guid")) {
      $screening->setScreeningGuestImage( $this -> postVar("FILE_host_image_original_guid").".jpg" );
    } else {
      if ($user -> getUserImage() != '') {
        $screening->setScreeningGuestImage( $user -> getUserImage() );
      } elseif ($user -> getUserPhotoUrl() != '') {
        $screening->setScreeningGuestImage( $user -> getUserPhotoUrl() );
      }
    }
    
    $screening->setScreeningDescription( $obj["film_synopsis"] );
    
    if ($this -> postVar("video_host") == "on") {
      $screening->setScreeningLiveWebcam( 1 );
      $screening->setScreeningHasQanda( 1 );
    } else {
      $screening->setScreeningLiveWebcam( 0 );
      $screening->setScreeningHasQanda( 0 );
    }
    $screening->setScreeningIsAdmin( 0 );
    if ($this -> postVar("hosting_type") == "featured") {
      $screening->setScreeningFeatured( 1 );
    } else {
      $screening->setScreeningFeatured( 0 );
    }
		
		$tz = $this -> postVar("tzSelector");
    $screening->setScreeningCreditStatus( 0 );
    $screening->setScreeningDefaultTimezone( getUsTz($tz) );
    $screening->setScreeningReceiptStatus( 0 );
    $screening->setScreeningDefaultTimezoneId( $tz );
    $screening->setScreeningVideoServerHostname( null );
    $screening->setScreeningVideoServerInstanceId( null );
    if ($this -> postVar("hosting_type") == "private") {
      $screening->setScreeningIsPrivate( 1 );
    } else {
      $screening->setScreeningIsPrivate( 0 );
    }
   
    $screening -> save();
    
    //Cross reference the item in the payment
    $this -> order -> crud -> setFkScreeningId( $screening -> Screening -> getScreeningId() );
    $this -> order -> crud -> setFkScreeningName( $screening -> Screening -> getScreeningName() );
    $this -> order -> crud -> save();
    
    //Update the SOLR Search Engine
    $solr = new SolrManager_PageWidget(null, null, $this -> context);
    $solr -> execute("add","screening",$screening -> Screening -> getScreeningId());
    
    if (forTesting()) {
      //dump($item -> UserOrderProduct);
    }
    
    $this -> order -> ordertotal = str_replace("$","",$this -> postVar("setup_price"));
    $this -> order -> orderskus[] = $this -> getVar("op");
    $this -> order -> orderitems[] = $screening;
    
  }
  
  public function postSubscriptionItem() {
    
    $user = getUserById( $this -> sessionVar("user_id") );
    
    if ($this -> postVar("step") == "gift") {
      $total_tickets = $this -> postVar("gift_ticket_number");
      $total = $this -> postVar("fld-gift_ticket_price") * $this -> postVar("gift_ticket_number");
    } elseif ($this -> postVar("step") == "subscription") {
      $total_tickets = $this -> postVar("fld-subscription_period") * $this -> postVar("subscription_ticket_number");
      $total = $this -> postVar("fld-subscription_ticket_price") * $total_tickets;
    }
    
    $subscription = new SubscriptionCrud( $this -> context );
    //Note, we can't really check for multiple subscriptions
    //As users can create as many as they'd like, overlapping
    /*
    $vars = array("fk_host_id"=>$this -> sessionVar("user_id"),
                  "fk_film_id"=>$this -> postVar("film_id"),
                  "screening_date"=>formatDate($this -> postVar("fld-host_date"),"MDY-"),
                  "screening_time"=>formatDate($this -> postVar("fld-host_date"),"time"));
    $screening->checkUnique( $vars );
    */
    $subscription->setFkUserId( $this -> sessionVar("user_id") );
    $subscription->setFkPaymentId( $this -> order -> crud -> Payment -> getPaymentId() );
    $subscription->setSubscriptionUniqueKey( setUserOrderTicket() );
    $subscription->setSubscriptionDateAdded( now() );
    $subscription -> setSubscriptionType( $this -> postVar("step") );
    if ($this -> postVar("step") == "gift") {
      $subscription -> setSubscriptionStartDate(now());
      $subscription -> setSubscriptionTicketNumber($this -> postVar("gift_ticket_number"));
      $subscription -> setSubscriptionTicketPrice($this -> postVar("fld-gift_ticket_price"));
    } elseif ($this -> postVar("step") == "subscription") {
      $subscription -> setSubscriptionStartDate( $this -> postVar("fld-subscription_date") );
      $subscription -> setSubscriptionTicketNumber($this -> postVar("subscription_ticket_number"));
      $subscription -> setSubscriptionTerm($this -> postVar("fld-subscription_term"));
      $subscription -> setSubscriptionPeriod($this -> postVar("fld-subscription_period"));
      $subscription -> setSubscriptionTicketPrice($this -> postVar("fld-subscription_ticket_price"));
    }
    $subscription -> setSubscriptionTotalPrice($total);
    $subscription -> setSubscriptionTotalTickets($total_tickets);
    $subscription -> save();
    
    //Cross reference the item in the payment
    $this -> order -> crud -> setFkSubscriptionId( $subscription -> Subscription -> getSubscriptionId() );
    $this -> order -> crud -> save();
    
    if (forTesting()) {
      //dump($item -> UserOrderProduct);
    }
    
    $this -> order -> ordertotal = $total;
    $this -> order -> orderskus[] = $subscription -> getSubscriptionId();
    $this -> order -> orderitems[] = $subscription;

  }
  
  //This updates the order in the Database, "complete..."
  public function confirmOrder( $code ) {
    
    $this -> order -> crud -> Payment -> setPaymentTransactionId( $code );
    $this -> order -> crud -> Payment -> setPaymentCardType( "Coupon" );
    $this -> order -> crud -> Payment -> setPaymentOrderProcessor( "redeem" );
    $this -> order -> crud -> Payment -> setPaymentEmail($this -> postVar("email_recipient"));
    $this -> order -> crud -> Payment -> setPaymentStatus( 2 );
    $this -> order -> crud -> save();
    
    $this -> order -> order_result_code = 2;
    
  }
  
  //This updates the items in the Database, "complete..."
  public function confirmItem( $obj=null, $type="screening") {
    switch ($type){
      case "screening":
        return $this -> confirmScreeningItem($obj);
        break;
      case "host":
        return $this -> confirmHostItem($obj);
        break;
      case "subscription":
        return $this -> confirmSubscriptionItem($obj);
        break;
    }
  }
  
  //If the order went through, set the seat as "paid"
  public function confirmScreeningItem( $obj ) {
    putLog("USER:: ".$obj[0]->getFkUserId()." | MESSAGE:: PaypalWPPOrders confirmScreeningItem is: " . $obj[0]->getAudienceInviteCode());
    $obj[0]->setAudiencePaidStatus( 2 );
    $obj[0] -> save();
    
    //$this -> order -> sendOrderNotification( $obj[0] );
  }
  
  //If the order went through, set the screening as "paid"
  public function confirmHostItem( $obj ) {
    
    $obj[0]->setScreeningPaidStatus( 2 );
    $obj[0] -> save();
    
    //Create a seat for the host:
    //Add the item to the database
    $item = new AudienceCrud( $this -> order -> context );
    $vars = array("fk_user_id"=>$this-> order -> orderuser -> getUserId(),"fk_payment_id"=>$this -> order -> crud -> Payment -> getPaymentId(),"fk_screening_unique_key"=>$obj[0] -> getScreeningUniqueKey());
    $item -> checkUnique( $vars );
    $item -> setFkScreeningUniqueKey( $obj[0] -> getScreeningUniqueKey() );
    $item -> setFkScreeningId( $obj[0] -> getScreeningId() );
    $item -> setFkUserId( $this -> order -> orderuser -> getUserId() );
    $item -> setAudienceTicketPrice( 0 );
    $item -> setFkPaymentId( $this -> order -> crud -> Payment -> getPaymentId() );
    $item -> setAudiencePaidStatus( 2 );
    $item -> setAudienceIpAddr( REMOTE_ADDR() );
    $item -> setAudienceCreatedAt( now() );
    $item -> setAudienceStatus( 0 );
    if ($this -> order -> orderuser -> getUserUsername() != '') {
      $item -> setAudienceUsername( $this -> order -> orderuser -> getUserUsername() );
    } else {
      $item -> setAudienceUsername( $this -> sessionVar("user_username") );
    }
    $code = setUserOrderTicket();
    $item -> setAudienceInviteCode( $code );
    $item -> setAudienceShortUrl( '/theater/' .$obj[0] -> getScreeningUniqueKey() . '/' . $code );
    //$item -> setAudiencePaidStatus( 2 );
    
    $item -> save(); 
    
    $solr = new SolrManager_PageWidget(null, null, $this -> context);
    $solr -> execute("add","audience",$item -> Audience -> getAudienceId());
    
    //Cross reference the item in the payment
    $this -> order -> crud -> setFkAudienceId( $item -> Audience -> getAudienceId() );
    $this -> order -> crud -> save();
  
    return $item -> Audience;
    
  }
  
  //If the order went through, set the screening as "paid"
  public function confirmSubscriptionItem( $obj ) {
  
    if ($obj[0] -> getSubscriptionType() == "gift") {
      $startdate = now();
    } elseif ($obj[0] -> getSubscriptionType() == "subscription") {
      $startdate = $this -> postVar("fld-subscription_date");
    }
    if ($obj[0] -> getSubscriptionType() == "subscription") {
      $thecode = setUserOrderTicket();
    }
    for ($i=0;$i<$obj[0] -> getSubscriptionPeriod();$i++) {
      for ($j=0;$j<$obj[0] -> getSubscriptionTicketNumber();$j++) {
        $ticket = new TicketCrud($this -> order -> context);
        $ticket->setTicketPaidStatus( $this -> order -> crud -> Payment -> getPaymentStatus() );
        $ticket->setFkUserId( $user );
        $ticket->setFkPaymentId( $this -> order -> crud -> Payment -> getPaymentId() );
        if ($obj[0] -> getSubscriptionType() == "gift") {
          $thecode = setUserOrderTicket();
        }
        $ticket->setTicketInviteCode( $thecode );
        $ticket->setTicketIpAddr( REMOTE_ADDR() );
        $ticket->setTicketStartDate( $startdate );
        $ticket->setTicketCreatedAt( now() );
        $ticket->setTicketUpdatedAt( null );
        $ticket->setTicketStatus( 0 );
        $ticket->setTicketUsername( $this -> sessionVar("user_username") );
        $ticket->setTicketTicketPrice( $obj[0] -> getSubscriptionTicketPrice() );
        $ticket->save();
      }
      if ($obj[0] -> getSubscriptionType() == "subscription") {
        switch ($obj[0] -> getSubscriptionTerm()) {
          case "weekly":
            $startdate = dateAddExt($startdate,7,'d');
            break;
          case "bi-weekly":
            $startdate = dateAddExt($startdate,14,'d');
            break;
          case "monthly":
            $startdate = dateAddExt($startdate,1,'m');
            break;
          case "yearly":
            $startdate = dateAddExt($startdate,1,'y');
            break;
        }
      }
    }
    
    $this -> order -> sendOrderNotification( $obj[0] );
    
    $obj[0] -> setFkPaymentstatus( $this -> order -> crud -> Payment -> getPaymentStatus() );
    $obj[0] -> save();
    
  }
  
  public function insertOrder( $user, $film, $type = "screening" ) {
    
    if (is_array($user)) {
      //Put Order Post Data in the database
      $this -> order -> crud ->  setPaymentFirstName($user[0]["user_fname"]);
      $this -> order -> crud ->  setPaymentLastName($user[0]["user_lname"]);
      $this -> order -> crud ->  setPaymentEmail($user[0]["user_email"]);
      //Place the User in the Order
      $this -> order -> crud -> setFkUserId( $user[0]["user_id"] );
    } else {
      //Put Order Post Data in the database
      $this -> order -> crud ->  setPaymentFirstName($user -> getUserFname());
      $this -> order -> crud ->  setPaymentLastName($user -> getUserLname());
      $this -> order -> crud ->  setPaymentEmail($user -> getUserEmail());
      //Place the User in the Order
      $this -> order -> crud -> setFkUserId( $user -> getUserId() );
    
    }
    
    $this -> order -> crud ->  setPaymentType($type);
    
    $this -> order -> crud ->  setFkFilmId($film["data"][0]["film_id"]);
    $this -> order -> crud ->  setPaymentAmount(0);
    
    //Generate the Order Id
    $this -> order -> orderguid = setUserOrderGuid();
    
    $this -> order -> crud -> setPaymentUniqueCode( $this -> order -> orderguid );
    $this -> order -> crud -> setPaymentCreatedAt( now() );
    $this -> order -> crud -> setPaymentIp( REMOTE_ADDR() );
    $this -> order -> crud -> setPaymentSiteId( sfConfig::get("app_site_id") );
    
    //Add some special items
    $this -> order -> crud -> setPaymentOrderProcessor( "sponsor_code" );
    
    //We are not saving CVV2 for the upcoming transaction, and are required to delete later by PCI DSS
    //$this -> crud -> setPaymentCvv2( $this -> postVar("card_verification_number") );
    
    $this -> order -> crud -> save();
    
  }
  
  //This comes from the "Instant View" Feature
  public function insertHostItem( $user, $obj, $date, $time ) {
    
    //Convert using TZ Settings
    //kickdump(formatDate($this -> postVar("host_date") . " " . $this -> postVar("host_time"),"pretty"));
    //$date = setTzDate($this -> postVar("host_date") . " " . $this -> postVar("host_time"));
    //dump(formatDate($date,"pretty"));
    if (! $user) {
      $this -> redirect("error");
    }
    $screening = new ScreeningCrud( $this -> order -> context );
    //Make sure this user isn't hosting the same time
    //For the same film...
    
    $screening->setFkFilmId( $obj["data"][0]["film_id"] );
    $screening->setFkPaymentId( $this -> order -> crud -> Payment -> getPaymentId() );
    $screening->setFkHostId( $user -> getUserId() );
    $screening->setScreeningDate( formatDate($date,"MDY-") );
    $screening->setScreeningTime( $time );
    $starttime = formatDate($date,"MDY-") . " " . formatDate($time,"time");
    $times = explode(":",$obj["data"][0]["film_running_time"]);
    $totaltime = ($times[0] * 3600) + ($times[1] * 60) + ($times[2]);
    $screening->setScreeningEndTime(strtotime($starttime) + $totaltime);
   
    $screening->setScreeningPaidStatus( 2 );
    //$screening->setScreeningSeatsOccupied( WTVRcleanString( $screening -> getScreeningSeatsOccupied()) );
    $screening->setScreeningCreatedAt( now() );
    $screening->setScreeningUniqueKey( setScreeningId() );
    $screening->setScreeningStatus( 2 );
    $screening->setScreeningType( 3 );
    
    $screening->setScreeningTotalSeats( 200 );
    $screening->setScreeningConstellationImage( $obj["data"][0]["film_logo"] );
    $screening->setScreeningDescription( $obj["data"][0]["film_synopsis"] );
    $screening->setScreeningLiveWebcam( null );
    $screening->setScreeningIsAdmin( 0 );
    if ($this -> postVar("hosting_type") == "featured") {
      $screening->setScreeningFeatured( 1 );
    } else {
      $screening->setScreeningFeatured( 0 );
    }
    $screening->setScreeningCreditStatus( 0 );
    $screening->setScreeningDefaultTimezone( getUsTz($this -> sessionVar("user_timezone")) );
    $screening->setScreeningReceiptStatus( 0 );
    $screening->setScreeningDefaultTimezoneId( $this -> sessionVar("user_timezone") );
    $screening->setScreeningVideoServerHostname( null );
    $screening->setScreeningVideoServerInstanceId( null );
    if ($this -> postVar("hosting_type") == "private") {
      $screening->setScreeningIsPrivate( 1 );
    } else {
      $screening->setScreeningIsPrivate( 0 );
    }
    $screening -> save();
    
    //Cross reference the item in the payment
    $this -> order -> crud -> setFkScreeningId( $screening -> Screening -> getScreeningId() );
    $this -> order -> crud -> setFkScreeningName( $screening -> Screening -> getScreeningName() );
    $this -> order -> crud -> save();
    
    //Update the SOLR Search Engine
    $solr = new SolrManager_PageWidget(null, null, $this -> context);
    $solr -> execute("add","screening",$screening -> Screening -> getScreeningId());
    
    if (forTesting()) {
      //dump($item -> UserOrderProduct);
    }
    
    $this -> order -> ordertotal = str_replace("$","",$this -> postVar("setup_price"));
    $this -> order -> orderskus[] = $this -> getVar("op");
    $this -> order -> orderitems[] = $screening;
    
  }
  
  //This comes from the "Instant View" Feature Also
  //Note the Payment Status is set here to "2"
  //Be careful we aren't authorizing screenings incorrectly
  public function insertScreeningItem( $obj=null, $id=null ) {
    
    //Add the item to the database
    $item = new AudienceCrud( $this -> order -> context );
    $vars = array("fk_user_id"=>$this-> order -> orderuser -> getUserId(),"fk_payment_id"=>$this -> order -> crud -> Payment -> getPaymentId(),"fk_screening_unique_key"=>$obj["data"][0]["screening_unique_key"] );
    $item -> checkUnique( $vars );
    $item -> setFkScreeningId( $obj["data"][0]["screening_id"] );
    $item -> setFkScreeningUniqueKey( $obj["data"][0]["screening_unique_key"] );
    $item -> setFkUserId( $this -> order -> orderuser -> getUserId() );
    $item -> setAudienceTicketPrice( 0 );
    $item -> setFkPaymentId( $this -> order -> crud -> Payment -> getPaymentId() );
    $item -> setAudiencePaidStatus( 2 );
    $item -> setAudienceIpAddr( REMOTE_ADDR() );
    $item -> setAudienceCreatedAt( now() );
    $item -> setAudienceStatus( 0 );
    if ($this -> order -> orderuser -> getUserUsername() != '') {
      $item -> setAudienceUsername( $this -> order -> orderuser -> getUserUsername() );
    } else {
      $item -> setAudienceUsername( $this -> sessionVar("user_username") );
    }
    $code = setUserOrderTicket();
    $item -> setAudienceInviteCode( $code );
    $item -> setAudienceShortUrl( '/theater/' . $obj["data"][0]["screening_unique_key"] . '/' . $code );
    //$item -> setAudiencePaidStatus( 2 );
    
    $item -> save(); 
    
    $solr = new SolrManager_PageWidget(null, null, $this -> context);
    $solr -> execute("add","audience",$item -> Audience -> getAudienceId());
    
    //Cross reference the item in the payment
    $this -> order -> crud -> setPaymentStatus( 2 );
    $this -> order -> crud -> setFkScreeningId( $obj["data"][0]["screening_id"] );
    $this -> order -> crud -> setFkScreeningName( $obj["data"][0]["screening_name"] );
    $this -> order -> crud -> setFkAudienceId( $item -> Audience -> getAudienceId() );
    $this -> order -> crud -> save();
   
    if (forTesting()) {
      //dump($item -> UserOrderProduct);
    }
    
    $this -> order -> ordertotal = str_replace("$","",$this -> postVar("ticket_price"));
    $this -> order -> orderskus[] = $obj;
    $this -> order -> orderitems[] = $item;
 	return $item;   
  }
  
  function instantPurchase( $user_id, $screening_id, $film, $processor="admin" ) {
		
		$user = UserPeer::retrieveByPk( $user_id );
		$c = new Criteria();
		$c -> add(ScreeningPeer::SCREENING_UNIQUE_KEY,$screening_id);
		$c -> setLimit(1);
		$res = ScreeningPeer::doSelect( $c );
		
		if (! $res) return false;
		$screening = $res[0];
		
		//Check to make sure this host has a payment
    //Put Order Post Data in the database
    $pmt = new PaymentCrud( $this -> context );
    $vars = array("fk_user_id"=>$user -> getUserId(),"fk_screening_id"=>$screening -> getScreeningId());
    $pmt -> checkUnique($vars);
    $pmt -> setPaymentFirstName($user -> getUserFname());
    $pmt -> setPaymentLastName($user -> getUserLname());
    $pmt -> setPaymentType("screening");
    $pmt -> setPaymentEmail($user -> getUserEmail());
    $pmt -> setPaymentInvites( 0 );
    $pmt -> setFkFilmId($screening -> getFkFilmId());
    $pmt -> setFkScreeningId($screening -> getScreeningId());
    $pmt -> setPaymentStatus(2);
    $pmt -> setPaymentAmount(0);
    $pmt -> setFkUserId( $user -> getUserId() );
    $pmt -> setPaymentUniqueCode( setUserOrderGuid() );
    $pmt -> setPaymentCreatedAt( now() );
    $pmt -> setPaymentIp( REMOTE_ADDR() );
    $pmt -> setPaymentSiteId( sfConfig::get("app_site_id") );
    $pmt -> setPaymentOrderProcessor( $processor );
    $pmt -> save();
    
    //Check to make sure this host has a ticket
    $item = new AudienceCrud( $this -> context );
    $vars = array("fk_user_id"=>$user->getUserId(),"fk_payment_id"=>$pmt -> Payment -> getPaymentId(),"fk_screening_unique_key"=>$screening -> getScreeningUniqueKey());
    $item -> checkUnique( $vars );
		if ($item -> getAudienceId() < 1) {
			$item -> setFkScreeningUniqueKey( $screening -> getScreeningUniqueKey() );
      $item -> setFkScreeningId( $screening -> getScreeningId() );
      $item -> setFkUserId( $user -> getUserId() );
      $item -> setAudienceTicketPrice( 0 );
      $item -> setFkPaymentId( $pmt -> Payment -> getPaymentId() );
      $item -> setAudiencePaidStatus( 2 );
      $item -> setAudienceIpAddr( REMOTE_ADDR() );
      $item -> setAudienceCreatedAt( now() );
      $item -> setAudienceStatus( 0 );
      if ($user -> getUserUsername() != '') {
        $item -> setAudienceUsername( $user -> getUserUsername() );
      }
      $code = setUserOrderTicket();
      $item -> setAudienceInviteCode( $code );
      $item -> setAudienceShortUrl( '/theater/' .$screening -> getScreeningUniqueKey() . '/' . $code );
      $item -> setAudiencePaidStatus( 2 );
      $item -> save();
      
      $pmt -> setFkAudienceId( $item -> Audience -> getAudienceId() );
      $pmt -> save();
    	
    	$solr = new SolrManager_PageWidget(null, null, $this -> context);
    	$solr -> execute("add","audience",$item -> Audience -> getAudienceId());
      
			if ($this -> cookieVar("cs_referer") != "") {
          $this -> order -> recordReferer( $this -> sessionVar("user_id"), $this -> cookieVar("cs_referer"), $screening -> getFkFilmId(), $item -> Audience -> getAudienceId(), $screening -> getScreeningId(), $item -> Audience ->getAudienceCreatedAt() );
      }
          
    	$user = getUserById( $this -> sessionVar("user_id") );
			sendOrderEmail( $user, $pmt -> Payment, array($item), $film, $this -> context );
			
			recordBeaconAction( $this-> sessionVar("user_id"), "screening", $screening -> getFkFilmId(), $screening -> getScreeningId(), $pmt -> getPaymentId() );
    
		}
    
    return $item;
	}
}
?>
