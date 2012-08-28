<?php

/**
 * PhotoSortStrategy ByTakenDate Offline Tests
 *
 * @version $Id: ByTakenDate.php 406 2005-09-03 04:49:39Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Tests/constants.inc';

require_once 'Phlickr/Photo.php';
require_once 'Phlickr/PhotoSortStrategy/ByTakenDate.php';

class Phlickr_Tests_Offline_PhotoSortStrategy_ByTakenDate extends PHPUnit2_Framework_TestCase {
    var $photo;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET);
	$this->strategy = new Phlickr_PhotoSortStrategy_ByTakenDate();
    	$this->photo = new Phlickr_Photo($this->api, simplexml_load_string(
<<<XML
<photo id="2733" secret="123456" server="12" isfavorite="0" license="3">
    <owner nsid="12037949754@N01" username="Bees" realname="Cal Henderson"
        location="Bedford, UK" />
    <title>orford_castle_taster</title>
    <description>hello!</description>
    <dates posted="1100897479" taken="2004-11-19 12:51:19" takengranularity="0" />
</photo>
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
	$this->assertEquals('2004-11-19 12:51:19', $result,
	    'the photo taken date/time should have been returned.');
    }
}

?>
