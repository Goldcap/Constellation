import java.awt.*;
import java.applet.*;
import java.net.*;

// Variables:
// Trace - trace control
// URL - Url for image
// Interval update interval

public class JavaCam extends Applet implements Runnable
{

	boolean	boolean_Debug;			// Debugging enabled/disabled
	Image	image_WebcamImage;		// Image from Webcam32
	Thread	thread_This;			// Thread for refreshing image
	int		int_Interval;			// Interval between image refreshes ...

	public static void main(String args[])
	{
		System.out.println("Webcam32's JavaCam: V1.1");
	}

	public void trace(String message)
	{
		if (boolean_Debug)
		{
			System.out.println(message);
		}
	}

	public void destroy()
	{
		trace("Applet.destroy() called");
		thread_This.stop();
	}

	public void start()
	{
		trace("Start called");
		thread_This.resume();
	}

	public void stop()
	{
		trace("Stop called");
		thread_This.suspend();

	}
	public void run()
	{
		trace("run(): Thread started!");
		while (true)
		{
			try
			{
				// Put the refresh thread to sleep for the supplied interval
				Thread.sleep(int_Interval*1000);
				
				trace("Flushing image");
				// Flush the image in the image buffer so we get a new one
				image_WebcamImage.flush();
				// Track the image and wait till its loaded
				MediaTracker mediaTracker_track = new MediaTracker(this);
				mediaTracker_track.addImage(image_WebcamImage, 1);
				mediaTracker_track.waitForID(1);
				// Repaint the image
				repaint();
			}
			catch (Exception e)
			{
				trace("Caught:"+e.toString());
			}
		}
	}

	public void init()
	{
		String	string_Url;
		URL		url_Webcam;
		int		int_Port;

		boolean_Debug = false;
		if (getParameter("Trace") != null)
		{
			boolean_Debug = true;
		}


		trace("Init called");

		string_Url = getParameter("URL");
		if (string_Url == null)
		{
			showStatus("No URL for Webcam32 image supplied");
			return;
		}

		try
		{
			int_Interval = Integer.parseInt(getParameter("Interval"));
		}
		catch (Exception e)
		{
			// Format exception ... default interval 30
			int_Interval = 30;
		}
		trace("Refresh interval="+int_Interval);
		
		try
		{
			url_Webcam = new URL(string_Url);
			trace("Target URL="+url_Webcam.toString());
			image_WebcamImage = getImage(url_Webcam);
			MediaTracker mediaTracker_track = new MediaTracker(this);
			mediaTracker_track.addImage(image_WebcamImage, 1);
			mediaTracker_track.waitForID(1);
		}

		catch(Exception e)
		{
			trace("Image get exception:"+e.toString());
			showStatus("Problem getting initial image: "+e.toString());
			return;
		}
		/*
		int int_Width = image_WebcamImage.getWidth(this);
		int int_Height = image_WebcamImage.getHeight(this);
		trace("width="+int_Width+" height="+int_Height);
		resize(int_Width, int_Height);
		*/

		// Create and start the new thread initially suspended
		thread_This = new Thread(this);
		thread_This.suspend();
		thread_This.start();

	}

	public void paint(Graphics g)
	{
		trace("paint()");

		if (image_WebcamImage != null)
		{
			g.drawImage(image_WebcamImage, 0,0, this);
		}
	}
	
}
