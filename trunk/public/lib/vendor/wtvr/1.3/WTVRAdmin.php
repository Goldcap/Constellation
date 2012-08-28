<?php

/**
 * WTVRAdmin.php, Styroform XML Form Controller
 * 
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
// WTVRAdmin

/**
 * The Form Controller class, responsible for drawing the form, calling the validator, adding "Items" and "ItemLists", and navigating through formsets
 * @package WTVRAdmin
 * @subpackage classes
 */
class WTVRAdmin extends WTVRBase {
  
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
  function __construct() {
    parent::__construct();
  }
  
  function memory_get_usage() {
      //If its Windows
      //Tested on Win XP Pro SP2. Should work on Win 2003 Server too
      //Doesn't work for 2000
      //If you need it to work for 2000 look at http://us2.php.net/manual/en/function.memory-get-usage.php#54642
      if ( substr(PHP_OS,0,3) == 'WIN')
      {
             if ( substr( PHP_OS, 0, 3 ) == 'WIN' )
              {
                  $output = array();
                  exec( 'tasklist /FI "PID eq ' . getmypid() . '" /FO LIST', $output );
     
                  return preg_replace( '/[\D]/', '', $output[5] ) * 1024;
              }
      }else
      {
          //We now assume the OS is UNIX
          //Tested on Mac OS X 10.4.6 and Linux Red Hat Enterprise 4
          //This should work on most UNIX systems
          $pid = getmypid();
          exec("ps -eo%mem,rss,pid | grep $pid", $output);
          $output = explode("  ", $output[0]);
          //rss is given in 1024 byte units
          return $output[1] * 1024;
      }
  }
  
  /***************************************************************************/
  /*Admin Output
  /***************************************************************************/
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name adminToggle
  */
  function adminToggle() {
    ob_start();
    ?>
      <div id="wtvr_toggle">
        <a href="javascript: void(0);" onclick="toggleAdmin()">admin console</a>
      </div>
    <?
    return ob_get_clean();
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
  * @name adminControl
  */
  function adminControl() {
    ob_start();
    ?>
      
      <div id="wtvr_control">
      <table class="table_control">
        <tr>
          <th width="25%" align="left" class="text_control">
          <a href="javascript: void(0)" onclick="flopUp( 'addpage', 'Add A Page', true, '<? echo($this -> asseturl)?>/services/admin/addform?form=addpage' )" >Add A Page</a>
          </th>
          <th width="25%" align="left" class="text_control">
          <a href="javascript: void(0)" onclick="flopUp( 'copypage', 'Copy A Page', true, '<? echo($this -> asseturl)?>/services/admin/addform?form=copypage' )" >Copy A Page</a>
          </th>
          <th width="25%" align="left" class="text_control">
          <a href="javascript: void(0)" onclick="flopUp( 'modpage', 'Modify A Page', true, '<? echo($this -> asseturl)?>/services/admin/addform?form=modpage' )" >Modify A Page</a>
          </th>
          <th width="25%" align="left" class="text_control">
          &nbsp;
          </th>
        </tr>
        <tr>
          <th width="25%" align="left" class="text_control">
          <a href="javascript: void(0)" onclick="flopUp( 'addmodule', 'Add A Module', true, '<? echo($this -> asseturl)?>/services/admin/addform?form=addmodule' )" >Add A Module</a>
          </th>
          <th width="25%" align="left" class="text_control">
          <a href="javascript: void(0)" onclick="flopUp( 'copymodule', 'Copy A Module', true, '<? echo($this -> asseturl)?>/services/admin/addform?form=copymodule' )" >Copy A Module</a>
          </th>
          <th width="25%" align="left" class="text_control">
          <a href="javascript: void(0)" onclick="flopUp( 'modmod', 'Modify A Module', true, '<? echo($this -> asseturl)?>/services/admin/addform?form=modmod' )" >Modify A Module</a>
          </th>
          <th width="25%" align="left" class="text_control">
          &nbsp;
          </th>
        </tr>
        <tr>
          <th width="25%" align="left" class="text_control">
          <a href="javascript: void(0)" onclick="flopUp( 'copypage', 'Copy A Page', true, '<? echo($this -> asseturl)?>/services/admin/addform?form=copyscaff' )" >Copy A Scaffolded Page</a>
          </th>
          <th width="25%" align="left" class="text_control">
          <a href="javascript: void(0)" onclick="flopUp( 'copyscaffmod', 'Copy A Module', true, '<? echo($this -> asseturl)?>/services/admin/addform?form=copyscaffmod' )" >Copy A Scaffolded Module</a>
          </th>
          <th width="25%" align="left" class="text_control">
          &nbsp;
          </th>
          <th width="25%" align="left" class="text_control">
          <a href="javascript: void(0)" onclick="flopUp( 'modcontent', 'Modify Module Content', true, '<? echo($this -> asseturl)?>/services/admin/addform?form=modcontent' )" >Modify Content</a>
          
          </th>
        </tr>
        <tr>
          <th width="25%" align="left" class="text_control">
          <a href="javascript: void(0)" onclick="flopUp( 'conf', 'Generate Conf', false, '<? echo($this -> asseturl)?>/services/admin/conf/detail' )">Generate Conf</a>
          </th>
          <th width="25%" align="left" class="text_control">
          <a href="javascript: void(0)" onclick="flopUp( 'conf', 'Generate Crud', false, '<? echo($this -> asseturl)?>/services/admin/crud/detail' )">Generate Crud</a>
          </th>
          <th width="25%" align="left" class="text_control">
          <a href="javascript: void(0)" onclick="flopUp( 'conf', 'Generate Scaffolding', false, '<? echo($this -> asseturl)?>/services/admin/scaffold/detail' )">Generate Scaffold</a>
          </th>
          <th width="25%" align="left" class="text_control">
          <a href="javascript: void(0)" onclick="flopUp( 'conf', 'Generate Smarty', false, '<? echo($this -> asseturl)?>/services/admin/smarty/detail' )">Generate Smarty</a>
          </th>
        </tr>
        <tr>
          <th width="25%" align="left" class="text_control">
          <a href="javascript: void(0)" onclick="flopUp( 'conf', 'WTVR Log', false, '<? echo($this -> asseturl)?>/services/admin/log' )">Read Log</a>
          </th>
          <th width="25%" align="left" class="text_control">
          <a href="javascript: void(0)" onclick="flopUp( 'conf', 'Generate Propel', false, '<? echo($this -> asseturl)?>/services/admin/propel/detail' )">Generate Propel</a>
          </th>
          <th width="25%" align="left" class="text_control">
          <a href="javascript: void(0)" onclick="flopUp( 'conf', 'Generate ORM', false, '<? echo($this -> asseturl)?>/services/admin/orm/detail' )">Generate ORM</a>
          </th>
          <th width="25%" align="left" class="text_control">
          <a href="<?echo $_SERVER["REQUEST_URI"].URLDelim($_SERVER["REQUEST_URI"])."showXML=true"?>" target="_new">View XML</a>
          </th>
        </tr>
      </table>
      </div>
    <?
    return ob_get_clean();
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
  * @name debugOutput
  */
  function debugOutput() {
    ob_start();
    ?>
    <div id="wtvr_debug">
      <table align="center">
        <tr>
          <th width="33%" align="left" class="text_control">
          Stats
          </th>
          <th width="33%" align="left" class="text_control">
          $_SESSION
          </th>
          <th width="33%" align="left" class="text_control">
          $_POST
          </th>
          <th width="33%" align="left" class="text_control">
          $_GET
          </th>
        </tr>
        <tr valign='top'>
          <td>
            <table class="table_control">
              <tr>
                <td align='left' class='text_control'>
                  <?echo round(memory_get_usage() / 1024) ; ?> MB
                </td>
              </tr>
            </table>
          </td>
          <td>
          <table class="table_control">
          <?
          foreach ($_SESSION as $key=>$value) {
            echo("<tr valign='top'><td align='left' class='text_control'>");
            echo $key."=>".$value;
            echo("</td></tr>");
          }
          ?>
          </table>
          </td>
          <td>
          <table class="table_control">
          <?
          foreach ($_POST as $key=>$value) {
            echo("<tr valign='top'><td align='left' class='text_control'>");
            echo $key."=>".$value;
            echo("</td></tr>");
          }
          ?>
          </table>
          </td>
          <td>
          <table class="table_control">
          <?
          foreach ($_GET as $key=>$value) {
            echo("<tr valign='top'><td align='left' class='text_control'>");
            echo $key."=>".$value;
            echo("</td></tr>");
          }
          ?>
          </table>
          </td>
        </tr>
      </table>
      </div>
      <?
    return ob_get_clean();
  }
  
}

?>
