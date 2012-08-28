<?php
  
class TheaterHosting_PageWidget extends Widget_PageWidget {
  
  public $context;
  public $seat;
  public $as_host;
  public $hostname;
  
  public $tokbox_key;
  public $tokbox_sessionId;
  public $tokbox_token;
  public $shareUrl;
                  
  private $tokbox_session;
	private $tokbox_secret;
	private $apiObj;
  
  function __construct( $context, $seat=null ) {
    $this -> context = $context;
    $this -> tokbox_key = "11604782";
    $this -> tokbox_secret = "8ff1ecf4715117205fc2f09494916df5817561d4";
    $this -> debug = false;
    $this -> as_host = false;
    $this -> apiObj = new OpenTokSDK($this -> tokbox_key, $this -> tokbox_secret);

		parent::__construct( $context );
  
	}
  
	function genSession() {
	  $this -> tokbox_session = $this -> apiObj->create_session(REMOTE_ADDR());
	  $this -> tokbox_sessionId = $this -> tokbox_session->getSessionId();
	}
	
  function parse( $session_id=false ) {
		
		if (! $session_id) return;
		
		$this -> tokbox_sessionId = $session_id;
    
    if (! $this -> as_host) {
    	$this -> tokbox_token = $this -> apiObj->generate_token($this -> tokbox_sessionId);
		} else {
			//$connectionMetaData = "username=" . $this -> hostname; // Replace with meaningful metadata for the connection.
			//$this -> tokbox_token = $this -> apiObj->generate_token($this -> tokbox_sessionId, RoleConstants::MODERATOR, null, $connectionMetaData); // Replace with the correct session ID
			$this -> tokbox_token = $this -> apiObj->generate_token($this -> tokbox_sessionId, RoleConstants::MODERATOR); // Replace with the correct session ID
		
		}
		$this -> shareUrl = "http://".sfConfig::get("app_domain").$_SERVER["REQUEST_URI"]."?tokbox_sessionId=".$this -> tokbox_sessionId;
	 	
	}
	
	function genEmbed() {
		$this -> embed = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="770" height="500" id="MyApp">
										    <param name="movie" value="MyApp.swf">
										    <param name="quality" value="high">
										    <param name="bgcolor" value="#ffffff">
										    <param name=FlashVars value="apiKey='.$this -> tokbox_key.'&sessionId='.$this -> tokbox_sessionId.'&token='.$this -> tokbox_token.'">
												<param name="allowScriptAccess" value="sameDomain">
										    <param name="allowFullScreen" value="true">
										    <!--[if !IE]>-->
										    <object type="application/x-shockwave-flash" data="MyApp.swf" width="770" height="500">
										        <param name="quality" value="high">
										        <param name="bgcolor" value="#ffffff">
										        <param name=FlashVars value="apiKey='.$this -> tokbox_key.'&sessionId='.$this -> tokbox_sessionId.'&token='.$this -> tokbox_token.'">
												<param name="allowScriptAccess" value="sameDomain">
										        <param name="allowFullScreen" value="true">
										        <!--<![endif]-->
										        <!--[if gte IE 6]>-->
										        <p>
										            Either scripts and active content are not permitted to run or Adobe Flash Player version 10.0.0 or greater is not installed.
										        </p><!--<![endif]-->
										        <a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash Player"></a> <!--[if !IE]>-->
										    </object> <!--<![endif]-->
										</object>';	
	}
}

?>
