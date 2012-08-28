<?php

/**
 * Uploader Online Tests
 *
 * @version $Id: Uploader.php 523 2006-08-28 18:30:20Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Tests/constants.inc';
require_once 'Phlickr/Uploader.php';
require_once 'Phlickr/AuthedUser.php'; // to verify that photo count increments
require_once 'Phlickr/AuthedPhoto.php'; // to verify that photos exist

class Phlickr_Tests_Online_Uploader extends PHPUnit2_Framework_TestCase {
    var $api;
    var $uploader;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET, TESTING_API_TOKEN);
        $this->uploader = new Phlickr_Uploader($this->api);
    }
    function tearDown() {
        unset($this->uploader);
        unset($this->api);
    }


    function testUpload_BadLoginThrows() {
        $this->api->setAuthToken('BADTOKEN');
        try {
            $this->uploader->Upload(TESTING_FILE_NAME_JPG);
        } catch (Phlickr_Exception $ex) {
            return;
        } catch (Exception $ex) {
            $this->fail('threw the wrong type ('.get_class($ex).') of exception.');
        }
        $this->fail('an exception should have been thrown');
    }

    function testUpload_WithoutMeta() {
        $user = new Phlickr_User($this->api, $this->api->getUserId());
        $oldPhotoCount = $user->getPhotoCount();

        // upload it
        $result = $this->uploader->Upload(TESTING_FILE_NAME_JPG);

        // ensure the photo count increases
        $user->refresh();
        $this->assertEquals($oldPhotoCount + 1, $user->getPhotoCount(),
            'Photocount should have increased.');

        // verify the returned id
        $this->assertType('string', $result, 'Returned the wrong type.');
        $photo = new Phlickr_Photo($this->api, $result);
        $this->assertNotNull($photo, "Couldn't load a photo from the result id.");

        $this->assertEquals('small_sample', $photo->getTitle());
        $this->assertEquals('', $photo->getDescription());
        $this->assertEquals(array(), $photo->getTags());

        $this->assertTrue($photo->isForPublic());
        $this->assertTrue($photo->isForFriends());
        $this->assertTrue($photo->isForFamily());
    }

    function testUpload_WithMeta() {
        $user = new Phlickr_User($this->api, $this->api->getUserId());
        $oldPhotoCount = $user->getPhotoCount();

        $this->uploader->setPerms(true, false, false);

        // upload it
        $result = $this->uploader->Upload(TESTING_FILE_NAME_JPG,
            'testing title', 'a description', 'atag btag');

        // ensure the photo count increases
        $user->refresh();
        $this->assertEquals($oldPhotoCount + 1, $user->getPhotoCount(),
            'Photocount should have increased.');

        // verify the returned id
        $this->assertType('string', $result, 'Returned the wrong type.');
        $photo = new Phlickr_Photo($this->api, $result);
        $this->assertNotNull($photo, "Couldn't load a photo from the result id.");

        $this->assertEquals('testing title', $photo->getTitle());
        $this->assertEquals('a description', $photo->getDescription());
        $this->assertEquals(array('atag', 'btag'), $photo->getTags());

        $this->assertTrue($photo->isForPublic());
        $this->assertFalse($photo->isForFriends());
        $this->assertFalse($photo->isForFamily());
    }

    function testUpload_WithClassTags() {
        $this->uploader->setTags(array('classtag', 'barf'));

        // upload it
        $result = $this->uploader->Upload(TESTING_FILE_NAME_JPG,
            'testing title', 'a description', 'atag btag');

        $this->assertType('string', $result, 'Returned the wrong type.');
        $photo = new Phlickr_Photo($this->api, $result);

        $this->assertEquals(array('classtag', 'barf', 'atag', 'btag'), $photo->getTags());
    }


    function testUpload_WithClassPermissions() {
        $this->uploader->setPerms(false, false, false);

        // upload it
        $result = $this->uploader->Upload(TESTING_FILE_NAME_JPG,
            'testing title', 'a description', 'atag btag');

        // verify the returned id
        $this->assertType('string', $result, 'Returned the wrong type.');
        $photo = new Phlickr_Photo($this->api, $result);

        $this->assertFalse($photo->isForPublic());
        $this->assertFalse($photo->isForFriends());
        $this->assertFalse($photo->isForFamily());
    }
}
