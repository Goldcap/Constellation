<?php

/**
 * crud.php, Styroform XML Form Controller
 * Be sure your crud directory is APACHE writable
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package crud
 */
// WTVRUtils

$GLOBALS["wtvr_version"] = "1.2";
$GLOBALS["libroot"] = "/var/www/html/lib/";
$GLOBALS['local_libroot'] = "/var/www/html/sites/atkins/lib/";

/**
* XML Form Utilites for string manipulation and flow control
*/
include_once("wtvr/".$GLOBALS["wtvr_version"]."/admin/clsWTVRCrudCreator.php");

$crud = new WTVRCrudCreator();
$crud -> doCrud();

?>
