<?php

/**
 * WTVRData.php, Styroform XML Form Controller
 * 
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
// WTVRData

/**
 * The Form Controller class, responsible for drawing the form, calling the validator, adding "Items" and "ItemLists", and navigating through formsets
 * @package WTVRData
 * @subpackage classes
 */
class WTVRData extends Utils_PageWidget{
  
  var $res;
  
  var $tablepeer;
  var $map;
  var $resultname;
  var $titlename;
  var $allowadd;
  var $genExcel;
  var $primary;
  var $sort;
  var $sorts;
  var $orders;
  var $distincts;
  var $cols;
  var $colcount;
  
  //Sepcific to the Resultset, these are arrays
  var $col;
  var $prefix;
  var $format;
  var $suffix;
  var $order;
  var $size;
  var $key;
  var $outputformat;
  var $name;
  var $col_length;
  var $thegroups;
  var $hidden;
  var $rpp;
  var $ppp;
  var $limit;
  var $limit_total;
  var $terms;
  var $skipcount;
  var $baseref;
  
  var $theattribs;
  var $thelinks;
  
  var $structype;
  
  //For Lone SQL Queries
  var $sqlquery;
  var $sqlcountquery;
  var $sqlcount;
  var $sqlselect;
  var $sqlcriteria;
  
  //For SOLR Queries
  var $boost;
  
  var $result;
  var $result_postprocess;
  var $pagename;
  var $offsetname;
  var $baseqs;
  var $total;
  
  var $output_type;
  var $VARIABLES;
  
  var $format_utility;
  
  //Cache Variables
  var $docache;
  var $cachepath;
  var $cachedir;
  var $cachefile;
  var $cachesubdir;
  var $cacheparams;
  
  var $conf;
  var $doinit;
  
  var $isdebug;
  var $record_query;
  var $record_query_result_id;
  var $stmt;
  var $is_record_query;
  var $nullCallback;
  var $postCallback;
  var $recordParams;
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
  * @param array $formsettings  - Array with both Formset, and XSL Doc
  */
  function __construct( $context ) {
    if (! $context ) {
      parent::__construct( sfContext::getInstance());
    } else {
      parent::__construct( $context );
    }
    
    $this -> output_type = "array";
    $this -> structype = "xml";
    $this -> map = false;
    $this -> is_record_query = false;
    $this -> isdebug = false;
    //If this is true, and a method recordQuery() exists in the utility
    //The string or sql will be pushed to that DB Table
    //Only available with SQL or SOLR, not with Propel
    $this -> record_query = false;
    $this -> record_query_result_id = false;
    $this -> nullCallback = false;
    $this -> doinit = true;
    $this -> requestParams = array();
    $this -> docache = "skip";
    
  }
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name propelQuery
  * @param string $sql - FPO copy here
  */
  function propelQuery($sql,$connect=null) {
    $con = Propel::getConnection($connect, Propel::CONNECTION_READ);
    //$stmt = $con->createStatement();
    //$rs = $stmt->executeQuery($sql, ResultSet::FETCHMODE_NUM);
    $statement = $con->prepare($sql);
    $statement->execute();
    
    return $statement;
  }
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name propelQuery
  * @param string $sql - FPO copy here
  */
  function propelInsert($sql,$connect=null) {
    $con = Propel::getConnection($connect);
    $statement = $con->prepare($sql);
    $statement->execute();
    
    return true;
  }
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name propelQuery
  * @param string $sql - FPO copy here
  */
  function propelArgs($sql,$args,$connect=null) {
    $con = Propel::getConnection($connect, Propel::CONNECTION_READ);
    $statement = $con->prepare($sql);
    $i=1;
    foreach($args as $value) {
      $statement->bindValue($i, $value);
      $i++;
    }
    $statement->execute();
    
    return $statement;
  }
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name dataMap
  * @param string $conf - FPO copy here
  * @param string $limit - FPO copy here
  */
  function dataMap( $conf, $limit=true, $format="array", $util=false ) {
    
    $this -> conf = $conf;
    
    if (is_object($util)) {
        $this -> format_utility = $util;
    }
    
    $this -> initConf( $conf );
    
    $this -> initCache( $conf );
    
    //if (! $this -> readCache()) {
      
      $this -> output_type = $format;
      $result = null;
      
      $rs = $this -> buildResult( $conf, $limit );
      
      if (! $rs)
        return false;
      
      if ($rs != "cache") {
        
        /*Compile our list of Results*/
        $cnt = 0;
        $j=1;
        
        switch ($this -> structype) {
          case "mongo":
            if (! is_array($rs)) {
              while( $rs->hasNext() ) {
                $items[] = $rs -> getNext();
              }
              $rs = $items;
            }
            if (is_array($this -> distincts)) {
              $check_distinct = true;
              $distinct_added=array();
            }
            if (count($rs) > 0) {
            foreach($rs as $item){
              for ($i=1;$i<$this -> colcount+1;$i++) {
                
                $tmpval = ($this -> col[$i-1] == "null") ? $this -> name[$i-1] : $item[ $this -> col[$i-1] ];
                
                $tmpval = $this -> formatValue($tmpval,$i,$cnt);
                
                $tmpval = WTVRcleanString($tmpval);
                
                $tmp[$this -> name[$i-1]] = $this -> prefix[$i-1] . $tmpval  . $this -> suffix[$i-1];
                ($this -> col[$i-1] != "null") ? $j++ : null ;
                
                
              }
              array_multisort($this -> order, $tmp);
              
              $distinct_add=true;
              if ($check_distinct) {
                $thisone = "";
                foreach($this -> distincts as $key) {
                  $thisone .= ":".$tmp[$key];
                }
                if (in_array($thisone,$distinct_added)) {
                  $distinct_add=false;
                } else {
                  $distinct_added[] = $thisone;
                }
              }
              if ($distinct_add) {
                $this -> result[$cnt] = $tmp;
              }
              $cnt++;
            }
            if ($check_distinct) {
              $this -> total = count($this -> result);
            }
            }
            
            break;
            
          case "solr":
          
            $j=1;
            
            if (($rs -> getElementsByTagName("result") -> item(0) -> getAttribute("numFound") == 0) && ($this -> nullCallback)) {
              eval("\$data = \$this -> format_utility -> ".$this -> nullCallback."();");
              return $data;
            }
            
            if (is_array($this -> distincts)) {
              $check_distinct = true;
              $distinct_added=array();
            }
            
            foreach ($rs -> getElementsByTagName("doc") as $item) {
              $tmp = null;
              
              $nodes = $item -> childNodes;
              //dump($item->saveXML());
              
              foreach ($nodes as $node) {
                
                if ($node -> nodeType == 1) {
                  $i = array_search($node -> getAttribute("name"),$this -> col,true) + 1;
                  
                  //kickdump($node -> getAttribute("name") . " = " . $node -> nodeValue);
                  //If this return value is an array, create an array with those unique vals
                  if ($node -> nodeName == "arr") {
                    $vals=null;
                    $subnodes = $node -> childNodes;
                    foreach ($subnodes as $subnode) {
                    	if ($subnode -> nodeType == 1) {
                    	 $vals[] = $subnode -> nodeValue;
                    	}
                    }
                    $tmpval = $vals;
                  } else {
                    $tmpval = $node -> nodeValue;
                  }
                  //kickdump($node -> getAttribute("name") . " = " . $node -> nodeValue . " at " . $i);
                  $tmpval = $this -> formatValue($tmpval,$i,$cnt);
                  //kickdump($node -> getAttribute("name") . " = " . $node -> nodeValue . " at " . $i . " Became " . $tmpval);
                  
                  if (! is_array($tmpval)) {
                    $tmp[$node -> getAttribute("name")] = $this -> prefix[$i-1] . $tmpval  . $this -> suffix[$i-1];
                  } else {
                    $tmp[$node -> getAttribute("name")] = $tmpval;
                  }
                }
              }
              
              $thearr=null;
              $j=0;
              foreach($this -> col as $colname) {
                $thearr[$this -> name[$j]]=$tmp[$colname];
                $j++;
              }
              
              array_multisort($this -> order, $thearr);
              
              $distinct_add=true;
              if ($check_distinct) {
                $thisone = "";
                foreach($this -> distincts as $key) {
                  $thisone .= ":".$thearr[$key];
                }
                
                if (in_array($thisone,$distinct_added)) {
                  $distinct_add=false;
                } else {
                  $distinct_added[] = $thisone;
                }
              }
              if ($distinct_add) {
                $this -> result[$cnt] = $thearr;
              }
              
              $cnt++;
              $j++;
              
            }
            
            if ($check_distinct) {
              $this -> total = count($this -> result);
            }
	
            break;
          
          default:
          	
						if ($rs -> rowCount() > 0) {
            while ($row = $rs->fetch()) {
              for ($i=0;$i<$this -> colcount;$i++) {
              
                $tmpval = ($this -> col[$i] == "null") ? $this -> name[$i] : $row[$i];
                
                $tmpval = $this -> formatValue($tmpval,$i+1,$cnt);
                
                $tmpval = WTVRcleanString($tmpval);
                
                $tmp[$this -> name[$i]] = $this -> prefix[$i] . $tmpval  . $this -> suffix[$i];
                ($this -> col[$i] != "null") ? $j++ : null ;
                
              }
              //dump($this -> order);
              //dump($tmp);
              array_multisort($this -> order, $tmp);
              //dump($tmp);
              $this -> result[$cnt] = $tmp;
              $cnt++;
              $j=1;
            }}
        	 break;
        }
        
        /*Post Process to add the appropriate HREFS*/
        if ((count($this -> result) > 0) && (count($this ->result_postprocess) > 0)) {
          $cnt = 0;
          foreach($this -> result as $aresult) {
            foreach ($this -> result_postprocess as $postcol => $postfunc) {
              if (in_array($postcol,$this -> name)) {
                $tmpval = $this -> format_utility -> $postfunc($aresult,$postcol);
                $this -> result[$cnt][$postcol] = $tmpval;
              }
            }
            $cnt++;
          }
          //dump($this -> result_postprocess);
        }
        
        /*Post Process to add the appropriate HREFS*/
        if ((count($this -> result) > 0) && (count($this ->thelinks) > 0)) {
        $cnt = 0;
        foreach($this -> result as $aresult) {
          foreach($aresult as $col => $value) {
            
            foreach($this -> thelinks as $link) {
            if (isset($link[$col])) {
              $alink = "";
              $i=0;
              //Make this a javacript link...
              if ($link[$col]["javascript"] != "") {
                switch ($link[$col]["javascript"]) {
                  //Note, this is considered a general enough case that it isn't included in a "utils" class
                  //But it does require a specific call to a specific service in the application, represented
                  //As a URL in the "Content" attribute.
                  case "DojoDynamicDialog":
                    $thisid = str_replace("-","_",cleanFileName($value))."_".nowAsId();
                    $this -> result[$cnt][$col] = "<div dojoType=\"dojo_widgets.layout.DynamicDialog\" contentHref=\"'".$link[$col]["base"].$aresult[$link[$col]["attribs"][0]]."'\" button=\"'".$value."'\" theId=\"'".$thisid."'\" title=\"'".$value."'\"></div>";
                    break;
                  case "attribute":
                    $this -> result[$cnt][$col] = "<a ".$link[$col]["attribute"]."=\"".$this -> result[$cnt][$link[$col]["attribs"][0]]."\">".$value."</a>";
                    break;
                  default:
                    foreach($link[$col]["attribs"] as $attrib) {
                      if ($i > 0){ $alink .= ","; }
                      $alink .= "\$aresult['".$attrib."']";
                      $i++;
                    }
                    $js_res = eval("return sprintf(\"".$link[$col]["javascript"]."\", ".$alink.");");
                    $this -> result[$cnt][$col] = "<a href=\"#\" onclick=\"".$js_res."\">".$value."</a>";
                    break;
                }
                //dump($this -> result[$cnt][$col]);
              //Make this a javacript link...
              } elseif ($link[$col]["dialog"] != "") {
              //Make this a static link
              } else {
                foreach($link[$col]["attribs"] as $attrib) {
                  if ($i > 0){ $alink .= "/"; } $alink .= $aresult[$attrib]; $i++;
                }
                $this -> result[$cnt][$col] = $link[$col]["base"].$alink."|".$value;
              }
            }
          }}
          $cnt++;
        }}
        
        /* Set the base QS removing the reserved var names from the query conf */
        $qvars = array();
        if (isset($_SERVER["QUERY_STRING"])) {
          $qvars = explode("&",$_SERVER["QUERY_STRING"]);
        }
        
        $this -> baseqs = "?";
        foreach($qvars  as $pair) {
          $vals = explode("=",$pair);
          if ((isset($vals[1])) && ($vals[0] != $this -> sort) && ($vals[0] != $this -> pagename) && ($vals[0] != "")) {
            $this -> baseqs .= $vals[0]."=".$vals[1]."&";
          }
        }
        
        //Pull terms from the UTIL class if they exist
        if ((is_object($this -> format_utility)) && ($this -> format_utility -> terms)) {
          $this -> terms = $this -> format_utility -> terms;
        }
        
        //$baseqs=substr($baseqs,0,strlen($thevars) - 1);
        $this -> res["data"] = $this -> result;
        $this -> res["hidden"] = $this -> hidden;
        $this -> res["meta"]["name"] = $this -> resultname;
        $this -> res["meta"]["title"] = $this -> titlename;
        $this -> res["meta"]["allow_add"] = $this -> allowadd;
        $this -> res["meta"]["pk"] = $this -> primary;
        $this -> res["meta"]["totalresults"] = $this -> total;
        $this -> res["meta"]["currentresults"] = count($this -> result);
        $this -> res["meta"]["pagevar"] = $this -> pagename;                    
        $this -> res["meta"]["page"] = $this -> page;
        $this -> res["meta"]["rpp"] = $this -> rpp;
        $this -> res["meta"]["ppp"] = $this -> ppp;
        $this -> res["meta"]["baseqs"] = $this -> baseqs;
        $this -> res["meta"]["script_default"] = $this -> baseref;
        $this -> res["meta"]["sort"] = $this -> sorts;
        $this -> res["meta"]["terms"] = $this -> terms;
        $this -> res["meta"]["group"] = $this -> thegroups;
        $this -> res["meta"]["result_id"] = $this -> record_query_result_id;
        $this -> res["output"]["sizes"] = $this -> size;
        $this -> res["output"]["format"] = $this -> outputformat;
        if ($this -> map -> getElementsByTagName("textbody"))
        $this -> res["output"]["textbody"] = $this -> map -> getElementsByTagName("textbody") -> item(0) -> nodeValue;
        if ($this -> map -> getElementsByTagName("textfooter"))
        $this -> res["output"]["textfooter"] = $this -> map -> getElementsByTagName("textfooter") -> item(0) -> nodeValue;
        if ($this -> map -> getElementsByTagName("formwrapper"))
        $this -> res["output"]["formwrapper"] = $this -> map -> getElementsByTagName("formwrapper") -> item(0) -> nodeValue;
        
        if (sfConfig::get("app_query_cache") && ($this -> docache != "")) {
          //dump($this -> getCacheParams());
          $this -> writeCache( $this -> res );
        }
      }
    //}
    
    if ($this -> postCallback) {
      eval("\$this -> format_utility -> ".$this -> postCallback."( \$this -> res );");
    }
    
    switch ($this -> output_type) {
      case "json":
      return json_encode($this -> res);
      break;
      default:
      return $this -> res;
      break;
    }
  }
  
  function formatValue( $tmpval, $i, $cnt) {
  	
    $tmpval = ($this -> col_length[$i-1] > 0) ? substr($tmpval,0,$this -> col_length[$i-1])."..." : $tmpval;
    
    if ($this -> format[$i-1] != ""){
      switch ($this -> format[$i-1]) {
        case "date":
          if ($tmpval == "") {
            $tmpval = "null";
          } else {
            $tmpval = ((is_numeric($tmpval)) && (strlen($tmpval) == 10)) ? $tmpval: strtotime($tmpval);
            $tmpval = date('m/d/y',$tmpval);
          }
        break;
        case "dateW3XML":
          throw new Exception('Date in Deprecated dateW3XML Format.');
        break;
        case "dateW3XMLIN":
          if ($tmpval != "") {
            $tmpval = formatDate($tmpval,"W3XMLIN");
          } else {
            $tmpval = "1900-01-01T0:00:00Z";
          }
        break;
        case "dateW3XMLOUT":
          if ($tmpval != "") {
            $tmpval = formatDate($tmpval,"W3XMLOUT");
          } else {
            $tmpval = "1900-01-01T0:00:00Z";
          }
        break;
        case "datetime":
          if ($tmpval == "") {
            $tmpval = "null";
          } else {
            $tmpval = ((is_numeric($tmpval)) && (strlen($tmpval) == 10)) ? $tmpval: strtotime($tmpval);
            $tmpval = date('m/d/y h:i:s A',$tmpval);
          }
        break;
        case "dateformat":
          if ($tmpval == "") {
            $tmpval = "null";
          } else {
            $tmpval = ((is_numeric($tmpval)) && (strlen($tmpval) == 10)) ? $tmpval: strtotime($tmpval);
            $tmpval = formatDate($tmpval,$this -> size[$i-1]);
          }
        break;
        case "enum":
          $thevars=explode(",",$this -> size[$i-1]);
          if (is_null($tmpval)) {
            $tmpval = "null";
          } else {
            $tmpval = $thevars[$tmpval];
          }
        break;
        case "image":
          $tmpval = '<img src="'.$this -> size[$i-1].'" border="0" />';
        break;
        case "list":
          $thevars=array_combine(explode(",",$this -> size[$i-1]),explode(",",$this -> key[$i-1]));
          $tmpval = $thevars[$tmpval];
        break;
        case "regex_replace":
          $tmpval=preg_replace($this -> size[$i-1],$this -> key[$i-1],trim($tmpval));
        break;
        case "bool":
          $thevars=explode(",",$this -> size[$i-1]);
          $tmpval = ($tmpval == 0) ? "false" : "true";
        break;
        case "javascript":
          $thevars=$this -> size[$i-1];
          $tmpval = "<span onclick=\"".$thevars."\">".$tmpval."</span";
        break;
        case "binary":
          $thevars=explode(",",$this -> size[$i-1]);
          $tmpval = ($tmpval == 1) ? 1 : 0;
        break;
        case "money_us":
          $tmpval = sprintf("$%.02f",$tmpval);
          break;
        case "null":
          $tmpval = ($tmpval == '') ? "null" : $tmpval;
        break;
        case "space":
          $tmpval = ($tmpval == '') ? " " : $tmpval;
        break;
        case "default":
          $tmpval = ($tmpval == '') ? $this -> key[$i-1] : $tmpval;
        break;
        default:
          $tmpval = sprintf($this -> format[$i-1],$tmpval);
        break;
      }
    }
    ($this -> urlencode[$i-1] == 'true') ? $tmpval = urlencode($tmpval) : null;
    ($this -> escapequote[$i-1] == 'true') ? $tmpval = str_replace("'","\'",$tmpval) : null;
    if (strlen($this -> filter[$i-1]) > 0) {
    	eval("\$tmpval = ".$this -> filter[$i-1]."(\$tmpval);");
    }
    
    if (is_object($this -> format_utility) && (strlen($this -> util[$i-1]) > 0)) {
      $func = $this -> util[$i-1];
      if (left($func,6)=="item::") {
        $thefunc = str_replace("item::","",$func);
        //$tmpval = $this -> format_utility -> $thefunc($tmpval);
        $this -> result_postprocess[$this -> name[$i-1]] = $thefunc;
      } else  {
        $tmpval = $this -> format_utility -> $func($tmpval);
      }
    }
    
    return $tmpval;
  }
  
  
  function buildResult( $conf, $limit=true ) {
    
    $this -> initConf( $conf );
    
    $sql = $this -> map -> getElementsByTagName("sql");
    $solr = $this -> map -> getElementsByTagName("solr");
    $mongo = $this -> map -> getElementsByTagName("mongo");
    
    switch (true) {
      case ($sql -> length > 0):
        $this -> structype = "sql";
        break;
      case ($solr -> length > 0):
        $this -> structype = "solr";
        break;
      case ($mongo -> length > 0):
        $this -> structype = "mongo";
        break;
      default:
        $this -> structype = "xml";
        break;
    }
    
    $this -> getName();
    $this -> getTitle();
    $this -> allowadd = $this -> map -> getPathAttribute("//map","allow_add");
    $this -> genExcel = $this -> map -> getPathAttribute("//map","genExcel");
    $this -> skipcount = $this -> map -> getPathAttribute("//map","skipcount");
    
    $this -> primary = $this -> map -> getPathAttribute("//column[@key='PRI']","column");
    
    if ($this -> structype == "xml") {
      $this -> tablepeer = ucwords(str_replace("_", " ", $this -> map -> getPathAttribute("//map","table")))."Peer";
      $this -> tablepeer = str_replace(" ","",$this -> tablepeer);
      $criteria = new Criteria(); 
      $criteria -> clearSelectColumns();             
    }
    
    $this -> cols = $this -> map -> getElementsByTagName("column");
    $this -> colcount = 0;
    
    $this -> sort = $this -> map -> getPathAttribute("//sort","var");
    
    foreach ($this -> cols as $column) {
      $this -> col[] = $column->getAttribute("column");
      $this -> prefix[] = $column->getAttribute("prefix");
      $this -> format[] = $column->getAttribute("format");
      $this -> suffix[] = $column->getAttribute("suffix");
      $this -> order[] = $column->getAttribute("order");
      $this -> size[] = $column->getAttribute("size");
      $this -> key[] = $column->getAttribute("key");
      $this -> outputformat[] = $column->getAttribute("outputformat");
      $this -> urlencode[] = $column->getAttribute("urlencode");
      $this -> escapequote[] = $column->getAttribute("escapequote");
      $this -> filter[] = $column->getAttribute("filter");
      $this -> util[] = (strlen($column->getAttribute("util")) > 0) ? $column->getAttribute("util") : false ;
      $this -> col_length[] = (strlen($column->getAttribute("col_length")) > 0) ? $column->getAttribute("col_length") : 0 ;
      $current = $this -> name[] = (strlen($column->getAttribute("name")) > 0) ? $column->getAttribute("name") : $column->getAttribute("column") ;
      
      (strlen($column->getAttribute("hidden")) > 0) ? $this -> hidden[] = $current : null ;
      $colname = strtoupper($column->getAttribute("column"));
      if (($column->getAttribute("column") != "null") && ($this -> structype == "xml"))  {
        eval("\$criteria ->addSelectColumn(".$this -> tablepeer."::".$colname.");");
      }
      $this -> colcount++;
    }
    
    $critters = $this -> map -> getElementsByTagName("criteria");
    
    switch ($this -> structype) {
      //*****************************************//
      //****Generate the Query Using MONGO DB   *****//
      //*****************************************//
      case "mongo":
      
        $connection = $this -> map -> getPathAttribute("//mongo","connection");
        $mdb = $this -> map -> getPathAttribute("//mongo","database");
        $collection = $this -> map -> getPathAttribute("//mongo","collection");
        $port = ($this -> map -> getPathAttribute("//mongo","port") == "") ? 27017 : $this -> map -> getPathAttribute("//mongo","port");
        $opts = explode(":",$this -> context -> getDatabaseManager() -> getDatabase($connection)->getParameter("dsn"));
	      if ($mdb != "") {
          $mdb = str_replace("%env",sfConfig::get("sf_environment"),$mdb);
          $opts[1] = $mdb;
        }
        $this -> hit = new Mongo($opts[0].":".$port);
        $this -> hitcollection = $this -> hit->selectDB( $opts[1] )->selectCollection( $collection );
        
        $this -> stmt = $this -> generateCriterion( $critters, "MONGO", $this -> stmt );
        $this -> stmt = $this -> generateSortOpts( $this -> stmt, "MONGO" );
        
        $this -> getRpp();
        
        $this -> offset = $this -> getOffset();
        $this -> page = $this -> getPage();
        
        if (($this -> offset > 0) && ($limit)) {
          if ($this -> stmt == null) {
            $rs = $this -> hitcollection -> find() -> limit( $this -> rpp ) -> skip( $this -> offset ) -> sort( $this -> orders );
          } else {
            $rs = $this -> hitcollection -> find( $this -> stmt ) -> limit( $this -> rpp ) -> skip( $this -> offset ) -> sort( $this -> orders );
          }
        } else if (($this -> rpp > 0) && ($this -> page > 0) && ($limit)) {
          $offset = ($this -> page - 1) * $this -> rpp;
          if ($this -> stmt == null) {
            $rs = $this -> hitcollection -> find() -> limit( $this -> rpp ) -> skip( $offset ) -> sort( $this -> orders );
          } else {
            $rs = $this -> hitcollection -> find( $this -> stmt ) -> limit( $this -> rpp ) -> skip( $offset ) -> sort( $this -> orders );
          }
        } else {
          if ($this -> stmt == null) {
            $this -> logItem("WTVR Data","Execute MONGO Query on DB \"".implode(",",$opts)."\"");
            $rs = $this -> hitcollection -> find();
          } else {
            $this -> logItem("WTVR Data","Execute MONGO Query \"".implode(",",$this -> stmt)."\" on DB \"".implode(",",$opts)."\"");
            $rs = $this -> hitcollection -> find( $this -> stmt );
          }
        }
        
        if ($this -> skipcount == "true") {
          $this -> total = null;
        } else {
          if ($this -> stmt == null) {
            $this -> logItem("WTVR Data","Execute MONGO Query Without Limits on DB \"".implode(",",$opts)."\"");
            $this -> total = $this -> hitcollection -> count();
          } else {
            $this -> logItem("WTVR Data","Execute MONGO Query Without Limits \"".implode(",",$this -> stmt)."\" on DB \"".implode(",",$opts)."\"");
            $this -> total = $this -> hitcollection -> count( $this -> stmt );
          }
        }
        
        if (sfConfig::get("showData")) {
          var_dump($this -> stmt);
          die();
        }
        
        break;
      case "solr":
        
        $curl = new Curl;
    
        $uri = "http://".sfConfig::get("app_solr_ip").":".sfConfig::get("app_solr_port").sfConfig::get("app_solr_uri")."/select/?";
        
        if (! $this -> record_query_result_id) {
          
          $this -> stmt = $this -> generateSolrQuery ( $critters, $this -> stmt );
          
        } else {
          
          $uriobj = $this -> format_utility -> retrieveQuery( $this -> record_query_result_id );
          
          if (! $uriobj) {
          
            $this -> stmt = $this -> generateSolrQuery ( $critters, $this -> stmt );
            
          } else {
            
            $this -> is_record_query = true;
          
            if (is_array($uriobj)) {
              $uri = $uriobj["search_string"];
              $params = $uriobj["search_params"];
            } elseif (is_object($uriobj)) {
              $uri = $uriobj -> getSearchString();
              $params = $uriobj -> getSearchParams();
            } else {
              return false;
            }
            
            $uri = preg_replace("/&(start|rows|fl|q|sort)=[^&]+/","",$uri);
            if($params != null) {
            if (! is_array($params)) {
              $jparams = json_decode($params);
              foreach($jparams as $name => $value) {
                $tparams[$name]=$value;
                sfConfig::set($name,$value);
                if ($name == "op") {
                  $tparams["op_rev"]=$value;
                  sfConfig::set("op_rev",$value);
                }
                if ($name == "total_limit") {
                  $this -> total_limit = $value;
                }
              }
              sfConfig::set("json_params",json_encode($tparams));
            } else {
            foreach($params as $name => $value) {
              sfConfig::set($name,$value);
              if ($name == "total_limit") {
                $this -> total_limit = $value;
              }
            }}
            sfConfig::set("json_params",json_encode($params));
            }
            //dump($this -> context -> getRequest() -> getParameterHolder() -> getAll());
            
          }
          
        }
        
        $this -> getRpp();
        
        if (($this -> rpp > 0) && ($limit)) {
          $this -> stmt = $this -> stmt . "&rows=".$this -> rpp;
        } elseif ($this -> rpp == 0) {
          //http://wiki.apache.org/solr/FAQ#How_can_I_get_ALL_the_matching_documents_back.3F_..._How_can_I_return_an_unlimited_number_of_rows.3F
          //Read the above comment. Fucking Java Twats.
          $this -> stmt = $this -> stmt . "&rows=10000";
        }
        
        $this -> offset = $this -> getOffset();
        $this -> page = $this -> getPage();
        
        //dump($this -> page);
        if (($this -> offset > 0) && ($limit)) {
          $this -> stmt = $this -> stmt . "&start=".$this -> offset;
        } else if (($this -> page > 0) && ($limit)) {
          $offset = ($this -> page - 1) * $this -> rpp;
          $this -> stmt = $this -> stmt . "&start=".$offset;
        }
        
        $this -> stmt = $this -> generateSortOpts( $this -> stmt, "SOLR" );
        
        $fields = implode(",",$this->col);
        $this -> stmt .= "&fl=".$fields.",score";
        $this -> stmt .= "&bq=".urlencode($this -> boost);
        
        $this -> cacheparams = $this -> stmt;
        
        if ($this -> readCache( true )) {
          return "cache";
        }
        
        $url = $uri . "q=" . $this -> stmt;
       
        if (sfConfig::get("showData")) {
          print(urldecode($url));
          die();
        }
        
        $this -> logItem("WTVR Data","Prepare SOLR Query \"".$url."\"");
        
        $rs = new XML();
        $rs -> loadXML($curl->get($url)->body);
        if (sfConfig::get("showXML")) {
          $rs -> saveXML();
        }
        $this -> total = $rs -> getElementsByTagname("result") -> item(0) -> getAttribute("numFound");
        if (($this -> total_limit > 0) && ($this -> total > $this -> total_limit)) {
          $this -> total = $this -> total_limit;
        }
        
        //This method should return the Query ID for the result set
        if (($this -> record_query) && ( ! $this -> is_record_query)) {
          $rams = $this -> context -> getRequest() -> getParameterHolder()->getAll();
          foreach($this -> requestParams as $param) {
            $rams[$param]=sfConfig::get($param);
          }
          $rams["total_limit"] =  $this -> total_limit;
          
          $this -> record_query_result_id = $this -> format_utility -> recordQuery( $url, $rams );
        }
        
        
      //*****************************************//
      //****End Generate Using SOLR         *****//
      //*****************************************//
      break;
      
      
      //*****************************************//
      //****Generate the Query Using SQL    *****//
      //*****************************************//
      case "sql":
        
        $connection = $this -> map -> getPathAttribute("//sql","connection");
        $con = Propel::getConnection( $connection );
        $this -> sqlquery = $this -> map -> getElementsByTagName("sqlselect") -> item(0) -> nodeValue;
        $this -> generateSortOpts( $criteria, "SQL" );
        
        if ($this -> resultname == 'cms_object_list_datamap') {
          //print($this -> sqlquery);
          //die();
        }
        
        if (sfConfig::get("showData")) {
          print(urldecode($this -> sqlquery));
          die();
        }
        
        $this -> logItem("WTVR Data","Prepare Native SQL Query \"".$this -> sqlquery."\"");
        
        $this -> stmt = $con->prepare($this -> sqlquery);
        
        //Start Criteria
        $this -> stmt = $this -> generateCriterion( $critters, "SQL", $this -> stmt );
        
        //$this -> cacheparams = $this -> sqlquery . $this -> cacheparams;
        
        if ($this -> readCache( true )) {
          return "cache";
        }
        
        if ($this -> skipcount == "true") {
          $this -> total = null;
        } else {
          $this -> logItem("WTVR Data","Execute Native SQL Query Without Limits \"".$this -> sqlquery."\"");
          /*
          $rs_count = $this -> stmt -> execute(ResultSet::FETCHMODE_NUM);
          $this -> total = $rs_count -> getRecordCount();
          */
          $this -> stmt -> execute();
          $this -> total = $this -> stmt -> rowCount();
        }
        
        $this -> getRpp();
        
        if (($this -> rpp > 0) && ($limit)) {
          $this -> sqlquery .= " limit ".$this -> rpp." ";
        }
        
        $this -> offset = $this -> getOffset();
        $this -> page = $this -> getPage();
        
        if (($this -> offset > 0) && ($limit)) {
          $this -> sqlquery .= " offset ".$this -> offset." ";
        } else if (($this -> page > 0) && ($this -> rpp > 0) && ($limit)) {
          $offset = ($this -> page - 1) * $this -> rpp;
          $this -> sqlquery .= " offset ".$offset." ";
        }
        
        $this -> stmt = $con->prepare($this -> sqlquery);
        
        //Start Criteria
       $this -> stmt = $this -> generateCriterion( $critters, "SQL", $this -> stmt );
        
        $this -> cacheparams = $this -> sqlquery . $this -> cacheparams;
        
        //This method should return the Query ID for the result set
        if ($this -> record_query) {
          $this -> record_query_result_id = $this -> format_utility -> recordQuery( $this -> sqlquery );
        }
        
        $this -> logItem("WTVR Data","Execute Native SQL Query With Limits \"".$this -> sqlquery."\"");
        $this -> stmt-> execute();
        
        $rs = $this -> stmt;
       
      //*****************************************//
      //****End Generate Using SQL          *****//
      //*****************************************//
      break;
      
      //*****************************************//
      //****Generate Query Using Criterion  *****//
      //*****************************************//
      case "xml":
        
        $joins = $this -> map -> getElementsByTagName("join");
        foreach ($joins as $join) {
          if ($join -> getAttribute("localtable") != "") {
            $localtablepeer = ucwords(str_replace("_", " ", $join -> getAttribute("localtable")))."Peer";
            $localtablepeer = str_replace(" ","",$localtablepeer);
          } else {
            $localtablepeer = $this -> tablepeer;
          }
          if ($join -> getAttribute("foreigntable") != "") {
            $foreigntablepeer = ucwords(str_replace("_", " ", $join -> getAttribute("foreigntable")))."Peer";
            $foreigntablepeer = str_replace(" ","",$foreigntablepeer);
          } else {
            $foreigntablepeer = ucwords(str_replace("_", " ", $join -> getAttribute("table")))."Peer";
            $foreigntablepeer = str_replace(" ","",$foreigntablepeer);
          }
          $localkey = strtoupper($join -> getAttribute("local"));
          $foreignkey = strtoupper($join -> getAttribute("foreign"));
          if ($join -> getAttribute("left") == "true") {
            //print("\$criteria -> addJoin(".$localtablepeer."::".$localkey.",".$foreigntablepeer."::".$foreignkey.");<br />");
            eval("\$criteria -> addJoin(".$localtablepeer."::".$localkey.",".$foreigntablepeer."::".$foreignkey.", Criteria::LEFT_JOIN);");
          } else {
            //print("\$criteria -> addJoin(".$localtablepeer."::".$localkey.",".$foreigntablepeer."::".$foreignkey.");<br />");
            eval("\$criteria -> addJoin(".$localtablepeer."::".$localkey.",".$foreigntablepeer."::".$foreignkey.");");
          }
          
          /*
          $this -> size[] = $column->getAttribute("size");
          $this -> key[] = $column->getAttribute("key");
          $this -> outputformat[] = $column->getAttribute("outputformat");
          $this -> filter[] = $column->getAttribute("filter");
          $this -> util[] = (strlen($column->getAttribute("util")) > 0) ? $column->getAttribute("util") : false ;
      
          */
          
          $foreigncols = $join -> getElementsByTagName("foreigncolumn");
          foreach($foreigncols as $foreigncolumn) {
            $this -> col[] = $foreigncolumn->getAttribute("column");
            $this -> col_length[] = (strlen($foreigncolumn->getAttribute("col_length")) > 0) ? $foreigncolumn->getAttribute("col_length") : 0 ;
            $this -> prefix[] = $foreigncolumn->getAttribute("prefix");
            $this -> format[] = $foreigncolumn->getAttribute("format");
            $this -> suffix[] = $foreigncolumn->getAttribute("suffix");
            $this -> order[] = $foreigncolumn->getAttribute("order");
            $this -> urlencode[] = $foreigncolumn->getAttribute("urlencode");
            $this -> escapequote[] = $foreigncolumn->getAttribute("escapequote");
            $this -> filter[] = $foreigncolumn->getAttribute("filter");
            $this -> size[] = $foreigncolumn->getAttribute("size");
            $this -> key[] = $foreigncolumn->getAttribute("key");
            $this -> outputformat[] = $foreigncolumn->getAttribute("outputformat");
            $this -> filter[] = $foreigncolumn->getAttribute("filter");
            $this -> util[] = (strlen($foreigncolumn->getAttribute("util")) > 0) ? $foreigncolumn->getAttribute("util") : false ;
            $current = $this -> name[] = (strlen($foreigncolumn->getAttribute("name")) > 0) ? $foreigncolumn->getAttribute("name"): $foreigncolumn->getAttribute("column") ;
            (strlen($foreigncolumn->getAttribute("hidden")) > 0) ? $this -> hidden[] = $current : null ;
            $foreigncolname = strtoupper($foreigncolumn->getAttribute("column"));
            if ($foreigncolumn->getAttribute("column") != "null") {
              eval("\$criteria ->addSelectColumn(".$foreigntablepeer."::".$foreigncolname.");");
            }
            $this -> colcount++;
          }
        }
        
        //Start Propel Criteria
        $this -> generateCriterion( $critters, "Propel", $criteria );
        
        $sqlgroups = $this -> map -> getElementsByTagName("sqlgroup");
        foreach ($sqlgroups as $sqlgroup) {
          $criteria -> addGroupByColumn( $sqlgroup->getAttribute("column") );
        }
        
        $groups = $this -> map -> getElementsByTagName("group");
        foreach($groups as $group) {
          $this -> thegroups[] = (strlen($group->getAttribute("name")) > 0) ? $group->getAttribute("name"): $group->getAttribute("column") ;
        }
        $this -> hidden[] = "styrogroup_val";
        
        $criteria = $this -> generateSortOpts( $criteria );
          
        if ($this -> readCache( true )) {
          return "cache";
        }
        
        $distinct = $this -> map -> getPathAttribute("//criterion","distinct");
        if ($distinct == "true") {
          $criteria->setDistinct();
        }
        
        if ($this -> skipcount == "true") {
          $this -> total = null;
        } else {
          $this -> logItem("WTVR Data","Execute Propel Query Without Limits \"".$this -> resultname."\"");
          eval("\$this -> total = ".$this -> tablepeer."::doCount(\$criteria);");
        }
        $this -> getRpp();
        
        if (($this -> rpp > 0) && ($limit)) {
          $criteria->setLimit( $this -> rpp );
        }
        
        $this -> offset = $this -> getOffset();
        $this -> page = $this -> getPage();
        
        if (($this -> offset > 0) && ($limit)) {
          $criteria->setOffset( $this -> offset );
        } else if (($this -> page > 0) && ($limit)) {
          $offset = ($this -> page - 1) * $this -> rpp;
          $criteria->setOffset( $offset );
        }
        
        $this -> logItem("WTVR Data","Execute Propel Query With Limits \"".$this -> resultname."\"");
        $rs = BasePeer::doSelect($criteria);
        break;
      //*****************************************//
      //****End Generate Using Criterion    *****//
      //*****************************************//
      
    }
    
    $termlinks = $this -> map -> getElementsByTagName("term");
    
    foreach ($termlinks as $term) {
      $attribv = $term -> getAttribute("name");
      $attribd = $term -> getAttribute("value");
      $aterm["name"] = $attribv;
      $aterm["value"] = $attribd;
      $this -> terms[] = $aterm;
    }
    
    $maplinks = $this -> map -> getElementsByTagName("maplink");
    
    foreach ($maplinks as $maplink) {
      $this -> theattribs = array();
      $attribs = $maplink -> getElementsByTagName("attribute");
      foreach($attribs as $attrib) {
        $this -> theattribs[] = $attrib -> getAttribute("name");
      }
      
      //Pull link params from the UTIL class if they exist
      if ((is_object($this -> format_utility)) && ($this -> format_utility -> maplinks)) {
        $thelinks = $this -> format_utility -> maplinks;
        foreach ($thelinks as $link) {
          $key = array_keys($link);
          $link[$key[0]]["attribs"]=$this -> theattribs;
          $this -> thelinks[]=$link;
        }
      } else {
        $this -> thelinks[] = array($maplink->getAttribute("column")=>
                array(
                  "base"=>$maplink->getAttribute("base"),
                  "javascript"=>$maplink->getAttribute("javascript"),
                  "params"=>array_combine(explode(",",$maplink->getAttribute("paramnames")),explode(",",$maplink->getAttribute("params"))),
                  "attribute"=>$maplink->getAttribute("attribute"),
                  "attribs"=>$this -> theattribs
                  )
                );
      }
    }
    //dump($this -> thelinks);
    $this -> ppp = $this -> map -> getPathAttribute("//pagesperpage","value");
    
    if (sfConfig::get("showData")) {
      dump($rs);
    }
    
    if ($this -> resultname == 'user_order_product_list_datamap') {
      //dump($rs);
    }
    
    return $rs;
    
  }
  
  function generateSolrQuery( $critters, $stmt ) {
  
    //Generate our URL
    $stmt = $this -> generateCriterion( $critters, "SOLR", $stmt );
    //Drop the last statement delimiter
    //$stmt = (right($stmt,5) == " AND ") ? substr($stmt, 0, -5) : substr($stmt, 0, -2);
    
    $stmt = (right($stmt,1) == ", ") ? substr($stmt, 0, -2) : $stmt;
    $stmt = urlencode($stmt) . "&version=2.2&indent=on";
    $stmt = str_replace("+%2C+&version","&version",$stmt);
    return $stmt;
    
  }
  
  function setStatement( $stmt, $i, $value, $type ) {
    #
    # setArray
    # setBlob
    # setBoolean
    # setClob
    # setDate
    # setFloat
    # setInt
    # setLimit
    # setNull
    # setOffset
    # setString
    # setTime
    # setTimestamp
    //Soooo much easier with PDO!
    $stmt->bindValue($i, $value);
    
    return $stmt;
    
  }
  
  
  function generateSortOpts( $criteria, $mode="Propel" ) {
    
    $sortopts = $this -> map -> getElementsByTagName("sortopt");
    $sortforce=array();
    $sortactive=array();
    $defsort = array();
    $addend = false;
    $selected = false;
    
    $sortcount=0;
    foreach ($sortopts as $sortopt) {
      $sort["name"] = $sortopt-> getAttribute("name");
      $sort["column"] = $sortopt-> getAttribute("value");
      $sort["default"] = $sortopt-> getAttribute("default");
      $sort["param"] = $sortopt-> getAttribute("param");
      $sort["force"] = $sortopt-> getAttribute("force");
      $sort["direction"] = $sortopt-> getAttribute("direction");
      $sort["selected"] = "false";
      $sort["as_select"] = $sortopt-> getAttribute("as_select");
      
      //Generate The URL Based Sort Criteria which are to be used "as select" criteria
      //i.e. Sort by this criteria, AND only select if the column has this criteria
      if (($mode == "Propel") && (($sort["as_select"] == "true") && ($this -> getVar($this -> sort) == $sort["name"]))) {
          
        $colname = strtoupper($sort["column"]);
        $value = $sort["param"];
        $constant = ($sortopt-> getAttribute("constant") != "") ? ",Criteria::".ucwords($sortopt-> getAttribute("constant")) : "" ;
        
        if ($sortopt -> getAttribute("table") != "") {
          $localtablepeer = ucwords(str_replace("_", " ", $sortopt -> getAttribute("table")))."Peer";
          $localtablepeer = str_replace(" ","",$localtablepeer);
        } else {
          $localtablepeer = $this -> tablepeer;
        }
        
        if ($sort["param"] != '') {
          if ((! is_numeric($sort["param"])) and ($value != "null")) {
            $val = "'".$this -> getVar($sort["param"])."'";
          } else {
            $val = $sort["param"];
          }
          $struct = ("\$criteria->add(".$localtablepeer."::".strtoupper($colname).",".$val.$constant.");");
          $this -> cacheparams = $this -> cacheparams . $localtablepeer."::".strtoupper($colname).",".$val.$constant;
          try {
            eval($struct);
          } catch (Exception $e) {
              echo 'Caught exception: ',  $struct, "\n";
          }
        }
      }
      
      //Generate The "Default" Sort Criteria
      //Make sure to only pick it if it isn't already forced
      if (($sort["default"] == "true") && ($sort["force"] == "false")) {
        ($mode == "Propel") ? $defsort[] = $sort["column"] : $defsort[] = $sort;
      }
      //Pick the "Active" sort if there is one
      if ($this-> greedyVar($this -> sort) == $sort["name"]) {
        $sort["active"] = "true";
        $sortactive[] = $sort;
      } else {
        $sort["active"] = "false";
        if ($sort["force"] == "true") {
          $sortforce[] = $sort;
        }
      }
      
      $this -> sorts[] = $sort;
      $sortcount++;
    }
    
    //Now Add the Sort Criteria
    if (count($this -> sorts) > 0) {
      switch ($mode) {
        
        //Add sort info as propel Criteria
        case "Propel":
        foreach($sortactive as $sortcol) {
          $colname = strtoupper($sortcol["column"]);
          $selected = $sortcol["name"];
          if ($sortcol["direction"] == "ASC") {
            $criteria->addAscendingOrderByColumn($colname);
            $this -> cacheparams = $this -> cacheparams . "AscendingOrderByColumn::".$colname;
          } else {
            $criteria->addDescendingOrderByColumn($colname);
            $this -> cacheparams = $this -> cacheparams . "DescendingOrderByColumn::".$colname;
          }
        }
        if ((count($sortactive) == 0) && (count($defsort) > 0)) {
          foreach($defsort as $sortcol) {
            $colname = strtoupper($sortcol["column"]);
            if ($selected == '') { $selected = $sortcol["name"]; }
            if ($sortcol["direction"] == "ASC") {
              $criteria->addAscendingOrderByColumn($colname);
              $this -> cacheparams = $this -> cacheparams . "AscendingOrderByColumn::".$colname;
            } else {
              $criteria->addDescendingOrderByColumn($colname);
              $this -> cacheparams = $this -> cacheparams . "DescendingOrderByColumn::".$colname;
            }
          }
        }
        if (count($sortforce) > 0) {
          foreach($sortforce as $sortcol) {
            $colname = strtoupper($sortcol["column"]);
            if ($selected == '') { $selected = $sortcol["name"]; }
            if ($sortcol["direction"] == "ASC") {
              $criteria->addAscendingOrderByColumn($colname);
              $this -> cacheparams = $this -> cacheparams . "AscendingOrderByColumn::".$colname;
            } else {
              $criteria->addDescendingOrderByColumn($colname);
              $this -> cacheparams = $this -> cacheparams . "DescendingOrderByColumn::".$colname;
            }
          }
        }
        
        break;
        
        case "MONGO":
          foreach($sortactive as $sortcol) {
            $colname = strtoupper($sortcol["column"]);
            $selected = $sortcol["name"];
            if ($sortcol["direction"] == "ASC") {
              $this -> orders[$colname] = 1;
              $this -> cacheparams = $this -> cacheparams . "AscendingOrderByColumn::".$colname;
            } else {
              $this -> orders[$colname] = -1;
              $this -> cacheparams = $this -> cacheparams . "DescendingOrderByColumn::".$colname;
            }
          }
          if ((count($sortactive) == 0) && (count($defsort) > 0)) {
            foreach($defsort as $sortcol) {
              $colname = strtoupper($sortcol["column"]);
              if ($selected == '') { $selected = $sortcol["name"]; }
              if ($sortcol["direction"] == "ASC") {
                $this -> orders[$colname] = 1;
                $this -> cacheparams = $this -> cacheparams . "AscendingOrderByColumn::".$colname;
              } else {
                $this -> orders[$colname] = -1;
                $this -> cacheparams = $this -> cacheparams . "DescendingOrderByColumn::".$colname;
              }
            }
          }
          if (count($sortforce) > 0) {
            foreach($sortforce as $sortcol) {
              $colname = strtoupper($sortcol["column"]);
              if ($selected == '') { $selected = $sortcol["name"]; }
              if ($sortcol["direction"] == "ASC") {
                $this -> orders[$colname] = 1;
                $this -> cacheparams = $this -> cacheparams . "AscendingOrderByColumn::".$colname;
              } else {
                $this -> orders[$colname] = -1;
                $this -> cacheparams = $this -> cacheparams . "DescendingOrderByColumn::".$colname;
              }
            }
          }
          break;
        //Generate URL using "sortopt" options
        case "SOLR":
          $criteria = $criteria . "&sort=";
          
          $i=0;
          if (count($sortactive) > 0) {
          $addend = true;
          foreach($sortactive as $sortcol) {
            $criteria = $criteria .urlencode($sortcol["column"]." ".$sortcol["direction"]);
            $this -> cacheparams = $this -> cacheparams . $sortcol["direction"] ."::".$sortcol["column"];
            $selected = $sortcol["name"];
            if ($i<count($sortactive) - 1) {
              $criteria = $criteria .urlencode(", ");
              $addend = false;
            }
            $i++;
          }
          }
          //dump($criteria);
          if ((count($sortactive) == 0) && (count($defsort) > 0 )) {
            foreach($defsort as $sortcol) {
              $criteria = $criteria . urlencode($sortcol["column"]." ".$sortcol["direction"]);
              $this -> cacheparams = $this -> cacheparams . $sortcol["direction"] ."::".$sortcol["column"];
              if ($selected == '') {$selected = $sortcol["name"];}
              if ($i<count($defsort) - 1) {
                $criteria = $criteria .urlencode(", ");
                $addend = false;
              }
              $i++;
            }
            $addend = true;
          }
          
          $i=0;
          if (count($sortforce) > 0) {
            foreach($sortforce as $sortcol) {
              if ($addend){
                $criteria = $criteria . urlencode(", ");
              }
              
              $criteria = $criteria . urlencode($sortcol["column"]." ".$sortcol["direction"]);
              $this -> cacheparams = $this -> cacheparams . $sortcol["direction"] ."::".$sortcol["column"];
              if ($selected == '') {$selected = $sortcol["name"];}
              if ($i<count($sortforce) - 1) {
                $criteria = $criteria . urlencode(", ");
                $addend = false;
              }
              $i++;
            }
          }
        	break;
        	
        //Add sort info to the SQL Query
        default:
          $this -> sqlquery = $this -> sqlquery . " ORDER BY ";
          $i=0;
          if (count($sortactive) > 0) {
          $addend = true;
          foreach($sortactive as $sortcol) {
            $this -> sqlquery = $this -> sqlquery .$sortcol["column"]." ".$sortcol["direction"];
            $this -> cacheparams = $this -> cacheparams . $sortcol["direction"] ."::".$sortcol["column"];
            $selected = $sortcol["name"];
            if ($i<count($sortactive) - 1) {
              $this -> sqlquery = $this -> sqlquery .",";
              $addend = false;
            }
            $i++;
          }
          }
          if ((count($sortactive) == 0) && (count($defsort) > 0 )) {
            foreach($defsort as $sortcol) {
              $this -> sqlquery = $this -> sqlquery .$sortcol["column"]." ".$sortcol["direction"];
              $this -> cacheparams = $this -> cacheparams . $sortcol["direction"] ."::".$sortcol["column"];
              if ($selected == '') {$selected = $sortcol["name"];}
              if ($i<count($defsort) - 1) {
                $this -> sqlquery = $this -> sqlquery .",";
                $addend = false;
              }
              $i++;
            }
            $addend = true;
          }
          
          $i=0;
          if (count($sortforce) > 0) {
            foreach($sortforce as $sortcol) {
              if ($addend){
                $this -> sqlquery = $this -> sqlquery . ",";
              }
              
              $this -> sqlquery = $this -> sqlquery .$sortcol["column"]." ".$sortcol["direction"];
              $this -> cacheparams = $this -> cacheparams . $sortcol["direction"] ."::".$sortcol["column"];
              if ($selected == '') {$selected = $sortcol["name"];}
              if ($i<count($sortforce) - 1) {
                $this -> sqlquery = $this -> sqlquery .",";
                $addend = false;
              }
              $i++;
            }
          }
          break;
      }
      
      //Add the sorts to the Resultset
      $sr=0;
      foreach($this -> sorts as $asort) {
        if ($asort["name"] == $selected) {
          $this -> sorts[$sr]["selected"] = "true";
        }
        $sr++;
      }
    //If no sorts apply, run the default sorts
    } else {
    
      switch ($mode) {
       case "Propel":
          $orders = $this -> map -> query("//criterion/*");
          if ($orders) {
          for ( $i=0;$i<$orders -> length; $i++) {
            switch ($orders -> item($i) -> nodeName) {
              case "ascorderby":
                $colname = strtoupper($orders -> item($i) -> getAttribute("column"));
                $random = strtoupper($orders -> item($i) -> getAttribute("random"));
                if ($random == "TRUE") {
                  $criteria->addAscendingOrderByColumn('rand()');
                  $this -> cacheparams = $this -> cacheparams . "AscendingOrderByColumn::rand()";
                  $criteria->setLimit(1);
                  //If Randomizing results, make sure we skip any "limits" the query might otherwise have
                  $limit = false;
                } else {
                  $criteria->addAscendingOrderByColumn($colname);
                  $this -> cacheparams = $this -> cacheparams . "AscendingOrderByColumn::".$colname;
                }
                break;
              case "descorderby":
                $colname = strtoupper($orders -> item($i) -> getAttribute("column"));
                $criteria->addDescendingOrderByColumn($colname);
                $this -> cacheparams = $this -> cacheparams . "DescendingOrderByColumn::".$colname;
                break;
              }
            }
          }
          break;
        
        case "MONGO":
          $orders = $this -> map -> query("//criterion/*");
          if ($orders) {
          for ( $i=0;$i<$orders -> length; $i++) {
            switch ($orders -> item($i) -> nodeName) {
              case "ascorderby":
                $this -> orders[$orders -> item($i) -> getAttribute("column")] = 1;
                $this -> cacheparams = $this -> cacheparams . "AscendingOrderByColumn::".$orders -> item($i) -> getAttribute("column");
                break;
              case "descorderby":
                $this -> orders[$orders -> item($i) -> getAttribute("column")] = -1;
                $this -> cacheparams = $this -> cacheparams . "DescendingOrderByColumn::".$orders -> item($i) -> getAttribute("column");
                break;
              }
            }
          }
          break;
          
        case "SOLR":
          $orders = $this -> map -> query("//criterion/*");
          if ($orders) {
          $criteria = $criteria . "&sort=";
          
          for ( $i=0;$i<$orders -> length; $i++) {
            $colname = $orders -> item($i) -> getAttribute("column");
            $random = strtoupper($orders -> item($i) -> getAttribute("random"));
            switch ($orders -> item($i) -> nodeName) {
              case "ascorderby":
                if ($random == "TRUE") {
                  $var = "random_". mt_rand();
                  $criteria = $criteria .urlencode($var." desc,");
                  $this -> cacheparams = $this -> cacheparams . "random::random";
                  //If Randomizing results, make sure we skip any "limits" the query might otherwise have
                  $limit = false;
                } else {
                  $criteria = $criteria .urlencode($colname." asc,");
                  $this -> cacheparams = $this -> cacheparams . "asc::".$sortcol["column"];
                }
                break;
              case "descorderby":
                  $criteria = $criteria .urlencode($colname." desc,");
                  $this -> cacheparams = $this -> cacheparams . "desc::".$sortcol["column"];
                break;
              }
            }
          }
          break;
           case "SQL":
            if (preg_match("/order by/i",$this -> sqlquery)) {
              break;
            }
            $orders = $this -> map -> query("//criterion/*");
            if ($orders) {
              for ( $i=0;$i<$orders -> length; $i++) {
                $colname = $orders -> item($i) -> getAttribute("column");
                $random = strtoupper($orders -> item($i) -> getAttribute("random"));
                switch ($orders -> item($i) -> nodeName) {
                  case "ascorderby":
                    if ($random == "TRUE") {
                      $sqlquery = $sqlquery . " RAND() ";
                      $this -> cacheparams = $this -> cacheparams . "asc::".$colname;
                      //If Randomizing results, make sure we skip any "limits" the query might otherwise have
                      $limit = false;
                    } else {
                      $sqlquery = $sqlquery . $colname . " ASC ";
                      $this -> cacheparams = $this -> cacheparams . "asc::".$colname;
                    }
                    $addme = true;
                    break;
                  case "descorderby":
                    $sqlquery = $sqlquery . $colname . " DESC ";
                    $this -> cacheparams = $this -> cacheparams . "desc::".$colname;
                    $addme = true;
                    break;
                  }
                }
                if ($addme) {
                  $this -> sqlquery = $this -> sqlquery . " ORDER BY " . $sqlquery;
                }
              }
            break;
      }
    }
    
    $distinctopts = $this -> map -> getElementsByTagName("distinct");
    
    foreach($distinctopts as $dist) {
      $this -> distincts[] = $dist -> getAttribute("value");
    }
    
    switch ($mode) {
      case "Propel":
      case "MONGO":
      case "SOLR":
      case "SQL":
        return $criteria;
        break;
    }
      
    
  }
  
  function generateCriterion( $critters, $mode="Propel", $criteria=null ) {
    
    if ($mode == "SQL") {
      $struct = $criteria;
      $criteria = null;
    } else {
      $struct = null;
    }
    $i=1;
    $string = "";
    
    foreach ($critters as $critter) {
        $colname = ($mode == "Propel") ? strtoupper($critter->getAttribute("column")) : $critter->getAttribute("column");
        $scope = ($mode == "Propel") ? strtoupper($critter->getAttribute("scope")) : $critter->getAttribute("scope");
        $value = $critter->getAttribute("value");
        $type = $critter->getAttribute("type");
        $size = $critter->getAttribute("size");
        $key = $critter->getAttribute("key");
        
        $constant = (($mode == "Propel") && ($critter->getAttribute("constant") != "")) ? ",Criteria::".ucwords($critter->getAttribute("constant")) : $critter->getAttribute("constant") ;
        
        if ($value == "") {
          continue;
        }
        
        if ($critter -> getAttribute("table") != "") {
          $localtablepeer = ucwords(str_replace("_", " ", $critter -> getAttribute("table")))."Peer";
          $localtablepeer = str_replace(" ","",$localtablepeer);
        } else {
          $localtablepeer = $this -> tablepeer;
        }
        $doEval = true;
        
        $val = null;
        switch($scope) {
          case "POST":
           if (($mode == "Propel") && ((! is_numeric($this -> postVar($value))) and ($value != "null"))) {
              $val = "'".$this ->  postVar($value)."'";
            } else {
              $val = $this ->  postVar($value);
            }
            $this -> addRequestParam( $value );
            $struct = $this -> setCriteria( $localtablepeer, $colname, $val, $type, $constant, $mode, $critter, $i, $struct, $criteria );
          break;
          case "GET":
            if (($mode == "Propel") &&  ((! is_numeric($this -> getVar($value))) and ($value != "null"))) {
              $val = "'".$this -> getVar($value)."'";
            } else {
              $val = $this -> getVar($value);
            }
            $this -> addRequestParam( $value );
            $struct = $this -> setCriteria( $localtablepeer, $colname, $val, $type, $constant, $mode, $critter, $i, $struct, $criteria );
          break;
          case "SESSION":
             if (($mode == "Propel") &&  ((! is_numeric($this -> sessionVar($value))) and ($value != "null"))) {
                $val = "'".$this -> sessionVar($value)."'";
             } else {
                $val = $this -> sessionVar($value);
             }
             $this -> addRequestParam( $value );
             $struct = $this -> setCriteria( $localtablepeer, $colname, $val, $type, $constant, $mode, $critter, $i, $struct, $criteria );
          break;
          case "PROCESS":
            if (($type != "daterange") && ($type != "numericrange")) {
            if (($mode == "Propel") && (! is_array($this -> processVar($value))) && ((! is_numeric($this -> processVar($value))) and ($value != "null"))) {
              $val = "'".$this -> processVar($value)."'";
            } else {
              $val = $this -> processVar($value);
            }}
						if (($type == "daterange") || ($type == "numericrange")) {
            	$struct = $this -> setCriteria( $localtablepeer, $colname, $this -> processVar($value), $type, $constant, $mode, $critter, $i, $struct, $criteria );
              continue;
            }
            $this -> addRequestParam( $value );
            
            $struct = $this -> setCriteria( $localtablepeer, $colname, $val, $type, $constant, $mode, $critter, $i, $struct, $criteria );
          break;
          case "SYSTEM":
            switch($value) {
              case "floor_now()":
                if ( $this -> structype == 'solr') {
									$val = formatDate(null,"TSFloor");
									$val = "\"".formatDate($val,"W3XMLIN")."\"";
								} elseif ( $this -> structype == 'sql') {
                  $val = formatDate(null,"TSFloor");
                } else {
									$val = "'".formatDate(null,"TSFloor")."'";
                }
                break;
              case "ceiling_now()":
                if ( $this -> structype == 'solr') {
                	$val =  formatDate(null,"TSCeiling");
                	$val = "\"".formatDate($val,"W3XMLIN")."\"";
								} elseif ( $this -> structype == 'sql') {
                  $val =  formatDate(null,"TSCeiling");
                } else {
									$val =  "'".formatDate(null,"TSCeiling")."'";
                }
                break;
              case "now()":
                if ( $this -> structype == 'solr') {
                	$val = formatDate(null,"TS");
                	$val = "\"".formatDate($val,"W3XMLIN")."\"";
								} elseif ( $this -> structype == 'sql') {
                  $val = formatDate(null,"TS");
                } else {
									$val = "'".formatDate(null,"TS")."'";
                }
                break;
              case "today()":
                if ( $this -> structype == 'solr') {
                	$val = formatDate(null,"TSRound");
                	$val = "\"".formatDate($val,"W3XMLIN")."\"";
								} elseif ( $this -> structype == 'sql') {
                  $val = formatDate(null,"TSRound");
                } else {
									$val = "'".formatDate(null,"TSRound")."'";
                }
                break;
              case "datediff":
                if ($key == "now()") {
                  $startdate = now();
                } elseif ($key == "floor_now()") {
                  $startdate = formatDate(null,"TSFloor");
                } elseif ($key == "ceiling_now()") {
                  $startdate = formatDate(null,"TSCeiling");
                } else {
                  $startdate = $key;
                }
                $temp=explode("%",$size);
                $val = dateAdd($startdate,$temp[0],$temp[1]);
                break;
              default:
                eval("\$val = ".$value.";");
              	break;
            }
            $this -> addRequestParam( $value );
            $struct = $this -> setCriteria( $localtablepeer, $colname, $val, $type, $constant, $mode, $critter, $i, $struct, $criteria );
          break;
          default:
						if (($mode == "Propel") && ((! is_numeric($value)) and ($value != "null"))) {
              $val = "'".$value."'";
            } else {
              $val = $value;
            }
            $struct = $this -> setCriteria( $localtablepeer, $colname, $val, $type, $constant, $mode, $critter, $i, $struct, $criteria );
            break;
        }
        
        if ($doEval)
          $this -> runCriteria( $struct, $mode, $criteria );
          
        $i++;
      }

    switch ($mode) {
      case "SQL":
        return $struct;
        break;
      case "SOLR":
        return $struct;
        break;
      case "MONGO":
        return $struct;
        break;
    }
  }
  
  function addRequestParam( $name ) {
    if (! in_array($name,$this -> requestParams)) {
      $this -> requestParams[] = $name;
      
     }
  }
  
  function setCriteria( $localtablepeer, $colname, $val, $type, $constant, $mode, $criterianode, $i, $struct, $criteria=null ) {
    
    switch ($mode) {
    case "MONGO":
      switch ($type) {
        case "rangeinclusive":
          $opts=explode("|",$val);
          $struct[$colname] = array("\$gte" => $opts[0], "\$lte" => $opts[1]);
          break;
        case "range":
          $opts=explode("|",$val);
          $struct[$colname] = array("\$gt" => $opts[0], "\$lt" => $opts[1]);
          break;
        case "gte":
          $struct[$colname] = array("\$gte" => $val);
          break;
        case "lte":
          $struct[$colname] = array("\$lte" => $val);
          break;
        case "gt":
          $struct[$colname] = array("\$gt" => $val);
          break;
        case "lt":
          $struct[$colname] = array("\$lt" => $val);
          break;
        case "ne":
          $struct[$colname] = array("\$nt" => $val);
          break;
        case "in":
          $struct[$colname] = array("\$in" => explode(",",$val));
          break;
        case "nin":
          $struct[$colname] = array("\$nin" => explode(",",$val));
          break;
        case "mod":
          $struct[$colname] = array("\$mod" => explode(",",$val));
          break;
        case "all":
          $struct[$colname] = array("\$all" => explode(",",$val));
          break;
        case "size":
          $struct[$colname] = array("\$size" => $val);
          break;
        case "exists":
          $struct[$colname] = array("\$exists" => true);
          break;
        case "notexists":
          $struct[$colname] = array("\$exists" => false);
          break;
        case "re":
          $struct[$colname] = "/".$val."/";
          break;
        case "rei":
          $struct[$colname] = "/".$val."/i";
          break;
        default:
          if (is_numeric($val)) {
            if (is_float($val)) {
              $struct[$colname] = (float) $val;
            } else {
              $struct[$colname] = (int) $val;
            }
          } else {
            $struct[$colname] = $val;
          }
          break;
      }
      return $struct;
      break;
    case "Propel":
      if ($type == "daterange") {
       $dates = explode("|",str_replace("'","",$val));
       eval("\$cton1 = \$criteria->getNewCriterion(".$localtablepeer."::".strtoupper($colname).",'".$dates[0]."',Criteria::GREATER_EQUAL);");
       eval("\$cton2 = \$criteria->getNewCriterion(".$localtablepeer."::".strtoupper($colname).",'".$dates[1]."',Criteria::LESS_EQUAL);");
       $cton1->addAnd($cton2);
       $criteria->add($cton1);
        return;
      }elseif ($type == "numericrange") {
			 $numbers = explode("|",str_replace("'","",$val));
       eval("\$cton1 = \$criteria->getNewCriterion(".$localtablepeer."::".strtoupper($colname).",".$numbers[0].",Criteria::GREATER_EQUAL);");
       eval("\$cton2 = \$criteria->getNewCriterion(".$localtablepeer."::".strtoupper($colname).",".$numbers[1].",Criteria::LESS_EQUAL);");
       $cton1->addAnd($cton2);
       $criteria->add($cton1);
       return;
      } elseif ($type == "OR") {
        $group = $criterianode -> getAttribute("key");
        $last = $criterianode -> getAttribute("last");
        if (($group != '') && (! $this -> $group)) {
        	eval("\$this -> \$group = \$criteria->getNewCriterion(".$localtablepeer."::".strtoupper($colname).",".$val.$constant.");");
        } elseif ($group != '') {
        	eval("\$colname = \$criteria->getNewCriterion(".$localtablepeer."::".strtoupper($colname).",".$val.$constant.");");
          $this ->$group->addOr($colname);
        }
        if ($last == "true") {
          $criteria->add($this -> $group);
        }
        return $struct;
        
      }
      if ($colname == "OBJECT_NAME") {
        echo "Error in Query Parameters for ".$this -> conf;
        die();
      }
      if ((preg_match("/::IN/",$constant)) && (! is_array($val))) {
        $val = str_replace("'","",$val);
        $val="array".$val;
      } elseif (preg_match("/::IN/",$constant)) {
        $str="array(";
        foreach ($val as $item) {
          $str.="'".$item."',";
        }
        $str.=")";
        $val = $str;
      }
      if ($val) {
        $struct = ("\$criteria->add(".$localtablepeer."::".strtoupper($colname).",".$val.$constant.");");
        //Add the criteria tuple to the cacheparams;
        $this -> cacheparams = $this -> cacheparams . $localtablepeer."::".strtoupper($colname).",".$val.$constant;
      } else {
        $struct = ("\$criteria->add(".$localtablepeer."::".strtoupper($colname).",0);");
        $this -> cacheparams = $this -> cacheparams . $localtablepeer."::".strtoupper($colname).",0";
      }
      return $struct;
      break;
    case "SQL":
      $struct = $this -> setStatement( $struct, $i, $val, $type ) ;
      $this -> cacheparams = $this -> cacheparams . $i.":". $val.":". $type;
      return $struct;
      break;
    case "SOLR":
      if ($type == '') {
        $type = ",";
      }
      
      if ($constant == "daterange") {
        $dates = explode("|",str_replace("'","",$val));
        $val = "[".formatDate($dates[0],"W3XMLIN")." TO ".formatDate($dates[1],"W3XMLIN")."]";
        $constant = "upper";
      }
      
      $constant = preg_replace("/(pregroup[_]?)/","",$constant,1,$predicate);
      $constant = preg_replace("/([_]?postgroup)/","",$constant,1,$antecedent);
      $pred == "";
      $ant == "";
      if ($predicate == 1) {
        $pred = " (";
      }
      if ($antecedent == 1) {
        $ant = ") ";
      }
      
      //Is this term quoted?
      $qouvadis = "";
      if (preg_match("/quoted/",$constant)) {
        //$struct = $struct . $pred . $colname.": \"".$val."\" ". $ant . $type." ";
        $qouvadis = "\"";
      }
      
      $booster="";
      $boost = $criterianode -> getAttribute("boost");
      if ($boost){
        $this -> boost .= $val."^".$boost.",";
        $booster="^".$boost;
      }
      
      //Determine Case Sensitivity
      //Note:: Case Insensitivity in SOLR "CAN" be had using Tokenizers and Analyzers, but in case not...
      //lowercase is enforced by default
      //use "upper" to convert to UCASE
      if (preg_match("/native/",$constant)) {
        $struct = $struct . $pred . $colname.": ".$qouvadis.$val.$qouvadis.$booster." ". $ant . $type." ";
      } elseif (preg_match("/insensitive/",$constant)) {
        $struct = $struct . $pred . "(".$colname.": ".$qouvadis.strtolower($val).$qouvadis.$booster." ". " OR " .$colname.": ".$qouvadis.$val.$qouvadis. ")" . " ". $ant . $type . " ";
      } elseif (preg_match("/upper/",$constant)) {
        $struct = $struct . $pred . $colname.": ".$qouvadis.strtoupper($val).$qouvadis.$booster." ". $ant . $type . " ";
      } elseif (preg_match("/ucwords/",$constant)) {
        $struct = $struct . $pred . $colname.": ".$qouvadis.ucwords($val).$qouvadis.$booster." ". $ant . $type . " ";
      } else {
        $struct = $struct . $pred . $colname.": ".$qouvadis.strtolower($val).$qouvadis.$booster." ". $ant . $type." ";
      }
      
      $this -> cacheparams .= $struct;
      return $struct;
      break;
    }
    
  }
  
  function runCriteria( $struct, $mode, $criteria=null ) {
    switch ($mode) {
    case "Propel":
      try {
        eval($struct);
      } catch (Exception $e) {
          echo 'Caught exception: ',  $struct, "\n";
      }
      break;
    case "SQL":
      break;
    case "SOLR":
      break;
    }
  }
  
  function getPage() {
    if (($this -> format_utility) && ($this -> format_utility -> page > 0)) {
      $page = $this -> format_utility -> page;
    } else {
      if ($this -> pagename != '') {
				$page = ($this -> ifVar($this -> pagename)) ? $this -> greedyVar($this -> pagename) : $this -> map -> getPathAttribute("//page","value");
    	} else {
				$page = -1;
			}
		}
    return $page;
  }
  
  function getOffset() {
    if (($this -> format_utility) && ($this -> format_utility -> offset > 0)) {
      $offset = $this -> format_utility -> offset;
    } else {
    	if ($this -> offsetname != '') {
      	$offset = ($this -> ifVar($this -> offsetname)) ? $this -> greedyVar($this -> offsetname) : $this -> map -> getPathAttribute("//offset","value");
    	} else {
				$offset = -1;
			}
		}
    return $offset;
  }
  
  function getRpp() {
    if (($this -> format_utility) && ($this -> format_utility -> rpp > 0)) {
      $this -> rpp = $this -> format_utility -> rpp;
    } elseif (($this -> format_utility) && ($this -> format_utility -> rpp == "all")) {
      $this -> rpp = 0;
    } else {
      $this -> rpp = $this -> map -> getPathAttribute("//recordssperpage","value");
    }
    
    $total_limit = $this -> map -> getPathAttribute("//criterion","limit");
    if (($total_limit) &&($total_limit > 0)) {
      $this -> total_limit = $total_limit;
    }
  }
  
  function getTitle() {
    if (($this -> format_utility) && ($this -> format_utility -> title != "")) {
      $this -> titlename = $this -> format_utility -> title;
    } else {
      $this -> titlename = $this -> map -> getPathAttribute("//map","title");
    }
  }
  
  function getName() {
    if (($this -> format_utility) && ($this -> format_utility -> name != "")) {
      $this -> resultname = $this -> format_utility -> name;
    } else {
      $this -> resultname = $this -> map -> getPathAttribute("//map","result");
    }
  }
  
  function initConf( $conf ) {
    if (! $this -> map) {
      if ($conf instanceof XML) {
        $this -> map = $conf;
      } else {
        $this -> map = new XML();
        $this -> map -> loadXML($conf);
      }
    }
    
    $this -> pagename = $this -> map -> getPathAttribute("//page","var");
    $this -> offsetname = $this -> map -> getPathAttribute("//offset","var");
    $this -> nullCallback = $this -> map -> getPathAttribute("//map","callback");
    $this -> postCallback = $this -> map -> getPathAttribute("//map","postback");
    $this -> baseref = $this -> map -> getPathAttribute("//maplinks","default");
    
    if (($this -> doinit) && ($this -> format_utility) && (method_exists($this -> format_utility,"getConf"))) {
      $this -> map = $this -> format_utility -> getConf( $this -> map );
      $this -> doinit = false;
    }
    
    $this -> docache = ($this -> map -> getPathAttribute("//map","docache") != "") ? $this -> map -> getPathAttribute("//map","docache") : "skip";
    if (! $this -> docache) {
      $this -> isdebug = true;
    }
    if (($this -> format_utility) && (method_exists($this -> format_utility,"recordQuery")) && ($this -> map -> getPathAttribute("//map","record_query") == "true")) {
      $this -> record_query = true;
    }
    
    if (($this -> format_utility) &&  ($this -> format_utility -> record_query_result_id != "")) {
      $this -> record_query_result_id = $this -> format_utility -> record_query_result_id;
    }
  }
  
  function initCache( $conf ) {
    if ($this -> docache) {
      $this -> cacheparams = "";
    }
  }
  
  function getCacheParams() {
    return $this ->cacheparams;
  }
  
  function readCache( $debug=false ) {
    
    if (! sfConfig::get("app_query_cache"))
      return false;
    
    if ($this -> is_record_query)
      return false;
      
    $thefile = $this -> cachefile = hash("md4",$this ->getCacheParams());
    
    $memcache = new Memcached;
    $memcache->addServer(sfConfig::get("app_mongo_query"), 11211);
    $res = $memcache->get( $thefile );
    
    if (($this -> docache == "false") && ($res)) {
      //if ($debug) die("REMOVIN'");
      $this -> logItem("WTVR Data","Deleting Query Cache from  \"".$thefile."\"");
      $memcache -> delete( $thefile );
      return false;
    } elseif (($this -> docache != "skip") && ($res)) {
      //if ($debug) die("READIN");
      //If it isn't false (above) or true (infinite)
      //Check the TTL of the file
      
      $this -> getCacheFile( $res ); 
      return true;
      
    } else {
      $this -> logItem("WTVR Data","No Query Cache from  \"".$thefile."\"");
      return false;
    }
    
  }
  
  function getCacheFile( $res ) {
    
    $result =$res["rs"];
    $this -> res = unserialize($result);
    return true;
    
  }
  
  
  function writeCache( $rs ) {
    
    //if ($debug) die("CLEARIN");
    $cachetime=explode(":",$this -> docache);
    switch ($cachetime[1]) {
      case "WEEK":
        $thecurrenttime=$cachetime[0]*7*86400;
      	break;
      case "DAY":
        $thecurrenttime=$cachetime[0]*86400;
      	break;
      case "HOUR":
        $thecurrenttime=$cachetime[0]*3600;
      	break;
      case "MINUTE":
        $thecurrenttime=$cachetime[0]*60;
      	break;
      default:
        $thecurrenttime=86400;
        break;
    }
        
    $this -> cachefile = hash("md4",$this ->getCacheParams());
    
    $memcache = new Memcached();
    $memcache->setOption(Memcached::OPT_COMPRESSION, false);
    $memcache->addServer(sfConfig::get("app_mongo_query"), 11211);
    $memcache->set( $this -> cachefile, array("rs" => serialize($rs), "date" => now()), $thecurrenttime );
    
    $this -> logItem("WTVR Data","Create Query Cache at \"".$this -> cachepath.$this -> cachedir.$this -> cachefile."\"");
  }
  
}

?>
