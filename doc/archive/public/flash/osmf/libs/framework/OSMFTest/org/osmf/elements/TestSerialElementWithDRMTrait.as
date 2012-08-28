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
package org.osmf.elements
{
	import flash.utils.ByteArray;
	
	import flexunit.framework.Assert;
	import flexunit.framework.TestCase;
	
	import org.osmf.events.DRMEvent;
	import org.osmf.events.MediaError;
	import org.osmf.traits.DRMState;
	import org.osmf.traits.DRMTrait;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.utils.DynamicDRMTrait;
	import org.osmf.utils.DynamicMediaElement;
	import org.osmf.utils.DynamicTimeTrait;

	public class TestSerialElementWithDRMTrait extends TestCase
	{
		public function testSerialProperties():void
		{
			var elem:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.DRM, MediaTraitType.TIME, MediaTraitType.PLAY], null, null, true);
			var elem2:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.DRM, MediaTraitType.TIME, MediaTraitType.PLAY], null, null, true);
			
			var serial:SerialElement = new SerialElement();
			
			Assert.assertFalse(serial.getTrait(MediaTraitType.DRM) != null);
			
			serial.addChild(elem);
			serial.addChild(elem2);
			
			Assert.assertTrue(serial.getTrait(MediaTraitType.DRM) != null);
			
			var elem1Drm:DynamicDRMTrait = elem.getTrait(MediaTraitType.DRM) as DynamicDRMTrait;			
			var elem2Drm:DynamicDRMTrait = elem2.getTrait(MediaTraitType.DRM) as DynamicDRMTrait;			
			var timeTrait1:DynamicTimeTrait = elem.getTrait(MediaTraitType.TIME) as  DynamicTimeTrait;		
			timeTrait1.duration = 1;	
			
			elem.doAddTrait(MediaTraitType.TIME, timeTrait1);
			
			Assert.assertTrue(serial.getTrait(MediaTraitType.DRM) != null);
			
			var trait:DRMTrait = serial.getTrait(MediaTraitType.DRM) as DRMTrait;
			
			Assert.assertEquals(trait.drmState, elem1Drm.drmState);
			Assert.assertEquals(trait.endDate, elem1Drm.endDate);
			Assert.assertEquals(trait.startDate, elem1Drm.startDate);
			Assert.assertEquals(trait.period, elem1Drm.period);
			
			//Moves the serial element to the next child.							
			timeTrait1.currentTime = 1;
		
			Assert.assertTrue(serial.getTrait(MediaTraitType.DRM) != null);
			
			trait = serial.getTrait(MediaTraitType.DRM) as DRMTrait;
			
			Assert.assertEquals(trait.drmState, elem2Drm.drmState);
			Assert.assertEquals(trait.endDate, elem2Drm.endDate);
			Assert.assertEquals(trait.startDate, elem2Drm.startDate);
			Assert.assertEquals(trait.period, elem2Drm.period);
			
						
			var start:Date = new Date(1);
			var end:Date = new Date(2);
			
			elem2Drm.invokeDrmStateChange(DRMState.AUTHENTICATION_COMPLETE,  new ByteArray(), new MediaError(1, "test"), start, end, 50, "server");
			
			Assert.assertEquals(trait.drmState, DRMState.AUTHENTICATION_COMPLETE);
			Assert.assertEquals(trait.endDate, end);
			Assert.assertEquals(trait.startDate, start);
			Assert.assertEquals(trait.period, 50);
								
		}
		
		public function testSerialEvents():void
		{
			var elem:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.DRM, MediaTraitType.TIME, MediaTraitType.PLAY], null, null, true);
			var elem2:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.DRM, MediaTraitType.TIME, MediaTraitType.PLAY], null, null, true);
			
			var serial:SerialElement = new SerialElement();
					
			serial.addChild(elem);
			serial.addChild(elem2);
			
			Assert.assertTrue(serial.getTrait(MediaTraitType.DRM) != null);
			
			var elem1Drm:DynamicDRMTrait = elem.getTrait(MediaTraitType.DRM) as DynamicDRMTrait;			
			var elem2Drm:DynamicDRMTrait = elem2.getTrait(MediaTraitType.DRM) as DynamicDRMTrait;			
			var timeTrait1:DynamicTimeTrait = elem.getTrait(MediaTraitType.TIME) as  DynamicTimeTrait;		
			timeTrait1.duration = 1;	
			
			elem.doAddTrait(MediaTraitType.TIME, timeTrait1);
			
			Assert.assertTrue(serial.getTrait(MediaTraitType.DRM) != null);
			
			var trait:DRMTrait = serial.getTrait(MediaTraitType.DRM) as DRMTrait;			
			trait.addEventListener(DRMEvent.DRM_STATE_CHANGE, onDRMStateChange);
							
			Assert.assertEquals(trait.drmState, DRMState.UNINITIALIZED);
					
			var currentDRMTrait:DRMTrait = 	elem1Drm;
			
			var token:ByteArray = new ByteArray();		
			
			var events:Number = 0;		
					
			elem1Drm.invokeDrmStateChange(DRMState.AUTHENTICATION_ERROR, token, null, new Date(), new Date(), 50, "SeverURL");
			elem1Drm.invokeDrmStateChange(DRMState.AUTHENTICATION_COMPLETE, token, null, new Date(), new Date(), 50, "SeverURL");
			elem1Drm.invokeDrmStateChange(DRMState.AUTHENTICATING, token, null, new Date(), new Date(), 50, "SeverURL");
			elem1Drm.invokeDrmStateChange(DRMState.AUTHENTICATION_NEEDED, token, null, new Date(), new Date(), 50, "SeverURL");
			elem1Drm.invokeDrmStateChange(DRMState.UNINITIALIZED, token, null, new Date(), new Date(), 50, "SeverURL");		
			
			currentDRMTrait = elem2Drm;
						
			//Moves the serial element to the next child.							
			timeTrait1.currentTime = 1;
				
			elem2Drm.invokeDrmStateChange(DRMState.UNINITIALIZED, token, null, new Date(), new Date(), 50, "SeverURL");	
			elem2Drm.invokeDrmStateChange(DRMState.AUTHENTICATION_ERROR, token, null, new Date(), new Date(), 50, "SeverURL");
			elem2Drm.invokeDrmStateChange(DRMState.AUTHENTICATION_COMPLETE, token, null, new Date(), new Date(), 50, "SeverURL");
			elem2Drm.invokeDrmStateChange(DRMState.AUTHENTICATING, token, null, new Date(), new Date(), 50, "SeverURL");
			elem2Drm.invokeDrmStateChange(DRMState.AUTHENTICATION_NEEDED, token, null, new Date(), new Date(), 50, "SeverURL");
			
						
			//Test init
				
			serial.removeChild(elem);
			
			assertEquals(trait.drmState, elem2Drm.drmState);
					
			function onDRMStateChange(event:DRMEvent):void
			{
				events++;
				assertEquals(event.token, token);
				assertEquals(event.serverURL, "SeverURL");
				assertEquals(trait.drmState, currentDRMTrait.drmState, event.drmState);	
			}
			
			assertEquals(events, 9);
			
		}
		
		public function testAuthentication():void
		{
			var events:Number = 0;		
			var eventQueue:Array = [DRMState.AUTHENTICATION_COMPLETE, DRMState.AUTHENTICATING, DRMState.AUTHENTICATION_NEEDED,  DRMState.AUTHENTICATION_COMPLETE, DRMState.AUTHENTICATING];
			var elem:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.DRM, MediaTraitType.TIME, MediaTraitType.PLAY], null, null, true);
			var elem2:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.DRM, MediaTraitType.TIME, MediaTraitType.PLAY], null, null, true);			
			var serial:SerialElement = new SerialElement();
			var elem1Drm:DynamicDRMTrait = elem.getTrait(MediaTraitType.DRM) as DynamicDRMTrait;			
			var elem2Drm:DynamicDRMTrait = elem2.getTrait(MediaTraitType.DRM) as DynamicDRMTrait;		
			elem1Drm.invokeDrmStateChange(DRMState.AUTHENTICATION_NEEDED, null, null, new Date(), new Date(), 50, "SeverURL");
			elem2Drm.invokeDrmStateChange(DRMState.AUTHENTICATION_NEEDED, null, null, new Date(), new Date(), 50, "SeverURL");	

			serial.addChild(elem);
			serial.addChild(elem2);
			
			var trait:DRMTrait = serial.getTrait(MediaTraitType.DRM) as DRMTrait;	
			
			Assert.assertTrue(serial.getTrait(MediaTraitType.DRM) != null);
					
			trait.addEventListener(DRMEvent.DRM_STATE_CHANGE, onDRMStateChange);
			
			trait.authenticate("ryan", "password");
													
			function onDRMStateChange(event:DRMEvent):void
			{
				assertEquals(event.drmState, eventQueue.pop());
				events++;						
			}
			
			assertEquals( events, 2);
			
			//Move the serial to the next element
			var time:DynamicTimeTrait = elem.getTrait(MediaTraitType.TIME) as DynamicTimeTrait;
			time.duration = 1;
			time.currentTime = 1;
									
			trait.authenticate("ryan2", "password2");
			
			assertEquals(events, 5);
		}
		
			
		public function testAuthenticationToken():void
		{
			var events:Number = 0;		
			var eventQueue:Array = [DRMState.AUTHENTICATION_COMPLETE, DRMState.AUTHENTICATING, DRMState.AUTHENTICATION_NEEDED, DRMState.AUTHENTICATION_COMPLETE, DRMState.AUTHENTICATING];
			var elem:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.DRM, MediaTraitType.TIME, MediaTraitType.PLAY], null, null, true);
			var elem2:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.DRM, MediaTraitType.TIME, MediaTraitType.PLAY], null, null, true);			
			var serial:SerialElement = new SerialElement();
			var elem1Drm:DynamicDRMTrait = elem.getTrait(MediaTraitType.DRM) as DynamicDRMTrait;			
			var elem2Drm:DynamicDRMTrait = elem2.getTrait(MediaTraitType.DRM) as DynamicDRMTrait;		
			elem1Drm.invokeDrmStateChange(DRMState.AUTHENTICATION_NEEDED, null, null, new Date(), new Date(), 50, "SeverURL");
			elem2Drm.invokeDrmStateChange(DRMState.AUTHENTICATION_NEEDED, null, null, new Date(), new Date(), 50, "SeverURL");	

			serial.addChild(elem);
			serial.addChild(elem2);
			
			var trait:DRMTrait = serial.getTrait(MediaTraitType.DRM) as DRMTrait;	
			
			Assert.assertTrue(serial.getTrait(MediaTraitType.DRM) != null);
					
			trait.addEventListener(DRMEvent.DRM_STATE_CHANGE, onDRMStateChange);
			
			trait.authenticateWithToken(new ByteArray());
													
			function onDRMStateChange(event:DRMEvent):void
			{
				assertEquals(event.drmState, eventQueue.pop());
				events++;						
			}
			
			assertEquals( events, 2);
			
			//Move the serial to the next element
			var time:DynamicTimeTrait = elem.getTrait(MediaTraitType.TIME) as DynamicTimeTrait;
			time.duration = 1;
			time.currentTime = 1;
									
			trait.authenticateWithToken(new ByteArray());
			
			assertEquals(events, 5);
		}
		
		public function testAuthFailure():void
		{
			var events:Number = 0;		
			var eventQueue:Array = [DRMState.AUTHENTICATION_COMPLETE, DRMState.AUTHENTICATING, DRMState.AUTHENTICATION_NEEDED, DRMState.AUTHENTICATION_ERROR, DRMState.AUTHENTICATING];
			var elem:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.DRM, MediaTraitType.TIME, MediaTraitType.PLAY], null, null, true);
			var elem2:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.DRM, MediaTraitType.TIME, MediaTraitType.PLAY], null, null, true);			
			var serial:SerialElement = new SerialElement();
			var elem1Drm:DynamicDRMTrait = elem.getTrait(MediaTraitType.DRM) as DynamicDRMTrait;			
			var elem2Drm:DynamicDRMTrait = elem2.getTrait(MediaTraitType.DRM) as DynamicDRMTrait;		
			elem1Drm.invokeDrmStateChange(DRMState.AUTHENTICATION_NEEDED, null, null, new Date(), new Date(), 50, "SeverURL");
			elem2Drm.invokeDrmStateChange(DRMState.AUTHENTICATION_NEEDED, null, null, new Date(), new Date(), 50, "SeverURL");	

			serial.addChild(elem);
			serial.addChild(elem2);
			
			var trait:DRMTrait = serial.getTrait(MediaTraitType.DRM) as DRMTrait;	
			
			Assert.assertTrue(serial.getTrait(MediaTraitType.DRM) != null);
					
			trait.addEventListener(DRMEvent.DRM_STATE_CHANGE, onDRMStateChange);
			
			assertEquals(trait.drmState, DRMState.AUTHENTICATION_NEEDED);
			
			trait.authenticate();
																
			function onDRMStateChange(event:DRMEvent):void
			{
				assertEquals(event.drmState, eventQueue.pop());
				events++;						
			}
			
			assertEquals(2, events);
			
			//Move the serial to the next element
			var time:DynamicTimeTrait = elem.getTrait(MediaTraitType.TIME) as DynamicTimeTrait;
			time.duration = 1;
			time.currentTime = 1;
									
			trait.authenticateWithToken(new ByteArray());
			
			assertEquals(5, events);
			assertEquals(eventQueue.length, 0);
		}
				
		
		
	}
}