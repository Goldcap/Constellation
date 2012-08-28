<?php

/**
 * Mock Request
 *
 * @version $Id: PhotoListRequest.php 515 2006-02-06 00:29:20Z drewish $
 * @author Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php GNU Lesser General Public License, Version 2.1
 */

/**
 * Phlickr_Api includes the core classes
 */
require_once 'Phlickr/Api.php';
require_once 'Phlickr/Tests/constants.inc';

class Phlickr_Tests_Mocks_PhotoListRequest extends Phlickr_Request {
    var $perPage, $totalPhotos, $totalPages;

    function __construct(Phlickr_Api $api, $totalPhotos, $perPage) {
        parent::__construct($api, 'MOCK PHOTOLIST REQUEST');
        $this->perPage = (integer) $perPage;
        $this->totalPhotos = (integer) $totalPhotos;
        $this->totalPages = (integer) ($totalPhotos / $perPage) + 1;
    }

    function execute() {
        $params = $this->getParams();
        $pageNum = (integer) $params['page'];

        $xml = TESTING_RESP_OK_PREFIX . "\n";
        $xml .= sprintf('<photos page="%d" pages="%d" perpage="%d" total="%d">\n',
            $pageNum,
            $this->totalPages,
            $this->perPage,
            $this->totalPhotos
        );

        // photo count is the number of photos that should be on this page
        // the special case is the last page.
        if ($pageNum == $this->totalPages) {
            $photoCount = $this->totalPhotos % $this->perPage;
        } else {
            $photoCount = $this->perPage;
        }
        // id's must be unique so juts do it as an index
        $id = 1 + (($pageNum - 1) * $this->perPage);

        for ($i = 1; $i <= $photoCount; $i++) {
            $xml .= sprintf('<photo id="%s" owner="%s" secret="a123456" server="2" title="test_%i" ispublic="1" isfriend="0" isfamily="0" />\n',
                $id,
                '47058503995@N01',
                $id
            );
            $id++;
        }
        $xml .= '</photos>' . TESTING_RESP_SUFIX;
        return new Phlickr_Response($xml, false);
    }
}
