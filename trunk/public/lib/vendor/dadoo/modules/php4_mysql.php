<?
class MySQL {

	var $host;
	var $username;
	var $password;
	var $database;
	var $connection_id;
	var $result;
	var $admin;
		
	function __construct() {
		$this -> host = $GLOBALS["dbip"];
		$this -> username = $GLOBALS["user"];
		$this -> password = $GLOBALS["pass"];
		$this -> database = $GLOBALS["database"];
		$this -> admin = $GLOBALS['admin'];
		$this -> connection_id = mysql_connect($this -> host, $this -> username, $this -> password);
		mysql_select_db($this -> database, $this -> connection_id);
	}
	
	function data_query($query,$escape=true) {
		
		if ($escape) {
			$query = preg_replace('/[\\]/', '', $query);
		}
		
		$thisresult = mysql_query($query);
		
		//if (! $thisresult) {
		//	error_log("$query -- QUERY", 1,$this -> admin);
		//	die($query);
		//}
		
		$thiscount = mysql_num_rows($thisresult);
		
    $fCount = mysql_num_fields($thisresult); 
		for ($i=0; $i< $fCount; $i++){
		  $fNames[$i] = strtolower(mysql_field_name($thisresult, $i));
		}
		for ($i=0;  $i < $thiscount; $i++){
		  $result_row = mysql_fetch_row($thisresult);
		  for ($j = 0; $j < $fCount; $j++){
		    $fName = strtolower(mysql_field_name($thisresult, $j)); 
		    $record[$fName] = $result_row[$j];
		    
		  }
		  $thisvalue[$i]=$record;
		}
		
		$this -> result = array( 'dbcount' => $thiscount,
				                     'dbvalues' => $thisvalue);
							
		return $this -> result;
	}
	
	function data_insert($query) 
		{
		
		$query = preg_replace('/[\\]/', '', $query);
		
		$this -> result = mysql_query($query);
		
		if (! $this -> result) {
			error_log("$query on page $p -- QUERY", 1,$this -> admin);
			die($query);
		}
		
	}
}
?>
