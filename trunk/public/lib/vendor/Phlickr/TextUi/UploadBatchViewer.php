<?php

/**
 * @version $Id: UploadBatchViewer.php 504 2006-01-28 03:44:34Z drewish $
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 * @package Phlickr
 */

/**
 * This class uses Phlickr_Framework_IUploadBatch as a parameter.
 */
require_once 'Phlickr/Framework/IUploadBatch.php';

/**
 * A command line viewer for upload batches. So you can see what's going to be uploaded before uploading.
 *
 * @author      Andrew Morton <drewish@katherinehouse.com>
 * @package     Phlickr
 * @subpackage  TextUi
 * @since       0.2.5
 */
class Phlickr_TextUi_UploadBatchViewer
{
    /**
     * Upload Batch
     *
     * @var Phlickr_Framework_IUploadBatch
     */
    private $_batch = null;

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
    public function __construct(Phlickr_Framework_IUploadBatch $batch, $fh = null) {
        if (is_null($fh)) {
            $this->_fp = fopen('php://stdout', 'w');
        } else {
            $this->_fp = $fh;
        }

        $this->_batch = $batch;
    }

    /**
     * Write ouput.
     *
     * @param   string $str
     */
    private function _write($str) {
        fputs($this->_fp, $str);
    }

    /**
     * Get the upload batch being viewed.
     *
     * @return  Phlickr_Framework_IUploadBatch
     */
    public function getBatch() {
        return $this->_batch;
    }

    /**
     * Display all information about the files in this batch.
     *
     * @return  void
     */
    public function viewFiles() {
        $files = $this->_batch->getFiles();
        if ($files) {
            $this->_write("Files:\n");
            foreach ($files as $file) {
                $title = $this->_batch->getTitleForFile($file);
                $desc = $this->_batch->getDescriptionForFile($file);
                $tags = $this->_batch->getTagsForFile($file);
                $date = $this->_batch->getTakenDateForFile($file);

                $this->_write($file . "\n");
                if ($title) {
                    $this->_write("Title:       $title\n");
                }
                if ($tags) {
                    $tags = implode(', ', $tags);
                    $this->_write("Tags:        $tags\n");
                }
                if ($date) {
                    if (is_long($date)) {
                        $date = date('Y-m-d H:i:s', $date);
                    }
                    $this->_write("Taken Date:  $date\n");
                }
                if ($desc) {
                    $this->_write("Description: $desc\n");
                }
                $this->_write("\n");
            }
        } else {
            $this->_write("There are no files in this batch.\n");
        }
    }

    /**
     * Display all information about the creation of a photoset.
     *
     * @return  void
     */
    public function viewSet() {
        if ($this->_batch->isSetWanted()) {
            $title = trim($this->_batch->getSetTitle());
            $desc = trim($this->_batch->getSetDescription());

            $this->_write("A photoset titled '$title' will be created. ");
            if ($desc) {
                $this->_write("Its description will be:\n$desc");
            }
            $this->_write("\n");
        } else {
            $this->_write("No photoset will be created.\n");
        }
    }
}
