Flash STREAM RELAYER application for FMS 3.5
by Javier Peletier (jpeletie@akamai.com)


This FMS application will subscribe to any given public RTMP stream and republish it in the Akamai Network. This is useful if the customer has an infrastructure already and they want to connect to Akamai easily.

This is part of the solution for La Sexta, a Spanish TV channel, where they had a WireCast 3.0 encoder. This encoder would only stream RTSP, so the solution was to connect WireCast to the Wowza media server (a Flash Media Server clone that would ingest RTSP) and republish the stream in Akamai.

INSTALLATION

To install this application, simply copy the included folder to your Flash Media Server applications folder. You can rename the folder to anything you like.

Edit the configuration file "config.asc" to subscribe to the desired source stream and configure the Akamai entry point information.

Restart Flash Media Server. The application will automatically connect to the source stream and republish it in the provided Akamai Entry point. If the connection is lost with either source or Akamai, the application will tear everything down and retry the connection automatically.

If you want to do primary/backup streaming, it is recommended that you create another instance of this application in a different folder and change the AKAMAI_ENTRYPOINT_TYPE to "backup".