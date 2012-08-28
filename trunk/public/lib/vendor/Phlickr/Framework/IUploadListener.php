<?php

/**
 * @version $Id: IUploadListener.php 499 2006-01-03 22:35:52Z drewish $
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 * @package Phlickr
 */

/**
 * An interface for listening to events raised when uploading a batch of
 * photos to Flickr.
 *
 * Order of calls:
 * - beforeUpload()
 * - getFiles()
 * - (for each file) beforeFileUpload(), afterFileUpload().
 * - (if requested) afterCreatePhotoset()
 * - afterUpload()
 *
 * @author      Andrew Morton <drewish@katherinehouse.com>
 * @package     Phlickr
 * @since       0.2.5
 * @see         Phlickr_Uploader::uploadBatch()
 */
interface Phlickr_Framework_IUploadListener {
    /**
     * Called before any files are uploaded.
     *
     * @return  void
     * @see     afterUpload()
     */
    public function beforeUpload();

    /**
     * Called before each file is uploaded.
     *
     * @param   string $fullPath Full file path.
     * @return  void
     * @see     afterFileUpload(), failedFileUpload()
     */
    public function beforeFileUpload($fullPath);

    /**
     * Called after each file is uploaded.
     *
     * @param   string $fullPath Full file path.
     * @param   Phlikcr_AuthedPhoto $photo
     * @return  void
     * @see     beforeFileUpload(), failedFileUpload()
     */
    public function afterFileUpload($fullPath, Phlickr_AuthedPhoto $photo);

    /**
     * Called if an upload fails.
     *
     * @param   string $fullPath Full file path.
     * @param   Exception $exception The exception thrown when
     * @see     beforeFileUpload(), afterFileUpload()
     */
    public function failedFileUpload($fullPath, Exception $ex);

    /**
     * Called after a photoset is created.
     *
     * @param   Phlickr_AuthedPhotoset $set
     */
    public function afterCreatePhotoset(Phlickr_AuthedPhotoset $set);

    /**
     * Called after all the files are uploaded.
     *
     * @param   array $photos An array of Phlickr_AuthedPhoto objects with
     *          their ids as the array keys.
     * @return  void
     * @see     beforeUpload()
     */
    public function afterUpload($photos);
}
