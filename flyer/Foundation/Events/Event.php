<?php

namespace Flyer\Foundation\Events;

class Event
{

	protected $title;

	protected $event;

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function setEvent($event)
	{
		$this->event = $event;
	}

	public function getTitle($title)
	{
		return $this->title;
	}

	public function getEvent($event)
	{
		return $this->event;
	}
}