<?php

/**
 * @version $Id: GroupList.php 500 2006-01-03 23:29:08Z drewish $
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
 * One or more methods returns Phlickr_Group objects.
 */
require_once 'Phlickr/Group.php';

/**
 * Phlickr_GroupList
 *
 * Sample usage:
 * <code>
 * <?php
 * include_once 'Phlickr/Api.php';
 * include_once 'Phlickr/User.php';
 * include_once 'Phlickr/Group.php';
 *
 * $api = new Phlickr_Api(FLICKR_API_KEY, FLICKR_API_SECRET, FLICKR_TOKEN);
 * $user = Phlickr_User::findByUrl($api, 'http://flickr.com/people/drewish/');
 *
 * $grouplist = $user->getGroupList();
 *
 * // print out the user's groups
 * foreach ($grouplist->getGroups() as $group) {
 *     print "Group: {$group->getName()} ({$group->buildUrl()})\n";
 * }
 * ?>
 * </code>
 *
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @see     Phlickr_Group
 * @since   0.1.4
 */
class Phlickr_GroupList extends Phlickr_Framework_ListBase {
    /**
     * The name of the XML element in the response that defines the list object.
     *
     * @var string
     */
    const XML_RESPONSE_LIST_ELEMENT = 'groups';
    /**
     * The name of the XML element in the response that defines a member of the
     * list.
     *
     * @var string
     */
    const XML_RESPONSE_ELEMENT = 'group';

    /**
     * Constructor.
     *
     * @param object Phlickr_Request $request
     */
    function __construct(Phlickr_Request $request) {
        parent::__construct($request, self::XML_RESPONSE_ELEMENT,
            self::XML_RESPONSE_LIST_ELEMENT);
    }

    /**
     * Return an array of the ids in this list.
     *
     * Override the base class because these id's are strings.
     *
     * @return array
     */
    public function getIds() {
        if (!isset($this->_cachedXml->{$this->getResponseElement()})) {
            $this->refresh();
        }
        $ret = array();
        foreach ($this->_cachedXml->{$this->getResponseElement()} as $xml) {
            // most of the xml as the id in the "id" attribute but
            // flickr.people.getPublicGroups returns it in the "nsid" attribute
            if (isset($xml['id'])) {
                $ret[] = (string) $xml['id'];
            } else {
                $ret[] = (string) $xml['nsid'];
            }
        }
        return $ret;
    }

    /**
     * Return an array of Phlickr_Group objects.
     *
     * @return array
     */
    public function getGroups() {
        if (!isset($this->_cachedXml->{$this->getResponseElement()})) {
            $this->refresh();
        }
        $ret = array();
        foreach ($this->_cachedXml->{$this->getResponseElement()} as $xml) {
            $ret[] = new Phlickr_Group($this->getApi(), $xml);
        }
        return $ret;
    }
}
