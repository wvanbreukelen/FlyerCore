<?php

namespace Flyer\Components\Web\YouTube;

use Flyer\Foundation\ServiceProvider;

class YouTubeServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->share('youtubeVideo', new VideoFactory);
	}
}