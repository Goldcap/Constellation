<?php

/**
 * Phlickr makes use of PHP5 exceptions to simplify the detection of and
 * differentiation programming and connection errors.
 *
 * @version $Id: Exception.php 500 2006-01-03 23:29:08Z drewish $
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php GNU Lesser General
 *          Public License, Version 2.1
 * @package Phlickr
 * @todo    Split this into separate files.
 */

/**
 * Exception base class thrown when there is no more specific exception.
 * Callers should use this as their final catch when calling various Phlickr
 * functions.
 *
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 */
class Phlickr_Exception extends Exception {
}

/**
 * Exception thrown when there is a problem connecting to the service.
 *
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 */
class Phlickr_ConnectionException extends Phlickr_Exception {
    /**
     * The URL that was being requested when the problem occured.
     *
     * @var string
     */
    protected $_url;
    
    /**
     * Constructor
     *
     * @param string $message Error message
     * @param integer $code Error code
     * @param string $url URL accessed during failure
     */
    public function __construct($message = null, $code = null, $url = null) {
        parent::__construct($message, $code);
        $this -> debug = false;
        $this->_url = (string) $url;
    }

    public function __toString() {
     $s = "exception '" . __CLASS__ . "' [{$this->code}]: {$this->message}\n";
      if (isset($this->_url)) {
          $s .= "URL: {$this->_url}\n";
      }
      $s .= "Stack trace:\n" . $this->getTraceAsString();
      return $s;
    }

    /**
     * Return the URL associated with the connection failure.
     *
     * @return string
     */
    public function getUrl() {
        return $this->_url;
    }
}

/**
 * Exception (optionally) thrown when an API method call fails.
 *
 * You can determine if this exception should be thrown by calling
 * Phlickr_Request's setExceptionThrownOnFailure() method.
 *
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 */
class Phlickr_MethodFailureException extends Phlickr_Exception {
}

/**
 * Exception thrown when XML cannot be parsed.
 *
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 */
class Phlickr_XmlParseException extends Phlickr_Exception {
    /**
     *
     * @var string
     */
    protected $_xml;

    /**
     * Constructor
     *
     * @param string $message
     * @param string $xml
     */
    public function __construct($message = null, $xml = null) {
        parent::__construct($message);
        $this->_xml = (string) $xml;
    }

    public function __toString() {
        $s = "exception '" . __CLASS__ . "' {$this->message}\n";
        if (isset($this->_xml)) {
            $s .= "XML: '{$this->_xml}'\n";
        }
        $s .= "Stack trace:\n" . $this->getTraceAsString();
        return $s;
    }

    /**
     * Return the un-parseable XML.
     *
     * @return string
     */
    public function getXml() {
        return $this->_xml;
    }
}
