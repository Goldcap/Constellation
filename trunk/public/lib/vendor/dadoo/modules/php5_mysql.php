<?
class MySQL {

	var $dsn;
	var $connection_id;
	var $result;
	var $htmlentities;
			
	function __construct( $dsn = false ) {
	  
	  if (! $dsn) {
	     $this -> dsn = sfConfig::get("app_mysqli_dsn");
		} else {
      $this -> dsn = $dsn;
    }
		$this -> htmlentities = true;
    
    /* check connection */
    if (mysqli_connect_errno()) {
       printf("Connect failed: %s\n", mysqli_connect_error());
       exit();
    }
	}
	
	function data_query($query,$escape=true) {

		$this -> connection_id = new mysqli( $this -> dsn[0],  $this -> dsn[1],  $this -> dsn[2],  $this -> dsn[3] );

		$thisvalue = "";
		
		if ($escape) {
			$query = preg_replace('/[\\]/', '', $query);
		}
		
		$this -> result = $this -> connection_id -> query($query);
		
    if (! $this -> result = $this -> connection_id -> query($query)) {
			die($query."-- query failed");
		}
		
		$i = 0;
		while ($record = $this -> result -> fetch_assoc()) {
		  $thisvalue[$i]=$record;
		  $i++;
		}
		
		$this -> result = array('dbvalues' => $thisvalue);
							
		return $this -> result;
	}
	
	function data_insert($query) {
	
		$this -> connection_id = new mysqli( $this -> dsn[0],  $this -> dsn[1],  $this -> dsn[2],  $this -> dsn[3] );
    /* check connection */
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
		$query = preg_replace('/[\\]/', '', $query);
		
		$this -> result = $this -> connection_id -> query($query);
		
		if (! $this -> result) {
			
      printf("Errormessage: %s\n", $this -> connection_id ->error);

		}
		
	}
}
?>
