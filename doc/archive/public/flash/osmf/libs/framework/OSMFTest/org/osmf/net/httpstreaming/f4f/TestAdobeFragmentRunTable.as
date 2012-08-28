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
	
	import flash.utils.ByteArray;
	
	import flexunit.framework.TestCase;
	
	import org.osmf.net.httpstreaming.AdobeBootstrapBoxDescriptor;
	import org.osmf.net.httpstreaming.HTTPStreamingTestsHelper;
	
	public class TestAdobeFragmentRunTable extends TestCase
	{
		override public function setUp():void
		{
			abstDescriptor = HTTPStreamingTestsHelper.createBasicAdobeBootstrapBoxDescriptor(); 						
			var bytes:ByteArray = HTTPStreamingTestsHelper.createAdobeBootstrapBox(abstDescriptor);

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
			assertTrue(afrt.findFragmentIdByTime(4399, abst.totalDuration).fragId == 2);
			assertTrue(afrt.findFragmentIdByTime(4400, abst.totalDuration).fragId == 2);
			assertTrue(afrt.findFragmentIdByTime(5000, abst.totalDuration).fragId == 2);
			assertTrue(afrt.findFragmentIdByTime(6000, abst.totalDuration).fragId == 2);
			assertTrue(afrt.findFragmentIdByTime(7000, abst.totalDuration).fragId == 2);
			assertTrue(afrt.findFragmentIdByTime(8000, abst.totalDuration).fragId == 2);
			assertTrue(afrt.findFragmentIdByTime(8799, abst.totalDuration).fragId == 3);
			assertTrue(afrt.findFragmentIdByTime(8800, abst.totalDuration).fragId == 3);
			assertTrue(afrt.findFragmentIdByTime(9000, abst.totalDuration).fragId == 3);
			assertTrue(afrt.findFragmentIdByTime(10000, abst.totalDuration).fragId == 3);
			assertTrue(afrt.findFragmentIdByTime(11000, abst.totalDuration).fragId == 3);
			assertTrue(afrt.findFragmentIdByTime(12000, abst.totalDuration).fragId == 3);
			assertTrue(afrt.findFragmentIdByTime(12100, abst.totalDuration).fragId == 4);
			assertTrue(afrt.findFragmentIdByTime(12107, abst.totalDuration).fragId == 4);
			assertTrue(afrt.findFragmentIdByTime(12108, abst.totalDuration).fragId == 4);
			assertTrue(afrt.findFragmentIdByTime(16001, abst.totalDuration).fragId == 7);
			assertTrue(afrt.findFragmentIdByTime(18001, abst.totalDuration).fragId == 12);
			assertTrue(afrt.findFragmentIdByTime(21999, abst.totalDuration).fragId == 15);			
			
			// Jump to the end.
			assertTrue(afrt.findFragmentIdByTime(60000, abst.totalDuration) == null);
			assertTrue(afrt.findFragmentIdByTime(60800, abst.totalDuration) == null);
			assertTrue(afrt.findFragmentIdByTime(60922, abst.totalDuration) == null);
			assertTrue(afrt.findFragmentIdByTime(60923, abst.totalDuration) == null);

			var table:AdobeFragmentRunTable = new AdobeFragmentRunTable();
			assertTrue(table.findFragmentIdByTime(0, abst.totalDuration) == null);
			assertTrue(table.findFragmentIdByTime(1000, abst.totalDuration) == null);
			assertTrue(table.findFragmentIdByTime(24000, abst.totalDuration) == null);
		}

		public function testValidateFragment():void
		{
			assertTrue(afrt.validateFragment(1, abst.totalDuration).fragId == 1);
			assertTrue(afrt.validateFragment(2, abst.totalDuration).fragId == 2);
			assertTrue(afrt.validateFragment(3, abst.totalDuration).fragId == 3);
			assertTrue(afrt.validateFragment(4, abst.totalDuration).fragId == 4);
			assertTrue(afrt.validateFragment(5, abst.totalDuration).fragId == 7);
			assertTrue(afrt.validateFragment(6, abst.totalDuration).fragId == 7);
			assertTrue(afrt.validateFragment(7, abst.totalDuration).fragId == 7);
			assertTrue(afrt.validateFragment(8, abst.totalDuration).fragId == 8);
			assertTrue(afrt.validateFragment(9, abst.totalDuration).fragId == 9);
			assertTrue(afrt.validateFragment(10, abst.totalDuration).fragId == 10);
			assertTrue(afrt.validateFragment(11, abst.totalDuration).fragId == 12);
			assertTrue(afrt.validateFragment(12, abst.totalDuration).fragId == 12);
			assertTrue(afrt.validateFragment(13, abst.totalDuration).fragId == 13);
			assertTrue(afrt.validateFragment(14, abst.totalDuration).fragId == 14);
			assertTrue(afrt.validateFragment(15, abst.totalDuration).fragId == 15);
			assertTrue(afrt.validateFragment(16, abst.totalDuration) == null);
			assertTrue(afrt.validateFragment(17, abst.totalDuration) == null);
			assertTrue(afrt.validateFragment(18, abst.totalDuration) == null);
			
			var pairs:Vector.<FragmentDurationPair> = afrt.fragmentDurationPairs;
			pairs.pop();

			assertTrue(afrt.validateFragment(1, abst.totalDuration).fragId == 1);
			assertTrue(afrt.validateFragment(2, abst.totalDuration).fragId == 2);
			assertTrue(afrt.validateFragment(3, abst.totalDuration).fragId == 3);
			assertTrue(afrt.validateFragment(4, abst.totalDuration).fragId == 4);
			assertTrue(afrt.validateFragment(5, abst.totalDuration).fragId == 7);
			assertTrue(afrt.validateFragment(6, abst.totalDuration).fragId == 7);
			assertTrue(afrt.validateFragment(7, abst.totalDuration).fragId == 7);
			assertTrue(afrt.validateFragment(8, abst.totalDuration).fragId == 8);
			assertTrue(afrt.validateFragment(9, abst.totalDuration).fragId == 9);
			assertTrue(afrt.validateFragment(10, abst.totalDuration).fragId == 10);
			assertTrue(afrt.validateFragment(11, abst.totalDuration).fragId == 12);
			assertTrue(afrt.validateFragment(12, abst.totalDuration).fragId == 12);
			assertTrue(afrt.validateFragment(13, abst.totalDuration).fragId == 13);
			assertTrue(afrt.validateFragment(14, abst.totalDuration).fragId == 14);
			assertTrue(afrt.validateFragment(15, abst.totalDuration).fragId == 15);
			assertTrue(afrt.validateFragment(16, abst.totalDuration) == null);
			assertTrue(afrt.validateFragment(17, abst.totalDuration) == null);
			assertTrue(afrt.validateFragment(18, abst.totalDuration) == null);
			
			abst.currentMediaTime = 21998;

			assertTrue(afrt.validateFragment(1, abst.totalDuration).fragId == 1);
			assertTrue(afrt.validateFragment(2, abst.totalDuration).fragId == 2);
			assertTrue(afrt.validateFragment(3, abst.totalDuration).fragId == 3);
			assertTrue(afrt.validateFragment(4, abst.totalDuration).fragId == 4);
			assertTrue(afrt.validateFragment(5, abst.totalDuration).fragId == 7);
			assertTrue(afrt.validateFragment(6, abst.totalDuration).fragId == 7);
			assertTrue(afrt.validateFragment(7, abst.totalDuration).fragId == 7);
			assertTrue(afrt.validateFragment(8, abst.totalDuration).fragId == 8);
			assertTrue(afrt.validateFragment(9, abst.totalDuration).fragId == 9);
			assertTrue(afrt.validateFragment(10, abst.totalDuration).fragId == 10);
			assertTrue(afrt.validateFragment(11, abst.totalDuration).fragId == 12);
			assertTrue(afrt.validateFragment(12, abst.totalDuration).fragId == 12);
			assertTrue(afrt.validateFragment(13, abst.totalDuration).fragId == 13);
			assertTrue(afrt.validateFragment(14, abst.totalDuration).fragId == 14);
			assertTrue(afrt.validateFragment(15, abst.totalDuration).fragId == 15);
			assertTrue(afrt.validateFragment(16, abst.totalDuration) == null);
		}
		
		public function testTableComplete():void
		{
			assertTrue(afrt.tableComplete());

			var pairs:Vector.<FragmentDurationPair> = afrt.fragmentDurationPairs;
			pairs.pop();

			assertTrue(!afrt.tableComplete());

			var table:AdobeFragmentRunTable = new AdobeFragmentRunTable();
			assertTrue(!table.tableComplete());
		}
		
		public function testFragmentsLeft():void
		{
			var table:AdobeFragmentRunTable = new AdobeFragmentRunTable();
			assertTrue(table.fragmentsLeft(1, abst.currentMediaTime) == 0);

			// we don't worry about the case when the end of the program has been reached.
			var pairs:Vector.<FragmentDurationPair> = afrt.fragmentDurationPairs;
			pairs.pop();
			
			assertTrue(afrt.fragmentsLeft(1, abst.currentMediaTime) == 14);
			assertTrue(afrt.fragmentsLeft(2, abst.currentMediaTime) == 13);
			assertTrue(afrt.fragmentsLeft(3, abst.currentMediaTime) == 12);
			assertTrue(afrt.fragmentsLeft(4, abst.currentMediaTime) == 11);
			assertTrue(afrt.fragmentsLeft(7, abst.currentMediaTime) == 8);
			assertTrue(afrt.fragmentsLeft(8, abst.currentMediaTime) == 7);
			assertTrue(afrt.fragmentsLeft(9, abst.currentMediaTime) == 6);
			assertTrue(afrt.fragmentsLeft(10, abst.currentMediaTime) == 5);
			assertTrue(afrt.fragmentsLeft(12, abst.currentMediaTime) == 3);
			assertTrue(afrt.fragmentsLeft(13, abst.currentMediaTime) == 2);
			assertTrue(afrt.fragmentsLeft(14, abst.currentMediaTime) == 1);
			assertTrue(afrt.fragmentsLeft(15, abst.currentMediaTime) == 0);
		}
		
		public function testAdjustEndEntryDurationAccrued():void
		{
			var pairs:Vector.<FragmentDurationPair> = afrt.fragmentDurationPairs;
			afrt.adjustEndEntryDurationAccrued(23000);
			assertTrue(pairs[pairs.length - 1].durationAccrued == 23000);
			
			pairs.pop();			
			afrt.adjustEndEntryDurationAccrued(23000);
			assertTrue(pairs[pairs.length - 1].durationAccrued != 23000);
		}
		
		// Internals
		//
		
		private var afrt:AdobeFragmentRunTable;
		private var abst:AdobeBootstrapBox;
		private var abstDescriptor:AdobeBootstrapBoxDescriptor;
	}
}