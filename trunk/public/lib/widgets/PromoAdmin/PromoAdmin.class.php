<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/PromoAdmin_crud.php';
  
   class PromoAdmin_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	  $this -> XMLForm -> item_forcehidden = true;
    $this -> crud = new PromotionCrud( $context );
    parent::__construct( $context );
  }

	function parse() {
	 
	 if ($this -> as_service) {
      $sql = "select promotion_id, promotion_name from promotion where promotion_name like ?";
      $term = "%".$this -> getVar("term")."%";
      $rs = $this -> propelArgs($sql,array($term));
      while ($row = $rs -> fetch()) {
        $ar["id"] = $row[0];
        $ar["value"] = $row[1];
        $arr[] = $ar;
      }
      if (count($arr) > 0) {
        print json_encode($arr);
      }
      die();
    }
    
	 //return $this -> widget_vars;
   
	  if ($this -> XMLForm -> isPosted()) {  
      $this -> doPost();
    }
    $this -> doGet();
   
   $this -> SearchForm = new XMLForm($this -> widget_name, "searchconf.php");
   $this -> SearchForm -> validated= false;
   $searchform = $this -> SearchForm -> drawForm();
   $this -> widget_vars["search_form"] = $searchform["form"];
   
   $form = $this -> drawPage();
   $this -> widget_vars["form"] = $form["form"];
   return $this -> widget_vars;
    
  }

  function doPost(){
     
     if ($this -> XMLForm -> validateForm()) {
        switch ($this -> getFormMethod()) {
          case "submit":
          $this -> crud -> write();
          
          //Update the SOLR Search Engine
          $solr = new SolrManager_PageWidget(null, null, $this -> context);
          $solr -> execute("add","promotion",$this -> crud -> Promotion -> getPromotionId());
          break;
          case "delete":
          $this -> crud -> remove();
          //Update the SOLR Search Engine
          $solr = new SolrManager_PageWidget(null, null, $this -> context);
          $solr -> execute("delete","promotion",$this -> crud -> Promotion -> getPromotionId());
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
    
    if ($this ->getOp() == "search" ) {
      return $this -> promoSearch();
    } elseif (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      return $this -> returnForm();
    } elseif ($this ->getOp() == "list" ) {
      return $this -> returnList();
    }
    
  }
  
  function promoSearch( $query=false ) {
    
    //$this -> showData();
    //$this -> showXML();  
  
    $util = new PromoAdmin_format_utility( $this -> context );
    
    if (! $query) {
    
      if (($this -> greedyVar("promo_start_date")) && (! $this -> greedyVar("promo_end_date"))) {
        $start = formatDate($this -> greedyVar("promo_start_date"),"W3XMLIN");
        //$end = formatDate($this -> postVar("cms_object_end_date"),"W3XML");
        $util -> startdate = "[ ". $start ." TO * ]"; 
      }
      if ((! $this -> greedyVar("promo_start_date")) && ($this -> greedyVar("promo_end_date"))) {
        //$start = formatDate($this -> postVar("cms_object_start_date"),"W3XML");
        $end = formatDate($this -> greedyVar("user_end_date"),"W3XMLIN");
        $util -> enddate = "[ * TO ". $end." ]"; 
      }
      if (($this -> greedyVar("promo_start_date")) && ($this -> greedyVar("promo_end_date"))) {
        $start = formatDate($this -> greedyVar("promo_start_date"),"W3XMLIN");
        $end = formatDate($this -> greedyVar("promo_end_date"),"W3XMLIN");
        $util -> startdate = "[ ". $start ." TO ". $end." ]"; 
        $util -> enddate = "[ ". $start ." TO ". $end." ]"; 
      }
      if (($this -> greedyVar("promo_term"))) {
        $term = $this -> greedyVar("promo_term");
      }
      
      $search = ($term != "") ? $term : "[ * TO * ]";
      
      if (is_numeric($term) && ($term < 1000000)) {
        $num = $term;
      } else {
        $num = 0;
      }
      
      sfConfig::set("search",$term);
      sfConfig::set("num",$num);
      
    }
    
    
    return $this -> returnList( "PromoAdmin_search_datamap.xml", true, true, "standard", $util );
    
  }
  
}
?>
