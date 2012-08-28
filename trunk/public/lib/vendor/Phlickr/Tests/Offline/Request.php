<?php

/**
 * Request Offline Tests
 *
 * @version $Id: Request.php 494 2005-11-26 10:03:16Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Tests/constants.inc';
require_once 'Phlickr/Api.php';

class Phlickr_Tests_Offline_Request extends PHPUnit2_Framework_TestCase {
    var $api;
    var $reqValid, $reqInvalid;

    function __construct($name) {
        parent::__construct($name);
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET);
    }
    function setUp() {
        $this->reqValid = new Phlickr_Request($this->api, 'flickr.test.echo');
        $this->reqInvalid = new Phlickr_Request($this->api, 'NONEXISTANT_METHOD');
    }
    function tearDown() {
        unset($this->reqValid);
        unset($this->reqInvalid);
    }

    function testConstructor_AssignsApi() {
        $this->assertSame($this->api, $this->reqValid->getApi());
        $this->assertSame($this->api, $this->reqInvalid->getApi());
    }
    function testConstructor_AssignsMethod() {
        $this->assertEquals('flickr.test.echo', $this->reqValid->getMethod());
        $this->assertEquals('NONEXISTANT_METHOD', $this->reqInvalid->getMethod());
    }
    function testConstructor_AssignsIsExceptionThrownOnFailure() {
        $this->assertEquals(true, $this->reqValid->isExceptionThrownOnFailure());
        $this->assertEquals(true, $this->reqInvalid->isExceptionThrownOnFailure());
    }
    function testConstructor_AssignsParamsToEmptyArrayByDefault() {
        $this->assertEquals(array(), $this->reqValid->getParams());
        $this->assertEquals(array(), $this->reqInvalid->getParams());
    }
    function testConstructor_AssignsParamsWhenSpecified() {
        $params = array('foo'=>'bar', 'baz'=>'faz');
        $request = new Phlickr_Request($this->api,'NOT_A_REAL_METHOD', $params);
        $this->assertEquals($params, $request->getParams());
    }
    function testConstructor_AssignsParamsInSpiteOfNull() {
        $params = null;
        $request = new Phlickr_Request($this->api,'NOT_A_REAL_METHOD', $params);
        $this->assertEquals(array(), $request->getParams());
    }

    function testBuildSignedUrl_NoParams() {
        $url = $this->reqValid->buildUrl();
        $this->assertEquals('http://flickr.com/services/rest/?api_key=' . TESTING_API_KEY
            . '&method=flickr.test.echo&api_sig=32896da7a980b3456007f8646f3e0643', $url);
    }
    function testBuildSignedUrl_WithParams() {
        $this->reqValid->setParams(array('foo'=>'bar'));
        $url = $this->reqValid->buildUrl();
        $this->assertEquals('http://flickr.com/services/rest/?api_key=' . TESTING_API_KEY .
            '&foo=bar&method=flickr.test.echo&api_sig=d8a63992dffa6a192fc0bd263659d5e5', $url);
    }
    function testBuildSignedUrl_UseApiParams() {
        $params = array(
            'api_key' => TESTING_API_KEY,
            'desc' => 'A "quoted" string'
        );
        $result = Phlickr_Request::signParams('secret', $params);
        $this->assertEquals('api_key=' . TESTING_API_KEY
            . '&desc=A+%22quoted%22+string&api_sig=e1a598d192667408953c6c22668d7216', $result);
    }


    function testGetParams_UseReferences() {
        $this->reqValid->setParams(array());
        $this->assertEquals(array(), $this->reqValid->getParams());
        $result =& $this->reqValid->getParams();
        $result['foo'] = 'bar';
        $this->assertEquals(array('foo'=>'bar'), $this->reqValid->getParams());
    }


    function testSetParams_ToValue() {
        $anArray = array('name'=>'testing', 'foo'=>'bar');
        $this->reqValid->setParams($anArray);
        $this->assertEquals($anArray, $this->reqValid->getParams());
    }
    function testSetParams_ToEmptyArray() {
        $this->reqValid->setParams(array());
        $this->assertEquals(array(), $this->reqValid->getParams());
    }
    function testSetParams_ToNull() {
        $this->reqValid->setParams(null);
        $this->assertEquals(array(), $this->reqValid->getParams());
    }


    function testExecute_ThrowsWhenThrowOnErrorSpecified() {
        try {
            $this->reqInvalid->setExceptionThrownOnFailure(true);
            $result = $this->reqInvalid->execute();
        } catch (Phlickr_Exception $ex) {
            $this->assertEquals(array(), $this->reqInvalid->getParams());
            return;
        } catch (Exception $ex) {
            $this->Fail('threw wrong type ('. get_class($ex) . ') of exception.');
            return;
        }
        $this->Fail('did not throw an exception');
    }
    function testExecute_FromCache() {
        $xml = TESTING_RESP_OK_PREFIX . TESTING_XML_ECHO . TESTING_RESP_SUFIX;
        $request = new Phlickr_Request(
            $this->api,
            'NOT_A_REAL_METHOD',
            array('foo' => 'bar')
        );
        $this->api->getCache()->set($request->buildUrl(), $xml);

        $expected = new Phlickr_Response($xml);
        $actual = $request->execute(true);

        $this->assertEquals($expected->xml, $actual->xml,
            "Returned response didn't match the exepect cache value");
    }
}

?>
