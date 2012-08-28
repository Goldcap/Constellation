<?php

/**
 * @version $Id: ObjectBase.php 506 2006-01-28 04:17:13Z drewish $
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 * @package Phlickr
 */

/**
 * A base class for the Phlickr objects that wrap XML returned by API calls.
 *
 * This class is responsible for:
 * - Creating the object from an Id value or an XML description.
 * - Requesting and caching the requested XML.
 *
 * @author      Andrew Morton <drewish@katherinehouse.com>
 * @package     Phlickr
 */
abstract class Phlickr_Framework_ObjectBase {
    /**
     * Reference to the API.
     *
     * Technically we could pull this from $_request but it makes sense to
     * cache it.
     *
     * @var object Phlickr_Api
     * @see __construct(), getApi()
     */
    protected $_api = null;
    /**
     * @var object Phlickr_Request
     * @see __construct(), createRequest(), getRequest()
     */
    protected $_request = null;
    /**
     * The name of the XML element in the response that defines the object.
     *
     * If the response looks like <resp><objname /></resp> this should be
     * "objname".
     *
     * @var string
     * @see getResponseElement()
     */
    protected $_respElement;
    /**
     * The last bit of XML from Flickr (or the cache) used to define the object.
     *
     * @var object SimpleXMLElement
     */
    protected $_cachedXml = null;

    /**
     * Constructor.
     *
     * The idea with this base class is that you should be able to construct an
     * object in two ways: By Id or by XML. If it's a XML we store it as the
     * cached copy and and try to pull an Id. Once we've got an Id value we can
     * build a Phlickr_Request object that will provide any missing data.
     *
     * @param   object Phlickr_API $api
     * @param   mixed $source string Id, object SimpleXMLElement
     * @param   string $responseElement Name of the XML element in the response
     *          that defines this object.
     * @throws  Phlickr_Exception, Phlickr_ConnectionException,
     *          Phlickr_XmlParseException
     */
    function __construct(Phlickr_Api $api, $source, $responseElement) {
        $this->_api =& $api;
        $this->_respElement = $responseElement;
        if (is_null($source)) {
            throw new Phlickr_Exception("The source parameter cannot be null. Try passing in an Id or SimpleXML object. ");
        } else if (is_object($source) && ($source instanceof SimpleXMLElement)) {
            // if the source is xml see if we can parse an id out of it
            $this->_cachedXml = $source;
            $id = $this->getId();
            $this->_request =& $this->createRequest($api, $id);
        } else {
            $this->_request =& $this->createRequest($api, $source);
            $this->load();
        }
    }

    /**
     * Create a Phlickr_Request object that will request the XML for this object.
     *
     * @param   object Phlickr_Api $api
     * @param   mixed string $id
     * @return  object Phlickr_Request
     * @see     __construct()
     */
    protected function &createRequest(Phlickr_Api $api, $id) {
        $request = $api->createRequest(
            $this->getRequestMethodName(),
            $this->getRequestMethodParams($id)
        );

        if (is_null($request)) {
            throw new Phlickr_Exception('Could not create a Request.');
        } else {
            return $request;
        }
    }
    /**
     * Returns the name of the XML element in the response that defines this
     * object.
     *
     * If the response looks like <resp><obj><obj></resp> this should return
     * "obj".
     *
     * @return  string
     * @see     requestXml()
     */
    protected function getResponseElement() {
        return $this->_respElement;
    }
    /**
     * Connect to Flickr and update class's cashedXml member.
     *
     * @param   boolean $allowCached If a cached result exists, should it be
     *          returned?
     * @return  object SimpleXMLElement
     * @throws  Phlickr_ConnectionException, Phlickr_XmlParseException
     */
    protected function requestXml($allowCached = false) {
        $response = $this->getRequest()->execute($allowCached);
        $xml = $response->xml->{$this->getResponseElement()};
        if (is_null($xml)) {
            throw new Exception("Could not load object with id: '{$this->getId()}'.");
        }
        return $xml;
    }

    /**
     * Returns the name of this object's getInfo API method.
     *
     * @return  string
     */
    abstract static function getRequestMethodName();
    /**
     * Returns an array of parameters to be used when creating a
     * Phlickr_Request to call this object's getInfo API method.
     *
     * @param   string $id The id value of this object.
     * @return  array
     * @see     getId()
     */
    abstract static function getRequestMethodParams($id);
    /**
     * Return the object's Id.
     *
     * @return  string
     */
    abstract function getId();
    /**
     * Return a URL to the Flickr webpage to view this object.
     *
     * @return  string
     */
    abstract public function buildUrl();


    /**
     * Return a reference to this object's Phlickr_Api.
     *
     * @return  object Plickr_Api
     * @see     __construct()
     */
    public function &getApi() {
        return $this->_api;
    }
    /**
     * Return the Phlickr_Request the object is based on.
     *
     * @return  object Phlickr_Request
     * @see     __construct()
     */
    public function getRequest() {
        return $this->_request;
    }
    /**
     * Return the cached XML as a SimpleXMLElement.
     *
     * View it as text call $x->getXml()->asXml().
     *
     * @return  object SimpleXMLElement
     * @see     __construct(), SimpleXMLElement->asXml()
     * @since   0.2.4
     */
    public function getXml() {
        return $this->_cachedXml;
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
     * Connect to Flickr and get the current, complete information on this object.
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
}
