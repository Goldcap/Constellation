<?
class PgSQL {

	var $host;
	var $username;
	var $password;
	var $database;
	var $connection_id;
		
	function pgsql() {
		$this -> host = $GLOBALS["dbip"];
		$this -> username = $GLOBALS["user"];
		$this -> password = $GLOBALS["pass"];
		$this -> database = $GLOBALS["database"];
		$this -> connection_id = pg_connect("host=".$this -> host." dbname=".$this -> database." user=".$this -> username." password=".$this -> password);
	}
	
	function data_query($query,$escape=true) 
		{
		
		if ($escape) {
			$query = preg_replace('/[\\]/', '', $query);
		}
		
		$thisresult = pg_query($query);
		
		if (! $thisresult) {
			error_log("$query -- QUERY", 1,"amadsen@gmail.com");
			die($query);
		}
		
		$thisvalue=array();
		   $fCount = pg_num_fields($thisresult); 
		   for ($i=0; $i< $fCount; $i++){
		     $fNames[$i] = strtolower(pg_fieldname($thisresult, $i));
		   }
		   $rows = pg_num_rows($thisresult);
		   for ($i=0;  $i < $rows; $i++){
		   	 pg_fetch_row($thisresult,$i);
		     $record=array();
		     for ($j = 0; $j < $fCount; $j++){
		         $fName = strtolower(pg_fieldname($thisresult, $j)); 
		         $record[$fName]=pg_fetch_result($thisresult, $j);
		     }
		     $thisvalue[$i]=$record;
		   }
		$thiscount = $i;
		$theresult = array( 'dbcount' => $thiscount,
							'dbvalues' => $thisvalue);
							
		return($theresult);
	}
	
	function data_insert($query) 
		{
		
		$this -> get_connection();
		
		$query = preg_replace('/[\\]/', '', $query);
		
		$thisresult = pg_query($query);
		
		if (! $thisresult) {
			error_log("$query on page $p -- QUERY", 1,"amadsen@gmail.com");
			die($query);
		}
		
	}
}
?>
