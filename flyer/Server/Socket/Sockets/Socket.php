<?php

namespace Flyer\Components\Server\Socket;

use Exception;

abstract class Socket
{
	public function onOpen();
	public function onMessage();
	public function onClose();
	public function onError();
}