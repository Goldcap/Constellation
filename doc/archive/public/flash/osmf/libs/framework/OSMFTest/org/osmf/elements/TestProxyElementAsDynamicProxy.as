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
	import __AS3__.vec.Vector;
	
	import org.osmf.events.MediaElementEvent;
	import org.osmf.media.MediaElement;
	import org.osmf.traits.BufferTrait;
	import org.osmf.traits.LoadTrait;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.traits.PlayTrait;
	import org.osmf.traits.SeekTrait;
	import org.osmf.traits.TimeTrait;
	import org.osmf.utils.DynamicMediaElement;
	import org.osmf.utils.DynamicProxyElement;
	import org.osmf.utils.SimpleLoader;

	public class TestProxyElementAsDynamicProxy extends TestProxyElement
	{
		public function testBlockedTraits():void
		{
			var events:Array = [];
			
			var wrappedElement:DynamicMediaElement
				= new DynamicMediaElement( [MediaTraitType.TIME, MediaTraitType.LOAD]
										 , new SimpleLoader()
										 );

			var proxyElement:DynamicProxyElement = new DynamicProxyElement(wrappedElement);
			proxyElement.addEventListener(MediaElementEvent.TRAIT_ADD, onTraitEvent);
			proxyElement.addEventListener(MediaElementEvent.TRAIT_REMOVE, onTraitEvent);
			
			var blockedTraits:Vector.<String> = new Vector.<String>();
			blockedTraits.push(MediaTraitType.PLAY);
			blockedTraits.push(MediaTraitType.LOAD);
			proxyElement.setBlockedTraits(blockedTraits);

			// The proxy blocks LOAD.
			//
			
			assertFalse(proxyElement.hasTrait(MediaTraitType.PLAY));
			assertFalse(proxyElement.hasTrait(MediaTraitType.LOAD));
			assertTrue(proxyElement.hasTrait(MediaTraitType.TIME));

			assertTrue(wrappedElement.hasTrait(MediaTraitType.LOAD));
			assertTrue(wrappedElement.getTrait(MediaTraitType.LOAD) != null);

			assertTrue(events.length == 1);
			assertTrue(MediaElementEvent(events[0]).type == MediaElementEvent.TRAIT_REMOVE);
			assertTrue(MediaElementEvent(events[0]).traitType == MediaTraitType.LOAD);

			// TIME, LOAD
			assertTrue(wrappedElement.traitTypes.length == 2);
			
			// TIME
			assertTrue(proxyElement.traitTypes.length == 1);
			
			events = [];
						
			// If we now replace the wrapped element, then the traits of the
			// wrapped element should be reflected in the proxy's traits,
			// though the blocked traits should remain the same. 
			//
			
			var proxiedElement2:DynamicMediaElement
				= new DynamicMediaElement( [MediaTraitType.PLAY, MediaTraitType.SEEK, MediaTraitType.LOAD]
										 , new SimpleLoader()
										 );

			proxyElement.proxiedElement = proxiedElement2;

			assertFalse(proxyElement.hasTrait(MediaTraitType.PLAY));
			assertTrue(proxyElement.hasTrait(MediaTraitType.SEEK));
			assertFalse(proxyElement.hasTrait(MediaTraitType.LOAD));
			assertFalse(proxyElement.hasTrait(MediaTraitType.TIME));
			
			// The TIME trait gets removed, then the SEEK trait is added.
			// There should be no event for PLAY or LOAD, since they're blocked.
			assertTrue(events.length == 2);
			assertTrue(MediaElementEvent(events[0]).type == MediaElementEvent.TRAIT_REMOVE);
			assertTrue(MediaElementEvent(events[0]).traitType == MediaTraitType.TIME);
			assertTrue(MediaElementEvent(events[1]).type == MediaElementEvent.TRAIT_ADD);
			assertTrue(MediaElementEvent(events[1]).traitType == MediaTraitType.SEEK);
			
			events = [];
			
			// Changing the blocked traits can cause the dispatch of trait events.
			blockedTraits = new Vector.<String>();
			blockedTraits.push(MediaTraitType.TIME);
			blockedTraits.push(MediaTraitType.PLAY);
			proxyElement.setBlockedTraits(blockedTraits);
			
			assertFalse(proxyElement.hasTrait(MediaTraitType.PLAY));
			assertTrue(proxyElement.hasTrait(MediaTraitType.SEEK));
			assertTrue(proxyElement.hasTrait(MediaTraitType.LOAD));
			assertFalse(proxyElement.hasTrait(MediaTraitType.TIME));
			
			assertTrue(events.length == 1);
			assertTrue(MediaElementEvent(events[0]).type == MediaElementEvent.TRAIT_ADD);
			assertTrue(MediaElementEvent(events[0]).traitType == MediaTraitType.LOAD);
			
			events = [];
			
			// But if we add a trait for a type that's blocked, we should get
			// no traitAdd event.
			proxyElement.doAddTrait(MediaTraitType.PLAY, new PlayTrait());
			assertTrue(events.length == 0);
			
			// However, as soon as we unblock that trait, it's exposed, so
			// we should get an event.
			blockedTraits = new Vector.<String>();
			blockedTraits.push(MediaTraitType.TIME);
			proxyElement.setBlockedTraits(blockedTraits);
			
			assertTrue(events.length == 1);
			assertTrue(MediaElementEvent(events[0]).type == MediaElementEvent.TRAIT_ADD);
			assertTrue(MediaElementEvent(events[0]).traitType == MediaTraitType.PLAY);

			function onTraitEvent(event:MediaElementEvent):void
			{
				events.push(event);
			}
		}
		
		public function testOverriddenTraits():void
		{
			var wrappedElement:DynamicMediaElement
				= new DynamicMediaElement( [MediaTraitType.TIME, MediaTraitType.LOAD]
										 , new SimpleLoader()
										 );

			var overriddenTimeTrait:TimeTrait = new TimeTrait();
			var proxyElement:DynamicProxyElement = new DynamicProxyElement(wrappedElement);
			
			// Override PLAY and TIME.
			proxyElement.doAddTrait(MediaTraitType.PLAY, new PlayTrait());
			proxyElement.doAddTrait(MediaTraitType.TIME, overriddenTimeTrait);
			
			assertTrue(proxyElement.hasTrait(MediaTraitType.PLAY));
			assertTrue(proxyElement.hasTrait(MediaTraitType.LOAD));
			assertTrue(proxyElement.hasTrait(MediaTraitType.TIME));
			
			assertTrue(proxyElement.getTrait(MediaTraitType.TIME) == overriddenTimeTrait);

			// If we now replace the wrapped element, then the proxy should still
			// have the same overridden traits.
			//
			
			var wrappedElement2:DynamicMediaElement
				= new DynamicMediaElement( [MediaTraitType.PLAY, MediaTraitType.TIME, MediaTraitType.LOAD]
										 , new SimpleLoader()
										 );

			proxyElement.proxiedElement = wrappedElement2;

			assertTrue(proxyElement.hasTrait(MediaTraitType.PLAY));
			assertTrue(proxyElement.hasTrait(MediaTraitType.LOAD));
			assertTrue(proxyElement.hasTrait(MediaTraitType.TIME));
			
			// The TimeTrait should still come from the proxy element.
			assertTrue(proxyElement.getTrait(MediaTraitType.TIME) == overriddenTimeTrait);
			assertTrue(proxyElement.getTrait(MediaTraitType.TIME) != wrappedElement2.getTrait(MediaTraitType.TIME));
		}
		
		public function testDispatchEvent():void
		{
			// Wrap up a temporal element, but override the TimeTrait
			// and block the PlayTrait.
			//
			
			var wrappedElement:DynamicMediaElement
				= new DynamicMediaElement( [MediaTraitType.TIME, MediaTraitType.LOAD]
										 , new SimpleLoader()
										 );

			var proxyElement:DynamicProxyElement = new DynamicProxyElement(wrappedElement);
			var blockedTraits:Vector.<String> = new Vector.<String>();
			blockedTraits.push(MediaTraitType.PLAY);
			proxyElement.setBlockedTraits(blockedTraits);

			var timeTrait:TimeTrait = new TimeTrait(30);
			proxyElement.doAddTrait(MediaTraitType.TIME, timeTrait);

			proxyElement.addEventListener(MediaElementEvent.TRAIT_ADD, onTraitAdd);
			proxyElement.addEventListener(MediaElementEvent.TRAIT_REMOVE, onTraitRemove);

			assertTrue(traitAddEventCount == 0);
			assertTrue(traitRemoveEventCount == 0);
			
			// If the TimeTrait is added or removed on the wrapped
			// element, we shouldn't get any events.
			wrappedElement.doRemoveTrait(MediaTraitType.TIME);
			assertTrue(traitAddEventCount == 0);
			assertTrue(traitRemoveEventCount == 0);
			wrappedElement.doAddTrait(MediaTraitType.TIME, new TimeTrait());
			assertTrue(traitAddEventCount == 0);
			assertTrue(traitRemoveEventCount == 0);
			
			// Similarly, if we add or remove a trait to the wrapped element
			// which the proxy blocks, then we shouldn't get any events.
			wrappedElement.doAddTrait(MediaTraitType.PLAY, new PlayTrait());
			assertTrue(traitAddEventCount == 0);
			assertTrue(traitRemoveEventCount == 0);
			wrappedElement.doRemoveTrait(MediaTraitType.PLAY);
			assertTrue(traitAddEventCount == 0);
			assertTrue(traitRemoveEventCount == 0);

			// But if our proxy doesn't have an override for a trait or
			// block a trait, then we should get events.
			wrappedElement.doAddTrait(MediaTraitType.SEEK, new SeekTrait(timeTrait));
			assertTrue(traitAddEventCount == 1);
			assertTrue(traitRemoveEventCount == 0);
			wrappedElement.doRemoveTrait(MediaTraitType.SEEK);
			assertTrue(traitAddEventCount == 1);
			assertTrue(traitRemoveEventCount == 1);
			
			// If we add a trait to the proxy which the wrapped element
			// already has, we should get a remove event (for the wrapped
			// element's trait) followed by an add event.
			proxyElement.doAddTrait(MediaTraitType.LOAD, new LoadTrait(null,null));
			assertTrue(traitAddEventCount == 2);
			assertTrue(traitRemoveEventCount == 2);
			
			// If we remove a trait from the proxy which the wrapped element
			// already has, we should get a remove event (for the proxy's
			// trait) followed by an add event.
			proxyElement.doRemoveTrait(MediaTraitType.LOAD);
			assertTrue(traitAddEventCount == 3);
			assertTrue(traitRemoveEventCount == 3);

			// If we add or remove a trait to the proxy which the wrapped
			// element doesn't have, then we should get events.
			proxyElement.doAddTrait(MediaTraitType.BUFFER, new BufferTrait());
			assertTrue(traitAddEventCount == 4);
			assertTrue(traitRemoveEventCount == 3);
			proxyElement.doRemoveTrait(MediaTraitType.BUFFER);
			assertTrue(traitAddEventCount == 4);
			assertTrue(traitRemoveEventCount == 4);
			
			// Last, if we add or remove a trait to the proxy which the proxy
			// also blocks, then we should not get events. 
			proxyElement.doAddTrait(MediaTraitType.PLAY, new PlayTrait());
			assertTrue(traitAddEventCount == 4);
			assertTrue(traitRemoveEventCount == 4);
			proxyElement.doRemoveTrait(MediaTraitType.PLAY);
			assertTrue(traitAddEventCount == 4);
			assertTrue(traitRemoveEventCount == 4);
		}
		
		// Overrides
		//

		override protected function createProxyElement():ProxyElement
		{
			return new DynamicProxyElement();
		}
		
		override protected function createMediaElement():MediaElement
		{
			return new DynamicProxyElement(new DynamicMediaElement(WRAPPED_TRAITS, new SimpleLoader()));
		}
		
		override public function setUp():void
		{
			super.setUp();
			
			traitAddEventCount = traitRemoveEventCount = 0;
		}

		override protected function get hasLoadTrait():Boolean
		{
			return true;
		}
		
		override protected function get existentTraitTypesOnInitialization():Array
		{
			return WRAPPED_TRAITS;
		}

		override protected function get existentTraitTypesAfterLoad():Array
		{
			return REFLECTED_TRAITS;
		}
		
		// Internals
		//
		
		private function onTraitAdd(event:MediaElementEvent):void
		{
			traitAddEventCount++;
		}

		private function onTraitRemove(event:MediaElementEvent):void
		{
			traitRemoveEventCount++;
		}
		
		private var traitAddEventCount:int;
		private var traitRemoveEventCount:int;
		
		private static const WRAPPED_TRAITS:Array =
					[ MediaTraitType.AUDIO
					, MediaTraitType.BUFFER
					, MediaTraitType.LOAD
					, MediaTraitType.PLAY
				    , MediaTraitType.DISPLAY_OBJECT
				    ];

		private static const REFLECTED_TRAITS:Array =
					[ MediaTraitType.AUDIO
					, MediaTraitType.BUFFER
					, MediaTraitType.LOAD
					, MediaTraitType.PLAY
				    , MediaTraitType.DISPLAY_OBJECT
				    ];
	}
}