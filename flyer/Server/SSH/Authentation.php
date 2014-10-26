<?php

namespace Flyer\Components\Server\SSH;

/**
 * Used for authentation for the SSH client
 */
class Authentation
{
	protected $user;

	protected $password;

	/**
	 * Set the user
	 * @param string $user The user
	 */
	public function setUser($user)
	{
		$this->user = $user;

		return $this;
	}

	/**
	 * Get the user
	 * @return string The user
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * Set the user password
	 * @param string $password The password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
		
		return $this;
	}

	/**
	 * Get the user password
	 * @return string The password
	 */
	public function getPassword()
	{
		return $this->password;
	}
}