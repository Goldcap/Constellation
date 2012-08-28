<?php

/**
 * PhotoSortStrategy ById Offline Tests
 *
 * @version $Id: ById.php 376 2005-08-06 08:42:32Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Tests/constants.inc';

require_once 'Phlickr/Photo.php';
require_once 'Phlickr/PhotoSortStrategy/ById.php';

class Phlickr_Tests_Offline_PhotoSortStrategy_ById extends PHPUnit2_Framework_TestCase {
    var $photo;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET);
	$this->strategy = new Phlickr_PhotoSortStrategy_ById();
    	$this->photo = new Phlickr_Photo($this->api, simplexml_load_string(
<<<XML
<photo id="23155946" secret="7f6672db61" server="16"
    title="spaceman and the family arrive" isprimary="0"/>
XML
));
    }
    function tearDown() {
        unset($this->api);
	unset($this->strategy);
	unset($this->photo);
    }

    function testGetSortString() {
	$result = $this->strategy->stringFromPhoto($this->photo);
	$this->assertEquals('000023155946', $result,
	    'the photo id should have been padded to 12 characters');
    }
}

?>
