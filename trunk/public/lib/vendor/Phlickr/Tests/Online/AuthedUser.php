<?php

/**
 * AuthedUser Online Tests
 *
 * @version $Id: AuthedUser.php 497 2005-12-13 08:53:10Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/AuthedUser.php';
require_once 'Phlickr/Tests/constants.inc';


class Phlickr_Tests_Online_AuthedUser extends PHPUnit2_Framework_TestCase {
    var $api;
    var $user;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET, TESTING_API_TOKEN);
        $this->user = new Phlickr_AuthedUser($this->api);
    }
    function tearDown() {
        unset($this->user);
        unset($this->api);
    }

    function testConstructor_AssignsId() {
        $this->assertType('Phlickr_AuthedUser', $this->user);
        $this->assertEquals(TESTING_XML_USER_ID, $this->user->getId());
    }

    function testGetContactUserList_ReturnsCorrectClass() {
        $result = $this->user->getContactUserList();
        $this->assertType('Phlickr_UserList', $result);
        $this->assertEquals(2, $result->getCount());
        $this->assertEquals(array('26159919@N00','40962351@N00'),
            $result->getIds());
    }

    function testGetPhotoList_DefaultPerPage() {
        $result = $this->user->getPhotoList();
        $this->assertType('Phlickr_PhotoList', $result);
        $this->assertEquals(Phlickr_PhotoList::PER_PAGE_DEFAULT, $result->getPhotosPerPage());
        $photos = $result->getPhotos();
        $this->assertEquals(TESTING_USER_ID, $photos[0]->getUserId());
    }

    function testGetPhotoList_AssignedPerPage() {
        $result = $this->user->getPhotoList(1);
        $this->assertType('Phlickr_PhotoList', $result);
        $this->assertEquals(1, $result->getPhotosPerPage());
        $photos = $result->getPhotos();
        $this->assertEquals(TESTING_USER_ID, $photos[0]->getUserId());
    }

    function testGetFavoritesPhotoList() {
        $result = $this->user->getFavoritePhotoList();
        $this->assertType('Phlickr_PhotoList', $result);
        $this->assertEquals('flickr.favorites.getList', $result->getRequest()->getMethod());
        //$this->assertEquals(1, $result->getCount());
        $this->assertContains('6508272', $result->getIds());
    }

    function testGetGroupList() {
        $result = $this->user->getGroupList();
        $this->assertType('Phlickr_GroupList', $result);
        $this->assertEquals(2, $result->getCount());
        $ids = $result->getIds();
        $this->assertContains('84636767@N00', $ids);
        $this->assertContains('84636767@N00', $ids);
    }

    function testAddRemoveFavorite() {
        $photo_id = '12720027';

        // make sure the photo isn't a fav before we add it
        $favIds = $this->user->getFavoritePhotoList()->getIds();
        if (in_array($photo_id, $favIds)) {
            $this->user->removeFavorite($photo_id);
        }
        $favIds = $this->user->getFavoritePhotoList()->getIds();
        $this->assertNotContains($photo_id, $favIds, "shouldn't be a fav when we start... it should have been removed but the change may not have appeared. re-run the test.");

        // add it and verify that it is a fav
        $ret = $this->user->addFavorite($photo_id);
        sleep(1);
        $ret->refresh();

        $this->assertType('Phlickr_PhotoList', $ret);
        $this->assertEquals('flickr.favorites.getList', $ret->getRequest()->getMethod());
        $this->assertContains($photo_id, $ret->getIds(), "should be a fav after we add it");

        // remove it and verify that it isn't a fav
        $ret = $this->user->removeFavorite($photo_id);
        sleep(1);
        $ret->refresh();

        $this->assertType('Phlickr_PhotoList', $ret);
        $this->assertEquals('flickr.favorites.getList', $ret->getRequest()->getMethod());
        $this->assertNotContains($photo_id, $ret->getIds(), "shouldn't be a fav after we remove it.");
    }
}

?>
