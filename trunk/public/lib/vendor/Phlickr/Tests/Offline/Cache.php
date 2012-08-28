<?php

/**
 * Cache Offline Tests
 *
 * @version $Id: Cache.php 377 2005-08-14 01:26:32Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Tests/constants.inc';
require_once 'Phlickr/Request.php';

class Phlickr_Tests_Offline_Cache extends PHPUnit2_Framework_TestCase {
    var $cache;

    const URL_A = 'http://flickr.com/services/rest/?api_key=11feb6fd0db850debccf2dc309dbc93a&email=testing%40drewish.com&password=testing&method=flickr.test.login';
    const URL_B = 'http://flickr.com/services/rest/?api_key=11feb6fd0db850debccf2dc309dbc93a&email=BADUSER&password=BAD+PASSWORD&method=flickr.test.login';

    function setUp() {
        $this->cache = new Phlickr_Cache();
    }
    function tearDown() {
        unset($this->cache);
    }

    function testConstructor_AssignsShelfLife_Default() {
        $this->assertEquals(Phlickr_Cache::DEFAULT_SHELF_LIFE,
            $this->cache->getShelfLife());
    }
    function testConstructor_AssignsShelfLife_SetToZero() {
        $cache = new Phlickr_Cache(0);
        $this->assertEquals(0, $cache->getShelfLife());
    }
    function testConstructor_AssignsShelfLife_SetToNegative() {
        $cache = new Phlickr_Cache(-99);
        $this->assertEquals(-99, $cache->getShelfLife());
    }

    function testShelfLifeConstant_IsOneHour() {
        //  1 hour = 60 sec * 60 minutes
        $this->assertEquals(60 * 60, Phlickr_Cache::DEFAULT_SHELF_LIFE);
    }
    function testSetShelfLife() {
        $this->assertEquals(Phlickr_Cache::DEFAULT_SHELF_LIFE,
            $this->cache->getShelfLife());
        $this->cache->setShelfLife(99);
        $this->assertEquals(99, $this->cache->getShelfLife());
    }
    function testGetShelfLife_Default() {
        $this->assertEquals(Phlickr_Cache::DEFAULT_SHELF_LIFE,
            $this->cache->getShelfLife());
    }


    function testGet_NonExistant() {
        $this->assertNull($this->cache->get(self::URL_A),
            'Getting from a cold cache should have returned null. ');
    }
    function testSetGet_Simple() {
        $this->cache->set(self::URL_B, 'response');
        $this->assertEquals('response', $this->cache->get(self::URL_B));
    }
    function testSetGet_Array() {
        $this->cache->set(self::URL_B, array('response1', 'response2'));
        $this->assertEquals(array('response1', 'response2'),
            $this->cache->get(self::URL_B));
    }
    function testSetGet_Overwrite() {
        $this->cache->set(self::URL_A, 'first response');
        $this->cache->set(self::URL_A, 'response');
        $this->assertEquals('response', $this->cache->get(self::URL_A));
    }
    function testSetGet_Multiple() {
        $this->cache->set(self::URL_A, 'first response');
        $this->cache->set(self::URL_B, 'second response');
        $this->assertEquals('first response', $this->cache->get(self::URL_A));
        $this->assertEquals('second response', $this->cache->get(self::URL_B));
    }


    function testSet_ZeroShelfLife() {
        $this->cache->set(self::URL_A, 'first response', 0);
        $this->assertFalse($this->cache->has(self::URL_A),
            "Entries with a 0 shelf life should not be cached. ");
    }
    function testSet_ZeroShelfLife_RemovesOld() {
        // set an entry
        $this->cache->set(self::URL_A, 'first response');
        $this->assertTrue($this->cache->has(self::URL_A));
        // then make sure a 0 shelf life removes it
        $this->cache->set(self::URL_A, 'second response', 0);
        $this->assertFalse($this->cache->has(self::URL_A),
            "Entries with a 0 shelf life should not be cached. ");
    }
    function testSet_NegativeShelfLife() {
        $this->cache->set(self::URL_A, 'response', -1);
        $this->assertTrue($this->cache->has(self::URL_A),
            "Entries with -1 shelf life should never expire. ");
    }
    function testSet_OneSecondShelfLife() {
        $this->cache->set(self::URL_A, 'response', 1);
        $this->assertTrue($this->cache->has(self::URL_A),
            'Just making sure its here.');
        sleep(2);
        $this->assertFalse($this->cache->has(self::URL_A),
            "After one second, the entry should expired. ");
    }
    function testSet_TwoSecondShelfLife() {
        $this->cache->set(self::URL_A, 'response', 2);
        $this->assertTrue($this->cache->has(self::URL_A),
            'Just making sure its here.');
        sleep(3);
        $this->assertFalse($this->cache->has(self::URL_A),
            "After two seconds, the entry should have expired. ");
    }

    function testHas_Missing() {
        $this->assertFalse($this->cache->has('url'), "cold cache shouldn't have this url. ");
    }
    function testHas_Exists() {
        $this->cache->set('url', 'first response');
        $this->assertTrue($this->cache->has('url'));
    }


    function testSerializing() {
        $this->cache->set(self::URL_A, 'first response');
        $this->cache->set(self::URL_B, 'second response');

        // serialize it and save it to a file
        $temp = tmpfile();
        fwrite($temp, serialize($this->cache));
        // read it from the file and unserialize it
        fseek($temp, 0);
        $unser = unserialize(fread($temp, 8096));
        fclose($temp);

        $this->assertType('Phlickr_Cache', $unser);
        $this->assertEquals('first response', $unser->get(self::URL_A));
        $this->assertEquals('second response', $unser->get(self::URL_B));
    }


    function testSaveAs() {
        $tempName = tempnam('/tmp', 'cache');

        $this->cache->set(self::URL_A, 'first response');
        $this->cache->set(self::URL_B, 'second response');

        // serialize it and save it to a file
        $this->cache->saveAs($tempName);

        $this->assertTrue(file_exists($tempName));

        // read it from the file and unserialize it
        $unser = unserialize(file_get_contents($tempName));

        unlink($tempName);

        $this->assertType('Phlickr_Cache', $unser);
        $this->assertEquals('first response', $unser->get(self::URL_A));
        $this->assertEquals('second response', $unser->get(self::URL_B));
    }


    function testCreateFrom_Goodfile() {
        $shelfLife = $this->cache->getShelfLife();
        $tempName = tempnam('/tmp', 'cache');
        $this->cache->set(self::URL_A, 'first response');
        $this->cache->set(self::URL_B, 'second response');
        $this->cache->saveAs($tempName);

        $cache = Phlickr_Cache::createFrom($tempName);

        unlink($tempName);

        $this->assertType('Phlickr_Cache', $cache);
        $this->assertEquals($shelfLife, $cache->getShelfLife());
        $this->assertEquals('first response', $cache->get(self::URL_A));
        $this->assertEquals('second response', $cache->get(self::URL_B));
    }

    function testCreateFrom_MissingFile_DefaultShelfLife() {
        $cache = Phlickr_Cache::createFrom('');

        $this->assertType('Phlickr_Cache', $cache);
        $this->assertEquals(Phlickr_Cache::DEFAULT_SHELF_LIFE,
            $cache->getShelfLife());
        $this->assertFalse($cache->has(self::URL_A));
        $this->assertFalse($cache->has(self::URL_B));
    }
    function testCreateFrom_MissingFile_AssignedShelfLife() {
        $cache = Phlickr_Cache::createFrom('', 999);

        $this->assertType('Phlickr_Cache', $cache);
        $this->assertEquals(999, $cache->getShelfLife());
        $this->assertFalse($cache->has(self::URL_A));
        $this->assertFalse($cache->has(self::URL_B));
    }

    function testCreateFrom_EmptyFile_DefaultShelfLife() {
        $tempName = tempnam('/tmp', 'cache');
        touch($tempName);

        $this->assertTrue(file_exists($tempName));
        $cache = Phlickr_Cache::createFrom($tempName);

        unlink($tempName);

        $this->assertType('Phlickr_Cache', $cache);
        $this->assertEquals(Phlickr_Cache::DEFAULT_SHELF_LIFE,
            $cache->getShelfLife());
        $this->assertFalse($cache->has(self::URL_A));
        $this->assertFalse($cache->has(self::URL_B));
    }
    function testCreateFrom_EmptyFile_AssignedShelfLife() {
        $tempName = tempnam('/tmp', 'cache');
        touch($tempName);

        $this->assertTrue(file_exists($tempName));
        $cache = Phlickr_Cache::createFrom($tempName, 999);

        unlink($tempName);

        $this->assertType('Phlickr_Cache', $cache);
        $this->assertEquals(999, $cache->getShelfLife());
        $this->assertFalse($cache->has(self::URL_A));
        $this->assertFalse($cache->has(self::URL_B));
    }
}

?>
