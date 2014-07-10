<?php

namespace Flyer\Components\Router;

use Symfony\Component\HttpFoundation\Request;

/**
 * The router inferface has to been implemented in your router
 */

interface RouterInterface
{
	public function setRequest(Request $request);
}
