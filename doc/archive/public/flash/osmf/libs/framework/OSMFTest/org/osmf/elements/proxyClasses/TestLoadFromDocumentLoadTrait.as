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
package org.osmf.elements.proxyClasses
{
	import flexunit.framework.Assert;
	import flexunit.framework.TestCase;
	
	import org.osmf.media.MediaElement;
	import org.osmf.media.MediaResourceBase;
	import org.osmf.traits.LoaderBase;

	public class TestLoadFromDocumentLoadTrait extends TestCase
	{
		public function testGetter():void
		{
			var loader:LoaderBase = new LoaderBase();
			
			var resource:MediaResourceBase = new MediaResourceBase();
			
			var trait:LoadFromDocumentLoadTrait = new LoadFromDocumentLoadTrait(loader, resource);
					
			var elem:MediaElement = new MediaElement();
			trait.mediaElement  = elem;
			
			assertEquals(elem, trait.mediaElement);
			
			assertEquals(resource, trait.resource);
			
		}
		
	}
}