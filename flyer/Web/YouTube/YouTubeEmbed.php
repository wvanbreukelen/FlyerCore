<?php

namespace Flyer\Components\Web\YouTube;

class Embed {

	protected $youtubeVideoID;

	public function setYouTubeUrl($url)
	{
		$pieces = explode('watch?v=', $url);
		$this->youtubeVideoID = $pieces[1];
	}

	public function getEmbedUrl()
	{
		return '//www.youtube.com/embed/' . $this->youtubeVideoID;
	}

	public function getYouTubeVideoID()
	{
		return $this->youtubeVideoID;
	}
}