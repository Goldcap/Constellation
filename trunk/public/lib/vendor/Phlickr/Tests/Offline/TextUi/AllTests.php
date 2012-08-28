<?php

/**
 * Runner for all offline TextUi tests.
 *
 * To run the offline test suites (assuming the Phlickr installation is in the
 * include path) run:
 *      phpunit Phlickr_Tests_Offline_TextUi_AllTests
 *
 * @version $Id: AllTests.php 520 2006-04-24 06:11:53Z drewish $
 * @copyright 2005
 */


if (!defined('PHPUnit2_MAIN_METHOD')) {
    define('PHPUnit2_MAIN_METHOD', 'Phlickr_Tests_Offline_TextUi_AllTests::main');
}

require_once 'PHPUnit2/Framework/TestSuite.php';
require_once 'PHPUnit2/TextUI/TestRunner.php';
require_once 'PHPUnit2/Util/Filter.php';

require_once 'Phlickr/Tests/Offline/TextUi/UploadBatchViewer.php';
require_once 'Phlickr/Tests/Offline/TextUi/UploadListener.php';

class Phlickr_Tests_Offline_TextUi_AllTests {
    public static function main() {
        PHPUnit2_TextUI_TestRunner::run(self::suite());
    }

    public static function suite() {
        $suite = new PHPUnit2_Framework_TestSuite('Phlickr Offline Import Tests');

        $suite->addTestSuite('Phlickr_Tests_Offline_TextUi_UploadBatchViewer');
        $suite->addTestSuite('Phlickr_Tests_Offline_TextUi_UploadListener');

        return $suite;
    }
}

if (PHPUnit2_MAIN_METHOD == 'Phlickr_Tests_Offline_TextUi_AllTests::main') {
    Phlickr_Tests_Offline_TextUi_AllTests::main();
}
