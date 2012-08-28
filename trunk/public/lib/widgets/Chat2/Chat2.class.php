<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Chat2_crud.php';
  
  class Chat2_PageWidget extends Widget_PageWidget {
	
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
	   
	  if ($this -> as_service) {
      $this -> doGet();
    }
    
    $user = $this -> context -> getUser();
    $this -> widget_vars["file"] = "default";
    $this -> widget_vars["room"] = "Default";
    $this -> widget_vars["name"] = $user -> getAttribute("user_username");
    return $this -> widget_vars;
     
  }

  function doGet(){
    
    switch ($this -> getVar("id")) {
      case "process":
        
        $function = htmlentities($this -> postVar("function"), ENT_QUOTES);
      	$file = getFilename( $this -> postVar("file") );
        $log = array();
        
        switch ($function) {
          case ('getState'):
          	 if (file_exists($file)) {
                 $lines = file($file);
          	 }
             $log['state'] = count($lines);
                
          	 break;	
          case ('send'):
            $nickname = htmlentities($this -> postVar("nickname"), ENT_QUOTES);
            $patterns = array("/:\)/", "/:D/", "/:p/", "/:P/", "/:\(/");
            $replacements = array("<img src='/smiles/smile.gif'/>", "<img src='/smiles/bigsmile.png'/>", "<img src='/smiles/tongue.png'/>", "<img src='/smiles/tongue.png'/>", "<img src='/smiles/sad.png'/>");
            $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
            $blankexp = "/^\n/";
            $message = htmlentities($this -> postVar('message'), ENT_QUOTES);
            
             if (!preg_match($blankexp, $message)) {
                	
            	 if (preg_match($reg_exUrl, $message, $url)) {
               			$message = preg_replace($reg_exUrl, '<a href="'.$url[0].'" target="_blank">'.$url[0].'</a>', $message);
            	 } 
            	 $message = preg_replace($patterns, $replacements, $message);
               fwrite(fopen($file, 'a'), "<span>". $nickname . "</span>" . $message = str_replace("\n", " ", $message) . "\n"); 
             
             }
          	 break;
          }
          
          echo json_encode($log);
          die();
        break;
      case "update":
        $state = htmlentities($this -> getVar("state"), ENT_QUOTES);
        $file = $file = getFilename( $this -> getVar("file") );
        
        $finish = time() + 50;
        $count = getlines(getfile($file));
        
        while ($count <= $state) {
        
            $now = time();
            usleep(10000);
            
            if ($now <= $finish) {
                $count = getlines(getfile($file));
            } else {
                break;	
            }  
             
        }
        
        if ($state == $count) {
        
            $log['state'] = $state;
            $log['t'] = "continue";
            
        } else {
        
            $text= array();
            $log['state'] = $state + getlines(getfile($file)) - $state;
            $thefile = getfile($file);
            if (! is_null($thefile)) {
            foreach (getfile($file) as $line_num => $line) {
                if ($line_num >= $state) {
                    $text[] =  $line = str_replace("\n", "", $line);
                }
        
                $log['text'] = $text; 
            }
          }
        }
        
        echo json_encode($log);	
        die();
        
        break;
      case "userlist":
        
        //Start Array
        $data = array();
        // Get data to work with
    		$current = $this -> getVar('current');
    		$room = $this -> getVar('room');
    		$username = $this -> getVar('username');
    		$now = time();
        
        // INSERT your data (if is not already there)
        $findUser = $this -> propelQuery("SELECT count(*) FROM chat_users_rooms WHERE username = '".$username."' AND room ='".$room."'");
    		while ($row = $findUser->fetch()) {
          $findUserCount = $row[0];
        }
        if($findUserCount == 0) {
          $this -> propelInsert("INSERT INTO chat_users_rooms (id, username, room, mod_time) VALUES ( NULL , '".$username."', '".$room."', '".$now."')");
    		}
    			
    		// INSERT your data (if is not already there)
        $findUser2 = $this -> propelQuery( "SELECT count(*) FROM chat_users WHERE username = '".$username."'");
    		while ($row = $findUser2->fetch()) {
          $findUser2Count = $row[0];
        }
        
  			if($findUser2Count === 0) {
					$this -> propelInsert("INSERT INTO chat_users (id, username, status, time_mod) VALUES (NULL , '".$username."', '1', '".$now."')");
					$data['check'] = 'true';
				}			
    		
        $finish = time() + 7;
    		$getRoomUsers = $this -> propelQuery("SELECT count(*) FROM chat_users_rooms WHERE room = '".$room."'");
    		while ($row = $getRoomUsers->fetch()) {
          $check = $row[0];
        }
            	
    	  while(true) {
    			
          usleep(10000);
    			$this -> propelInsert("UPDATE chat_users SET time_mod = '".$now."' WHERE username = '".$username."'");
    			//$olduser = time() - 5;
    			//$eraseuser = time() - 30;
    			$olduser = time() - 50;
    			$eraseuser = time() - 100;
    			
    			$this -> propelInsert("DELETE FROM chat_users_rooms WHERE mod_time <  '".$olduser."'");
    			$this -> propelInsert("DELETE FROM chat_users WHERE time_mod <  '".$eraseuser."'");
    			$getRoomUsers = $this -> propelQuery("SELECT id, username, room, mod_time FROM chat_users_rooms WHERE room = '".$room."'");
      		$users = $getRoomUsers->fetchAll();
          $check = count($users);
          
          $now = time();
    			if($now <= $finish) {
    				$this -> propelInsert("UPDATE chat_users_rooms SET mod_time = '".$now."' WHERE username = '".$username."' AND room ='".$room."'  LIMIT 1");
    				if($check != $current){
    				  break;
    				}
    			} else {
    			 break;	
    		  }
        }		 		
        
        // Get People in chat
    		if($check != $current) {
    			$data['numOfUsers'] = $check;
    			// Get the user list (Finally!!!)
    			$data['userlist'] = array();
    			foreach ($users as $user) {
    				$data['userlist'][] = $user['username'];
    			}
    			$data['userlist'] = array_reverse($data['userlist']);
    		} else {
    			$data['numOfUsers'] = $current;	
    			foreach ($users as $user) {
    				$data['userlist'][] = $user['username'];
    			}
    			$data['userlist'] = array_reverse($data['userlist']);
    		}
    		echo json_encode($data);
    		die();
        break;
      }
    
  }

}

function getfile($f) {
  if (file_exists($f)) {
    $lines = file($f);
  }	
  return $lines; 
}

function getlines($fl){
  return count($fl);	
}

function getFilename( $file ) {
  return sfConfig::get("sf_data_dir")."/chat/".htmlentities($file, ENT_QUOTES)."_".formatDate(null,"MDY-").".txt";
}
?>
