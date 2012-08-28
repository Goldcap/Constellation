<?php

/**
 * @version $Id: UploadListener.php 504 2006-01-28 03:44:34Z drewish $
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 * @package Phlickr
 */

/**
 * This class extends Phlickr_Framework_IUploadListener.
 */
require_once 'Phlickr/Framework/IUploadListener.php';
/**
 * This class uses Phlickr_Uploader to build URLs to edit uploaded photos.
 */
require_once 'Phlickr/Uploader.php';

/**
 * A command line listener to display upload status information.
 *
 * @author      Andrew Morton <drewish@katherinehouse.com>
 * @package     Phlickr
 * @subpackage  TextUi
 * @since       0.2.5
 */
class Phlickr_TextUi_UploaderListener
implements Phlickr_Framework_IUploadListener
{
    /**
     * Array of uploaded photos.
     *
     * @var array of Phlickr_AuthedPhotos
     */
    private $_photos = array();

    /**
     * Output file pointer.
     *
     * @var resource
     */
    private $_fp = null;

    /**
     * Constuct an upload listner that prints its output to a file handle.
     * Derived classes must call this constructor.
     *
     * @param   resource $fh resource opened with fopen().
     */
    public function __construct($fh = null) {
        if (is_null($fh)) {
            $this->_fp = fopen('php://stdout', 'w');
        } else {
            $this->_fp = $fh;
        }
    }

    /**
     * Write ouput.
     *
     * @param   string $str
     */
    private function _write($str) {
        fputs($this->_fp, $str);
    }

    public function beforeUpload() {
        $this->_write("Begining upload...\n");
    }

    /**
     * @uses    Phlickr_Uploader::buildEditUrl() to provide the user with a
     *          URL to edit the uploaded photos.
     */
    public function afterUpload($photos) {
        // if any photos upload
        $photoIds = array_keys($photos);

        if ($photos) {
            $this->_write("All done! If you care to make some changes:\n");
            $this->_write(Phlickr_Uploader::buildEditUrl($photoIds) . "\n");
        } else {
            $this->_write("No photos were uploaded.\n");
        }
    }

    /**
     * @uses    Phlickr_AuthedPhotoset::buildUrl() to provide the user with a
     *          URL to edit the photo set.
     */
    public function afterCreatePhotoset(Phlickr_AuthedPhotoset $set) {
        $this->_write("Created a photoset named '{$set->getTitle()}':\n");
        $this->_write($set->buildUrl() . "\n");
    }

    public function beforeFileUpload($fullPath) {
        $this->_write("Uploading " . basename($fullPath) . "...\n");
    }

    public function afterFileUpload($fullPath, Phlickr_AuthedPhoto $photo) {
        $this->_photos[$photo->getId()] = $photo;
        $this->_write("\tid: {$photo->getId()}\ttitle: '{$photo->getTitle()}'\n");
    }

    /**
     * @uses    Phlickr_Uploader::buildEditUrl() to provide the user with a
     *          URL to edit the uploaded photos.
     */
    public function failedFileUpload($fullPath, Exception $ex) {
        print $ex;

        fprintf(STDERR, "ERROR: Could not upload %s...\n", basename($fullPath));
        if (count($this->_photos)) {
            fprintf(STDERR,
                "Some photos were uploaded:\n%s\n",
                Phlickr_Uploader::buildEditUrl(array_keys($this->_photos))
            );
        }
        exit(-1);
    }
}
