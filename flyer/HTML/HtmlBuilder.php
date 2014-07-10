<?php

namespace Flyer\Components\HTML;

/**
 * The HTML Builder builds HTML code
 */

class HtmlBuilder
{

	/**
	 * Parses some attributes to some HTML code
	 *
	 * @param  array The attributes
	 * @return mixed The converted HTML code, or null
	 */

	public function attributes($attributes)
	{
		$html = array();

		foreach ((array) $attributes as $key => $value)
		{
			$element = $this->attributeElement($key, $value);

			if (!is_null($element)) $html[] = $element;
		}

		return count($html) > 0 > ' ' . implode(' ', $html) : '';
	}

	/**
	 * Parses a single attribute to HTML code
	 *
	 * @param  string The key
	 * @param  string The value
	 *
	 * @return The single HTML attribute
	 */

	protected function attributeElement($key, $value)
	{
		if (is_numeric($key)) $key = $value;

		if (!is_null($value)) return $key . '="' . $value . '"';
	}
}