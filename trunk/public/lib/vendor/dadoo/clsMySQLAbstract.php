<?
//PHP5 or above
include_once("modules/php5_mysql.php");

class MySQLAbstract extends MySQL {

	var $objMySQL;
	var $query;
	var $result;
	
	/*
	var $host;
	var $username;
	var $password;
	var $database;
	var $connection_id;
	var $result;
	var $admin;
	*/
	
	function __construct( $dsn=false ) {
	  parent::__construct( $dsn );
	}
	
}
?>
