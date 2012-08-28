The DVRSample sample defines an application that illustrates how to connect to a DVRCast equipped FMS server that is in progress of recording a live stream.

Features:

* Exposes a control bar as defined by the ChromeLibrary project.
* On detecting that the constructed media exposes a DVRTrait object, the UI will:
    * Expose a "rec" button, indicating that the live stream is being recorded on the server. On clicking the "rec" button, the player will seek to the most live position available at that time.
    * Expose a "live" glyph indicating that the playhead is currently at the most live position available.

By leveraging the DefaultMediaFactory class, the player can distill the following media element types from the provided input URL:

Usage:

After opening the project in FlexBuilder, open DVRSample.as. The clause at line 78 needs to be commented out, and the server URL that's there needs to be replaced by a url that points to an FMS server instance that is in progress of recording a live stream.

DVRSample uses the Standard 07_55 font by Craig Kroeger. Please note that this is *not* a free font. Please visit http://www.miniml.com for more licensing information. 