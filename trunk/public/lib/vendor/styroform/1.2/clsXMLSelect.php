<?php
/**
 *  XMLSelect.php, Styroform XML Form Controller
 * XML Form Generator and Validator
 * Retrieves lists for select elements, either from a database via ORM, or directly from SQL/Query files.
 * @author Andy Madsen <amadsen@operislabs.com>
 * @version 1.2
 * @package com.Operis.Styroform
 */
 
/**
 * Responsible for formatting db data into select list elements.
 */
class XMLSelect extends Utils_PageWidget {

  /** 
  * Base Table for the Select Query
  *   
  * @access public
  * @var string
  * @name $table 
  */
	var $table;
	
  /** 
  * Integer of Primary Key for Deletion
  *   
  * @access public
  * @var int
  * @name $id 
  */
	var $id;
	
  /** 
  * Buffer of Results from Query
  *   
  * @access public
  * @var mixed
  * @name $dbresults
  */
	var $dbresults;
	
  /**
  * Class Constructor
  *  
  * @name XMLSelect
  */
	function XMLSelect() {
	  if (isset($_GET["table"])) {
      $this -> table = $_GET["table"];
      $this -> outputXML();
	  }
  }
  
  /**
  * Dumps DB Select Results to an XML Formatted Outpu
  *  
  * @name outputXML
  */
  function outputXML() {
    $this -> runSQL();
    $this -> echoXML();
  }
  
  /**
  * Returns and array of results in "sel_key", "sel_value" format
  *  
  * @name arrayReturn
  * @param boolean $count - Returns either count or results
  * @param boolean $alpha - Order Alphabetically          
  * @return mixed
  */
  function arrayReturn($count=false,$alpha=false) {
    if ($alpha) {
      $this -> runSQLAlpha( $alpha );
    } else {
      $this -> runSQL();
    }
      
    if (! $count) {
      return $this -> dbresults;
    } else {
      return count($this -> dbresults);
    }
  }
  
  /**
  * Returns and array of results in "sel_key", "sel_value" format
 
  * @name itemReturn
  * @param string $count - Returns either count or results
  * @return mixed  
  */
  function itemReturn($count=false) {
    $this -> runItem();
    if (! $count) {
      return $this -> dbresults;
    } else {
      return count($this -> dbresults);
    }
  }
  
  /**
  * Returns results from a WTVR XML Query
  *  
  * @name arrayReturn
  * @param string $count - Returns either count or results
  */
  function queryReturn($count=false) {
    $data = new WTVRData( $this -> context );
    $res = $data -> dataMap($this -> query);
    $this -> dbresults = $res["data"];
    
    if (! $count) {
      return $this -> dbresults;
    } else {
      return count($this -> dbresults);
    }
  }
  
  /**
  * Returns results from a SQL Query, using standardized columnar names
  * 
  * <code>
  * 	select {$table}_id as sel_key, {$table}_name as sel_value from {$table}
  * </code>			  
  *  
  * @name runItem
  * */  
  function runItem() {
    $rs = $this -> propelQuery("SELECT ".$this -> table."_id as sel_key, ".$this -> table."_name as sel_value FROM ".$this -> table." WHERE ".$this -> table."_name <> 'Other' and ".$this -> table."_id = ".$this -> id." order by ".$this -> table."_name");
    $i = 0;
    
    while($row = $rs->fetch()) {
		  $this -> dbresults["dbvalues"][$i]["sel_key"] = $row[0];
		  $this -> dbresults["dbvalues"][$i]["sel_value"] = $row[1];
		  $i++;
		}
		
  }
  
  
  /**
  * Returns results from a Dadoo SQL Query, using standardized columnar names
  * 
  
  * @name runDadooSQL
  */
	function runDadooSQL() {
    $rs = new MySQLAbstract();
    $sql = "SELECT ".$this -> table."_id as sel_key, ".$this -> table."_name as sel_value FROM ".$this -> table." WHERE ".$this -> table."_name <> 'Other' order by ".$this -> table."_name";
    
    //$otherval["sel_key"] = 99999;
    //$otherval["sel_value"] = "Other";
    
    $this -> dbresults = $rs -> data_query($sql);
    //array_unshift($this -> dbresults["dbvalues"],$otherval);
    //$this -> dbresults["dbcount"]++;
		
  }
  
  /**
  * Returns results from a Dadoo SQL Query, using standardized columnar names
  * 
  
  * @deprecated 
  *	@name findAndUpdateItem
  * @param string $name
  */
  function findAndUpdateItem( $name ) {
    $rs = $this -> propelQuery("SELECT count(".$this -> table."_id) FROM ".$this -> table." WHERE ".$this -> table."_name = '".$name."'");
    while($row = $rs->fetch()) {
		  if($row[0] == "0") {
        $this -> addItem($name);
      }
		}
  }
 
  /**
  * Updates an item with a new "name" value
                
  * @name updateItem
  * @param string $name  - New Name of item
  */
  function updateItem( $name ) {
    $rs = $this -> propelQuery("UPDATE ".$this -> table." set ".$this -> table."_name = '".$name."' WHERE ".$this -> table."_id = ".$this -> id);
  }
 
  /**
  * Inserts an item with a "name" value
                
  * @name updateItem
  * @name addItem
  * @param string $name  - Name of item
  */
  function addItem( $name ) {
    $rs = $this -> propelQuery("INSERT INTO ".$this -> table." (".$this -> table."_name) values ('".$name."')");
  }
  
  /**
  * Deletes an item with a specific ID
                
  * @name updateItem
  * @name addItem
  * @param string $id  - ID of item
  */
  function deleteItem( $id ) {
    $rs = $this -> propelQuery("DELETE FROM ".$this -> table." WHERE ".$this -> table."_id = ".$id);
  }
  
  /**
  * Returns results from a SQL Query, using standardized columnar names
  * 
  * <code>
  * 	select {$table}_id as sel_key, {$table}_name as sel_value from {$table}
  * </code>			  
  *  
  * @name runSQL
  * @param string $other  - FPO copy here
  */
  function runSQL( $other = true ) {
    $rs = $this -> propelQuery("SELECT ".$this -> table."_id as sel_key, ".$this -> table."_name as sel_value FROM ".$this -> table." WHERE ".$this -> table."_name <> 'Other' order by ".$this -> table."_name");
    
    $i = 0;
    
    while($row = $rs->fetch()) {
		  $this -> dbresults["dbvalues"][$i]["sel_key"] = $row[0];
		  $this -> dbresults["dbvalues"][$i]["sel_value"] = $row[1];
		  $i++;
		}
		
  }
  
  /**
  * Returns string-match results from a SQL Query, using standardized columnar names,
  * and a letter   
  * 
  * <code>
  * 	select {$table}_id as sel_key, {$table}_name as sel_value from {$table}
  * 	and LOWER(SUBSTRING({$table}_name FROM 1 FOR 1)) = LOWER('A')  
  * </code>			  
  *  
  * @name runSQLAlpha
  * @param string $alpha  - String of item to search
  */
  function runSQLAlpha( $alpha ) {
    
    $rs = $this -> propelQuery("SELECT ".$this -> table."_id as sel_key, ".$this -> table."_name as sel_value FROM ".$this -> table." WHERE ".$this -> table."_name <> 'Other' and LOWER(SUBSTRING(".$this -> table."_name FROM 1 FOR 1)) = LOWER('".$alpha."') order by ".$this -> table."_name");
    $i = 0;
    
    while($row = $rs->fetch()) {
		  $this -> dbresults["dbvalues"][$i]["sel_key"] = $row[0];
		  $this -> dbresults["dbvalues"][$i]["sel_value"] = $row[1];
		  $i++;
		}
		
  }
  
  /**
  * Output standard XML Headers for XML Dump
  *  
  * @name drawXMLHeader
  */
  function drawXMLHeader() {
		header ("Content-type: application/xml");
	}
	
 /**
  * Output xml format data for AJAX Queries
  *  
  * @name echoXML
  */
	function echoXML() {
    $this -> drawXMLHeader();
    echo("<VALUES>");
    foreach ($this -> dbresults["dbvalues"] as $avalue) {
      echo ("<VALUE id=\"".$avalue["sel_key"]."\" data=\"".$avalue["sel_value"]."\" />");
    }
    echo("</VALUES>");
  }
  
}
?>
