<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
// CVS: $Id: bug-9213.phpt,v 1.1 2006/11/20 09:08:05 firman Exp $
?>
--TEST--
Bug #9213: Date_Calc doesn't like including Date.php
--FILE--
<?php
/**
 * Test for: Date_Calc
 * Parts tested: DATE_CALC_FORMAT constant
 */

require_once 'Date.php'; //Uh oh! I break things
require_once 'Date/Calc.php';

$calc = new Date_Calc();
print $calc->beginOfWeek(1, 6, 2006) . "\n";
print $calc->beginOfWeek(1, 6, 2006) . "\n";
print $calc->beginOfNextWeek(1, 6, 2006) . "\n";
print $calc->beginOfWeek() . "\n";

?>
--EXPECT--
20060529
20060529
20060605
(timestamp)
<?php
/*
 * Local variables:
 * mode: php
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
?>