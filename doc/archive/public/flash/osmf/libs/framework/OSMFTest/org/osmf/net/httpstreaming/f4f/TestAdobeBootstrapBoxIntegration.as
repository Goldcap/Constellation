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
	import flash.utils.ByteArray;
	
	import flexunit.framework.TestCase;
	
	import mx.utils.Base64Decoder;
	
	import org.osmf.utils.TestConstants;
	
	public class TestAdobeBootstrapBoxIntegration extends TestCase
	{
		override public function setUp():void
		{
			var decoder:Base64Decoder = new Base64Decoder();
			decoder.decode(TestConstants.ABST_BOX_DATA);
			var bytes:ByteArray = decoder.drain();

			var parser:BoxParser = new BoxParser();
			parser.init(bytes);
			var boxes:Vector.<Box> = parser.getBoxes();
			assertTrue(boxes.length == 1);
			
			abst = boxes[0] as AdobeBootstrapBox;
			assertTrue(abst != null);
		}
		
		public function testFindSegmentId():void
		{
			// Segment number increases every 15 fragments.
			assertTrue(abst.findSegmentId(0) == 0);
			for (var i:int = 1; i <= 15; i++)
			{			
				assertTrue(abst.findSegmentId(i) == 1);
			}
			for (i = 16; i <= 30; i++)
			{			
				assertTrue(abst.findSegmentId(i) == 2);
			}
			assertTrue(abst.findSegmentId(31) == 3);
		}
		
		public function testTotalDuration():void
		{
			assertTrue(abst.totalDuration == 60905);
		}

		public function testTotalFragments():void
		{
			assertTrue(abst.totalFragments == 14);
		}
		
		// Internals
		//
		
		private var abst:AdobeBootstrapBox;
	}
}