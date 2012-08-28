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
package org.osmf.elements.htmlClasses
{
	import org.osmf.elements.HTMLElement;
	import org.osmf.flexunit.TestCaseEx;

	public class TestHTMLAudioTrait extends TestCaseEx
	{
		public function testHTMLAudioTrait():void
		{
			htmlElement = new HTMLElement();
			var trait:HTMLAudioTrait = new HTMLAudioTrait(htmlElement);
			assertNotNull(trait);
			
			trait.setMuted(true);
			trait.setPan(1);
			trait.setVolume(0.5);
		}
		
		private var htmlElement:HTMLElement;
	}
}