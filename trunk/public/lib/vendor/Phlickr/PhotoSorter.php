<?php

/**
 * @version $Id: PhotoSorter.php 516 2006-03-29 03:56:51Z drewish $
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
            GNU Lesser General Public License, Version 2.1
 * @package Phlickr
 */


/**
 * Phlickr_Api includes the core classes.
 */
require_once 'Phlickr/Api.php';
/**
 * This class accepts IPhotoSortStrategy objects as parameters.
 */
require_once 'Phlickr/Framework/IPhotoSortStrategy.php';


/**
 * An object to sorts Phlickr_Photos using a given
 * Phlickr_Framework_IPhotoComparator.
 *
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @package Phlickr
 * @since   0.2.3
 * @see     Phlickr_Framework_IPhotoComparator, Phlickr_Framework_IPhotoList
 */
class Phlickr_PhotoSorter {
    /**
     * Should the photo be sorted in reverse order?
     *
     * @var boolean
     */
    private $_inReverse;
    /**
     * How should the photos be sorted?
     *
     * @var object Phlickr_Framework_IPhotoSortStrategy
     */
    private $_sortStrategy;

    /**
     * Constructor.
     *
     * @param   object Phlickr_Framework_IPhotoComparator $comparator Specifies
     *          how the sorting will be done.
     * @param   boolean $inReverse Should the photos be sorted in reverse order.
     * @see     isInReverse()
     */
    function __construct(Phlickr_Framework_IPhotoSortStrategy $strategy,
        $inReverse = false)
    {
        $this->_strategy = $strategy;
        $this->_inReverse = (boolean) $inReverse;
    }

    /**
     * Will the sort be done in reverse order?
     *
     * @return  boolean
     * @see     __construct()
     */
    function isInReverse() {
        return $this->_inReverse;
    }

    /**
     * Sort an array of photos using the comparator.
     *
     * Keep in mind a couple of other classes that implement
     * Phlickr_Framework_IPhotoList:
     *  - Phlickr_PhotosetPhotoList all the photos in a photoset.
     *  - Phlickr_PhotoListIterator will pull all pages from a photo list.
     *
     * @param   object Phlickr_Framework_IPhotoList $photoslist
     * @return  array Phlickr_Photo Sorted array of photos
    */
    function sort(Phlickr_Framework_IPhotoList $photolist) {
        foreach ($photolist->getPhotos() as $photo) {
            $id = $photo->getId();
            $keys[$id] = $this->_strategy->stringFromPhoto($photo);
            $photos[$id] = $photo;
        }

        if ($this->isInReverse()) {
            arsort($keys);
        } else {
            asort($keys);
        }

        $ret = array();
        foreach($keys as $id => $key) {
            $ret[] = $photos[$id];
        }
        return $ret;
    }

    /**
     * Return an array of ids from an array of Phlickr_Photos.
     *
     * @param   array object Phlickr_Photo
     * @return  array of string ids
     */
    static function idsFromPhotos($photos) {
        $ids = array();
        foreach ($photos as $photo) {
            $ids[] = $photo->getId();
        }
        return $ids;
    }
}
