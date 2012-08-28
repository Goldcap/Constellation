Sample Application: MASTSample

A. Overview

The MASTSample sample application demonstrates the use of the MAST Actionscript plugin to retrieve a
MAST document, parse it into a MAST object model, and play a pre-roll ad before a video.  

B. Installation Instructions (Flex Builder)

1. Unzip/copy the MASTSample project into your Flex Builder workspace folder. 
2. In Flex Builder, go to the File menu and select "Import".
3. Select "General", then "Existing Projects into Workspace", and click "Next".
4. Choose "Select root directory" and "Browse".
5. Browse to your Flex Builder workspace folder.
6. Select the checkbox next to "MASTSample", and click "Finish".  This will import the project.
7. Build the project.
8. Launch the application from the Run menu.

NOTE: this sample project requires the MediaFramework project and the VAST project, you will need to 
import those Flex projects as well in order to build the MASTSample project.

C. Usage Instructions

The MASTSample app is a pure AS3 application which loads a MAST document, parses it and displays a preroll video ad.
When you run the application, you'll see a 10-15 second preroll, followed by a 30 second video.  The preroll
is generated from the VAST document located at the URL referenced in MASTSample.as. 

Note that not all MAST elements are supported in the current release.  The OSMF MAST specification (located
at http://opensource.adobe.com/wiki/display/osmf/MAST+Support has additional details on the current scope of
MAST support within OSMF.
