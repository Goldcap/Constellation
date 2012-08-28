<?php

/**
 * PhotoList Online Tests
 *
 * @version $Id: PhotoList.php 523 2006-08-28 18:30:20Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Tests/constants.inc';
require_once 'Phlickr/PhotoList.php';

class Phlickr_Tests_Online_PhotoList extends PHPUnit2_Framework_TestCase {
    var $api, $request, $pl;
    var $perPage = 5;
    // this is a magic number if photos with the tag get added or deleted it'll
    // need to be changed.
    var $totalPhotos = 7;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET, TESTING_API_TOKEN);
        $this->request = $this->api->createRequest('flickr.photos.search',
            array(
                'user_id'=>$this->api->getUserId(),
                'tags'=>'searchtag'
            )
        );
        $this->pl = new Phlickr_PhotoList($this->request, $this->perPage);
    }
    function tearDown() {
        unset($this->pl);
        unset($this->request);
        unset($this->api);
    }

    function testGetPhotosPerPage() {
        $this->assertEquals($this->perPage, $this->pl->getPhotosPerPage());
    }

    function testGetCount() {
        $count = $this->pl->getCount();
        $this->assertEquals($this->totalPhotos, $count);
    }

    function testGetPagecount() {
        $expected = (integer) ceil($this->totalPhotos / $this->perPage);
        $this->assertEquals($expected, $this->pl->getPageCount());
    }

    function testGetPhotos_FirstPage() {
        $photos = $this->pl->getPhotos();
        $this->assertTrue(is_array($photos), 'Did not return an array.');
        $this->assertEquals($this->perPage, count($photos));
    }

    function testGetPhotos_SecondPage() {
        $this->pl->setPage(2);
        $photos = $this->pl->getPhotos();
        $this->assertTrue(is_array($photos), 'Did not return an array.');
        $this->assertEquals(($this->totalPhotos % $this->perPage), count($photos));
    }
}
