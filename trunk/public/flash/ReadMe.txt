ReadMe for Constellation TV - March 20, 2011.

Contact Bruce Epstein (bruce@zeusprod.com or 908-705-0288) with any questions.

Andy Madsen generally takes the necessary files and posts them to the web. I'm not sure what folders/files he uses. So ask him.
 
Subfolders in this folder include:

1. aes_lostinactionscript_crypto - this folder contains a AES cryptography library from "LostInActionScript.com". Only one .as file in this folder is used. It is refererenced by Bruce's AEScrypto class in CommonLib/src/com/zeusprod/AEScrypto.as

2. CommonLib - some utils from Bruce for logging and a crypto helper. You'll generally need to link to this source folder from any project that uses these functions.

3. ovp - this folder contains the "theater" app named (AkamaiMultiPlayerExample.swf). Lousy name, I know. It is built by compiling this FLA in this subfolder (the structure represents the OVP/Akamai examples from openvideoplayer sourceforge web site: \flash\ovp\ovp_sourceforge_flash_trunk\players\akamai\multi\AkamaiMultiPlayerExample.fla

4 HostCamViewer - this app creates a stream from the Host's camera and sends it to Akamai. It is based on an example provided by Asai and then heavily customized by Bruce. It is a Flex 3 project and should be buildable in either Flex 3 or FlashBuilder 4. Bruce usually builds it on a Mac. You will need to tweak the build path to point to the right ../CommonLib/src folder one folder above the HostCamViewer folder. Not that Asai "CamViewer" is obsolete (replaced by Bruce's LiveStreamTest). Only the "HostCam" portion of the app is used from this folder.

5. LiveStreamTest - this app is the "release" version of Bruce's viewer based on the OVP LiveStream Akamai examples. It is built using Flash CS4 from the FLA in this older and relies on some libraries in other folders such as ../CommonLib/src  (see the FLA ActionScript build paths)

6. dsTest - I don't know what that is. It came from Asai and I don't think it is used.
