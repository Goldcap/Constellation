<?php

/**
 * PhotoByColor Sorter Offline Tests
 *
 * @version $Id: ByColor.php 516 2006-03-29 03:56:51Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Tests/constants.inc';

require_once 'Phlickr/Photo.php';
require_once 'Phlickr/PhotoSortStrategy/ByColor.php';

class Phlickr_Tests_Offline_PhotoSortStrategy_ByColor extends PHPUnit2_Framework_TestCase {
    var $photo;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET);
        $this->strategy = new Phlickr_PhotoSortStrategy_ByColor($this->api->getCache());
        $this->photo = new Phlickr_Photo($this->api, simplexml_load_string(
<<<XML
<photo id="23155946" secret="7f6672db61" server="16"
    title="spaceman and the family arrive" isprimary="0"/>
XML
));
        // add a color cache so that stringFromPhoto() works
        $this->api->getCache()->set(
            'avg_color:'. $this->photo->getId(),
            array(235,45,20,'type'=>'rgb')
        );
    }
    function tearDown() {
        unset($this->api);
        unset($this->strategy);
        unset($this->photo);
    }

    function testGetAverageRgbColor() {
        $result = $this->strategy->getAverageRgbColor(TESTING_FILE_NAME_JPG);
        $this->assertEquals(array(235,45,20,'type'=>'rgb'), $result);
    }

    function testGetSortString() {
        // check that the cached rgb values is converted to hsl
        $result = $this->strategy->stringFromPhoto($this->photo);
        $this->assertEquals('00,91,92', $result);
    }
}
