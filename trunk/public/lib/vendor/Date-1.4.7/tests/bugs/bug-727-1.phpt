<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
// CVS: $Id: bug-727-1.phpt,v 1.1 2006/11/20 08:54:51 firman Exp $
?>
--TEST--
Bug #727: Date_Calc::weeksInMonth() wrong result
Tests for weeksInMonth, february with 4 weeks
Monday as 1st day of week
--FILE--
<?php
/**
 * Test for: Date_Calc
 * Parts tested: Date_Calc::weeksInMonth()
 */

/**
 * Monday as 1st day of week
 */
define('DATE_CALC_BEGIN_WEEKDAY', 1);

require_once "Date/Calc.php";

$tests = array(
    array(1999, 2), array(2010, 2), array(2021, 2), array(2027, 2),
    array(1937, 2), array(1943, 2), array(1802, 2), array(1813, 2),
    array(1819, 2), array(1830, 2), array(1841, 2), array(1847, 2),
    array(1858, 2), array(1869, 2), array(1875, 2), array(1886, 2),
    array(1897, 2), array(1909, 2), array(1915, 2), array(1926, 2)
);

foreach ($tests as $date) {
    list ($year, $month) = $date;
    echo $year . '/' . $month . ' = ' . Date_Calc::weeksInMonth($month, $year) . ' weeks' . "\n";
}
?>
--EXPECT--
1999/2 = 4 weeks
2010/2 = 4 weeks
2021/2 = 4 weeks
2027/2 = 4 weeks
1937/2 = 4 weeks
1943/2 = 4 weeks
1802/2 = 4 weeks
1813/2 = 4 weeks
1819/2 = 4 weeks
1830/2 = 4 weeks
1841/2 = 4 weeks
1847/2 = 4 weeks
1858/2 = 4 weeks
1869/2 = 4 weeks
1875/2 = 4 weeks
1886/2 = 4 weeks
1897/2 = 4 weeks
1909/2 = 4 weeks
1915/2 = 4 weeks
1926/2 = 4 weeks
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