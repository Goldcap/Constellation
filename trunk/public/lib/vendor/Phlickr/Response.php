<?php

/**
 * @version $Id: Response.php 500 2006-01-03 23:29:08Z drewish $
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 * @package Phlickr
 */

/**
 * Phlickr_Api includes the core classes.
 */
require_once 'Phlickr/Api.php';

/**
 * Phlickr_Response handles the XML returned by a Phlickr_Request.
 *
 * Sample usage:
 * <code>
 * <?php
 * include_once 'Phlickr/Api.php';
 *
 * // store a sample response into a variable
 * $xmlResponse = <<<XML
 * <//?xml version="1.0" encoding="utf-8" //?//>
 *  <rsp stat="ok">
 *      <user id="39059360@N00">
 *          <username>just testing</username>
 *      </user>
 *  </rsp>
 * XML;
 *
 * // instantiate the object
 * $response = new Phlickr_Response($xmlResponse);
 *
 * // was the request successful?
 * print $response->isOk();
 *
 * // view the response (using its __toString() function)
 * print $response;
 * //?//>
 * </code>
 *
 * This class is responsible for:
 * - Converting the XML string returned by a Phlickr_Request object into a
 *   SimpleXML object.
 * - Determining the success or failure of the request.
 *
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @since   0.1.0
 */
class Phlickr_Response {
    /**
     * The Request was sent and the server responded with a valid Response.
     * This constant is defined by Flickr's API.
     *
     * @var string
     */
    const STAT_OK = 'ok';
    /**
     * The Request was sent but the server found a problem with the Request.
     * This constant is defined by Flickr's API.
     *
     * @var string
     */
    const STAT_FAIL = 'fail';

    /**
     * XML payload of the Response.
     *
     * @var object SimpleXMLElement
     */
    var $xml = null;
    /**
     * Status of the Reponse.
     *
     * @var string
     * @see STAT_OK, STAT_FAIL
     */
    var $stat = null;
    /**
     * Error code.
     * This variable is only assigned when !$this->isOk()
     *
     * @var integer
     */
    var $err_code = null;
    /**
     * Error message.
     * This variable is only assigned when !$this->isOk()
     *
     * @var string
     * @access public
     */
    var $err_msg = null;

    /**
     * Constructor takes output from the http request
     *
     * @param string $restResult XML string from a Flickr_Request object.
     * @param boolean $throwOnFailed Should an exception be thrown when the
     *      response indicates failure?
     * @throws Phlickr_XmlParseException, Phlickr_Exception
     */
    function __construct($restResult, $throwOnFailed = false) {
        $xml = simplexml_load_string($restResult);
        
        if (false === $xml) {
            //throw new Phlickr_XmlParseException('Could not parse XML.', $restResult);
            return false;
        }

        $this->stat = (string) $xml['stat'];
        if ($this->isOk()) {
            $this->xml = $xml;
        } else {
            $this->err_code = (integer) $xml->err['code'];
            $this->err_msg = (string) $xml->err['msg'];
            
            if ($throwOnFailed) {
                throw new Phlickr_MethodFailureException($this->err_msg, $this->err_code);
            }
        }
    }

    public function __toString() {
       if ($this -> xml) {
        return $this->xml->asXML();
      }
    }

    /**
     * Check if the Response is successful
     *
     * @return boolean
     */
    public function isOk() {
        return ($this->stat == self::STAT_OK);
    }

    /**
     * Get the XML Object.
     *
     * @return  object SimpleXML
     * @see     SimpleXML::asXML()
     * @since   0.2.3
     */
    public function getXml() {
        return $this->xml;
    }
}
