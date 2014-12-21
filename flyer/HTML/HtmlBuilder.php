<?php

namespace Flyer\Components\Html;

use Symfony\Request;

class HtmlBuilder
{

	protected $baseUrl;

	public function __construct($baseUrl = null)
	{
		$this->setBaseUrl($baseUrl);
	}

	public function entities($value)
	{
		return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
	}

	public function decode($value)
	{
		return html_entity_decode($value, ENT_QUOTES, 'UTF-8');
	}

	public function script($url, $attributes = array(), $secure = null)
	{
		$attributes['src'] = 
	}

	public function setBaseUrl($baseUrl)
	{
		$this->baseUrl = $baseUrl;
	}

	public function getBaseUrl()
	{
		if (is_null($this->baseUrl))
		{
			return null;
		}

		return $this->baseUrl;
	}
}