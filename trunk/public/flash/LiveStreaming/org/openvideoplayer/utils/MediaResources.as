//
// Copyright (c) 2009-2011, the Open Video Player authors. All rights reserved.
//
// Redistribution and use in source and binary forms, with or without 
// modification, are permitted provided that the following conditions are 
// met:
//
//    * Redistributions of source code must retain the above copyright 
//		notice, this list of conditions and the following disclaimer.
//    * Redistributions in binary form must reproduce the above 
//		copyright notice, this list of conditions and the following 
//		disclaimer in the documentation and/or other materials provided 
//		with the distribution.
//    * Neither the name of the openvideoplayer.org nor the names of its 
//		contributors may be used to endorse or promote products derived 
//		from this software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS 
// "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT 
// LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR 
// A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT 
// OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, 
// SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT 
// LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, 
// DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY 
// THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT 
// (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE 
// OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
//
package org.openvideoplayer.utils
{    
    public final class MediaResources
    {
        public static const PROGRESSIVE_DOWNLOAD:String = "http://mediapm.edgesuite.net/ovp/content/demo/video/elephants_dream/elephants_dream_768x428_24.0fps_608kbps.mp4"
        public static const PROGRESSIVE_DOWNLOAD_BWESTIMATE:String = "http://products.edgeboss.net/download/products/jsherry/testfiles/stream001.flv"; 		
        public static const RTMP_STREAMING:Object = {hostName:"cp67126.edgefcs.net/ondemand", streamName:"mediapm/strobe/content/test/SpaceAloneHD_sounas_640_500_short"};
        public static const RTMP_STREAMING_MP4:Object = {hostName:"cp67126.edgefcs.net/ondemand", streamName:"mp4:mediapm/ovp/content/demo/video/elephants_dream/elephants_dream_768x428_24.0fps_608kbps.mp4"};
        public static const RTMP_STREAMING_CAPTIONS:Object = {hostName:"cp67126.edgefcs.net/ondemand", streamName:"mediapm/ovp/content/test/video/Akamai_10_Year_F8_512K"};
        public static const RTMP_STREAMING_CUEPOINTS:Object = {hostName:"cp27886.edgefcs.net/ondemand", streamName:"14808/nocc_small307K"};
        //rtmp://cp113557.live.edgefcs.net/live/<playpath>constellation@45907
		public static const RTMP_STREAMING_LIVE:Object = {
								hostName: "cp113557.live.edgefcs.net/live", 
								streamName:"constellation@45907", 
								authToken:"auth=yyyy&amp;aifp=zzzz"};
		/*public static const RTMP_STREAMING_LIVE:Object = {
								hostName: "cp34973.live.edgefcs.net/live", 
								streamName:"Flash_live_bm_500K@9319", 
								authToken:"auth=yyyy&amp;aifp=zzzz"};
								*/
								
        public static const SMIL_FILE_1:String = "http://mediapm.edgesuite.net/ovp/content/demo/smil/elephants_dream.smil"; 
		public static const SMIL_FILE_LIVE:String = "http://mediapm.edgesuite.net/ovp/content/demo/smil/akamai_fms_live.smil";
		public static const SMIL_FILE_SUBCLIP:String = "http://mediapm.edgesuite.net/ovp/content/demo/smil/elephants-dream-sub-clips2.smil";
    }
}