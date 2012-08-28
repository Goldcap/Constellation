<?php

/**
 * @version $Id: UserList.php 500 2006-01-03 23:29:08Z drewish $
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 * @package Phlickr
 */

/**
 * Phlickr_Api includes the core classes.
 */
require_once 'Phlickr/Api.php';
/**
 * This class extends Phlickr_ListBase.
 */
require_once 'Phlickr/Framework/ListBase.php';
/**
 * One or more methods returns Phlickr_User objects.
 */
require_once 'Phlickr/User.php';

/**
 * Phlickr_UserList simply holds a list of users.
 *
 * Flickr is rather inconsistent in the naming of elements in the XML they
 * return so you can pass the name of the list element and the member element
 * to the constructor.
 *
 * Sample usage:
 * <code>
 * <?php
 * include_once 'Phlickr/Api.php';
 *
 * // insert sample code here
 *
 * ?>
 * </code>
 *
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @see     Phlickr_User
 * @since   0.2.1
 * @todo    Add sample code.
 */
class Phlickr_UserList extends Phlickr_Framework_ListBase {
    /**
     * The name of the XML element in the response that defines the list object.
     *
     * @var string
     */
    const XML_RESPONSE_LIST_ELEMENT = 'contacts';
    /**
     * The name of the XML element in the response that defines a member of the
     * list.
     *
     * @var string
     */
    const XML_RESPONSE_ELEMENT = 'contact';

    /**
     * Constructor.
     *
     * Unlike most other lists the user list lets you specify the name of the
     * XML list and member elements. This is because Flickr uses many different
     * names depending on the context (users/user, online/user,
     * contacts/contact, etc).
     *
     * @param   object Phlickr_Request $request
     * @param   string $responseElement Name of the XML element in the response
     *          that defines this list's members.
     * @param   string $responseListElement Name of the XML element in the
     *      response that defines this list.
     */
    function __construct(Phlickr_Request $request,
        $responseElement = self::XML_RESPONSE_ELEMENT,
        $responseListElement = self::XML_RESPONSE_LIST_ELEMENT)
    {
        parent::__construct($request, $responseElement, $responseListElement);
    }

    /**
    * Return an array of the ids in this list.
    *
    * Override the base class because these id's are strings.
    *
    * @return   array
     */
    public function getIds() {
        if (!isset($this->_cachedXml->{$this->getResponseElement()})) {
            $this->refresh();
        }
        $ret = array();
        foreach ($this->_cachedXml->{$this->getResponseElement()} as $xml) {
            $ret[] = (string) $xml['nsid'];
        }
        return $ret;
    }

    /**
     * Return an array of Phlickr_User objects.
     *
     * @return  array
     */
    public function getUsers() {
        if (!isset($this->_cachedXml->{$this->getResponseElement()})) {
            $this->refresh();
        }
        $ret = array();
        foreach ($this->_cachedXml->{$this->getResponseElement()} as $xml) {
            $ret[] = new Phlickr_User($this->getApi(), $xml);;
        }
        return $ret;
    }
}
