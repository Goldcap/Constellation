<?php

/**
 * index.php, Styroform XML Form Controller
 * 
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
// index

//ini_set ( include_path, "/usr/home/jwtnadb/sharedlib/:/usr/home/jwtnadb/lib/:/usr/www/users/jwtnadb/" );

/**
* XML Form Utilites for string manipulation and flow control
*/
include_once("globals/globals.php");

/**
* XML Form Utilites for string manipulation and flow control
*/
include_once("wtvr/1.0/clsGlobalBase.php");

$thepage = new GlobalBase();

$thepage -> restRedirect();

?>
