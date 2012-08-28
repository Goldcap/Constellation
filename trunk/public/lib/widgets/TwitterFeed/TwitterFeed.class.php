<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/TwitterFeed_crud.php';
  
   class TwitterFeed_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
  var $rpp;
  var $page;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> rpp = 100;
    $this -> page = 1;
    parent::__construct( $context );
  }

	function parse() {
	 
   if ($this -> as_cli) {
      switch ($this -> widget_vars["args"][0]) {
          case "farm":
            $this -> farmFeeds();
            break;
          case "send":
            $this -> sendFeeds();
            break;
      }
	 }
   die();
  }

  function farmFeeds() {
    
    $curl = new Curl();
    $url = "http://search.twitter.com/search.json?q=thevow&rpp=".$this -> rpp."&page=".$this->page."&include_entities=true&result_type=mixed";
    $json = $curl -> get($url) -> body;
    $json = (json_decode($json));
    
    foreach($json -> results as $res) {
      
      $this -> crud = new TwitterFeedCrud( $this -> context );
      $vars = array("twitter_feed_guid"=>$res -> id_str);
      $this -> crud -> checkUnique($vars);
      if ($this -> crud -> getTwitterFeedId() > 0) {
        continue;
      }
      $this -> crud -> setTwitterFeedGuid( $res -> id_str );  
      $this -> crud -> setTwitterFeedAuthor( $res -> from_user ); 
      $this -> crud -> setTwitterFeedAuthorId( $res -> from_user_id );
      $this -> crud -> setTwitterFeedDateCreated( $res -> created_at ); 
      $this -> crud -> setTwitterFeedText( $res -> text );
      $this -> crud -> setTwitterFeedResponded( 0 );
      $this -> crud -> save();
      
    }
  }
  
  function sendFeeds() {
    
    /* THE VOW EVENT  */
    define("CONSUMER_KEY", "JsFfwiAamctqwiCilWfiQ");
    define("CONSUMER_SECRET", "KnrSzZ9wAHvdcH6Zv51Yab2aIT2BP8XLJPS3VZlIc");
    define("OAUTH_TOKEN", "448117802-zP8UQPtqAmAEoPBrlU1hyshZelCOijlr48x6oUlV");
    define("OAUTH_SECRET", "NgzuuU6tdti7ulgVHNjrSbPHaI382SOMPj67srkKg");
    /* */
    
    /* GOLDCAP 
    define("CONSUMER_KEY", "ENrmvsRfwzwx2KwQpv9Yw");
    define("CONSUMER_SECRET", "SdqoPkyrZbk4PpAc1Qw0DcjZ0YoDwA27bZrTAoypcQ");
    define("OAUTH_TOKEN", "15364553-OG1PwUQJ4zTxeBKeHvpUJ86Zvoykz6yGQNAAJzkGU");
    define("OAUTH_SECRET", "522vhQ88kd73qV48vm5547Q4eM9cb3Vq0Eu9oErYrWY");
    */    
     
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_SECRET);
    $content = $connection->get('account/verify_credentials');
    
    $sql = "select twitter_feed_guid, twitter_feed_author from twitter_feed feed where twitter_feed_responded = 0 and twitter_feed_author != 'thevowevent'";
    $results = $this -> propelQuery($sql);
    while ($row = $results -> fetch()) {
      $res = $connection->post('statuses/update', array("status"=>"@".$row[1]." Come chat LIVE with Channing Tatum at free online interactive event: http://constellation.tv/thevow.","in_reply_to_status_id"=>$row[0]));
      //$res = $connection->post('statuses/update', array("status"=>"@goldcap Some JUNK LIVE with Channing Tatum at free online interactive event: http://constellation.tv/thevow.","in_reply_to_status_id"=>154281523632275456));
      //kickdump($res);
      $sql = "update twitter_feed set twitter_feed_responded = 1 where twitter_feed_guid = '".$row[0]."'";
      $this -> propelQuery($sql);
    } 
    die();
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