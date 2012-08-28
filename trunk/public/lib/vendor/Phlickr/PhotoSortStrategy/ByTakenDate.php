<?php

/**
 * @version $Id: ByTakenDate.php 499 2006-01-03 22:35:52Z drewish $
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
 * This class implements IPhotoSortStrategy.
 */
require_once 'Phlickr/Framework/IPhotoSortStrategy.php';


/**
 * An object to allow the sorting of photos by their the date they were taken.
 *
 * @author      Andrew Morton <drewish@katherinehouse.com>
 * @package     Phlickr
 * @subpackage  PhotoSortStrategy
 * @since       0.2.5
 * @see         Phlickr_PhotoSorter
 */
class Phlickr_PhotoSortStrategy_ByTakenDate implements Phlickr_Framework_IPhotoSortStrategy {
    /**
     * Return the photo's date for sorting.
     *
     * @param   object Phlickr_Photo $photo
     * @return  string
     */
    function stringFromPhoto(Phlickr_Photo $photo) {
        return $photo->getTakenDate();
    }
}
