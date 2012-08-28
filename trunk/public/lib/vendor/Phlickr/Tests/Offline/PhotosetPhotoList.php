<?php

/**
 * PhotoList Offline Tests
 *
 * @version $Id: PhotosetPhotoList.php 520 2006-04-24 06:11:53Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/PhotosetPhotoList.php';
require_once 'Phlickr/Tests/Mocks/Request.php';

class Phlickr_Tests_Offline_PhotosetPhotoList extends PHPUnit2_Framework_TestCase {
    var $api, $request, $psl;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET, TESTING_API_TOKEN);
        $this->api->setEndpointUrl('http://example.com');

        $this->request = new Phlickr_Tests_Mocks_Request(
            $this->api, 'USER SPECIFIED SEARCH',
            TESTING_RESP_OK_PREFIX . TESTING_XML_PHOTOSET_PHOTOS . TESTING_RESP_SUFIX
        );

        // ... then for the photos
        $this->api->addResponseToCache(
            Phlickr_PhotosetPhotoList::getRequestMethodName(),
            Phlickr_PhotosetPhotoList::getRequestMethodParams(TESTING_XML_PHOTOSET_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_PHOTOSET_PHOTOS . TESTING_RESP_SUFIX
        );

        $this->request = $this->api->createRequest(
            'flickr.photosets.getPhotos',
            array('photoset_id' => TESTING_XML_PHOTOSET_ID)
        );
        $this->psl = new Phlickr_PhotosetPhotoList($this->request);
    }
    function tearDown() {
        unset($this->psl);
        unset($this->api);
    }

    function testConstructor() {
        $this->assertNotNull($this->psl);
    }

    function testConstructor_AssignsRequest() {
        $this->assertSame($this->request, $this->psl->getRequest());
    }

    function testGetPhotoCount() {
        $this->assertEquals(5, $this->psl->getCount());
    }

    function testGetIds() {
        $this->assertEquals(array('2484', '2483', '2487', '2488', '2489'), $this->psl->getIds());
    }

    function testGetRandomPhoto() {
        // if there's more than one in the set make sure
        // that we get a different one after a few trys.
        $this->assertTrue($this->psl->getCount() > 1);

        $photo = $this->psl->getRandomPhoto();
        $this->assertNotNull($photo);
        $this->assertType('Phlickr_Photo', $photo);
        $id1 = $photo->getId();

        $id2 = $this->psl->getRandomPhoto()->getId();
        if ($id1 == $id2) {
            $id3 = $this->psl->getRandomPhoto()->getId();
            $this->assertTrue($id1 != $id3, 'got the same id 3 times.');
        }
    }
}
