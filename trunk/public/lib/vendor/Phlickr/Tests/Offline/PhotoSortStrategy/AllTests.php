<?php

/**
 * Runner for all offline comparator tests.
 *
 * To run the offline test suites (assuming the Phlickr installation is in the
 * include path) run:
 *      phpunit Phlickr_Tests_Offline_Comparators_AllTests
 *
 * @version $Id: AllTests.php 406 2005-09-03 04:49:39Z drewish $
 * @copyright 2005
 */


if (!defined('PHPUnit2_MAIN_METHOD')) {
    define('PHPUnit2_MAIN_METHOD', 'Phlickr_Tests_Offline_PhotoSortStrategy_AllTests::main');
}

require_once 'PHPUnit2/Framework/TestSuite.php';
require_once 'PHPUnit2/TextUI/TestRunner.php';
require_once 'PHPUnit2/Util/Filter.php';


require_once 'Phlickr/Tests/Offline/PhotoSortStrategy/ByColor.php';
require_once 'Phlickr/Tests/Offline/PhotoSortStrategy/ById.php';
require_once 'Phlickr/Tests/Offline/PhotoSortStrategy/ByTakenDate.php';
require_once 'Phlickr/Tests/Offline/PhotoSortStrategy/ByTitle.php';


class Phlickr_Tests_Offline_PhotoSortStrategy_AllTests {
    public static function main() {
        PHPUnit2_TextUI_TestRunner::run(self::suite());
    }

    public static function suite() {
        $suite = new PHPUnit2_Framework_TestSuite('Phlickr Tests');

        $suite->addTestSuite('Phlickr_Tests_Offline_PhotoSortStrategy_ByColor');
        $suite->addTestSuite('Phlickr_Tests_Offline_PhotoSortStrategy_ById');
        $suite->addTestSuite('Phlickr_Tests_Offline_PhotoSortStrategy_ByTakenDate');
        $suite->addTestSuite('Phlickr_Tests_Offline_PhotoSortStrategy_ByTitle');

        return $suite;
    }
}

if (PHPUnit2_MAIN_METHOD == 'Phlickr_Tests_Offline_PhotoSortStrategy_AllTests::main') {
    Phlickr_Tests_Offline_PhotoSortStrategy_AllTests::main();
}

?>
