<?php

/**
 * Response Offline Tests
 *
 * @version $Id: Response.php 357 2005-07-19 00:33:56Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Tests/constants.inc';
require_once 'Phlickr/Response.php';

class Phlickr_Tests_Offline_Response extends PHPUnit2_Framework_TestCase {
    var $resp_ok, $resp_fail;

    function setUp() {
        $this->resp_ok = new Phlickr_Response(TESTING_RESP_OK);
        $this->resp_fail = new Phlickr_Response(TESTING_RESP_FAIL);
    }
    function tearDown() {
        unset($this->resp_ok);
        unset($this->resp_fail);
    }

    function testConstructor_ThrowsWithInvalidXml() {
        try {
            $resp = new Phlickr_Response(TESTING_RESP_INVALID);
        } catch (Phlickr_XmlParseException $ex) {
            return;
        } catch (Exception $ex) {
            $this->Fail('threw wrong type of exception.');
            return;
        }
        $this->Fail('did not throw an exception');
    }

    function testConstructor_ThrowsWhenThrowOnErrorSpecified() {
        try {
            $resp = new Phlickr_Response(TESTING_RESP_FAIL, true);
        } catch (Phlickr_MethodFailureException $ex) {
            return;
        } catch (Exception $ex) {
            $this->Fail('threw wrong type ('. get_class($ex) . ') of exception.');
            return;
        }
        $this->Fail('did not throw an exception');
    }


    function testConstructor_WorksWithOkResponse() {
        $this->assertEquals(Phlickr_Response::STAT_OK, $this->resp_ok->stat, 'compare status.');
        $this->assertNull($this->resp_ok->err_code, 'checking error code is null');
        $this->assertNull($this->resp_ok->err_msg, 'checking error message is null');
        $this->assertType('SimpleXMLElement', $this->resp_ok->xml, 'XML is wrong type');
        $this->assertNotNull($this->resp_ok->xml, 'checking xml is set');
        $this->assertEquals((string) $this->resp_ok->xml->method[0], 'flickr.test.echo');
    }

    function testConstructor_WorksWithFailResponse() {
        $this->assertEquals(Phlickr_Response::STAT_FAIL, $this->resp_fail->stat, 'compare status.');
        $this->assertEquals(1, $this->resp_fail->err_code, 'compare error code.');
        $this->assertEquals('Photo not found', $this->resp_fail->err_msg, 'compare error message');
        $this->assertNull($this->resp_fail->xml, 'checking xml is null');
    }

    function testIsOkay() {
        $this->assertTrue($this->resp_ok->isOk());
        $this->assertFalse($this->resp_fail->isOk());
    }

    function testGetXml() {
        $this->assertType('SimpleXMLElement', $this->resp_ok->getXml());
        $this->assertNull($this->resp_fail->getXml());
    }
}

?>
