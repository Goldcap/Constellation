<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
// CVS: $Id: bug-674.phpt,v 1.1 2006/11/20 08:53:05 firman Exp $
?>
--TEST--
Bug #674: strange (wrong?) result of Date_Calc::endOfWeek
--FILE--
<?php
/**
 * Test for: Date_Calc
 * Parts tested: Date_Calc::endOfWeek(), Date_Calc::beginOfWeek(),
 *               Date_Calc::beginOfNextWeek() and Date_Calc::beginOfPrevWeek().
 */

require_once 'Date.php';

$dates = array (array(2003,3,17), array(2003,3,20), array(2003,3,23));
foreach ($dates as $date) {
    echo 'Parameters: ' . implode('-', array_reverse($date)) . "\n";
    $bow = Date_Calc::endOfWeek($date[2],$date[1],$date[0]);
    $eow = Date_Calc::beginOfWeek($date[2],$date[1],$date[0]);
    $bonw = Date_Calc::beginOfNextWeek($date[2],$date[1],$date[0]);
    $bopw = Date_Calc::beginOfPrevWeek($date[2],$date[1],$date[0]);
    echo 'Begin of week = ' . $bow . ', End of week = ' . $eow . ', ' .
         'Begin of next week = ' . $bonw . ', Begin of previous week = ' . $bopw .
         "\n\n";
}
?>
--EXPECT--
Parameters: 17-3-2003
Begin of week = 20030323, End of week = 20030317, Begin of next week = 20030324, Begin of previous week = 20030310

Parameters: 20-3-2003
Begin of week = 20030323, End of week = 20030317, Begin of next week = 20030324, Begin of previous week = 20030310

Parameters: 23-3-2003
Begin of week = 20030323, End of week = 20030317, Begin of next week = 20030324, Begin of previous week = 20030310

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