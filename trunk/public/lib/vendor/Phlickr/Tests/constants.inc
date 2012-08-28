<?php

/**
* Constants for use with the unit tests.
*
* @version $Id: constants.inc 508 2006-02-02 09:00:31Z drewish $
* @copyright 2005
*/

define('TESTING_API_KEY', '58ac590250ef4eeb81e6d853ee942034');
define('TESTING_API_SECRET', '139c27ff722afe40');
define('TESTING_API_TOKEN', '418269-e278cc900dcd9787');
define('TESTING_USER_EMAIL', 'testing@drewish.com');
define('TESTING_USER_ID', '39059360@N00');
define('TESTING_OTHER_USER_ID', '26159919@N00');

define('TESTING_FILE_NAME_JPG', dirname(__file__) . '\small_sample.JPG');
define('TESTING_FILE_NAME_PNG', dirname(__file__) . '\small_sample.png');
define('TESTING_FILE_NAME_GIF', dirname(__file__) . '\small_sample.gif');
define('TESTING_FILE_NAME_MISSING', dirname(__file__) . '\NON_EXISTANT_FILE.JPG');

define('TESTING_REAL_PHOTO_ID_JPG', "73388608");
define('TESTING_REAL_PHOTO_ID_GIF', "25002121");
define('TESTING_REAL_PHOTO_ID_PNG', "25002122");

define('TESTING_REAL_PHOTOSET_ID1', "107561");
define('TESTING_REAL_PHOTOSET_ID2', "107552");

define('TESTING_XML_ECHO', <<<XML
    <api_key>11feb6fd0db850debccf2dc309dbc93a</api_key>
    <foo>bar</foo>
    <method>flickr.test.echo</method>
XML
);

define('TESTING_XML_CHECKTOKEN', <<<XML
<auth>
    <token>739-db0795e964f080fa</token>
    <perms>write</perms>
    <user nsid="39059360@N00" username="just testing" fullname="just testing" />
</auth>

<user id="39059360@N00">
    <username>just testing</username>
</user>
XML
);
define('TESTING_XML_LOGIN', <<<XML
<user id="39059360@N00">
    <username>just testing</username>
</user>
XML
);

define('TESTING_XML_FAVORITE_PHOTOLIST', <<<XML
<photos page="1" pages="1" perpage="100" total="1">
  <photo id="6508272" owner="26159919@N00" secret="6825c25369" server="3" title="DSC00398_exit_sign" ispublic="1" isfriend="1" isfamily="1" />
</photos>
XML
);

define('TESTING_XML_GROUP_ID', '84636767@N00');
define('TESTING_XML_GROUP_NAME', 'phlickr tests');
define('TESTING_XML_GROUP_SHORT', '<group nsid="84636767@N00" name="phlickr tests" admin="1" privacy="1" photos="2" />');
define('TESTING_XML_GROUP_LONG', <<<XML
<group id="84636767@N00">
    <name>phlickr tests</name>
    <members>2</members>
    <online>0</online>
    <privacy>1</privacy>
    <chatid>58499676@N00</chatid>
    <chatcount>0</chatcount>
</group>
XML
);

define('TESTING_XML_GROUPS_PUBLIC', <<<XML
<groups>
    <group nsid="97544914@N00" name="infrastructure" admin="0"
        eighteenplus="0" />
</groups>
XML
);
define('TESTING_XML_GROUPS_PRIVATE', <<<XML
<groups>
    <group id="97544914@N00" name="infrastructure" admin="0" privacy="3"
        photos="419"/>
    <group id="84636767@N00" name="phlickr tests" admin="1" privacy="1"
        photos="2"/>
</groups>
XML
);


define('TESTING_XML_GROUP_PHOTOLIST', <<<XML
<photos page="1" pages="1" perpage="100" total="2">
    <photo id="3928131" owner="39059360@N00" secret="ccb7e63151" server="4"
        title="testing title" ispublic="0" isfriend="0" isfamily="0"
        dateadded="1112231154" ownername="just testing" />
    <photo id="3681845" owner="26159919@N00" secret="17162c6459" server="2"
        title="Clouds" ispublic="1" isfriend="1" isfamily="1"
        dateadded="1112231067" ownername="drewish" />
</photos>
XML
);

define('TESTING_PHOTO_ID', '2733');
define('TESTING_XML_PHOTO_FROM_PHOTOSET', '<photo id="2733" secret="123456" server="12" isprimary="0" />');
define('TESTING_XML_PHOTO_FROM_NOTINSET', <<<XML
<photo id="2733" owner="12037949754@N01" secret="123456" server="12"
    title="orford_castle_taster" ispublic="1" isfriend="0" isfamily="0" />
XML
);
define('TESTING_XML_PHOTO_LONG', <<<XML
<photo id="2733" secret="123456" server="12" isfavorite="0" license="3">
    <owner nsid="12037949754@N01" username="Bees" realname="Cal Henderson"
        location="Bedford, UK" />
    <title>orford_castle_taster</title>
    <description>hello!</description>
    <visibility ispublic="1" isfriend="0" isfamily="0" />
    <dates posted="1100897479" taken="2004-11-19 12:51:19" takengranularity="0" />
    <permissions permcomment="3" permaddmeta="2" />
    <editability cancomment="1" canaddmeta="1" />
    <comments>1</comments>
    <notes>
        <note id="312" author="12037949754@N01" authorname="Bees" x="11" y="11"
            w="51" h="51">foo1</note>
        <note id="313" author="12037949754@N01" authorname="Bees" x="10" y="10"
            w="50" h="50">foo</note>
    </notes>
    <tags>
        <tag id="1234" author="12037949754@N01" raw="woo yay">wooyay</tag>
        <tag id="1235" author="12037949754@N01" raw="hoopla">hoopla</tag>
    </tags>
</photo>
XML
);
define('TESTING_XML_PHOTO_SIZES', <<<XML
<sizes>
    <size label="Square" width="75" height="75"
        source="http://photos12.flickr.com/2733_123456_s.jpg"
            url="http://www.flickr.com/photo_zoom.gne?id=2733&amp;size=sq" />
    <size label="Thumbnail" width="100" height="62"
        source="http://photos12.flickr.com/2733_123456_t.jpg"
            url="http://www.flickr.com/photo_zoom.gne?id=2733&amp;size=t" />
    <size label="Small" width="112" height="69"
        source="http://photos12.flickr.com/2733_123456_m.jpg"
            url="http://www.flickr.com/photo_zoom.gne?id=2733&amp;size=s" />
    <size label="Large" width="112" height="69"
        source="http://photos12.flickr.com/2733_123456_o.jpg"
            url="http://www.flickr.com/photo_zoom.gne?id=2733&amp;size=o" />
</sizes>
XML
);

define('TESTING_XML_PHOTOS', <<<XML
<photos page="2" pages="89" perpage="10" total="881">
    <photo id="2636" owner="47058503995@N01" secret="a123456" server="2"
        title="test_04" ispublic="1" isfriend="0" isfamily="0" />
    <photo id="2635" owner="47058503995@N01" secret="b123456" server="2"
        title="test_03" ispublic="0" isfriend="1" isfamily="1" />
    <photo id="2633" owner="47058503995@N01" secret="c123456" server="2"
        title="test_01" ispublic="1" isfriend="0" isfamily="0" />
    <photo id="2610" owner="12037949754@N01" secret="d123456" server="2"
        title="00_tall" ispublic="1" isfriend="0" isfamily="0" />
</photos>
XML
);

define('TESTING_XML_PHOTOSET_ID', '4');
define('TESTING_XML_PHOTOSETS', <<<XML
<photosets cancreate="1">
    <photoset id="5" primary="2483" secret="abcdef" server="8" photos="4">
        <title>Test</title>
        <description>foo</description>
    </photoset>
    <photoset id="4" primary="1234" secret="832659" server="3" photos="12">
        <title>My Set</title>
        <description>bar</description>
    </photoset>
</photosets>
XML
);
define('TESTING_XML_PHOTOSET', <<<XML
<photoset id="4" owner="12037949754@N01" primary="2483" photos="4">
    <title>My Set</title>
    <description>bar</description>
</photoset>
XML
);
define('TESTING_XML_PHOTOSET_PHOTOS', <<<XML
<photoset id="4" primary="2483">
    <photo id="2484" secret="123456" server="1" isprimary="0" />
    <photo id="2483" secret="123456" server="1" isprimary="1" />
    <photo id="2487" secret="123456" server="1" isprimary="1" />
    <photo id="2488" secret="123456" server="1" isprimary="1" />
    <photo id="2489" secret="123456" server="1" isprimary="1" />
</photoset>
XML
);

define('TESTING_XML_USER_ID', '39059360@N00');
define('TESTING_XML_USER_SHORT', <<<XML
<user nsid="39059360@N00">
    <username>just testing</username>
</user>
XML
);
define('TESTING_XML_USER_LONG', <<<XML
<person id="39059360@N00" nsid="39059360@N00" isadmin="0" ispro="0" iconserver="0">
    <username>just testing</username>
    <realname>phlickr test account</realname>
    <location>some place, some country</location>
    <photos>
        <firstdatetaken>2005-01-29 00:22:17</firstdatetaken>
        <firstdate>1106958137</firstdate>
        <count>36</count>
    </photos>
</person>
XML
);

define('TESTING_XML_USER_TAGS', <<<XML
<who id="39059360@N00">
    <tags>
        <tag>2004</tag>
        <tag>anewishtag</tag>
        <tag>another</tag>
        <tag>atag</tag>
        <tag>barf</tag>
        <tag>btag</tag>
        <tag>classtag</tag>
        <tag>classtagbarf</tag>
        <tag>gif</tag>
        <tag>newtag</tag>
        <tag>png</tag>
        <tag>searchtag</tag>
        <tag>something</tag>
    </tags>
</who>
XML
);
define('TESTING_XML_USER_POPULAR_TAGS', <<<XML
<who id="39059360@N00">
    <tags>
        <tag count="2">2004</tag>
        <tag count="1">anewishtag</tag>
        <tag count="1">another</tag>
        <tag count="39">atag</tag>
        <tag count="13">barf</tag>
        <tag count="39">btag</tag>
        <tag count="13">classtag</tag>
        <tag count="1">classtagbarf</tag>
        <tag count="7">searchtag</tag>
    </tags>
</who>
XML
);


define('TESTING_XML_USERS_PUBLIC_CONTACTS', <<<XML
<contacts>
    <contact nsid="12037949629@N01" username="Eric" ignored="1" />
    <contact nsid="12037949631@N01" username="neb" ignored="0" />
    <contact nsid="41578656547@N01" username="cal_abc" ignored="0" />
</contacts>
XML
);
define('TESTING_XML_USERS_PRIVATE_CONTACTS', <<<XML
<contacts>
    <contact nsid="12037949629@N01" username="Eric" realname="Eric Costello"
        friend="1" family="0" ignored="1" />
    <contact nsid="12037949631@N01" username="neb" realname="Ben Cerveny"
        friend="0" family="0" ignored="0" />
    <contact nsid="41578656547@N01" username="cal_abc" realname="Cal Henderson"
        friend="1" family="1" ignored="0" />
</contacts>
XML
);
define('TESTING_XML_USERS_ONLINE', <<<XML
<online>
    <user nsid="72037949754@N01" username="Jack" online="2" />
    <user nsid="12037949754@N01" username="Bees" online="1">Away!</user>
</online>
XML
);

define('TESTING_RESP_OK_PREFIX', <<<XML
<?xml version="1.0" encoding="utf-8" ?>
<rsp stat="ok">

XML
);

define('TESTING_RESP_FAIL_PREFIX', <<<XML
<?xml version="1.0" encoding="utf-8" ?>
<rsp stat="fail">
    <err code="1" msg="Photo not found" />

XML
);
define('TESTING_RESP_SUFIX', "\n</rsp>");

define('TESTING_RESP_OK', TESTING_RESP_OK_PREFIX . TESTING_XML_ECHO . TESTING_RESP_SUFIX);
define('TESTING_RESP_FAIL', TESTING_RESP_FAIL_PREFIX . TESTING_RESP_SUFIX);
define('TESTING_RESP_INVALID', "");
