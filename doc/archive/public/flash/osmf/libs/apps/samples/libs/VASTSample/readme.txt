Sample Application: VASTSample

A. Overview

The VASTSample sample application demonstrates the use of the VAST Actionscript library to retrieve a
VAST document, parse it into a VAST object model, and generate one or more MediaElements that correspond
to the playback instructions of that VAST document.  

B. Installation Instructions (Flex Builder)

1. Unzip/copy the VASTSample project into your Flex Builder workspace folder. 
2. In Flex Builder, go to the File menu and select "Import".
3. Select "General", then "Existing Projects into Workspace", and click "Next".
4. Choose "Select root directory" and "Browse".
5. Browse to your Flex Builder workspace folder.
6. Select the checkbox next to "VASTSample", and click "Finish".  This will import the project.
7. Build the project.
8. Launch the application from the Run menu.

NOTE: this sample project requires the MediaFramework project and the VAST project, you will need to 
import those Flex projects as well in order to build the VASTSample project.

C. Usage Instructions

The VASTSample app is a pure AS3 application which loads a VAST document as as preroll or postroll video ad.
When you run the application, you'll see a 10-15 second preroll, followed by a 30 second video.  The preroll
is generated from the VAST document located at the URL referenced in VASTSample.as.  If the VAST document
contains Impression or TrackingEvent elements, then the corresponding "pings" will be triggered at the
appropriate times during playback.  (You can follow these "pings" by sniffing the HTTP traffic during
playback.)

To have the VAST ad package play as a postroll, simply change the second parameter to the loadMediaWithAd()
method in VASTSample.

You can also use this application with other VAST documents by changing the VAST_DOCUMENT_URL property in
VASTSample.as.  Note that the class currently holds two example URLs, one for a VAST document that contains
an inline ad, another for a VAST document that contains a wrapper ad.

Note that not all VAST elements are supported in the current release.  The OSMF VAST specification (located
at http://opensource.adobe.com/wiki/display/osmf/VAST+Support has additional details on the current scope of
VAST support within OSMF.
