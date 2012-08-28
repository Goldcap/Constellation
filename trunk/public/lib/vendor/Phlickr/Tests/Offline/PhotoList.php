<?php

/**
 * PhotoList Offline Tests
 *
 * @version $Id: PhotoList.php 515 2006-02-06 00:29:20Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/PhotoList.php';
require_once 'Phlickr/Tests/Mocks/PhotoListRequest.php';


class Phlickr_Tests_Offline_PhotoList extends PHPUnit2_Framework_TestCase {
    var $api, $request, $pl;
    var $totalPhotos, $perPage;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET, TESTING_API_TOKEN);
        $this->api->setEndpointUrl('http://example.com');

        // so we can find the user to load authedphotos where appropriate.
        $this->api->addResponseToCache(
            'flickr.auth.checkToken',
            $this->api->getParamsForRequest(),
            TESTING_RESP_OK_PREFIX . TESTING_XML_CHECKTOKEN . TESTING_RESP_SUFIX
        );

        $this->totalPhotos = 31;
        $this->perPage = 10;
        $this->request = new Phlickr_Tests_Mocks_PhotoListRequest($this->api, $this->totalPhotos, $this->perPage);
        $this->pl = new Phlickr_PhotoList($this->request, $this->perPage);
    }
    function tearDown() {
        unset($this->totalPhotos);
        unset($this->perPage);
        unset($this->pl);
        unset($this->request);
        unset($this->api);
    }

    function testConstructor_AssignsRequest() {
        $this->assertEquals($this->request, $this->pl->getRequest());
    }
    function testConstructor_AssignsPhotosPerPageDefault() {
        $pl = new Phlickr_PhotoList($this->request);
        $this->assertEquals(100, $pl->getPhotosPerPage());
    }
    function testConstructor_AssignsPhotosPerPage10Specified() {
        $this->assertEquals($this->perPage, $this->pl->getPhotosPerPage());
    }
    function testConstructor_AssignsPhotosPerPageToMaximum() {
        $pl = new Phlickr_PhotoList($this->request, Phlickr_PhotoList::PER_PAGE_MAX + 1);
        $this->assertEquals(Phlickr_PhotoList::PER_PAGE_MAX, $pl->getPhotosPerPage(), "Didn't limit photos per page to the maximum.");
    }
    function testConstructor_AssignsCurrentPage() {
        $this->assertEquals(1, $this->pl->getPage());
    }

    function testSetPage() {
        $this->pl->setPage(2);
        $this->assertEquals(2, $this->pl->getPage());
    }

    function testGetPagecount() {
        $expected = (integer) ceil($this->totalPhotos / $this->perPage);
        $this->assertEquals($expected, $this->pl->getPageCount());
    }

    function testGetPhotoCount() {
        $this->assertEquals($this->totalPhotos, $this->pl->getCount());
    }

    function testGetIds_FirstPage() {
        $ids = $this->pl->getIds();
        for ($i = 1; $i <= $this->perPage; $expected[] = (string) $i++);
        $this->assertTrue(is_array($ids), 'Did not return an array.');
        $this->assertEquals($expected, $ids);
    }

    function testGetIds_SecondPage() {
        $this->pl->setPage(2);
        for ($i = $this->perPage + 1; $i <= 2 * $this->perPage; $i++) {
            $expected[] = (string) $i;
        }
        $ids = $this->pl->getIds();
        $this->assertTrue(is_array($ids), 'Did not return an array.');
        $this->assertEquals($expected, $ids);
    }

    function testGetIds_AllPages() {
        // there idea here is to do a complete test
        $totalIds = array();

        for ($page = 1; $page < $this->pl->getPageCount() + 1; $page++) {
            $this->pl->setPage($page);

            $this->assertEquals($page, $this->pl->getPage($page));

            $ids = $this->pl->getIds();
            $this->assertTrue(is_array($ids), 'Did not return an array.');

            foreach ($ids as $id) {
                array_push($totalIds, $id);
            }

            $this->assertEquals(count($totalIds), count(array_unique($totalIds)));
        }
        $this->assertEquals(count($totalIds), $this->pl->getCount());
    }

    function testGetPhotos_ReturnsCorrectClass() {
        $userid = $this->api->getUserId();
        foreach ($this->pl->getPhotos() as $p) {
            // everything should be a photo object
            $this->assertType('Phlickr_Photo', $p);
            if ($userid == $p->getUserId()) {
                // but those with our user id should be authed photos
                $this->assertType('Phlickr_AuthedPhoto', $p);
            }
        }
    }

    function testGetPhotos_FirstPage() {
        $photos = $this->pl->getPhotos();
        $this->assertTrue(is_array($photos), 'Did not return an array.');
        $this->assertEquals($this->perPage, count($photos));
        foreach ($photos as $p) {
            $this->assertType('Phlickr_Photo', $p);
        }
    }

    function testGetPhotos_SecondPage() {
        $this->pl->setPage(2);
        $photos = $this->pl->getPhotos();
        $this->assertTrue(is_array($photos), 'Did not return an array.');
        $this->assertEquals($this->perPage, count($photos));
        foreach ($photos as $p) {
            $this->assertType('Phlickr_Photo', $p);
        }
    }

    function testGetRandomPhoto() {
        $this->pl->setPage(2);

        $photo = $this->pl->getRandomPhoto();
        $this->assertType('Phlickr_Photo', $photo);
        $this->assertEquals(2, $this->pl->getPage(), 'the page should not have changed.');

        $id1 = $photo->getId();

        $id2 = $this->pl->getRandomPhoto()->getId();
        if ($id1 == $id2) {
            $id3 = $this->pl->getRandomPhoto()->getId();
            $this->assertTrue($id1 != $id3, 'got the same id 3 times.');
        }
    }
}
