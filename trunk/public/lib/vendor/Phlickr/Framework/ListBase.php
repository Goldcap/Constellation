<?php

/**
 * @version $Id: ListBase.php 499 2006-01-03 22:35:52Z drewish $
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 * @package Phlickr
 */

/**
 * This class implements the IList interface.
 */
require_once 'Phlickr/Framework/IList.php';

/**
 * A base class for the Phlickr lists that wrap XML returned by API calls.
 *
 * This class provide default implementations for all its functions. You can
 * probably obtain better performance by overriding them.
 *
 * @author      Andrew Morton <drewish@katherinehouse.com>
 * @package     Phlickr
 */
abstract class Phlickr_Framework_ListBase implements Phlickr_Framework_IList {
    /**
     * Request the PhotoList is based on.
     *
     * @var object Phlickr_Request
     */
    protected $_request = null;
    /**
     * XML from Flickr.
     *
     * @var object SimpleXMLElement
     */
    protected $_cachedXml = null;
    /**
     * The name of the XML element in the response that defines this list.
     *
     * @var string
     */
    protected $_respListElement;
    /**
     * The name of the XML element in the response that defines this list's
     * members.
     *
     * @var string
     */
    protected $_respElement;

    /**
     * Constructor.
     *
     * @param   object Phlickr_Request $request
     * @param   string $responseElement Name of the XML element in the response
     *          that defines this list's members.
     * @param   string $responseListElement Name of the XML element in the
     *          response that defines this list.
     * @throws  Phlickr_Exception, Phlickr_ConnectionException,
     *          Phlickr_XmlParseException
     */
    function __construct(Phlickr_Request $request, $responseElement, $responseListElement) {
        $this->_respListElement = $responseListElement;
        $this->_respElement = $responseElement;
        $this->_request = $request;
        $this->load();
    }

    /**
     * Returns the name of the XML element in the response that defines the
     * list object.
     *
     * If the response looks like <resp><objs><obj><obj></objs></resp> this
     * should return "objs".
     *
     * @return  string
     */
    protected function getResponseListElement() {
        return $this->_respListElement;
    }
    /**
     * Returns the name of the XML element in the response that defines the
     * object.
     *
     * If the response looks like <resp><objs><obj><obj></objs></resp> this
     * should return "obj".
     *
     * @return  string
     */
    protected function getResponseElement() {
        return $this->_respElement;
    }
    /**
     * Return a reference to this object's Phlickr_Api.
     *
     * @return  object Plickr_Api
     */
    public function &getApi() {
        return $this->_request->getApi();
    }
    /**
     * Return the Phlickr_Request the PhotoList is based on.
     *
     * @return  object Phlickr_Request
     */
    public function getRequest() {
        return $this->_request;
    }

    /**
     * Connect to Flickr and update class's cashedXml member.
     *
     * @param   boolean $allowCached    If a cached result exists, should it be
     *          returned?
     * @return  object SimpleXMLElement
     * @throws  Phlickr_ConnectionException, Phlickr_XmlParseException
     */
    protected function requestXml($allowCached = false) {
        $response = $this->getRequest()->execute($allowCached);
        $xml = $response->xml->{$this->getResponseListElement()};
        if (is_null($xml)) {
            throw new Exception(
                sprintf("Could not load object with request: '%s'.",
                    $this->getRequest())
            );
        }
        return $xml;
    }
    /**
     * Load the complete information on object.
     *
     * If this request has been executed previously the cached data will be
     * returned. To force a connection use refresh().
     *
     * @return  void
     * @throws  Phlickr_Exception, Phlickr_ConnectionException,
     *          Phlickr_XmlParseException
     * @see     refresh(), requestXml()
     */
    public function load() {
        // allow cached results
        $this->_cachedXml = $this->requestXml(true);
    }
    /**
     * Connect to Flickr and get the current, complete information on this
     * object.
     *
     * This function always connect to the Flickr service. To allow cashed
     * results use load().
     *
     * @return  void
     * @throws  Phlickr_Exception, Phlickr_ConnectionException,
     *          Phlickr_XmlParseException
     * @see     load(), requestXml()
     */
    public function refresh() {
        // force a non-cached update
        $this->_cachedXml = $this->requestXml(false);
    }
    /**
    * Return the total number of items in the list.
    *
    * @return   integer
    */
    public function getCount() {
        try {
            return count($this->getIds());
        }
        catch (Phlickr_Exception $ex) {
            return 0;
        }
    }
    /**
    * Return an array of the integer ids in this list.
    *
    * If the list uses another datatype for the ids this method will need to be
    * overridden.
    *
    * @return   array
    */
    public function getIds() {
        if (!isset($this->_cachedXml->{$this->getResponseElement()})) {
            $this->load();
        }
        $ret = array();
        foreach ($this->_cachedXml->{$this->getResponseElement()} as $xml) {
            $ret[] = (string) $xml['id'];
        }
        return $ret;
    }
}
