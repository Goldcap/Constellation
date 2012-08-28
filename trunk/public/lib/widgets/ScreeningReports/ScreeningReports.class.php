<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/ImageHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/ScreeningReports_crud.php';
  
   class ScreeningReports_PageWidget extends Widget_PageWidget {
	
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
	 
    //return $this -> widget_vars;
   
    return $this -> drawPage();
    
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
    
  }

  function drawPage(){
  
    if ($this ->getOp() == "detail") {
	//$this -> showXML();
      $util = new ScreeningReports_format_utility( $this -> context );
      $list = $this ->returnList("Screening_Chat_list_datamap.xml", true, true, "standard", $util );
      $this -> widget_vars["form"] = $list["form"];
      return $this -> widget_vars;
      //return $this -> returnForm();
    } else if ($this ->getOp() == "export") {
      $file = $this -> exportReport();
      $this -> widget_vars["file"] = $file;
      return $this -> widget_vars;
    } else if ($this ->getOp() == "filmexport") {
      $this -> widget_vars["file"] = $this -> exportFilmReport();
      return $this -> widget_vars;
    } else if ($this ->getOp() == "daily") {
      if ($this -> postVar("startdate")) {
        $this -> widget_vars["file"] = $this -> exportDailyReport();  
      }
      $form = $this -> returnForm();
      $this -> widget_vars["form"] = $form["form"];
      return $this -> widget_vars;
    } else if ($this ->getOp() == "filmsummary") {
      $this -> widget_vars["file"] = $this -> exportReportSummary();
      return $this -> widget_vars;
    } elseif ($this -> getOp() == "download") {
      //dump(sfConfig::get("sf_data_dir")."/chat/".$this -> getVar("file"));
      showExcel( $this -> context, sfConfig::get("sf_data_dir")."/exports/".$this -> getVar("file"), "false", $this -> getVar("file") );
      die();
    } elseif ($this ->getOp() == "screenings" ) {
      $util = new ScreeningReports_format_utility( $this -> context );
      return $this -> returnList( "ScreeningUsage_list_datamap.xml", true, true, "standard", $util );
    } elseif ($this ->getOp() == "list" ) {
      return $this -> returnList();
    }
    
  }
  
  function exportFilmReport() {
    include_once("FilmReport.php");
    return $args["filename"];
  }
  
  function exportDailyReport() {
    include_once("DailyReport.php"); 
    return $args["filename"];
  }
  
  function exportReport() {
    error_reporting(0);
    
    $util = new ScreeningReports_format_utility( $this -> context );
		$users = $this -> dataMap( sfConfig::get("sf_lib_dir")."/widgets/ScreeningReports/query/Audience_list_datamap.xml", false, "array", $util );
    
    $args["filename"] = cleanFileName('Constellation_Chat_Report_'.date("mdY-hi")).'.xlsx';
    $args["location"] = sfConfig::get("sf_data_dir")."/exports/";
    
    $array = $this -> dataMap( sfConfig::get("sf_lib_dir")."/widgets/ScreeningReports/query/Screening_Chat_list_datamap.xml", false );
    createDirectory($args["location"]);
    
    // We give the path to our file here
    // Create new PHPExcel object
    $workbook = new PHPExcel();
    
    // Set properties
    $workbook->getProperties()->setCreator("Constellation System");
    $workbook->getProperties()->setLastModifiedBy("Constellation System");
    $workbook->getProperties()->setTitle("Chat History");
    $workbook->getProperties()->setSubject("Chat History");
    $workbook->getProperties()->setDescription("Chat History");
    
    // Add some data
    $workbook->setActiveSheetIndex(0);
    // Rename sheet
    $workbook->getActiveSheet()->setTitle('Chat History');
    
    $row=1;
    $column=0;
    
    if ($array['data'] != null) {
      $colnames = (array_keys($array['data'][0]));
      
      foreach($colnames as $colname) {
        //echo($colname."<br/>");
        if (($colname != 'listtype') && ($colname != 'id')) {
          $workbook->getActiveSheet()->setCellValueByColumnAndRow($column,$row,$colname);
          $column++;
        }
      }
    
      $row=2;
      $column=0;
      
      $theitems = $array["data"];
      
      foreach($theitems as $item) {
        foreach($item as $key=>$value) {
          //echo($key ."=". $value."<br />");
          if (($key != 'listtype') && ($key != 'id')) {
            $theval = (strlen(trim($value)) == 0)?"null":$value;
            if ($theval[0] == "=") {
              $theval = " " . $theval;
            }
            $workbook->getActiveSheet()->setCellValueByColumnAndRow($column,$row,$theval);
            $column++;
          }
        }
        $column=0;
        $row++;
      }
    }
     
    $workbook->createSheet(1);
    $workbook->setActiveSheetIndex(1);
    
    // Rename sheet
    $workbook->getActiveSheet()->setTitle('User Participation');
    
    $row=1;
    $column=0;
    $array = $users;
    
		if ($array['data'] != null) {
      $colnames = (array_keys($array['data'][0]));
      
      foreach($colnames as $colname) {
        //echo($colname."<br/>");
        if (($colname != 'listtype') && ($colname != 'id')) {
          $workbook->getActiveSheet()->setCellValueByColumnAndRow($column,$row,$colname);
          $column++;
        }
      }
    
      $row=2;
      $column=0;
      
      $theitems = $array["data"];
      
      foreach($theitems as $item) {
        foreach($item as $key=>$value) {
          //echo($key ."=". $value."<br />");
          if (($key != 'listtype') && ($key != 'id')) {
            $theval = (strlen(trim($value)) == 0)?"":$value;
            if ($theval[0] == "=") {
              $theval = " " . $theval;
            }
            $workbook->getActiveSheet()->setCellValueByColumnAndRow($column,$row,$theval);
            $column++;
          }
        }
        $column=0;
        $row++;
      }
      
    }
    
    //$worksheet = $workbook->addWorksheet($array["meta"]["title"]);
    //die();
    // Save Excel 2007 file
    $objWriter = new PHPExcel_Writer_Excel2007($workbook);
    $objWriter->save($args["location"]."/".$this -> encodeFilename($args["filename"]));
    
    return $this -> encodeFilename($args["filename"]);
    
  }
  
  function exportReportSummary() {
    //error_reporting(0);
    //die("HERE");
    $users = $this -> dataMap( sfConfig::get("sf_lib_dir")."/widgets/ScreeningReports/query/Audience_list_datamap.xml", false );
    $film = FilmPeer::retrieveByPk( $this -> getVar("id") );
    
    $args["filename"] = cleanFileName($film -> getFilmName() . '_ChatUsage_Report_'.date("mdY-hi")).'.xls';
    $args["location"] = sfConfig::get("sf_data_dir")."/exports/";
    
    //Loop over every screening for this film, in the past
    $sql = "select screening_id,
            screening_unique_key,
            screening_date,
            screening_time
            from screening
            where fk_film_id = ".$this -> getVar("id")."
            and screening_date < '".formatDate(null,TS)."';";
    
    $hrs = $this -> propelQuery($sql);
    //$rows = $hrs -> fetchAll();
    // We give the path to our file here
    $workbook = new PHPExcel();
    
    // Set properties
    $workbook->getProperties()->setCreator("Constellation System");
    $workbook->getProperties()->setLastModifiedBy("Constellation System");
    $workbook->getProperties()->setTitle("Chat History");
    $workbook->getProperties()->setSubject("Chat History");
    $workbook->getProperties()->setDescription("Chat History");
    
    // Add some data
    $workbook->setActiveSheetIndex(0);
    // Rename sheet
    $workbook->getActiveSheet()->setTitle('Chat History');
    createDirectory($args["location"]);
    
    //Get some formats ready!
    /*
    $format_bold =& $workbook->addFormat();
    $format_bold->setBold();
    
    $currency_format =& $workbook->addFormat();
    $currency_format->setNumFormat('$0.00;[Red]($0.00)');
    $currency_format->setHAlign('right');
    
    $percent_format =& $workbook->addFormat();
    $percent_format->setNumFormat('0.00%');
    
    $total_format =& $workbook->addFormat();
    $total_format->setNumFormat('$0.00;[Red]($0.00)');
    $total_format->setColor("green");
    
    $title_format =& $workbook->addFormat();
    $title_format->setFgColor("yellow");
    $title_format->setBold();
    
    $mgr_format =& $workbook->addFormat();
    $mgr_format->setFgColor(27);
    $mgr_format->setBold();
    
    $academy_format =& $workbook->addFormat();
    $academy_format->setFgColor(31);
    $academy_format->setBold();
    
    $avg_format =& $workbook->addFormat();
    $avg_format->setFgColor(29);
    $avg_format->setBold();
    
    $tot_format =& $workbook->addFormat();
    $tot_format->setFgColor(31);
    $tot_format->setBold();
    */
    
    $academy_format = array(
      'fill' => array(
	 			'type'       => PHPExcel_Style_Fill::FILL_SOLID,
	  		'startcolor' => array(
	 				'argb' => PHPExcel_Style_Color::COLOR_BLUE
	 			)
	 		)
    );
    
    $title_format = array(
      'fill' => array(
	 			'type'       => PHPExcel_Style_Fill::FILL_SOLID,
	  		'startcolor' => array(
	 				'argb' => PHPExcel_Style_Color::COLOR_YELLOW
	 			)
	 		)
    );
    
    $mgr_format = array(
      'fill' => array(
	 			'type'       => PHPExcel_Style_Fill::FILL_SOLID,
	  		'startcolor' => array(
	 				'argb' => PHPExcel_Style_Color::COLOR_GREEN
	 			)
	 		)
    );
    
    $j=0;
    
    //Column Widths
    $worksheet->setColumn($j,0,40);
    $j++;
    $worksheet->setColumn($j,1,20);
    $j++;
    $worksheet->setColumn($j,2,20);
    $j++;
    $worksheet->setColumn($j,3,20);
    $j++;
    $worksheet->setColumn($j,4,40);
    $j++;
    
    $j=0;
    $workbook->getActiveSheet()->setCellValueByColumnAndRow($j,0,$film -> getFilmName() . " Chat History by Screening");
    $workbook->getActiveSheet()->getStyleByColumnAndRow($j,0)->applyFromArray($title_format);
    $workbook->getActiveSheet()->setCellValueByColumnAndRow($j,1,"");
    $workbook->getActiveSheet()->getStyleByColumnAndRow($j,1)->applyFromArray($title_format);
    $workbook->getActiveSheet()->setCellValueByColumnAndRow($j,2,"");
    $workbook->getActiveSheet()->getStyleByColumnAndRow($j,2)->applyFromArray($title_format);
    $workbook->getActiveSheet()->freezePane('A1');
    //$worksheet->freezePanes(array(1, 0));
    
    $row=0;
    $column=0;
    
    while ($line = $hrs -> fetch()) {
      //kickdump("Screening ".$line[1]." Chat (".$line[0].")");
      
      $row++;
      $workbook->getActiveSheet()->setCellValueByColumnAndRow($row,0,$line[1]);
      $workbook->getActiveSheet()->getStyleByColumnAndRow($row,0)->applyFromArray($academy_format);
      $workbook->getActiveSheet()->setCellValueByColumnAndRow($row,1,formatDate("TS",$line[2]." ".$line[3]),$academy_format);
      $workbook->getActiveSheet()->getStyleByColumnAndRow($row,1)->applyFromArray($academy_format);
      //$workbook->getActiveSheet()->setCellValueByColumnAndRow($row,2,$line[0],$title_format);
   
      $array = null;
      sfConfig::set("room",$line[1]);
      $array = $this -> dataMap( sfConfig::get("sf_lib_dir")."/widgets/ScreeningReports/query/ScreeningById_Chat_list_datamap.xml", false );
      
      if ($array["meta"]["totalresults"] > 0) {
        $row++;
        
        $colnames = (array_keys($array['data'][0]));
        foreach($colnames as $colname) {
          //echo($colname."<br/>");
          if (($colname != 'listtype') && ($colname != 'id')) {
            $workbook->getActiveSheet()->setCellValueByColumnAndRow($row,$column,$colname,$mgr_format);
            $workbook->getActiveSheet()->getStyleByColumnAndRow($row,$column)->applyFromArray($mgr_format);
            $column++;
          }
        }
        //die();
        $row++;
        $column=0;
        
        $theitems = $array["data"];
        
        foreach($theitems as $item) {
          foreach($item as $key=>$value) {
            //echo($key ."=". $value."<br />");
            if (($key != 'listtype') && ($key != 'id') && ($key != 'Time')) {
              $workbook->getActiveSheet()->setCellValueByColumnAndRow($row,$column,(strlen(trim($value)) == 0)?"":$value);
              $column++;
            } elseif ($key == 'Time') {
              $workbook->getActiveSheet()->setCellValueByColumnAndRow($row,$column,(strlen(trim($value)) == 0)?"":formatDate($value,"TS"));
              $column++;
            }
          }
          $column=0;
          $row++;
        }
      }
      
    }
    
    
    $array = $this -> dataMap( sfConfig::get("sf_lib_dir")."/widgets/ScreeningReports/query/ScreeningUsers_list_datamap.xml", false );
    
    $workbook->createSheet(1);
    $workbook->setActiveSheetIndex(1);
    
    // Rename sheet
    $workbook->getActiveSheet()->setTitle('User Participation');
    
    $workbook->getActiveSheet()->setCellValueByColumnAndRow(0,0,$film -> getFilmName() . "  Participation History by Screening");
    $workbook->getActiveSheet()->getStyleByColumnAndRow(0,0)->applyFromArray($title_format);
    $workbook->getActiveSheet()->setCellValueByColumnAndRow(0,1,"");
    $workbook->getActiveSheet()->getStyleByColumnAndRow(0,1)->applyFromArray($title_format);
    $workbook->getActiveSheet()->setCellValueByColumnAndRow(0,2,"");
    $workbook->getActiveSheet()->getStyleByColumnAndRow(0,2)->applyFromArray($title_format);
    $workbook->getActiveSheet()->setCellValueByColumnAndRow(0,3,"");
    $workbook->getActiveSheet()->getStyleByColumnAndRow(0,3)->applyFromArray($title_format);
    $workbook->getActiveSheet()->setCellValueByColumnAndRow(0,4,"");
    $workbook->getActiveSheet()->getStyleByColumnAndRow(0,4)->applyFromArray($title_format);
    $workbook->getActiveSheet()->setCellValueByColumnAndRow(0,5,"");
    $workbook->getActiveSheet()->getStyleByColumnAndRow(0,5)->applyFromArray($title_format);
    $workbook->getActiveSheet()->setCellValueByColumnAndRow(0,6,"");
    $workbook->getActiveSheet()->getStyleByColumnAndRow(0,6)->applyFromArray($title_format);
    $workbook->getActiveSheet()->freezePane('A1');
    //$worksheet->freezePanes(array(1, 0));
    
    $row=1;
    $column=0;
    
    if ($array["meta"]["totalresults"] > 0) {
      $colnames = (array_keys($array['data'][0]));
      
      foreach($colnames as $colname) {
        //echo($colname."<br/>");
        if (($colname != 'listtype') && ($colname != 'id')) {
          $workbook->getActiveSheet()->setCellValueByColumnAndRow($row,$column,$colname);
          $workbook->getActiveSheet()->getStyleByColumnAndRow($row,$column)->applyFromArray($mgr_format);
          $column++;
        }
      }
      
      $row++;
      $column=0;
      $count = 0;
      
      $theitems = $array["data"];
      $screening = "";
        
      foreach($theitems as $item) {
        foreach($item as $key=>$value) {
          if (($key != 'listtype') && ($key != 'id')) {
            //echo($key ."=". $value."=".$screening."<br />");
            if (($key == "Screening") && ($screening != $value) && ($screening != "")) {
              $workbook->getActiveSheet()->setCellValueByColumnAndRow($row,0,"");
              $workbook->getActiveSheet()->setCellValueByColumnAndRow($row,1,"");
              $workbook->getActiveSheet()->setCellValueByColumnAndRow($row,2,"");
              $workbook->getActiveSheet()->setCellValueByColumnAndRow($row,3,"");
              $workbook->getActiveSheet()->setCellValueByColumnAndRow($row,4,"");
              $workbook->getActiveSheet()->setCellValueByColumnAndRow($row,5,"Total");
              $workbook->getActiveSheet()->setCellValueByColumnAndRow($row,6,$count);
              $row++;
              $workbook->getActiveSheet()->setCellValueByColumnAndRow($row,0,"");
              $row++;
              $count = 0;
              //echo ("<br />");
            }
            if ($key == "Screening") {
              $screening = $value;
              $count++;
            }
            $workbook->getActiveSheet()->setCellValueByColumnAndRow($row,$column,(strlen(trim($value)) == 0)?"":$value);
            $column++;
          }
        }
        $column=0;
        
        //Add each user's chat usage here:
        /*
        $searray = $this -> dataMap( sfConfig::get("sf_lib_dir")."/widgets/ScreeningReports/query/ScreeningEntry_list_datamap.xml", false );
        if ($searray["meta"]["totalresults"] > 0){
        foreach($searray["data"] as $seitem) {
          $row++;
          $column=0;
          foreach($item as $key=>$value) {
            $workbook->getActiveSheet()->setCellValueByColumnAndRow($row,$column,(strlen(trim($value)) == 0)?"":$value);
            $column++;
          }
        }}
        */
        $row++;
      }  
    }
    
    // Save Excel 2007 file
    $objWriter = new PHPExcel_Writer_Excel2007($workbook);
    $objWriter->save($args["location"]."/".$this -> encodeFilename($args["filename"]));
    
    return $this -> encodeFilename($args["filename"]);
    
  }

} ?>
