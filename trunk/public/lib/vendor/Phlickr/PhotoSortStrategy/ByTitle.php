<?php

/**
 * @version $Id: ByTitle.php 499 2006-01-03 22:35:52Z drewish $
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
 * An object to allow the sorting of photos by title.
 *
 * @author      Andrew Morton <drewish@katherinehouse.com>
 * @package     Phlickr
 * @subpackage  PhotoSortStrategy
 * @since       0.2.4
 * @see         Phlickr_PhotoSorter
 */
class Phlickr_PhotoSortStrategy_ByTitle implements Phlickr_Framework_IPhotoSortStrategy {
    /**
     * Return the photo's title for sorting.
     *
     * @param   object Phlickr_Photo $photo
     * @return  string
     * @since   0.2.4
     */
    function stringFromPhoto(Phlickr_Photo $photo) {
        return $photo->getTitle();
    }
}
