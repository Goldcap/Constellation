<?php

/**
 * @version $Id: IList.php 506 2006-01-28 04:17:13Z drewish $
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 * @package Phlickr
 */

/**
 * IList is the interface that defines the minimum operations a list should
 * support.
 *
 * @author      Andrew Morton <drewish@katherinehouse.com>
 * @package     Phlickr
 * @see         ObjectBase
 */
interface Phlickr_Framework_IList {
    /**
    * Return the total number of items in the list.
    *
    * @return   integer
    */
    function getCount();

    /**
    * Return an array of the Ids in this list.
    *
    * @return  array of string ids
    */
    function getIds();
}
