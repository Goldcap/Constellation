<?php

/**
 * Runner for all tests.
 *
 * To run all test suite (assuming the Phlickr installation is in the include path)
 * run "phpunit Phlickr_Tests_AllTests"
 *
 * @version $Id: AllTests.php 324 2005-07-06 01:00:30Z drewish $
 * @copyright 2005
 */

if (!defined('PHPUnit2_MAIN_METHOD')) {
    define('PHPUnit2_MAIN_METHOD', 'Phlickr_Tests_AllTests::main');
}

require_once 'PHPUnit2/Framework/TestSuite.php';
require_once 'PHPUnit2/TextUI/TestRunner.php';

// sub-directories
require_once 'Phlickr/Tests/Online/AllTests.php';
require_once 'Phlickr/Tests/Offline/AllTests.php';


class Phlickr_Tests_AllTests {
    public static function main() {
        PHPUnit2_TextUI_TestRunner::run(self::suite());
    }

    public static function suite() {
        $suite = new PHPUnit2_Framework_TestSuite('PHPUnit');

        $suite->addTest(Phlickr_Tests_Offline_AllTests::suite());
        $suite->addTest(Phlickr_Tests_Online_AllTests::suite());

        return $suite;
    }
}

if (PHPUnit2_MAIN_METHOD == 'Phlickr_Tests_AllTests::main') {
    Phlickr_Tests_AllTests::main();
}


?>
