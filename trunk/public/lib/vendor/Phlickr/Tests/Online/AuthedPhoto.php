<?php

/**
 * AuthedPhoto Online Tests
 *
 * @version $Id: AuthedPhoto.php 508 2006-02-02 09:00:31Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/AuthedPhoto.php';
require_once 'Phlickr/Uploader.php';
require_once 'Phlickr/Tests/constants.inc';

class Phlickr_Tests_Online_AuthedPhoto extends PHPUnit2_Framework_TestCase {
    var $api;
    var $photo;
    var $filepath;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET, TESTING_API_TOKEN);
        $this->photo = new Phlickr_AuthedPhoto($this->api, TESTING_REAL_PHOTO_ID_JPG);
        $this->filepath = tempnam('/tmp', 'image');
    }
    function tearDown() {
        unset($this->photo);
        unset($this->api);
        if (file_exists($this->filepath))
            unlink($this->filepath);
        unset($this->filepath);
    }


    function testSetPosted() {
        $tsActual = time();
        $this->photo->setPosted($tsActual);
        sleep(2);
        $this->assertEquals($tsActual, $this->photo->getPostedTimestamp());
    }


    function testSetTaken_String_Granularity0() {
        $granularity = 0;
        $date = date('Y-m-d H:i:s');
        $this->photo->setTaken($date, $granularity);

        $this->assertEquals($date, $this->photo->getTakenDate());
        $this->assertEquals($granularity, $this->photo->getTakenGranularity());
    }
    function testSetTaken_String_Granularity4() {
        $granularity = 4;
        $date = date('Y-m-01 00:00:00');
        $this->photo->setTaken($date, $granularity);

        $this->assertEquals($date, $this->photo->getTakenDate());
        $this->assertEquals($granularity, $this->photo->getTakenGranularity());
    }
    function testSetTaken_String_Granularity6() {
        $granularity = 6;
        $date = date('Y-01-01 00:00:00');
        $this->photo->setTaken($date, $granularity);

        $this->assertEquals($date, $this->photo->getTakenDate());
        $this->assertEquals($granularity, $this->photo->getTakenGranularity());
    }
    function testSetTaken_String_NegativeDate() {
        $granularity = 0;
        $date = '1968-01-10 00:00:00';
        $this->photo->setTaken($date, $granularity);

        $this->assertEquals($granularity, $this->photo->getTakenGranularity());
        $this->assertEquals($date, $this->photo->getTakenDate());
    }


    function testSetTaken_Timestamp_Granularity0() {
        $granularity = 0;
        $tsActual = time();
        $tsRounded = strtotime(date('Y-m-d H:i:s', $tsActual));
        $this->photo->setTaken($tsActual, $granularity);

        $this->assertEquals($granularity, $this->photo->getTakenGranularity());
        $this->assertEquals($tsRounded, $this->photo->getTakenTimestamp());
    }
    function testSetTaken_Timestamp_Granularity4() {
        $granularity = 4;
        $tsActual = time();
        // granularity 4 is only accurate to years and months
        $tsRounded = strtotime(date('Y-m-d 00:00:00', $tsActual));
        $this->photo->setTaken($tsActual, $granularity);

        $this->assertEquals($granularity, $this->photo->getTakenGranularity());
        $this->assertEquals($tsRounded, $this->photo->getTakenTimestamp());
    }
    function testSetTaken_Timestamp_Granularity6() {
        $granularity = 6;
        $tsActual = time();
        // granularity 4 is only accurate to years
        $tsRounded = strtotime(date('Y-01-01 00:00:00', $tsActual));
        $this->photo->setTaken($tsActual, $granularity);

        $this->assertEquals($granularity, $this->photo->getTakenGranularity());
        $this->assertEquals($tsRounded, $this->photo->getTakenTimestamp());
    }
    function testSetTaken_Timestamp_NegativeDate() {
        $tsOriginal = $this->photo->getTakenTimestamp();

        // try a date before the unix epoch so it'll be negative
        $granularity = 4;
        $tsActual = strtotime('1968-01-10 00:00:00');

        $this->photo->setTaken($tsActual, $granularity);
        if ($_SERVER['OS'] == 'Windows_NT') {
            // windows doesn't deal with negative timestamps so it won't have changed
            $this->assertEquals($tsOriginal, $this->photo->getTakenTimestamp());
        } else {
            $this->assertEquals($tsActual, $this->photo->getTakenTimestamp());
        }
    }


    function testAddTags_UsingCleanTags() {
        $this->photo->addTags(array('newtag'));
        $result = $this->photo->getTags();
        $this->assertContains('newtag', $result);
    }
    function testAddTags_UsingMessyTags() {
        $this->photo->addTags(array('a New-ish Tag!'));
        $result = $this->photo->getTags();
        $this->assertContains('anewishtag', $result);
    }


    function testSetTags_ToNothing() {
        $this->photo->setTags(array());
        $result = $this->photo->getTags();
        $this->assertEquals(array(), $result);
    }
    function testSetTags_UsingCleanTags() {
        $this->photo->setTags(array('something', 'another'));
        $result = $this->photo->getTags();
        $this->assertEquals(array('something', 'another'), $result);
    }
    function testSetTags_UsingMessyTags() {
        $this->photo->setTags(array('some thing!', 'a-nother.'));
        $result = $this->photo->getTags();
        $this->assertEquals(array('something', 'another'), $result);
    }


    function testRemoveTag_UsingCleanTags() {
        $this->photo->setTags(array('something', 'another', '2004'));
        sleep(2);
        $this->photo->removeTag('another');
        sleep(2);
        $result = $this->photo->getTags();
        $this->assertEquals(array('something', '2004'), $result);
    }
    function testRemoveTag_UsingMessyTags() {
        $this->photo->setTags(array('some thing!', 'a-nother.', '2004'));
        sleep(2);
        $this->photo->removeTag('a-nother.');
        sleep(2);
        $result = $this->photo->getTags();
        $this->assertEquals(array('something', '2004'), $result);
    }


    function testSetMeta_ToNothing() {
        $this->photo->setMeta('', '');
        $this->assertEquals('', $this->photo->getTitle());
        $this->assertEquals('', $this->photo->getDescription());
    }
    function testSetMeta_ToSomething() {
        $this->photo->setMeta('a title', 'descrip');
        $this->assertEquals('a title', $this->photo->getTitle());
        $this->assertEquals('descrip', $this->photo->getDescription());
    }


    function testDelete() {
        // create a new photo
        $uploader = new Phlickr_Uploader($this->api);
        $id = $uploader->Upload(TESTING_FILE_NAME_JPG);
        sleep(2);

        // delete it
        $photo = new Phlickr_AuthedPhoto($this->api, $id);
        $photo->delete();

        try {
            sleep(2);
            $photo->refresh();
        } catch (Phlickr_Exception $ex) {
            return;
        } catch (Exception $ex) {
            $this->fail('threw the wrong type ('.get_class($ex).') of exception.');
        }
    }
}
