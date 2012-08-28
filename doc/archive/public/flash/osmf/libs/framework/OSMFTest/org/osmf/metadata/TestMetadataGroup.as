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
package org.osmf.metadata
{
	import flash.events.Event;
	
	import org.osmf.flexunit.TestCaseEx;

	public class TestMetadataGroup extends TestCaseEx
	{
		public function testMetadataGroup():void
		{
			var url:String = "url";
			var mg:MetadataGroup = new MetadataGroup(url);
			
			assertNotNull(mg);
			assertEquals(0, mg.metadatas.length);
			assertEquals(url, mg.namespaceURL);
			assertEquals(-1, mg.indexOf(null, null));	
			assertEquals(null, mg.removeMetadata(null, null));
			
			var metadata1:Metadata = new Metadata();
			var metadata2:Metadata = new Metadata();
			var child:Metadata = new Metadata();
			child.addValue(url,"hello");
			
			metadata1.addValue(url, child);
			metadata2.addValue(url, child);
			
			assertDispatches(mg, [Event.CHANGE], mg.addMetadata, metadata1, child);
			assertEquals(1, mg.metadatas.length);
			assertEquals(child, mg.metadatas[0]);
			assertEquals(metadata1, mg.parentMetadatas[0]);
			assertEquals(0, mg.indexOf(metadata1, child));	
			
			assertDispatches(mg, [Event.CHANGE], mg.addMetadata, metadata2, child);
			assertEquals(2, mg.metadatas.length);
			assertEquals(child, mg.metadatas[1]);
			assertEquals(metadata2, mg.parentMetadatas[1]);
			assertEquals(1, mg.indexOf(metadata2, child));
			
			assertEquals
				( child
				, assertDispatches(mg, [Event.CHANGE], mg.removeMetadata, metadata1, child)
				);
				
			assertEquals(1, mg.metadatas.length);
			assertEquals(child, mg.metadatas[0]);
			assertEquals(metadata2, mg.parentMetadatas[0]);
			assertEquals(0, mg.indexOf(metadata2, child));	
		}
	}
}