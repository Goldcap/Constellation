<?php

/**
 * @version $Id: IPhotoSortStrategy.php 499 2006-01-03 22:35:52Z drewish $
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 * @package Phlickr
 */

/**
 * An interface for specifying how Phlickr_Photos should be sorted.
 *
 * @author      Andrew Morton <drewish@katherinehouse.com>
 * @package     Phlickr
 * @since       0.2.4
 * @see         Phlickr_PhotoSorter, usort()
 */
interface Phlickr_Framework_IPhotoSortStrategy {
    /**
     * Convert a Phlickr_Photo into a string that can be sorted.
     *
     * The string will be sorted in a case senstive manner. Feel free to use
     * strtolower() and strtoupper() accordingly.
     *
     * @param   object Phlickr_Photo $photo.
     * @return  string
     * @since   0.2.4
     */
    function stringFromPhoto(Phlickr_Photo $photo);
}
