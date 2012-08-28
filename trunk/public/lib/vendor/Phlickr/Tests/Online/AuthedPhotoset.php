<?php

/**
 * AuthedPhotoset Online Tests
 *
 * @version $Id: AuthedPhotoset.php 358 2005-07-19 00:38:59Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Tests/constants.inc';
require_once 'Phlickr/AuthedPhotoset.php';

class Phlickr_Tests_Online_AuthedPhotoset extends PHPUnit2_Framework_TestCase {
    var $api;
    var $photoset;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET, TESTING_API_TOKEN);
        $this->photoset = new Phlickr_AuthedPhotoset($this->api, TESTING_REAL_PHOTOSET_ID1);
    }
    function tearDown() {
        unset($this->photoset);
        unset($this->api);
    }

    function testSetMeta() {
        $this->photoset->setMeta('just a title');
        $this->assertEquals('just a title', $this->photoset->getTitle());
        $this->assertEquals('', $this->photoset->getDescription());

        $this->photoset->setMeta('a title', 'descrip');
        $this->assertEquals('a title', $this->photoset->getTitle());
        $this->assertEquals('descrip', $this->photoset->getDescription());
    }

    function testEditPhotos_ThrowsIfPrimaryNotInSet() {
        // the primary photo must be in the array
        try {
            $this->photoset->editPhotos(TESTING_REAL_PHOTO_ID_JPG,
                array(TESTING_REAL_PHOTO_ID_PNG));
        } catch (Phlickr_MethodFailureException $ex) {
            return;
        } catch (Exception $ex) {
            $this->fail('threw the wrong type ('.get_class($ex).') of exception.');
        }
        $this->Fail('An exception should have been thrown.');
    }

    function testEditPhotos_ReturnsCorrect() {
        $photoIds = array(TESTING_REAL_PHOTO_ID_JPG);
        $ret = $this->photoset->editPhotos(TESTING_REAL_PHOTO_ID_JPG, $photoIds);

        $this->assertType('Phlickr_PhotosetPhotoList', $ret);
        $this->assertEquals('flickr.photosets.getPhotos',
            $ret->getRequest()->getMethod());
    }

    function testEditPhotos_SingleItem() {
        $photoIds = array(TESTING_REAL_PHOTO_ID_PNG);
        $ret = $this->photoset->editPhotos(TESTING_REAL_PHOTO_ID_PNG, $photoIds);

        $this->assertEquals(TESTING_REAL_PHOTO_ID_PNG,
            $this->photoset->getPrimaryId(), 'Primary photo was not set.');
        $this->assertEquals($photoIds, $ret->getIds(),
            'Primary photo was not set.');
    }

    function testEditPhotos_MultipleItem() {
        $photoIds = array(TESTING_REAL_PHOTO_ID_PNG, TESTING_REAL_PHOTO_ID_JPG);
        $ret = $this->photoset->editPhotos(TESTING_REAL_PHOTO_ID_JPG, $photoIds);

        $this->assertEquals(TESTING_REAL_PHOTO_ID_JPG,
            $this->photoset->getPrimaryId(), 'Primary photo was not set.');
        $this->assertEquals($photoIds, $ret->getIds(),
            'Primary photo was not set.');

        // now switch the order to ensure that caching doesn't screw up the results
        $photoIds = array(TESTING_REAL_PHOTO_ID_JPG, TESTING_REAL_PHOTO_ID_PNG);
        $ret = $this->photoset->editPhotos(TESTING_REAL_PHOTO_ID_JPG, $photoIds);

        $this->assertEquals(TESTING_REAL_PHOTO_ID_JPG,
            $this->photoset->getPrimaryId(), 'Primary photo was not set.');
        $this->assertEquals($photoIds, $ret->getIds(),
            'Primary photo was not set.');
    }
}

?>
