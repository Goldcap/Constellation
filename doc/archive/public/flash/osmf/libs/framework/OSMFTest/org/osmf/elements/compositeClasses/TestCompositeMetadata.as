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
package org.osmf.elements.compositeClasses
{
	import org.osmf.flexunit.TestCaseEx;
	import org.osmf.metadata.Metadata;
	import org.osmf.metadata.MetadataGroup;
	import org.osmf.metadata.MetadataSynthesizer;

	public class TestCompositeMetadata extends TestCaseEx
	{
		public function testCompositeMetadataBase():void
		{
			var cm:CompositeMetadata = new CompositeMetadata();
			assertNotNull(cm);
			
			assertNull(cm.mode);
			cm.mode = CompositionMode.SERIAL;
			assertEquals(cm.mode, CompositionMode.SERIAL);
			
			assertNull(cm.activeChild);
			var metadata:Metadata = new CompositeMetadata(); 
			cm.activeChild = metadata;
			assertEquals(metadata, cm.activeChild);
			
			assertNull(cm.getMetadataSynthesizer(null));
			
			assertEquals(cm.numChildren, 0);
			
			assertThrows(cm.getChildAt, 0);
			assertThrows(cm.addChild, 0);
			assertDispatches(cm, [CompositeMetadataEvent.CHILD_ADD], cm.addChild, metadata);
			assertEquals(1, cm.numChildren);
			assertEquals(metadata, cm.getChildAt(0));
			assertThrows(cm.addChild, metadata);
			assertDispatches(cm, [CompositeMetadataEvent.CHILD_REMOVE], cm.removeChild, metadata);
			assertEquals(0, cm.numChildren);
			assertThrows(cm.removeChild, metadata);
			assertThrows(cm.removeChild, null);
			
			assertEquals(0, cm.getMetadataGroupNamespaceURLs().length);
			assertNull(cm.getMetadataSynthesizer(null));
			assertThrows(cm.removeMetadataSynthesizer, null);
			assertThrows(cm.addMetadataSynthesizer, null);
			
			var url:String = "myURL";
			var synth:AMetadataSynthesizer = new AMetadataSynthesizer();
			cm.addMetadataSynthesizer(url, synth);
			assertEquals(synth, cm.getMetadataSynthesizer(url));
			assertThrows(function():void{cm.addMetadataSynthesizer(url, synth)});
			cm.removeMetadataSynthesizer(url);
			assertNull(null, cm.getMetadataSynthesizer(url));
			cm.addMetadataSynthesizer(url, synth);
			
			var childMetadata:Metadata = new Metadata();
			childMetadata.addValue(url,"test");
			cm.addChild(metadata);
			assertDispatches
				(	cm
				,	[ CompositeMetadataEvent.METADATA_GROUP_ADD ]
				,	metadata.addValue
				,	url
				, 	childMetadata
				);	
			
			var md2:Metadata = new Metadata();
			md2.addValue(url, childMetadata);
			assertDispatches
				( 	cm
				, 	[ CompositeMetadataEvent.CHILD_ADD
					, CompositeMetadataEvent.METADATA_GROUP_CHANGE	
				  	]
				, 	cm.addChild
				,	md2
				);
				
			assertDispatches
				( 	cm
				, 	[ CompositeMetadataEvent.METADATA_GROUP_CHANGE	]
				, 	md2.removeValue
				,	url
				);
				
			assertDispatches
				( 	cm
				, 	[ CompositeMetadataEvent.METADATA_GROUP_CHANGE
					, CompositeMetadataEvent.METADATA_GROUP_REMOVE
				  	]
				, 	metadata.removeValue
				,	url
				);
			
			assertDispatches
				( 	cm
				, 	[ CompositeMetadataEvent.CHILD_REMOVE
				  	]
				, 	cm.removeChild
				,	metadata
				);
		}
	
		public function testCompositeMetadataEvent():void
		{
			var metadata:Metadata = new Metadata();
			var childMetadataNamespaceURL:String = "foo";
			var childMetadata:Metadata = new Metadata();
			var metadataGroup:MetadataGroup = new MetadataGroup("");
			var metadataSynthesizer:MetadataSynthesizer = new AMetadataSynthesizer();
			var e:CompositeMetadataEvent
				= new CompositeMetadataEvent
					( CompositeMetadataEvent.CHILD_ADD
					, false, false
					, metadata, childMetadataNamespaceURL, childMetadata, metadataGroup, metadataSynthesizer
					);
					
			assertNotNull(e);
			assertEquals(metadata, e.child);
			assertEquals(childMetadataNamespaceURL, e.childMetadataNamespaceURL);
			assertEquals(childMetadata, e.childMetadata);
			assertEquals(metadataGroup, e.metadataGroup);
			assertEquals(metadataSynthesizer, e.suggestedMetadataSynthesizer);
		}
		
		public function testAnotherMetadataSynthesizer():void // FM-848.
		{
			var key1:String = "key1";
			var cmd:CompositeMetadata = new CompositeMetadata();
			cmd.mode = CompositionMode.PARALLEL;
			
			var m1:Metadata = new SynthesizingMetadata();
			m1.addValue(key1,"1");
			var m2:Metadata = new SynthesizingMetadata();
			m2.addValue(key1,"2");
			
			cmd.addChild(m1);
			cmd.addChild(m2);
			
			//trace(cmd.getValue(key1)); --> returns null: expected "1, 2";
		}
	}
}

import org.osmf.metadata.MetadataSynthesizer;
import org.osmf.metadata.Metadata;
import org.osmf.elements.compositeClasses.CompositionMode;
import __AS3__.vec.Vector;

class AMetadataSynthesizer extends MetadataSynthesizer
{
	public function AMetadataSynthesizer()
	{
		super();
	}
	
	override public function synthesize
		( namespaceURL:String
		, targetParentMetadata:Metadata
		, metadatas:Vector.<Metadata>
		, mode:String
		, activeParentMetadata:Metadata
		):Metadata
	{
		return null;
	}
}

class AnotherMetadataSynthesizer extends MetadataSynthesizer
{
	override public function synthesize
							( namespaceURL:String
							, targetParentMetadata:Metadata
							, metadatas:Vector.<Metadata>
							, mode:String
							, serialElementActiveChildMetadata:Metadata
							):Metadata
	{
		var result:Metadata;
		
		if (mode == CompositionMode.SERIAL)
		{
			result = serialElementActiveChildMetadata;
		}
		else
		{
			result = new Metadata();
			
			var keys:Array = [];
			for each (var metadata:Metadata in metadatas)
			{
				for each (var key:String in metadata.keys)
				{
					keys[key] ||= [];
					keys[key].push(metadata.getValue(key));
				}
			}
			
			for (key in keys)
			{
				result.addValue(key, keys[key].toString());
			}
		}
		
		return result;	
	}
}

class SynthesizingMetadata extends Metadata
{
	override public function get synthesizer():MetadataSynthesizer
	{
		return new AnotherMetadataSynthesizer();
	}
}