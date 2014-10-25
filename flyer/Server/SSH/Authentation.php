<?php

namespace Flyer\Components\Server\SSH;

class Authentation
{
	protected $user;

	protected $password;

	public function setUser($user)
	{
		$this->user = $user;

		return $this;
	}

	public function getUser()
	{
		return $this->user;
	}

	public function setPassword($password)
	{
		$this->password = $password;
		
		return $this;
	}

	public function getPassword()
	{
		return $this->password;
	}
}