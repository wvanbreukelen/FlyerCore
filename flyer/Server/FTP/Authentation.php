<?php

namespace Flyer\Components\Server\FTP;

class Authentation
{
	/**
	 * The user to authentate
	 * @var string
	 */
	protected $user;

	/**
	 * The password for the user to authentate
	 * @var string
	 */
	protected $password;

	/**
	 * Sets the user to authentate
	 * @param string $user The user
	 */
	public function setUser($user)
	{
		$this->user = $user;

		return $this;
	}

	/**
	 * Get the authentation user
	 * @return string The user
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * Set the password for the user to authentate
	 * @param string $password The password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
		
		return $this;
	}

	/**
	 * Get the password
	 * @return string The password
	 */
	public function getPassword()
	{
		return $this->password;
	}
}