<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
// CVS: $Id: bug-727-2.phpt,v 1.1 2006/11/20 08:56:24 firman Exp $
?>
--TEST--
Bug #727: Date_Calc::weeksInMonth() wrong result
Tests for weeksInMonth, february with 4 weeks
Sunday as 1st day of week
--FILE--
<?php
/**
 * Test for: Date_Calc
 * Parts tested: Date_Calc::weeksInMonth()
 */

/**
 * Sunday as 1st day of week
 */
define('DATE_CALC_BEGIN_WEEKDAY', 0);

require_once "Date/Calc.php";

$tests = array(
    array(2009, 2), array(2015, 2), array(2026, 2), array(2037, 2),
    array(1931, 2), array(1942, 2), array(1801, 2), array(1807, 2),
    array(1818, 2), array(1829, 2), array(1835, 2), array(1846, 2),
    array(1857, 2), array(1863, 2), array(1874, 2), array(1885, 2),
    array(1891, 2), array(1903, 2), array(1914, 2), array(1925, 2)
);

foreach ($tests as $date) {
    list ($year, $month) = $date;
    echo $year . '/' . $month . ' = ' . Date_Calc::weeksInMonth($month, $year) . ' weeks' . "\n";
}
?>
--EXPECT--
2009/2 = 4 weeks
2015/2 = 4 weeks
2026/2 = 4 weeks
2037/2 = 4 weeks
1931/2 = 4 weeks
1942/2 = 4 weeks
1801/2 = 4 weeks
1807/2 = 4 weeks
1818/2 = 4 weeks
1829/2 = 4 weeks
1835/2 = 4 weeks
1846/2 = 4 weeks
1857/2 = 4 weeks
1863/2 = 4 weeks
1874/2 = 4 weeks
1885/2 = 4 weeks
1891/2 = 4 weeks
1903/2 = 4 weeks
1914/2 = 4 weeks
1925/2 = 4 weeks
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