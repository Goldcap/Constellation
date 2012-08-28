<?php

/**
 * @version $Id: PhotoList.php 515 2006-02-06 00:29:20Z drewish $
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
 * This class implements IPhotoList.
 */
require_once 'Phlickr/Framework/IList.php';
/**
 * This class implements IPhotoList.
 */
require_once 'Phlickr/Framework/IPhotoList.php';
/**
 * One or more methods returns Phlickr_Photo and Phlickr_AuthedPhoto objects.
 */
require_once 'Phlickr/AuthedPhoto.php';

/**
 * Phlickr_PhotoList represents paged list of photos.
 *
 * <b>WATCH OUT</b>: there's still some problems with the caching in the class.
 * if you call refresh() it'll force and update only to the current page. If
 * you want the whole thing refreshed you'll need to call it on each page.
 *
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @see     Phlickr_PhotoListIterator
 * @since   0.1.0
 */
class Phlickr_PhotoList implements Phlickr_Framework_IPhotoList {
    /**
     * The name of the XML element in the response that defines the object.
     *
     * @var string
     */
    const XML_RESPONSE_LIST_ELEMENT = 'photos';
    /**
     * The name of the XML element in the response that defines a member of the
     * list.
     *
     * @var string
     */
    const XML_RESPONSE_ELEMENT = 'photo';
    /**
     * Default number of photos per page.
     *
     * @var integer
     */
    const PER_PAGE_DEFAULT = 100;
    /**
    * Maximum number of photos per page.
    *
    * @var integer
    */
    const PER_PAGE_MAX = 500;

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
    protected $_cachedXml = array();
    /**
    * Current page in the PhotoList.
    *
    * @var integer
    */
    private $_page = 1;
    /**
    * Number of photos on a page.
    *
    * @var integer
    */
    private $_perPage;

    /**
     * Constructor.
     *
     * @param   object Phlickr_Request $request
     * @param   integer $photosPerPage Number of photos on each page.
     * @throws  Phlickr_Exception, Phlickr_ConnectionException,
     *          Phlickr_XmlParseException
     */
    function __construct(Phlickr_Request $request,
        $photosPerPage = self::PER_PAGE_DEFAULT)
    {
        $this->_request = $request;
        // API limits the number of photos per page
        $this->_perPage = ($photosPerPage > self::PER_PAGE_MAX)
            ? self::PER_PAGE_MAX : (integer) $photosPerPage;
        $this->load();
    }

    static protected function getResponseListElement() {
        return self::XML_RESPONSE_LIST_ELEMENT;
    }

    static protected function getResponseElement() {
        return self::XML_RESPONSE_ELEMENT;
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
    * @return object Phlickr_Request
    */
    public function getRequest() {
        return $this->_request;
    }

    /**
     * Connect to Flickr and retreive a page of photos.
     *
     * @param   boolean $allowCached If a cached result exists, should it be
     *          returned?
     * @param   integer $page The page number to request.
     * @return  object SimpleXMLElement
     * @throws  Phlickr_ConnectionException, Phlickr_XmlParseException
     * @see     load(), refresh()
     */
    protected function requestXml($allowCached = false, $page) {
        $params =& $this->_request->getParams();
        $params['page'] = $page;
        $params['per_page'] = $this->getPhotosPerPage();

        $response = $this->_request->execute($allowCached);
        $xml = $response->xml->{self::getResponseListElement()};
        if (is_null($xml)) {
            throw new Exception(
                sprintf(
                    "Could not load object with request: '%s'.",
                    $request->getMethod()
                )
            );
        }
        return $xml;
    }
    /**
     * Load the complete information on object.
     *
     * @param   integer $page The page number to load. Defaults to the current
     *          page.
     * @return  void
     * @see     refresh(), requestXml()
     */
    public function load($page = null) {
        // if page is null use the current page.
        $page = (is_null($page)) ? $this->getPage() : (integer) $page ;

        // allow cached results
        $this->_cachedXml[$page] = $this->requestXml(true, $page);
    }
    /**
     * Connect to Flickr and get the current, complete information on this
     * object.
     *
     * @param   integer $page The page number to load. Defaults to the current
     *          page.
     * @return  void
     * @see     load(), requestXml()
     */
    public function refresh($page = null) {
        // if page is null use the current page.
        $page = (is_null($page)) ? $this->getPage() : (integer) $page ;

        // force a non-cached update
        $this->_cachedXml[$page] = $this->requestXml(false, $page);
    }

    /**
     * Return the number of photos on a page.
     *
     * @return  integer
     * @since   0.1.5
     */
    function getPhotosPerPage() {
        return $this->_perPage;
    }

    /**
     * Return the total number of pages in the list.
     *
     * @return  integer
     */
    function getPageCount() {
        if (!isset($this->_cachedXml[$this->_page]['pages'])) {
            $this->load();
        }
        return (integer) $this->_cachedXml[$this->_page]['pages'];
    }
    /**
     * Return the current page.
     *
     * @return integer
     */
    public function getPage() {
        return (integer) $this->_page;
    }
    /**
     * Set the current page number.
     *
     * @param   integer $page The page in the photolist to view.
     * @return  void
     */
    public function setPage($page) {
        $this->_page = (integer) $page;
    }

    /**
     * Return the total number of photos in the photolist.
     *
     * @return  integer
     */
    public function getCount() {
        if (!isset($this->_cachedXml[$this->_page])) {
            $this->load();
        }
        return (integer) $this->_cachedXml[$this->_page]['total'];
    }


    /**
     * Return an array of the photo ids on a given page.
     *
     * This function is designed to allow iterators access into the class.
     *
     * @param   integer $page is the page number 1 to getPageCount()
     * @param   boolean $allowCached Should cached data be allowed?
     * @return  array of string ids
     * @since   0.2.3
     */
    public function getIdsFromPage($page, $allowCached = true) {
        if ($allowCached) {
            $this->load($page);
        } else {
            $this->refresh($page);
        }

        $ret = array();
        foreach ($this->_cachedXml[$page]->{self::getResponseElement()} as $xmlPhoto) {
            $ret[] = (string) $xmlPhoto['id'];
        }
        return $ret;
    }

    /**
     * Return an array of photos on a given page.
     *
     * This function is designed to allow iterators access into the class.
     *
     * @param   integer $page is the page number 1 to getPageCount()
     * @param   boolean $allowCached Should cached data be allowed?
     * @return  array object Phlickr_AuthedPhoto or Phlickr_Photo depending
     *          on the owner.
     */
    public function getPhotosFromPage($page, $allowCached = true) {
        if ($allowCached) {
            $this->load($page);
        } else {
            $this->refresh($page);
        }

        $ret = array();
        foreach ($this->_cachedXml[$page]->{self::getResponseElement()} as $xmlPhoto) {
            if ($xmlPhoto['owner'] == $this->getApi()->getUserId()) {
                $ret[] = new Phlickr_AuthedPhoto($this->getApi(), $xmlPhoto);
            } else {
                $ret[] = new Phlickr_Photo($this->getApi(), $xmlPhoto);
            }
        }
        return $ret;
    }

    /**
     * Return an array of the photo ids on this page of the list.
     *
     * @return  array of string ids
     */
    public function getIds() {
        if (!isset($this->_cachedXml[$this->_page]->{$this->getResponseElement()})) {
            $this->load();
        }

        $ret = $this->getIdsFromPage($this->_page);
        return $ret;
    }

    /**
    * Return an array of the Phlickr_Photo objects on this page of the list.
    *
    * @return   array object Phlickr_AuthedPhoto or Phlickr_Photo depending on
    *           the owners.
    * @see      getIds()
    */
    public function getPhotos() {
        return $this->getPhotosFromPage($this->_page, true);
    }

    /**
     * Return a random photo from the photo list.
     *
     * The the photo can be from any one of the pages in the list.
     *
     * @return  object Phlickr_AuthedPhoto or Phlickr_Photo depending on the
     *          owner.
     * @since   0.1.7
     */
    function getRandomPhoto() {
        // this might be in-efficient if the number of photos per page is large
        $photos = $this->getPhotosFromPage(rand(1, $this->getPageCount()), true);

        return $photos[rand(0, count($photos) - 1)];
    }
}
