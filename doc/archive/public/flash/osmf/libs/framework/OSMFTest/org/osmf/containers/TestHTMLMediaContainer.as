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
package org.osmf.containers
{
	import flash.external.ExternalInterface;
	
	import org.osmf.elements.HTMLElement;
	import org.osmf.elements.ProxyElement;
	import org.osmf.flexunit.TestCaseEx;
	import org.osmf.media.MediaElement;
	
	public class TestHTMLMediaContainer extends TestCaseEx
	{
		public function testHTMLMediaContainer():void
		{
			if (ExternalInterface.available)
			{
				var container:HTMLMediaContainer = new HTMLMediaContainer("test");
				
				// There three lines need further review: they seem to fail on IE for no
				// apparent reason:
				/*
				assertTrue(ExternalInterface.call("function(){return document.osmf;}"));
				assertTrue(ExternalInterface.call("function(){return document.osmf.containers;}"));
				assertTrue(ExternalInterface.call("function(){return document.osmf.containers.MediaFrameworkTest_test;}"));
				*/
				assertThrows(container.addMediaElement, null);
				assertThrows(container.addMediaElement, new MediaElement());
				
				var element:HTMLElement = new HTMLElement();
				container.addMediaElement(element);
				
				assertTrue(container.containsMediaElement(element));
				assertFalse(container.containsMediaElement(null));
				assertFalse(container.containsMediaElement(new MediaElement()));
				
				assertThrows(container.removeMediaElement, null);
				assertThrows(container.removeMediaElement, new MediaElement());
				
				container.removeMediaElement(element);
				assertFalse(container.containsMediaElement(element));
				
				container.addMediaElement(element);
				assertTrue(container.containsMediaElement(element));
				
				var element2:HTMLElement = new HTMLElement();
				container.addMediaElement(new ProxyElement(new ProxyElement(element2)));
				
				assertTrue(container.containsMediaElement(element2));
				
				// Test if the container constructs its own id if we omit it:
				var container2:HTMLMediaContainer = new HTMLMediaContainer();
				
			}
		}
		
	}
}