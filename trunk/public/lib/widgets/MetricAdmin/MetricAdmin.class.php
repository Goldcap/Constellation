<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/ImageHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/MetricAdmin_crud.php';
  
   class MetricAdmin_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	  $this -> XMLForm -> item_forcehidden = true;
   
    parent::__construct( $context );
  }

	function parse() {
	 
	 if ($this -> getVar("op") == "export") {
		
			$util = new MetricAdmin_format_utility( $this -> context );
			$args["filename"] = cleanFileName('Constellation_Metric_Report_'.date("mdY-hi")).'.xls';
			$args["location"] = sfConfig::get("sf_data_dir")."/exports/";
			
			$array = $this -> dataMap( sfConfig::get("sf_lib_dir")."/widgets/MetricAdmin/query/DateUser_list_datamap.xml", false, "array", $util );
			
			createDirectory($args["location"]);
			
			// We give the path to our file here
			$workbook = new Spreadsheet_Excel_Writer($args["location"]."/".$this -> encodeFilename($args["filename"]));
			$worksheet = $workbook->addWorksheet("Metrics");
			
			$row=0;
			$column=0;
			
			if ($array['data'] != null) {
			  $colnames = (array_keys($array['data'][0]));
			  
			  foreach($colnames as $colname) {
			    //echo($colname."<br/>");
			    if (($colname != 'listtype') && ($colname != 'id')) {
			      $worksheet->write($row,$column,$colname);
			      $column++;
			    }
			  }
			
			  $row=1;
			  $column=0;
			  
			  $theitems = $array["data"];
			  
			  foreach($theitems as $item) {
			    foreach($item as $key=>$value) {
			      //echo($key ."=". $value."<br />");
			      if (($key != 'listtype') && ($key != 'id')) {
			        $worksheet->write($row,$column,(strlen(trim($value)) == 0)?"":$value);
			        $column++;
			      }
			    }
			    $column=0;
			    $row++;
			  }
			}
			
			//$worksheet = $workbook->addWorksheet($array["meta"]["title"]);
			// We still need to explicitly close the workbook
			$workbook->close();
			$this -> widget_vars["filename"] = $this -> encodeFilename($args["filename"]);
      return $this -> widget_vars;
   } elseif ($this -> getOp() == "download") {
      //dump(sfConfig::get("sf_data_dir")."/chat/".$this -> getVar("file"));
      showExcel( $this -> context, sfConfig::get("sf_data_dir")."/exports/".$this -> getVar("file"), "false", $this -> getVar("file") );
      die();
	 } elseif ($this -> getOp() == "tickets") {
		 
		 if ($this -> getVar("id") > 0) {
		   $util = new MetricAdmin_format_utility( $this -> context );
		   sfConfig::set("thecount",$this -> getVar("id"));
		   $list = $this ->returnList("TicketUsers_list_datamap.xml", true, true, "standard", $util );
		   $this -> widget_vars["form"] = $list["form"];
		 } else {
			 $util = new MetricAdmin_format_utility( $this -> context );
		   $list = $this ->returnList("TicketCount_list_datamap.xml", true, true, "standard", $util );
		   $this -> widget_vars["form"] = $list["form"];
	   }
		 return $this -> widget_vars;
	 
	 } else {
		 
		 $util = new MetricAdmin_format_utility( $this -> context );
	   $list = $this ->returnList("DateUser_list_datamap.xml", true, true, "standard", $util );
	   $this -> widget_vars["form"] = $list["form"];
	   return $this -> widget_vars;
	 
	 }   
	  
  }

}

  ?>
