<?php

/**
 * PhotoList Offline Tests
 *
 * @version $Id: PhotoListIterator.php 494 2005-11-26 10:03:16Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/PhotoListIterator.php';
require_once 'Phlickr/Tests/Mocks/PhotoListRequest.php';

class Phlickr_Tests_Offline_PhotoListIterator extends PHPUnit2_Framework_TestCase {
    var $api, $pl, $request, $iterator;
    var $totalPhotos, $perPage;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET, TESTING_API_TOKEN);
        $this->api->setEndpointUrl('http://example.com');

        $this->totalPhotos = 31;
        $this->perPage = 10;
        $this->request = new Phlickr_Tests_Mocks_PhotoListRequest($this->api,
            $this->totalPhotos, $this->perPage);
        $this->pl = new Phlickr_PhotoList($this->request, $this->perPage);
        $this->iterator =  new Phlickr_PhotoListIterator($this->pl);
    }
    function tearDown() {
        unset($this->totalPhotos);
        unset($this->perPage);
        unset($this->pl);
        unset($this->request);
        unset($this->api);
    }

    function testConstructor_AssignsKey() {
        $this->assertEquals(1, $this->iterator->key());
    }
    function testConstructor_AssignsPhotolist() {
        $this->assertEquals($this->pl, $this->iterator->getPhotoList());
    }
    function testConstructor() {
        $this->assertTrue($this->iterator instanceof Iterator);
        $this->assertTrue($this->iterator instanceof Phlickr_Framework_IList);
    }

    function testKey() {
        $this->assertEquals(1, $this->iterator->key());

        $this->iterator->next();
        $this->assertEquals(2, $this->iterator->key());

        $this->iterator->next();
        $this->assertEquals(3, $this->iterator->key());
    }

    function testValid() {
        // there should be 4 pages...
        // page 1
        $this->assertTrue($this->iterator->valid());
        // page 2
        $this->iterator->next();
        $this->assertTrue($this->iterator->valid());
        // page 3
        $this->iterator->next();
        $this->assertTrue($this->iterator->valid());
        // page 4
        $this->iterator->next();
        $this->assertTrue($this->iterator->valid());
        // page 5 (invalid)
        $this->iterator->next();
        $this->assertFalse($this->iterator->valid());
        // page 6 (invalid)
        $this->iterator->next();
        $this->assertFalse($this->iterator->valid());
    }

    function testCurrent() {
        $result = $this->iterator->current();
        $this->assertType('array', $result);

        $this->iterator->next();
        $result = $this->iterator->current();
        $this->assertType('array', $result);

        $this->iterator->next();
        $result = $this->iterator->current();
        $this->assertType('array', $result);
    }

    function testRewind() {
        $this->iterator->rewind();
        $this->assertTrue($this->iterator->valid());
        $this->assertEquals(1, $this->iterator->key());

        $this->iterator->next();
        $this->iterator->next();
        $this->iterator->next();
        $this->iterator->rewind();
        $this->assertTrue($this->iterator->valid());
        $this->assertEquals(1, $this->iterator->key());
    }

    function testEverything() {
        $expectedIds = array();
        $actualIds = array();

        // try it the old fashion way
        for ($page = 1; $page < $this->pl->getPageCount() + 1; $page++) {
            $this->pl->setPage($page);

            $ids = $this->pl->getIds();
            foreach ($ids as $id) {
                array_push($expectedIds, $id);
            }
        }

        // use the iterator
        foreach($this->iterator as $photos) {
            $this->assertType('array', $photos, 'Did not return an array.');
            foreach ($photos as $photo) {
                $this->assertType('Phlickr_Photo', $photo);
                array_push($actualIds, $photo->getId());
            }
        }

        $this->assertEquals($expectedIds, $actualIds);
    }

    function testGetAllIds() {
        $results = $this->iterator->getIds();
        for ($i = 1; $i <= 31; $i++) {
            $expected[] = (string) $i;
        }
        $this->assertType('array', $results);
        $this->assertEquals($expected, $results);
    }

    function testGetCount() {
        $result = $this->iterator->getCount();
        $this->assertEquals($this->totalPhotos, $result);
    }

    function testGetAllPhotos() {
        $photos = $this->iterator->getPhotos();
        $expected = range(1,31);
        $this->assertType('array', $photos);
        for ($i=0; $i<count($photos); $i++) {
            $this->assertType('Phlickr_Photo', $photos[$i]);
            $this->assertEquals((string) $expected[$i], $photos[$i]->getId());
        }
    }
}

?>
