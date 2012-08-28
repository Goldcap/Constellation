<?
/**
 * runs CLI commands and returns output if applicable.  also returns unix status, where 0 = successful exec and non-0 = failure.
 *
 * USAGE NOTES:
 *  - instantiate object (...duh)
 *  - to ignore output, $var = $obj->exec($cmd) where $var will hold UNIX return status, 
 *    and $command is a DIRECT COMMAND incl. path if necessary
 *  - to get output, $array = $obj->execWithOutput($cmd) where $array[0] will hold cmd's 
 *    output, $array[1] holds UNIX return status and $command is a DIRECT COMMAND incl. path if necessary
 *
 * USAGE EXAMPLE:
 *
 * $CLI = new CLI;
 * print_r($CLI->execWithOutput("ls"));
 */
class CLI{

  var $output;
  var $return_status;

  /**
   * constructor.  
   */
  function CLI(){
    //FIXFIX rethink security

  }

  /**
   * execute a CLI command and ignore it's output but return status
   *
   * @return $status UNIX return status
   */
  function exec($command){
    $this->_run($command);
    return $this->return_status;
  }

  /**
   * execute a CLI command and return it's output and status
   *
   * @return array
   */
  function execWithOutput($command){
    $this->_run($command);
    return array("output"=>$this->output, "return_status"=>$this->return_status);
  }

  /**
   * private function to actually run command
   * 
   * @param $command
   */
  function _run($command){
    ob_start();
    passthru($command, $this->return_status);
    $this->output = ob_get_contents();
    ob_end_clean();
  }


}

?>
