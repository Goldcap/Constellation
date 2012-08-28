Fairway FlexBox jQuery Plugin
=============================
Find latest version to download, and issue tracker, at http://flexbox.codeplex.com
Find latest documentation and demos at http://fairwaytech.com/flexbox or in the Documentation folder (index.html)

Copyright (c) 2008-2010 Noah Heldman and Fairway Technologies (http://www.fairwaytech.com/flexbox)
Licensed under Ms-PL (http://www.codeplex.com/flexbox/license)


FlexBox is a jQuery plugin that is intended to be a very flexible replacement for html
textboxes and dropdowns, optionally using ajax to retrieve and bind JSON data.

It can be used as a:
 - ComboBox, with optional per-result html templates
 - Suggest box, like Google's search
 - Data-driven type-ahead input box

It supports:
 - Auto-completion using local (JSON) or remote (JSON via ajax) data
 - Skinning via css
 - Flexible paging of results
 - Configurable client-side caching
 - Much more... (see Configuration Options in the documentation)


HISTORY:

0.9.6 (2010-11-24):
 - When selecting a matching value, the corresponding id is POSTed (form field name matches FlexBox id)
 - When typing a value not in the list, the new value is POSTed (form field name matches FlexBox id)
 - Updated demos.html to reflect changes
 
0.9.5 (2010-11-16):
 - Added new option "matchAny" (default true), for client-side filtering ONLY, which will match the search term anywhere within the string (set to false to match beginning only)
 
0.9.4 (2010-10-07):
 - 8972: ISSUE: Dropdown list does not stay when scrolling down in IE8
 
0.9.3 (2010-09-30):
 - Client-side JSON filtering
 - MANY bug fixes
 - Browser compatibility improvements
 - Enhanced documentation
 - New source code bundle

0.9.2 (2008-12-17):
 - 3496: FEATURE: Allow client-side paging
 - 3319: FEATURE: Allow passing JSON object as first parameter to FlexBox 
 - 3405: ISSUE: Disable autocompleter
 - 3323: ISSUE: Backspace key in input box does not work elegantly

0.9.1 (2008-11-15):
 - Defect fixes from initial release
 - 3119: Filtered List Highlighting Implies a "Like" Search
 - 3247: Disable automatic insertion of a potential match
 - 3248: On Tab, Select highlighted entry and move to next field
 - 3297: Support POST in addition to GET to retrieve JSON data

0.9.0 (2008-11-06):
 - Initial public release (beta)