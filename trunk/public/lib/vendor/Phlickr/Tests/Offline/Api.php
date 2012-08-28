<?php

/**
 * Api Offline Tests
 *
 * @version $Id: Api.php 514 2006-02-05 23:38:23Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Api.php';
require_once 'Phlickr/Tests/constants.inc';

class Phlickr_Tests_Offline_Api extends PHPUnit2_Framework_TestCase {
    var $api;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET);
    }
    function tearDown() {
        unset($this->api);
    }

    function testConstructor_NoAuth() {
        $ret = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET);
        $this->assertEquals(TESTING_API_KEY, $ret->getKey());
        $this->assertEquals(TESTING_API_SECRET, $ret->getSecret());
        $this->assertNull($ret->getAuthToken());
    }
    function testConstructor_WithAuth() {
        $ret = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET, TESTING_API_TOKEN);
        $this->assertEquals(TESTING_API_KEY, $ret->getKey());
        $this->assertEquals(TESTING_API_SECRET, $ret->getSecret());
        $this->assertEquals(TESTING_API_TOKEN, $ret->getAuthToken());
    }
    function testConstructor_ThrowsOnMissingKey() {
        try {
            $api = new Phlickr_Api(null, TESTING_API_SECRET);
        } catch (Exception $ex) {
            return;
        }
        $this->fail('an exception should have been thrown');
    }
    function testConstructor_ThrowsOnMissingSecret() {
        try {
            $ret = new Phlickr_Api(TESTING_API_KEY, null);
        } catch (Exception $ex) {
            return;
        }
        $this->fail('an exception should have been thrown');
    }
    function testConstructor_AssignsCache() {
        $this->assertNotNull($this->api->getCache());
        $this->assertType('Phlickr_Cache', $this->api->getCache());
    }
    function testConstructor_AssignsCacheFilename() {
        $this->assertEquals('', $this->api->getCacheFilename());
    }
    function testConstructor_AssignsEndpoint() {
        $this->assertEquals(Phlickr_Api::REST_ENDPOINT_URL, $this->api->getEndpointUrl());
    }
    function testConstructor_AssignsSecret() {
        $this->assertEquals(TESTING_API_SECRET, $this->api->getSecret());
    }


    function testSaveAs() {
        $filename = tempnam('/tmp', 'foo');

        $this->api->saveAs($filename);
        $this->assertTrue(file_exists($filename), "saveAs did not create a file.");
        try {
            $api = Phlickr_Api::createFrom($filename);
        } catch (Phlickr_Exception $ex) {
            $this->fail('the save was probably bad, createFrom() barfed on the file.');
        }
        unlink($filename);
        $this->assertType('Phlickr_Api', $api);
        $this->assertEquals($this->api->getKey(), $api->getKey(), 'key was not loaded correctly.');
        $this->assertEquals($this->api->getSecret(), $api->getSecret(), 'secret was not loaded correctly.');
        $this->assertEquals($this->api->getAuthToken(), $api->getAuthToken(), 'token was not loaded correctly.');
        $this->assertEquals($this->api->getCacheFilename(), $api->getCacheFilename(), 'cachefilename was not loaded correctly.');
    }


    function testCreateFrom_MissingFile() {
        try {
            $api = Phlickr_Api::createFrom('MISSINGFILE.XYZ');
        } catch (Phlickr_Exception $ex) {
            return;
        }
        $this->fail('should have thrown an exception');
    }
    function testCreateFrom_MinimalSettings() {
        $filename = tempnam('/tmp', 'foo');
        $settings = "api_key=key01234\n" .
                    "api_secret=a-secret\n";
        file_put_contents($filename, $settings);

        $api = Phlickr_Api::createFrom($filename);
        unlink($filename);

        $this->assertType('Phlickr_Api', $api);
        $this->assertEquals('key01234', $api->getKey(), 'key was not loaded correctly.');
        $this->assertEquals('a-secret', $api->getSecret(), 'secret was not loaded correctly.');
        $this->assertNull($api->getAuthToken(), 'token was not loaded correctly.');
        $this->assertEquals('', $api->getCacheFilename(), 'cache filename was not loaded correctly.');
    }
    function testCreateFrom_AllSettings() {
        $filename = tempnam('/tmp', 'foo');
        $settings = "api_key=key\n" .
                    "api_secret=secret\n" .
                    "api_token=token\n" .
                    "cache_file=c:\filename\n";
        file_put_contents($filename, $settings);

        $api = Phlickr_Api::createFrom($filename);
        unlink($filename);

        $this->assertType('Phlickr_Api', $api);
        $this->assertEquals('key', $api->getKey(), 'key was not loaded correctly.');
        $this->assertEquals('secret', $api->getSecret(), 'secret was not loaded correctly.');
        $this->assertEquals('token', $api->getAuthToken(), 'token was not loaded correctly.');
        $this->assertEquals('c:\filename', $api->getCacheFilename(), 'cache filename was not loaded correctly.');
    }


    function testBuildAuthUrl_WithFrob() {
        $result = $this->api->buildAuthUrl('read', '123456');
        $this->assertEquals('http://flickr.com/services/auth/?api_key='
            . TESTING_API_KEY
            . '&frob=123456&perms=read&api_sig=ae0b23d8b4748a7eb9829f3c061d1f6e'
            , $result);
    }
    function testBuildAuthUrl_WithoutFrob() {
        $result = $this->api->buildAuthUrl('delete');
        $this->assertEquals('http://flickr.com/services/auth/?api_key='
            . TESTING_API_KEY
            . '&perms=delete&api_sig=fea66633593d134ff0d5c57ebab63658', $result);
    }

    function testIsAuthValid() {
        // ... first the login details (so it can figure out the user id)
        $this->api->addResponseToCache(
            'flickr.auth.checkToken',
            $this->api->getParamsForRequest(),
            TESTING_RESP_OK_PREFIX . TESTING_XML_CHECKTOKEN . TESTING_RESP_SUFIX
        );
        $this->assertTrue($this->api->IsAuthValid());
    }

    function testGetUserId_WithAuth() {
        $api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET, TESTING_API_TOKEN);
        // ... first the login details (so it can figure out the user id)
        $api->addResponseToCache(
            'flickr.auth.checkToken',
            $this->api->getParamsForRequest(),
            TESTING_RESP_OK_PREFIX . TESTING_XML_CHECKTOKEN . TESTING_RESP_SUFIX
        );
        $this->assertEquals(TESTING_USER_ID, $api->getUserId());
    }
    function testGetUserId_NoAuth() {
        $this->assertNull($this->api->getUserId());
    }

    function testSetGetCache() {
        $c = new Phlickr_Cache();
        $this->api->setCache($c);
        $this->assertSame($c, $this->api->getCache());
    }


    function testSetGetCacheFilename_Null() {
        $cache = $this->api->getCache();
        $this->api->setCacheFilename(null);
        $this->assertEquals('', $this->api->getCacheFilename(),
            'the null should have been converted to a empty string.');
        $this->assertSame($cache, $this->api->getCache(),
            'the cache file should not have changed');
    }
    function testSetGetCacheFilename_NewFile() {
        $oldcache = $this->api->getCache();
        $filename = tempnam('/tmp', 'foo');
        unlink($filename);
        $this->assertFalse(file_exists($filename), 'the file should not exist');

        $this->api->setCacheFilename($filename);
        $this->assertEquals($filename, $this->api->getCacheFilename());
        $this->assertSame($oldcache, $this->api->getCache(),
            'since the file does not exist the cache should not change.');
    }
    function testSetCacheFilename_ExistingFile() {
        $oldcache = $this->api->getCache();

        $filename = tempnam('/tmp', 'foo');
        $cache = new Phlickr_Cache();
        $cache->set('a key', 'value');
        $cache->saveAs($filename);
        unset($cache);

        $this->api->setCacheFilename($filename);
        $this->assertEquals($filename, $this->api->getCacheFilename());
        $cache = $this->api->getCache();
        $this->assertNotSame($oldcache, $cache);
        $this->assertTrue($cache->has('a key'));
    }
    function testSetCacheFilename_DestructorSaves() {
        $filename = tempnam('/tmp', 'foo');
        // create some cache data.
        $cache = new Phlickr_Cache();
        $cache->set('a key', 'value');
        $cache->saveAs($filename);
        unset($cache);
        // load the cache and then delete the cache file
        $this->api->setCacheFilename($filename);
        unlink($filename);
        $this->assertFalse(file_exists($filename), 'the file should not exist');

        // unsetting the api should call the destructor and writeout the file.
        unset($this->api);
        $this->assertTrue(file_exists($filename), 'the file should exist');
        $cache = Phlickr_Cache::createFrom($filename);
        $this->assertType('Phlickr_Cache', $cache);
        $this->assertTrue($cache->has('a key'), 'value should exist');
    }


    function testAddResponseToCache() {
        $cache =& $this->api->getCache();

        $request = $this->api->createRequest('method', array('foo'=>'bar'));
        $url = $request->buildUrl();

        $this->assertFalse($cache->has($url), 'it should not already be in the cache.');
        $this->assertNull($cache->get($url), 'the cache should be empty, so it should return null.');

        $this->api->addResponseToCache(
            $request->getMethod(),
            $request->getParams(),
            TESTING_RESP_OK_PREFIX . TESTING_XML_ECHO . TESTING_RESP_SUFIX
        );

        $this->assertTrue($cache->has($url), 'it should be in the cache.');
        $this->assertEquals(TESTING_RESP_OK_PREFIX . TESTING_XML_ECHO . TESTING_RESP_SUFIX,
            $cache->get($url), 'it should return it now.'
        );
    }


    function testSetLogin_SetsLoginInfo() {
        $result = $this->api->setAuthToken(TESTING_API_TOKEN);
        $this->assertEquals(TESTING_API_TOKEN, $this->api->getAuthToken(),
            'Token was not set.');
        $result = $this->api->setAuthToken('');
        $this->assertEquals('', $this->api->getAuthToken(),
            'Token was not set.');
    }


    function testGetSetEndpointUrl_ToSomething() {
        $url = 'http://example.com/';
        $this->api->setEndpointUrl($url);
        $this->assertEquals($url, $this->api->getEndpointUrl());
    }


    function testGetParamsForRequest_WorksWithoutUserInfo() {
        // with no auth
        $params = $this->api->getParamsForRequest();
        $this->assertTrue(is_array($params));
        $this->assertEquals(array('api_key' => $this->api->getKey()), $params);
    }
    function testGetParamsForRequest_WorksWithUserInfo() {
        // with just an email and password
        $this->api->setAuthToken(TESTING_API_TOKEN);
        $params = $this->api->getParamsForRequest();
        $this->assertTrue(is_array($params));
        $this->assertEquals(
            array('api_key'=>$this->api->getKey(),
                'auth_token' => TESTING_API_TOKEN),
            $params
        );
    }


    function testCreateRequest_ReturnsCorrectClass() {
        $request = $this->api->CreateRequest('amethod');
        $this->assertType('Phlickr_Request', $request, 'Returned the wrong type.');
    }
    function testCreateRequest_ReturnsRequestWithMethod() {
        $request = $this->api->CreateRequest('amethod');
        $this->assertEquals('amethod', $request->getMethod(), 'return a Request with the wrong method');
    }
    function testCreateRequest_ReturnsRequestWithApi() {
        $request = $this->api->CreateRequest('amethod');
        $this->assertSame($this->api, $request->getApi(), 'returned a Request with a different API');
    }
}

?>
