<?php

/**
 * Mock Request
 *
 * @version $Id: Request.php 199 2005-04-28 02:09:03Z drewish $
 * @author Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php GNU Lesser General Public License, Version 2.1
 */

/**
 * Phlickr_Api includes the core classes
 */
require_once 'Phlickr/Api.php';
require_once 'Phlickr/Tests/constants.inc';

class Phlickr_Tests_Mocks_Request extends Phlickr_Request {
    /**
     * The xml value that this request will return.
     *
     * @var string
     */
    var $xmlToReturn;

    /**
     * Create a mock Request that returns the provided xml
     *
     * @param object Phlickr_Api.
     * @param string the name of the method.
     * @param string the xml to return.
     */
    function __construct(Phlickr_Api $api, $method, $xmlToReturn) {
        parent::__construct($api, $method);
        $this->xmlToReturn = (string) $xmlToReturn;
    }

    function execute() {
        return new Phlickr_Response($this->xmlToReturn, true);
    }
}

?>
