<?php

namespace Flyer\Foundation\Events;

class Events
{

	protected static $events = array();

	public static function create($event)
	{
		if (is_object($event))
		{
			self::$events[$event->getTitle] = $event->getEvent;
		} else if (is_array($event)) {
			self::$events[$event['title']] = $event['event'];
		} else {
			throw new Exception("Events: Cannot create new event, because the event is not an object or array.");
		}
	}

	public static function trigger($eventTitle)
	{
		if (self::exists($eventTitle))
		{
			$event = self::$events[$eventTitle];

			if (is_object($event) && $event instanceof Closure)
			{
				return $event();
			} else {
				if (isset(self::$events[$eventTitle]))
				{
					$event = self::$events[$eventTitle];

					if (is_object($event))
					{
						return $event();
					} else {
						return $event();
					}
				} else {
					throw new Exception("Events: Failed to find event, named '" . $eventTitle . "'!");

					return false;
				}
			}
		}
	}
	
	public static function exists($eventTitle)
	{
		if (isset(self::$events[$eventTitle]))
		{
			return true;
		} else {
			return false;
		}
	}

	public static function getEvents()
	{
		return self::$events;
	}
}