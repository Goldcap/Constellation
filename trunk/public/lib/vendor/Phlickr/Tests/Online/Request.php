<?php

/**
 * Request Online Tests
 *
 * @version $Id: Request.php 523 2006-08-28 18:30:20Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Tests/constants.inc';
require_once 'Phlickr/Api.php';

class Phlickr_Tests_Online_Request extends PHPUnit2_Framework_TestCase {
    var $api;
    var $reqValid, $reqInvalid;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET);
        $this->reqValid = new Phlickr_Request($this->api, 'flickr.test.echo');
        $this->reqInvalid = new Phlickr_Request($this->api, 'NONEXISTANT_METHOD');
    }
    function tearDown() {
        unset($this->reqValid);
        unset($this->reqInvalid);
        unset($this->api);
    }

    function testExecute_FailsWithInvalidMethod() {
        try {
            $this->reqInvalid->setExceptionThrownOnFailure(false);
            $result = $this->reqInvalid->execute();
        } catch (Phlickr_Exception $ex) {
            $this->Fail('Should not have thrown an exception');
        }
        $this->assertEquals(array(), $this->reqInvalid->getParams());
        $this->assertNotNull($result, 'Execute returned null.');
        $this->assertType('Phlickr_Response', $result, 'Returned the wrong type.');
        $this->assertEquals(Phlickr_Response::STAT_FAIL, strval($result->stat), 'Status should be failed.');
    }

    function testExecute_WorksWithValid() {
        $result = $this->reqValid->execute();
        $this->assertEquals(array(), $this->reqValid->getParams());
        $this->assertNotNull($result, 'Execute returned null.');
        $this->assertType('Phlickr_Response', $result, 'Returned the wrong type.');
        $this->assertEquals(Phlickr_Response::STAT_OK, strval($result->stat), 'Expected ok, error: ' . $result->err_msg);
    }

    function testExecute_AddsToCache() {
        $url = $this->reqValid->buildUrl();
        $result = $this->reqValid->execute();
        $cache =& $this->api->getCache();

        $this->assertTrue($cache->has($url), "URL should have been cached.");
        $actual = new Phlickr_Response($cache->get($url));
        $this->assertEquals($result->xml, $actual->xml,
            "Cache value didn't match what the Request returned. ");
    }

    function testExecute_ThrowsWithBadUrl() {
        $this->api->setEndpointUrl('http://example.com/BAD/');
        $req = new Phlickr_Request($this->api, 'flickr.test.echo');
        try {
            $result = $req->execute();
        } catch (Phlickr_ConnectionException $ex){
            $this->assertEquals(array(), $this->reqInvalid->getParams());
            return;
        } catch (Exception $ex){
            $this->fail('threw the wrong type ('.get_class($ex).') of exception.');
        }
        $this->Fail('An exception should have been thrown.');
    }
}
