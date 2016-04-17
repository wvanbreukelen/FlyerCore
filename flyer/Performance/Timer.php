<?php

namespace Flyer\Components\Performance;

use Symfony\Component\Stopwatch\Stopwatch as SymfonyStopwatch;
use Exception;

class Timer extends SymfonyStopwatch
{

	protected $stopwatch;

	public function anomynous($function)
	{
		$this->start('_anomynous');
		$result = call_user_func($function);
		$this->stop('_anomynous');

		return ['elapsed' => $this->getElapsedTime('_anomynous'), 'mem' => $this->getElapsedTime('_anomynous'), 'result' => $result];
	}

	public function method($instance, $method)
	{
		if (method_exists($instance, '__construct'))
		{
			$this->start('_method');
			$result = $instance->$method();
			$this->stop('_method');

			return ['elapsed' => $this->getElapsedTime('_anomynous'), 'mem' => $this->getElapsedTime('_anomynous'), 'result' => $result];

		}
	}

	public function getElapsedTime($event)
	{
		if (is_string($event)) {
			$event = $this->getEvent($event);
		}

		return ($event->getEndTime()) - ($event->getStartTime());
	}

	public function getMemUsage($event)
	{
		if (is_string($event)) {
			$event = $this->getEvent($event);
		}

		return $event->getMemoryUsage();
	}

	public function getStopwatch()
	{
		return $this->stopwatch;
	}
}
