<?php

class TheaterChat extends Widget_PageWidget {
  
  var $film;
  var $chatseats;
  
  function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> chatseats = 1000;
    parent::__construct( $context );
    
  }
  
  function getHistoryInstance() {
    $ports = getRandomServicePort();
    
    $this -> widget_vars["chat_instance_host"] = $ports[0];
    $this -> widget_vars["chat_instance_port_base"] = $ports[1];
    return $this -> widget_vars;
        
  }
  
  function getChatInstance() {
		if ($this -> cookieVar("ci_".$this -> getVar("op"))!= '') {
			$this -> setsessionVar("ci_".$this -> getVar("op"), $this -> cookieVar("ci_".$this -> getVar("op")));
		} 

		
		if (! $this -> sessionVar("ci_".$this -> getVar("op"))) {
      
      //Get the chat instance for this screening
      //The code below is just a safety mechanism
      //And can be removed after the service is working (8/8/2011)
      $doins = true;
      
			//if ($this -> film["data"][0]["screening_total_seats"] < 10) {
      //  $this -> film["data"][0]["screening_total_seats"] = 100;
      //}
      
      $csql = "select count(chat_usage_id) 
                from chat_usage
								where fk_screening_unique_key = '".$this->getVar("op")."'";
      $res = $this -> propelQuery( $csql );
      
      //There is an open Instance, so use that
      if ($res -> rowCount() <= $this -> chatseats){
        $rsql = "select chat_instance_id, 
                chat_instance_key,
                chat_instance_host,
                chat_instance_port,
                chat_instance_name
                from chat_instance 
                where fk_screening_key = '".$this->getVar("op")."'
                and chat_instance_count <= ".$this -> chatseats."
                order by chat_instance_id
                limit 1";
				
				$rres = $this -> propelQuery( $rsql );
				if ($rres -> rowCount() > 0){
				$doins = false;
				while($row = $rres -> fetch(PDO::FETCH_NUM)){
          if (($this -> sessionVar("ci_".$this -> getVar("op")) != $row[1]) || (! $this -> sessionVar("ci_".$this -> getVar("op")))) {
            $csql = "update chat_instance 
                    set chat_instance_count = chat_instance_count + 1
                    where chat_instance_id = ".$row[0].";";
            $this -> propelInsert($csql);
          }
          $this -> widget_vars["chat_instance_key"] = $row[1];
          $this -> widget_vars["chat_instance_host"] = $row[2];
          $this -> widget_vars["chat_instance_port_base"] = $row[3];
        }}
      }
      
      if ($doins) {
        //This is a simple "Randomizer" for Service Port Activity
        //We'll want to make this more specific later
        $ports = getRandomServicePort();
        $name = 0;
        $msql = "select max(chat_instance_name)
                from chat_instance 
                where fk_screening_key = ?";
        $mres = $this -> propelArgs( $msql, array($this->getVar("op") ));
        while($mrow = $mres -> fetch(PDO::FETCH_NUM)){
          $name = $mrow[0];
        }
        
        $csql = "insert into chat_instance 
                (fk_screening_key,
                chat_instance_key,
                chat_instance_host, 
                chat_instance_port,
                chat_instance_count,
                chat_instance_name)
                values
                (?,
                ?,
                ?,
                ?,
                1,
                ?)";
        $this -> widget_vars["chat_instance_key"] = setUserOrderTicket();
        $this -> widget_vars["chat_instance_host"] = $ports[0];
        $this -> widget_vars["chat_instance_port_base"] = $ports[1];
        $res = $this -> propelArgs( $csql, array($this->getVar("op"),$this -> widget_vars["chat_instance_key"],$this -> widget_vars["chat_instance_host"],$this -> widget_vars["chat_instance_port_base"],$name+1) );
      }
      
      //Record User Attendance
      $cu = new ChatUsage();
      $cu -> setChatUsageDateAdded(now());
      $cu -> setFkUserId($this -> sessionVar("user_id"));
      $cu -> setFkChatInstanceKey($this -> widget_vars["chat_instance_key"]);
      $cu -> setFkScreeningUniqueKey($this->getVar("op"));
      $cu -> setChatUsageBrowser($_SERVER["HTTP_USER_AGENT"]);
      $cu -> setChatUsageReferer($_SERVER["HTTP_REFERER"]);
      $cu -> setChatUsageRemoteAddr($_SERVER["REMOTE_ADDR"]);
      $cu -> setChatUsageRemoteAddrComputed(REMOTE_ADDR());
      $cu -> save();
      $this -> widget_vars["cud"] = $cu -> getChatUsageId();
      if (! $this -> sessionVar("ci_".$this -> getVar("op"))) {
				$this -> setSessionVar("ci_".$this -> getVar("op"),$this -> widget_vars["chat_instance_key"]);
			}
      //$this -> setSessionVar("ci_".$this -> getVar("op"),$this -> widget_vars["chat_instance_key"]."|".$this -> widget_vars["chat_instance_host"]."|".$this -> widget_vars["chat_instance_port_base"]);
    
    } else {
      // dump($this -> sessionVar("ci_".$this -> getVar("op")));
			$rsql = "select chat_instance_id, 
                chat_instance_key,
                chat_instance_host,
                chat_instance_port,
                chat_instance_name
                from chat_instance 
                where fk_screening_key = '".$this->getVar("op")."'
                and chat_instance_key = '".$this -> sessionVar("ci_".$this -> getVar("op"))."'
                order by chat_instance_id
                limit 1";
				
			$rres = $this -> propelQuery( $rsql );
			if ($rres -> rowCount() > 0){
			$doins = false;
			while($row = $rres -> fetch(PDO::FETCH_NUM)){
        $this -> widget_vars["chat_instance_key"] = $row[1];
        $this -> widget_vars["chat_instance_host"] = $row[2];
        $this -> widget_vars["chat_instance_port_base"] = $row[3];
      }}
      setcookie('ci_'.$this -> getVar("op"),$this -> widget_vars["chat_instance_key"], time() + 1209600, "/",".constellation.tv");
    
    }
    
    return $this -> widget_vars;
  }
  
}
?>