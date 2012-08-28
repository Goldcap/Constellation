package org.osmf.media
{
	import flexunit.framework.TestCase;
	
	import org.osmf.containers.MediaContainer;
	import org.osmf.elements.AudioElement;
	import org.osmf.elements.VideoElement;
	import org.osmf.layout.LayoutMetadata;
	import org.osmf.layout.ScaleMode;
	
	public class TestMediaPlayerSprite extends TestCase
	{
		public function testConstructor():void
		{
			var container:MediaContainer = new MediaContainer();
			var player:MediaPlayer = new MediaPlayer();
			var factory:MediaFactory = new MediaFactory();
			
			var mps:MediaPlayerSprite = new MediaPlayerSprite(player, container, factory);
			assertEquals(mps.mediaPlayer, player);
			assertEquals(mps.mediaContainer, container);
			assertEquals(mps.mediaFactory, factory);
			
			mps = new MediaPlayerSprite();
			assertNotNull(mps.mediaPlayer);
			assertNotNull(mps.mediaContainer);
			assertNotNull(mps.mediaFactory);
			
			player.media = new AudioElement();
			
			mps = new MediaPlayerSprite(player);
			assertTrue(mps.mediaContainer.containsMediaElement(player.media));
			assertEquals(mps.media, player.media);		
		}
		
		public function testMedia():void
		{			
			var mps:MediaPlayerSprite = new MediaPlayerSprite();
			
			assertNull(mps.media);
			assertNull(mps.mediaPlayer.media);
			
			mps.mediaPlayer.media = new AudioElement();
			
			assertEquals(mps.media, mps.mediaPlayer.media);
			assertTrue(mps.mediaContainer.containsMediaElement(mps.media));
			
			mps.media = new VideoElement();
			
			assertEquals(mps.media, mps.mediaPlayer.media);
			assertTrue(mps.mediaContainer.containsMediaElement(mps.media));
			assertTrue(mps.media is VideoElement);						
		}
		
		public function testResource():void
		{
			var mps:MediaPlayerSprite = new MediaPlayerSprite();
			
			var resource:URLResource = new URLResource("http://example.com/video.flv");
			
			mps.resource = resource;
			
			assertEquals(mps.resource, resource);
			assertNotNull(mps.media);
			assertEquals(mps.media, mps.mediaPlayer.media);
						
		}
		
		public function testScaleMode():void
		{
			var mps:MediaPlayerSprite = new MediaPlayerSprite();
			
			assertEquals(mps.scaleMode, ScaleMode.LETTERBOX);
			
			mps.media = new VideoElement();

			assertEquals(mps.scaleMode, ScaleMode.LETTERBOX);
			
			var layout:LayoutMetadata = mps.media.getMetadata(LayoutMetadata.LAYOUT_NAMESPACE) as LayoutMetadata;
			
			assertEquals(layout.scaleMode, mps.scaleMode, ScaleMode.LETTERBOX);
			
			mps.scaleMode = ScaleMode.NONE;
			
			assertEquals(layout.scaleMode, mps.scaleMode, ScaleMode.NONE);
			
			
			//Make sure layout is preserved if already set.
			var element:MediaElement = new VideoElement();
			layout = new LayoutMetadata()
			element.addMetadata(LayoutMetadata.LAYOUT_NAMESPACE, layout);
			layout.percentWidth = 50;
			layout.percentHeight = 50;
			layout.percentX = 20;
			layout.percentY = 20;
			layout.scaleMode = ScaleMode.STRETCH;			
			
			mps = new MediaPlayerSprite();
			
			mps.media = element;
			
			assertEquals(mps.scaleMode, ScaleMode.LETTERBOX);	
			
			assertEquals(mps.media.getMetadata(LayoutMetadata.LAYOUT_NAMESPACE), layout);	
					
		}
		
		public function testLayout():void
		{
			var mps:MediaPlayerSprite = new MediaPlayerSprite();
			
			mps.media = new VideoElement();
			
			mps.width = 200;
			mps.height = 200;
			
			assertEquals(mps.mediaContainer.width, mps.width);
			assertEquals(mps.mediaContainer.height, mps.height);
									
			mps.width = 400;
			mps.height = 400;
			
			assertEquals(mps.mediaContainer.width, mps.width);
			assertEquals(mps.mediaContainer.height, mps.height);
		}
		
		
		
	}
}