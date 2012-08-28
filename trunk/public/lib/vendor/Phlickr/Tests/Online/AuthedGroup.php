<?php

/**
 * Group Online Tests
 *
 * @version $Id: AuthedGroup.php 497 2005-12-13 08:53:10Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/AuthedGroup.php';
require_once 'Phlickr/Tests/constants.inc';


class Phlickr_Tests_Online_AuthedGroup extends PHPUnit2_Framework_TestCase {
    var $api;
    var $group;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET, TESTING_API_TOKEN);
        $this->group = new Phlickr_AuthedGroup($this->api, TESTING_XML_GROUP_ID);
    }
    function tearDown() {
        try {
            $this->group->remove(TESTING_REAL_PHOTO_ID_JPG);
        } catch (Exception $ex) {}
        unset($this->group);
        unset($this->api);
    }

    function testGetPhotoList() {
        $result = $this->group->getPhotoList();
        $this->assertType('Phlickr_PhotoList', $result);
        $this->assertEquals('flickr.groups.pools.getPhotos', $result->getRequest()->getMethod());
    }

    function testAddRemovePhoto() {
        $photos = $this->group->getPhotoList();
        $photos->refresh();
        $count = $photos->getCount();

        // add
        $this->group->add(TESTING_REAL_PHOTO_ID_JPG);
        sleep(1);
        $photos->refresh();
        $this->assertEquals($count + 1, $photos->getCount());
        $this->assertContains(TESTING_REAL_PHOTO_ID_JPG, $photos->getIds());
        $count = $photos->getCount();

        // remove
        $this->group->remove(TESTING_REAL_PHOTO_ID_JPG);
        sleep(1);
        $photos->refresh();
        $this->assertEquals($count - 1, $photos->getCount());
        $this->assertNotContains(TESTING_REAL_PHOTO_ID_JPG, $photos->getIds());
    }
}

?>
