<?php

/**
 * AuthedPhotsetList Online Tests
 *
 * @version $Id: AuthedPhotosetList.php 519 2006-04-24 06:10:30Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Tests/constants.inc';
require_once 'Phlickr/AuthedPhotosetList.php';

class Phlickr_Tests_Online_AuthedPhotosetList extends PHPUnit2_Framework_TestCase {
    var $api;
    var $psl;

    function deleteExtraSets() {
        // delete every set but those in keep id
        $ids = $this->psl->getIds();
        $ids = array_diff($ids, array(TESTING_REAL_PHOTOSET_ID1, TESTING_REAL_PHOTOSET_ID2));
        foreach ($ids as $id) {
            print "\nfound an extra photoset deleting it.\n";
            $this->psl->delete($id);
        }
    }

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET, TESTING_API_TOKEN);
        $this->psl = new Phlickr_AuthedPhotosetList($this->api);
    }
    function tearDown() {
        unset($this->psl);
        unset($this->api);
    }

    function testGetIds() {
        $result = $this->psl->getIds();
        $this->assertTrue(is_array($result), 'Response should be an an array.');
    }

    function testGetPhotosets() {
        $result = $this->psl->getPhotosets();
        $this->assertTrue(is_array($result), 'Response should be an an array.');
        foreach ($result as $o) {
            $this->assertType('Phlickr_AuthedPhotoset', $o,
                'Should have returned an array of AuthedPhotosets.');
        }
    }

    function testDelete() {
        // verify that we can create
        $count = $this->psl->getCount();
        if ($count > 2) {
            $this->deleteExtraSets();
        }

        // create something to delete
        $id = $this->psl->create('delete me', 'a description', TESTING_REAL_PHOTO_ID_JPG);
        $this->assertTrue(strlen($id) > 0, 'Should have returned string with some length.');

        sleep(3);

        // drop it
        $count = $this->psl->getCount();
        $result = $this->psl->delete($id);
        $this->assertEquals($count - 1, $this->psl->getCount(), 'number of photosets did not decrease');
    }

    function testDelete_FailsWithInvalidId() {
        try {
            $result = $this->psl->delete(1);
        } catch (Phlickr_MethodFailureException $ex) {
            return;
        } catch (Exception $ex) {
            $this->fail('threw the wrong type ('.get_class($ex).') of exception.');
        }
        $this->assertFalse($result, 'Deletion should have failed');
    }

    function testCreate_WithValidInfo() {
        // verify that we can create
        $count = $this->psl->getCount();
        if ($count > 2) {
            $this->deleteExtraSets();
        }

        // create it
        $result = $this->psl->create('test creation', 'delete me!', TESTING_REAL_PHOTO_ID_JPG);
        $this->assertType('string', $result, 'Returned the wrong type.');

        sleep(3);

        // instantiate the object and check it out
        $ps = new Phlickr_AuthedPhotoset($this->api, $result);
        $this->assertNotNull($ps->getId(), 'Id was not set');
        $this->assertEquals(TESTING_REAL_PHOTO_ID_JPG, $ps->getPrimaryId(), 'Primary photo was not set.');
        $this->assertEquals('test creation', $ps->getTitle());
        $this->assertEquals('delete me!', $ps->getDescription());
        $this->assertEquals($count + 1, $this->psl->getCount(), 'number of photosets did not increase');

        // clean it up
        $this->psl->delete($result);
    }

    function testCreate_FailsWithInvalidPhotoId() {
        // verify that we can create
        $count = $this->psl->getCount();
        if ($count > 2) {
            $this->deleteExtraSets();
        }

        try {
            $result = $this->psl->create('creation should fail', 'created to delete', 1);
        } catch (Phlickr_Exception $ex) {
            return;
        } catch (Exception $ex) {
            $this->fail('threw the wrong type ('.get_class($ex).') of exception.');
        }
        $this->assertTrue($result == false, 'Creation should have failed');
    }

    function testReorder() {
        $expected = $this->psl->getIds();

        // set the order one way
        sort($expected);
        $result = $this->psl->reorder($expected);
        $this->assertEquals($expected, $this->psl->getIds());

        // then the other
        rsort($expected);
        $result = $this->psl->reorder($expected);
        $this->assertEquals($expected, $this->psl->getIds());
    }
}

