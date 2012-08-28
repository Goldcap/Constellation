/*****************************************************
*  
*  Copyright 2009 Adobe Systems Incorporated.  All Rights Reserved.
*  
*****************************************************
*  The contents of this file are subject to the Mozilla Public License
*  Version 1.1 (the "License"); you may not use this file except in
*  compliance with the License. You may obtain a copy of the License at
*  http://www.mozilla.org/MPL/
*   
*  Software distributed under the License is distributed on an "AS IS"
*  basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
*  License for the specific language governing rights and limitations
*  under the License.
*   
*  
*  The Initial Developer of the Original Code is Adobe Systems Incorporated.
*  Portions created by Adobe Systems Incorporated are Copyright (C) 2009 Adobe Systems 
*  Incorporated. All Rights Reserved. 
*  
*****************************************************/
package org.osmf.net.httpstreaming.f4f
{
	import __AS3__.vec.Vector;
	
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.net.URLLoader;
	import flash.net.URLRequest;
	import flash.utils.ByteArray;
	
	import flexunit.framework.TestCase;
	
	import mx.utils.Base64Decoder;
	
	import org.osmf.utils.TestConstants;
	
	public class TestBoxParser extends TestCase
	{
		override public function setUp():void
		{
			super.setUp();
			
			eventDispatcher = new EventDispatcher();
			base64Data = null;
		}
		
		public function testGetBoxesWithABST():void
		{
			var decoder:Base64Decoder = new Base64Decoder();
			decoder.decode(TestConstants.ABST_BOX_DATA);
			var bytes:ByteArray = decoder.drain();

			var parser:BoxParser = new BoxParser();
			parser.init(bytes);
			var boxes:Vector.<Box> = parser.getBoxes();
			assertTrue(boxes.length == 1);
			
			var abst:AdobeBootstrapBox = boxes[0] as AdobeBootstrapBox;
			assertTrue(abst != null);
			
			// Verify the structure.
			//
			
			// Box
			assertTrue(abst.size == 282);
			assertTrue(abst.type == "abst");
			assertTrue(abst.boxLength == 8);
			
			// FullBox
			assertTrue(abst.version == 0);
			assertTrue(abst.flags == 0);
			
			// AdobeBootstrapBox
			assertTrue(abst.bootstrapVersion == 15);
			assertTrue(abst.profile == 0);
			assertTrue(abst.live == false);
			assertTrue(abst.update == false);
			assertTrue(abst.timeScale == 1000);
			assertTrue(abst.currentMediaTime == 60905);
			assertTrue(abst.smpteTimeCodeOffset == 0);
			assertTrue(abst.movieIdentifier == "");
			assertTrue(abst.serverBaseURLs.length == 0);
			assertTrue(abst.qualitySegmentURLModifiers.length == 0);
			assertTrue(abst.drmData == "");
			assertTrue(abst.metadata == "");
			
			// SegmentRunTable
			assertTrue(abst.segmentRunTables.length == 1);
			var asrt:AdobeSegmentRunTable = abst.segmentRunTables[0] as AdobeSegmentRunTable;
			assertTrue(asrt != null);
			assertTrue(asrt.size == 25);
			assertTrue(asrt.type == "asrt");
			assertTrue(asrt.version == 0);
			assertTrue(asrt.flags == 0);
			assertTrue(asrt.boxLength == 8)
			assertTrue(asrt.segmentFragmentPairs.length == 1);
			
			// SegmentFragmentPair
			var sfp:SegmentFragmentPair = asrt.segmentFragmentPairs[0] as SegmentFragmentPair;
			assertTrue(sfp);
			assertTrue(sfp.firstSegment == 1);
			assertTrue(sfp.fragmentsPerSegment == 15);
			assertTrue(sfp.fragmentsAccrued == 0);
			
			// FragmentRunTable
			assertTrue(abst.fragmentRunTables.length == 1);
			var afrt:AdobeFragmentRunTable = abst.fragmentRunTables[0] as AdobeFragmentRunTable;
			assertTrue(afrt != null);
			assertTrue(afrt.size == 213);
			assertTrue(afrt.type == "afrt");
			assertTrue(afrt.version == 0);
			assertTrue(afrt.flags == 0);
			assertTrue(afrt.boxLength == 8)
			assertTrue(afrt.timeScale == 1000);
			assertTrue(afrt.fragmentDurationPairs.length == 12);
			verifyFragmentDurationPair(afrt.fragmentDurationPairs[0],   1, 4400, 0,     0);
			verifyFragmentDurationPair(afrt.fragmentDurationPairs[1],   3, 3300, 8808,  0);
			verifyFragmentDurationPair(afrt.fragmentDurationPairs[2],   4, 4400, 12112, 0);
			verifyFragmentDurationPair(afrt.fragmentDurationPairs[3],   6, 3300, 20920, 0);
			verifyFragmentDurationPair(afrt.fragmentDurationPairs[4],   7, 4400, 24224, 0);
			verifyFragmentDurationPair(afrt.fragmentDurationPairs[5],   9, 3200, 33066, 0);
			verifyFragmentDurationPair(afrt.fragmentDurationPairs[6],  10, 4400, 36302, 0);
			verifyFragmentDurationPair(afrt.fragmentDurationPairs[7],  11, 3800, 40707, 0);
			verifyFragmentDurationPair(afrt.fragmentDurationPairs[8],  12, 4400, 44511, 0);
			verifyFragmentDurationPair(afrt.fragmentDurationPairs[9],  13, 3300, 48915, 0);
			verifyFragmentDurationPair(afrt.fragmentDurationPairs[10], 14, 4400, 52218, 0);
			verifyFragmentDurationPair(afrt.fragmentDurationPairs[11], 15, 4300, 56623, 0);
		}

		public function testGetBoxesWithAFRA():void
		{
			var decoder:Base64Decoder = new Base64Decoder();
			decoder.decode(TestConstants.AFRA_BOX_DATA);
			var bytes:ByteArray = decoder.drain();

			var parser:BoxParser = new BoxParser();
			parser.init(bytes);
			var boxes:Vector.<Box> = parser.getBoxes();
			assertTrue(boxes.length == 1);
			
			var afra:AdobeFragmentRandomAccessBox = boxes[0] as AdobeFragmentRandomAccessBox;
			assertTrue(afra != null);
			
			verifyAdobeFragmentRandomAccessBox(afra);
		}
		
		public function testReadFragmentRandomAccessBox():void
		{
			var decoder:Base64Decoder = new Base64Decoder();
			decoder.decode(TestConstants.AFRA_BOX_DATA);
			var bytes:ByteArray = decoder.drain();

			var parser:BoxParser = new BoxParser();
			parser.init(bytes);
			var boxInfo:BoxInfo = parser.getNextBoxInfo();
			
			var afra:AdobeFragmentRandomAccessBox = parser.readFragmentRandomAccessBox(boxInfo);
			assertTrue(afra != null);
			
			verifyAdobeFragmentRandomAccessBox(afra);
		}
		
		public function testGetBoxesWithMultipleBoxes():void
		{
			callAfterLoad(doTestGetBoxes);
		}
		
		private function doTestGetBoxes():void
		{
			var decoder:Base64Decoder = new Base64Decoder();
			decoder.decode(base64Data);
			var bytes:ByteArray = decoder.drain();

			var parser:BoxParser = new BoxParser();
			parser.init(bytes);
			var boxes:Vector.<Box> = parser.getBoxes();
			assertTrue(boxes.length == 6);
			
			assertTrue(boxes[0] is AdobeFragmentRandomAccessBox);
			assertTrue(boxes[1] is AdobeBootstrapBox);
			assertTrue(boxes[2] is MediaDataBox);
			assertTrue(boxes[3] is AdobeFragmentRandomAccessBox);
			assertTrue(boxes[4] is AdobeBootstrapBox);
			assertTrue(boxes[5] is MediaDataBox);
			
			// Verify a few fields from a few boxes.
			//
			
			var afra:AdobeFragmentRandomAccessBox = boxes[0] as AdobeFragmentRandomAccessBox;
			assertTrue(afra.size == 37);
			assertTrue(afra.globalRandomAccessEntries.length == 0);
			assertTrue(afra.localRandomAccessEntries.length == 1);
			assertTrue(LocalRandomAccessEntry(afra.localRandomAccessEntries[0]).time == 0);
			assertTrue(LocalRandomAccessEntry(afra.localRandomAccessEntries[0]).offset == 21551);
			
			var mdat:MediaDataBox = boxes[2] as MediaDataBox;
			assertTrue(mdat.size == 1044084);
			assertTrue(mdat.type == "mdat");
			assertTrue(mdat.boxLength == 16);
			assertTrue(mdat.data != null);

			afra = boxes[3] as AdobeFragmentRandomAccessBox;
			assertTrue(afra.size == 37);
			assertTrue(afra.globalRandomAccessEntries.length == 0);
			assertTrue(afra.localRandomAccessEntries.length == 1);
			assertTrue(LocalRandomAccessEntry(afra.localRandomAccessEntries[0]).time == 4404);
			assertTrue(LocalRandomAccessEntry(afra.localRandomAccessEntries[0]).offset == 3219);

			mdat = boxes[5] as MediaDataBox;
			assertTrue(mdat.size == 140828);
			assertTrue(mdat.type == "mdat");
			assertTrue(mdat.boxLength == 16);
			assertTrue(mdat.data != null);
		}
		
		private function verifyAdobeFragmentRandomAccessBox(afra:AdobeFragmentRandomAccessBox):void
		{
			// Verify the structure.
			//
			
			// Box
			assertTrue(afra.size == 505);
			assertTrue(afra.type == "afra");
			assertTrue(afra.boxLength == 8);
			
			// FullBox
			assertTrue(afra.version == 0);
			assertTrue(afra.flags == 0);
			
			// AdobeFragmentRandomAccessBox
			assertTrue(afra.timeScale == 1000);
			assertTrue(afra.localRandomAccessEntries.length == 0);
			assertTrue(afra.globalRandomAccessEntries.length == 15);
			verifyGlobalRandomAccessEntry(afra.globalRandomAccessEntries[0],      0, 1, 1,    663378, 0);
			verifyGlobalRandomAccessEntry(afra.globalRandomAccessEntries[1],   4404, 1, 2,   4204379, 0);
			verifyGlobalRandomAccessEntry(afra.globalRandomAccessEntries[2],   8808, 1, 3,   7527832, 0);
			verifyGlobalRandomAccessEntry(afra.globalRandomAccessEntries[3],  12112, 1, 4,   9914593, 0);
			verifyGlobalRandomAccessEntry(afra.globalRandomAccessEntries[4],  16516, 1, 5,  13642093, 0);
			verifyGlobalRandomAccessEntry(afra.globalRandomAccessEntries[5],  20920, 1, 6,  16914747, 0);
			verifyGlobalRandomAccessEntry(afra.globalRandomAccessEntries[6],  24224, 1, 7,  18970007, 0);
			verifyGlobalRandomAccessEntry(afra.globalRandomAccessEntries[7],  28628, 1, 8,  23044620, 0);
			verifyGlobalRandomAccessEntry(afra.globalRandomAccessEntries[8],  33066, 1, 9,  26887336, 0);
			verifyGlobalRandomAccessEntry(afra.globalRandomAccessEntries[9],  36302, 1, 10, 29362083, 0);
			verifyGlobalRandomAccessEntry(afra.globalRandomAccessEntries[10], 40707, 1, 11, 31707812, 0);
			verifyGlobalRandomAccessEntry(afra.globalRandomAccessEntries[11], 44511, 1, 12, 35157965, 0);
			verifyGlobalRandomAccessEntry(afra.globalRandomAccessEntries[12], 48915, 1, 13, 38464620, 0);
			verifyGlobalRandomAccessEntry(afra.globalRandomAccessEntries[13], 52218, 1, 14, 40967843, 0);
			verifyGlobalRandomAccessEntry(afra.globalRandomAccessEntries[14], 56623, 1, 15, 44960711, 0);
		}
		
		// Internals
		//
		
		private function callAfterLoad(func:Function, triggerTestCompleteEvent:Boolean=true):void
		{
			if (triggerTestCompleteEvent)
			{
				eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, ASYNC_DELAY));
			}

			var urlRequest:URLRequest = new URLRequest("assets/mp4_bytes.txt");
			var urlLoader:URLLoader = new URLLoader();
			urlLoader.addEventListener(Event.COMPLETE, onLoadComplete);
			urlLoader.load(urlRequest);
			
			function onLoadComplete(event:Event):void
			{
				urlLoader.removeEventListener(Event.COMPLETE, onLoadComplete);
				
				base64Data = urlLoader.data;
				
				func.call();
				
				if (triggerTestCompleteEvent)
				{
					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
			}
		}
		
		private function verifyFragmentDurationPair(fdp:FragmentDurationPair, firstFragment:uint, duration:uint, durationAccrued:Number, discontinuityIndicator:uint):void
		{
			assertTrue(fdp != null);
			assertTrue(fdp.firstFragment == firstFragment);
			assertTrue(fdp.duration == duration);
			assertTrue(fdp.durationAccrued == durationAccrued);
			assertTrue(fdp.discontinuityIndicator == discontinuityIndicator);
		}

		private function verifyGlobalRandomAccessEntry(grae:GlobalRandomAccessEntry, time:Number, segment:uint, fragment:uint, afraOffset:Number, offsetFromAfra:Number):void
		{
			assertTrue(grae != null);
			assertTrue(grae.time == time);
			assertTrue(grae.segment == segment);
			assertTrue(grae.fragment == fragment);
			assertTrue(grae.afraOffset == afraOffset);
			assertTrue(grae.offsetFromAfra == offsetFromAfra);
		}
		
		private function mustReceiveEvent(event:Event):void
		{
			// Placeholder
		}
		
		private static const ASYNC_DELAY:Number = 5000;
				
		private var eventDispatcher:EventDispatcher;
		private var base64Data:String;
	}
}