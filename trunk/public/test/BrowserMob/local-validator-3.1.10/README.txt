BrowserMob Local Validator
Copyright (c) 2011 Neustar, Inc.

1.  Introduction
2.  Native Mouse and Keyboard Integration
3.  Usage


1.  Introduction

The BrowserMob Local Validator may be used to perform validation of BrowserMob JavaScript test scripts.

There are two directories:

bin - contains the execution scripts used to run the local validator
lib - contains the Java libraries and third party components


2.  Native Mouse and Keyboard Integration

In order to use native mouse and keyboard integration you will need to complete the installation by downloading a copy of the open source browsermob-vnc library and installing it in the lib directory.
(see the BrowserMob blog for more details: http://blog.browsermob.com/2009/12/flash-and-flex-automation-options-using-selenium/)

You can download a copy of the Open Source BrowserMob VNC library here:

http://search.maven.org/remotecontent?filepath=org/browsermob/browsermob-vnc/1.0-beta-1/browsermob-vnc-1.0-beta-1.jar

Once downloaded, copy the file to the lib directory.

Before you can use the VNC library, you need to have a VNC server running (e.g. "Vine Server" for OS X, or "Ultra VNC" for Windows).


3.  Usage

The validator accepts parameters as documented using the "-?" option

EXAMPLE:

cd bin
validator -?
BrowserMob Local Validator   1.1
Copyright (c) Neustar, Inc. - All Rights Reserved.
usage: Main
 -?            Help
 -firebug      Launch with Firebug
 -test <arg>   Script File Pathname

To validate a specific script file supply the pathname wth the "-test" argument.

EXAMPLE:

cd bin
validator -test example-script.js


In order to perform the validation procedure you must have Firefox version 3 installed.

If you wish to specify a particular location for the Firefox executable you may do so by creating a "config.properties" file containing the following entry:

FF3=/ff3/firefox.exe

Where "/ff3/firefox.exe" is an absolute pathname to the Firefox executable you wish to use for the validation process.

The "config.properties" file is commonly located in the ".browserMob" directory under your user directory.  Under Windows this location defaults to:

C:\Documents and Settings\user\.browserMob

Please refer to the BrowserMob site for further assistance:  https://browsermob.com/tools

