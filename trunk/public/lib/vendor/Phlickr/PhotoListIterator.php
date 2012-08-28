<?php

/**
 * @version $Id: PhotoListIterator.php 522 2006-05-09 22:35:21Z drewish $
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
 * This class uses the Phlickr_PhotoList object.
 */
require_once 'Phlickr/PhotoList.php';

/**
 * Phlickr_PhotoListIterator is used to iterate through the pages of a
 * Phlickr_PhotoList. It also can act as a Phlickr_IPhotoList effectively
 * combining a paged list into a single photo list.
 *
 * Sample usage:
 * <code>
 * <?php
 * include_once 'Phlickr/Api.php';
 * include_once 'Phlickr/Group.php';
 * include_once 'Phlickr/PhotoListIterator.php';
 *
 * $api = new Phlickr_Api(FLICKR_API_KEY, FLICKR_API_SECRET, FLICKR_TOKEN);
 *
 * $group = new Phlickr_Group($api, '98274710@N00');
 * $photolist = $group->getPhotoList();
 *
 * // iterate over all the pages
 * $iterator = new Phlickr_PhotoListIterator($photolist);
 * foreach($iterator as $page => $photos) {
 *     print "Page number: $page\n";
 *     foreach ($photos as $photo) {
 *         print "Photo Id: {$photo->getId()} Title: '{$photo->getTitle()}'\n";
 *     }
 * }
 *
 * // or, just extract all the photos. it does the same thing
 * foreach ($iterator->getPhotos() as $photo) {
 *     print "Photo Id: {$photo->getId()} Title: '{$photo->getTitle()}'\n";
 * }
 *
 * ?>
 * </code>
 *
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @todo    Rework the class to use a Phlickr_Request instead of a
 *          Phlickr_PhotoList.
 * @todo    Modify the constructor to accept either a Phlickr_Request or
 *          Phlickr_PhotoList.
 * @since   0.1.8
 */
class Phlickr_PhotoListIterator implements Iterator, Phlickr_Framework_IPhotoList {
    /**
     * Photo list being iterated.
     *
     * @var object Phlickr_PhotoList
     */
    private $_pl;
    /**
     * Number of photos per page.
     *
     * This is copied from the photo list because it shouldn't change.
     *
     * @var integer
     */
    private $_perPage;
    /**
     *
     * @var boolean
     */
    private $_isCachedAllowed;
    /**
     * The current photos from the current page.
     *
     * @var array of Phlickr_Photo objects
     */
    private $_photos;
    /**
     * The current page number.
     *
     * @var integer
     */
    private $_page;

    /**
     * Constructor.
     *
     * @param object Phlickr_PhotoList $pl The photo list to iterate.
     * @param boolean $isCachedAllowed Is cached data acceptable?
     */
    public function __construct(Phlickr_PhotoList $pl, $isCachedAllowed = true) {
        $this->_pl = $pl;
        $this->_isCachedAllowed = $isCachedAllowed;
        $this->_perPage = $pl->getPhotosPerPage();
        $this->rewind();
    }

    /**
     * Return an array of photos from the current page.
     *
     * @return array Phlickr_Photo
     */
    public function current() {
        if (!isset($this->_photos)) {
            $this->_photos = $this->_pl->getPhotosFromPage($this->_page);
        }
        return $this->_photos;
    }

    /**
     * Get the page number, which serves as the key.
     *
     * @return  void
     * @see     current()
     */
    public function key() {
        return (integer) $this->_page;
    }

    /**
     * Move the iterator to the next page.
     *
     * @return  void
     * @see     rewind(), valid()
     */
    public function next() {
        $this->_photos = null;
        $this->_page++;
    }

    /**
     * Reset the iterator to the first page.
     *
     * @return void
     */
    public function rewind() {
        $this->_photos = null;
        $this->_page = 1;
    }

    /**
     * Is the current page valid?
     *
     * @return boolean
    */
    public function valid() {
        return ($this->_page <= $this->_pl->getPageCount());
    }

    /**
     * Return the underlieing photo list we're iterating.
     *
     * @return object Phlickr_PhotoList
     */
    public function getPhotoList() {
        return $this->_pl;
    }

    /**
     * Return the total number of photos on every page in the list.
     *
     * @return  integer
     */
    function getCount() {
        return $this->_pl->getCount();
    }

    /**
     * Return an array of the photo ids on every page in the list.
     *
     * @return object Phlickr_PhotoList
     */
    public function getIds() {
        $count = $this->_pl->getPageCount();
        $ret = array();
        for ($page = 1; $page <= $count; $page++) {
            $ids = $this->_pl->getIdsFromPage($page);
            $ret = array_merge($ret, $ids);
        }
        return $ret;
    }

    /**
     * Return an array of photos from every page in the list.
     *
     * @return object Phlickr_PhotoList
     */
    public function getPhotos() {
        $count = $this->_pl->getPageCount();
        $ret = array();
        for ($page = 1; $page <= $count; $page++) {
            $photos = $this->_pl->getPhotosFromPage($page);
            $ret = array_merge($ret, $photos);
        }
        return $ret;
    }
}
