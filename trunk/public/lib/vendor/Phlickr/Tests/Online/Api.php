<?php

/**
 * Api Tests
 *
 * @version $Id: Api.php 355 2005-07-19 00:29:09Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Api.php';
require_once 'Phlickr/Tests/constants.inc';

class Phlickr_Tests_Online_Api extends PHPUnit2_Framework_TestCase {
    var $api;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET);
    }
    function tearDown() {
        unset($this->api);
    }

    function testGetFrob() {
        $result = $this->api->requestFrob();
        $this->assertType('string', $result);
    }

    function testGetUserId_WorksWithValidToken() {
        $this->api->setAuthToken(TESTING_API_TOKEN);
        $result = $this->api->getUserId();
        $this->assertEquals(TESTING_USER_ID, $result, 'Verify user\'s id.');
    }
    function testGetUserId_ReturnsEmptyWithInvalidToken() {
        $this->api->setAuthToken('BAD_TOKEN');
        $this->assertFalse($this->api->isAuthValid(), 'Provided logon should fail.');
        $result = $this->api->getUserId();
        $this->assertNull($result, 'Bad logon should return null UserID.');
    }

    function testIsValidUser_FailsWithInvalidToken() {
        $this->api->setAuthToken('BAD_TOKEN');
        $this->assertFalse($this->api->isAuthValid());
    }
    function testIsValidUser_WorksWithValidUser() {
        $this->api->setAuthToken(TESTING_API_TOKEN);
        $this->assertTrue($this->api->isAuthValid());
    }

    function testExecuteMethod_WorksWithNoParams() {
        $response = $this->api->ExecuteMethod('flickr.test.echo');
        $this->assertType('Phlickr_Response', $response, 'Returned the wrong type.');
        $this->assertTrue($response->isOk());
    }
    function testExecuteMethod_WorksWithParams() {
        $response = $this->api->ExecuteMethod('flickr.test.echo', array('name' => 'tester'));
        $this->assertType('Phlickr_Response', $response, 'Returned the wrong type.');
        $this->assertTrue($response->isOk());
    }
    function testExecuteMethod_ThrowsExceptionWhenThrowOnErrorSpecified() {
        $api = new Phlickr_Api('INVALID_KEY_FOR_TESTING', 'INVALID_SECRET');
        try {
            $api->executeMethod('flickr.test.echo', array());
        } catch (Phlickr_Exception $ex){
            $this->assertEquals('Invalid API Key (Key not found)', $ex->getMessage());
            $this->assertEquals(100, $ex->getCode());
            return;
        } catch (Exception $ex){
            $this->fail('threw the wrong type ('.get_class($ex).') of exception.');
        }
        $this->Fail('An exception should have been thrown.');
    }
}

?>
