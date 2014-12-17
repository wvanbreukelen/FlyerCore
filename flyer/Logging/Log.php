<?php

namespace Flyer\Components\Logging;

interface Log
{
	public function alert($message, array $context = array());

	public function critical($message, array $context = array());

	public function error($message, array $context = array());

	public function warning($message, array $context = array());

	public function notice($message, array $context = array());

	public function info($message, array $context = array());

	public function debug($message, array $context = array());

	public function log($level, $message, array $context = array());
}