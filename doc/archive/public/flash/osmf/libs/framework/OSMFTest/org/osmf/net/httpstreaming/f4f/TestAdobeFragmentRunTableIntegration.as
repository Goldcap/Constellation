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

	public class TestAdobeFragmentRunTableIntegration extends TestCase
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
			
			assertTrue(abst.fragmentRunTables.length == 1);
			afrt = abst.fragmentRunTables[0] as AdobeFragmentRunTable;
		}
		
		public function testFindFragmentIdByTime():void
		{
			assertTrue(afrt.findFragmentIdByTime(0, abst.totalDuration).fragId == 1);
			assertTrue(afrt.findFragmentIdByTime(1000, abst.totalDuration).fragId == 1);
			assertTrue(afrt.findFragmentIdByTime(2000, abst.totalDuration).fragId == 1);
			assertTrue(afrt.findFragmentIdByTime(3000, abst.totalDuration).fragId == 1);
			assertTrue(afrt.findFragmentIdByTime(4000, abst.totalDuration).fragId == 1);
			assertTrue(afrt.findFragmentIdByTime(4399, abst.totalDuration).fragId == 1);
			assertTrue(afrt.findFragmentIdByTime(4400, abst.totalDuration).fragId == 2);
			assertTrue(afrt.findFragmentIdByTime(5000, abst.totalDuration).fragId == 2);
			assertTrue(afrt.findFragmentIdByTime(6000, abst.totalDuration).fragId == 2);
			assertTrue(afrt.findFragmentIdByTime(7000, abst.totalDuration).fragId == 2);
			assertTrue(afrt.findFragmentIdByTime(8000, abst.totalDuration).fragId == 2);
			assertTrue(afrt.findFragmentIdByTime(8799, abst.totalDuration).fragId == 2);
			assertTrue(afrt.findFragmentIdByTime(8800, abst.totalDuration).fragId == 3);
			assertTrue(afrt.findFragmentIdByTime(9000, abst.totalDuration).fragId == 3);
			assertTrue(afrt.findFragmentIdByTime(10000, abst.totalDuration).fragId == 3);
			assertTrue(afrt.findFragmentIdByTime(11000, abst.totalDuration).fragId == 3);
			assertTrue(afrt.findFragmentIdByTime(12000, abst.totalDuration).fragId == 3);
			assertTrue(afrt.findFragmentIdByTime(12100, abst.totalDuration).fragId == 3);
			assertTrue(afrt.findFragmentIdByTime(12107, abst.totalDuration).fragId == 3);
			assertTrue(afrt.findFragmentIdByTime(12108, abst.totalDuration).fragId == 4);
			
			// Jump to the end.
			assertTrue(afrt.findFragmentIdByTime(60000, abst.totalDuration) == null);
			assertTrue(afrt.findFragmentIdByTime(60800, abst.totalDuration) == null);
			assertTrue(afrt.findFragmentIdByTime(60922, abst.totalDuration) == null);
			assertTrue(afrt.findFragmentIdByTime(60923, abst.totalDuration) == null);
		}

		public function testTotalDuration():void
		{
			assertTrue(afrt.totalDuration == 60923);
		}
		
		// Internals
		//
		
		private var afrt:AdobeFragmentRunTable;
		private var abst:AdobeBootstrapBox;
	}
}