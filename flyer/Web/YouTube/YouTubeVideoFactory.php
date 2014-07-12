<?php

namespace Flyer\Components\Web\YouTube;

class VideoFactory {

	protected $videoWidth;
	protected $videoHeight;

	protected $allowFullscreen = true;

	protected $frameborder = 0;

	protected $source;
	protected $autoplay = false;

	protected $options = null;
	protected $privacy = false;

	public function setSize($width, $heigth)
	{
		// Set the video height
		$this->videoWidth = $width;
		$this->videoHeight = $heigth;

		return $this;
	}

	public function setSizeArray(array $array = array())
	{
		// Set the video height
		$this->videoWidth = $array['width'];
		$this->videoHeight = $array['height'];

		return $this;
	}

	public function setSource($source)
	{
		// Set the YouTube source
		$this->source = $source;
		return $this;
	}

	public function blockFullscreen()
	{
		// Set allowFullscreen to false, so the client is unable to view to video fullscreen
		$this->allowFullscreen = false;
		return $this;
	}

	public function enablePrivacy($privacy)
	{
		// Enable YouTube Video privacy mode
		$this->privacy = $privacy;
		return $this;
	}

	public function enableAutoplay($autoplay)
	{
		// Enable YouTube Video autoplay
		$this->autoplay = $autoplay;
		return $this;
	}

	public function setFrameborder($frameborder)
	{
		// Set frameborder around the video
		$this->frameborder = $frameborder;
		return $this;
	}

	public function setOptions(array $options = array())
	{
		// Set options 
		$this->options = $options;
		return $this;
	}

	public function create()
	{
		// Create new video array
		$video = array();

		// Set video width & height
		$video['width'] = $this->videoWidth;
		$video['height'] = $this->videoHeight;

		// Set frameborder
		$video['frameborder'] = $this->frameborder;

		// Build the video source
		$video['src'] = $this->buildUrl($this->source);

		// Set video options
		$video['options'] = $this->options;

		// Set allow fullscreen
		$video['allowFullscreen'] = $this->allowFullscreen;

		// Assign all elements to a new HTML Frame
		$html = $this->assign($video, $video['options']);

		// Return the HTML video frame
		return $html;

	}

	public function createAndShow()
	{
		// Create new video array
		$video = array();

		// Set video width & height
		$video['width'] = $this->videoWidth;
		$video['heigth'] = $this->videoHeight;

		// Set frameborder
		$video['frameborder'] = $this->frameborder;

		// Set video source
		$video['src'] = $this->buildUrl($this->source);

		// Allowfullscreen set
		$video['allowFullscreen'] = $this->allowFullscreen;

		// Set the additional options
		$video['options'] = $this->options;
		// Assign elements to the video
		$html = $this->assign($video, $video['options']);

		// Print the frame
		echo $html;

		// Return true
		return $this;
	}

	private function buildUrl($originalUrl)
	{
		$adds = array();
		$pieces = explode('watch?v=', $originalUrl);
		$base = $pieces[0] . 'embed/' . $pieces[1];

		// Check if privacy mode is enabled
		if ($this->privacy)
		{
			// PRIVACY
			// Rebuild the source url so it is compatible with YouTube privacy mode
			$url = $originalUrl;
			$pieces = explode('youtube', $url);
			$url = $pieces[0] . 'youtube-nocookie' . $pieces[1];

			// Store rebuilded url
			$base = $url;
		}

		if ($this->autoplay)
		{
			$adds[] = '?autoplay=1';
		}

		// Parse the new url
		foreach ($adds as $add)
		{
			$base .= $add;
		}

		// Return the parsed url
		return $base;

	}

	private function assign(array $video = array(), $options = null)
	{
		// Open iFrame
		$code = "<iframe ";

		// Add the elements to the iFrame
		foreach ($video as $item => $value)
		{
			$code .= $item . "=" . $value . " ";
		}

		// Add allowFullScreen option to the iFrame if the value has been set to true
		if ($video['allowFullscreen'])
		{
			$code .= "allowfullscreen ";
		}

		// Close the iFrame and return it

		return $code .= "></iframe>";
	}

}