<?

/**
 * WTVRError.php, Styroform XML Form Controller
 * 
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
// WTVRError

/**
* Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
* Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
* <code> 
*  $fpo = something();
*  $fpo = something();
*  $fpo = something();
*  $fpo = something();
* </code>
* @name WTVRerrorHandler
* @param string $errno - FPO copy here
* @param string $errstr - FPO copy here
* @param string $errfile - FPO copy here
* @param string $errline - FPO copy here
* @param string $errcontext
*/
function WTVRErrorHandler($errno, $errstr, $errfile, $errline, $errcontext) {
  switch ($errno) {
    case E_USER_ERROR:
       $error = new WTVRError();
	     $error -> handleError($errno, $errstr, $errfile, $errline, $errcontext);
        break;

    case E_USER_WARNING:
        //echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
        break;

    case E_USER_NOTICE:
        //echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
        break;

    default:
       //echo "Unknown error type: [$errno] $errstr<br />\n";
        break;
    }

    /* Don't execute PHP internal error handler */
    return true;
  
}

set_error_handler("WTVRErrorHandler");

/**
 * The Form Controller class, responsible for drawing the form, calling the validator, adding "Items" and "ItemLists", and navigating through formsets
 * @package WTVRError
 * @subpackage classes
 */
class WTVRError {
	
	/**
	 *  Subscribe List.
	 *  @private array
	 */
	private $admin_emails;

	/**
	 *  Error ID.
	 *  @private integer
	 */
	private $error_id;

	/**
	 *  Error Code.
	 *  @private array
	 */	
	private $apache_codes = array(
								400=>'Bad Request',
								401=>'Authorization Required',
								402=>'Payment Required',
								403=>'Forbidden',
								404=>'Not Found',
								405=>'Method Not Allowed',
								406=>'Not Acceptable',
								407=>'Proxy Authentication',
								408=>'Request Timed Out',
								409=>'Conflicting Request',
								410=>'Gone',
								411=>'Content Length Required',
								412=>'Precondition Failed',
								413=>'Request Entity Too Long',
								414=>'Request URI Too Long',
								415=>'Unsupported Media Type',
								500=>'Internal Server Error',
								501=>'Not Implemented',
								502=>'Bad Gateway',
								503=>'Service Unavailable',
								504=>'Gateway Timeout',
								505=>'HTTP Version Not Supported'
							);
	/**
	 *  PHP Error Code.
	 *  @private string
	 */	
	private $php_codes = array (
							8191 => 'E_ALL',
							2048 => 'E_STRICT',
							1024 => 'E_USER_NOTICE',
							512 => 'E_USER_WARNING',
							256 => 'E_USER_ERROR',
							128 => 'E_COMPILE_WARNING',
							64 => 'E_COMPILE_ERROR',
							32 => 'E_CORE_WARNING',
							16 => 'E_CORE_ERROR',
							8 => 'E_NOTICE',	
							4 => 'E_PARSE',
							2 => 'E_WARNING',
							1 => 'E_ERROR',
							9999 => 'HACK_ATTEMPT'
						);
	/**
	 *  E-mail Body.
	 *  @public string
	 */	
	public $email_body;

	/**
	 *  Is PHP Error
	 *  @public boolean
	 */
	public $errsource = 'php'; 

	/**
	 *  Send E-mail
	 *  @public boolean
	 */	
	public $send_email = true;

	/**
	 *  PHP Error Properties
	 *  @private string
	 */	
	public  $errno;
	private $errstr;
	private $errfile;
	private $errline;

/**
* Form Constructor.
* The formsettings array should look like this, either passed in the constructor or via WTVR:
* Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
* <code> 
*  $fpo = something();
*  $fpo = something();
*  $fpo = something();
*  $fpo = something();
* </code>
* @name Constructor 
* @param string $errno - FPO copy here
* @param string $errstr - FPO copy here
* @param string $errfile - FPO copy here
* @param string $errline - FPO copy here
* @param string $errcontext - FPO copy here
* @param array $formsettings  - Array with both Formset, and XSL Doc
*/
	public function __construct($errno='',$errstr='',$errfile='',$errline='',$errcontext=''){
		
		global $ErrorAlertUsers;
    
    $this->admin_emails = $ErrorAlertUsers; 
    
		if($errno>0){
			/*-- PHP Error --*/
			$this->errsource='php';
			$this->errno=$errno;
			$this->errstr=$errstr;
			$this->errfile=$errfile;
			$this->errline=$errline;
			$this->errcontext=$errcontext;
			$this->error_code = ($this->errno) ? $this->php_codes[$this->errno] : 'Issue Unknown';
		} else {
		  /*-- Apache Error --*/
		  $this->errsource='Apache';
		  $this->errno=$_GET['code'];
			$this->error_code = (isset($this->errno)) ? $this->apache_codes[$this->errno] : 'Issue Unknown';
		}
	}

  /**
  * Insert Error Info into DB. 
  * @return void
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name popDocs  
  * @param string $nodevalue - FPO copy here
  * @param string $nodevalue - FPO copy here
  */
	public function handleError(){
		
    $this->logError();
    
		$this->drawPHPEmail();
		
		if($this->send_email) $this->emailAdmin( $this->email_body );
	}
	
  /**
  * Insert Error Info into DB. 
  * @return void
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name popDocs  
  * @param string $nodevalue - FPO copy here
  * @param string $nodevalue - FPO copy here
  */
	private function logError(){
    
    switch ($_SERVER["SERVER_NAME"]) {
      case "dev.tattoojohnny.com":
      case "dev.tattoojohnny":
      $dsn = array("localhost","amadsen","1hsvy5qb","ttj_dev");
      break;
      case "stage.tattoojohnny.com":
      $dsn = array("10.32.165.132","amadsen","1hsvy5qb","ttj_stage");
      break;
      default:
      $dsn = array("10.32.165.132","amadsen","1hsvy5qb","ttj");
      break;
      
    }  
    
		$connection = new MySQLAbstract( $dsn );
    
		$query =  "INSERT INTO wtvr_error 
              (error_no,
              error_code, 
              error_line,
              error_file,
              error_context,
              ip_address, 
              user_agent, 
              referring_url, 
              error_date,
              is_php ) 
              VALUES 
              ('".$this->errno."',
              '".$this->error_code."',
              '".$this->errline."',
              '".$this->errfile."',
              '".$this->errcontext."',
              '".REMOTE_ADDR()."',
              '".$_SERVER["HTTP_USER_AGENT"]."',
              '".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]."',
              NOW(),
              '".$this->errsource."');";
		
		$connection -> data_insert(addslashes($query));

	}

  /**
  * Draw E-mail Body for Apache Error. 
  * @return void
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name popDocs  
  * @param string $nodevalue - FPO copy here
  * @param string $nodevalue - FPO copy here
  */
	private function drawApacheEmail(){
		$this->email_body .= "An Apache Error has been reported by the ". $_SERVER["SERVER_NAME"] . " site:"."<br />";
		$this->email_body .= "======================================================================"."<br />";
		$this->email_body .= "Page Request: ".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"] ."<br />";
		$this->email_body .= "======================================================================"."<br />";
		$this->email_body .= "Error Type:                $this->error_id: $this->error_code"."<br />";
		$this->email_body .= "======================================================================"."<br />";
		$this->email_body .= "IP Address:               ".REMOTE_ADDR()."<br />";
		$this->email_body .= "User Agent:               ".$_SERVER["HTTP_USER_AGENT"]."<br />";
		$this->email_body .= "Time/Date:                 ".date("F j, Y, g:i a")."<br />";
		$this->email_body .= "======================================================================"."<br />";
	}

  /**
  * Draw E-mail Body for PHP Error. 
  * @return void
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name popDocs  
  * @param string $nodevalue - FPO copy here
  * @param string $nodevalue - FPO copy here
  */
	private function drawPHPEmail(){
		$this->email_body .= "A PHP Error has been reported by the ". $_SERVER["SERVER_NAME"] . " site:"."<br />";
		$this->email_body .= "======================================================================"."<br />";
		$this->email_body .= "Page Request: ".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"] ."<br />";
		$this->email_body .= "Page Referer: ".$_SERVER["SERVER_NAME"].$_SERVER["HTTP_REFERER"] ."<br />";
		$this->email_body .= "======================================================================"."<br />";
		$this->email_body .= "Error Type:               $this->errno: $this->error_code"."<br />";
		$this->email_body .= "Description:             $this->errstr"."<br />";
		$this->email_body .= "Location:                 $this->errfile"."<br />";
		$this->email_body .= "Line Number:            $this->errline"."<br />";
		$this->email_body .= "======================================================================"."<br />";
		$this->email_body .= "IP Address:               ".REMOTE_ADDR()."<br />";
		$this->email_body .= "User Agent:               ".$_SERVER["HTTP_USER_AGENT"]."<br />";
		$this->email_body .= "Time/Date:                ".date("F j, Y, g:i a")."<br />";
		$this->email_body .= "======================================================================"."<br />";
  }

  /**
  * E-mail Subscribers. 
  * @return void
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name popDocs  
  * @param string $nodevalue - FPO copy here
  * @param string $nodevalue - FPO copy here
  */
	private function emailAdmin( $message ){
		
    $subject = $_SERVER["SERVER_NAME"].' Server Error: '.$this->error_id.':'.$this->error_code;
    $this -> mailer = new PHPMailer();
    $this -> mailer ->  IsSendmail();
    $this -> mailer -> Host = "localhost";
    $this -> mailer -> Encoding = "quoted-printable";
    $this -> mailer -> FromName = $_SERVER["SERVER_NAME"] ;
    $this -> mailer -> AddAddress("amadsen@operislabs.com","Andy Madsen");
    $this -> mailer -> IsHTML(true);
    $this -> mailer -> Subject = $subject;
    $this -> mailer -> Body    = $message;
    
    $result = $this -> mailer -> Send();
    
	}
	
}
?>
