<?php

namespace wvanbreukelen\Test;

use Route;

class TestServiceProvider
{
	public function register()
	{
		
	}

	public function boot()
	{
		Route::get('hello', 'AnotherController@hello');
	}

}